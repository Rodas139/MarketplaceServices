<?php

namespace App\Services;

use App\Events\NotificationCreated;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Criar e enviar notificação global
     */
    public function create($title, $message, $type = 'info', $senderID = null, $sourceClass = null, $sourceID = null, $params = null)
    {
        // Se não especificar sender, usa o utilizador autenticado
        if (!$senderID && Auth::check()) {
            $senderID = Auth::id();
        }

        $notification = Notification::create([
            'sender_id' => $senderID,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'source_class' => $sourceClass,
            'source_id' => $sourceID,
            'params' => $params,
            'alert_at' => now()
        ]);

        // Broadcast via Pusher
        event(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Obter notificações recentes
     */
    public function getRecent($limit = 10)
    {
        return Notification::recent()
            ->limit($limit)
            ->get();
    }

    /**
     * Obter notificações por tipo
     */
    public function getByType($type, $limit = 10)
    {
        return Notification::byType($type)
            ->recent()
            ->limit($limit)
            ->get();
    }

    /**
     * Obter todas as notificações com paginação
     */
    public function paginate($perPage = 15)
    {
        return Notification::recent()
            ->paginate($perPage);
    }

    /**
     * Deletar notificação
     */
    public function delete($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->delete();
            return true;
        }

        return false;
    }

    /**
     * Soft delete em batch
     */
    public function deleteBatch($ids)
    {
        return Notification::whereIn('id', $ids)->delete();
    }

    /**
     * Forçar delete permanente
     */
    public function forceDelete($id)
    {
        $notification = Notification::withTrashed()->find($id);
        
        if ($notification) {
            $notification->forceDelete();
            return true;
        }

        return false;
    }

    /**
     * Obter notificacoes recentes para cliente (maximo 7 dias).
     *
     * @param int $customerId
     * @return \Illuminate\Support\Collection
     */
    public function getRecentForCustomer($customerId)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $notifications = Notification::recent()
            ->where('alert_at', '>=', $sevenDaysAgo)
            ->limit(10)
            ->get();

        return $notifications->map(function ($notification) use ($customerId) {
            $readBy = $notification->read_by_customer ?? [];
            $notification->is_read_by_user = in_array($customerId, $readBy);
            $notification->alert_at_formatted = $notification->alert_at->format('d/m/Y H:i');
            return $notification;
        });
    }

    /**
     * Marcar notificacoes recentes como lidas para o cliente.
     *
     * @param int $customerId
     * @return void
     */
    public function markRecentAsReadForCustomer($customerId)
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $notifications = Notification::recent()
            ->where('alert_at', '>=', $sevenDaysAgo)
            ->get();

        foreach ($notifications as $notification) {
            $readBy = $notification->read_by_customer ?? [];

            if (!in_array($customerId, $readBy)) {
                $readBy[] = $customerId;
                $notification->update(['read_by_customer' => $readBy]);
            }
        }
    }
}
