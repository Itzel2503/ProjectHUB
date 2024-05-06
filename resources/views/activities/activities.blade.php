@extends('layouts.header')

@section('all-activities')
    <span class="absolute inset-y-0 left-0 w-1 rounded-br-lg rounded-tr-lg" aria-hidden="true"></span>
@endsection

@section('content')
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
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
                <span class="ml-4 text-xl">Actividades</span>
            </h1>
        </div>
        <livewire:activities.all-activities >

        @livewireScripts
        @stack('js')
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    </div>
@endsection
