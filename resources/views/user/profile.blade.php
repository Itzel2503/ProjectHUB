@extends('layouts.header')

@section('usuarios')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black1')
text-yellow
@endsection

@section('content')
<div>
    <livewire:user.profile />
</div>

@endsection