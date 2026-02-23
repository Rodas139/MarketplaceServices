<?php

namespace Database\Seeders;

use \App\Models\Permission;
use \App\Models\Role;
use \App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Users
        User::updateOrCreate([
            'id' => 1
        ], [
            'code' => 'M001',
            'code_abbrv' => 'Estagiario',
            'name' => 'Estagiario',
            'fullname' => 'Estagiario',
            'fiscal_titularity' => 0,
            'fiscal_dependents' => 0,
            'email' => 'estagiario@enovo.pt',
            'password' => bcrypt('password'),
            'login_app' => 1,
            'active' => 1,
            'is_operator' => 0,
            'is_developer' => 1,
            'login_admin' => 1
        ]);

    }
}
