<?php

namespace App\Http\Controllers\Notion;

use App\Http\Controllers\Controller;
use App\Models\Notion as ModelsNotion;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Notion extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
            $projects = Project::all();
            $notios = ModelsNotion::all();

            return view('notion.notion', compact('projects', 'allUsers', 'notios'));
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
        if (Auth::check()) {
            try {
                // Validación de los campos
                $validatedData = $request->validate([
                    'title' => 'required',
                    'date' => 'required',
                ]);
                // Aquí puedes continuar con tu lógica después de la validación exitosa
            } catch (\Illuminate\Validation\ValidationException $e) {
                return redirect()->back()->with('error', 'Faltan campos o campos incorrectos.');
                throw $e;
            }

            $notion = new ModelsNotion();
            $now = Carbon::now();

            // Buscar el ícono seleccionado en las claves del request
            $icon = collect($request->all())->keys()->first(function ($key) {
                return in_array($key, ["🚀", "💡", "🔐", "💵", "☠️", "📎", "📆", "📝", "📩", "💬", "⭐"]);
            });
            // Asignar el ícono seleccionado
            $notion->icon = $icon;
            $notion->title = $request->title;
            if ($request->priority1) {
                $notion->priority = 'Alto';
            } else if ($request->priority2) {
                $notion->priority = 'Medio';
            } else {
                $notion->priority = 'Bajo';
            }
            $notion->date = $request->date;
            $notion->status = 'Abierto';
            $notion->finish = false;
            $notion->repeat = $request->repeat;
            $notion->project_id = $request->project_id;
            $notion->save();

            // Guardar usuarios relacionados en la tabla pivote
            if ($request->has('user_id') && is_array($request->user_id)) {
                $notion->delegate()->attach($request->user_id);
            }

            return redirect()->back()->with('success', 'Nota creada con éxito!');
        } else {
            return redirect('/login');
        }
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
