<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Project;
use App\Models\Report as ModelsReport;
use App\Models\ReportFiles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isNull;

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
                return view('projects.reports.reports', compact('project'));
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
                return view('projects.reports.newreport', compact('project', 'user', 'allUsers'));
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
            $dateString = $now->format("Y-m-d_H_i_s");

            if ($project && $report) {
                try {
                    if (Auth::user()->type_user != 3) {
                        // Validación de los campos
                        $validatedData = $request->validate([
                            'delegate' => 'required|not_in:0',
                        ]);
                    }

                    $projectName = Str::slug($project->name, '_');

                    $report->project_id = $project_id;
                    $report->user_id = $request->user_id;

                    if (Auth::user()->type_user == 3) {
                        // Usuario Soporte
                        /* $userSoporte = User::where('area_id', '4')->first();
                        if ($userSoporte) {
                            $delegate_id = $userSoporte->id;
                        } else {
                            // Usuario administradors
                            $userSoporte = User::where('area_id', '1')->first();
                            $delegate_id = $userSoporte->id;
                        } */
                        $report->delegate_id =  10;
                        $report->expected_date = null;
                        $report->updated_expected_date = false;
                    } else {
                        $report->delegate_id = $request->delegate;
                        $report->expected_date = ($request->expected_date) ? $request->expected_date : null;
                        $userSoporte = null;
                    }
                    $report->icon = ($request->icon) ? $request->icon : null;
                    $report->title = $request->title;

                    if ($request->priority1) {
                        $report->priority = 'Alto';
                    } else if ($request->priority2) {
                        $report->priority = 'Medio';
                    } else {
                        $report->priority = 'Bajo';
                    }

                    $report->state = "Abierto";
                    $report->description = $request->description;
                    $report->evidence = ($request->evidence) ? true : false;
                    $report->points = $request->points ?? 0;
                    // Crear un array asociativo con los valores
                    $questionsPriority = [
                        'pointKnow' => $request->pointKnow,
                        'pointMany' => $request->pointMany,
                        'pointEffort' => $request->pointEffort
                    ];
                    // Convertir el array a JSON
                    $questionsPriorityJson = json_encode($questionsPriority);
                    // Asignar y guardar 
                    $report->questions_points = $questionsPriorityJson;
                    $report->delegated_date = Carbon::now();
                    $report->save();

                    if (!$report) {
                        // Redirigir con un mensaje de error
                        return redirect()->back()->with('error', 'Error al guardar el reporte.');
                    }

                    if (isset($request->photo)) {
                        list($type, $data) = explode(';', $request->photo);
                        list(, $data)      = explode(',', $data);
                        $imageData = base64_decode($data);
                        // Sanitizar nombres de archivo y directorios
                        $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.jpg';
                        $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                        Storage::disk('reports')->put($filePath, $imageData);

                        $reportFile = new ReportFiles();
                        $reportFile->report_id = $report->id;
                        $reportFile->route = $filePath;
                        $reportFile->image = true;
                        $reportFile->save();
                    }

                    if ($request->hasFile('files')) {
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                        $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                        foreach ($request->file('files') as $file) {
                            $fileExtension = $file->extension();
                            // Sanitizar nombres de archivo y directorios
                            $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
                            Storage::disk('reports')->put($filePath, file_get_contents($file));

                            if (in_array($file->extension(), $extensionesImagen)) {
                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $filePath;
                                $reportFile->image = true;
                                $reportFile->save();
                            } elseif (in_array($file->extension(), $extensionesVideo)) {
                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $filePath;
                                $reportFile->video = true;
                                $reportFile->save();
                            } else {
                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $filePath;
                                $reportFile->file = true;
                                $reportFile->save();
                            }
                        }
                    }

                    Log::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'report_id' => $report->id ,
                        'view' => 'projects/reports/newreport',
                        'action' => 'Crear reporte',
                        'message' => 'Reporte creado exitosamente',
                        'details' => 'Delegado: ' . $report->delegate_id,
                    ]);

                    return redirect()->route('projects.reports.index', ['project' => $project_id]);

                    // Aquí puedes continuar con tu lógica después de la validación exitosa
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Guardar el error en la base de datos
                    ErrorLog::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'view' => 'projects/reports/newreport',
                        'action' => 'Crear reporte',
                        'message' => 'Error de validación en el formulario',
                        'details' => $e->getMessage(),
                    ]);

                    // Redirigir con un mensaje de error
                    return redirect()->back()->with('error', 'Faltan campos o campos incorrectos.');
                } catch (\Exception $e) {
                    // Guardar el error en la base de datos
                    ErrorLog::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'view' => 'projects/reports/newreport',
                        'action' => 'Crear reporte',
                        'message' => 'Error al guardar el reporte',
                        'details' => $e->getMessage(),
                    ]);

                    // Redirigir con un mensaje de error
                    return redirect()->back()->with('error', 'Ocurrió un error al guardar el reporte.');
                }
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

                return view('projects.reports.clonereport', compact('project', 'user', 'filteredUsers', 'report'));
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
                        'description' => 'required',
                    ]);

                    // Aquí puedes continuar con tu lógica después de la validación exitosa
                    $projectName = Str::slug($project->name, '_');
                    $customerName = Str::slug($project->customer->name, '_');

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
                            // Usuario Soporte
                            $userSoporte = User::where('area_id', '4')->first();
                            if ($userSoporte) {
                                $delegate_id = $userSoporte->id;
                            } else {
                                // Usuario administradors
                                $userSoporte = User::where('area_id', '1')->first();
                                $delegate_id = $userSoporte->id;
                            }
                            $reportNew->delegate_id =  $delegate_id;
                            $reportNew->expected_date = null;
                            $reportNew->updated_expected_date = false;
                        } else {
                            $reportNew->delegate_id = $request->delegate;
                            $reportNew->expected_date = $request->expected_date;
                        }

                        $reportNew->report_id = $report->report_id;
                        $reportNew->icon = ($request->icon) ? $request->icon : null;
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
                        $reportNew->description = $request->description;
                        $reportNew->evidence = $request->evidence ?? $report->evidence;
                        $reportNew->points = $request->points ?? 0;
                        // Crear un array asociativo con los valores
                        $questionsPriority = [
                            'pointKnow' => $request->pointKnow,
                            'pointMany' => $request->pointMany,
                            'pointEffort' => $request->pointEffort
                        ];
                        // Convertir el array a JSON
                        $questionsPriorityJson = json_encode($questionsPriority);
                        // Asignar y guardar 
                        $reportNew->questions_points = $questionsPriorityJson;
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
                        // Sanitizar nombres de archivo y directorios
                        $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.jpg';
                        $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName . '/' . $fileName;
                        Storage::disk('reports')->put($filePath, $imageData);

                        $reportNew->project_id = $project_id;
                        $reportNew->user_id = $request->user_id;

                        if (Auth::user()->type_user == 3) {
                            // Usuario Soporte
                            $userSoporte = User::where('area_id', '4')->first();
                            if ($userSoporte) {
                                $delegate_id = $userSoporte->id;
                            } else {
                                // Usuario administradors
                                $userSoporte = User::where('area_id', '1')->first();
                                $delegate_id = $userSoporte->id;
                            }
                            $reportNew->delegate_id =  $delegate_id;
                            $reportNew->expected_date = null;
                            $reportNew->updated_expected_date = false;
                        } else {
                            $reportNew->delegate_id = $request->delegate;
                            $reportNew->expected_date = $request->expected_date;
                        }

                        $reportNew->report_id = $report->report_id;
                        $reportNew->icon = ($request->icon) ? $request->icon : null;
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
                        $reportNew->description = $request->description;
                        $reportNew->evidence = $request->evidence ?? $report->evidence;
                        $reportNew->points = $request->points ?? 0;
                        // Crear un array asociativo con los valores
                        $questionsPriority = [
                            'pointKnow' => $request->pointKnow,
                            'pointMany' => $request->pointMany,
                            'pointEffort' => $request->pointEffort
                        ];
                        // Convertir el array a JSON
                        $questionsPriorityJson = json_encode($questionsPriority);
                        // Asignar y guardar 
                        $reportNew->questions_points = $questionsPriorityJson;
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
                        // Sanitizar nombres de archivo y directorios
                        $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                        $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName . '/' . $fileName;
                        Storage::disk('reports')->put($filePath, file_get_contents($file));

                        $reportNew->project_id = $project_id;
                        $reportNew->user_id = $request->user_id;

                        if (Auth::user()->type_user == 3) {
                            // Usuario Soporte
                            $userSoporte = User::where('area_id', '4')->first();
                            if ($userSoporte) {
                                $delegate_id = $userSoporte->id;
                            } else {
                                // Usuario administradors
                                $userSoporte = User::where('area_id', '1')->first();
                                $delegate_id = $userSoporte->id;
                            }
                            $reportNew->delegate_id =  $delegate_id;
                            $reportNew->expected_date = null;
                            $reportNew->updated_expected_date = false;
                        } else {
                            $reportNew->delegate_id = $request->delegate;
                            $reportNew->expected_date = $request->expected_date;
                        }

                        $reportNew->report_id = $report->report_id;
                        $reportNew->icon = ($request->icon) ? $request->icon : null;
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
                        $reportNew->description = $request->description;
                        $reportNew->evidence = $request->evidence ?? $report->evidence;
                        $reportNew->points = $request->points ?? 0;
                        // Crear un array asociativo con los valores
                        $questionsPriority = [
                            'pointKnow' => $request->pointKnow,
                            'pointMany' => $request->pointMany,
                            'pointEffort' => $request->pointEffort
                        ];
                        // Convertir el array a JSON
                        $questionsPriorityJson = json_encode($questionsPriority);
                        // Asignar y guardar 
                        $reportNew->questions_points = $questionsPriorityJson;
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
                            // Usuario Soporte
                            $userSoporte = User::where('area_id', '4')->first();

                            if ($userSoporte) {
                                $delegate_id = $userSoporte->id;
                            } else {
                                // Usuario administradors
                                $userSoporte = User::where('area_id', '1')->first();
                                $delegate_id = $userSoporte->id;
                            }
                            $reportNew->delegate_id =  $delegate_id;
                            $reportNew->expected_date = null;
                            $reportNew->updated_expected_date = false;
                        } else {
                            $reportNew->delegate_id = $request->delegate;
                            $reportNew->expected_date = $request->expected_date;
                        }

                        $reportNew->report_id = $report->report_id;
                        $reportNew->icon = ($request->icon) ? $request->icon : null;
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
                            // Sanitizar nombres de archivo y directorios
                            $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName . '/' . $fileName;

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
                        $reportNew->description = $request->description;
                        $reportNew->evidence = $request->evidence ?? $report->evidence;
                        $reportNew->points = $request->points ?? 0;
                        // Crear un array asociativo con los valores
                        $questionsPriority = [
                            'pointKnow' => $request->pointKnow,
                            'pointMany' => $request->pointMany,
                            'pointEffort' => $request->pointEffort
                        ];
                        // Convertir el array a JSON
                        $questionsPriorityJson = json_encode($questionsPriority);
                        // Asignar y guardar 
                        $reportNew->questions_points = $questionsPriorityJson;
                        if ($report->count == null) {
                            $reportNew->count = 1;
                        } else {
                            $reportNew->count = $report->count + 1;
                        }
                        $reportNew->repeat = true;
                        $reportNew->delegated_date = Carbon::now();
                        $reportNew->save();
                    }

                    Log::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'report_id' => $reportNew->id ,
                        'view' => 'projects/reports/clonereport',
                        'action' => 'Reincidencia de reporte',
                        'message' => 'Reincidencia creada exitosamente',
                        'details' => 'Delegado: ' .  $request->delegate,
                    ]);

                    return redirect()->route('projects.reports.index', ['project' => $project_id]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    ErrorLog::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'view' => 'projects/reports/clonereport',
                        'action' => 'Reincidencia report',
                        'message' => 'Error de validación en el formulario',
                        'details' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with('error', 'Faltan campos o campos incorrectos');
                } catch (\Exception $e) {
                    // Guardar el error en la base de datos
                    ErrorLog::create([
                        'user_id' => Auth::id(),
                        'project_id' => $project->id,
                        'view' => 'projects/reports/clonereport',
                        'action' => 'Reincidencia report',
                        'message' => 'Error al guardar el reporte',
                        'details' => $e->getMessage(),
                    ]);

                    // Redirigir con un mensaje de error
                    return redirect()->back()->with('error', 'Ocurrió un error al guardar el reporte.');
                }
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
