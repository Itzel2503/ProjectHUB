@extends('layouts.header')

@section('projects')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('content')
<div class=" mt-4 w-full mx-auto">
    <div class=" py-3 space-y-6 w-full px-4 ">
        <h1 class="tiitleHeader">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                <path d="M3 6l0 13" />
                <path d="M12 6l0 13" />
                <path d="M21 6l0 13" />
            </svg>
            <span class="ml-4 text-xl">Actividades "{{ $project->name }}"</span>
        </h1>
    </div>
    <livewire:projects.table-activities :project="$project" :backlog="$backlog" : sprints="$sprints">

    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
</div>
@endsection