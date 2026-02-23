<?php

namespace App\Http\Controllers\Account\Orders;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Customer;
use Auth;
use Illuminate\Http\Request;
use Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Date, DB, Setting, Log, DateTime;

class OrdersController extends \App\Http\Controllers\Account\Controller
{
    protected $menuOption = 'orders';

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $status = OrderStatus::remember(config('cache.query_ttl'))
            ->cacheTags(OrderStatus::CACHE_TAG)
            ->where('is_active', 1)
            ->pluck('name', 'id')
            ->toArray();

        $data = compact(
            'status'
        );

        return $this->setContent('account.orders.index', $data);
    }

    /**
     * SHOW INFO OF ORDER
     *
     * @return type
     */
    public function show(Request $request, Order $order)
    {
        $orders = Order::where('serie_code', $order->serie_code)
            ->where('code', $order->code)
            ->with('customer')
            ->with('status_history')
            ->orderBy('id', 'asc')
            ->get();

        $firstOrder = $orders->first();


        $data = compact(
            'orders',
            'firstOrder'
        );

        return view('account.orders.show', $data)->render();
    }

    /**
     * @param Request $request
     * @return DataTables
     */
    public function datatable(Request $request)
    {
        /** @var \App\Models\Order */
        $user = Auth::guard('customer')->user()->id;

        $data = Order::where('customer_id', $user)
            ->with('status')
            ->with('chat')->when($request->has('unread_messages') && $request->unread_messages == 1, function ($query) {
                $query->whereHas('chat.messages', function ($query) {
                    $query->where('message_customer', 0)
                        ->where('date', '>', DB::raw('chats.customer_last_time_open'));
                });
            });


        //filter date registration
        $registrationDateMin = $request->get('registration_date_min');
        if ($request->has('registration_date_min') && !empty($request->get('registration_date_min'))) {
            $registrationDateMax = $registrationDateMin;

            if ($request->has('registration_date_max') && !empty($request->get('registration_date_max'))) {
                $registrationDateMax = $request->get('registration_date_max');
            }

            $registrationDateMin = $registrationDateMin . ' 00:00:00';
            $registrationDateMax = $registrationDateMax . ' 23:59:59';
            $data->whereBetween('registration_date', [$registrationDateMin, $registrationDateMax]);
        }

        //filter date delivery
        $deliveryDateMin = $request->get('delivery_date_min');
        if ($request->has('delivery_date_min') && !empty($request->get('delivery_date_min'))) {
            $deliveryDateMax = $deliveryDateMin;

            if ($request->has('delivery_date_max') && !empty($request->get('delivery_date_max'))) {
                $deliveryDateMax = $request->get('delivery_date_max');
            }

            $deliveryDateMin = $deliveryDateMin . ' 00:00:00';
            $deliveryDateMax = $deliveryDateMax . ' 23:59:59';
            $data->whereBetween('delivery_date', [$deliveryDateMin, $deliveryDateMax]);
        }

        //filter status
        $value = $request->get('status');
        if ($request->has('status') && $request->get('status') != '') {
            $data = $data->where('status_id', $value);
        }

        //filter approved by customer
        $value = $request->approved_by_customer;
        if ($request->has('approved_by_customer') && $request->approved_by_customer != "") {
            $data = $data->where('approved_by_customer', $value);
        }

        $data = $data->groupby('code');
        $data = $data->orderBy('code','desc');

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return view('account.partials.datatables.select', compact('row'))->render();
            })
            ->addColumn('code', function ($row) {
                return view('account.orders.datatables.code', compact('row'))->render();
            })
            ->addColumn('serie', function ($row) {
                return view('account.orders.datatables.serie', compact('row'))->render();
            })
            ->addColumn('chat', function ($row) {
                return view('account.orders.datatables.chat', compact('row'))->render();
            })
            ->addColumn('registration_date', function ($row) {
                return view('account.orders.datatables.registration_date', compact('row'))->render();
            })
            ->addColumn('delivery_date', function ($row) {
                return view('account.orders.datatables.delivery_date', compact('row'))->render();
            })
            ->addColumn('status', function ($row) {
                return view('account.orders.datatables.status', compact('row'))->render();
            })
            ->addColumn('approved_by_customer', function ($row) {
                return view('account.orders.datatables.approved_by_customer', compact('row'))->render();
            })
            ->addColumn('actions', function ($row) {
                return view('account.orders.datatables.actions', compact('row'))->render();
            })
            ->addColumn('approve', function ($row) {
                return view('account.orders.datatables.approve', compact('row'))->render();
            })
            ->make(true);
    }


    /**
     * Customer approve order
     *
     * @return redirect
     */
    public function approve(Request $request, $order)
    {
        $order = Order::find($order);
        $order->status_id = OrderStatus::ACCEPTED_CUSTOMER;
        $order->approved_by_customer = 1;
        $order->save();

        Order::where('code', $order->code)
            ->update([
                'status_id' =>  OrderStatus::ACCEPTED_CUSTOMER,
                'approved_by_customer' => 1
        ]);

        $emails[] = 'portal@projetoBase.pt';

        if (!empty($order->customer->manager)) {
            if (!empty($order->customer->manager->email)) {
                $emails[] = $order->customer->manager->email;
            }
        }

        $emails = validateNotificationEmails($emails);

        $customerName = Customer::where('id', Auth::guard('customer')->user()->id)->value('name');
        if (!empty($emails['valid'])) {
            try {
                Mail::send('emails.orders.approved_by_customer', compact('order','customerName'), function ($message) use ($emails) {
                    $message->to($emails['valid'])
                        ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                        ->subject('Notificação: Encomenda Aprovada');
                });
            } catch (\Exception $e) {
            }
        }

        return Redirect::back()->with('success', 'Encomenda aprovada com sucesso');
    }

    /**
     * Customer approve line order
     *
     * @return redirect
     */
    public function lineApprove(Request $request, $id)
    {
        $line = Order::find($id);
        $line->line_accept_by_customer = 1;
        $line->save();

        $orders = Order::where('code',$line->code)->get();
        $customerApproveOrder = 1;
        foreach($orders as $order){
            if($order->line_accept_by_customer != 1){
                $customerApproveOrder = 0;
            }
        }

        if($customerApproveOrder){
            $request = new Request();
            $this->approve($request,$id);
        }else{
            $emails = [];
            $emails[] = 'portal@projetoBase.pt';
            if (!empty($line->customer->manager)) {
                if (!empty($line->customer->manager->email)) {
                    $emails[] = $line->customer->manager->email;
                }
            }

            //SEND EMAIL
            $emails = validateNotificationEmails($emails);

            if (!empty($emails['valid'])) {
                try {
                    Mail::send('emails.orders.line_approved', compact('line'), function ($message) use ($emails, $line) {
                        $message->to($emails['valid'])
                            ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                            ->subject('Maquete Aprovada - ' . $line->code);
                    });

                    $emailFeedback = 'Email enviado com sucesso. ';
                } catch (\Exception $e) {
                    dd($e->getMessage());
                    $emailFeedback = 'Não foi possível enviar o Email. ';
                }
            }
        }    
        
        return response()->json([
            'result' => true,
            'text' => 'Aprovado com sucesso',
            'lineId' => $id,
        ]);
    }


    /**
     * Customer refuse line order
     *
     * @return redirect
     */
    public function lineRefuse(Request $request, $id)
    {
        $line = Order::find($id);
        $line->line_reject_by_customer = 1;
        $line->save();

        //enviar email para portal.projetoBase e para o gestor
        $emails = [];
        $emails[] = 'portal@projetoBase.pt';
        if (!empty($line->customer->manager)) {
            if (!empty($line->customer->manager->email)) {
                $emails[] = $line->customer->manager->email;
            }
        }

        //SEND EMAIL
        $emails = validateNotificationEmails($emails);

        if (!empty($emails['valid'])) {
            try {
                Mail::send('emails.orders.line_refused', compact('line'), function ($message) use ($emails, $line) {
                    $message->to($emails['valid'])
                        ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                        ->subject('Maquete Recusada - ' . $line->code);
                });

                $emailFeedback = 'Email enviado com sucesso. ';
            } catch (\Exception $e) {
                dd($e->getMessage());
                $emailFeedback = 'Não foi possível enviar o Email. ';
            }
        }

        return response()->json([
            'result' => true,
            'text' => 'Recusado com sucesso',
            'lineId' => $id,
        ]);
    }
    
}
