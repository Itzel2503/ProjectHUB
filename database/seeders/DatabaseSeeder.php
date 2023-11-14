<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Permit;
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
        Permit::create([
            'name' => 'Home Office'
        ]);
        Permit::create([
            'name' => 'Horas fuera de oficina'
        ]);
        Permit::create([
            'name' => 'Permiso de ausencia'
        ]);
        Permit::create([
            'name' => 'Vacaiones'
        ]);
        Permit::create([
            'name' => 'Tiempo extra'
        ]);
    }
}
