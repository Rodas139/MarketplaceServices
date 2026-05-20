<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use App\Models\Service;
use App\Models\Comment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Get all orders ordered by date descending
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrders()
    {
        return Order::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get a specific order by ID
     *
     * @param int $id
     * @return Order
     */
    public function getOrderById($id)
    {
        return Order::findOrFail($id);
    }

    /**
     * Create a new order
     *
     * @param Request $request
     * @return Order
     */
    public function createOrder(Request $request)
    {
        $order = new Order();
        return $this->saveOrderData($order, $request);
    }

    /**
     * Update an existing order
     *
     * @param int $id
     * @param Request $request
     * @return Order
     */
    public function updateOrder($id, Request $request)
    {
        $order = Order::findOrFail($id);
        return $this->saveOrderData($order, $request);
    }

    /**
     * Save order data (create or update)
     *
     * @param Order $order
     * @param Request $request
     * @return Order
     */
    private function saveOrderData(Order $order, Request $request)
    {
        $input = $request->all();

        if (!$order->validate($input)) {
            throw new Exception($order->errors()->first());
        }

        $order->fill($input);
        $order->save();

        return $order;
    }

    /**
     * Delete a specific order
     *
     * @param int $id
     * @return bool
     */
    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        
        // Delete associated comments
        Comment::where('id_order', $id)->delete();
        
        // Delete associated payments
        Payment::where('id_order', $id)->delete();
        
        if (!$order->delete()) {
            throw new Exception('Ocorreu um erro ao tentar remover o pedido.');
        }

        return true;
    }

    /**
     * Delete multiple orders
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleOrders($ids)
    {
        // Delete associated comments for all orders
        Comment::whereIn('id_order', $ids)->delete();
        
        // Delete associated payments for all orders
        Payment::whereIn('id_order', $ids)->delete();
        
        $result = Order::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new Exception('Não foi possível remover os registos selecionados.');
        }

        return true;
    }

    /**
     * Update order status
     *
     * @param int $id
     * @param string $status
     * @return Order
     */
    public function updateOrderStatus($id, $status)
    {
        if (!in_array($status, ['pending', 'accepted', 'declined', 'completed'])) {
            throw new Exception('Status inválido.');
        }

        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();

        return $order;
    }

    /**
     * Get datatable data for orders
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Order::query();
    }

    /**
     * Search orders by customer or description
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchOrders($query)
    {
        return Order::where('description', 'LIKE', "%$query%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->get();
    }

    /**
     * Get data for order create form
     *
     * @return array
     */
    public function getCreateData()
    {
        $action = 'Novo Pedido';
        $order = new Order();
        $customers = Customer::pluck('name', 'id')->toArray();
        $users = User::role('prestador')->pluck('name', 'id')->toArray();
        $services = Service::pluck('name', 'id')->toArray();
        $formOptions = ['route' => ['admin.orders.store']];

        return compact('action', 'formOptions', 'order', 'customers', 'users', 'services');
    }

    /**
     * Get data for order edit form
     *
     * @param int $id
     * @return array
     */
    public function getEditData($id)
    {
        $order = Order::findOrFail($id);
        $action = 'Editar Pedido';
        $customers = Customer::pluck('name', 'id')->toArray();
        $users = User::role('prestador')->pluck('name', 'id')->toArray();
        $services = Service::pluck('name', 'id')->toArray();
        $formOptions = ['route' => ['admin.orders.update', $order->id], 'method' => 'PUT'];

        return compact('order', 'action', 'formOptions', 'customers', 'users', 'services');
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
            'accepted' => '<span class="label label-info">Aceite</span>',
            'declined' => '<span class="label label-danger">Recusada</span>',
            'completed' => '<span class="label label-success">Concluída</span>'
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
            'accepted' => 'Aceite',
            'declined' => 'Recusada',
            'completed' => 'Concluída'
        ];

        return $names[$status] ?? $status;
    }

    /**
     * Get customer orders list with optional status filter.
     *
     * @param int $customerId
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomerOrders($customerId, Request $request)
    {
        $query = Order::where('id_customer', $customerId)->with('user');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        return $query->orderBy('id', 'asc')->get();
    }

    /**
     * Load order details for customer views.
     *
     * @param Order $order
     * @return Order
     */
    public function getCustomerOrderDetails(Order $order)
    {
        return $order->load('user.service', 'customer');
    }

    /**
     * Get datatable query for customer orders.
     *
     * @param int $customerId
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getCustomerOrdersDatatableQuery($customerId, Request $request)
    {
        $data = Order::where('customer_id', $customerId)
            ->with('status')
            ->with('chat')->when($request->has('unread_messages') && $request->unread_messages == 1, function ($query) {
                $query->whereHas('chat.messages', function ($query) {
                    $query->where('message_customer', 0)
                        ->where('date', '>', DB::raw('chats.customer_last_time_open'));
                });
            });

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

        $value = $request->get('status');
        if ($request->has('status') && $request->get('status') != '') {
            $data = $data->where('status_id', $value);
        }

        $value = $request->approved_by_customer;
        if ($request->has('approved_by_customer') && $request->approved_by_customer != "") {
            $data = $data->where('approved_by_customer', $value);
        }

        $data = $data->groupby('code');
        $data = $data->orderBy('code', 'desc');

        return $data;
    }

    /**
     * Create a new order for a customer.
     *
     * @param Request $request
     * @param int $customerId
     * @return Order
     */
    public function createCustomerOrder(Request $request, $customerId)
    {
        $user = User::find($request->user_id);
        $service = Service::find($request->service_id);

        if (!$user || !$service) {
            throw new Exception('user_or_service_missing');
        }

        if ($user->id_service != $service->id) {
            throw new Exception('service_mismatch');
        }

        $order = new Order();
        $order->id_customer = $customerId;
        $order->user_id = $request->user_id;
        $order->service_id = $request->service_id;
        $order->date = $request->date;
        $order->hour = $request->hour;
        $order->description = $request->description;
        $order->status = 'pending';
        $order->save();

        return $order;
    }

    /**
     * Store a comment for a completed order.
     *
     * @param Request $request
     * @param Order $order
     * @param int $customerId
     * @param array $validated
     * @return Comment
     */
    public function addCustomerComment(Request $request, Order $order, $customerId, array $validated)
    {
        if ($order->id_customer != $customerId) {
            throw new Exception('comment_unauthorized');
        }

        if ($order->status != 'completed') {
            throw new Exception('comment_not_completed');
        }

        $comment = new Comment();
        $comment->id_customer = $customerId;
        $comment->id_order = $order->id;
        $comment->comment = $validated['comment'];
        $comment->rating = $validated['rating'];

        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();

                $file->move(public_path('uploads/comments'), $filename);
                $comment->image = 'uploads/comments/' . $filename;
            } catch (\Exception $imageException) {
            }
        }

        $comment->save();

        return $comment;
    }
}
