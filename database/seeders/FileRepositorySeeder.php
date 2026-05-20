<?php

namespace Database\Seeders;

use App\Models\FileRepository;
use Illuminate\Database\Seeder;

class FileRepositorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        FileRepository::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Clientes',
            'is_folder' => 1,
            'is_static' => 1,
        ]);

        FileRepository::updateOrCreate([
            'id' => 2
        ], [
            'name' => 'Utilizadores',
            'is_folder' => 1,
            'is_static' => 1,
        ]);

        FileRepository::updateOrCreate([
            'id' => 3
        ], [
            'name' => 'Veiculos',
            'is_folder' => 1,
            'is_static' => 1,
        ]);

        FileRepository::updateOrCreate([
            'id' => 4
        ], [
            'name' => 'Projetos',
            'is_folder' => 1,
            'is_static' => 1,
        ]);

        FileRepository::updateOrCreate([
            'id' => 5,
        ], [
            'name' => 'Fornecedores',
            'is_folder' => 1,
            'is_static' => 1,
        ]);
    }
}
