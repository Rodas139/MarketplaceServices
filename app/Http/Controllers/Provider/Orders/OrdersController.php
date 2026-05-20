<?php

namespace App\Http\Controllers\Provider\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private OrderService $orderService, private PaymentService $paymentService)
    {
    }

    /**
     * Display a listing of orders for the authenticated provider.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return view('provider.orders.index', $data);
    }

    /**
     * Get datatable data for provider orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $serviceId = $user->id_service;
        
        $data = $this->orderService->getDatatableData()
            ->where('user_id', $userId)
            ->where('service_id', $serviceId);

        if ($request->has('status') && $request->get('status')) {
            $data->where('status', $request->get('status'));
        }

        $statusLabels = $this->orderService->getStatusLabels();

        return DataTables::of($data)
            ->addColumn('id', function ($row) {
                return '<a href="' . route('admin.orders.edit', $row->id) . '">' . $row->id . '</a>';
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('order_date', function ($row) {
                return $row->date ? $row->date->format('d/m/Y') : '-';
            })
            ->addColumn('hour', function ($row) {
                return $row->hour ? str_pad($row->hour, 2, '0', STR_PAD_LEFT) . ':00' : '-';
            })
            ->addColumn('description', function ($row) {
                return substr($row->description, 0, 50) . (strlen($row->description) > 50 ? '...' : '');
            })
            ->addColumn('status', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? '-';
            })
            ->addColumn('actions', function ($row) {
                return view('provider.orders.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['id', 'status', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $search = $request->get('search')['value'];
                    $query->where('description', 'LIKE', "%$search%");
                }
            })
            ->make(true);
    }

    /**
     * Update the status of an order
     * 
     * @param  \App\Models\Order  $order
     * @param  string  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Order $order, $status)
    {
   
        $userId = Auth::user()->id;
        if ($order->user_id !== $userId) {
            return Redirect::back()->with('error', 'Acesso não autorizado.');
        }

        try {
            // Validação: Se tentar completar um pedido, deve verificar se foi pago
            if ($status === 'completed') {
                $payment = Payment::where('id_order', $order->id)->first();
                if (!$payment || $payment->status !== 'paid') {
                    return Redirect::back()->with('error', 'Não pode concluir o pedido. O pagamento ainda não foi realizado.');
                }
            }

            $this->orderService->updateOrderStatus($order->id, $status);
            $statusName = $this->orderService->getStatusName($status);
            
 
            if ($status === 'accepted') {
                $orderData = $this->paymentService->getOrderData($order);
                Payment::create([
                    'id_order' => $order->id,
                    'total' => $orderData['total'],
                    'status' => 'pending'
                ]);
            }
            
            return Redirect::back()->with('success', 'Pedido ' . $statusName . ' com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an order
     * 
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {

        $userId = Auth::user()->id;
        if ($order->user_id !== $userId) {
            return Redirect::back()->with('error', 'Acesso não autorizado.');
        }

        try {
            $this->orderService->deleteOrder($order->id);
            return Redirect::back()->with('success', 'Pedido removido com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show order details with comments
     * 
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Order $order)
    {
        $userId = Auth::user()->id;
        if ($order->user_id !== $userId) {
            return Redirect::back()->with('error', 'Acesso não autorizado.');
        }

        $order->load('customer', 'user.service', 'comments', 'service');

        $earnings = null;
        if ($order->status === 'completed') {
            $earnings = $this->paymentService->getProviderEarnings($order);
        }
        
        return view('provider.orders.show', compact('order', 'earnings'));
    }
}
