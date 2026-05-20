<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/**
 * Get current url language and set system language
 */

Route::post('/api/calls-logs', [ApiController::class, 'callsLogs'])
    ->name('api.calls-logs');



/**
 * Get current url language and set system language
 */
Route::get('/area-cliente', [HomeController::class, 'index'])
    ->name('account.index');


/**
 * Get current url language and set system language
 */
Route::get('', [HomeController::class, 'index']) //[HomeController::class, 'index']
    ->name('home');

Route::get('pc/irs', [IrsController::class, 'index'])
    ->name('irs.index');

Route::get('/pdg', array(
    'as'     => 'pdg.index',
    'before' => '',
    'uses'   => [BarcodesController::class, 'pdg']
));

/**
 * Calback pagamentos
 */
// Route::post('payments/callback/{gateway}/{type}', [PaymentsCallbackController::class, 'genericCallback'])
//     ->name('payment.callback');

// Route::get('payments/callback/{gateway}/{type}', [PaymentsCallbackController::class, 'genericCallback'])
//     ->name('payment.callback.visa');

/**
 * Test Controller
 */
// Route::resource('test', TestController::class, [
//     'as' => 'admin',
//     'only' => ['index']
// ]);



require base_path() . '/routes/api.php';
require base_path() . '/routes/admin.php';
