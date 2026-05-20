<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session, Auth;
use Carbon\Carbon;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prazo = Carbon::now()->subDay();
        $usersLast24hours = User::ignoreAdmins()->where('created_at', '>' , $prazo)->count();
        
        
        $totalUsers = User::ignoreAdmins()->count();
        $totalCustomers = Customer::count();
        $totalUsers = $totalUsers + $totalCustomers;
        
        
        $activeServices = Service::where('status', 'active')->count();
        
        
        $totalOrders = Order::count();
        
        
        $ordersPerMonth = Order::whereIn('status', ['accepted', 'completed'])
            ->orderBy('date')
            ->get()
            ->groupBy(function($order) {
                return $order->date->format('Y-m');
            })
            ->map(function($group) {
                $first = $group->first();
                return (object) [
                    'mes' => (int) $first->date->month,
                    'ano' => (int) $first->date->year,
                    'total' => (int) $group->count()
                ];
            })
            ->values();

        $data = compact(
            'usersLast24hours',
            'totalUsers',
            'activeServices',
            'totalOrders',
            'ordersPerMonth'
        );

        return $this->setContent('admin.dashboard.index', $data);
    }

    /**
     * Logout a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remoteLogout(Request $request, User $user)
    {

        if (Session::has('source_user_id')) {
            $userSource = User::findOrFail(Session::get('source_user_id'));

            Session::forget('source_user_id');

            Auth::login($userSource);

            return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');
        }

        return Redirect::back()->with('error', 'Nenhuma sessão remota iniciada.');
    }
}
