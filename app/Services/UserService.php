<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Croppa;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserService
{
    /**
     * Get data for users index view.
     *
     * @return array
     */
    public function getIndexData()
    {
        $roles = Role::listRoles();

        return compact('roles');
    }

    /**
     * Get data for create view.
     *
     * @param Request $request
     * @return array
     */
    public function getCreateData(Request $request)
    {
        $user = new User();
        $action = 'Adicionar Colaborador';
        $roles = Role::listRoles();
        $rolePermissions = Role::with('perms')->get(['id'])->toArray();
        $assignedRoles = [];
        $formOptions = ['route' => ['admin.users.store', 'source' => 'users'], 'files' => true];
        $loginFormOptions = ['route' => ['admin.users.update.login', 'source' => 'users'], 'method' => 'PUT', 'files' => true];

        $operatorsList = User::remember(config('cache.query_ttl'))
            ->where('id', '>', 1)
            ->pluck('name', 'id')
            ->toArray();

        $services = [];

        return compact(
            'action',
            'formOptions',
            'loginFormOptions',
            'user',
            'roles',
            'rolePermissions',
            'assignedRoles',
            'operatorsList',
            'services'
        );
    }

    /**
     * Get data for edit view.
     *
     * @param User $user
     * @return array
     */
    public function getEditData(User $user)
    {
        $action = 'Editar Colaborador';
        $roles = Role::listRoles();
        $rolePermissions = Role::with('perms')->get(['id'])->toArray();

        $assignedRoles = $user->roles()
            ->pluck('role_id')
            ->toArray();

        $assignedRoles = array_map('intval', $assignedRoles);

        $formOptions = ['route' => ['admin.users.update', $user], 'method' => 'PUT', 'files' => true];
        $loginFormOptions = ['route' => ['admin.users.update.login', $user, 'source' => 'users'], 'method' => 'PUT', 'files' => true];

        $operatorsList = User::remember(config('cache.query_ttl'))
            ->where('id', '>', 1)
            ->whereNot('id', $user->id)
            ->pluck('name', 'id')
            ->toArray();

        $services = Service::where('status', 'active')->pluck('name', 'id')->toArray();

        return compact(
            'user',
            'action',
            'formOptions',
            'loginFormOptions',
            'roles',
            'rolePermissions',
            'assignedRoles',
            'operatorsList',
            'services'
        );
    }

    /**
     * Create or update a user.
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function updateUser(Request $request, User $user)
    {
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();
        $input['active'] = $request->get('active', false);
        $roleId = $request->get('role_id');

        if (Auth::user()->id == $user->id) {
            unset($input['active']);
        }

        if (!$user->exists && $roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $isAdmin = strtolower($role->name) === 'administrator';
                $input['status'] = $isAdmin ? 'active' : 'pending';
            }
        }

        $validator = Validator::make($input, []);
        $input['email'] = trim(strtolower($input['email']));

        $emailExists = User::where('email', $input['email'])
            ->where('id', '<>', $user->id)
            ->first();

        if ($emailExists) {
            throw new \Exception('Este email já está registado por outro colaborador.');
        }

        if (!empty($input['password']) && $input['password'] !== $input['password_confirmation']) {
            throw new \Exception('As palavras-passe não coincidem.');
        }

        if ($validator->passes()) {
            if (!empty($input['password'])) {
                $input['password'] = bcrypt($input['password']);
            } else {
                unset($input['password']);
            }

            unset($input['password_confirmation']);

            $user->fill($input);
            $user->save();

            if ($request->has('role_id')) {
                $roleId = $request->get('role_id');
                $roles = [$roleId];
                $user->roles()->sync($roles);
            }

            return;
        }

        throw new \Exception($validator->errors()->first());
    }

    /**
     * Update login details for a user.
     *
     * @param Request $request
     * @param User $user
     * @return string
     */
    public function updateUserLogin(Request $request, User $user)
    {
        User::flushCache(User::CACHE_TAG);

        $input = $request->except('role_id');

        $input['active'] = $request->get('active', false);
        $input['login_app'] = $request->get('login_app', false);
        $input['login_admin'] = $request->get('login_admin', false);
        $input['allowed_actions'] = $request->get('allowed_actions', false);
        $input['allowed_actions'] = $request->get('allowed_actions', false);
        $input['location_enabled'] = $request->get('location_enabled', false);
        $input['email'] = trim(strtolower(@$input['email']));
        $input['is_operator'] = false;

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
            $rules['name'] = 'required';
            $checkEmail = true;
        } elseif ($user->exists && $user->password) {
            $changePass = true;
            $checkEmail = false;
            $feedback = 'Palavra-passe alterada com sucesso.';
            $rules['password'] = 'confirmed';
        } elseif (!$user->exists) {
            $checkEmail = true;
            $rules['name'] = 'required';
        }

        if ($checkEmail) {
            $userEmail = User::where('email', $input['email'])
                ->where('source', config('app.source'));

            if ($user->exists) {
                $userEmail = $userEmail->where('id', '<>', $user->id);
            }

            $userEmail = $userEmail->first();

            if ($userEmail) {
                throw new \Exception('Já existe outro utilizador com o e-mail ' . $input['email']);
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
                $input['source'] = null;
                $input['login_admin'] = 1;
            }

            $user->fill($input);
            $user->fullname = $user->fullname ? $user->fullname : $user->name;

            if (@$input['delete_photo'] && !empty($user->filepath)) {
                Croppa::delete($user->filepath);
                $user->filepath = null;
                $user->filename = null;
                $user->filehost = null;
            }

            if ($request->hasFile('image')) {
                if ($user->exists && !empty($user->filepath) && File::exists(public_path() . '/' . $user->filepath)) {
                    Croppa::delete($user->filepath);
                }

                if (!$user->upload($request->file('image'), true, 20)) {
                    throw new \Exception('Não foi possível alterar a imagem do perfil.');
                }
            } else {
                $user->save();
            }

            if (!$changePass && $user->id != Auth::user()->id) {
                $roles = $request->has('role_id') ? [$request->get('role_id')] : [];
                $user->roles()->sync($roles);
            }

            if (@$input['send_email']) {
                $input['password'] = @$input['uncrypted_password'];

                Mail::send('emails.users.password', compact('input', 'user', 'isOperator'), function ($message) use ($input, $user, $isOperator) {
                    $message->to($user->email)
                        ->from(env('MAIL_FROM'), env('MAIL_FROM_NAME'))
                        ->subject('Dados de acesso à Área de Gestão');
                });
            }

            return $feedback;
        }

        throw new \Exception($validator->errors()->first());
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user)
    {
        User::flushCache(User::CACHE_TAG);

        $user = User::findOrFail($user->id);

        if ($user->service) {
            $pendingOrAcceptedOrders = $user->service->orders()
                ->whereIn('status', ['pending', 'accepted'])
                ->count();

            if ($pendingOrAcceptedOrders > 0) {
                throw new \Exception('Não é possível remover este utilizador. O serviço associado possui pedidos pendentes ou aceites.');
            }
        }

        $user->email = '_' . time() . '_' . $user->email;
        $user->save();

        $result = $user->delete();

        if (!$result) {
            throw new \Exception('Ocorreu um erro ao tentar remover o utilizador.');
        }
    }

    /**
     * Delete multiple users.
     *
     * @param array $ids
     * @return void
     */
    public function deleteUsers(array $ids)
    {
        User::flushCache(User::CACHE_TAG);

        $usersWithPendingOrders = User::whereIn('id', $ids)
            ->with('service')
            ->get()
            ->filter(function ($user) {
                if ($user->service) {
                    $pendingOrAcceptedOrders = $user->service->orders()
                        ->whereIn('status', ['pending', 'accepted'])
                        ->count();
                    return $pendingOrAcceptedOrders > 0;
                }
                return false;
            });

        if ($usersWithPendingOrders->count() > 0) {
            throw new \Exception('Não é possível remover. Um ou mais utilizadores têm serviços com pedidos pendentes ou aceites.');
        }

        $result = User::whereIn('id', $ids)->delete();

        if (!$result) {
            throw new \Exception('Não foi possível remover os registos selecionados');
        }
    }

    /**
     * Get datatable query for users.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getDatatableQuery(Request $request)
    {
        $data = User::with('roles')
            ->select();

        if ($value = $request->get('role')) {
            $data = $data->whereHas('roles', function ($q) use ($value) {
                $q->where('role_id', $value);
            });
        }

        if ($value = $request->get('status')) {
            $data = $data->where('status', $value);
        }

        if ($value = $request->get('login_admin')) {
            $data = $data->where('login_admin', $value);
        }

        if ($value = $request->get('login_app')) {
            $data = $data->where('login_app', $value);
        }

        if ($value = $request->get('login')) {
            if ($value) {
                $data = $data->whereNotNull('password');
            } else {
                $data = $data->whereNull('password');
            }
        }

        return $data;
    }

    /**
     * Update user status.
     *
     * @param User $user
     * @param string $status
     * @return string
     */
    public function updateUserStatus(User $user, $status)
    {
        if (!in_array($status, ['pending', 'active', 'inactive'])) {
            throw new \Exception('Status inválido.');
        }

        $user->status = $status;
        $user->save();

        $statusNames = [
            'pending' => 'Pendente',
            'active' => 'Ativo',
            'inactive' => 'Inativo'
        ];

        return $statusNames[$status] ?? $status;
    }
}
