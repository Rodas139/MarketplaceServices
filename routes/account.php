<?php

namespace App\Http\Controllers;

// use Google\Service\AdExchangeBuyer\Account;
use Illuminate\Support\Facades\Route;
use Redirect;

/*
 * Customer Login
 */

Route::prefix('area-cliente/login')->middleware('guest')->group(function () {
    Route::get('/', [Auth\LoginController::class, 'index'])
        ->name('account.login');

    Route::post('/', [Auth\LoginController::class, 'login'])
        ->name('account.login.submit');
});

/**
 *
 * Customer Logout
 *
 */
//test commit
Route::get('account/logout', [Auth\LoginController::class, 'logout'])->middleware('auth')
    ->name('account.logout');

/*
 * Customer forgot and reset password
 */
Route::prefix('area-cliente/password')->middleware('guest')->group(function () {
    Route::get('/forgot', [Auth\ForgotPasswordController::class, 'index'])
        ->name('account.password.forgot.index');

    Route::post('/email', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('account.password.email');

    Route::get('reset/{token}', [Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('account.password.reset');

    Route::post('reset', [Auth\ResetPasswordController::class, 'reset'])
        ->name('account.password.reset.submit');
});

/**
 * Customer Account
 */
Route::prefix('area-cliente')->middleware('auth')->group(function () {
    Route::get('/', [Account\HomeController::class, 'index'])
        ->name('account.index');



    Route::prefix('orders')->group(function () {
        Route::get('/', [Account\Orders\OrdersController::class, 'index'])
            ->name('account.orders.index');

        Route::post('/datatable', [Account\Orders\OrdersController::class, 'datatable'])
            ->name('account.orders.datatable');

        Route::post('/{order}/approve', [Account\Orders\OrdersController::class, 'approve'])
            ->name('account.orders.approve');

        Route::prefix('{order}')->group(function () {
            Route::get('/', [Account\Orders\OrdersController::class, 'show'])
                ->name('account.orders.show');
        });

        Route::get('orders/{id}/status/edit', [account\Orders\OrdersController::class, 'statusEdit'])
            ->name('account.order.status.edit'); //acho que não está a ser usada

        Route::post('line/{id}/customer/approve', [Account\Orders\OrdersController::class, 'lineApprove'])
            ->name('account.orders.line.customer.approve');

        Route::post('line/{id}/customer/refuse', [Account\Orders\OrdersController::class, 'lineRefuse'])
            ->name('account.orders.line.customer.refuse');
            
        Route::get('line/customer/reject', [Account\Orders\OrdersController::class, 'lineReject'])
            ->name('account.orders.line.customer.reject');
    });

    Route::get('chat/create/{order}', [Account\Chat\ChatController::class, 'create'])
        ->name('account.chat.create');

    Route::post('chat/store/{order}', [Account\Chat\ChatController::class, 'store'])
        ->name('account.chat.store');

    Route::get('chat/message/create/{order}', [Account\Chat\ChatController::class, 'createMessage'])
        ->name('account.chat.message.create');

    Route::post('chat/message/{chat}/save', [Account\Chat\ChatController::class, 'saveMessage'])
        ->name('account.chat.message.save');

    Route::post('chat/{chat}/close', [Account\Chat\ChatController::class, 'closeChat'])
        ->name('account.chat.close');

    Route::get('account/change/password', [Account\HomeController::class, 'changePassword'])
        ->name('account.change.password');

    Route::post('account/update/password', [Account\HomeController::class, 'updatePassword'])
        ->name('account.update.password');

    Route::get('account/update/language/{lang}', [Account\HomeController::class, 'updateLanguage'])
        ->name('account.update.language');
});


Route::any('/', function () {
    return Redirect::route('account.login');
});
