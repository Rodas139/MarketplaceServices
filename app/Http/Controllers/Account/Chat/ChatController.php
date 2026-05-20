<?php

namespace App\Http\Controllers\Account\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Order;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;


class ChatController extends \App\Http\Controllers\Account\Controller
{
    protected $menuOption = 'orders';


    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $order)
    {
        $chat = new Chat();

        $order = Order::find($order);

        $chat->subject                       = 'APROVAÇÃO IMAGEM';
        $chat->customer_id                   = $order->customer_id;
        $chat->order_id                      = $order->id;
        $chat->started_in                    =  Carbon::now()->format('Y-m-d H:i:s');
        $chat->user_id                       = Auth::guard('customer')->user()->id;
        $chat->admin_last_time_open          = Carbon::now()->format('Y-m-d H:i:s');
        $chat->save();

        $request = new Request();
        return $this->createMessage($request, $order->id);
    }

    /**
     * Store news values
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order)
    {
        $chat = new Chat();
        $request = $request->all();

        $order = Order::find($order);

        $chat->subject                       = $request['subject'];
        $chat->customer_id                   = $order->customer_id;
        $chat->order_id                      = $order->id;
        $chat->started_in                    =  Carbon::now()->format('Y-m-d H:i:s');
        $chat->user_id                       = Auth::guard('customer')->user()->id;
        $chat->admin_last_time_open          = Carbon::now()->format('Y-m-d H:i:s');
        $chat->save();

        return Redirect::back()->with('success', 'Chat criado com sucesso.');
    }

    /**
     * Edit a order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createMessage(Request $request, $order)
    {
        $order = Order::with('chat')->where('id', $order)->first();

        $chat  = Chat::find($order->chat->id);
        $chat->customer_last_time_open = Carbon::now()->format('Y-m-d H:i:s');
        $chat->save();

        $action = trans('account/orders.subject') . ' ' . $order->chat->subject;
        $formOptions = array(
            'route' => array('account.chat.message.save', $order->chat->id),
            'method' => 'POST', 'class' => 'ajax-form', 'enctype' => 'multipart/form-data', 'id' =>  'messageForm'
        );

        $data = compact(
            'action',
            'formOptions',
            'order'
        );

        return view('account.chat.messages.messages', $data)->render();
    }
    

    public function saveMessage(Request $request, $chat)
    {
        $file = $request->file('file');
        $request = $request->all();
        $chatMessage = new ChatMessage();

        if (!empty($file)) {
            $fileName               = $file->getClientOriginalName();
            $filePublic =  Str::random(32) . str_replace(' ', '', $fileName);

            $fileNameStoraged = $filePublic;

            $pathLocal = storage_path('app/chat/');
            $file->move($pathLocal, $fileNameStoraged);

            $chatMessage->file_path               = $pathLocal . $fileNameStoraged;
            $chatMessage->file_name               = $fileName;
            $chatMessage->file_name_storaged      = $fileNameStoraged;
            $chatMessage->file_public_path        = $filePublic;

            $chatMessage->save();
        }

        $chatMessage->chat_id = $chat;
        $chatMessage->message_customer = 1;
        $chatMessage->date = Carbon::now()->format('Y-m-d H:i:s');
        $chatMessage->message = $request['message'];
        $chatMessage->save();

        $chat   = Chat::find($chat);
        $customer = Customer::where('id', $chat->customer_id)->first();
        $emails = 'portal@projetoBase.pt';
        $customerName = $customer->name;

        $order = Order::find($chat->order_id);

        $emailResponse =  ChatMessage::sendEmail('customer', $emails, $order, $customerName);

        $emailFeedback = 'Email enviado com sucesso.';

        if ($emailResponse == 0) {
            $emailFeedback = 'Erro ao enviar email.';
        }

        $message = $chatMessage->message;
        if (!empty($chatMessage->file_public_path)) {
            $message = $chatMessage->message . '<br> <a target="_blank" href="' . route('chatMessage.file', $chatMessage->file_public_path) . '">' . $chatMessage->file_name . '</a>';
        }

        $response = [
            'result'        => true,
            'message'       => $message,
            'date'          => $chatMessage->date,
            'emailFeedback' => $emailFeedback,
            'emailResponse' => $emailResponse
        ];

        return $response;
    }
}
