@extends('layouts.header')

@livewireStyles

<style>
    .loader {
        border-top-color: #3088D9;
        -webkit-animation: spinner 1.5s linear infinite;
        animation: spinner 1.5s linear infinite;
    }

    @-webkit-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spinner {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #7ac142;
        fill: none;
        animation: showSuccess 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #fff;
        stroke-miterlimit: 10;
        margin: 10% auto;
        box-shadow: inset 0px 0px 0px #7ac142;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {

        0%,
        100% {
            transform: none;
        }

        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #7ac142;
        }
    }

    .vibrate {
        -webkit-animation: vibrate 0.2s ease-in-out infinite alternate both;
        animation: vibrate 0.2s ease-in-out infinite alternate both;
    }

    @-webkit-keyframes vibrate {
        0% {
            -webkit-transform: rotate(-0.2deg) scale(1);
            transform: rotate(-0.2deg) scale(1);
        }

        100% {
            -webkit-transform: rotate(0.2deg) scale(1.01);
            transform: rotate(0.2deg) scale(1.01);
        }
    }

    @keyframes vibrate {
        0% {
            -webkit-transform: rotate(-0.2deg) scale(1);
            transform: rotate(-0.2deg) scale(1);
        }

        100% {
            -webkit-transform: rotate(0.2deg) scale(1.01);
            transform: rotate(0.2deg) scale(1.01);
        }
    }

    .accordion {
        transition: 0.4s;
    }

    .panel {
        padding: 0 18px;
        display: none;
        background-color: white;
        overflow: hidden;
    }
</style>

@section('userCatalog')
<span class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
@endsection

@section('black3')
text-yellow
@endsection

@section('content')
<div>
    <livewire:user-catalog />
</div>
@livewireScripts

@stack('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
@endsection