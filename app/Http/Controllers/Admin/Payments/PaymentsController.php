<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Http\Controllers\Admin\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class PaymentsController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'payments';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private PaymentService $paymentService)
    {
        $this->middleware(['can:admin_payments']);
    }

    /**
     * Display a listing of the resource.
     * 
     *  @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.payments.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->paymentService->getCreateData();
        return $this->setContent('admin.payments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->paymentService->createPayment($request);
            return Redirect::route('admin.payments.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Payment $payment)
    {
        $data = $this->paymentService->getEditData($payment->id);
        return $this->setContent('admin.payments.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            $this->paymentService->updatePayment($payment->id, $request);
            return Redirect::route('admin.payments.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Payment $payment)
    {
        try {
            $this->paymentService->deletePayment($payment->id);
            return Redirect::route('admin.payments.index')->with('success', 'Pagamento removido com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove all selected resources from storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids'));
            $this->paymentService->deleteMultiplePayments($ids);
            return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Loading table data
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $data = $this->paymentService->getDatatableData();

        if ($request->has('id_order') && $request->get('id_order')) {
            $data->where('id_order', $request->get('id_order'));
        }

        if ($request->has('status') && $request->get('status')) {
            $data->where('status', $request->get('status'));
        }

        $statusLabels = $this->paymentService->getStatusLabels();

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
            })
            ->addColumn('id', function ($row) {
                return '<a href="' . route('admin.payments.edit', $row->id) . '">' . $row->id . '</a>';
            })
            ->addColumn('order_id', function ($row) {
                return $row->order ? $row->order->id : '-';
            })
            ->addColumn('method', function ($row) {
                return $row->method ?? '-';
            })
            ->addColumn('total', function ($row) {
                return $row->total ? number_format($row->total, 2, ',', '€') : '-';
            })
            ->addColumn('status', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.payments.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'id', 'status', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $search = $request->get('search')['value'];
                    $query->where('total', 'LIKE', "%$search%");
                }
            })
            ->make(true);
    }

    /**
     * Update the status of a payment
     * 
     * @param  \App\Models\Payment  $payment
     * @param  string  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Payment $payment, $status)
    {
        try {
            $this->paymentService->updatePaymentStatus($payment->id, $status);
            $statusName = $this->paymentService->getStatusName($status);
            return Redirect::back()->with('success', 'Pagamento ' . $statusName . ' com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get order data for total calculation
     * 
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderData(Order $order)
    {
        $data = $this->paymentService->getOrderData($order);
        return response()->json($data);
    }
}
