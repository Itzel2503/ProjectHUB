<?php

namespace App\Http\Controllers\ActivitiesReports;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            // Definir las fechas
            $startMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); // Primer día del mes actual
            $endMonth = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último día del mes actual
            // Subconsulta de Reports por mes incluyendo puntos resueltos y los demás estados
            $reportsMonthly = Report::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto', 'Resuelto') THEN points ELSE 0 END) as total_points_reports"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_reports")
            )
                ->where('delegate_id', Auth::id())
                ->where(function ($query) use ($startMonth, $endMonth) {
                    $query
                        ->whereBetween('expected_date', [$startMonth, $endMonth])
                        ->orWhereBetween('progress', [$startMonth, $endMonth])
                        ->orWhereBetween('end_date', [$startMonth, $endMonth]);
                })
                ->groupBy('delegate_id');
            // Subconsulta de Activities por mes incluyendo puntos resueltos y los demás estados
            $activitiesMonthly = Activity::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto', 'Resuelto') THEN points ELSE 0 END) as total_points_activities"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_activities")
            )
                ->where('delegate_id', Auth::id())
                ->where(function ($query) use ($startMonth, $endMonth) {
                    $query
                        ->whereBetween('expected_date', [$startMonth, $endMonth])
                        ->orWhereBetween('progress', [$startMonth, $endMonth])
                        ->orWhereBetween('end_date', [$startMonth, $endMonth]);
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
                ->where('delegate_id', Auth::id())
                ->where(function ($query) use ($startMonth, $endMonth) {
                    $query
                        ->whereBetween('expected_date', [$startMonth, $endMonth])
                        ->orWhereBetween('progress', [$startMonth, $endMonth])
                        ->orWhereBetween('end_date', [$startMonth, $endMonth]);
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
                ->where('delegate_id', Auth::id())
                ->where(function ($query) use ($startMonth, $endMonth) {
                    $query
                        ->whereBetween('expected_date', [$startMonth, $endMonth])
                        ->orWhereBetween('progress', [$startMonth, $endMonth])
                        ->orWhereBetween('end_date', [$startMonth, $endMonth]);
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
                ->where('users.id', Auth::id())
                ->get();

            foreach ($points as $key => $point) {
                // Puntos por terminar
                $points_finish = $point->total_points_reports + $point->total_points_activities;
                // Puntos por asignar
                $points_assigned = $point->effort_points - $points_finish;
                if ($points_assigned < 0) {
                    // Crear el nuevo atributo extrapoints y establecerlo como el valor positivo del número negativo
                    $point->extrapoints = abs($points_assigned);
                    // Asignar el valor de points_assigned al objeto point
                    $point->points_assigned = 0;
                } else {
                    // Si points_assigned no es negativo, asegúrate de que extrapoints esté vacío o nulo
                    $point->extrapoints = 0;
                    // Asignar el valor de points_assigned al objeto point
                    $point->points_assigned = $points_assigned;
                }
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
                    'data' => $points
                        ->map(function ($point) {
                            return $point->report_resuelto + $point->activity_resuelto;
                        })
                        ->toArray(),
                ],
                [
                    'name' => 'Proceso',
                    'data' => $points
                        ->map(function ($point) {
                            return $point->report_proceso + $point->activity_proceso;
                        })
                        ->toArray(),
                ],
                [
                    'name' => 'Conflicto',
                    'data' => $points
                        ->map(function ($point) {
                            return $point->report_conflicto + $point->activity_conflicto;
                        })
                        ->toArray(),
                ],
                [
                    'name' => 'Abierto',
                    'data' => $points
                        ->map(function ($point) {
                            return $point->report_abierto + $point->activity_abierto;
                        })
                        ->toArray(),
                ],
                [
                    'name' => 'Asignar',
                    'data' => $points
                        ->map(function ($point) {
                            return $point->points_assigned;
                        })
                        ->toArray(),
                ],
                [
                    'name' => 'Extras',
                    'data' => $points
                        ->map(function ($point) {
                            return $point->extrapoints;
                        })
                        ->toArray(),
                ],
            ];
            // Preparar los datos para el gráfico
            $categories = $points
                ->map(function ($point) {
                    return $point->name_with_advance;
                })
                ->toArray();

            $totalEffortPoints = $points
                ->map(function ($point) {
                    return $point->total_effort_points;
                })
                ->toArray();
            return view('activitiesreports.activitiesreports', compact('categories', 'series', 'totalEffortPoints'));
        } else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
