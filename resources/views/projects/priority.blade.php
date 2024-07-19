@extends('layouts.header')

@section('content')
    <style>
        .borderK1 {
            border: 2px solid;
            border-color: #F19632;
        }

        .borderK2 {
            border: 2px solid;
            border-color: #F6B36B;
        }

        .borderK3 {
            border: 2px solid;
            border-color: #FBC568;
        }

        .borderK4 {
            border: 2px solid;
            border-color: #D5CF6F;
        }

        .borderK5 {
            border: 2px solid;
            border-color: #ABC878;
        }

        .borderK6 {
            border: 2px solid;
            border-color: #81C181;
        }

        .borderK7 {
            border: 2px solid;
            border-color: #57BB8A;
        }

        .borderK8 {
            border: 2px solid;
            border-color: #57BB8A;
        }

        .borderK9 {
            border: 2px solid;
            border-color: #57BB8A;
        }

        .borderK10 {
            border: 2px solid;
            border-color: #57BB8A;
        }

        .bgK1 {
            background-color: #F19632;
        }

        .bgK2 {
            background-color: #F6B36B;
        }

        .bgK3 {
            background-color: #FBC568;
        }

        .bgK4 {
            background-color: #D5CF6F;
        }

        .bgK5 {
            background-color: #ABC878;
        }

        .bgK6 {
            background-color: #81C181;
        }

        .bgK7 {
            background-color: #57BB8A;
        }

        .bgK8 {
            background-color: #57BB8A;
        }

        .bgK9 {
            background-color: #57BB8A;
        }

        .bgK10 {
            background-color: #57BB8A;
        }

        .hidden-info {
            display: none;
        }

        .principal:hover .hidden-info {
            display: block;
        }
    </style>
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                    class="icon icon-tabler icons-tabler-filled icon-tabler-star">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" />
                </svg>
                <span class="ml-4 text-xl">Prioridades</span>
            </h1>
        </div>
        <div class="mb-5 flex justify-center">
            <h5 class="text-lg font-bold">{{ $weekText }}</h5>
        </div>
        <div class="w-full xl:flex xl:justify-center">
            {{-- ACTIVOS --}}
            <div class="mt-5 w-full px-5">
                <h5 class="text-lg font-bold">ACTIVOS</h5>
                <div class="flex w-full rounded-full p-2 font-semibold">
                    <div class="w-1/6"></div>
                    <div class="m-auto w-full"></div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Lider / Scrum</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-96">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Líder/Scrum:</p>
                                    <p>
                                        Está a cargo de establecer la metodología Scrum y mantener a los miembros del equipo
                                        enfocados en los principios y las prácticas de desarrollo. Sus responsabilidades son:
                                    </p>
                                    <ol>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Planificar el desarrollo de sprints.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Ayudar a gestionar el trabajo pendiente del proyecto.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Eliminar obstáculos.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Organizar reuniones.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Product Owner</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-80 right-0">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Product Owner:</p>
                                    <p>
                                        Es la persona del equipo que funge como la "voz del cliente", para asegurarse de que el
                                        producto final cumpla con los requisitos y se alinee con los objetivos comerciales,
                                        puesto que no solo entiende los requerimientos de la empresa, sino tambiém de los
                                        usuarios.
                                    </p>
                                    <ol>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Transforma las ideas de los clientes.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Prioriza ciertas funciones en lugar de otras.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Developer</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-80 right-0">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Developer:</p>
                                    <p>
                                        Juega un papel crucial en el desarrollo del producto, adoptando una metodología de
                                        auto-organicación y auto-getión para asegurar la entrega de incrementos de software
                                        al final de cada sprint. Los Developers asumen la responsabilidad de ejecutar las
                                        tareas del backlog del producto, transformando requisitos en soluciones tecnológicas
                                        funcionales.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($activos as $activo)
                    <div class="borderK{{ $activo->priority }} my-4 flex w-full rounded-full p-2">
                        <div class="bgK{{ $activo->priority }} mx-3 w-24 h-10 rounded-full">
                            <p class="my-2 text-center font-semibold text-black">K{{ $activo->priority }}</p>
                        </div>
                        <p class="m-auto mx-1 w-full">{{ $activo->name }}</p>
                        <p class="m-auto mx-1 w-1/3">{{ $activo->leader_name }}</p>
                        <p class="m-auto mx-1 w-1/3">{{ $activo->product_owner_name }}</p>
                        <div class="m-auto mx-1 w-1/3">
                            @if ($activo->developer1_name)
                                <p>{{ $activo->developer1_name }}</p>
                            @else
                                <p>Sin asignar</p>
                            @endif
                            @if ($activo->developer2_name)
                                <p>{{ $activo->developer2_name }}</p>
                            @else
                                <p>Sin asignar</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- SOPORTES --}}
            <div class="mt-10 w-full px-5 xl:mt-5">
                <h5 class="text-lg font-bold">SOPORTES</h5>
                <div class="flex w-full rounded-full p-2 font-semibold">
                    <div class="w-1/6"></div>
                    <div class="m-auto w-full"></div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Lider / Scrum</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-96 right-0">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Líder/Scrum:</p>
                                    <p>
                                        Está a cargo de establecer la metodología Scrum y mantener a los miembros del equipo
                                        enfocados en los principios y las prácticas de desarrollo. Sus responsabilidades son:
                                    </p>
                                    <ol>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Planificar el desarrollo de sprints.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Ayudar a gestionar el trabajo pendiente del proyecto.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Eliminar obstáculos.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Organizar reuniones.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Product Owner</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-80 right-0">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Product Owner:</p>
                                    <p>
                                        Es la persona del equipo que funge como la "voz del cliente", para asegurarse de que el
                                        producto final cumpla con los requisitos y se alinee con los objetivos comerciales,
                                        puesto que no solo entiende los requerimientos de la empresa, sino tambiém de los
                                        usuarios.
                                    </p>
                                    <ol>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Transforma las ideas de los clientes.</p>
                                        </li>
                                        <li class="m-auto flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-point">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 7a5 5 0 1 1 -4.995 5.217l-.005 -.217l.005 -.217a5 5 0 0 1 4.995 -4.783z" />
                                            </svg>
                                            <p class="my-auto">Prioriza ciertas funciones en lugar de otras.</p>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="principal m-auto mx-1 w-1/3">
                        <h5>Developer</h5>
                        <div class="relative">
                            <div class="absolute w-40 md:w-80 right-0">
                                <div class="hidden-info relative z-10 bg-gray-100 p-2 text-xs">
                                    <p class="font-semibold">Developer:</p>
                                    <p>
                                        Juega un papel crucial en el desarrollo del producto, adoptando una metodología de
                                        auto-organicación y auto-getión para asegurar la entrega de incrementos de software
                                        al final de cada sprint. Los Developers asumen la responsabilidad de ejecutar las
                                        tareas del backlog del producto, transformando requisitos en soluciones tecnológicas
                                        funcionales.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($soportes as $soporte)
                    <div class="borderK{{ $soporte->priority }} my-4 flex w-full rounded-full p-2">
                        <div class="bgK{{ $soporte->priority }} mx-3 w-24 h-10 rounded-full">
                            <p class="my-2 text-center font-semibold text-black">K{{ $soporte->priority }}</p>
                        </div>
                        <p class="m-auto mx-1 w-full">{{ $soporte->name }}</p>
                        <p class="m-auto mx-1 w-1/3">{{ $soporte->leader_name }}</p>
                        <p class="m-auto mx-1 w-1/3">{{ $soporte->product_owner_name }}</p>
                        <div class="m-auto mx-1 w-1/3">
                            @if ($soporte->developer1_name)
                                <p>{{ $soporte->developer1_name }}</p>
                            @else
                                <p>Sin asignar</p>
                            @endif
                            @if ($soporte->developer2_name)
                                <p>{{ $soporte->developer2_name }}</p>
                            @else
                                <p>Sin asignar</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <livewire:projects.priorities-coments />

        @livewireScripts
        @stack('js')
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    </div>
@endsection
