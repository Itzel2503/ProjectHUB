@extends('layouts.header')

@section('content')
    <style>
        /* Estilos para los iconos */
        .icon-event {
            font-size: 24px;
            cursor: pointer;
            margin: 5px;
            transition: transform 0.2s ease, font-size 0.2s ease;
        }

        /* Estilo cuando un icono está seleccionado */
        .icon-event.selected {
            font-size: 36px;
            /* Tamaño más grande */
            transform: scale(1.2);
            /* Efecto de agrandar */
        }

        /* Ocultar el checkbox real */
        .icon-checkbox {
            display: none;
        }
    </style>

    <div class=" mt-4 w-full mx-auto">
        <div class=" py-3 space-y-6 w-full px-4 ">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                    <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                    <path d="M3 6l0 13" />
                    <path d="M12 6l0 13" />
                    <path d="M21 6l0 13" />
                </svg>
                <span class="ml-4 text-xl">Actividades "{{ $project->name }}"</span>
            </h1>
        </div>
        @if ($client == false)
            <livewire:projects.activities.backlog-sprints :project="$project" :backlog="$backlog">
        @else
            <livewire:projects.activities.backlog-sprints-client :project="$project" :backlog="$backlog">
        @endif

        @livewireScripts
        @stack('js')
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    </div>
@endsection
