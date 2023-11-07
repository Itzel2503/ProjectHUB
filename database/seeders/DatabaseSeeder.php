<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Area::create([
            'name' => 'Administrador'
        ]);
        Area::create([
            'name' => 'Administración'
        ]);
        Area::create([
            'name' => 'Programación'
        ]);
        Area::create([
            'name' => 'Diseño'
        ]);
        Area::create([
            'name' => 'Soporte'
        ]);
    }
}
