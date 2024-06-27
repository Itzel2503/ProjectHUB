@extends('layouts.header')

@section('content')
<style>
    @media (max-width: 1378px) {
        .divSelect {
            flex-direction: column;
        }
    }
</style>

<div class="mt-4 w-full mx-auto">
    <div class="py-3 space-y-6 w-full px-4 ">
        <h1 class="tiitleHeader">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bug w-auto h-auto" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 9v-1a3 3 0 0 1 6 0v1" /> 
                <path d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                <path d="M3 13l4 0" /> 
                <path d="M17 13l4 0" />
                <path d="M12 20l0 -6" />
                <path d="M4 19l3.35 -2" /> 
                <path d="M20 19l-3.35 -2" />
                <path d="M4 7l3.75 2.4" />
                <path d="M20 7l-3.75 2.4" />
            </svg>
            <span class="ml-4 text-xl">Reportes "{{ $project->name }}"</span>
        </h1>
    </div>
    <livewire:projects.table-reports :project="$project">

    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
</div>
@endsection