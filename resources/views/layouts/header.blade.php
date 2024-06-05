<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ARTEN/KIRCOF</title>

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

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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

<body>
    <div id="container" class="text-text1 relative flex h-screen">
        <!-- Desktop sidebar -->
        <div class="bg-primaryColor z-20 hidden w-60 flex-shrink-0 overflow-y-auto shadow-md md:block">
            <div class="bg-primaryColor py-1" style="height:100%">

                @if (Auth::user())
                <div class="mt-5 flex justify-center justify-items-center">
                    @if (Auth::user()->profile_photo)
                    <img class="mx-auto h-24 w-24 rounded-full object-cover" aria-hidden="true"
                        src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}" alt="Avatar" />
                    @else
                    <img class="mx-auto h-24 w-24 rounded-full object-cover" aria-hidden="true"
                        src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="Avatar" />
                    @endif
                </div>
                <div class="w-full pt-2 text-center text-base font-medium">
                    <p>{{ Auth::user()->name }}</p>
                    <p>{{ Auth::user()->email }}</p>
                    <p>{{ Auth::user()->area->name }}</p>
                    <a class="menu flex justify-center cursor-pointer" href="{{ route('profile.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                    </a>
                </div>
                @endif
                <ul class="mt-5">
                    @if (Auth::user()->type_user == 1)
                    <li class="menu {{ request()->routeIs('userCatalog.index') ? 'active' : '' }}">
                        @yield('userCatalog')
                        <a class="inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                            href="{{ route('userCatalog.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                    <li class="menu {{ request()->routeIs('customers.index') ? 'active' : '' }}">
                        @yield('customers')
                        <a class="inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                            href="{{ route('customers.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            <span class="ml-4">Clientes</span>
                        </a>
                    </li>
                    @endif
                    <li class="menu {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                        @yield('projects')
                        <a class="inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                            href="{{ route('projects.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                    @if (Auth::user()->type_user == 1)
                        <li class="menu {{ request()->routeIs('activities-reports.index') ? 'active' : '' }}">
                            @yield('all-activities')
                            <a class="inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                                href="{{ route('activities-reports.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
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
                    {{-- <li class="menu">
                        @yield('permits')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black5')"
                            href="{{ route('permits.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-run" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M13 4m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M4 17l5 1l.75 -1.5"></path>
                                <path d="M15 21l0 -4l-4 -3l1 -6"></path>
                                <path d="M7 12l0 -3l5 -1l3 3l3 1"></path>
                            </svg>
                            <span class="ml-4">Permisos</span>
                        </a>
                    </li> --}}
                    {{-- <li class="menu">
                        @yield('control_activities')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black6')"
                            href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-user"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5"></path>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M4 11h16"></path>
                                <path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2"></path>
                            </svg>
                            <span class="ml-4">Control de actividades</span>
                        </a>
                    </li> --}}
                    {{-- <li class="menu">
                        @yield('petty_cash')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black7')"
                            href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dollar"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                                </path>
                                <path d="M12 3v3m0 12v3"></path>
                            </svg>
                            <span class="ml-4">Caja chica</span>
                        </a>
                    </li> --}}
                </ul>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <div class="pt-20 text-center">
                        <span class="text-base font-semibold">
                            <button value="Log out" type="submit"
                                class="border-secundaryColor hover: rounded border bg-transparent px-4 py-2 font-semibold hover:border-white">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-logout float-right ml-2 mt-1 h-5 w-5" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
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
            x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" x-data="{
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
            }" class="bg-primaryColor block md:hidden">
            <div
                class="@if (Route::currentRouteName() == 'projects.reports.index') justify-between @else justify-end @endif flex p-4">
                @if (Route::currentRouteName() == 'projects.reports.index')
                <a class="inline-flex w-auto items-center text-base font-semibold transition-colors duration-150"
                    href="{{ route('projects.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    <span class="ml-2">Regresar</span>
                </a>
                @endif
                <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                    :aria-controls="$id('dropdown-button')" type="button"
                    class="flex items-center gap-2 rounded-md px-5 py-2.5 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6l16 0" />
                        <path d="M4 12l16 0" />
                        <path d="M4 18l16 0" />
                    </svg>
                </button>
            </div>
            <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')"
                style="display: none;"
                class="bg-primaryColor textg-white absolute z-40 w-full rounded-b-md py-4 shadow-md">
                <ul>
                    <li class="menu">
                        @yield('profile')
                        <a class="@yield('black1') inline-flex w-full text-base font-semibold transition-colors duration-150"
                            href="{{ route('profile.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                            <span class="ml-4">Perfil</span>
                        </a>
                    </li>
                    @if (Auth::user()->type_user == 1)
                    <li class="menu">
                        @yield('userCatalog')
                        <a class="@yield('black2') inline-flex w-full text-base font-semibold transition-colors duration-150"
                            href="{{ route('userCatalog.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                    <li class="menu">
                        @yield('customers')
                        <a class="@yield('black3') inline-flex w-full text-base font-semibold transition-colors duration-150"
                            href="{{ route('customers.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            <span class="ml-4">Clientes</span>
                        </a>
                    </li>
                    @endif
                    <li class="menu">
                        @yield('projects')
                        <a class="@yield('black4') inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                            href="{{ route('projects.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                    <li class="menu">
                        @yield('activities-reports')
                        <a class="@yield('black5') inline-flex w-full items-center text-base font-semibold transition-colors duration-150"
                            href="{{ route('activities-reports.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
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
                    {{-- <li class="menu">
                        @yield('permits')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black5')"
                            href="{{ route('permits.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-run" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M13 4m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M4 17l5 1l.75 -1.5"></path>
                                <path d="M15 21l0 -4l-4 -3l1 -6"></path>
                                <path d="M7 12l0 -3l5 -1l3 3l3 1"></path>
                            </svg>
                            <span class="ml-4">Permisos</span>
                        </a>
                    </li> --}}
                    {{-- <li class="menu">
                        @yield('control_activities')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black6')"
                            href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-user"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5"></path>
                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M4 11h16"></path>
                                <path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2"></path>
                            </svg>
                            <span class="ml-4">Control de actividades</span>
                        </a>
                    </li> --}}
                    {{-- <li class="menu">
                        @yield('petty_cash')
                        <a class=" inline-flex items-center w-full text-base font-semibold transition-colors duration-150   @yield('black7')"
                            href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dollar"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                                </path>
                                <path d="M12 3v3m0 12v3"></path>
                            </svg>
                            <span class="ml-4">Caja chica</span>
                        </a>
                    </li> --}}
                    <li class="menu">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button value="Log out" type="submit"
                                class="inline-flex w-full items-center text-base font-semibold transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
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