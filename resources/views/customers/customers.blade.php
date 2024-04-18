@extends('layouts.header')

@section('customers')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black3')
text-yellow-400
@endsection

@section('content')
<div class="mt-4 w-full mx-auto">
    <div class="py-3 space-y-6 w-full px-4 ">
        <h1 class="tiitleHeader">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users w-auto h-auto" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
            </svg>
            <span class="ml-4 text-xl">Clientes</span>
        </h1>
    </div>
    <livewire:customers.table-customers/>

    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
</div>
@endsection