<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*=================================================================================================================
 * AUTH ROUTES
 =================================================================================================================*/

Route::prefix('admin/login')
    ->middleware('guest.admin')
    ->controller(Admin\Auth\LoginController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('admin.login');

        Route::post('/', 'login')
            ->name('admin.login.submit');
    });

Route::prefix('admin/register')
    ->middleware('guest.admin')
    ->controller(Admin\Auth\RegisterController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('admin.register');

        Route::post('/', 'store')
            ->name('admin.register.store');
    });

//RESET PASSWORD
Route::prefix('admin/password')->middleware('guest.admin')->group(function () {

    Route::get('forgot', [Admin\Auth\ForgotPasswordController::class, 'index'])
        ->name('admin.password.forgot');

    Route::post('email', [Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.forgot.email');

    Route::get('reset/{token}', [Admin\Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('admin.password.reset');

    Route::post('reset', [Admin\Auth\ResetPasswordController::class, 'reset'])
        ->name('admin.password.reset.submit');
});

//LOGOUT
Route::get('admin/logout', [Admin\Auth\LoginController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth.admin');

/**
 *
 * Admin application routes
 *
 */
Route::prefix('admin')->middleware(['auth.admin', 'role:administrator'])->group(function () {

    Route::get('/', [Admin\HomeController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('password', [Admin\AccountController::class, 'password'])
        ->name('admin.account.password');

    Route::get('users/{user}/remote-logout', [Admin\HomeController::class, 'remoteLogout'])
        ->name('admin.users.remote-logout');

    /*=================================================================================================================
    * USERS
    * =================================================================================================================*/
    Route::post('users/datatable', [Admin\Users\UsersController::class, 'datatable'])
        ->name('admin.users.datatable');

    Route::post('users/selected/destroy', [Admin\Users\UsersController::class, 'massDestroy'])
        ->name('admin.users.selected.destroy');

    Route::post('users/{user}/remote-login', [Admin\Users\UsersController::class, 'remoteLogin'])
        ->name('admin.users.remote-login');

    Route::put('users/store/login', [Admin\Users\UsersController::class, 'storeLogin'])
        ->name('admin.users.store.login');

    Route::put('users/{user}/update/login', [Admin\Users\UsersController::class, 'updateLogin'])
        ->name('admin.users.update.login');

    Route::put('users/{user}/status/{status}', [Admin\Users\UsersController::class, 'updateStatus'])
        ->name('admin.users.updateStatus');

    Route::resource('users', Admin\Users\UsersController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * FILE REPOSITORY
    * =================================================================================================================*/
    Route::post('files/datatable', [Admin\FileRepositoryController::class, 'datatable'])
        ->name('admin.files.datatable');

    Route::get('files/download', [Admin\FileRepositoryController::class, 'massDownload'])
        ->name('admin.attachments.massDownload');
    
    Route::delete('files/', [Admin\FileRepositoryController::class, 'massDestroy'])
        ->name('admin.attachments.massDestroy');

    Route::post('files/{target}/{id}/upload', [Admin\FileRepositoryController::class, 'upload'])
        ->name('admin.files.upload');

    Route::get('files/{file}', [Admin\FileRepositoryController::class, 'open'])
        ->name('admin.files.open');

    Route::get('{target}/{id}/files/create', [Admin\FileRepositoryController::class, 'create'])
        ->name('admin.attachments.create');

    Route::post('{target}/{id}/files/store', [Admin\FileRepositoryController::class, 'store'])
        ->name('admin.attachments.store');

    Route::get('files/{file}/edit/', [Admin\FileRepositoryController::class, 'edit'])
        ->name('admin.attachments.edit');

    Route::put('files/{file}', [Admin\FileRepositoryController::class, 'update'])
        ->name('admin.attachments.update');

    Route::delete('files/{file}', [Admin\FileRepositoryController::class, 'destroy'])
        ->name('admin.attachments.destroy');

    Route::get('files/{file}/download', [Admin\FileRepositoryController::class, 'download'])
        ->name('admin.attachments.download');

    /*=================================================================================================================
    * CUSTOMERS
    * =================================================================================================================*/
    Route::post('customers/datatable', [Admin\Customers\CustomersController::class, 'datatable'])
        ->name('admin.customers.datatable');

    Route::post('customers/selected/destroy', [Admin\Customers\CustomersController::class, 'massDestroy'])
        ->name('admin.customers.selected.destroy');

    Route::post('customers/{customer}/remote-login', [Admin\Customers\CustomersController::class, 'remoteLogin'])
        ->name('admin.customers.remote-login');

    Route::resource('customers', Admin\Customers\CustomersController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * SERVICES
    * =================================================================================================================*/
    Route::post('services/datatable', [Admin\Services\ServicesController::class, 'datatable'])
        ->name('admin.services.datatable');

    Route::post('services/selected/destroy', [Admin\Services\ServicesController::class, 'massDestroy'])
        ->name('admin.services.selected.destroy');

    Route::put('services/{service}/status/{status}', [Admin\Services\ServicesController::class, 'updateStatus'])
        ->name('admin.services.updateStatus');

    Route::resource('services', Admin\Services\ServicesController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * COMMENTS 
    * =================================================================================================================*/
    Route::post('comments/datatable', [Admin\Comments\CommentsController::class, 'datatable'])
        ->name('admin.comments.datatable');

    Route::post('comments/selected/destroy', [Admin\Comments\CommentsController::class, 'massDestroy'])
        ->name('admin.comments.selected.destroy');

    Route::resource('comments', Admin\Comments\CommentsController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * CHATS
    * =================================================================================================================*/
    Route::post('chats/datatable', [Admin\Chats\ChatsController::class, 'datatable'])
        ->name('admin.chats.datatable');

    Route::post('chats/selected/destroy', [Admin\Chats\ChatsController::class, 'massDestroy'])
        ->name('admin.chats.selected.destroy');

    Route::resource('chats', Admin\Chats\ChatsController::class, [
        'as' => 'admin',
        'only' => ['index', 'destroy']
    ]);

    /*=================================================================================================================
    * PEDIDOS
    * =================================================================================================================*/
    Route::post('orders/datatable', [Admin\Orders\OrdersController::class, 'datatable'])
        ->name('admin.orders.datatable');

    Route::post('orders/selected/destroy', [Admin\Orders\OrdersController::class, 'massDestroy'])
        ->name('admin.orders.selected.destroy');

    Route::put('orders/{order}/status/{status}', [Admin\Orders\OrdersController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');

    Route::resource('orders', Admin\Orders\OrdersController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * PRESTADOR
    * =================================================================================================================*/
    Route::get('prestador/pedidos', [Admin\Orders\OrdersController::class, 'prestadorPedidos'])
        ->name('admin.prestador.pedidos');

    /*=================================================================================================================
    * PAYMENTS
    * =================================================================================================================*/
    Route::post('payments/datatable', [Admin\Payments\PaymentsController::class, 'datatable'])
        ->name('admin.payments.datatable');

    Route::post('payments/selected/destroy', [Admin\Payments\PaymentsController::class, 'massDestroy'])
        ->name('admin.payments.selected.destroy');

    Route::put('payments/{payment}/status/{status}', [Admin\Payments\PaymentsController::class, 'updateStatus'])
        ->name('admin.payments.updateStatus');

    Route::get('payments/get/order-data/{order}', [Admin\Payments\PaymentsController::class, 'getOrderData'])
        ->name('admin.payments.get.order.data');

    Route::resource('payments', Admin\Payments\PaymentsController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * COMISSIONS
    * =================================================================================================================*/
    Route::post('comissions/datatable', [Admin\Comissions\ComissionsController::class, 'datatable'])
        ->name('admin.comissions.datatable');

    Route::post('comissions/selected/destroy', [Admin\Comissions\ComissionsController::class, 'massDestroy'])
        ->name('admin.comissions.selected.destroy');

    Route::get('comissions/get/payment-data/{payment}', [Admin\Comissions\ComissionsController::class, 'getPaymentData'])
        ->name('admin.comissions.get.payment.data');

    Route::resource('comissions', Admin\Comissions\ComissionsController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * CATEGORYS
    * =================================================================================================================*/
    Route::post('categorys/datatable', [Admin\Categorys\CategorysController::class, 'datatable'])
        ->name('admin.categorys.datatable');

    Route::post('categorys/selected/destroy', [Admin\Categorys\CategorysController::class, 'massDestroy'])
        ->name('admin.categorys.selected.destroy');

    Route::resource('categorys', Admin\Categorys\CategorysController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * MANAGE LOGS
    * =================================================================================================================*/
    Route::get('logs/errors/{filename}/download', [Admin\LogViewerController::class, 'download'])
        ->name('admin.logs.errors.download');

    Route::post('logs/errors/destroy/all', [Admin\LogViewerController::class, 'destroyAll'])
        ->name('admin.logs.errors.destroy.all');

    /*=================================================================================================================
    * NOTIFICATIONS
    * =================================================================================================================*/
    Route::post('notifications/datatable', [Admin\Notifications\NotificationsController::class, 'datatable'])
        ->name('admin.notifications.datatable');
    Route::post('notifications/mass-destroy', [Admin\Notifications\NotificationsController::class, 'massDestroy'])
        ->name('admin.notifications.mass-destroy');
    Route::get('notifications/api/recent', [Admin\Notifications\NotificationsController::class, 'getRecent'])
        ->name('admin.notifications.api.recent');
    Route::post('notifications/api/mark-read', [Admin\Notifications\NotificationsController::class, 'markAsRead'])
        ->name('admin.notifications.api.mark-read');
    Route::get('notifications/api/type/{type}', [Admin\Notifications\NotificationsController::class, 'getByType'])
        ->name('admin.notifications.api.by-type');
    Route::get('notifications', [Admin\Notifications\NotificationsController::class, 'index'])
        ->name('admin.notifications.index');
    Route::get('notifications/create', [Admin\Notifications\NotificationsController::class, 'create'])
        ->name('admin.notifications.create');
    Route::post('notifications', [Admin\Notifications\NotificationsController::class, 'store'])
        ->name('admin.notifications.store');
    Route::get('notifications/{notification}', [Admin\Notifications\NotificationsController::class, 'show'])
        ->name('admin.notifications.show');
    Route::get('notifications/{notification}/edit', [Admin\Notifications\NotificationsController::class, 'edit'])
        ->name('admin.notifications.edit');
    Route::put('notifications/{notification}', [Admin\Notifications\NotificationsController::class, 'update'])
        ->name('admin.notifications.update');
    Route::delete('notifications/{notification}', [Admin\Notifications\NotificationsController::class, 'destroy'])
        ->name('admin.notifications.destroy');

    /*=================================================================================================================
    * LOGS
    * =================================================================================================================*/
    Route::resource('logs/errors', Admin\LogViewerController::class, [
        'as'    => 'admin.logs',
        'only'  => ['index', 'destroy']
    ]);

    /*=================================================================================================================
    * SETTINGS
    * =================================================================================================================*/
    Route::resource('settings', Admin\SettingsController::class, [
        'as' => 'admin',
        'only' => ['index', 'store']
    ]);

    /*=================================================================================================================
    * CHANGE LOG
    * =================================================================================================================*/
    Route::get('change-log/{source}/{sourceId}', [Admin\ChangesLogController::class, 'show'])
        ->name('admin.change-log.show');

    Route::get('login-log/{target}/{sourceId}', [Admin\ChangesLogController::class, 'showLogin'])
        ->name('admin.login-log.show');

    /*=================================================================================================================
    * TEST CONTROLLER
    * =================================================================================================================*/
    Route::get('test', [Admin\TestController::class, 'index'])
        ->name('admin.test');

    /*=================================================================================================================
    * DEBUG ROUTE
    * =================================================================================================================*/
    Route::get('debug', function () {
        $user = auth('admin')->user();
        if (!$user) {
            return "Não autenticado";
        }
        
        $roles = $user->roles()->pluck('name')->toArray();
        $hasRole = method_exists($user, 'hasRole');
        
        return "User: {$user->name}<br>
                Email: {$user->email}<br>
                Roles: " . json_encode($roles) . "<br>
                hasRole method exists: " . ($hasRole ? "Sim" : "Não") . "<br>
                hasRole('prestador'): " . ($hasRole && $user->hasRole('prestador') ? "Sim ✓" : "Não ✗");
    })->name('admin.debug');
});
