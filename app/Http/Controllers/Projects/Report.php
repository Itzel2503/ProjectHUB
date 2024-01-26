<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Report as ModelsReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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

            return view('projects.reports', compact('project'));
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
            $allUsers = User::all();
            $project = Project::find($project_id);

            return view('projects.newreport', compact('project', 'user', 'allUsers'));
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
        $project = Project::find($project_id);
        $report = new ModelsReport();
        $now = Carbon::now();
        $dateString = $now->format("Y-m-d H_i_s");

        if (isset($request->video)) {
            // $fileName = 'Reporte ' . $dateString . '.mp4';
            // $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/';

            // // Obtener el contenido del video en formato "blob"
            // $videoBlob = $request->input('video');
            // // Decodificar el contenido base64 si es necesario
            // $videoData = base64_decode($videoBlob);

            // // Crear un objeto Blob a partir del contenido del video
            // // Crear el archivo temporal
            // $archivoTemporal = tempnam(sys_get_temp_dir(), 'video');
            // file_put_contents($archivoTemporal, $videoData);
            // $blobVideo = new \Illuminate\Http\File($archivoTemporal);
            // $rutaDeseada = 'public/uploads/temp/video.mp4';

            // // Copia el archivo a la ruta deseada en tu directorio de almacenamiento
            // Storage::put($rutaDeseada, file_get_contents($blobVideo));

            // // Puedes imprimir o usar la ruta según tus necesidades
            // // dd($rutaDeseada);

            // FFMpeg::fromDisk('public')
            //     ->open($rutaDeseada)
            //     ->export()
            //     ->toDisk('reports')
            //     ->inFormat(new \FFMpeg\Format\Video\X264())
            //     ->save($filePath . '/' . 'Reporte ' . $dateString . '.mp4');

            $report->project_id = $project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = 'Falta video grabado';
            $report->image = false;
            $report->video = true;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        if (isset($request->photo)) {
            list($type, $data) = explode(';', $request->photo);
            list(, $data)      = explode(',', $data);
            $imageData = base64_decode($data);

            $fileName = 'Reporte ' . $dateString . '.jpg';
            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
            Storage::disk('reports')->put($filePath, $imageData);

            $report->project_id = $project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $filePath;
            $report->image = true;
            $report->video = false;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        if (isset($request->file)) {

            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv'];   
            
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' . $fileName;
            Storage::disk('reports')->put($filePath, file_get_contents($file));

            $report->project_id = $project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $filePath;

            if (in_array($file->extension(), $extensionesImagen)) {
                $report->image = true;
                $report->video = false;
            } elseif (in_array($file->extension(), $extensionesVideo)) {
                $report->image = false;
                $report->video = true;
            } else {
                $report->image = false;
                $report->video = false;
            }
            
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        if (!isset($request->video) && !isset($request->photo) && !isset($request->file)) {
            $report->project_id = $project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->image = false;
            $report->video = false;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        return redirect()->route('projects.reports.index', ['project' => $project_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $project_id)
    {
        dd($id);
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
