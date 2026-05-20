<?php

namespace App\Http\Controllers\Provider\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ServicesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private ServiceService $serviceService)
    {
    }

    /**
     * Display a listing of services for the authenticated provider.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $service = $this->serviceService->getProviderService($user);

        $data = compact('service');
        return view('provider.services.index', $data);
    }

    /**
     * Display a listing of all available services (active and without user_id).
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function available()
    {
        $user = Auth::user();
        $currentServiceId = $user->id_service;

        $services = $this->serviceService
            ->getProviderAvailableServicesQuery()
            ->get();

        $data = compact('services', 'currentServiceId');
        return view('provider.services.available', $data);
    }

    /**
     * Change provider's service.
     *
     * @param  Service  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeService(Service $service)
    {
        $user = Auth::user();

        try {
            $this->serviceService->changeProviderService($user, $service);
            return Redirect::route('provider.services.index')
                ->with('success', 'Serviço alterado com sucesso para: ' . $service->name);
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $this->mapChangeServiceError($e));
        }
    }

    /**
     * Display form to create a new service.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->serviceService->getProviderCreateData();
        return view('provider.services.create', $data);
    }

    /**
     * Store a new service for the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categorys,id',
        ], [
            'name.required' => 'O nome do serviço é obrigatório.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'category_id.required' => 'A categoria é obrigatória.',
        ]);

        try {
            $this->serviceService->createProviderService($request);

            return Redirect::route('provider.services.available')
                ->with('success', 'Serviço enviado com sucesso! Aguarde a aprovação do administrador.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get datatable data for available services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatableAvailable(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $currentServiceId = $user->id_service;

        $services = $this->serviceService->getProviderAvailableServicesQuery();

        if ($request->has('category_id') && $request->get('category_id')) {
            $services->where('category_id', $request->get('category_id'));
        }

        return \Yajra\DataTables\Facades\DataTables::of($services)
            ->addColumn('category', function($service) {
                return $service->category->name ?? 'N/A';
            })
            ->addColumn('price', function($service) {
                return number_format($service->price, 2, ',', '.') . '€';
            })
            ->addColumn('status', '<span class="label label-success"><i class="fa fa-check"></i> Ativo</span>')
            ->addColumn('actions', function($service) use ($currentServiceId) {
                if ($currentServiceId != $service->id) {
                    $html = '<form action="' . route('provider.services.change', $service->id) . '" method="POST" style="display: inline;">';
                    $html .= csrf_field();
                    $html .= '<button type="submit" class="btn btn-xs btn-default" title="Mudar para este serviço">';
                    $html .= '<i class="fa fa-exchange"></i> Mudar';
                    $html .= '</button></form>';
                    return $html;
                } else {
                    return '<span class="label label-primary"><i class="fa fa-check"></i> Atual</span>';
                }
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * Translate service error codes into user messages.
     *
     * @param \Exception $exception
     * @return string
     */
    private function mapChangeServiceError(\Exception $exception)
    {
        switch ($exception->getMessage()) {
            case 'same_service':
                return 'Já tem este serviço atribuído.';
            case 'pending_orders':
                return 'Não pode mudar de serviço com pedidos pendentes neste serviço. Todos os pedidos devem estar concluídos ou recusados.';
            default:
                return $exception->getMessage();
        }
    }
}
