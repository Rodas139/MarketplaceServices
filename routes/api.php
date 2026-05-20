<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Comment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Get services by category
 */
Route::get('/services/by-category/{categoryId}', function ($categoryId) {
    $services = Service::where('category_id', $categoryId)
        ->where('status', 'active')
        ->select('id', 'name', 'price')
        ->get();
    
    return response()->json($services);
});

/**
 * Get providers by service
 */
Route::get('/providers/by-service/{serviceId}', function ($serviceId) {
    $providers = User::where('id_service', $serviceId)
        ->where('status', 'active')
        ->select('id', 'name', 'email')
        ->get();

    $providerIds = $providers->pluck('id');
    $ratings = Comment::join('orders', 'orders.id', '=', 'comments.id_order')
        ->whereIn('orders.user_id', $providerIds)
        ->whereNotNull('comments.rating')
        ->groupBy('orders.user_id')
        ->select(
            'orders.user_id',
            DB::raw('AVG(comments.rating) as avg_rating'),
            DB::raw('COUNT(comments.rating) as total_reviews')
        )
        ->get()
        ->keyBy('user_id');

    $providers = $providers->map(function ($provider) use ($ratings) {
        $rating = $ratings->get($provider->id);
        $provider->avg_rating = $rating ? round($rating->avg_rating, 1) : null;
        $provider->total_reviews = $rating ? (int) $rating->total_reviews : 0;
        return $provider;
    });
    
    return response()->json($providers);
});

