<?php

namespace App\Http\Controllers;

use App\Models\Notion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Test extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Buscar la nota por su ID
        $notion = Notion::findOrFail(2018);
        
        // Formatear la fecha de inicio al formato deseado (Y-m-d H:i:s)
        $startDate = Carbon::parse("2025-02-13T00:00:00.000Z")->format('Y-m-d H:i:s');
        // Inicializar la fecha de fin
        $end = null;
        if ($end == null) {
            // Calcular la diferencia entre las fechas actuales
            $startCurrent = Carbon::parse($notion->start_date);
            $endCurrent = Carbon::parse($notion->end_date);
            $diff = $endCurrent->diff($startCurrent); // Diferencia entre las fechas
            
            // Extraer la hora de $request->start
            $startTime = Carbon::parse("2025-02-13T00:00:00.000Z")->format('H:i:s');

            dd($diff, $startTime);

            if ($startTime == '00:00:00') {
                // Si la hora de inicio es 00:00:00, solo considerar la diferencia de días
                if ($diff->days > 0 || $diff->h > 0 || $diff->i > 0) {
                    // Si hay una diferencia, aplicarla
                    $endDate = Carbon::parse("2025-02-13T00:00:00.000Z")
                        ->addDays($diff->days) // Sumar la diferencia de días
                        ->format('Y-m-d H:i:s');
                        
                } else {
                    // Si hay una diferencia, aplicarla
                    $endDate = Carbon::parse("2025-02-13T00:00:00.000Z")
                        ->addDay() // Agregar un día
                        ->format('Y-m-d H:i:s');
                }
            } else {
                // Si la hora de inicio no es 00:00:00, considerar la diferencia de días y horas
                if ($diff->h > 0 || $diff->i > 0 || $diff->s > 0) {
                    // Si hay una diferencia, aplicarla
                    $endDate = Carbon::parse("2025-02-13T00:00:00.000Z")
                        ->addDays($diff->days) // Sumar la diferencia de días
                        ->addHours($diff->h) // Sumar la diferencia de horas
                        ->addMinutes($diff->i) // Sumar la diferencia de minutos
                        ->addSeconds($diff->s) // Sumar la diferencia de segundos
                        ->addHour() // Sumar 1 hora adicional
                        ->format('Y-m-d H:i:s');
                } else {
                    // Si no hay diferencia, usar la fecha de inicio como fecha de fin y sumar 1 hora
                    $endDate = Carbon::parse($startDate)
                        ->addHour() // Sumar 1 hora adicional
                        ->format('Y-m-d H:i:s');
                }
            }
        } else {
            // Si $request->end no es null, usar la fecha proporcionada
            $endDate = Carbon::parse("2025-02-13T10:00:00.000Z")->format('Y-m-d H:i:s');
        }

        // Actualizar las fechas
        $notion->start_date = $startDate;
        $notion->end_date = $endDate;
        dd($notion);
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
