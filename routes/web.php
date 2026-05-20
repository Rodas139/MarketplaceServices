<?php

// require base_path() . '/routes/website.php';
require base_path() . '/routes/admin.php';
require base_path() . '/routes/account.php';
require base_path() . '/routes/provider.php';

// require base_path() . '/routes/modules/tickets.php';
Route::get('/start', function () {
    \Artisan::call('migrate:fresh');
    echo "Não esquecer de remover esta rota";
});

Route::get('order/{filename}', function ($filename) {
    return App::call('App\Http\Controllers\Admin\Orders\OrdersController@access', ['filename' => $filename]);
})->name('orderMockup.file');


Route::get('chatMessage/{filename}', function ($filename) {
    return App::call('App\Http\Controllers\Admin\Chat\ChatController@fileAccess', ['filename' => $filename]);
})->name('chatMessage.file');
