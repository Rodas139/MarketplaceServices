<?php

namespace Database\Seeders;

use \App\Models\Permission;
use \App\Models\Role;
use \App\Models\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //Roles
        Role::updateOrCreate([
            'id' => 1
        ], [
            'source' => NULL,
            'name' => 'administrator',
            'guard_name' => 'admin',
            'display_name' => 'Administrador',
            'description' => NULL,
            'color' => '#e41319',
            'sort' => NULL,
            'is_static' => 1
        ]);

        Role::updateOrCreate([
            'id' => 2
        ], [
            'source' => NULL,
            'name' => 'chief',
            'guard_name' => 'admin',
            'display_name' => 'Gerencia',
            'description' => NULL,
            'color' => '#e41319',
            'sort' => NULL,
            'is_static' => 1
        ]);

        // Permissions
        Permission::updateOrCreate([
            'name' => 'admin_roles'
        ], [
            'guard_name' => 'admin',
            'display_name' => 'Permissões',
            'group' => 'Permissões',
            'module' => 'base',
            'description' => NULL
        ]);
        Permission::updateOrCreate([
            'name' => 'admin_customers'
        ], [
            'guard_name' => 'admin',
            'display_name' => 'Clientes',
            'group' => 'Permissões',
            'module' => 'base',
            'description' => NULL
        ]);
        Permission::updateOrCreate([
            'name' => 'admin_users'
        ], [
            'guard_name' => 'admin',
            'display_name' => 'Colaboradores',
            'group' => 'Permissões',
            'module' => 'base',
            'description' => NULL
        ]);
        Permission::updateOrCreate([
            'name' => 'admin_settings'
        ], [
            'guard_name' => 'admin',
            'display_name' => 'Definições',
            'group' => 'Permissões',
            'module' => 'base',
            'description' => NULL
        ]);

        // Permission_roles
        $role = Role::first();
        foreach (Permission::get() as $key => $perm) {
            $role->givePermissionTo($perm->name);
        }

        User::find(1)->assignRole('administrator');
    }
}
