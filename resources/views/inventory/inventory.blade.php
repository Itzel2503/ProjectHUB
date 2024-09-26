@extends('layouts.header')

@section('content')
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-archive h-auto w-auto">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                    <path d="M10 12l4 0" />
                </svg>
                <span class="ml-4 text-xl">Inventario</span>
            </h1>
        </div>
        <livewire:inventory.inventory/>

        @livewireScripts
        @stack('js')
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    </div>
@endsection
