<?php

namespace App\Http\Livewire\EffortPoints;

use App\Models\Activity;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EffortPoints extends Component
{
    protected $listeners = ['setDate'];
    // Fecha
    public $dateRange;
    public $startDate, $endDate;
    public $qty = 7;

    public function render()
    {
        if ($this->startDate == null && $this->startDate == null) {
            // Establecer valores iniciales para las fechas en formato YYYY-MM-DD
            // $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d'); // Lunes de esta semana
            // $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d'); // Domingo de esta semana
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d'); // Primer día del mes actual
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último día del mes actual
        }
        
        $reportsSubquery = Report::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as report_points_abierto"),
                DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as report_points_proceso"),
                DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as report_points_conflicto"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as report_points_resuelto")
            )
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->groupBy('delegate_id');

        $activitiesSubquery = Activity::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as activity_points_abierto"),
                DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as activity_points_proceso"),
                DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as activity_points_conflicto"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as activity_points_resuelto")
            )
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->groupBy('delegate_id');

        $points = User::select(
                'users.id',
                'users.name',
                'users.effort_points',
                'report_points_abierto',
                'report_points_proceso',
                'report_points_conflicto',
                'report_points_resuelto',
                'activity_points_abierto',
                'activity_points_proceso',
                'activity_points_conflicto',
                'activity_points_resuelto'
            )
            ->leftJoinSub($reportsSubquery, 'reports', 'users.id', '=', 'reports.delegate_id')
            ->leftJoinSub($activitiesSubquery, 'activities', 'users.id', '=', 'activities.delegate_id')
            ->where('type_user', '!=', 3)
            ->orderBy('name', 'asc')
            ->get();
        
        foreach ($points as $key => $point) {
            // Puntos por terminar
            $points_finish_report = $point->report_points_abierto + $point->report_points_proceso + $point->report_points_conflicto;
            $points_finish_activity = $point->activity_points_abierto + $point->activity_points_proceso + $point->activity_points_conflicto;
            $points_finish = $points_finish_report + $points_finish_activity;
            // Puntos por asignar
            $point->points_assigned = $point->effort_points - $points_finish;
            // Avance porcentaje
            $percentage_progress = $point->report_points_resuelto +  $point->activity_points_resuelto;
            // Evitar división por cero
            if ($point->effort_points != 0) {
                $advance = $percentage_progress / $point->effort_points;
            } else {
                $advance = 0; // O cualquier valor que tenga sentido en tu contexto
            }
            // Obtener la primera palabra del nombre
            $first_name = explode(' ', trim($point->name))[0];
            // Formatear el nombre con el avance
            $point->name_with_advance = $first_name . ' (' . number_format($advance * 100, 2) . '%)';
        }
        // Preparar los datos para el gráfico
        $categories = $points->pluck('name_with_advance')->toArray();
        $series = [
            [
                'name' => 'Resuelto',
                'data' => $points->map(function($point) {
                    return $point->report_points_resuelto + $point->activity_points_resuelto;
                })->toArray(),
            ],
            [
                'name' => 'Proceso',
                'data' => $points->map(function($point) {
                    return $point->report_points_proceso + $point->activity_points_proceso;
                })->toArray(),
            ],
            [
                'name' => 'Conflicto',
                'data' => $points->map(function($point) {
                    return $point->report_points_conflicto + $point->activity_points_conflicto;
                })->toArray(),
            ],
            [
                'name' => 'Abierto',
                'data' => $points->map(function($point) {
                    return $point->report_points_abierto + $point->activity_points_abierto;
                })->toArray(),
            ],
            [
                'name' => 'Asignar',
                'data' => $points->map(function($point) {
                    return $point->points_assigned;
                })->toArray(),
            ]
        ];
        // contar los dias
        $carbon = Carbon::now();
        // Obtener el número de días en el mes actual
        $diasEnElMes = $carbon->daysInMonth;
        $days_filtred = $diasEnElMes;
        return view('livewire.effort-points.effort-points', [
            'categories' => $categories,
            'series' => $series,
            'days_filtred' => $days_filtred,
        ]);
    }

    public function setDate($dateRange)
    {
        // Suponiendo que $dateRange ya contiene las fechas en formato 'YYYY-MM-DD - YYYY-MM-DD'
        $dateRange = explode(' - ', $dateRange);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
        // Calcular el número total de días
        $this->qty = $endDate->diffInDays($startDate) + 1;
        // filtro
        $this->startDate = $startDate->format('Y-m-d');
        $this->endDate = $endDate->format('Y-m-d');
        // input en vista
        $this->dateRange = $dateRange;
        // Emitir un evento para forzar la actualización de la vista
        $this->emit('dateUpdated');
    }
}
