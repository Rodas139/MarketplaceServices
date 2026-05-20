<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Admin\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'orders';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private OrderService $orderService)
    {
        $this->middleware(['can:admin_orders'])->except(['prestadorPedidos']);
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.orders.index', $data);
    }

    /**
     * Display pedidos for the authenticated provider.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function prestadorPedidos()
    {
        $user = Auth::guard('admin')->user();
        
        // Verificar se é prestador
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('prestador')) {
            return Redirect::route('admin.dashboard')->with('error', 'Acesso não autorizado.');
        }

        $data = [];
        return $this->setContent('provider.orders.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->orderService->getCreateData();
        return $this->setContent('admin.orders.create', $data);
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
            $this->orderService->createOrder($request);
            return Redirect::route('admin.orders.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource (Pedido).
     * 
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Order $order)
    {
        $data = $this->orderService->getEditData($order->id);
        return $this->setContent('admin.orders.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order)
    {
        try {
            $this->orderService->updateOrder($order->id, $request);
            return Redirect::route('admin.orders.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        try {
            $this->orderService->deleteOrder($order->id);
            return Redirect::route('admin.orders.index')->with('success', 'Pedido removido com sucesso.');
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
            $this->orderService->deleteMultipleOrders($ids);
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
        $data = $this->orderService->getDatatableData();

        // Se o utilizador é um prestador, filtrar apenas seus pedidos
        $user = Auth::guard('admin')->user();
        if ($user && method_exists($user, 'hasRole') && $user->hasRole('prestador')) {
            $data->where('user_id', $user->id);
        }

        if ($request->has('id_customer') && $request->get('id_customer')) {
            $data->where('id_customer', $request->get('id_customer'));
        }

        if ($request->has('status') && $request->get('status')) {
            $data->where('status', $request->get('status'));
        }

        $statusLabels = $this->orderService->getStatusLabels();

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
            })
            ->addColumn('id', function ($row) {
                return '<a href="' . route('admin.orders.edit', $row->id) . '">' . $row->id . '</a>';
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : '-';
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '-';
            })
            ->addColumn('service_name', function ($row) {
                return $row->service ? $row->service->name : '-';
            })
            ->addColumn('order_date', function ($row) {
                return $row->date ? $row->date->format('d/m/Y') : '-';
            })
            ->addColumn('hour', function ($row) {
                return $row->hour ? str_pad($row->hour, 2, '0', STR_PAD_LEFT) . ':00' : '-';
            })
            ->addColumn('status', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.orders.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'id', 'status', 'actions'])
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
        try {
            $this->orderService->updateOrderStatus($order->id, $status);
            $statusName = $this->orderService->getStatusName($status);
            return Redirect::back()->with('success', 'Pedido ' . $statusName . ' com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
