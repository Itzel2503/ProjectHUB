@extends('layouts.header')

@section('content')
<div class="mt-4 w-full mx-auto">
    <div class="py-3 space-y-6 w-full px-4 ">
        <h1 class="tiitleHeader">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group w-auto h-auto"" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
            </svg>
            <span class="ml-4 text-xl">Usuarios</span>
        </h1>
    </div>
    <livewire:users.table-users/>

    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
</div>
@endsection