<?php

namespace App\Http\Controllers\Account\Chat;

use App\Http\Controllers\Account\Controller;
use App\Models\Order;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ConversationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private ChatService $chatService)
    {
    }

    public function show(Order $order)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $chat = $this->chatService->getCustomerOrderChat($order, $customer->id);

            return $this->setContent('account.chat.show', compact('order', 'chat'));
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $this->mapChatError($e));
        }
    }

    public function store(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            $customer = Auth::guard('customer')->user();
            $chat = $this->chatService->getCustomerOrderChat($order, $customer->id);
            $this->chatService->addMessage($chat, 'customer', $customer->id, $request->input('message'));

            return Redirect::back()->with('success', 'Mensagem enviada.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $this->mapChatError($e));
        }
    }

    /**
     * Translate service error codes into user messages.
     *
     * @param \Exception $exception
     * @return string
     */
    private function mapChatError(\Exception $exception)
    {
        switch ($exception->getMessage()) {
            case 'unauthorized':
                return 'Acesso não autorizado.';
            case 'not_accepted':
                return 'Pedido não está aceite.';
            default:
                return $exception->getMessage();
        }
    }
}
