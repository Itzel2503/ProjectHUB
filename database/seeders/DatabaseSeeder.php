<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Permit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        User::create([
            'name' => 'Kircof',
            'lastname' => 'admin',
            'email' => 'admin@kircof.com',
            'password' => Hash::make('Arten.123!'),
            'type_user' => '1',
            'date_birthday' => Carbon::now(),
            'area_id' => '1',
        ]);
    }
}