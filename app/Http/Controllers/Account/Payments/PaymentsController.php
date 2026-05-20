<?php

namespace App\Http\Controllers\Account\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Comission;
use App\Services\PaymentService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PaymentsController extends \App\Http\Controllers\Account\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private PaymentService $paymentService)
    {
    }

    /**
     * Show payment page for order
     * 
     * @param Request $request
     * @return type
     */
    public function show(Order $order)
    {
        $customer = Auth::guard('customer')->user();
        
  
        if ($order->id_customer !== $customer->id) {
            return Redirect::back()->with('error', 'Acesso não autorizado.');
        }


        if ($order->status !== 'accepted') {
            return Redirect::back()->with('error', 'Pedido não está pronto para pagamento.');
        }

        $order->load('user.service');
        $data = compact('order');
        return $this->setContent('account.orders.pay', $data);
    }

    /**
     * Process payment for order
     * 
     * @param Request $request
     * @return type
     */
    public function process(Request $request, Order $order, $method)
    {
        $customer = Auth::guard('customer')->user();
        

        if ($order->id_customer !== $customer->id) {
            return Redirect::back()->with('error', 'Acesso não autorizado.');
        }


        if (!in_array($method, ['mbway', 'transferencia_bancaria'])) {
            return Redirect::back()->with('error', 'Método de pagamento inválido.');
        }

        try {

            $orderData = $this->paymentService->getOrderData($order);
            

            $payment = Payment::where('id_order', $order->id)->first();
            
            if (!$payment) {
                $payment = new Payment();
                $payment->id_order = $order->id;
            }

            $payment->total = $orderData['total'];
            $payment->method = $method;
            $payment->status = 'paid';
            $payment->save();

            if (!Comission::where('id_payment', $payment->id)->exists()) {
                $commissionValue = round($payment->total * 0.20, 2);
                Comission::create([
                    'id_payment' => $payment->id,
                    'value' => 20,
                    'gain' => $commissionValue
                ]);
            }

            return Redirect::route('account.payments.show', $order->id)
                ->with('success', 'Pagamento processado com sucesso. Método: ' . ($method === 'mbway' ? 'MBway' : 'Transferência Bancária'));
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
