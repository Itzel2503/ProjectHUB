@extends('layouts.header')

@section('content')
<div>
    <livewire:auth.profile />

    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
</div>
@endsection