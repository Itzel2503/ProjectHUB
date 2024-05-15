@extends('layouts.header')

@section('activities-reports')
    <span class="absolute inset-y-0 left-0 w-1 rounded-br-lg rounded-tr-lg" aria-hidden="true"></span>
@endsection

@section('content')
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-search">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M18.5 18.5l2.5 2.5" />
                    <path d="M4 6h16" />
                    <path d="M4 12h4" />
                    <path d="M4 18h4" />
                </svg>
                <span class="ml-4 text-xl">Actividades y Reportes</span>
            </h1>
        </div>
        <livewire:activities-reports.activities-reports>

            @livewireScripts
            @stack('js')
            <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    </div>
@endsection
