<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Report as ModelsReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Report extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        if (Auth::check()) {
            $project = Project::find($project_id);

            if ($project) {
                return view('projects.reports', compact('project'));
            } else {
                return redirect('/projects');
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
            $project = Project::find($project_id);

            if ($project) {
                return view('projects.newreport', compact('project', 'user', 'allUsers'));
            } else {
                return redirect('/projects');
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        if (Auth::check()) {
            $project = Project::find($project_id);
            $report = new ModelsReport();
            $now = Carbon::now();
            $dateString = $now->format("Y-m-d H_i_s");
            if ($project && $report) {
                if (Auth::user()->type_user != 3) {
                    try {
                        // Validación de los campos
                        $validatedData = $request->validate([
                            'delegate' => 'required|not_in:0',
                        ]);
                        // Aquí puedes continuar con tu lógica después de la validación exitosa
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        return redirect()->back()->with('error', 'Faltan campos o campos incorrectos.');
                        throw $e;
                    }
                }

                if (isset($request->video)) {
                    $report->project_id = $project_id;
                    $report->user_id = $request->user_id;
                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $report->delegate_id = 10;
                        $report->expected_date = Carbon::now();
                        $report->updated_expected_date = false;
                        $report->points = 0;
                    } else {
                        $report->delegate_id = $request->delegate;
                        $report->expected_date = $request->expected_date;
                        $report->points = $request->points;
                    }
                    $report->title = $request->title;
                    $report->content = $request->video;

                    if ($request->priority1) {
                        $report->priority = 'Alto';
                    } else if ($request->priority2) {
                        $report->priority = 'Medio';
                    } else {
                        $report->priority = 'Bajo';
                    }

                    $report->state = "Abierto";
                    $report->comment = $request->comment;
                    $report->evidence = ($request->evidence) ? true : false;
                    $report->image = false;
                    $report->video = true;
                    $report->file = false;
                    $report->delegated_date = Carbon::now();
                    $report->save();
                }

                if (isset($request->photo)) {
                    list($type, $data) = explode(';', $request->photo);
                    list(, $data)      = explode(',', $data);
                    $imageData = base64_decode($data);

                    $fileName = 'Reporte ' . $project->name . ', ' . $dateString . '.jpg';
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                    Storage::disk('reports')->put($filePath, $imageData);

                    $report->project_id = $project_id;
                    $report->user_id = $request->user_id;
                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $report->delegate_id = 10;
                        $report->expected_date = Carbon::now();
                        $report->updated_expected_date = false;
                        $report->points = 0;
                    } else {
                        $report->delegate_id = $request->delegate;
                        $report->expected_date = $request->expected_date;
                        $report->points = $request->points;
                    }
                    $report->title = $request->title;
                    $report->content = $filePath;
                    if ($request->priority1) {
                        $report->priority = 'Alto';
                    } else if ($request->priority2) {
                        $report->priority = 'Medio';
                    } else {
                        $report->priority = 'Bajo';
                    }
                    $report->state = "Abierto";
                    $report->comment = $request->comment;
                    $report->evidence = ($request->evidence) ? true : false;
                    $report->image = true;
                    $report->video = false;
                    $report->file = false;
                    $report->delegated_date = Carbon::now();
                    $report->save();
                }

                if (isset($request->file)) {
                    $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                    $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                    $file = $request->file('file');
                    $fileExtension = $file->extension();
                    $fileName = 'Reporte ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                    Storage::disk('reports')->put($filePath, file_get_contents($file));

                    $report->project_id = $project_id;
                    $report->user_id = $request->user_id;
                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $report->delegate_id = 10;
                        $report->expected_date = Carbon::now();
                        $report->updated_expected_date = false;
                        $report->points = 0;
                    } else {
                        $report->delegate_id = $request->delegate;
                        $report->expected_date = $request->expected_date;
                        $report->points = $request->points;
                    }
                    $report->title = $request->title;
                    $report->content = $filePath;

                    if ($request->priority1) {
                        $report->priority = 'Alto';
                    } else if ($request->priority2) {
                        $report->priority = 'Medio';
                    } else {
                        $report->priority = 'Bajo';
                    }

                    $report->state = "Abierto";
                    $report->comment = $request->comment;
                    $report->evidence = ($request->evidence) ? true : false;

                    if (in_array($file->extension(), $extensionesImagen)) {
                        $report->image = true;
                        $report->video = false;
                        $report->file = false;
                    } elseif (in_array($file->extension(), $extensionesVideo)) {
                        $report->image = false;
                        $report->video = true;
                        $report->file = false;
                    } else {
                        $report->image = false;
                        $report->video = false;
                        $report->file = true;
                    }

                    $report->delegated_date = Carbon::now();
                    $report->save();
                }

                if (!isset($request->video) && !isset($request->photo) && !isset($request->file)) {
                    $report->project_id = $project_id;
                    $report->user_id = $request->user_id;
                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $report->delegate_id = 10;
                        $report->expected_date = Carbon::now();
                        $report->updated_expected_date = false;
                        $report->points = 0;
                    } else {
                        $report->delegate_id = $request->delegate;
                        $report->expected_date = $request->expected_date;
                        $report->points = $request->points;
                    }
                    $report->title = $request->title;

                    if ($request->priority1) {
                        $report->priority = 'Alto';
                    } else if ($request->priority2) {
                        $report->priority = 'Medio';
                    } else {
                        $report->priority = 'Bajo';
                    }

                    $report->state = "Abierto";
                    $report->comment = $request->comment;
                    $report->evidence = ($request->evidence) ? true : false;
                    $report->image = false;
                    $report->video = false;
                    $report->file = false;
                    $report->delegated_date = Carbon::now();
                    $report->save();
                }
                return redirect()->route('projects.reports.index', ['project' => $project_id]);
            } else {
                return redirect('/projects');
            }
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
    public function show($project_id, $report_id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
            $project = Project::find($project_id);
            $report = ModelsReport::find($report_id);

            if ($project && $report) {
                // Filtrar la colección para eliminar el usuario que coincide con delegate_id
                $filteredUsers = $allUsers->reject(function ($user) use ($report) {
                    return $user->id == $report->delegate_id;
                });
                $expectedDate = Carbon::parse($report->expected_date)->toDateString();
                
                return view('projects.clonereport', compact('project', 'user', 'filteredUsers', 'report', 'expectedDate'));
            } else {
                return redirect('/projects');
            }
        } else {
            return redirect('/login');
        }
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
    public function update(Request $request, $project_id, $id)
    {
        if (Auth::check()) {
            $project = Project::find($project_id);
            $report = ModelsReport::find($id);
            $reportNew = new ModelsReport();
            $now = Carbon::now();
            $dateString = $now->format("Y-m-d H_i_s");
            
            if ($project && $report) {
                try {
                    // Validación de los campos
                    $validatedData = $request->validate([
                        'comment' => 'required',
                    ]);
                    // Aquí puedes continuar con tu lógica después de la validación exitosa
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return redirect()->back()->with('error', 'Faltan campos o campos incorrectos');

                    throw $e;
                }
                if ($report->report_id == null) {
                    $report->report_id = $id;
                    $report->state = "Resuelto";
                    $report->save();
                }
                $report->repeat = false;
                $report->save();
                if (isset($request->video)) {
                    $reportNew->project_id = $project_id;
                    $reportNew->user_id = $request->user_id;

                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $reportNew->delegate_id = 10;
                        $reportNew->expected_date = Carbon::now();
                        $reportNew->points = 0;
                    } else {
                        $reportNew->delegate_id = $request->delegate;
                        $reportNew->expected_date = $request->expected_date;
                        $reportNew->points = $request->points;
                    }
                    
                    $reportNew->report_id = $report->report_id;
                    $reportNew->title = $report->title;
                    $reportNew->content = $request->video;
                    if ($request->priority1) {
                        $reportNew->priority = 'Alto';
                    } else if ($request->priority2) {
                        $reportNew->priority = 'Medio';
                    } else {
                        $reportNew->priority = 'Bajo';
                    }
                    $reportNew->state = "Abierto";
                    $reportNew->comment = $request->comment;
                    $reportNew->evidence = $request->evidence ?? $report->evidence;
                    $reportNew->image = false;
                    $reportNew->video = true;
                    $reportNew->file = false;
                    if ($reportNew->count == null) {
                        $reportNew->count = 1;
                    } else {
                        $reportNew->count = $report->count + 1;
                    }
                    $reportNew->repeat = true;
                    $reportNew->delegated_date = Carbon::now();
                    $reportNew->save();
                }

                if (isset($request->photo)) {
                    list($type, $data) = explode(';', $request->photo);
                    list(, $data)      = explode(',', $data);
                    $imageData = base64_decode($data);

                    $fileName = 'Reporte ' . $project->name . ', ' . $dateString . '.jpg';
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                    Storage::disk('reports')->put($filePath, $imageData);

                    $reportNew->project_id = $project_id;
                    $reportNew->user_id = $request->user_id;

                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $reportNew->delegate_id = 10;
                        $reportNew->expected_date = Carbon::now();
                        $reportNew->points = 0;
                    } else {
                        $reportNew->delegate_id = $request->delegate;
                        $reportNew->expected_date = $request->expected_date;
                        $reportNew->points = $request->points;
                    }

                    $reportNew->report_id = $report->report_id;
                    $reportNew->title = $report->title;
                    $reportNew->content = $filePath;
                    if ($request->priority1) {
                        $reportNew->priority = 'Alto';
                    } else if ($request->priority2) {
                        $reportNew->priority = 'Medio';
                    } else {
                        $reportNew->priority = 'Bajo';
                    }
                    $reportNew->state = "Abierto";
                    $reportNew->comment = $request->comment;
                    $reportNew->evidence = $request->evidence ?? $report->evidence;
                    $reportNew->image = true;
                    $reportNew->video = false;
                    $reportNew->file = false;
                    if ($reportNew->count == null) {
                        $reportNew->count = 1;
                    } else {
                        $reportNew->count = $report->count + 1;
                    }
                    $reportNew->repeat = true;
                    $reportNew->delegated_date = Carbon::now();
                    $reportNew->save();
                }

                if (isset($request->file)) {
                    $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                    $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                    $file = $request->file('file');
                    $fileExtension = $file->extension();
                    $fileName = 'Reporte ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                    Storage::disk('reports')->put($filePath, file_get_contents($file));

                    $reportNew->project_id = $project_id;
                    $reportNew->user_id = $request->user_id;

                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $reportNew->delegate_id = 10;
                        $reportNew->expected_date = Carbon::now();
                        $reportNew->points = 0;
                    } else {
                        $reportNew->delegate_id = $request->delegate;
                        $reportNew->expected_date = $request->expected_date;
                        $reportNew->points = $request->points;
                    }

                    $reportNew->report_id = $report->report_id;
                    $reportNew->title = $report->title;
                    $reportNew->content = $filePath;
                    if ($request->priority1) {
                        $reportNew->priority = 'Alto';
                    } else if ($request->priority2) {
                        $reportNew->priority = 'Medio';
                    } else {
                        $reportNew->priority = 'Bajo';
                    }
                    $reportNew->state = "Abierto";
                    $reportNew->comment = $request->comment;
                    $reportNew->evidence = $request->evidence ?? $report->evidence;

                    if (in_array($fileExtension, $extensionesImagen)) {
                        $reportNew->image = true;
                        $reportNew->video = false;
                        $reportNew->file = false;
                    } elseif (in_array($fileExtension, $extensionesVideo)) {
                        $reportNew->image = false;
                        $reportNew->video = true;
                        $reportNew->file = false;
                    } else {
                        $reportNew->image = false;
                        $reportNew->video = false;
                        $reportNew->file = true;
                    }

                    if ($report->count == null) {
                        $reportNew->count = 1;
                    } else {
                        $reportNew->count = $report->count + 1;
                    }

                    if ($reportNew->count == null) {
                        $reportNew->count = 1;
                    } else {
                        $reportNew->count = $report->count + 1;
                    }
                    $reportNew->repeat = true;
                    $reportNew->delegated_date = Carbon::now();
                    $reportNew->save();
                }

                if (!isset($request->video) && !isset($request->photo) && !isset($request->file)) {
                    $reportNew->project_id = $project_id;
                    $reportNew->user_id = $request->user_id;

                    if (Auth::user()->type_user == 3) {
                        // En produccion es usuario Soporte, Daniel Rodriguez§
                        $reportNew->delegate_id = 10;
                        $reportNew->expected_date = Carbon::now();
                        $reportNew->points = 0;
                    } else {
                        $reportNew->delegate_id = $request->delegate;
                        $reportNew->expected_date = $request->expected_date;
                        $reportNew->points = $request->points;
                    }
                    
                    $reportNew->report_id = $report->report_id;
                    $reportNew->title = $report->title;

                    $currentYear = date("Y");
                    $startsWithYear = strpos($report->content, $currentYear) === 0;
                    $startsWithReporte = strpos($report->content, "Reporte") === 0;

                    if ($startsWithYear) {
                        // $report->content comienza con el año actual
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                        $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                        $sourcePath = public_path('reportes/' . $report->content); // Ruta del archivo existente
                        $pathInfo = pathinfo($sourcePath); // Obtener información sobre la ruta del archivo
                        $fileExtension = $pathInfo['extension']; // Obtener la extensión del archivo

                        $fileName = 'Reporte ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                        $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;

                        $destinationPath = public_path('reportes/' . $filePath); // Ruta donde se guardará la copia

                        if (File::exists($sourcePath)) {
                            File::copy($sourcePath, $destinationPath);
                        }
                        $reportNew->content = $filePath;
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $reportNew->image = true;
                            $reportNew->video = false;
                            $reportNew->file = false;
                        } elseif (in_array($fileExtension, $extensionesVideo)) {
                            $reportNew->image = false;
                            $reportNew->video = true;
                            $reportNew->file = false;
                        } else {
                            $reportNew->image = false;
                            $reportNew->video = false;
                            $reportNew->file = true;
                        }
                    } elseif ($startsWithReporte) {
                        // $report->content comienza con "Reporte"
                        $reportNew->content = $report->content;
                        $reportNew->image = false;
                        $reportNew->video = true;
                        $reportNew->file = false;
                    } else {
                        $reportNew->image = false;
                        $reportNew->video = false;
                        $reportNew->file = false;
                    }
                    
                    if ($request->priority1) {
                        $reportNew->priority = 'Alto';
                    } else if ($request->priority2) {
                        $reportNew->priority = 'Medio';
                    } else {
                        $reportNew->priority = 'Bajo';
                    }
                    $reportNew->state = "Abierto";
                    $reportNew->comment = $request->comment;
                    $reportNew->evidence = $request->evidence ?? $report->evidence;
                    if ($report->count == null) {
                        $reportNew->count = 1;
                    } else {
                        $reportNew->count = $report->count + 1;
                    }
                    $reportNew->repeat = true;
                    $reportNew->delegated_date = Carbon::now();
                    $reportNew->save();
                }

                return redirect()->route('projects.reports.index', ['project' => $project_id]);
            } else {
                return redirect('/projects');
            }
        } else {
            return redirect('/login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        // 
    }
}
