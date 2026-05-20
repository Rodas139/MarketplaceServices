<?php

namespace App\Http\Controllers\Admin\Users;

use Auth, App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Services\UserService;


class UsersController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    public $sidebarActiveOption = 'users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private UserService $userService)
    {
        $this->middleware(['can:admin_users']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $data = $this->userService->getIndexData();
        return $this->setContent('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $data = $this->userService->getCreateData($request);
        return $this->setContent('admin.users.edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = new User();

        return $this->update($request, $user);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, User $user)
    {

        $action = 'Editar Colaborador';

        if (!Auth::user()->hasRole([config('permission.role.admin')]) && $user->id == Auth::user()->id) {
            App::abort(403);
        }

        $data = $this->userService->getEditData($user);
        $data['action'] = $action;
        return $this->setContent('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            $this->userService->updateUser($request, $user);
            return Redirect::route('admin.users.index')->with('success', 'Colaborador gravado com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLogin(Request $request)
    {
        return $this->updateLogin($request, null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLogin(Request $request, User $user)
    {
        try {
            $feedback = $this->userService->updateUserLogin($request, $user);
            return Redirect::back()->with('success', $feedback);
        } catch (\Exception $e) {
            return Redirect::back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(user $user)
    {
        if (!Auth::user()->hasRole([config('permission.role.admin')]) && Auth::user()->id == $user->id) {
            App::abort(403);
        }

        try {
            $this->userService->deleteUser($user);
            return Redirect::back()->with('success', 'Utilizador removido com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massDestroy(Request $request)
    {
        $ids = explode(',', $request->get('ids'));

        try {
            $this->userService->deleteUsers($ids);
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
        $data = $this->userService->getDatatableQuery($request);

        return Datatables::of($data)
            ->addColumn('photo', function ($row) {
                $row->avatar = 1;
                return view('admin.partials.datatables.photo', compact('row'))->render();
            })
            ->editColumn('id', function ($row) use ($request) {
                return view('admin.users.datatables.code', compact('row'))->render();
            })
            ->editColumn('name', function ($row) use ($request) {
                return view('admin.users.datatables.name', compact('row'))->render();
            })
            ->editColumn('email', function ($row) use ($request) {
                return view('admin.users.datatables.email', compact('row'))->render();
            })
            ->editColumn('status', function ($row) {
                $statuses = [
                    'pending' => '<span class="label label-warning">Pendente</span>',
                    'active' => '<span class="label label-success">Ativo</span>',
                    'inactive' => '<span class="label label-danger">Inativo</span>',
                    null => '-'
                ];
                return $statuses[$row->status] ?? '-';
            })
            ->addColumn('select', function ($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->addColumn('roles', function ($row) {
                return view('admin.users.datatables.roles', compact('row'))->render();
            })
            ->addColumn('login_app', function ($row) {
                return view('admin.users.datatables.login_app', compact('row'))->render();
            })
            ->addColumn('login_admin', function ($row) {
                return view('admin.users.datatables.login_admin', compact('row'))->render();
            })
            ->addColumn('active', function ($row) {
                return view('admin.users.datatables.active', compact('row'))->render();
            })
            ->editColumn('location_last_update', function ($row) {
                return view('admin.users.datatables.location', compact('row'))->render();
            })
            ->addColumn('actions', function ($row) use ($request) {
                $row->is_operator = $request->get('source') == 'operators';
                return view('admin.users.datatables.actions', compact('row'))->render();
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    /**
     * Start a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remoteLogin(Request $request, User $user)
    {

        $currentUser = Auth::user();

        Session::put('source_user_id', $currentUser->id);

        Auth::login($user);

        return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');
    }

    /**
     * Update the status of a user
     */
    public function updateStatus(User $user, $status)
    {
        try {
            $statusName = $this->userService->updateUserStatus($user, $status);
            return Redirect::back()->with('success', 'Utilizador ' . $statusName . ' com sucesso.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}
