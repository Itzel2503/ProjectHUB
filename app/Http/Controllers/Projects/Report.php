<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Report as ModelsReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            
            return view('reports.reports', compact('project'));
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

            return view('reports.newreport', compact('project_id', 'user', 'allUsers'));
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
    public function store(Request $request)
    {
        $project = Project::find($request->project_id);

        $report = new ModelsReport();

        if (isset($request->video)) {
            $report->project_id = $request->project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $request->video;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        if (isset($request->photo)) {
            $report->project_id = $request->project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $request->photo;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }

        if (isset($request->file)) {

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            Storage::disk('public')->put(now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name . '/' .$fileName, file_get_contents($file));

            $report->project_id = $request->project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $file->getClientOriginalName();
            $report->state = "Abierto";
            $report->comment = $request->comment;

            $report->save();
        }

        if (!isset($request->video) && !isset($request->photo) && !isset($request->file)) {
            $report->project_id = $request->project_id;
            $report->user_id = $request->user_id;
            $report->delegate_id = $request->delegate;
            $report->title = $request->title;
            $report->content = $request->file;
            $report->state = "Abierto";
            $report->comment = $request->comment;
            $report->save();
        }
        
        return redirect()->route('reports.index', ['project_id' => $request->project_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
