<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class ChatService
{
    /**
     * Get or create chat for provider order flow.
     *
     * @param Order $order
     * @param int $providerId
     * @return Chat
     */
    public function getProviderOrderChat(Order $order, int $providerId)
    {
        if ($order->user_id !== $providerId) {
            throw new Exception('unauthorized');
        }

        $this->ensureOrderAccepted($order);

        return $this->getOrCreateOrderChat($order, $order->id_customer, $order->user_id);
    }

    /**
     * Get or create chat for customer order flow.
     *
     * @param Order $order
     * @param int $customerId
     * @return Chat
     */
    public function getCustomerOrderChat(Order $order, int $customerId)
    {
        if ($order->id_customer !== $customerId) {
            throw new Exception('unauthorized');
        }

        $this->ensureOrderAccepted($order);

        return $this->getOrCreateOrderChat($order, $customerId, $order->user_id);
    }

    /**
     * Create a chat message.
     *
     * @param Chat $chat
     * @param string $senderType
     * @param int $senderId
     * @param string $message
     * @return ChatMessage
     */
    public function addMessage(Chat $chat, string $senderType, int $senderId, string $message)
    {
        return ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $message
        ]);
    }

    /**
     * Ensure order is accepted.
     *
     * @param Order $order
     * @return void
     */
    private function ensureOrderAccepted(Order $order)
    {
        if ($order->status !== 'accepted') {
            throw new Exception('not_accepted');
        }
    }

    /**
     * Create or fetch chat for an order.
     *
     * @param Order $order
     * @param int $customerId
     * @param int $providerId
     * @return Chat
     */
    private function getOrCreateOrderChat(Order $order, int $customerId, int $providerId)
    {
        $chat = Chat::where('order_id', $order->id)->first();
        if (!$chat) {
            $chat = Chat::create([
                'customer_id' => $customerId,
                'provider_id' => $providerId,
                'order_id' => $order->id,
                'subject' => 'Chat Pedido #' . $order->id
            ]);
        }

        $chat->load('messages');

        return $chat;
    }

    /**
     * Get admin chat datatable query with filters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAdminDatatableQuery(Request $request)
    {
        $data = Chat::with(['customer', 'provider', 'order']);

        if ($request->has('customer_id') && $request->get('customer_id')) {
            $data->where('customer_id', $request->get('customer_id'));
        }

        if ($request->has('provider_id') && $request->get('provider_id')) {
            $data->where('provider_id', $request->get('provider_id'));
        }

        return $data;
    }

    /**
     * Delete a chat and its messages.
     *
     * @param Chat $chat
     * @return void
     */
    public function deleteAdminChat(Chat $chat)
    {
        ChatMessage::where('chat_id', $chat->id)->delete();
        $chat->delete();
    }

    /**
     * Delete multiple chats and their messages.
     *
     * @param array $ids
     * @return void
     */
    public function deleteAdminChats(array $ids)
    {
        $ids = array_filter($ids);
        if (empty($ids)) {
            return;
        }

        ChatMessage::whereIn('chat_id', $ids)->delete();
        Chat::whereIn('id', $ids)->delete();
    }
}
