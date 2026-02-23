<?php

namespace App\Http\Controllers\Admin\Users;

use Auth, App, Croppa, File, Date, Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Support\Str;


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
    public function __construct()
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
        $roles = Role::listRoles();

        return $this->setContent('admin.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $user = new User;
        $user->code  = $user->setCode(false);

        $action = 'Adicionar Colaborador';

        $roles = Role::listRoles();
        $rolePermissions = Role::with('perms')->get(['id'])->toArray();

        $assignedRoles = [];

        $formOptions = array('route' => array('admin.users.store', 'source' => 'users'), 'files' => true);

        $loginFormOptions = ['route' => array('admin.users.update.login', 'source' => 'users'), 'method' => 'PUT', 'files' => true];

        $operatorsList = User::remember(config('cache.query_ttl'))
            ->where('id', '>', 1)
            ->pluck('name', 'id')
            ->toArray();

        $data = compact(
            'action',
            'formOptions',
            'loginFormOptions',
            'user',
            'roles',
            'rolePermissions',
            'assignedRoles',
            'operatorsList'
        );

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

        $roles = Role::listRoles();

        $rolePermissions = Role::with('perms')->get(['id'])->toArray();

        $assignedRoles = $user->roles()
            ->pluck('role_id')
            ->toArray();

        $assignedRoles = array_map('intval', $assignedRoles);

        $formOptions = ['route' => array('admin.users.update', $user), 'method' => 'PUT', 'files' => true];

        $loginFormOptions = ['route' => array('admin.users.update.login', $user, 'source' => 'users'), 'method' => 'PUT', 'files' => true];

        $operatorsList = User::remember(config('cache.query_ttl'))
            ->where('id', '>', 1)
            ->whereNot('id', $user->id)
            ->pluck('name', 'id')
            ->toArray();

        //count and store on customer
        $now = new Date();

        $data = compact(
            'user',
            'action',
            'formOptions',
            'loginFormOptions',
            'roles',
            'rolePermissions',
            'assignedRoles',
            'operatorsList',
        );

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
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();
        $input['active'] = $request->get('active', false);

        $roleId = $request->get('role_id');

        // if ($roleId == 3) {
        //     $input['login_app']   = 1;
        //     $input['login_admin'] = 0;
        //     $input['is_operator'] = 1;
        // } else {
        //     $input['login_app']   = 1;
        //     $input['login_admin'] = 1;
        //     $input['is_operator'] = 0;
        // }

        if (Auth::user()->id == $user->id) {
            unset($input['active']);
        }

        

        // $user  = User::findOrNew($user);

        $validator = Validator::make($input, []);
        $input['email'] = 'email_acesso_' . $input['code'] . '@'.env('APP_SOURCE').'.pt';


        if ($validator->passes()) {
            $user->fill($input);

            if (empty($user->name) && !empty($user->fullname)) {
                $user->name = split_name($user->fullname);
            }

            if (empty($user->name)) {
                $user->name = $user->fullname;
            }

            $user->source = config('app.source');
            

            //delete image
            if (@$input['delete_photo'] && !empty($user->filepath)) {
                Croppa::delete($user->filepath);
                $user->filepath = null;
                $user->filename = null;
                $user->filehost = null;
            }

            //upload image
            if ($request->hasFile('image')) {

                if ($user->exists && !empty($user->filepath) && File::exists(public_path() . '/' . $user->filepath)) {
                    Croppa::delete($user->filepath);
                }

                if (!$user->upload($request->file('image'), true, 20)) {
                    return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
                }
            } else {
                $user->save();
            }

            //store cards
            if (!empty($user->id_card)) {
                $this->saveCard($user, 'cc', $user->id_card);
            }

            if (!empty($user->ss_card)) {
                $this->saveCard($user, 'ss', $user->ss_card);
            }

            if ($request->has('role_id')) {
                $roleId = $request->get('role_id');
                $roles = [$roleId];
                $user->roles()->sync($roles);
            }

            return Redirect::route('admin.users.edit', $user->id)->with('success', 'Alterações gravadas com sucesso.');
        }

        return Redirect::back()->withInput()->with('error', $validator->errors()->first());
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

        User::flushCache(User::CACHE_TAG);

        $input = $request->except('role_id');

        $input['active']            = $request->get('active', false);
        $input['login_app']         = $request->get('login_app', false);
        $input['login_admin']       = $request->get('login_admin', false);
        $input['allowed_actions']   = $request->get('allowed_actions', false);
        $input['allowed_actions']   = $request->get('allowed_actions', false);
        $input['location_enabled']  = $request->get('location_enabled', false);
        $input['email']             = trim(strtolower(@$input['email']));
        $input['is_operator']       = false;

        if (Auth::user()->id == $user->id) {
            unset(
                $input['active'],
                $input['is_operator'],
                $input['login_app'],
                $input['login_admin'],
                $input['allowed_actions']
            );
        }

        $changePass = false;
        $checkEmail = true;
        $isOperator = false;

        $feedback = 'Dados gravados com sucesso.';
        $rules = [];
        if ($user->exists && empty($input['password'])) {
            $rules['name']  = 'required';
            $checkEmail = true;
        } elseif ($user->exists && $user->password) {
            $changePass = true;
            $checkEmail = false;
            $feedback = 'Palavra-passe alterada com sucesso.';
            $rules['password'] = 'confirmed';
        } elseif (!$user->exists) {
            $checkEmail = true;
            $rules['name']  = 'required';
        }


        if ($checkEmail) {
            $userEmail = User::where('email', $input['email'])
                ->where('source', config('app.source'));

            if ($user->exists) {
                $userEmail = $userEmail->where('id', '<>', $user->id);
            }

            $userEmail = $userEmail->first();

            if ($userEmail) {
                return Redirect::back()->withInput()->with('error', 'Já existe outro utilizador com o e-mail ' . $input['email']);
            }
        }


        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            if (empty($input['password'])) {
                unset($input['password']);
            } else {
                $input['uncrypted_password'] = $input['password'];
                $input['password'] = bcrypt($input['password']);
            }

            $input['source'] = config('app.source');
            if ($request->get('role_id') == 1 && Auth::user()->isAdmin()) {
                $input['source']      = null;
                $input['login_admin'] = 1;
            }
            // elseif ($request->get('role_id') == 3) { //motorista
            //     $isOperator           = true;
            //     $input['source']      = config('app.source');
            //     $input['is_operator'] = 1;
            //     $input['login_admin'] = 0;
            // }

            $user->fill($input);
            $user->fullname = $user->fullname ? $user->fullname : $user->name;

            //delete image
            if (@$input['delete_photo'] && !empty($user->filepath)) {
                Croppa::delete($user->filepath);
                $user->filepath = null;
                $user->filename = null;
                $user->filehost = null;
            }

            //upload image
            if ($request->hasFile('image')) {

                if ($user->exists && !empty($user->filepath) && File::exists(public_path() . '/' . $user->filepath)) {
                    Croppa::delete($user->filepath);
                }

                if (!$user->upload($request->file('image'), true, 20)) {
                    return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
                }
            } else {
                $user->save();
            }

            if (!$changePass && $user->id != Auth::user()->id) {
                $roles = $request->has('role_id') ? [$request->get('role_id')] : [];
                $user->roles()->sync($roles);
            }

            //send email with password
            if (@$input['send_email']) {

                $input['password'] = @$input['uncrypted_password'];

                Mail::send('emails.users.password', compact('input', 'user', 'isOperator'), function ($message) use ($input, $user, $isOperator) {
                    $message->to($user->email)
                        ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                        ->subject('Dados de acesso à Área de Gestão');
                });
            }

            return Redirect::back()->with('success', $feedback);
        }

        return Redirect::back()->withInput()->with('error', $validator->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(user $user)
    {
        User::flushCache(User::CACHE_TAG);

        $user = User::findOrFail($user->id);

        if (!Auth::user()->hasRole([config('permission.role.admin')]) && Auth::user()->id == $user->id) {
            App::abort(403);
        }

        $user->email = '_' . time() . '_' . $user->email;
        $user->save();

        $result = $user->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o utilizador.');
        }

        return Redirect::back()->with('success', 'Utilizador removido com sucesso.');
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
        User::flushCache(User::CACHE_TAG);

        $ids = explode(',', $request->get('ids'));

        $result = User::whereIn('id', $ids)->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Não foi possível remover os registos selecionados');
        }

        return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
    }

    /**
     * Loading table data
     *
     * @return Datatables
     */
    public function datatable(Request $request)
    {
        $data = User::with('roles')
            ->where(function ($q) {
                $q->where('source', config('app.source'));
                $q->orWhereNull('source');
            })
            ->select();

        //filter role
        if ($value = $request->get('role')) {
            $data = $data->whereHas('roles', function ($q) use ($value) {
                $q->where('role_id', $value);
            });
        }

        //filter login admin
        if ($value = $request->get('login_admin')) {
            $data = $data->where('login_admin', $value);
        }

        //filter is operator
        if ($value = $request->get('login_app')) {
            $data = $data->where('login_app', $value);
        }

        //filter active
        if ($value = $request->get('active')) {
            $data = $data->where('active', $value);
        }

        //filter login
        if ($value = $request->get('login')) {
            if ($value) {
                $data = $data->whereNotNull('password');
            } else {
                $data = $data->whereNull('password');
            }
        }

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
}
