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
            'name' => 'Estagiario',
            'email' => 'estagiario@enovo.pt',
            'password' => bcrypt('password')
        ]);

    }
}
