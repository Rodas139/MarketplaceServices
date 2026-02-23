<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;

use Cache, Auth;

class RolesController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'admin_roles';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['can:admin_roles']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::filterSource()
            ->where('id', '>', 1)
            ->first();

        $this->show($role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'name'         => $request->input('name'),
            'display_name' => $request->input('display_name'),
            'source'       => config('app.source'),
            'guard_name'   => 'admin',
        ]);

        if (!$role->save()) {
            return Redirect::route('admin.roles.index')->with('error', $role->errors()->first());
        }

        return Redirect::route('admin.roles.show', array($role->id))->with('success', 'Perfil criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $selectedRole = $role;
        $roles = Role::filterSource()
            ->where('id', '>', 1)
            ->orderBy('sort')
            ->get();

        if (!empty($selectedRole->source)) {
            $activeModules = getActiveModules();
            $activeModules[] = 'base';

            if (Auth::user()->hasRole([config('permission.role.admin')])) {
                $activeModules[] = 'admin';
            }

            $groupedPermissions = Permission::whereIn('module', $activeModules)
                ->orderBy('display_name')
                ->get();
        } else {
            $groupedPermissions = Permission::orderBy('display_name')->get();
        }

        $groupedPermissions = $groupedPermissions->groupBy('group');

        return $this->setContent('admin.roles.show', compact('roles', 'groupedPermissions', 'selectedRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        Cache::flush();

        //block admin role permissions change
        if (!$this->isEditableRole($role)) {
            return Redirect::back()->with('error', 'Não é possivel editar as permissões do perfil de ' . $role->display_name);
        }

        $permission = $request->input('permission');
        $permission = Permission::whereIn('id', $permission)->get();

        $role->syncPermissions($permission);

        return Redirect::back()->with('success', 'Alterações guardadas com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\redirectResponse
     */
    public function destroy(Role $role)
    {
        Cache::flush();
        abort_if(!$this->isEditableRole($role), 403);

        if (!$role->delete()) {
            return Redirect::route('admin.roles.index')->with('error', 'Ocorreu um erro ao tentar remover o perfil.');
        }

        return Redirect::route('admin.roles.index')->with('success', 'Perfil removido com sucesso.');
    }

    /**
     * Filter if the role can be edited
     *
     * @param Role $role
     * @return bool
     */
    public function isEditableRole(Role $role)
    {
        return $role->name !== config('permission.role.admin')
            && ($role->is_static && Auth::user()->hasRole(config('permission.role.admin')));
    }
}
