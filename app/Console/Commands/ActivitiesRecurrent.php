<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\ActivityRecurrent;
use App\Models\ErrorLog;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class ActivitiesRecurrent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:activitiesRecurrent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para crear las actividades recurrentes sin fecha límite.';

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
        $activitiesRecurrent = ActivityRecurrent::where('end_date', null)->get();

        if ($activitiesRecurrent->isEmpty()) {
            $this->info("No hay actividades recurrentes encontradas.");
            return;
        }

        foreach ($activitiesRecurrent as $activityRecurrent) {
            $this->info("Procesando actividad recurrente con ID: " . $activityRecurrent->id);

            $lastDate = Carbon::parse($activityRecurrent->last_date)->startOfDay(); // Solo toma la fecha (sin hora)
            $today = Carbon::now()->startOfDay(); // Fecha actual (sin hora)
            $oneWeekBefore = Carbon::now()->addWeek()->startOfDay(); // Fecha dentro de una semana (sin hora)
            $oneMonthBefore = Carbon::now()->addMonth()->startOfDay(); // Fecha dentro de un mes (sin hora)

            $this->info("lastDate: {$lastDate->toDateString()}, today: {$today->toDateString()}, oneWeekBefore: {$oneWeekBefore->toDateString()}, oneMonthBefore: {$oneMonthBefore->toDateString()}");

            // Verijfica si lastDate es hoy o el mismo día que oneWeekBefore/oneMonthBefore
            if ($activityRecurrent->frequency == 'Dairy' && ($lastDate->isSameDay($today) || $lastDate->isSameDay($oneWeekBefore))) {
                $this->createDailyRepetitions($activityRecurrent); 
                $this->info('Dairy');
            } elseif ($activityRecurrent->frequency == 'Weekly' && ($lastDate->isSameDay($today) || $lastDate->isSameDay($oneWeekBefore))) {
                $this->createWeeklyRepetitions($activityRecurrent); 
                $this->info('Weekly');
            } elseif ($activityRecurrent->frequency == 'Monthly' && ($lastDate->isSameDay($today) || $lastDate->isSameDay($oneMonthBefore))) {
                $this->createMonthlyRepetitions($activityRecurrent);
                $this->info('Monthly');
            } elseif ($activityRecurrent->frequency == 'Yearly' && ($lastDate->isSameDay($today) || $lastDate->isSameDay($oneMonthBefore))) {
                $this->createYearRepetitions($activityRecurrent); 
                $this->info('Yearly');
            }
        }
    }

    private function createDailyRepetitions($activityRecurrent)
    {
        try {
            $task = Activity::where('activity_repeat', $activityRecurrent->activity_repeat)->get()->last();

            $startDate = Carbon::now();
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addDay() : null;
            $deadline = $activityRecurrent->end_date ? Carbon::parse($activityRecurrent->end_date)->addDay() : null;

            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInDays($deadline) : Carbon::now()->diffInDays(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            for ($i = 0; $i < $daysDifference; $i++) {
                $events[] = [
                    'sprint_id' => $task->sprint_id,
                    'user_id' => $task->user_id,
                    'delegate_id' => $task->delegate_id,
                    'icon' => $task->icon,
                    'title' => $task->title,
                    'content' => $task->content,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'state' => $task->state,
                    'points' => $task->points,
                    'questions_points' => $task->questions_points,
                    'activity_repeat' => $activityRepeat,
                    'delegated_date' => $task->delegated_date,
                    'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $tempStartDate->modify('+1 day');  
                // Avanzar un día sin modificar el original
                if ($tempExpectedDate) {
                    $tempExpectedDate->modify('+1 day');  
                }
            }
            // Restar una semana al resultado final
            $tempExpectedDate->modify('-1 day');
            // Inserción masiva en lotes si es necesario
            foreach (array_chunk($events, 500) as $batch) {
                Activity::insert($batch);
            }
            
            // Guardar información de recurrencia
            $activityRecurrentUpdated = ActivityRecurrent::where('activity_repeat', $task->activity_repeat)->first();
            $activityRecurrentUpdated->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrentUpdated->save();

            Log::create([
                'user_id' => 0,
                'activity_id' => $task->id,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades diarias',
                'message' => 'Actividades diarias creadas',
                'details' => 'Actividad recurrente: ' . $activityRecurrent->activity_repeat,
            ]);
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => 0,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades diarias',
                'message' => 'Error al crear actividades diarias',
                'details' => $e->getMessage(),
            ]);
        }
    }


    private function createWeeklyRepetitions($activityRecurrent)
    {
        try {
            $task = Activity::where('activity_repeat', $activityRecurrent->activity_repeat)->get()->last();

            $startDate = Carbon::now();
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addWeek() : null;
            $deadline = $activityRecurrent->end_date ? Carbon::parse($activityRecurrent->end_date)->addDay() : null;
            
            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInWeeks($deadline) + 1 : Carbon::now()->diffInWeeks(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            for ($i = 0; $i < $daysDifference; $i++) {
                $events[] = [
                    'sprint_id' => $task->sprint_id,
                    'user_id' => $task->user_id,
                    'delegate_id' => $task->delegate_id,
                    'icon' => $task->icon,
                    'title' => $task->title,
                    'content' => $task->content,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'state' => $task->state,
                    'points' => $task->points,
                    'questions_points' => $task->questions_points,
                    'activity_repeat' => $activityRepeat,
                    'delegated_date' => $task->delegated_date,
                    'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                $tempStartDate->modify('+1 week');  
                // Avanzar un día sin modificar el original
                if ($tempExpectedDate) {
                    $tempExpectedDate->modify('+1 week');  
                }
            }
            // Restar una semana al resultado final
            $tempExpectedDate->modify('-1 week');

            // Inserción masiva en lotes si es necesario
            foreach (array_chunk($events, 500) as $batch) {
                Activity::insert($batch);
            }

            // Guardar información de recurrencia
            $activityRecurrentUpdated = ActivityRecurrent::where('activity_repeat', $task->activity_repeat)->first();
            $activityRecurrentUpdated->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrentUpdated->save();

            Log::create([
                'user_id' => 0,
                'activity_id' => $task->id,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades semanales',
                'message' => 'Actividades semanales creadas',
                'details' => 'Actividad recurrente: ' . $activityRecurrent->activity_repeat,
            ]);
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => 0,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades semanales',
                'message' => 'Error al crear actividades semanales',
                'details' => $e->getMessage(),
            ]);
        }
    }

    private function createMonthlyRepetitions($activityRecurrent)
    {
        try {
            $task = Activity::where('activity_repeat', $activityRecurrent->activity_repeat)->get()->last();

            $startDate = Carbon::now();
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addMonth() : null;
            $deadline = $activityRecurrent->end_date ? Carbon::parse($activityRecurrent->end_date)->addDay() : null;

            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInMonths($deadline) + 1 : Carbon::now()->diffInMonths(Carbon::now()->addMonths(3));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            for ($i = 0; $i < $daysDifference; $i++) {
                $events[] = [
                    'sprint_id' => $task->sprint_id,
                    'user_id' => $task->user_id,
                    'delegate_id' => $task->delegate_id,
                    'icon' => $task->icon,
                    'title' => $task->title,
                    'content' => $task->content,
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'state' => $task->state,
                    'points' => $task->points,
                    'questions_points' => $task->questions_points,
                    'activity_repeat' => $activityRepeat,
                    'delegated_date' => $task->delegated_date,
                    'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                    'created_at' => $startDate,
                    'updated_at' => $startDate,
                ];

                // Avanzar un día sin modificar el original
                $tempStartDate->modify('+1 month');
                if ($tempExpectedDate) {
                    $tempExpectedDate->modify('+1 month');
                }
            }
            // Restar una semana al resultado final
            $tempExpectedDate->modify('-1 month');

            // Inserción masiva en lotes si es necesario
            foreach (array_chunk($events, 500) as $batch) {
                Activity::insert($batch);
            }

            // Guardar información de recurrencia
            $activityRecurrentUpdated = ActivityRecurrent::where('activity_repeat', $task->activity_repeat)->first();
            $activityRecurrentUpdated->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrentUpdated->save();

            Log::create([
                'user_id' => 0,
                'activity_id' => $task->id,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades mensuales',
                'message' => 'Actividades mensuales creadas',
                'details' => 'Actividad recurrente: ' . $activityRecurrent->activity_repeat,
            ]);
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => 0,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades mensuales',
                'message' => 'Error al crear actividades mensuales',
                'details' => $e->getMessage(),
            ]);
        }
    }

    private function createYearRepetitions($activityRecurrent)
    {
        try {
            $task = Activity::where('activity_repeat', $activityRecurrent->activity_repeat)->get()->last();

            $startDate = Carbon::now();
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addYear() : null;
            
            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            $activity = new Activity();
            $activity->sprint_id = $task->sprint_id;
            $activity->user_id = $task->user_id;
            $activity->delegate_id = $task->delegate_id;
            $activity->icon = $task->icon;
            $activity->title = $task->title;
            $activity->content = $task->content;
            $activity->description = $task->description;
            $activity->priority = $task->priority;
            $activity->state = $task->state;
            $activity->points = $task->points;
            $activity->questions_points = $task->questions_points;
            $activity->activity_repeat = $activityRepeat;
            $activity->delegated_date = $startDate;
            $activity->expected_date = $expectedDate;
            $activity->created_at = $startDate;
            $activity->updated_at = $startDate;
            $activity->save();

            // Guardar información de recurrencia
            $activityRecurrentUpdated = ActivityRecurrent::where('activity_repeat', $task->activity_repeat)->first();
            $activityRecurrentUpdated->last_date = $expectedDate; // Última fecha generada
            $activityRecurrentUpdated->save();

            Log::create([
                'user_id' => 0,
                'activity_id' => $task->id,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades anuales',
                'message' => 'Actividades anuales creadas',
                'details' => 'Actividad recurrente: ' . $activityRecurrent->activity_repeat,
            ]);
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => 0,
                'view' => 'app/Console/Commands/ActivitiesRecurrent',
                'action' => 'Comando de creación de actividades anuales',
                'message' => 'Error al crear actividades anuales',
                'details' => $e->getMessage(),
            ]);
        }
    }
}
