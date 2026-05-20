<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Admin\Controller;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ServicesController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'services';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private ServiceService $serviceService)
    {
        $this->middleware(['can:admin_services']);
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.services.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->serviceService->getCreateData();
        return $this->setContent('admin.services.create', $data);
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
            $this->serviceService->createService($request);
            return Redirect::route('admin.services.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Service $service)
    {
        $data = $this->serviceService->getEditData($service->id);
        return $this->setContent('admin.services.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Service $service)
    {
        try {
            $this->serviceService->updateService($service->id, $request);
            return Redirect::route('admin.services.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $service)
    {
        try {
            $this->serviceService->deleteService($service->id);
            return Redirect::route('admin.services.index')->with('success', 'Serviço removido com sucesso.');
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
            $this->serviceService->deleteMultipleServices($ids);
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
        $data = $this->serviceService->getDatatableData();

        if ($request->has('category_id') && $request->get('category_id')) {
            $data->where('category_id', $request->get('category_id'));
        }

        if ($request->has('status') && $request->get('status')) {
            $data->where('status', $request->get('status'));
        }

        $statusLabels = $this->serviceService->getStatusLabels();

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->editColumn('name', function ($row) {
                return view('admin.services.datatables.name', compact('row'))->render();
            })
            ->editColumn('category', function ($row) {
                return $row->category->name ?? '-';
            })
            ->editColumn('price', function ($row) {
                return number_format($row->price, 2, ',', '€');
            })
            ->editColumn('status', function ($row) use ($statusLabels) {
                return $statusLabels[$row->status] ?? '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.services.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['name', 'status', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $search = $request->get('search')['value'];
                    $query->where('name', 'LIKE', "%$search%");
                }
            })
            ->make(true);
    }

    /**
     * Update the status of a service
     * 
     * @param  \App\Models\Service  $service
     * @param  string  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Service $service, $status)
    {
        try {
            $this->serviceService->updateServiceStatus($service->id, $status);
            $statusName = $this->serviceService->getStatusName($status);
            return Redirect::back()->with('success', 'Serviço ' . $statusName . ' com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
