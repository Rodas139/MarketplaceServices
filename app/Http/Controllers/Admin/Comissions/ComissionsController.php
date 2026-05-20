<?php

namespace App\Http\Controllers\Admin\Comissions;

use App\Http\Controllers\Admin\Controller;
use App\Models\Comission;
use App\Models\Payment;
use App\Services\ComissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class ComissionsController extends Controller
{
    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'comissions';

    protected $comissionService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ComissionService $comissionService)
    {
        $this->comissionService = $comissionService;
        $this->middleware(['can:admin_comissions']);
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = [];
        return $this->setContent('admin.comissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $data = $this->comissionService->getCreateData();
        return $this->setContent('admin.comissions.create', $data);
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
            $this->comissionService->createComission($request);
            return Redirect::route('admin.comissions.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\Comission  $comission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Comission $comission)
    {
        $data = $this->comissionService->getEditData($comission->id);
        return $this->setContent('admin.comissions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comission  $comission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Comission $comission)
    {
        try {
            $this->comissionService->updateComission($comission->id, $request);
            return Redirect::route('admin.comissions.index')->with('success', 'Dados gravados com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\Comission  $comission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comission $comission)
    {
        try {
            $this->comissionService->deleteComission($comission->id);
            return Redirect::route('admin.comissions.index')->with('success', 'Comissão removida com sucesso.');
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
            $this->comissionService->deleteMultipleComissions($ids);
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
        $data = Comission::query();

        if ($request->has('id_payment') && $request->get('id_payment')) {
            $data->where('id_payment', $request->get('id_payment'));
        }

        return DataTables::of($data)
            ->addColumn('select', function ($row) {
                return '<input type="checkbox" name="select" value="' . $row->id . '" class="row-select">';
            })
            ->addColumn('id', function ($row) {
                return '<a href="' . route('admin.comissions.edit', $row->id) . '">' . $row->id . '</a>';
            })
            ->addColumn('payment_id', function ($row) {
                return $row->payment ? $row->payment->id : '-';
            })
            ->addColumn('value', function ($row) {
                return $row->value ? number_format($row->value, 2, ',', '€') : '-';
            })
            ->addColumn('gain', function ($row) {
                return $row->gain ? number_format($row->gain, 2, ',', '€') : '-';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.comissions.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['select', 'id', 'actions'])
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $search = $request->get('search')['value'];
                    $query->where('value', 'LIKE', "%$search%");
                }
            })
            ->make(true);
    }

    /**
     * Get payment data for gain calculation
     * 
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentData(Payment $payment)
    {
        return response()->json([
            'total' => $payment->total ?? 0
        ]);
    }
}
