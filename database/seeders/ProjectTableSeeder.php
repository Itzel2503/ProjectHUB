<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Project::factory()->count(5)->create();
        // Crear proyectos
        Project::factory()->count(20)->create()->each(function ($project) {
            // Seleccionar dos usuarios aleatorios
            $users = User::inRandomOrder()->take(2)->get();

            // Asegurarse de que siempre hay dos usuarios seleccionados
            if ($users->count() == 2) {
                // Asignar el primer usuario como líder
                $project->users()->attach($users->first()->id, [
                    'leader' => true,
                    'programmer' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Asignar el segundo usuario como programador
                $project->users()->attach($users->last()->id, [
                    'leader' => false,
                    'programmer' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
