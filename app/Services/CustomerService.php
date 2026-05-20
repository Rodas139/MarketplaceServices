<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Croppa;

class CustomerService
{
    /**
     * Get all customers
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCustomers()
    {
        return Customer::query()->orderBy('name')->get();
    }

    /**
     * Get customer by ID
     * 
     * @param int $customerId
     * @return Customer
     */
    public function getCustomerById($customerId)
    {
        return Customer::findOrFail($customerId);
    }

    /**
     * Create a new customer
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return Customer
     */
    public function createCustomer(Request $request)
    {
        $customer = new Customer();
        return $this->saveCustomerData($customer, $request);
    }

    /**
     * Update existing customer
     *  
     * @param int $customerId
     * @param  \Illuminate\Http\Request  $request
     * @return Customer
     */
    public function updateCustomer($customerId, Request $request)
    {
        $customer = $this->getCustomerById($customerId);
        return $this->saveCustomerData($customer, $request);
    }

    /**
     * Save customer data with validation
     * 
     * @param Customer $customer
     * @param Request $request 
     * @return Customer
     */
    private function saveCustomerData(Customer $customer, Request $request)
    {
        $input = $request->all();

        if ($customer->exists) {
            $customer->rules['code'] = 'required|unique:customers,code,' . $customer->id;
        }

        // Validar se email já existe
        if (!empty($input['email'])) {
            $emailExists = Customer::where('email', trim(strtolower($input['email'])))
                ->where('id', '<>', $customer->id)
                ->first();

            if ($emailExists) {
                throw new \Exception('Este email já está registado por outro cliente.');
            }
        }


        if (!empty($input['password']) && $input['password'] !== $input['password_confirmation']) {
            throw new \Exception('As palavras-passe não coincidem.');
        }

        if ($customer->validate($input)) {
      
            if (!empty($input['password'])) {
                $input['password'] = bcrypt($input['password']);
            } else {
                unset($input['password']);
            }
            unset($input['password_confirmation']);

            $customer->fill($input);
            $customer->save();
            return $customer;
        }

        throw new \Exception($customer->errors()->first());
    }

    /**
     * Delete a single customer
     * 
     * @param int $customerId
     * @return bool
     */
    public function deleteCustomer($customerId)
    {
        $customer = $this->getCustomerById($customerId);

        // Check if customer has orders with pending or accepted status
        $pendingOrAcceptedOrders = Order::where('id_customer', $customerId)
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
        
        if ($pendingOrAcceptedOrders > 0) {
            throw new \Exception('Não é possível remover este cliente. O cliente possui pedidos pendentes ou aceites.');
        }

        if (!$customer->delete()) {
            throw new \Exception('Ocorreu um erro ao tentar remover o cliente.');
        }

        return true;
    }

    /**
     * Delete multiple customers
     * 
     * @param array $ids
     * @return bool
     */
    public function deleteMultipleCustomers(array $ids)
    {
        // Check if any of the selected customers have orders with pending or accepted status
        $customersWithPendingOrders = Order::whereIn('id_customer', $ids)
            ->whereIn('status', ['pending', 'accepted'])
            ->count();
        
        if ($customersWithPendingOrders > 0) {
            throw new \Exception('Não é possível remover. Um ou mais clientes têm pedidos pendentes ou aceites.');
        }

        $result = Customer::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new \Exception('Não foi possível remover os registos selecionados');
        }

        return true;
    }

    /**
     * Search customers
     * 
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchCustomers($query)
    {
        $search = '%' . str_replace(' ', '%', $query) . '%';

        return Customer::where(function ($q) use ($search) {
            $q->where('name', 'LIKE', $search)
                ->orWhere('email', 'LIKE', $search);
        })
        ->get(['id', 'name', 'email']);
    }

    /**
     * Get datatable data for customers
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableData()
    {
        return Customer::select([
            'customers.id',
            'customers.name',
            'customers.email',
            'customers.phone'
        ]);
    }

    /**
     * Get data for index view
     * 
     * @return array
     */
    public function getIndexData()
    {
        return [];
    }

    /**
     * Get data for create form
     * 
     * @param Request $request
     * @return array
     */
    public function getCreateData(Request $request)
    {
        $customer = new Customer();
        $customer->payment_condition = '30d';

        $action = 'Novo Cliente';
        if ($request->get('prospect')) {
            $action = 'Novo Prospecto';
            $customer->is_prospect = 1;
        }

        return [
            'action' => $action,
            'customer' => $customer,
            'formOptions' => [
                'route' => ['admin.customers.store']
            ]
        ];
    }

    /**
     * Get data for edit form
     * 
     * @param int $customerId
     * @return array
     */
    public function getEditData($customerId)
    {
        $customer = $this->getCustomerById($customerId);

        return [
            'action' => 'Editar Cliente',
            'customer' => $customer,
            'fileMain' => $customer->filename,
            'formOptions' => [
                'route' => ['admin.customers.update', $customer->id],
                'method' => 'PUT'
            ]
        ];
    }

    /**
     * Update customer profile details.
     *
     * @param Customer $customer
     * @param array $input
     * @return void
     */
    public function updateAccountDetails(Customer $customer, array $input)
    {
        if ($customer->validate($input)) {
            $customer->fill($input);
            $customer->save();
            return;
        }

        throw new \Exception($customer->errors()->first());
    }

    /**
     * Update customer login details.
     *
     * @param Customer $customer
     * @param Request $request
     * @param array $input
     * @return void
     */
    public function updateAccountLogin(Customer $customer, Request $request, array $input)
    {
        $customer->fill($input);

        if ($input['delete_photo'] && !empty($customer->filepath)) {
            Croppa::delete($customer->filepath);
            $customer->filepath = null;
            $customer->filename = null;
        }

        if ($request->hasFile('image')) {
            if ($customer->exists && !empty($customer->filepath)) {
                Croppa::delete($customer->filepath);
            }

            if (!$customer->upload($request->file('image'))) {
                throw new \Exception('image_upload_failed');
            }
        } else {
            $customer->save();
        }
    }

    /**
     * Update customer password.
     *
     * @param Customer $customer
     * @param string $password
     * @return void
     */
    public function updateAccountPassword(Customer $customer, $password)
    {
        $customer->password = $password;
        $customer->save();
    }
}
