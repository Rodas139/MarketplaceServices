<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Comission;
use Illuminate\Http\Request;
use Exception;

class PaymentService
{
    /**
     * Get all payments ordered by date descending
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPayments()
    {
        return Payment::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get a specific payment by ID
     *
     * @param int $id
     * @return Payment
     */
    public function getPaymentById($id)
    {
        return Payment::findOrFail($id);
    }

    /**
     * Create a new payment
     *
     * @param Request $request
     * @return Payment
     */
    public function createPayment(Request $request)
    {
        $payment = new Payment();
        return $this->savePaymentData($payment, $request);
    }

    /**
     * Update an existing payment
     *
     * @param int $id
     * @param Request $request
     * @return Payment
     */
    public function updatePayment($id, Request $request)
    {
        $payment = Payment::findOrFail($id);
        return $this->savePaymentData($payment, $request);
    }

    /**
     * Save payment data (create or update)
     *
     * @param Payment $payment
     * @param Request $request
     * @return Payment
     */
    private function savePaymentData(Payment $payment, Request $request)
    {
        $input = $request->all();

        if (!$payment->validate($input)) {
            throw new Exception($payment->errors()->first());
        }

        $payment->fill($input);
        $payment->save();

        return $payment;
    }

    /**
     * Delete a specific payment
     *
     * @param int $id
     * @return bool
     */
    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        if (!$payment->delete()) {
            throw new Exception('Ocorreu um erro ao tentar remover o pagamento.');
        }

        return true;
    }

    /**
     * Delete multiple payments
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMultiplePayments($ids)
    {
        $result = Payment::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new Exception('Não foi possível remover os registos selecionados.');
        }

        return true;
    }

    /**
     * Update payment status
     *
     * @param int $id
     * @param string $status
     * @return Payment
     */
    public function updatePaymentStatus($id, $status)
    {
        if (!in_array($status, ['pending', 'paid'])) {
            throw new Exception('Status inválido.');
        }

        $payment = Payment::findOrFail($id);
        $payment->status = $status;
        $payment->save();

        return $payment;
    }

    /**
     * Get datatable data for payments
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Payment::query();
    }

    /**
     * Get data for payment create form
     *
     * @return array
     */
    public function getCreateData()
    {
        $action = 'Novo Pagamento';
        $payment = new Payment();
        $orders = Order::where('status', 'accepted')->pluck('id', 'id')->toArray();
        $formOptions = ['route' => ['admin.payments.store']];

        return compact('action', 'formOptions', 'payment', 'orders');
    }

    /**
     * Get data for payment edit form
     *
     * @param int $id
     * @return array
     */
    public function getEditData($id)
    {
        $payment = Payment::findOrFail($id);
        $action = 'Editar Pagamento';
        $orders = Order::where('status', 'accepted')->pluck('id', 'id')->toArray();
        $formOptions = ['route' => ['admin.payments.update', $payment->id], 'method' => 'PUT'];

        return compact('payment', 'action', 'formOptions', 'orders');
    }

    /**
     * Get status labels array
     *
     * @return array
     */
    public function getStatusLabels()
    {
        return [
            'pending' => '<span class="label label-warning">Pendente</span>',
            'paid' => '<span class="label label-success">Pago</span>'
        ];
    }

    /**
     * Get status name translation
     *
     * @param string $status
     * @return string
     */
    public function getStatusName($status)
    {
        $names = [
            'pending' => 'Pendente',
            'paid' => 'Pago'
        ];

        return $names[$status] ?? $status;
    }

    /**
     * Get order data for total calculation
     *
     * @param Order $order
     * @return array
     */
    public function getOrderData(Order $order)
    {
        $order->load('user.service');
        
        $price = ($order->user && $order->user->service) ? $order->user->service->price : 0;
        $total = ($order->hour ?? 0) * $price;
        
        return [
            'hour' => $order->hour,
            'price' => $price,
            'total' => $total
        ];
    }

    /**
     * Get provider earnings for a completed order.
     *
     * @param Order $order
     * @return array
     */
    public function getProviderEarnings(Order $order)
    {
        $orderData = $this->getOrderData($order);
        $total = (float) $orderData['total'];
        $comissionGain = 0.0;

        $comission = Comission::join('payments', 'payments.id', '=', 'comissions.id_payment')
            ->whereNull('comissions.deleted_at')
            ->where('payments.id_order', $order->id)
            ->orderBy('comissions.created_at', 'desc')
            ->select('comissions.*')
            ->first();

        if ($comission) {
            $comissionGain = (float) $comission->gain;
            if ($comissionGain <= 0 && $comission->value && $total > 0) {
                $comissionGain = round($total * ($comission->value / 100), 2);
            }
        }

        return [
            'total' => $total,
            'comission_gain' => $comissionGain,
            'provider_gain' => $total - $comissionGain,
        ];
    }
}
