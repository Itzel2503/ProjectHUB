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
    public $startDate, $endDate, $starMonth, $endMonth;
    public $qty = 7;

    public function render()
    {
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d'); // Lunes de esta semana
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d'); // Domingo de esta semana

        $this->starMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); // Primer día del mes actual
        $this->endMonth = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último día del mes actual
        // Subconsulta de Reports por mes incluyendo puntos resueltos y los demás estados
        $reportsMonthly = Report::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto') THEN points ELSE 0 END) as total_points_reports"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_reports")
        )
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');

        // Subconsulta de Activities por mes incluyendo puntos resueltos y los demás estados
        $activitiesMonthly = Activity::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto') THEN points ELSE 0 END) as total_points_activities"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_activities")
        )
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');

        // Subconsulta de Reports por semana
        $reportsWeekly = Report::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as report_abierto"),
            DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as report_proceso"),
            DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as report_conflicto"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as report_resuelto")
        )
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->startDate, $this->endDate])
                    ->orWhereBetween('expected_date', [$this->startDate, $this->endDate]);
            })
            ->groupBy('delegate_id');
        // Subconsulta de Activities por semana
        $activitiesWeekly = Activity::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as activity_abierto"),
            DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as activity_proceso"),
            DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as activity_conflicto"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as activity_resuelto")
        )
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->startDate, $this->endDate])
                    ->orWhereBetween('expected_date', [$this->startDate, $this->endDate]);
            })
            ->groupBy('delegate_id');
        // Consulta principal unificada
        $points = User::select(
            'users.id',
            'users.name',
            'users.effort_points',
            'reports_weekly.report_abierto',
            'reports_weekly.report_proceso',
            'reports_weekly.report_conflicto',
            'reports_weekly.report_resuelto',
            'activities_weekly.activity_abierto',
            'activities_weekly.activity_proceso',
            'activities_weekly.activity_conflicto',
            'activities_weekly.activity_resuelto',

            'reports_monthly.total_points_reports',
            'reports_monthly.total_resuelto_reports',
            'activities_monthly.total_points_activities',
            'activities_monthly.total_resuelto_activities'
        )
            ->leftJoinSub($reportsWeekly, 'reports_weekly', 'users.id', '=', 'reports_weekly.delegate_id')
            ->leftJoinSub($activitiesWeekly, 'activities_weekly', 'users.id', '=', 'activities_weekly.delegate_id')
            ->leftJoinSub($reportsMonthly, 'reports_monthly', 'users.id', '=', 'reports_monthly.delegate_id')
            ->leftJoinSub($activitiesMonthly, 'activities_monthly', 'users.id', '=', 'activities_monthly.delegate_id')
            ->where('type_user', '!=', 3)
            ->orderBy('name', 'asc')
            ->get();

        foreach ($points as $key => $point) {
            // Puntos por terminar
            $points_finish = $point->total_points_reports + $point->total_points_activities;
            // Puntos por asignar
            $point->points_assigned = $point->effort_points - $points_finish;
            // Avance porcentaje usando la suma de los puntos resueltos del mes
            $total_resuelto_monthly = $point->total_resuelto_reports + $point->total_resuelto_activities;
            // Evitar división por cero
            if ($point->effort_points != 0) {
                $advance = $total_resuelto_monthly / $point->effort_points;
            } else {
                $advance = 0; // O cualquier valor que tenga sentido en tu contexto
            }
            // Obtener la primera palabra del nombre
            $first_name = explode(' ', trim($point->name))[0];
            // Formatear el nombre con el avance
            $point->name_with_advance = $first_name . ' (' . number_format($advance * 100, 2) . '%)';
            // Asignar effort_points directamente
            $point->total_effort_points = $point->effort_points;
        }

        $series = [
            [
                'name' => 'Resuelto',
                'data' => $points->map(function ($point) {
                    return $point->report_resuelto + $point->activity_resuelto;
                })->toArray(),
            ],
            [
                'name' => 'Proceso',
                'data' => $points->map(function ($point) {
                    return $point->report_proceso + $point->activity_proceso;
                })->toArray(),
            ],
            [
                'name' => 'Conflicto',
                'data' => $points->map(function ($point) {
                    return $point->report_conflicto + $point->activity_conflicto;
                })->toArray(),
            ],
            [
                'name' => 'Abierto',
                'data' => $points->map(function ($point) {
                    return $point->report_abierto + $point->activity_abierto;
                })->toArray(),
            ],
            [
                'name' => 'Asignar',
                'data' => $points->map(function ($point) {
                    return $point->points_assigned;
                })->toArray(),
            ]
        ];
        // Preparar los datos para el gráfico
        $categories = $points->map(function ($point) {
            return $point->name_with_advance;
        })->toArray();

        $totalEffortPoints = $points->map(function ($point) {
            return $point->total_effort_points;
        })->toArray();

        return view('livewire.effort-points.effort-points', [
            'categories' => $categories,
            'series' => $series,
            'totalEffortPoints' => $totalEffortPoints,
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
        // Actualizar el rango de fechas
        $this->dateRange = $dateRange;
        // Forzar actualización de la vista
        $this->emit('updateChart');
    }
}
