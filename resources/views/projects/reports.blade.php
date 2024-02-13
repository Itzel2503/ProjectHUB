@extends('layouts.header')

@section('projects')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black4')
text-yellow
@endsection

@section('content')
<style>
    @media (max-width: 1378px) {
        .divSelect {
            flex-direction: column;
        }
    }
</style>
<div class="mt-8">
    <div class=" w-full mx-auto">
        <div class="mt-5 md:mt-0 md:col-span-1">
            <div class="px-4 py-5 space-y-6 sm:p-6 w-full bg-main-fund">
                <h1 class="inline-flex w-full font-semibold text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-files" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                    </svg> 
                    <span class="ml-4">Reportes del proyecto '{{ $project->name }}'</span>
                </h1>
            </div>
            <livewire:projects.table-reports :project="$project">
        </div>
    </div>
</div>
@endsection