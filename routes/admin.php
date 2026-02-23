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
Route::prefix('admin')->middleware('auth.admin')->group(function () {

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

    Route::resource('users', Admin\Users\UsersController::class, [
        'as' => 'admin',
        'except' => ['show']
    ]);

    /*=================================================================================================================
    * ROLES & PERMISSIONS
    * =================================================================================================================*/
    Route::resource('roles', Admin\RolesController::class, [
        'as' => 'admin',
        'except' => ['create', 'edit']
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

    Route::put('customers/{customer}/update/login', [Admin\Customers\CustomersController::class, 'updateLogin'])
        ->name('admin.customers.login');

    Route::post('customers/{customer}/remote-login', [Admin\Customers\CustomersController::class, 'remoteLogin'])
        ->name('admin.customers.remote-login');

    Route::post('customers/{id}/inactivate', [Admin\Customers\CustomersController::class, 'inactivate'])
        ->name('admin.customers.inactivate');

    Route::post('customers/{id}/activate', [Admin\Customers\CustomersController::class, 'activate'])
        ->name('admin.customers.activate');

    Route::post('customers/get/counties', [Admin\Customers\CustomersController::class, 'getCounties'])
        ->name('admin.customers.get.counties');

    Route::get('customers/{customer}/license/notify/preview', [Admin\Customers\CustomersController::class, 'notifyPreview'])
        ->name('admin.customers.license.notify.preview');

    Route::post('customers/{customer}/license/notify', [Admin\Customers\CustomersController::class, 'notifyPlatform'])
        ->name('admin.customers.license.notify');

    Route::post('customers/{customer}/balance/datatable', [Admin\Customers\CustomersController::class, 'datatableBalance'])
        ->name('admin.customers.balance.datatable');

    Route::post('customers/{customer}/balance/reset', [Admin\Customers\CustomersController::class, 'resetBalance'])
        ->name('admin.customers.balance.reset');

    Route::post('customers/search', [Admin\Customers\CustomersController::class, 'search'])
        ->name('admin.customers.search');

    Route::resource('customers', Admin\Customers\CustomersController::class, [
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
});
