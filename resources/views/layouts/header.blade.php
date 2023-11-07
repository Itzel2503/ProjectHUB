<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <script src="https://unpkg.com/flowbite@1.4.5/dist/flowbite.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.5/dist/flowbite.min.css" /> -->

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
            background-color: #968C60;
            /* color of the scroll thumb */
            border-radius: 20px;
            /* roundness of the scroll thumb */
            border: 2.5px solid #968C60;
            /* creates padding around scroll thumb */

        }

        .scrollEdit::-webkit-scrollbar-thumb:hover {
            background: #154854;
            box-shadow: 0 0 2px 1px rgb(0 0 0 / 20%);
            border: #ccc;
        }
    </style>
<body>
    <div class="flex h-screen bg-gray-50 " :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- Desktop sidebar -->
        <div class="z-20 hidden w-64 overflow-y-auto bg-white  md:block flex-shrink-0 shadow-md bg-primarycolor">
            <div class="py-1 text-gray-400  bg-primarycolor" style="height:100%">
                <a class="ml-6 text-lg font-bold text-gray-100 " href="/">
                    <div class="flex items-center justify-center w-full">
                        <!-- <img class="w-full" src="{{ asset('/img/SAGACE_logo_v4_white-01.png')}}" alt=""> -->
                    </div>
                </a>
                @if (Auth::user())
                <a href="">
                    <img class="object-cover w-20 h-20 rounded-full mx-auto" aria-hidden="true" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="Avatar">
                    <div class="pt-2 mb-10  w-full text-center  text-base text-white">{{ Auth::user()->name }}</div>
                </a>
                @endif

                <ul class="mt-6">
                    <li class="relative px-6 py-3">
                        @yield('perfil')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black1')" href="{{ route('perfil.index' ) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                            </svg>
                            <span class="ml-4">Perfil</span>
                        </a>
                    </li>
                    <li class="relative px-6 py-3">
                        @yield('permits')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black2')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-run" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M13 4m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                <path d="M4 17l5 1l.75 -1.5"></path>
                                <path d="M15 21l0 -4l-4 -3l1 -6"></path>
                                <path d="M7 12l0 -3l5 -1l3 3l3 1"></path>
                            </svg>
                            <span class="ml-4">Permisos</span>
                        </a>
                    </li>
                    @if (Auth::user()->type_user == 1)
                    <li class="relative px-6 py-3">
                        @yield('usuarios')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100 @yield('black3')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                            </svg>
                            <span class="ml-4">Usuarios</span>
                        </a>
                    </li>
                    <li class="relative px-6 py-3">
                        @yield('control_activities')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black4')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                    </li>
                    <li class="relative px-6 py-3">
                        @yield('petty_cash')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black5')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2"></path>
                                <path d="M12 3v3m0 12v3"></path>
                            </svg>
                            <span class="ml-4">Caja chica</span>
                        </a>
                    </li>
                    @endif
                </ul>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <div class="text-center pt-20">
                        <span class="text-base font-semibold text-gray-700">
                            <button value="Log out" type="submit" class="bg-transparent text-gray-400 font-semibold hover:text-white hover:bg-secondarycolor py-2 px-4 border border-gray-400 hover:border-secondarycolor rounded">
                                <svg class="w-5 h-5 float-right ml-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg> Cerrar sesión
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
        <div style="z-index: 55;" class="fixed inset-y-0  flex-shrink-0 w-64 mt-16 overflow-y-auto bg-primarycolor  md:hidden" x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
            <div class="py-4 text-gray-400 ">

                @if (Auth::user())
                <a href="">
                    <!-- <img class="object-cover w-20 h-20 rounded-full mx-auto" src="{{Auth::user()->img}}" alt="" aria-hidden="true" /> -->
                    <div class="pt-2 mb-10  w-full text-center  text-base text-white">
                        {{ Auth::user()->name }}
                    </div>
                </a>
                @endif
                <ul class="mt-6">
                    <li class="relative px-6 py-3">
                        @yield('permits')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black1')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-4">Permisos</span>
                        </a>
                    </li>
                    @if (Auth::user()->type_user == 1)
                    <li class="relative px-6 py-3">
                        @yield('usuarios')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100 @yield('black2')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="ml-4">Usuarios</span>
                        </a>
                    </li>
                    <li class="relative px-6 py-3">
                        @yield('control_activities')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black3')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="ml-4">Control de actividades</span>
                        </a>
                    </li>
                    <li class="relative px-6 py-3">
                        @yield('petty_cash')
                        <a class="inline-flex items-center w-full text-base font-semibold transition-colors duration-150 hover:text-gray-100  @yield('black4')" href="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="ml-4">Caja chica</span>
                        </a>
                    </li>
                    @endif
                </ul>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <div class="text-center pt-20">
                        <span class="text-base font-semibold text-gray-700">
                            <button value="Log out" type="submit" class="bg-transparent text-gray-400 font-semibold hover:text-white hover:bg-secondarycolor py-2 px-4 border border-gray-400 hover:border-secondarycolor rounded">
                                <svg class="w-5 h-5 float-right ml-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Cerrar sesión </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-col flex-1 w-full overflow-x-auto">
            <main class="h-full overflow-y-auto bg-gray-100 pb-20 scrollEdit">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>