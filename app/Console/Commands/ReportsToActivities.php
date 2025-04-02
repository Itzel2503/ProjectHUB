<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ReportsToActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reportsToActivities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for sending reports to activities';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // PROYECTO ID 30
        $reports = Report::where('project_id', 30)->get();

        if ($reports->isEmpty()) {
            $this->info("No hay actividades recurrentes encontradas.");
            return;
        } else {
            $this->info("Se encontraron " . $reports->count() . " reportes.");
        }

        foreach ($reports as $report) {
            $activity = new Activity();
            $activity->sprint_id = 76;
            $activity->user_id = $report->user_id;
            $activity->delegate_id = $report->delegate_id;
            $activity->icon = $report->icon;
            $activity->title = $report->title;
            $activity->content = $report->content;
            $activity->description = $report->description;
            $activity->priority = $report->priority;
            $activity->state = $report->state;
            $activity->points = $report->points;
            $activity->questions_points = $report->questions_points;
            $activity->look = $report->look;
            $activity->delegated_date = $report->delegated_date;
            $activity->expected_date = $report->expected_date;
            $activity->progress = $report->progress;
            $activity->end_date = $report->end_date;
            $activity->created_at = $report->created_at;
            $activity->updated_at = $report->updated_at;
            
            // Mover archivos de reports a activities manteniendo la estructura de directorios
            if ($report->content) {
                $sourcePath = public_path('reportes/' . $report->content); // Ruta del archivo existente
                $filePath = $report->content;
                $destinationPath = public_path('activities/' . $filePath); // Ruta donde se guardará la copia

                if (File::exists($sourcePath)) {
                    // Crear directorios necesarios si no existen
                    $destinationDir = dirname($destinationPath);
                    if (!File::exists($destinationDir)) {
                        File::makeDirectory($destinationDir, 0755, true); // true = crear directorios recursivamente
                    }

                    if (File::exists($sourcePath)) {
                        File::copy($sourcePath, $destinationPath);
                        File::delete($sourcePath); 
                        $this->info("Copia con exito del reporte: " . $report->id);
                    }
                }
            }

            // Guardar la nueva actividad
            $activity->save();
            // $report->delete();
        }
    }
}
