<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">


    <link rel="stylesheet" href="/css/introjs.css">
    <script src="/js/intro.js"></script>

    <!--        favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- /*tailwind elements*/ --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/index.min.css" /> -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eticom</title>
    <!-- <link rel="stylesheet" href="output.css"> -->
    <script defer src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>








    @livewireStyles
    <style>
        .collapsText{
                transition: .5s;
            }

        .navOpacity-100{
                opacity:1;
            }
        .navOpacity-0{
                opacity:0;
                
            }
        .sidenav {
            height: 100%;
            width: 242px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            overflow-x: hidden;
            
            transition: 0.5s;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
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

        @media (min-width:0px) and (max-width:1280px) {
            .menu {
                animation-name: slide;
                animation-duration: .5s;
                z-index: 1000;
            }
        }

        @keyframes slide {
            from {
                left: -16rem;
            }

            to {
                left: 0;
            }
        }




        .scrollx {
            overflow-x: auto;
        }


        .scrollEdit::-webkit-scrollbar {
            width: 8px;
            /* width of the entire scrollbar */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-track {
            background: #cad8f5;
            /* color of the tracking area */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-thumb {
            background-color: #1118278a;
            /* color of the scroll thumb */
            border-radius: 20px;
            /* roundness of the scroll thumb */

        }

        .scrollEdit::-webkit-scrollbar-thumb:hover {
            background: #1118278a;
            box-shadow: 0 0 2px 1px rgb(0 0 0 / 20%);


            /*background-color: cadetblue;*/
        }
    </style>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <script defer src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>


    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>

    {{-- install correct https://flowbite.com/docs/getting-started/laravel/ --}}
</head>

<body class="font-sans antialiased overflow-y-hidden">
    

    <div class="min-h-screen bg-gray-100">
        <main class="">
            <div class="flex h-screen ">

                <div class="h-screen flex flex-row  ">

                    <!-- Begin Navbar -->

                    <div id="mySidebar" class="w-64 md:w-96 sidenav noPrint sidenavback scrollEdit max-w-lg   xl:block mobile-menu menu hidden  xl:h-screen  backdrop-blur-md bg-cover  xl:relative fixed top-0 left-0 h-full z-30 " style=" background: #ffffffc9; background-image: url(https://e-eticom.com/img/FondoPanel.png); background-size: cover; width: 17rem;">
                        <div id="accordion" style="width:100%">
                            <div class=" ">
                                <a href="{{asset('/')}}">
                                    <img id="isotipo" class="h-6 mx-auto mt-6" src="{{asset('img/logo_eticom.svg')}}">
                                    <img id="imagotipo" style="display: none;" class="h-6 mx-auto mt-6" src="{{asset('img/logo_eticomicono.svg')}}">
                                </a>

                                <div class="pt-2  w-full text-center text-base text-gray-400">

                                </div>
                            </div>

                            <!-- NAVBAR COLLAPSE -->
                            <div class="w-100 h-75 hidden md:block" style="z-index: 10; text-align:left;">
                                <a id="cerrar" class="text-eticomblue" href="javascript:void(0)" onclick="closeNav()" st yle=" width:100%;  font-size:40px; cursor: pointer; transition: 0.5s;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 ml-auto mr-2">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-4.28 9.22a.75.75 0 000 1.06l3 3a.75.75 0 101.06-1.06l-1.72-1.72h5.69a.75.75 0 000-1.5h-5.69l1.72-1.72a.75.75 0 00-1.06-1.06l-3 3z" clip-rule="evenodd" />
                                    </svg>
                                </a>

                                <a href="javascript:void(0)" class="text-eticomblue" onclick="openNav()" id="abrir" style=" width:100%; font-size:40px; display: none; cursor: pointer; transition: 0.5s;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 mx-auto">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" clip-rule="evenodd" />
                                    </svg>

                                </a>          
                            </div>

                            <div class="mt-8 md:mt-0 w-full  antialiased flex flex-col hover:cursor-pointer px-2">


                                {{-- Publish --}}
                                @php
                                $nombreRuta = Route::currentRouteName();

                                @endphp
                                <a href="/publications/{{date('Y-m-d')}}" class=" flex-nowrap flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'publication') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">

                                        <i class="fa-solid fa-network-wired mr-2"></i><lavel class="collapsText">Eventos y tareas.</lavel>
                                    </span>

                                </a>

                            
                                <a href="/prices" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'precios') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.902 7.098a3.75 3.75 0 013.903-.884.75.75 0 10.498-1.415A5.25 5.25 0 008.005 9.75H7.5a.75.75 0 000 1.5h.054a5.281 5.281 0 000 1.5H7.5a.75.75 0 000 1.5h.505a5.25 5.25 0 006.494 2.701.75.75 0 00-.498-1.415 3.75 3.75 0 01-4.252-1.286h3.001a.75.75 0 000-1.5H9.075a3.77 3.77 0 010-1.5h3.675a.75.75 0 000-1.5h-3c.105-.14.221-.274.348-.402z" clip-rule="evenodd" />
                                        </svg>
                                        <lavel class="collapsText">Precios</lavel>
                                    </span>

                                </a>
                                <a href="/desarrollos" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'desarrollos') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-.062l3-3.75z" clip-rule="evenodd" />
                                        </svg>
                                        <lavel class="collapsText">Desarrollos</lavel>
                                    </span>

                                </a>


                                <a href="/costs/main" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'costs-main') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style="white-space: nowrap;">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 01-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004zM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 01-.921.42z" />
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v.816a3.836 3.836 0 00-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 01-.921-.421l-.879-.66a.75.75 0 00-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 001.5 0v-.81a4.124 4.124 0 001.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 00-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 00.933-1.175l-.415-.33a3.836 3.836 0 00-1.719-.755V6z" clip-rule="evenodd" />
                                        </svg>
                                        <lavel class="collapsText">Costos</lavel>
                                    </span>

                                </a>

                                <a href="/cotizaciones" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'cotizaciones') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                            <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                                        </svg>
                                        <lavel class="collapsText"> Cotizaciones</lavel>
                                    </span>

                                </a>
                                <!-- <a href="/muestras" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'muestras') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium ">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path d="M9.375 3a1.875 1.875 0 000 3.75h1.875v4.5H3.375A1.875 1.875 0 011.5 9.375v-.75c0-1.036.84-1.875 1.875-1.875h3.193A3.375 3.375 0 0112 2.753a3.375 3.375 0 015.432 3.997h3.943c1.035 0 1.875.84 1.875 1.875v.75c0 1.036-.84 1.875-1.875 1.875H12.75v-4.5h1.875a1.875 1.875 0 10-1.875-1.875V6.75h-1.5V4.875C11.25 3.839 10.41 3 9.375 3zM11.25 12.75H3v6.75a2.25 2.25 0 002.25 2.25h6v-9zM12.75 12.75v9h6.75a2.25 2.25 0 002.25-2.25v-6.75h-9z" />
                                        </svg>
                                        Muestras
                                    </span>

                                </a> -->
                                <a href="/proyectos" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'proyectos') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path d="M5.566 4.657A4.505 4.505 0 016.75 4.5h10.5c.41 0 .806.055 1.183.157A3 3 0 0015.75 3h-7.5a3 3 0 00-2.684 1.657zM2.25 12a3 3 0 013-3h13.5a3 3 0 013 3v6a3 3 0 01-3 3H5.25a3 3 0 01-3-3v-6zM5.25 7.5c-.41 0-.806.055-1.184.157A3 3 0 016.75 6h10.5a3 3 0 012.683 1.657A4.505 4.505 0 0018.75 7.5H5.25z" />
                                        </svg>
                                        <lavel class="collapsText">  Proyectos</lavel>
                                    </span>

                                </a>

                                <div x-data="{isActive: false,  @if (request()->routeIs('repProspectos') || request()->routeIs('repClientes') || request()->routeIs('repProyectos')) open: true  @else open: false  @endif}">
                                    
                                    <a @click="$event.preventDefault(); open = !open" class="w-full text-sm text-left  font-medium flex items-center my-2 py-1 px-4 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if (request()->routeIs('repProspectos') || request()->routeIs('repClientes') || request()->routeIs('repProyectos')) bg-gray-600  text-white  @else text-gray-800  @endif " :class="{'bg-eticomblue': isActive || open}" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'" style="white-space: nowrap;">
                                                    
                                        <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">
                                            <i class="fas fa-chart-pie mr-2"></i><lavel class="collapsText">Reportes </lavel>
                                        </span>

                                        <span class="ml-auto" aria-hidden="true">
                                                        <!-- active class 'rotate-180' -->
                                            <svg id="esconder4" class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>

                                    </a>

                                    <div role="menu" x-show="open" class="mt-2 pl-4 pr-1" aria-label="Dashboards">
                                        <a href="{{route('repProspectos')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('repProspectos')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                            </svg>

                                            <lavel class="collapsText">Reporte prospectos</lavel>
                                        </a>

                                        <a href="{{route('repProyectos')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('repProyectos')) underline  font-bold @endif">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                            </svg>
                                            <lavel class="collapsText">Reporte proyectos</lavel>
                                        </a>

                                        <a href="{{route('repClientes')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('repClientes')) underline  font-bold @endif">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                            </svg>
                                            <lavel class="collapsText">Reporte clientes</lavel>
                                        </a>
                                    </div>

                                </div>

                                <!-- <a href="/pedidos" class="flex items-center my-2 py-2 px-3 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if ($nombreRuta  == 'pedidos') bg-gray-600  text-white   @endif" role="button" aria-haspopup="true">


                                    <span class=" py-1  w-full text-sm text-left  font-medium ">

                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-2">
                                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                        </svg>

                                        Pedidos
                                    </span>

                                </a> -->

                                {{-- catalogos --}}

                                
                                <div x-data="{ isActive: false,  @if (request()->routeIs('catalogue-users') || request()->routeIs('catalogue-areas') || request()->routeIs('matrix-area') || request()->routeIs('lines-catalog')   || request()->routeIs('substrates-catalog') || request()->routeIs('toolings-catalog') || request()->routeIs('workforce-catalog') || request()->routeIs('model-catalog') || request()->routeIs('cost-tax-factors-catalog') || request()->routeIs('films-catalog') || request()->routeIs('inks-catalog') || request()->routeIs('providers-catalog') || request()->routeIs('custumers-catalog') || request()->routeIs('strawberries-burins-catalog') || request()->routeIs('materials-catalog') || request()->routeIs('emulsiones') || request()->routeIs('workforce-vitrificable-catalog')) open: true  @else open: false  @endif }">
                                    <!-- active & hover classes 'bg-indigo-100 dark:bg-indigo-600' -->
                                    <a href="#" @click="$event.preventDefault(); open = !open" class="flex items-center my-2 py-1 px-4 text-gray-800 transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if (request()->routeIs('catalogue-users') || request()->routeIs('catalogue-areas') || request()->routeIs('matrix-area') || request()->routeIs('lines-catalog')   || request()->routeIs('substrates-catalog') || request()->routeIs('toolings-catalog') || request()->routeIs('workforce-catalog') || request()->routeIs('model-catalog') || request()->routeIs('cost-tax-factors-catalog') || request()->routeIs('films-catalog') || request()->routeIs('inks-catalog') || request()->routeIs('providers-catalog') || request()->routeIs('custumers-catalog') || request()->routeIs('strawberries-burins-catalog') || request()->routeIs('materials-catalog')  || request()->routeIs('emulsiones')  || request()->routeIs('workforce-vitrificable-catalog') ) bg-gray-600  text-white  @else text-gray-800  @endif " :class="{'bg-eticomblue': isActive || open}" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">

                                        <span class=" py-1  w-full text-sm text-left  font-medium " style=" white-space: nowrap;">
                                            <i class="fa-solid fa-book-open mr-2"></i> <lavel class="collapsText">Catálogos </lavel></span>
                                        <span class="ml-auto" aria-hidden="true">
                                            <!-- active class 'rotate-180' -->
                                            <svg id="esconder2" class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </a>
                                    <div role="menu" x-show="open" class="mt-2 px-4" aria-label="Dashboards">
                                        <!-- active & hover classes 'text-gray-700 dark:text-light' -->
                                        <!-- inActive classes 'text-gray-400 dark:text-gray-400' -->
                                        <a href="{{route('currency')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('currency')) underline  font-bold @endif" style="white-space: nowrap;">

                                            <i class="fa-solid fa-coins mr-2"></i>

                                            <lavel class="collapsText"> Divisas</lavel>
                                        </a>

                                        <a href="{{route('catalogue-users')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('catalogue-users')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd" />
                                                <path d="M5.082 14.254a8.287 8.287 0 00-1.308 5.135 9.687 9.687 0 01-1.764-.44l-.115-.04a.563.563 0 01-.373-.487l-.01-.121a3.75 3.75 0 013.57-4.047zM20.226 19.389a8.287 8.287 0 00-1.308-5.135 3.75 3.75 0 013.57 4.047l-.01.121a.563.563 0 01-.373.486l-.115.04c-.567.2-1.156.349-1.764.441z" />
                                            </svg>
                                            <lavel class="collapsText"> Usuarios</lavel>
                                        </a>
                                        <a href="{{route('custumers-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('custumers-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 007.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 004.902-5.652l-1.3-1.299a1.875 1.875 0 00-1.325-.549H5.223z" />
                                                <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 009.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 002.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5H3zm3-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v3a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75v-3zm8.25-.75a.75.75 0 00-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.75-.75h-3z" clip-rule="evenodd" />
                                            </svg>
                                            <lavel class="collapsText">Clientes y prospectos</lavel>
                                        </a>
                                        <!-- <a href="/prospectos" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('custumers-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                            </svg>
                                            <lavel class="collapsText"> Prospectos</lavel>
                                        </a> -->
                                        <a href="{{route('providers-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('providers-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M3 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5H15v-18a.75.75 0 000-1.5H3zM6.75 19.5v-2.25a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v2.25a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75zM6 6.75A.75.75 0 016.75 6h.75a.75.75 0 010 1.5h-.75A.75.75 0 016 6.75zM6.75 9a.75.75 0 000 1.5h.75a.75.75 0 000-1.5h-.75zM6 12.75a.75.75 0 01.75-.75h.75a.75.75 0 010 1.5h-.75a.75.75 0 01-.75-.75zM10.5 6a.75.75 0 000 1.5h.75a.75.75 0 000-1.5h-.75zm-.75 3.75A.75.75 0 0110.5 9h.75a.75.75 0 010 1.5h-.75a.75.75 0 01-.75-.75zM10.5 12a.75.75 0 000 1.5h.75a.75.75 0 000-1.5h-.75zM16.5 6.75v15h5.25a.75.75 0 000-1.5H21v-12a.75.75 0 000-1.5h-4.5zm1.5 4.5a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008zm.75 2.25a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75v-.008a.75.75 0 00-.75-.75h-.008zM18 17.25a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" />
                                            </svg>
                                            <lavel class="collapsText"> Proveedores</lavel>
                                        </a> 
                                        <div x-data="{ isActive: false, open: false}">
                                            <a @click="$event.preventDefault(); open = !open" class="flex items-center my-2 py-1 px-1 text-sm text-left text-gray-800 font-medium  transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if (request()->routeIs('workforce-vitrificable-catalog') || request()->routeIs('workforce-catalog')) bg-gray-600  text-white  @else text-gray-800  @endif " :class="{'bg-eticomblue': isActive || open}" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'" style="white-space: nowrap;">

                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                    <path d="M10.5 1.875a1.125 1.125 0 012.25 0v8.219c.517.162 1.02.382 1.5.659V3.375a1.125 1.125 0 012.25 0v10.937a4.505 4.505 0 00-3.25 2.373 8.963 8.963 0 014-.935A.75.75 0 0018 15v-2.266a3.368 3.368 0 01.988-2.37 1.125 1.125 0 011.591 1.59 1.118 1.118 0 00-.329.79v3.006h-.005a6 6 0 01-1.752 4.007l-1.736 1.736a6 6 0 01-4.242 1.757H10.5a7.5 7.5 0 01-7.5-7.5V6.375a1.125 1.125 0 012.25 0v5.519c.46-.452.965-.832 1.5-1.141V3.375a1.125 1.125 0 012.25 0v6.526c.495-.1.997-.151 1.5-.151V1.875z" />
                                                </svg><lavel class="collapsText">Manos de obra</lavel>

                                                <span class="ml-auto" aria-hidden="true">
                                                    <!-- active class 'rotate-180' -->
                                                    <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>


                                            </a>

                                            <div role="menu" x-show="open" class="mt-2 pl-4 pr-1" aria-label="Dashboards">
                                                <a href="{{route('workforce-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('workforce-catalog')) underline  font-bold @endif" >

                                                    <i class="fa-solid fa-tape"></i>


                                                    <lavel class="collapsText">Mano de obra</lavel>
                                                </a>
                                                <a href="{{route('workforce-vitrificable-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('workforce-vitrificable-catalog')) underline  font-bold @endif">
                                                    <i class="fa-solid fa-paintbrush"></i>



                                                    <lavel class="collapsText">Mano de obra Vitrificable</lavel>
                                                </a>


                                            </div>
                                        </div>


                                        <a href="{{route('model-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('model-catalog')) underline  font-bold @endif" style="white-space: nowrap;"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M1.5 7.125c0-1.036.84-1.875 1.875-1.875h6c1.036 0 1.875.84 1.875 1.875v3.75c0 1.036-.84 1.875-1.875 1.875h-6A1.875 1.875 0 011.5 10.875v-3.75zm12 1.5c0-1.036.84-1.875 1.875-1.875h5.25c1.035 0 1.875.84 1.875 1.875v8.25c0 1.035-.84 1.875-1.875 1.875h-5.25a1.875 1.875 0 01-1.875-1.875v-8.25zM3 16.125c0-1.036.84-1.875 1.875-1.875h5.25c1.036 0 1.875.84 1.875 1.875v2.25c0 1.035-.84 1.875-1.875 1.875h-5.25A1.875 1.875 0 013 18.375v-2.25z" clip-rule="evenodd" />
                                            </svg>


                                            <lavel class="collapsText"> Modelos</lavel>
                                        </a>
                                        <a href="{{route('lines-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('lines-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39 1.186 3.548.428a18.849 18.849 0 005.441-5.44c.758-1.16.492-2.629-.428-3.548l-9.58-9.581a3 3 0 00-2.122-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" />
                                            </svg>
                                            <lavel class="collapsText"> Líneas</lavel>
                                        </a>
                                        <a href="{{route('substrates-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('substrates-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M16.098 2.598a3.75 3.75 0 113.622 6.275l-1.72.46V12a.75.75 0 01-.22.53l-.75.75a.75.75 0 01-1.06 0l-.97-.97-7.94 7.94a2.56 2.56 0 01-1.81.75 1.06 1.06 0 00-.75.31l-.97.97a.75.75 0 01-1.06 0l-.75-.75a.75.75 0 010-1.06l.97-.97a1.06 1.06 0 00.31-.75c0-.68.27-1.33.75-1.81L11.69 9l-.97-.97a.75.75 0 010-1.06l.75-.75A.75.75 0 0112 6h2.666l.461-1.72c.165-.617.49-1.2.971-1.682zm-3.348 7.463L4.81 18a1.06 1.06 0 00-.31.75c0 .318-.06.63-.172.922a2.56 2.56 0 01.922-.172c.281 0 .551-.112.75-.31l7.94-7.94-1.19-1.19z" clip-rule="evenodd" />
                                            </svg>
                                            <lavel class="collapsText">Sustratos</lavel>
                                        </a>
                                        <a href="{{route('emulsiones')}}" role="menuitem" class="block p-1   text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md hover:underline  hover:pl-3  @if (request()->routeIs('matrix-area')) underline   font-bold @endif" style="white-space: nowrap;">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path fill-rule="evenodd" d="M10.5 3.798v5.02a3 3 0 01-.879 2.121l-2.377 2.377a9.845 9.845 0 015.091 1.013 8.315 8.315 0 005.713.636l.285-.071-3.954-3.955a3 3 0 01-.879-2.121v-5.02a23.614 23.614 0 00-3 0zm4.5.138a.75.75 0 00.093-1.495A24.837 24.837 0 0012 2.25a25.048 25.048 0 00-3.093.191A.75.75 0 009 3.936v4.882a1.5 1.5 0 01-.44 1.06l-6.293 6.294c-1.62 1.621-.903 4.475 1.471 4.88 2.686.46 5.447.698 8.262.698 2.816 0 5.576-.239 8.262-.697 2.373-.406 3.092-3.26 1.47-4.881L15.44 9.879A1.5 1.5 0 0115 8.818V3.936z" clip-rule="evenodd" />
                                            </svg>
                                            <lavel class="collapsText">Emulsiones</lavel>
                                        </a>






                                        <div x-data="{ isActive: false, open: false}">
                                            <a @click="$event.preventDefault(); open = !open" class="flex items-center my-2 py-1 px-1 text-sm text-left text-gray-800 font-medium  transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if (request()->routeIs('shades-catalog') || request()->routeIs('inks-catalog')) bg-gray-600  text-white  @else text-gray-800  @endif " :class="{'bg-eticomblue': isActive || open}" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'" style="white-space: nowrap;">



                                                <i class="fa-solid fa-palette w-4 h-4  inline mr-2"></i>
                                                <lavel class="collapsText">Tintas</lavel>

                                                <span class="ml-auto" aria-hidden="true">
                                                    <!-- active class 'rotate-180' -->
                                                    <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>


                                            </a>

                                            <div role="menu" x-show="open" class="mt-2 pl-4 pr-1" aria-label="Dashboards">
                                                <a href="{{route('inks-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('inks-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                        <path fill-rule="evenodd" d="M20.599 1.5c-.376 0-.743.111-1.055.32l-5.08 3.385a18.747 18.747 0 00-3.471 2.987 10.04 10.04 0 014.815 4.815 18.748 18.748 0 002.987-3.472l3.386-5.079A1.902 1.902 0 0020.599 1.5zm-8.3 14.025a18.76 18.76 0 001.896-1.207 8.026 8.026 0 00-4.513-4.513A18.75 18.75 0 008.475 11.7l-.278.5a5.26 5.26 0 013.601 3.602l.502-.278zM6.75 13.5A3.75 3.75 0 003 17.25a1.5 1.5 0 01-1.601 1.497.75.75 0 00-.7 1.123 5.25 5.25 0 009.8-2.62 3.75 3.75 0 00-3.75-3.75z" clip-rule="evenodd" />
                                                    </svg>
                                                    Tintas
                                                </a>

                                                <a href="{{route('shades-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('shades-catalog')) underline  font-bold @endif">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25l1.5 1.5.75-.75V8.758l2.276-.61a3 3 0 10-3.675-3.675l-.61 2.277H12l-.75.75 1.5 1.5M15 11.25l-8.47 8.47c-.34.34-.8.53-1.28.53s-.94.19-1.28.53l-.97.97-.75-.75.97-.97c.34-.34.53-.8.53-1.28s.19-.94.53-1.28L12.75 9M15 11.25L12.75 9" />
                                                    </svg>


                                                    Tonos
                                                </a>


                                            </div>
                                        </div>

                                        <a href="{{route('films-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('films-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625z" />
                                                <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
                                            </svg>
                                            <lavel class="collapsText">Películas</lavel>
                                        </a>

                                        <div x-data="{ isActive: false, open: false}">
                                            <a @click="$event.preventDefault(); open = !open" class="flex items-center my-2 py-1 px-1 text-sm text-left text-gray-800 font-medium  transition-colors rounded-md  hover:bg-eticomblue  hover:text-white @if (request()->routeIs('toolings-catalog') || request()->routeIs('materials-catalog') || request()->routeIs('strawberries-burins-catalog')) bg-gray-600  text-white  @else text-gray-800  @endif " :class="{'bg-eticomblue': isActive || open}" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'" style="white-space: nowrap;">

                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                    <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" />
                                                    <path d="M10.076 8.64l-2.201-2.2V4.874a.75.75 0 00-.364-.643l-3.75-2.25a.75.75 0 00-.916.113l-.75.75a.75.75 0 00-.113.916l2.25 3.75a.75.75 0 00.643.364h1.564l2.062 2.062 1.575-1.297z" />
                                                    <path fill-rule="evenodd" d="M12.556 17.329l4.183 4.182a3.375 3.375 0 004.773-4.773l-3.306-3.305a6.803 6.803 0 01-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 00-.167.063l-3.086 3.748zm3.414-1.36a.75.75 0 011.06 0l1.875 1.876a.75.75 0 11-1.06 1.06L15.97 17.03a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                                </svg><lavel class="collapsText">Herramentales</lavel>

                                                <span class="ml-auto" aria-hidden="true">
                                                    <!-- active class 'rotate-180' -->
                                                    <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </span>


                                            </a>

                                            <div role="menu" x-show="open" class="mt-2 pl-4 pr-1" aria-label="Dashboards">


                                                <a href="{{route('materials-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('materials-catalog')) underline  font-bold @endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                        <path d="M12.378 1.602a.75.75 0 00-.756 0L3 6.632l9 5.25 9-5.25-8.622-5.03zM21.75 7.93l-9 5.25v9l8.628-5.032a.75.75 0 00.372-.648V7.93zM11.25 22.18v-9l-9-5.25v8.57a.75.75 0 00.372.648l8.628 5.033z" />
                                                    </svg>



                                                    Materiales
                                                </a>

                                                <a href="{{route('strawberries-burins-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('strawberries-burins-catalog')) underline  font-bold @endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 -mt-1 inline mr-1">
                                                        <path d="M17.004 10.407c.138.435-.216.842-.672.842h-3.465a.75.75 0 01-.65-.375l-1.732-3c-.229-.396-.053-.907.393-1.004a5.252 5.252 0 016.126 3.537zM8.12 8.464c.307-.338.838-.235 1.066.16l1.732 3a.75.75 0 010 .75l-1.732 3.001c-.229.396-.76.498-1.067.16A5.231 5.231 0 016.75 12c0-1.362.519-2.603 1.37-3.536zM10.878 17.13c-.447-.097-.623-.608-.394-1.003l1.733-3.003a.75.75 0 01.65-.375h3.465c.457 0 .81.408.672.843a5.252 5.252 0 01-6.126 3.538z" />
                                                        <path fill-rule="evenodd" d="M21 12.75a.75.75 0 000-1.5h-.783a8.22 8.22 0 00-.237-1.357l.734-.267a.75.75 0 10-.513-1.41l-.735.268a8.24 8.24 0 00-.689-1.191l.6-.504a.75.75 0 10-.964-1.149l-.6.504a8.3 8.3 0 00-1.054-.885l.391-.678a.75.75 0 10-1.299-.75l-.39.677a8.188 8.188 0 00-1.295-.471l.136-.77a.75.75 0 00-1.477-.26l-.136.77a8.364 8.364 0 00-1.377 0l-.136-.77a.75.75 0 10-1.477.26l.136.77c-.448.121-.88.28-1.294.47l-.39-.676a.75.75 0 00-1.3.75l.392.678a8.29 8.29 0 00-1.054.885l-.6-.504a.75.75 0 00-.965 1.149l.6.503a8.243 8.243 0 00-.689 1.192L3.8 8.217a.75.75 0 10-.513 1.41l.735.267a8.222 8.222 0 00-.238 1.355h-.783a.75.75 0 000 1.5h.783c.042.464.122.917.238 1.356l-.735.268a.75.75 0 10.513 1.41l.735-.268c.197.417.428.816.69 1.192l-.6.504a.75.75 0 10.963 1.149l.601-.505c.326.323.679.62 1.054.885l-.392.68a.75.75 0 101.3.75l.39-.679c.414.192.847.35 1.294.471l-.136.771a.75.75 0 101.477.26l.137-.772a8.376 8.376 0 001.376 0l.136.773a.75.75 0 101.477-.26l-.136-.772a8.19 8.19 0 001.294-.47l.391.677a.75.75 0 101.3-.75l-.393-.679a8.282 8.282 0 001.054-.885l.601.504a.75.75 0 10.964-1.15l-.6-.503a8.24 8.24 0 00.69-1.191l.735.268a.75.75 0 10.512-1.41l-.734-.268c.115-.438.195-.892.237-1.356h.784zm-2.657-3.06a6.744 6.744 0 00-1.19-2.053 6.784 6.784 0 00-1.82-1.51A6.704 6.704 0 0012 5.25a6.801 6.801 0 00-1.225.111 6.7 6.7 0 00-2.15.792 6.784 6.784 0 00-2.952 3.489.758.758 0 01-.036.099A6.74 6.74 0 005.251 12a6.739 6.739 0 003.355 5.835l.01.006.01.005a6.706 6.706 0 002.203.802c.007 0 .014.002.021.004a6.792 6.792 0 002.301 0l.022-.004a6.707 6.707 0 002.228-.816 6.781 6.781 0 001.762-1.483l.009-.01.009-.012a6.744 6.744 0 001.18-2.064c.253-.708.39-1.47.39-2.264a6.74 6.74 0 00-.408-2.308z" clip-rule="evenodd" />
                                                    </svg>



                                                    Fresas y buriles
                                                </a>
                                            </div>
                                        </div>



                                        <a href="{{route('cost-tax-factors-catalog')}}" role="menuitem" class="block p-1  transform rotate- text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md  hover:underline hover:pl-3  @if (request()->routeIs('cost-tax-factors-catalog')) underline  font-bold @endif" style="white-space: nowrap;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 -mt-1 inline mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                            </svg>

                                            <lavel class="collapsText"> Impuestos</lavel>
                                        </a>
                                        
                                        <!-- <a
                                                href="{{route('catalogue-areas')}}"
                                                role="menuitem"
                                                class="block p-1   text-base text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md hover:underline  hover:pl-3  @if (request()->routeIs('catalogue-areas')) underline  font-bold @endif"
                                                >
                                                <i class="fa-solid fa-building-user mr-2"></i> 
                                                Areas
                                                </a> -->
                                        <!-- <a href="{{route('matrix-area')}}" role="menuitem" class="block p-1   text-sm text-left text-gray-800 font-medium  transition-colors duration-200 rounded-md hover:underline  hover:pl-3  @if (request()->routeIs('matrix-area')) underline   font-bold @endif" style="white-space: nowrap;">

                                            <i class="fa-solid fa-table-cells mr-2"></i>
                                            <lavel class="collapsText">Matriz</lavel>
                                        </a> -->

    
                                    </div>
                                </div>

                                {{-- CALENDARIO --}}
                                <div  class="text-center pt-10 2xl:pt-24 mx-auto ">
                                    <div id="esconder" class="container flex flex-wrap">
                                        @php $area = DB::table('area')->where('id', Auth::user()->id_area)->first(); @endphp

                                        <a href="/my/profile">
                                            @if (!blank(Auth::user()->profile_photo_path))
                                                <img class="object-cover 2xl:w-12 2xl:h-12 w-12 h-12 rounded-full mb-4 mx-auto" src="{{Auth::user()->profile_photo_path}}" alt="" aria-hidden="true" />
                                                <p class=" -mt-1 2xl:text-base text-sm text-gray-900 "> {{Auth::user()->name}} {{Auth::user()->paternal_name}} {{Auth::user()->maternal_name}}<br><b class="p-1 rounded-sm text-gray-900 font-medium 2xl:text-sm text-xs">{{$area->area_name}}</b></p>

                                            @else
                                                <img class="object-cover 2xl:w-12 2xl:h-12 w-12 h-12 rounded-full mb-4 mx-auto" src="{{asset('img/user.png')}}" alt="" aria-hidden="true" />
                                                <p class=" -mt-1 2xl:text-base text-sm text-gray-900 "> {{Auth::user()->name}} {{Auth::user()->paternal_name}} {{Auth::user()->maternal_name}}<br><b class="p-1 rounded-sm text-gray-900 font-medium 2xl:text-sm text-xs">{{$area->area_name}}</b></p>
                                            @endif
                                        </a>
                                    </div>

                                    <span class="text-base font-medium text-gray-900">
                                        <form action="/logout" method="POST">
                                            @csrf
                                            <button class=" bg-transparent text-gray-900 font-medium hover:text-primarycolor py-2 px-2 border-2 border-gray-500  relative rounded mt-4 mb-8 w-full btn btn3 hover:bg-gray-600 hover:text-white" type="submint">
                                                <svg class="w-5 h-5 float-right ml-1 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg><p id="esconder3">
                                                Cerrar sesión</p>
                                            </button>
                                        </form>
                                    </span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                    <!-- End Navbar -->


                <div id="mainContainer" class="main scrollEdit w-full   h-screen  antialiased bg-gray-100 " style="overflow-y: auto; transition:.5s;">

                    <div class="insta-clone">
                        <nav class="bg-white xl:hidden">
                            <div class="max-w-6xl mx-auto px-4">
                                <div class="flex justify-between">
                                    <div class="flex space-x-4">
                                        <!-- logo -->
                                        <div>
                                            <a href="#" class="flex items-center py-5 px-2 text-gray-700 hover:text-gray-900">
                                                <img class="w-1/2" src="{{asset('img/logo_eticom.svg')}} ">
                                            </a>
                                        </div>
                                    </div>
                                    <!-- mobile button goes here -->
                                    <div class="xl:hidden flex items-center">
                                        <button class="mobile-menu-button">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <!--body start-->
                    <section class="text-gray-800 body-font px-2 animate animate-fadein">
                        <div class="w-full pb-10 pt-5 mx-auto">
                            {{ $slot }}
                        </div>
                        @if(Session::has('message'))
                            @switch( Session::get('alert-class'))
                                @case('success')
                                <div id="alert-3" class="flex p-4 mb-4 text-green-800 border-2 border-green-800 rounded-lg bg-green-50 absolute bottom-10 right-10" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div class="ml-3 text-sm font-medium">
                                    {{Session::get('message')}}
                                    </div>
                                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8" data-dismiss-target="#alert-3" aria-label="Close">
                                        <span class="sr-only">Close</span>
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>     
                                @break
                            @endswitch
                        @endif
                    </section>

                </div>

            </div>
        </main>
    </div>

    @stack('modals')
    @stack('js')
    @livewireScripts
    <script type="text/javascript">
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
            document.getElementById(modalID).classList.toggle("flex");
            document.getElementById(modalID + "-backdrop").classList.toggle("flex");
        }
        const modal = (el) => {
            const toggle = (wrapperEl, mainEl) => {
                document.querySelector('body').classList.toggle('overflow-y-hidden');
                wrapperEl.classList.toggle('opacity-100');
                wrapperEl.classList.toggle('opacity-0');
                wrapperEl.classList.toggle('visible');
                wrapperEl.classList.toggle('invisible');
                mainEl.classList.toggle('-translate-y-full');
                mainEl.classList.toggle('translate-y-0')
            };

            const extractElements = (target) => {
                const wrapper = document.querySelector(`[data-modal='${target}']`);
                const modal = wrapper.querySelector('[data-modal-main]');
                return {
                    wrapper,
                    modal
                };
            };

            const showEvent = new CustomEvent('show', {
                detail: {},
                bubbles: true,
                cancelable: true,
                composed: false,
            });

            const hideEvent = new CustomEvent('hide', {
                detail: {},
                bubbles: true,
                cancelable: true,
                composed: false,
            });

            if (!document.querySelector('[data-modal-toggle]')) {
                return;
            }

            if (!document.querySelector('[data-modal')) {
                return;
            }

            [...document.querySelectorAll('[data-modal-toggle]')].forEach((btn) =>
                btn.addEventListener('click', (event) => {
                    event.preventDefault();
                    const action = btn.getAttribute('data-modal-action');
                    const target = btn.getAttribute('data-modal-toggle');
                    const {
                        wrapper,
                        modal
                    } = extractElements(target);

                    if (action === 'open') {
                        modal.dispatchEvent(showEvent);
                    }
                    if (action === 'close') {
                        modal.dispatchEvent(hideEvent);
                    }
                    toggle(wrapper, modal);
                })
            );
        };

        // init
        modal();

        // Custom event listeners

        // This event fires immediately before the modal is start showing
        // document.querySelector('[data-modal="example"]').addEventListener('show', (event) => {
        //     const sayHi = ['Equipo de protección obligatorio​​'];
        //     const randomNum = Math.floor(Math.random() * sayHi.length);
        //     document.querySelector('#exampleHeader').innerText = sayHi[randomNum];
        //     console.log('show');
        // });

        // This event is fired immediately before modal is start hidding
        document.querySelector('[data-modal="example"]').addEventListener('hide', (event) => {
            console.log('hide');
        });
    </script>
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script>
        // grab everything we need
        const btn = document.querySelector("button.mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");
        const fondo = document.querySelector(".fondo");

        // add event listeners
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
            fondo.classList.toggle("hidden");

        });
    </script>
    <script type="text/javascript">
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.getElementById(modalID + "-backdrop").classList.toggle("hidden");
            document.getElementById(modalID).classList.toggle("flex");
            document.getElementById(modalID + "-backdrop").classList.toggle("flex");
        }
        const modal = (el) => {
            const toggle = (wrapperEl, mainEl) => {
                document.querySelector('body').classList.toggle('overflow-y-hidden');
                wrapperEl.classList.toggle('opacity-100');
                wrapperEl.classList.toggle('opacity-0');
                wrapperEl.classList.toggle('visible');
                wrapperEl.classList.toggle('invisible');
                mainEl.classList.toggle('-translate-y-full');
                mainEl.classList.toggle('translate-y-0')
            };

            const extractElements = (target) => {
                const wrapper = document.querySelector(`[data-modal='${target}']`);
                const modal = wrapper.querySelector('[data-modal-main]');
                return {
                    wrapper,
                    modal
                };
            };

            const showEvent = new CustomEvent('show', {
                detail: {},
                bubbles: true,
                cancelable: true,
                composed: false,
            });

            const hideEvent = new CustomEvent('hide', {
                detail: {},
                bubbles: true,
                cancelable: true,
                composed: false,
            });

            if (!document.querySelector('[data-modal-toggle]')) {
                return;
            }

            if (!document.querySelector('[data-modal')) {
                return;
            }

            [...document.querySelectorAll('[data-modal-toggle]')].forEach((btn) =>
                btn.addEventListener('click', (event) => {
                    event.preventDefault();
                    const action = btn.getAttribute('data-modal-action');
                    const target = btn.getAttribute('data-modal-toggle');
                    const {
                        wrapper,
                        modal
                    } = extractElements(target);

                    if (action === 'open') {
                        modal.dispatchEvent(showEvent);
                    }
                    if (action === 'close') {
                        modal.dispatchEvent(hideEvent);
                    }
                    toggle(wrapper, modal);
                })
            );
        };

        // init
        modal();

        // Custom event listeners

        // This event fires immediately before the modal is start showing
        document.querySelector('[data-modal="example"]').addEventListener('show', (event) => {
            const sayHi = ['Equipo de protección obligatorio​​'];
            const randomNum = Math.floor(Math.random() * sayHi.length);
            document.querySelector('#exampleHeader').innerText = sayHi[randomNum];
            console.log('show');
        });

        // This event is fired immediately before modal is start hidding
        document.querySelector('[data-modal="example"]').addEventListener('hide', (event) => {
            console.log('hide');
        });
    </script>
    <script src="https://unpkg.com/create-file-list"></script>
    <script>
        function dataFileDnD() {
            return {
                files: [],
                fileDragging: null,
                fileDropping: null,
                humanFileSize(size) {
                    const i = Math.floor(Math.log(size) / Math.log(1024));
                    return (
                        (size / Math.pow(1024, i)).toFixed(2) * 1 +
                        " " + ["B", "kB", "MB", "GB", "TB"][i]
                    );
                },
                remove(index) {
                    let files = [...this.files];
                    files.splice(index, 1);

                    this.files = createFileList(files);
                },
                drop(e) {
                    let removed, add;
                    let files = [...this.files];

                    removed = files.splice(this.fileDragging, 1);
                    files.splice(this.fileDropping, 0, ...removed);

                    this.files = createFileList(files);

                    this.fileDropping = null;
                    this.fileDragging = null;
                },
                dragenter(e) {
                    let targetElem = e.target.closest("[draggable]");

                    this.fileDropping = targetElem.getAttribute("data-index");
                },
                dragstart(e) {
                    this.fileDragging = e.target
                        .closest("[draggable]")
                        .getAttribute("data-index");
                    e.dataTransfer.effectAllowed = "move";
                },
                loadFile(file) {
                    const preview = document.querySelectorAll(".preview");
                    const blobUrl = URL.createObjectURL(file);

                    preview.forEach(elem => {
                        elem.onload = () => {
                            URL.revokeObjectURL(elem.src); // free memory
                        };
                    });

                    return blobUrl;
                },
                addFiles(e) {
                    const files = createFileList([...this.files], [...e.target.files]);
                    this.files = files;
                    this.form.formData.files = [...files];
                }
            };
        }
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }


        function openNav() {
            document.getElementById("mySidebar").style.width = "17rem";
            document.getElementById("abrir").style.display = "none";
            document.getElementById("cerrar").style.display = "block";
            document.getElementById("mainContainer").style.marginLeft = "0px";
            document.getElementById("imagotipo").style.display = "none";
            document.getElementById("isotipo").style.display = "block";
            document.getElementById("esconder").style.display = "block";
            document.getElementById("esconder2").style.display = "block";
            document.getElementById("esconder3").style.display = "block";
            document.getElementById("esconder4").style.display = "block";

            var collapsibleElements = document.querySelectorAll(".collapsText");
            for (var i = 0; i < collapsibleElements.length; i++) {
                collapsibleElements[i].classList.add("navOpacity-100");
                collapsibleElements[i].classList.remove("navOpacity-0");
            }


            sessionStorage.setItem("nav", 1);

        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "70px";
            document.getElementById("abrir").style.display = "block";
            document.getElementById("cerrar").style.display = "none";
            document.getElementById("mainContainer").style.marginLeft = "0px";
            document.getElementById("imagotipo").style.display = "block";
            document.getElementById("isotipo").style.display = "none";
            document.getElementById("esconder").style.display = "none";
            document.getElementById("esconder2").style.display = "none";
            document.getElementById("esconder3").style.display = "none";
            document.getElementById("esconder4").style.display = "none";

            var collapsibleElements = document.querySelectorAll(".collapsText");
            for (var i = 0; i < collapsibleElements.length; i++) {
                collapsibleElements[i].classList.add("navOpacity-0");
                collapsibleElements[i].classList.remove("navOpacity-100");
            }

            sessionStorage.setItem("nav", 0);
            
        }
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script> -->
</body>

</html>