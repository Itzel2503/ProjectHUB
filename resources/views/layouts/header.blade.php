<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>COMA</title>

    <!-- Logo -->
    <link rel="icon" href="{{ asset('logos/favicon_v2.png') }}" type="image/png">

    @livewireStyles
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    {{-- https://tabler.io/icons --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- fullcalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

    <!-- alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Toastr -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Date range picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .scrollEdit::-webkit-scrollbar {
            width: 6px;
            /* width of the entire scrollbar */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-track {
            background: #ccc;
            /* color of the tracking area */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-thumb {
            background-color: #324d57;
            /* color of the scroll thumb */
            border-radius: 20px;
            /* roundness of the scroll thumb */
            border: 2.5px solid #324d57;
            /* creates padding around scroll thumb */

        }

        .scrollEdit::-webkit-scrollbar-thumb:hover {
            background: #154854;
            box-shadow: 0 0 2px 1px rgb(0 0 0 / 20%);
            border: #ccc;
        }

        /* Estilos para pantallas de hasta 640px de ancho */
        @media (max-width: 767px) {
            #container {
                flex-direction: column;
                /* Cambia la disposición a vertical en pantallas pequeñas */
            }
        }
    </style>
</head>

<body>
    <div id="container" class="text-text1 relative flex h-screen">
        <!-- Desktop sidebar -->
        <div
            class="bg-coma-gradient z-20 hidden h-screen w-60 flex-shrink-0 overflow-y-auto text-white shadow-md md:block">
            <div class="bg-full bg-position-full h-full py-1">
                @if (Auth::user())
                    @php
                        $user = DB::table('users')
                            ->where('id', Auth::user()->id)
                            ->first();
                    @endphp
                    <div class="relative mt-5 flex justify-center justify-items-center">
                        @if (Auth::user()->profile_photo)
                            <img class="z-10 mx-auto h-24 w-24 rounded-full object-cover" aria-hidden="true"
                                src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}" alt="Avatar" />
                        @else
                            <img class="z-10 mx-auto h-24 w-24 rounded-full object-cover" aria-hidden="true"
                                src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="Avatar" />
                        @endif
                        <a class="absolute z-50 mx-auto h-24 w-24 cursor-pointer justify-center justify-items-center rounded-full bg-gray-900 opacity-0 hover:opacity-80"
                            href="{{ route('profile.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-edit mx-auto mt-8" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                        </a>
                    </div>
                    <div class="mb-10 w-full pt-2 text-center text-base">
                        <p class="font-medium">{{ Auth::user()->name }}</p>
                        <p class="font-light">{{ Auth::user()->email }}</p>
                        <span class="text-xs">-</span>
                        <p class="text-sm font-light">{{ Auth::user()->area->name }}</p>
                    </div>
                @endif
                <ul class="mt-5">
                    @if ($user->type_user == 1)
                        <li class="menu {{ request()->routeIs('userCatalog.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('userCatalog.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                </svg>
                                <span class="ml-4">Usuarios</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                        <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                            href="{{ route('projects.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                <path
                                    d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                <path d="M5 8h4" />
                                <path d="M9 16h4" />
                                <path
                                    d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" />
                                <path d="M14 9l4 -1" />
                                <path d="M16 16l3.923 -.98" />
                            </svg>
                            <span class="ml-4">Proyectos</span>
                        </a>
                    </li>
                    @if ($user->type_user != 3)
                        <li class="menu {{ request()->routeIs('activities-reports.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('activities-reports.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M18.5 18.5l2.5 2.5" />
                                    <path d="M4 6h16" />
                                    <path d="M4 12h4" />
                                    <path d="M4 18h4" />
                                </svg>
                                <span class="ml-4">Actividades / Reportes</span>
                            </a>
                        </li>
                    @endif
                    {{-- <li class="menu {{ request()->routeIs('notion.index') ? 'active' : '' }}">
                        <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                            href="{{ route('notion.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-note">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M13 20l7 -7" />
                                <path
                                    d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                            </svg>
                            <span class="ml-4">Notas</span>
                        </a>
                    </li> --}}
                    @if ($user->type_user == 1 && $user->area_id == 1)
                        <li class="menu">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="https://coma.artendigital.mx/projects/30/reports">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-star">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" />
                                </svg>
                                <span class="ml-4">Administración</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->type_user == 1)
                        <li class="menu {{ request()->routeIs('effortPoints.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('effortPoints.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12.003 21c-.732 .001 -1.465 -.438 -1.678 -1.317a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886 .215 1.325 .957 1.318 1.694" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M19.001 15.5v1.5" />
                                    <path d="M19.001 21v1.5" />
                                    <path d="M22.032 17.25l-1.299 .75" />
                                    <path d="M17.27 20l-1.3 .75" />
                                    <path d="M15.97 17.25l1.3 .75" />
                                    <path d="M20.733 20l1.3 .75" />
                                </svg>
                                <span class="ml-4">Story Points</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->type_user == 1 && $user->area_id == 1)
                        <li class="menu {{ request()->routeIs('storage.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('storage.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-archive">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                                    <path d="M10 12l4 0" />
                                </svg>
                                <span class="ml-4">Inventario</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <div class="py-5 pt-20 text-center">
                        <span class="text-base font-normal">
                            <button value="Log out" type="submit"
                                class="rounded border border-gray-500 bg-transparent px-4 py-2 font-normal hover:border-white">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-logout float-right ml-2 mt-1 h-5 w-5"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div x-on:keydown.escape.prevent.stop="close($refs.button)"
            x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']"
            x-data="{
                open: false,
                toggle() {
                    if (this.open) {
                        return this.close()
                    }
                    this.$refs.button.focus()
                    this.open = true
                },
            
                close(focusAfter) {
                    if (!this.open) return
                    this.open = false
                    focusAfter && focusAfter.focus()
                }
            }" class="bg-secundaryColor block md:hidden">
            <div class="@if (Route::currentRouteName() == 'projects.reports.index') justify-between @else justify-end @endif flex p-4">
                @if (Route::currentRouteName() == 'projects.reports.index')
                    <a class="inline-flex w-auto items-center text-base font-normal transition-colors duration-150"
                        href="{{ route('projects.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 14l-4 -4l4 -4" />
                            <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                        </svg>
                        <span class="ml-2">Regresar</span>
                    </a>
                @endif
                <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                    :aria-controls="$id('dropdown-button')" type="button"
                    class="border-primaryColor flex items-center gap-2 rounded-md border border-solid px-5 py-2.5 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg>
                </button>
            </div>
            <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')"
                style="display: none;"
                class="bg-secundaryColor absolute z-40 w-full rounded-b-md py-4 text-white shadow-md">
                <ul>
                    <li class="menu {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                        <a class="inline-flex w-full text-base font-normal transition-colors duration-150"
                            href="{{ route('profile.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            <span class="ml-4">Perfil</span>
                        </a>
                    </li>
                    @if (Auth::user()->type_user == 1)
                        <li class="menu {{ request()->routeIs('userCatalog.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full text-base font-normal transition-colors duration-150"
                                href="{{ route('userCatalog.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-users-group" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                </svg>
                                <span class="ml-4">Usuarios</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                        <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                            href="{{ route('projects.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                <path
                                    d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" />
                                <path d="M5 8h4" />
                                <path d="M9 16h4" />
                                <path
                                    d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" />
                                <path d="M14 9l4 -1" />
                                <path d="M16 16l3.923 -.98" />
                            </svg>
                            <span class="ml-4">Proyectos</span>
                        </a>
                    </li>
                    @if ($user->type_user != 3)
                        <li class="menu {{ request()->routeIs('activities-reports.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('activities-reports.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-list-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M18.5 18.5l2.5 2.5" />
                                    <path d="M4 6h16" />
                                    <path d="M4 12h4" />
                                    <path d="M4 18h4" />
                                </svg>
                                <span class="ml-4">Actividades / Reportes</span>
                            </a>
                        </li>
                    @endif
                    {{-- <li class="menu {{ request()->routeIs('notion.index') ? 'active' : '' }}">
                        <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                            href="{{ route('notion.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-note">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M13 20l7 -7" />
                                <path
                                    d="M13 20v-6a1 1 0 0 1 1 -1h6v-7a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7" />
                            </svg>
                            <span class="ml-4">Notas</span>
                        </a>
                    </li> --}}
                    @if ($user->type_user == 1)
                        <li class="menu {{ request()->routeIs('effortPoints.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('effortPoints.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-settings-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12.003 21c-.732 .001 -1.465 -.438 -1.678 -1.317a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886 .215 1.325 .957 1.318 1.694" />
                                    <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                    <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M19.001 15.5v1.5" />
                                    <path d="M19.001 21v1.5" />
                                    <path d="M22.032 17.25l-1.299 .75" />
                                    <path d="M17.27 20l-1.3 .75" />
                                    <path d="M15.97 17.25l1.3 .75" />
                                    <path d="M20.733 20l1.3 .75" />
                                </svg>
                                <span class="ml-4">Story Points</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->type_user == 1 && $user->area_id == 1)
                        <li class="menu">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="https://coma.artendigital.mx/projects/30/reports">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="currentColor"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-star">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" />
                                </svg>
                                <span class="ml-4">Administración</span>
                            </a>
                        </li>
                    @endif
                    @if ($user->type_user == 1 && $user->area_id == 1)
                        <li class="menu {{ request()->routeIs('storage.index') ? 'active' : '' }}">
                            <a class="inline-flex w-full items-center text-base font-normal transition-colors duration-150"
                                href="{{ route('storage.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-archive">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                                    <path d="M10 12l4 0" />
                                </svg>
                                <span class="ml-4">Inventario</span>
                            </a>
                        </li>
                    @endif
                    <li class="menu">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button value="Log out" type="submit"
                                class="inline-flex w-full items-center text-base font-normal transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                                <span class="ml-4">Cerrar sesión</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex w-full flex-1 flex-col overflow-x-auto">
            <main class="scrollEdit container mx-auto h-full pb-20">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
