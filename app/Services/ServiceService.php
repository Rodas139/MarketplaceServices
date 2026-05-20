<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

class ServiceService
{
    /**
     * Get all services ordered by name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllServices()
    {
        return Service::orderBy('name')->get();
    }

    /**
     * Get a specific service by ID
     *
     * @param int $id
     * @return Service
     */
    public function getServiceById($id)
    {
        return Service::findOrFail($id);
    }

    /**
     * Create a new service
     *
     * @param Request $request
     * @return Service
     */
    public function createService(Request $request)
    {
        $service = new Service();
        return $this->saveServiceData($service, $request);
    }

    /**
     * Update an existing service
     *
     * @param int $id
     * @param Request $request
     * @return Service
     */
    public function updateService($id, Request $request)
    {
        $service = Service::findOrFail($id);
        return $this->saveServiceData($service, $request);
    }

    /**
     * Save service data (create or update)
     *
     * @param Service $service
     * @param Request $request
     * @return Service
     */
    private function saveServiceData(Service $service, Request $request)
    {
        $input = $request->all();

        if (!$service->validate($input)) {
            throw new Exception($service->errors()->first());
        }

        $service->fill($input);
        $service->save();

        return $service;
    }

    /**
     * Delete a specific service
     *
     * @param int $id
     * @return bool
     */
    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        
        // Verificar se o serviço tem pedidos associados
        if ($service->orders()->count() > 0) {
            throw new Exception('Não é possível remover este serviço pois tem pedidos associados.');
        }
        
        if (!$service->delete()) {
            throw new Exception('Ocorreu um erro ao tentar remover o serviço.');
        }

        return true;
    }

    /**
     * Delete multiple services
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleServices($ids)
    {
        // Verificar se algum serviço tem pedidos associados
        $servicesWithOrders = Service::whereIn('id', $ids)
            ->whereHas('orders')
            ->pluck('name')
            ->toArray();
        
        if (!empty($servicesWithOrders)) {
            throw new Exception('Não é possível remover os seguintes serviços pois têm pedidos associados: ' . implode(', ', $servicesWithOrders));
        }
        
        $result = Service::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new Exception('Não foi possível remover os registos selecionados.');
        }

        return true;
    }

    /**
     * Update service status
     *
     * @param int $id
     * @param string $status
     * @return Service
     */
    public function updateServiceStatus($id, $status)
    {
        if (!in_array($status, ['pending', 'active', 'inactive'])) {
            throw new Exception('Status inválido.');
        }

        $service = Service::findOrFail($id);
        $service->status = $status;
        $service->save();

        return $service;
    }

    /**
     * Get datatable data for services
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Service::query();
    }

    /**
     * Get data for service create form
     *
     * @return array
     */
    public function getCreateData()
    {
        $action = 'Novo Serviço';
        $service = new Service();
        $categories = Category::pluck('name', 'id')->toArray();
        $formOptions = ['route' => ['admin.services.store']];

        return compact('action', 'formOptions', 'service', 'categories');
    }

    /**
     * Get data for service edit form
     *
     * @param int $id
     * @return array
     */
    public function getEditData($id)
    {
        $service = Service::findOrFail($id);
        $action = 'Editar Serviço';
        $categories = Category::pluck('name', 'id')->toArray();
        $formOptions = ['route' => ['admin.services.update', $service->id], 'method' => 'PUT'];

        return compact('service', 'action', 'formOptions', 'categories');
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
            'active' => '<span class="label label-success">Ativo</span>',
            'inactive' => '<span class="label label-danger">Inativo</span>'
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
            'active' => 'Ativo',
            'inactive' => 'Inativo'
        ];

        return $names[$status] ?? $status;
    }

    /**
     * Get the current provider service.
     *
     * @param User $user
     * @return Service|null
     */
    public function getProviderService(User $user)
    {
        return $user->service;
    }

    /**
     * Get available services for provider selection.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getProviderAvailableServicesQuery()
    {
        return Service::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', 0);
            })
            ->whereNotIn('id', function ($query) {
                $query->select('id_service')
                    ->from('users')
                    ->whereNotNull('id_service')
                    ->where('id_service', '!=', 0);
            })
            ->with('category');
    }

    /**
     * Get data for provider service create form.
     *
     * @return array
     */
    public function getProviderCreateData()
    {
        $categories = Category::all();

        return compact('categories');
    }

    /**
     * Create a provider service request.
     *
     * @param Request $request
     * @return Service
     */
    public function createProviderService(Request $request)
    {
        return Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'user_id' => 0,
            'status' => 'pending'
        ]);
    }

    /**
     * Change provider's service with safety checks.
     *
     * @param User $user
     * @param Service $service
     * @return void
     */
    public function changeProviderService(User $user, Service $service)
    {
        if ($user->id_service == $service->id) {
            throw new Exception('same_service');
        }

        $pendingOrders = Order::where('user_id', $user->id)
            ->where('service_id', $user->id_service)
            ->whereIn('status', ['pending', 'accepted', 'completed_partial'])
            ->count();

        if ($pendingOrders > 0) {
            throw new Exception('pending_orders');
        }

        $user->id_service = $service->id;
        $user->save();
    }
}
