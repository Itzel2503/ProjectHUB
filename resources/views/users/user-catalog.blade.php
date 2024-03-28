@extends('layouts.header')

@section('userCatalog')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black2')
text-yellow-400
@endsection

@section('content')
<div class="mt-8">
        <div class="w-full mx-auto">
            <div class="mt-5 md:mt-0 md:col-span-1">
                <div class="px-4 py-5 space-y-6 sm:p-6 w-full bg-main-fund">
                    <h1 class="inline-flex w-full font-semibold text-2xl text-secondary-fund">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group w-auto h-auto"" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                            <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                            <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                        </svg>     
                        <span class="ml-4">Usuarios</span>
                    </h1>
                </div>
                <livewire:users.table-users/>

                @livewireScripts
                @stack('js')
                <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
            </div>
        </div>
    </div>
</div>
@endsection