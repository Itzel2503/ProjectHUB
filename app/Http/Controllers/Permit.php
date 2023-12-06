<?php

namespace App\Http\Controllers;

use App\Models\HomeOffice;
use App\Models\OutOfOfficeHours;
use App\Models\Permit as Permits;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permit extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            // MODAL CREATE
            $permits = Permits::all();
            
            //MODAL PERMIT1 Y PERMIT2
            $motiveOptions = [
                HomeOffice::FAMILY => 'Situación familiar',
                HomeOffice::PERSONAL => 'Situación personal',
                HomeOffice::DISEASE => 'Enfermedad',
                HomeOffice::MEDICAL => 'Trámites médicos',
                HomeOffice::LEGAL => 'Trámites legales',
                HomeOffice::OTHER => 'Otro',
            ];

            //MODAL PERMIT2
            $typeHours = [
                OutOfOfficeHours::LATE => 'Llegada tarde',
                OutOfOfficeHours::EARLY => 'Salida tamprano',
                OutOfOfficeHours::BETWEEN => 'Horas entre turno',
            ];
            
            $users = User::all();
            foreach ($users as $key => $user) {
                if ($user->id === Auth::user()->id) {
                    unset($users[$key]);
                }
            }

            $takeHours = [];
            for ($i=0; $i < 7; $i++) { 
                $takeHours[$i] = $i + 1;
            }
            //MODAL PERMIT3
            //MODAL PERMIT4
            //MODAL PERMIT5

            return view('activitycontrol.permits.calendar', compact('permits', 'motiveOptions', 'users', 'typeHours','takeHours'));
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
