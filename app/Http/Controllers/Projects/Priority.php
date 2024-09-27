<?php

namespace App\Http\Controllers\Projects;

use App\Models\Project;
use Carbon\Carbon;
use Livewire\Component;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Priority extends Component
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            // Obtén la fecha de hoy y ajusta la semana para que comience el lunes y termine el viernes
            $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endDate = Carbon::now()->endOfWeek(Carbon::FRIDAY);
            // Formatea las fechas como "Semana día - div de Mes"
            $startDateFormatted = $startDate->isoFormat('dddd D ');
            $endDateFormatted = $endDate->isoFormat('dddd D');
            $endMonthFormatted = $endDate->isoFormat('MMMM');
            // Formato final de la semana
            $weekText = "Semana: $startDateFormatted a $endDateFormatted de $endMonthFormatted";
            // Proyectos Activos
            // PRODUCCION
            // $activos = Project::select(
            //     'projects.*',
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.leader = true LIMIT 1) as leader_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.product_owner = true LIMIT 1) as product_owner_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer1 = true LIMIT 1) as developer1_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer2 = true LIMIT 1) as developer2_name"),
            // )
            //     ->where('type', 'Activo')
            //     ->orderBy('priority', 'asc')
            //     ->get();
            // Proyectos soporte
            // $soportes = Project::select(
            //     'projects.*',
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.leader = true LIMIT 1) as leader_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.product_owner = true LIMIT 1) as product_owner_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer1 = true LIMIT 1) as developer1_name"),
            //     DB::raw("(SELECT SUBSTRING_INDEX(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer2 = true LIMIT 1) as developer2_name"),
            // )
                // ->where('type', 'Soporte')
                // ->orderBy('priority', 'asc')
                // ->get();
            // LOCAL
            $activos = Project::select(
                'projects.*',
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.leader = true LIMIT 1) as leader_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.product_owner = true LIMIT 1) as product_owner_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer1 = true LIMIT 1) as developer1_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer2 = true LIMIT 1) as developer2_name")
                )
                ->where('type', 'Activo')
                ->orderBy('priority', 'asc')
                ->get();
            // // Proyectos soporte
            $soportes = Project::select(
                'projects.*',
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.leader = true LIMIT 1) as leader_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.product_owner = true LIMIT 1) as product_owner_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer1 = true LIMIT 1) as developer1_name"),
                DB::raw("(SELECT split_part(name, ' ', 1) FROM users JOIN project_user ON users.id = project_user.user_id WHERE project_user.project_id = projects.id AND project_user.developer2 = true LIMIT 1) as developer2_name")
            )
                ->where('type', 'Soporte')
                ->orderBy('priority', 'asc')
                ->get();

            return view('projects.priority', compact('weekText', 'activos', 'soportes'));
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
