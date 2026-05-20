<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;

class CustomersController extends Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'customers';

    protected $customerService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->middleware(['can:admin_customers']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.customers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $data = $this->customerService->getCreateData($request);
        return $this->setContent('admin.customers.create', $data);
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
            $this->customerService->createCustomer($request);
            return Redirect::route('admin.customers.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  Customer $customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Customer $customer)
    {
        $data = $this->customerService->getEditData($customer->id);
        return $this->setContent('admin.customers.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $this->customerService->updateCustomer($customer->id, $request);
            return Redirect::route('admin.customers.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Customer $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->deleteCustomer($customer->id);
            return Redirect::route('admin.customers.index')->with('success', 'Cliente removido com sucesso.');
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
            $this->customerService->deleteMultipleCustomers($ids);
            return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Loading table data
     *
     * @return Datatables
     */
    public function datatable(Request $request)
    {
        try {
            $data = $this->customerService->getDatatableData();

            return DataTables::of($data)
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
                })
                ->editColumn('name', function ($row) {
                    return view('admin.customers.datatables.name', compact('row'))->render();
                })
                ->addColumn('actions', function ($row) {
                    return view('admin.customers.datatables.actions', compact('row'))->render();
                })
                ->rawColumns(['select', 'name', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search customers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $customers = $this->customerService->searchCustomers($search);

        if ($customers->isEmpty()) {
            return Response::json(['id' => '', 'text' => 'Nenhum cliente encontrado.']);
        }

        $results = [];
        foreach ($customers as $customer) {
            $results[] = ['id' => $customer->id, 'text' => $customer->name . ' (' . $customer->email . ')'];
        }

        return Response::json($results);
    }
}
