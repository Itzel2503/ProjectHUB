@extends('layouts.header')

@section('customers')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black3')
text-yellow
@endsection

@section('content')
<div class="mt-8">
    <div class=" w-full mx-auto">
        <div class="mt-5 md:mt-0 md:col-span-1">
            <div class="px-4 py-5 space-y-6 sm:p-6 w-full bg-main-fund">
                <h1 class="inline-flex w-full text-base font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                    <span class="ml-4">Clientes</span>
                </h1>
            </div>
            <livewire:customers.table-customers/>
        </div>
    </div>
</div>
@endsection