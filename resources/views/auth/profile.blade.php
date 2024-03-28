@extends('layouts.header')

@section('profile')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black1')
text-yellow-400
@endsection

@section('content')
<div>
    <livewire:auth.profile />

    @livewireScripts
    @stack('js')
</div>

@endsection