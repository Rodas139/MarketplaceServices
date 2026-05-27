<?php

use Illuminate\Support\Facades\Route;

Route::prefix('prestador')->middleware(['auth.admin'])->group(function () {
    // Notifications 
    Route::get('notificacoes/api/recentes', [App\Http\Controllers\Admin\Notifications\NotificationsController::class, 'getRecent'])
        ->name('provider.notifications.api.recent');
    Route::post('notificacoes/api/marcar-lidas', [App\Http\Controllers\Admin\Notifications\NotificationsController::class, 'markAsRead'])
        ->name('provider.notifications.api.mark-read');

    // Orders
    Route::get('pedidos', [App\Http\Controllers\Provider\Orders\OrdersController::class, 'index'])
        ->name('provider.orders.index');
    
    Route::post('pedidos/datatable', [App\Http\Controllers\Provider\Orders\OrdersController::class, 'datatable'])
        ->name('provider.orders.datatable');

    Route::put('pedidos/{order}/status/{status}', [App\Http\Controllers\Provider\Orders\OrdersController::class, 'updateStatus'])
        ->name('provider.orders.updateStatus');

    Route::delete('pedidos/{order}', [App\Http\Controllers\Provider\Orders\OrdersController::class, 'destroy'])
        ->name('provider.orders.destroy');

    Route::get('pedidos/{order}', [App\Http\Controllers\Provider\Orders\OrdersController::class, 'show'])
        ->name('provider.orders.show');

    // Services
    Route::get('servicos', [App\Http\Controllers\Provider\Services\ServicesController::class, 'index'])
        ->name('provider.services.index');

    Route::get('servicos/disponíveis', [App\Http\Controllers\Provider\Services\ServicesController::class, 'available'])
        ->name('provider.services.available');

    Route::post('servicos/disponíveis/datatable', [App\Http\Controllers\Provider\Services\ServicesController::class, 'datatableAvailable'])
        ->name('provider.services.datatable.available');

    Route::post('servicos/{service}/mudar', [App\Http\Controllers\Provider\Services\ServicesController::class, 'changeService'])
        ->name('provider.services.change');

    Route::get('servicos/criar', [App\Http\Controllers\Provider\Services\ServicesController::class, 'create'])
        ->name('provider.services.create');

    Route::post('servicos', [App\Http\Controllers\Provider\Services\ServicesController::class, 'store'])
        ->name('provider.services.store');

    Route::get('chat/conversation/{order}', [App\Http\Controllers\Provider\Chat\ConversationController::class, 'show'])
        ->name('provider.chat.show');

    Route::post('chat/conversation/{order}', [App\Http\Controllers\Provider\Chat\ConversationController::class, 'store'])
        ->name('provider.chat.store');
});
