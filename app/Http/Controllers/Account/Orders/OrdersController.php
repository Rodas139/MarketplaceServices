<?php

namespace App\Http\Controllers\Account\Orders;

use App\Models\Order;
use App\Services\OrderService;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends \App\Http\Controllers\Account\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $orders = $this->orderService->getCustomerOrders($customer->id, $request);

        $data = compact('orders');

        return $this->setContent('account.orders.index', $data);
    }

    /**
     * Show order details
     *
     * @param Request $request
     * @return type
     */
    public function show(Request $request, Order $order)
    {
        $order = $this->orderService->getCustomerOrderDetails($order);

        $data = compact('order');

        return $this->setContent('account.orders.show', $data);
    }

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return DataTables
     */
    public function datatable(Request $request)
    {
        $userId = Auth::guard('customer')->user()->id;
        $data = $this->orderService->getCustomerOrdersDatatableQuery($userId, $request);

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
     * Show form to create new order
     * 
     * @param Request $request
      * @return type
     */
    public function create()
    {
        $customer = Auth::guard('customer')->user();
        $categories = \App\Models\Category::select('id', 'name')->get();
        $data = compact('customer', 'categories');
        return $this->setContent('account.orders.create', $data);
    }

    /**
     * Store new order
     * 
     * @param Request $request
      * @return type
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categorys,id',
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'hour' => 'nullable|integer|between:0,23',
            'description' => 'nullable|string'
        ], [
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada é inválida.',
            'service_id.required' => 'O serviço é obrigatório.',
            'service_id.exists' => 'O serviço selecionado é inválido.',
            'user_id.required' => 'O prestador é obrigatório.',
            'user_id.exists' => 'O prestador selecionado é inválido.',
            'date.required' => 'A data do pedido é obrigatória.',
            'date.date' => 'A data deve ser uma data válida.',
            'hour.integer' => 'As horas devem ser um número.',
            'hour.between' => 'As horas devem estar entre 0 e 23.',
            'description.string' => 'A descrição deve ser um texto válido.'
        ]);

        try {
            $customerId = Auth::guard('customer')->user()->id;
            $order = $this->orderService->createCustomerOrder($request, $customerId);
            return redirect()->route('account.orders.show', $order)->with('success', 'Pedido criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors($this->mapCreateOrderError($e));
        }
    }

    /**
     * Store comment for completed order
     * 
     * @param Request $request
      * @return type
      */
    public function storeComment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'comment' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'comment.required' => 'O comentário é obrigatório',
            'comment.min' => 'O comentário deve ter no mínimo 5 caracteres',
            'rating.required' => 'A classificação é obrigatória',
            'rating.integer' => 'A classificação deve ser um número',
            'rating.min' => 'A classificação deve ser entre 1 e 5',
            'rating.max' => 'A classificação deve ser entre 1 e 5',
            'image.image' => 'O ficheiro deve ser uma imagem',
            'image.mimes' => 'O ficheiro deve ser JPEG, PNG, JPG ou GIF',
            'image.max' => 'A imagem não pode ultrapassar 2MB'
        ]);

        try {
            $customerId = Auth::guard('customer')->user()->id;
            $this->orderService->addCustomerComment($request, $order, $customerId, $validated);
            return redirect()->route('account.orders.show', $order)->with('success', 'Comentário deixado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($this->mapCommentError($e, $order));
        }
    }

    /**
     * Translate service errors for create order.
     *
     * @param \Exception $exception
     * @return string
     */
    private function mapCreateOrderError(\Exception $exception)
    {
        switch ($exception->getMessage()) {
            case 'user_or_service_missing':
                return 'Prestador ou serviço selecionado não encontrado.';
            case 'service_mismatch':
                return 'O prestador selecionado não oferece este serviço.';
            default:
                return 'Erro ao criar o pedido: ' . $exception->getMessage();
        }
    }

    /**
     * Translate service errors for comments.
     *
     * @param \Exception $exception
     * @param Order $order
     * @return string
     */
    private function mapCommentError(\Exception $exception, Order $order)
    {
        switch ($exception->getMessage()) {
            case 'comment_unauthorized':
                return 'Não tem permissão para comentar neste pedido.';
            case 'comment_not_completed':
                return 'Só é possível comentar em pedidos completados. Status atual: ' . $order->status;
            default:
                return 'Erro ao guardar o comentário: ' . $exception->getMessage();
        }
    }
    
}
