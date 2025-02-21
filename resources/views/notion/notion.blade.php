@extends('layouts.header')
@section('content')
    <style>
        /* Estilos para los círculos */
        .circle {
            font-size: 24px;
            cursor: pointer;
            margin: 5px;
            transition: transform 0.2s ease, font-size 0.2s ease;
        }

        /* Estilo cuando un círculo está seleccionado */
        .circle.selected {
            font-size: 36px;
            /* Tamaño más grande */
            transform: scale(1.2);
            /* Efecto de agrandar */
        }

        /* Ocultar el checkbox real */
        .circle-checkbox {
            display: none;
        }

        /* Estilos para los iconos */
        .icon-event {
            font-size: 24px;
            cursor: pointer;
            margin: 5px;
            transition: transform 0.2s ease, font-size 0.2s ease;
        }

        /* Estilo cuando un icono está seleccionado */
        .icon-event.selected {
            font-size: 36px;
            /* Tamaño más grande */
            transform: scale(1.2);
            /* Efecto de agrandar */
        }

        /* Ocultar el checkbox real */
        .icon-checkbox {
            display: none;
        }

        #calendar {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 1.75rem;
            padding-left: 1.75rem;
            padding-right: 1.75rem;
        }
    </style>
    <div class="mx-auto mt-4 w-full">
        <div class="w-full space-y-6 px-4 py-3">
            <h1 class="tiitleHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-week">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                    <path d="M7 14h.013" />
                    <path d="M10.01 14h.005" />
                    <path d="M13.01 14h.005" />
                    <path d="M16.015 14h.005" />
                    <path d="M13.015 17h.005" />
                    <path d="M7.01 17h.005" />
                    <path d="M10.01 17h.005" />
                </svg>
                <span class="ml-4 text-xl">Calendario</span>
            </h1>
        </div>
        <div class="px-4 py-4 sm:rounded-lg">
            {{-- NAVEGADOR --}}
            <div class="flex flex-wrap justify-end text-sm lg:text-base">
                <!-- BTN NEW -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-1/4 md:px-0">
                    <button id="btn-new" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <span>Evento</span>
                    </button>
                </div>
            </div>
        </div>
        <div id='calendar'></div>
        {{-- MODAL CREATE NOTE --}}
        <div id="modal-create" class="left-0 top-20 z-50 hidden max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                    <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Nuevo evento</h3>
                        <svg id="btn-close-modal-create" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <form id="form-new-notion" action="{{ route('calendar.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modalBody">
                            <div class="md-3/4 mb-5 mt-5 flex w-full flex-col">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="">
                                        Color
                                    </h5>
                                    <div class="flex justify-between mt-2">
                                        <!-- Círculo Rojo -->
                                        <label>
                                            <input type="checkbox" name="color" value="#dc2626" class="circle-checkbox">
                                            <span class="circle">🔴</span>
                                        </label>
                                        <!-- Círculo Verde -->
                                        <label>
                                            <input type="checkbox" name="color" value="#4d7c0f" class="circle-checkbox">
                                            <span class="circle">🟢</span>
                                        </label>
                                        <!-- Círculo Azul -->
                                        <label>
                                            <input type="checkbox" name="color" value="#3b82f6" class="circle-checkbox">
                                            <span class="circle">🔵</span>
                                        </label>
                                        <!-- Círculo Amarillo -->
                                        <label>
                                            <input type="checkbox" name="color" value="#facc15" class="circle-checkbox">
                                            <span class="circle">🟡</span>
                                        </label>
                                        <!-- Círculo Morado -->
                                        <label>
                                            <input type="checkbox" name="color" value="#7e22ce" class="circle-checkbox">
                                            <span class="circle">🟣</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="">
                                        Icono
                                    </h5>
                                    <div class="flex justify-between mt-2">
                                        <!--  🚀 Cohete: Propuestas; Lanzamientos -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F680;" class="icon-checkbox">
                                            <span class="icon-event">&#x1F680;
                                            </span>
                                        </label>
                                        <!-- 🔵 Círculo morado grande: Seguimiento -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F535;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x1F535;</span>
                                        </label>
                                        <!-- 💵 Dólar: Finanzas -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F4B5;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x1F4B5;</span>
                                        </label>
                                        <!-- 📅 Calendario: Cita -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F4C5;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x1F4C5;</span>
                                        </label>
                                        <!-- 💡 Bombilla: Ideas -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F4A1;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x1F4A1;</span>
                                        </label>
                                        <!-- 📎 Clip: Notas -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x1F4CE;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x1F4CE;</span>
                                        </label>
                                        <!-- ⭐ Estrella: Top -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x2B50;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x2B50;</span>
                                        </label>
                                        <!-- ⏸️ Doble barra vertical: Pausa -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x23F8;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x23F8;</span>
                                        </label>
                                        <!-- ✉️ Correo: Enviar -->
                                        <label>
                                            <input type="checkbox" name="icon" value="&#x2709;"
                                                class="icon-checkbox">
                                            <span class="icon-event">&#x2709;</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="title">
                                            Título <p class="text-red-600">*</p>
                                        </h5>
                                        <input required type="text" name="title" id="title" class="inputs"
                                            placeholder="Título general de la actividad">
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-row justify-end">
                                    <div class="flex w-auto px-3">
                                        <h5 class="font-semibold mr-2" for="allDay">
                                            Todo el día
                                        </h5>
                                        <input type="checkbox" name="allDay" id="allDay"
                                            style="height: 24px; width: 24px;">
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="dateFirst">
                                            Fecha<p class="text-red-600">*</p>
                                        </h5>
                                        <input required type="date" name="dateFirst" id="dateFirst" class="inputs">
                                    </div>
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="starTime">
                                            Hora
                                        </h5>
                                        <input required type="time" name="starTime" id="starTime" class="inputs">
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <input required type="date" name="dateSecond" id="dateSecond" class="inputs">
                                        <small id="smallDate" class="text-red-600 text-xsm"></small>
                                    </div>
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <input required type="time" name="endTime" id="endTime" class="inputs">
                                        <small id="smallTime" class="text-red-600 text-xsm"></small>
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-row">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="repeat">
                                            Repetir
                                        </h5>
                                        <select name="repeat" id="repeat" class="inputs">
                                            <option selected value="Once">No se repite</option>
                                            <option value="Dairy">Todos los días</option>
                                            <option value="Weeks">Todas las semanas</option>
                                            <option value="Months">Todos los meses</option>
                                            <option value="Years">Todos los años</option>
                                        </select>
                                    </div>
                                    <div id="divDeadline" class="mb-6 flex w-full flex-col px-3 hidden">
                                        <h5 class="inline-flex font-semibold" for="deadline">
                                            Fecha límite
                                        </h5>
                                        <input type="date" name="deadline" id="deadline" class="inputs">
                                        <small id="smallDeadline" class="text-red-600 text-xsm"></small>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6 flex flex-row">
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="phone">
                                            Prioridad
                                        </h5>
                                        <div class="flex justify-center gap-12">
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority1" id="priority1" value="Alto"
                                                    class="priority-checkbox border-red-600 bg-red-600"
                                                    style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                                <label for="priority1" class="mt-2">Alto</label>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority2" id="priority2" value="Medio"
                                                    class="priority-checkbox border-yellow-400 bg-yellow-400"
                                                    style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                                <label for="priority2" class="mt-2">Medio</label>
                                            </div>
                                            <div class="flex flex-col items-center">
                                                <input type="checkbox" name="priority3" id="priority3" value="Bajo"
                                                    class="priority-checkbox border-secondary bg-secondary"
                                                    style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                                <label for="priority3" class="mt-2">Bajo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="-mx-3 flex flex-row">
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="project_id">
                                            Proyecto
                                        </h5>
                                        <select name="project_id" id="project_id" class="inputs">
                                            <option value="0" selected>Selecciona...</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="dropdown-button-users">
                                            Delegar
                                        </h5>
                                        <div id="dropdown-button-users" class="relative">
                                            <!-- Button -->
                                            <button onclick="toggleDropdownUser()" type="button"
                                                class="inputs flex justify-between text-base">
                                                Selecciona...
                                                <!-- Heroicon: chevron-down -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-chevron-down" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M6 9l6 6l6 -6" />
                                                </svg>
                                            </button>
                                            <!-- Panel -->
                                            <div id="dropdown-panel-users" style="display: none;"
                                                class="absolute right-0 top-6 z-10 w-full rounded-md bg-gray-100">
                                                @foreach ($allUsers as $user)
                                                    <label class="block px-4 py-2">
                                                        <input name="delegate_id[]" type="checkbox"
                                                            value="{{ $user->id }}" class="delegate mr-2">
                                                        {{ $user->name }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modalFooter">
                            <button type="submit" class="btnSave">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-floppy mr-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END MODAL CREATE NOTE --}}
        {{-- MODAL SHOW --}}
        <div id="modal-show" class="hidden left-0 top-20 z-50 block max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="md:w-3/4 mx-auto flex flex-col overflow-y-auto rounded-lg" style="max-height: 90%;">
                    <div id="color-note"
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg  px-6 py-4 text-white">
                        <h3 id="title-note" class=" title-font w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        </h3>
                        <svg id="btn-close-modal-show" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
                        <div class="w-full px-5 lg:w-1/2 border-gray-400 lg:border-r-2">
                            <div class="mb-6 w-auto">
                                <div id="div-edit-btn" class="-mx-3 flex flex-row justify-between">
                                    <div id="btn-delete" class="flex flex-col px-3 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash text-red-600">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                    </div>
                                    <div id="btn-edit" class="flex flex-col px-3 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </div>
                                </div>
                                <div id="body-show" class="block">
                                    <div class="md-3/4 mb-5 flex w-full flex-col">
                                        <div class="-mx-3 mb-6 flex flex-row">
                                            <div class="flex w-full flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Fecha</h5>
                                                <p id="date-start"></p>
                                                <p id="date-end"></p>
                                            </div>
                                            <div class="flex w-3/5 flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Repetir</h5>
                                                <p id="repeat-note"></p>
                                            </div>
                                        </div>
                                        <div class="-mx-3 mb-6 flex flex-row">
                                            <div class="flex w-full flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Creado por</h5>
                                                <p id="user-note"></p>
                                            </div>
                                            <div class="flex w-full flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Delegado/s</h5>
                                                <p id="delegate-note"></p>
                                            </div>
                                        </div>
                                        <div class="-mx-3 mb-6 flex flex-row">
                                            <div class="flex w-full flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Prioridad</h5>
                                                <p id="priority-note"></p>
                                            </div>
                                            <div class="flex w-full flex-col px-3">
                                                <h5 class="text-text2 text-lg font-bold">Proyecto</h5>
                                                <p id="project-note"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form id="edit-notion-form">
                                    <div id="body-edit" class="hidden">
                                        <div class="md-3/4 mb-5 mt-5 flex w-full flex-col">
                                            <input name="id_note" id="id-edit" type="hidden">
                                            <input name="repeat-edit-input" id="repeat-edit-input" type="hidden">
                                            <input name="dateFirst-edit-input" id="dateFirst-edit-input" type="hidden">
                                            <input name="dateSecond-edit-input" id="dateSecond-edit-input"
                                                type="hidden">
                                            <input name="deadline-edit-input" id="deadline-edit-input" type="hidden">
                                            <div class="mb-6 flex w-full flex-col px-3">
                                                <h5 class="inline-flex font-semibold" for="file">
                                                    Color
                                                </h5>
                                                <div class="flex justify-between mt-2">
                                                    <!-- Círculo Rojo -->
                                                    <label>
                                                        <input type="checkbox" name="color" value="#dc2626"
                                                            class="circle-checkbox circle-color-edit">
                                                        <span class="circle">🔴</span>
                                                    </label>
                                                    <!-- Círculo Verde -->
                                                    <label>
                                                        <input type="checkbox" name="color" value="#4d7c0f"
                                                            class="circle-checkbox circle-color-edit">
                                                        <span class="circle">🟢</span>
                                                    </label>
                                                    <!-- Círculo Azul -->
                                                    <label>
                                                        <input type="checkbox" name="color" value="#3b82f6"
                                                            class="circle-checkbox circle-color-edit">
                                                        <span class="circle">🔵</span>
                                                    </label>
                                                    <!-- Círculo Amarillo -->
                                                    <label>
                                                        <input type="checkbox" name="color" value="#facc15"
                                                            class="circle-checkbox circle-color-edit">
                                                        <span class="circle">🟡</span>
                                                    </label>
                                                    <!-- Círculo Morado -->
                                                    <label>
                                                        <input type="checkbox" name="color" value="#7e22ce"
                                                            class="circle-checkbox circle-color-edit">
                                                        <span class="circle">🟣</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="mb-6 flex w-full flex-col px-3">
                                                <h5 class="inline-flex font-semibold" for="">
                                                    Icono
                                                </h5>
                                                <div class="flex justify-between mt-2">
                                                    <!--  🚀 Cohete: Propuestas; Lanzamientos -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F680;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F680;
                                                        </span>
                                                    </label>
                                                    <!-- 🔵 Círculo morado grande: Seguimiento -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F535;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F535;</span>
                                                    </label>
                                                    <!-- 💵 Dólar: Finanzas -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F4B5;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F4B5;</span>
                                                    </label>
                                                    <!-- 📅 Calendario: Cita -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F4C5;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F4C5;</span>
                                                    </label>
                                                    <!-- 💡 Bombilla: Ideas -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F4A1;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F4A1;</span>
                                                    </label>
                                                    <!-- 📎 Clip: Notas -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x1F4CE;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x1F4CE;</span>
                                                    </label>
                                                    <!-- ⭐ Estrella: Top -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x2B50;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x2B50;</span>
                                                    </label>
                                                    <!-- ⏸️ Doble barra vertical: Pausa -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x23F8;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x23F8;</span>
                                                    </label>
                                                    <!-- ✉️ Correo: Enviar -->
                                                    <label>
                                                        <input type="checkbox" name="icon" value="&#x2709;"
                                                            class="icon-checkbox icon-style-edit">
                                                        <span class="icon-event">&#x2709;</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="title">
                                                        Título <p class="text-red-600">*</p>
                                                    </h5>
                                                    <input required type="text" name="title" id="title-edit"
                                                        class="inputs" placeholder="Título general de la actividad">
                                                </div>
                                            </div>
                                            <div class="-mx-3 flex flex-row justify-end">
                                                <div class="flex w-auto px-3">
                                                    <h5 class="font-semibold mr-2" for="allDay">
                                                        Todo el día
                                                    </h5>
                                                    <input type="checkbox" name="allDay" id="allDay-edit"
                                                        style="height: 24px; width: 24px;">
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="dateFirst">
                                                        Fecha<p class="text-red-600">*</p>
                                                    </h5>
                                                    <input required type="date" name="dateFirst" id="dateFirst-edit"
                                                        class="inputs">
                                                </div>
                                                <div class="flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="starTime">
                                                        Hora
                                                    </h5>
                                                    <input required type="time" name="starTime" id="starTime-edit"
                                                        class="inputs">
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <input required type="date" name="dateSecond" id="dateSecond-edit"
                                                        class="inputs">
                                                    <small id="smallDate-edit" class="text-red-600 text-xsm"></small>
                                                </div>
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <input required type="time" name="endTime" id="endTime-edit"
                                                        class="inputs">
                                                    <small id="smallTime-edit" class="text-red-600 text-xsm"></small>
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="repeat">
                                                        Repetir
                                                    </h5>
                                                    <select name="repeat" id="repeat-edit" class="inputs">
                                                        <option selected value="Once">No se repite</option>
                                                        <option value="Dairy">Todos los días</option>
                                                        <option value="Weeks">Todas las semanas</option>
                                                        <option value="Months">Todos los meses</option>
                                                        <option value="Years">Todos los años</option>
                                                    </select>
                                                </div>
                                                <div id="divDeadline-edit" class="mb-6 flex w-full flex-col px-3 hidden">
                                                    <h5 class="inline-flex font-semibold" for="deadline">
                                                        Fecha límite
                                                    </h5>
                                                    <input type="date" name="deadline" id="deadline-edit"
                                                        class="inputs">
                                                    <input type="date" name="deadline" id="deadline-edit-disabled"
                                                        class="inputs hidden" disabled>
                                                    <small id="smallDeadline-edit" class="text-red-600 text-xsm"></small>
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="phone">
                                                        Prioridad
                                                    </h5>
                                                    <div class="flex justify-center gap-12">
                                                        <div class="flex flex-col items-center">
                                                            <input type="checkbox" name="priority1" id="priority1-edit"
                                                                value="Alto"
                                                                class="priority-checkbox border-red-600 bg-red-600"
                                                                style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                                            <label for="priority1" class="mt-2">Alto</label>
                                                        </div>
                                                        <div class="flex flex-col items-center">
                                                            <input type="checkbox" name="priority2" id="priority2-edit"
                                                                value="Medio"
                                                                class="priority-checkbox border-yellow-400 bg-yellow-400"
                                                                style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                                            <label for="priority2" class="mt-2">Medio</label>
                                                        </div>
                                                        <div class="flex flex-col items-center">
                                                            <input type="checkbox" name="priority3" id="priority3-edit"
                                                                value="Bajo"
                                                                class="priority-checkbox border-secondary bg-secondary"
                                                                style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                                            <label for="priority3" class="mt-2">Bajo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="-mx-3 flex flex-row">
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold" for="project_id">
                                                        Proyecto
                                                    </h5>
                                                    <select name="project_id" id="project_id-edit" class="inputs">
                                                        <option value="0" selected>Selecciona...</option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}">{{ $project->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <h5 class="inline-flex font-semibold"
                                                        for="dropdown-button-users-edit">
                                                        Delegar
                                                    </h5>
                                                    <div id="dropdown-button-users-edit" class="relative">
                                                        <!-- Button -->
                                                        <button type="button"
                                                            class="inputs flex justify-between text-base">
                                                            Selecciona...
                                                            <!-- Heroicon: chevron-down -->
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-chevron-down"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M6 9l6 6l6 -6" />
                                                            </svg>
                                                        </button>
                                                        <!-- Panel -->
                                                        <div id="dropdown-panel-users-edit" style="display: none;"
                                                            class="absolute right-0 top-6 z-10 w-full rounded-md bg-gray-100">
                                                            @foreach ($allUsers as $user)
                                                                <label class="block px-4 py-2">
                                                                    <input name="delegate_id[]" type="checkbox"
                                                                        value="{{ $user->id }}"
                                                                        class="mr-2 delegate-edit">
                                                                    {{ $user->name }}
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="-mx-3 flex flex-row">
                                                <div class="mb-6 flex w-auto mx-auto flex-col px-3">
                                                    <button type="button" id="btn-update-notion" class="btnSave">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-device-floppy mr-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                            <path d="M14 4l0 4l-6 0l0 -4" />
                                                        </svg>
                                                        Guardar
                                                    </button>
                                                </div>
                                                <div class="mb-6 flex w-auto mx-auto flex-col px-3">
                                                    <div id="btn-edit-cancel" class="btnSecondary">
                                                        Cancelar
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-start px-5 md:mb-0 lg:w-1/2">
                            <livewire:modals.notion.chat wire:key="chat-component" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW --}}
    {{-- LOADING PAGE --}}
    <div class="hidden absolute left-0 top-0 z-50 h-screen w-full">
        <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40">
        </div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>
    {{-- END LOADING PAGE --}}
    @livewireScripts
    @stack('js')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    {{-- MODALES DE AVISOS --}}
    @if (session('error'))
        <script>
            toastr['error']("{{ session('error') }}", "Error");
        </script>
    @endif
    @if (session('success'))
        <script>
            toastr['success']("{{ session('success') }}");
        </script>
    @endif
    @if (session('warning'))
        <script>
            toastr['warning']("{{ session('warning') }}");
        </script>
    @endif
    {{-- CALENDARIO --}}
    <!-- Pasa los datos de PHP a JavaScript -->
    <script>
        var notas = @json($notas);
    </script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script>
        // ----------------------------------- MODAL CREAR ----------------------------------- 
        let modalCreate = document.getElementById('modal-create');
        let btnNew = document.getElementById('btn-new');
        let btnCloseModalCreate = document.getElementById('btn-close-modal-create');
        // Manejar la selección de colores
        const circles = document.querySelectorAll('.circle');
        let checkboxesCircle = document.querySelectorAll('.circle-checkbox');
        // Manejar la selección de iconos
        const icons = document.querySelectorAll('.icon-event');
        let checkboxesIcon = document.querySelectorAll('.icon-checkbox');

        let title = document.getElementById('title');
        // Manejar la selección de Fecha y Hora
        let allDay = document.getElementById('allDay');
        let dateFirst = document.getElementById('dateFirst');
        let dateSecond = document.getElementById('dateSecond');
        let smallDate = document.getElementById('smallDate');
        let starTime = document.getElementById('starTime');
        let endTime = document.getElementById('endTime');
        let smallTime = document.getElementById('smallTime');
        // Manejar la selección de Repetir y fecha límite
        let repeat = document.getElementById('repeat');
        let divDeadline = document.getElementById('divDeadline');
        let deadline = document.getElementById('deadline');
        let smallDeadline = document.getElementById('smallDeadline');
        // Manejar la selección de prioridad
        let checkboxes = document.querySelectorAll('.priority-checkbox');

        let project_id = document.getElementById('project_id');
        // Manejar la selección de delegados
        let delegateCheckboxes = document.querySelectorAll('.delegate');
        var panel = document.getElementById('dropdown-panel-users');
        var allPanels = document.querySelectorAll('[id^="dropdown-panel-users"]');
        // CONSTANTES   
        const now = new Date(); // Obtener la fecha y hora actuales
        const formattedDate = now.toISOString().split('T')[0]; // Formatear la fecha para los campos de tipo 'date'
        // Definir las horas mínimas y máximas
        const MIN_TIME = '07:00'; // 7:00 AM
        const EIGHT_TIME = '20:00'; // 8:00 PM
        const MAX_TIME = '21:00'; // 9:00 PM
        // FORMULARIO
        var form = document.getElementById('form-new-notion');
        var loadingPage = document.querySelector('.loadingspinner').parentElement; // Contenedor de la animación

        // Función para convertir "YYYY-MM-DD" en objeto Date sin problemas de zona horaria
        function parseDate(dateString) {
            let parts = dateString.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]); // Año, Mes (0-indexado), Día
        }

        // Función para formatear Date a "YYYY-MM-DD"
        function formatDate(date) {
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0'); // Agregar 0 si es menor a 10
            let day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Función para formatear la hora sin minutos
        function formatTime(date) {
            const hours = date.getHours().toString().padStart(2, '0'); // Obtener horas (HH)
            return `${hours}:00`; // Formato HH:00
        }

        // Función para sumar una hora a una hora dada
        function addOneHour(time) {
            // Convertir la hora a un objeto Date
            let date = new Date(`2000-01-01T${time}:00`);
            // Sumar una hora (3600 segundos * 1000 milisegundos)
            date.setTime(date.getTime() + 3600 * 1000);
            // Formatear la hora resultante en formato HH:MM
            return date.toTimeString().slice(0, 5);
        }

        now.setMinutes(0, 0, 0); // Redondear la hora actual hacia abajo (minutos y segundos a 0)

        const startTimeNow = formatTime(now); // Hora actual sin minutos (HH:00)
        const endTimeNow = formatTime(new Date(now.getTime() + 60 * 60 * 1000)); // 1 hora después sin minutos (HH:00)

        // MOSTRAR MODAL DE CREAR
        btnNew.addEventListener("click", function() {
            modalCreate.classList.remove("hidden");
            modalCreate.classList.add("show");

            // Establecer la fecha en los campos de tipo 'date'
            dateFirst.value = formattedDate;
            dateSecond.value = formattedDate;

            // Establecer los valores en los campos de tipo 'time'
            allDay.checked = true;
            starTime.value = '';
            endTime.value = '';
            starTime.disabled = true;
            endTime.disabled = true;
        });

        // OCULTAR MODAL DE CREAR
        btnCloseModalCreate.addEventListener("click", function() {
            modalCreate.classList.remove("show");
            modalCreate.classList.add("hidden");

            // FORMATEAR INPUTS
            checkboxesIcon.forEach(function(input, i) {
                input.checked = false;
                icons[i].classList.remove('selected');
            });

            checkboxesCircle.forEach(function(input, i) {
                input.checked = false;
                circles[i].classList.remove('selected');
            });

            title.value = '';
            allDay.checked = false;
            repeat.value = 'Once';
            divDeadline.classList.add('hidden');

            checkboxes.forEach(function(input) {
                input.checked = false;
            });

            project_id.value = '';

            delegateCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });

            // small
            smallDate.value = '';
            smallTime.value = '';
            smallDeadline.value = '';
        });

        // CHECKBOX DE COLORES
        checkboxesCircle.forEach(function(checkbox, index) {
            checkbox.addEventListener('change', function() {
                // Desenmarcar el checkbox seleccionado                    
                if (checkbox.checked == false) {
                    // Desmarcar todos los checkboxesCircle
                    checkboxesCircle.forEach(function(input, i) {
                        input.checked = false;
                        circles[i].classList.remove('selected');
                    });
                } else {
                    // Desmarcar todos los checkboxesCircle
                    checkboxesCircle.forEach(function(input, i) {
                        input.checked = false;
                        circles[i].classList.remove('selected');
                    });
                    // Marcar el checkbox seleccionado
                    checkbox.checked = true;
                    circles[index].classList.add('selected');
                }
            });
        });

        // CHECKBOX DE ICONOS
        checkboxesIcon.forEach(function(checkbox, index) {
            checkbox.addEventListener('change', function() {
                // Desenmarcar el checkbox seleccionado      
                if (checkbox.checked == false) {
                    // Desmarcar todos los checkboxesIcon
                    checkboxesIcon.forEach(function(input, i) {
                        input.checked = false;
                        icons[i].classList.remove('selected');
                    });
                } else {
                    // Desmarcar todos los checkboxesIcon
                    checkboxesIcon.forEach(function(input, i) {
                        input.checked = false;
                        icons[i].classList.remove('selected');
                    });
                    // Marcar el checkbox seleccionado
                    checkbox.checked = true;
                    icons[index].classList.add('selected');
                }
            });
        });

        // CHECKBOX TODO EL DIA
        allDay.addEventListener('change', function() {
            if (allDay.checked == false) {
                // Fechas con hora
                starTime.value = startTimeNow;
                endTime.value = endTimeNow;
                starTime.disabled = false;
                endTime.disabled = false;
            } else {
                // Fechas sin hora
                starTime.value = '';
                endTime.value = '';
                starTime.disabled = true;
                endTime.disabled = true;
            }
        });

        // Evento para detectar cambios en dateFirst
        dateFirst.addEventListener('input', function() {
            // Asignar el valor de dateFirst a dateSecond
            dateSecond.value = dateFirst.value;
            smallDate.textContent = '';
        });

        // Evento para detectar cambios en dateSecond
        dateSecond.addEventListener('blur', function() {
            if (dateSecond.value < dateFirst.value) {
                smallDate.textContent = 'No puede ser menor a la primer fecha.';
                dateSecond.value = dateFirst.value;
            } else {
                smallDate.textContent = '';
            }
        });

        // Validar starTime y actualizar endTime
        starTime.addEventListener('blur', function() {
            // Validar que starTime no sea menor que 7:00 AM
            if (starTime.value < MIN_TIME) {
                starTime.value = MIN_TIME; // Ajustar a la hora mínima
                smallTime.textContent = 'La hora mínima es 7:00 AM';
            } else {
                smallTime.textContent = '';
            }

            if (starTime.value == MAX_TIME) {
                starTime.value = EIGHT_TIME; // Ajustar a la hora mínima
                smallTime.textContent = 'La hora inicial no puede ser igual a la hora máxima.';
            } else {
                smallTime.textContent = '';
            }

            // Calcular endTime sumando una hora a starTime
            let newEndTime = addOneHour(starTime.value);

            // Validar que endTime no sea mayor que 9:00 PM
            if (newEndTime > MAX_TIME) {
                newEndTime = MAX_TIME; // Ajustar a la hora máxima
            }

            // Actualizar el valor de endTime
            endTime.value = newEndTime;
        });

        // Validar endTime (no puede ser mayor que 9:00 PM)
        endTime.addEventListener('blur', function() {
            if (endTime.value > MAX_TIME) {
                endTime.value = MAX_TIME; // Ajustar a la hora máxima
                smallTime.textContent = 'La hora máxima es 9:00 PM';
            } else {
                smallTime.textContent = '';
            }
        });

        // Evento para detectar cambios en el campo "repeat"
        repeat.addEventListener('change', function() {
            if (repeat.value !== 'Once') {
                // Si la opción seleccionada no es "Once", mostrar el div
                divDeadline.classList.remove('hidden');
            } else {
                // Si la opción seleccionada es "Once", ocultar el div
                divDeadline.classList.add('hidden');
                deadline.value = '';
            }
        });

        // Validacion de fecha limite con fecha final
        deadline.addEventListener('blur', function() {            
            let dateSecondValue = parseDate(dateSecond.value);
            let deadlineValue = parseDate(deadline.value);
            
            if (deadlineValue < dateSecondValue) {
                smallDeadline.textContent = 'La fecha límite no debe ser menor a la fecha final.';
                
                // Ajustar la fecha de deadline a un día después de dateSecond
                let newDeadline = new Date(dateSecondValue);
                newDeadline.setDate(newDeadline.getDate() + 1);
                
                // Formatear la fecha en YYYY-MM-DD para el input
                let formattedDeadline = formatDate(newDeadline);
                deadline.value = formattedDeadline;
            } else {
                smallDeadline.textContent = '';
            }
        });

        // CHECKBOX DE PRIORIODAD
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Desenmarcar el checkbox seleccionado                    
                if (checkbox.checked == false) {
                    // Desmarcar todos los checkboxes
                    checkboxes.forEach(function(input) {
                        input.checked = false;
                    });
                } else {
                    // Desmarcar todos los checkboxes
                    checkboxes.forEach(function(input) {
                        input.checked = false;
                    });
                    // Marcar el checkbox seleccionado
                    checkbox.checked = true;
                }
            });
        });
        // SELECTS DE USUARIOS
        function toggleDropdownUser() {
            if (panel.style.display === 'none') {
                // Oculta todos los paneles de dropdown
                allPanels.forEach(function(panel) {
                    panel.style.display = 'none';
                });
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        }

        // Mostrar la animación cuando se envíe el formulario
        form.addEventListener('submit', function(event) {
            // Obtener los valores de las fechas
            var dateFirstValue = dateFirst.value;
            var dateSecondValue = dateSecond.value;
            // Obtener los valores de las horas
            var starTimeValue = starTime.value; // Valor de starTime (HH:MM)
            var endTimeValue = endTime.value; // Valor de endTime (HH:MM)

            // Convertir las fechas a objetos Date
            var dateFirstObj = new Date(dateFirstValue);
            var dateSecondObj = new Date(dateSecondValue);

            // Validar si dateSecond es menor que dateFirst
            if (dateSecondObj < dateFirstObj) {
                // Evitar que el formulario se envíe
                event.preventDefault();

                // Mostrar un mensaje de error con toastr
                toastr.error('La fecha final no puede ser menor que la fecha inicial.', 'Error');
            }

            // Validar si starTime es mayor o igual que endTime
            if (starTimeValue > endTimeValue) {
                // Evitar que el formulario se envíe
                event.preventDefault();

                // Mostrar un mensaje de error con toastr
                toastr.error('La hora de inicio no puede ser mayor o igual que la hora de finalización.', 'Error');
                return; // Detener la ejecución
            }
            // Mostrar la animación de carga
            loadingPage.classList.remove('hidden');
        });

        // ----------------------------------- MODAL MOSTRAR ----------------------------------- 
        let modalShow = document.getElementById('modal-show');
        let btnCloseModalShow = document.getElementById('btn-close-modal-show');
        let bodyShow = document.getElementById('body-show');
        let bodyEdit = document.getElementById('body-edit');
        let divEditBtn = document.getElementById('div-edit-btn');
        let btnEdit = document.getElementById('btn-edit');
        let btnEditCancel = document.getElementById('btn-edit-cancel');
        // Manejar la selección de inputs con valores por default
        let idEdit = document.getElementById("id-edit");
        let repeatEditInput = document.getElementById("repeat-edit-input");
        let dateFirstEditInput = document.getElementById("dateFirst-edit-input");
        let dateSecondEditInput = document.getElementById("dateSecond-edit-input");
        let deadlineEditInput = document.getElementById("deadline-edit-input");
        // Manejar la selección de colores
        let circleColorEdit = document.querySelectorAll('.circle-color-edit');
        // Manejar la selección de iconos
        let checkboxesIconEdit = document.querySelectorAll('.icon-style-edit');

        let titleEdit = document.getElementById('title-edit');
        // Manejar la selección de Fecha y Hora
        let allDayEdit = document.getElementById('allDay-edit');
        let dateFirstEdit = document.getElementById('dateFirst-edit');
        let dateSecondEdit = document.getElementById('dateSecond-edit');
        let smallDateEdit = document.getElementById('smallDate-edit');
        let starTimeEdit = document.getElementById('starTime-edit');
        let endTimeEdit = document.getElementById('endTime-edit');
        let smallTimeEdit = document.getElementById('smallTime-edit');
        // Manejar la selección de Repetir y fecha límite
        let repeatEdit = document.getElementById('repeat-edit');
        let divDeadlineEdit = document.getElementById('divDeadline-edit');
        let deadlineEdit = document.getElementById('deadline-edit');
        let deadlineEditDisabled = document.getElementById('deadline-edit-disabled');
        let smallDeadlineEdit = document.getElementById('smallDeadline-edit');
        // Manejar la selección de prioridad
        let priority1Edit = document.getElementById('priority1-edit');
        let priority2Edit = document.getElementById('priority2-edit');
        let priority3Edit = document.getElementById('priority3-edit');

        let project_idEdit = document.getElementById('project_id-edit');
        // Manejar la selección de delegados
        let delegateCheckboxesEdit = document.querySelectorAll('.delegate-edit');
        let btnPanelEdit = document.getElementById('dropdown-button-users-edit');
        var panelEdit = document.getElementById('dropdown-panel-users-edit');
        var allPanelsEdit = document.querySelectorAll('[id^="dropdown-panel-users-edit"]');

        // Cerrar Modal Show
        btnCloseModalShow.addEventListener("click", function() {
            modalShow.classList.remove("show");
            modalShow.classList.add("hidden");

            bodyShow.classList.remove("hidden");
            bodyShow.classList.add("block");

            bodyEdit.classList.remove("block");
            bodyEdit.classList.add("hidden");

            divEditBtn.classList.remove("hidden");
            divEditBtn.classList.add("block");
            // REINICIAR VALORES
            // inputs
            idEdit.value = '';
            repeatEditInput.value = '';
            dateFirstEditInput.value = '';
            dateSecondEditInput.value = '';
            deadlineEditInput.value = '';

            // Actualiza los colores (checkboxes)
            circleColorEdit.forEach((checkbox) => {
                checkbox.checked = false;
                // Remueve la clase 'selected' del span relacionado
                checkbox.nextElementSibling.classList.remove('selected');

            });
            
            titleEdit.value = '';

            allDayEdit.checked = '';
            dateFirstEdit.value = '';
            dateSecondEdit.value = '';

            starTimeEdit.disabled = false;
            endTimeEdit.disabled = false;
            starTimeEdit.value = '';
            endTimeEdit.value = '';

            repeatEdit.value = 'Once';
            divDeadlineEdit.classList.add('hidden');
            deadlineEdit.value = '';
            // Actualiza la prioridad
            priority1Edit.checked = false;
            priority2Edit.checked = false;
            priority3Edit.checked = false;

            project_idEdit.value = '';
            // Actualiza los delegados (checkboxes)
            delegateCheckboxesEdit.forEach((checkbox) => {
                checkbox.checked = false;
            });

            // small
            smallDateEdit.textContent = '';
            smallTimeEdit.textContent = '';
            smallDeadlineEdit.textContent = '';
        });
        // BOTON PARA EDITAR
        btnEdit.addEventListener("click", function() {
            bodyShow.classList.remove("block");
            bodyShow.classList.add("hidden");

            bodyEdit.classList.remove("hidden");
            bodyEdit.classList.add("block");

            divEditBtn.classList.remove("block");
            divEditBtn.classList.add("hidden");

            // small
            smallDateEdit.textContent = '';
            smallTimeEdit.textContent = '';
            smallDeadlineEdit.textContent = '';
        });
        // CANCELAR FORMULARIO
        btnEditCancel.addEventListener("click", function() {
            bodyShow.classList.remove("hidden");
            bodyShow.classList.add("block");

            bodyEdit.classList.remove("block");
            bodyEdit.classList.add("hidden");

            divEditBtn.classList.remove("hidden");
            divEditBtn.classList.add("block");

            // small
            smallDateEdit.textContent = '';
            smallTimeEdit.textContent = '';
            smallDeadlineEdit.textContent = '';
        });

        allDayEdit.addEventListener("change", function() {
            if (this.checked) {
                starTimeEdit.value = "";
                starTimeEdit.disabled = true;
                endTimeEdit.value = "";
                endTimeEdit.disabled = true;
            } else {
                starTimeEdit.disabled = false;
                endTimeEdit.disabled = false;
            }
        });

        // Evento para detectar cambios en dateFirst
        dateFirstEdit.addEventListener('input', function() {
            // Asignar el valor de dateFirst a dateSecondEdit
            dateSecondEdit.value = dateFirstEdit.value;
            smallDateEdit.textContent = '';
        });

        // Evento para detectar cambios en dateSecondEdit
        dateSecondEdit.addEventListener('blur', function() {
            if (dateSecondEdit.value < dateFirstEdit.value) {
                smallDateEdit.textContent = 'No puede ser menor a la primer fecha.';
                dateSecondEdit.value = dateFirstEdit.value;
            } else {
                smallDateEdit.textContent = '';
            }
        });

        // Validar starTime y actualizar endTime
        starTimeEdit.addEventListener('blur', function() {
            // Validar que starTime no sea menor que 7:00 AM
            if (starTimeEdit.value < MIN_TIME) {
                starTimeEdit.value = MIN_TIME; // Ajustar a la hora mínima                
                smallTimeEdit.textContent = 'La hora mínima es 7:00 AM';
            } else {
                smallTimeEdit.textContent = '';
            }

            if (starTimeEdit.value == MAX_TIME) {
                starTimeEdit.value = EIGHT_TIME; // Ajustar a la hora mínima
                smallTimeEdit.textContent = 'La hora inicial no puede ser igual a la hora máxima.';
            } else {
                smallTimeEdit.textContent = '';
            }

            // Calcular endTime sumando una hora a starTime
            let newEndTimeEdit = addOneHour(starTimeEdit.value);

            // Validar que endTime no sea mayor que 9:00 PM
            if (newEndTimeEdit > MAX_TIME) {
                newEndTimeEdit = MAX_TIME; // Ajustar a la hora máxima
            }

            // Actualizar el valor de endTime
            endTimeEdit.value = newEndTimeEdit;
        });

        // Validar endTime (no puede ser mayor que 9:00 PM)
        endTimeEdit.addEventListener('blur', function() {
            if (endTimeEdit.value > MAX_TIME) {
                endTimeEdit.value = MAX_TIME; // Ajustar a la hora máxima
                smallTimeEdit.textContent = 'La hora máxima es 9:00 PM';
            } else {
                smallTimeEdit.textContent = '';
            }
        });

        // Función para validar si las fechas han cambiado
        function validateDates() {
            // Obtener los valores actuales de los campos de fecha
            let currentDateFirst = dateFirstEdit.value;
            let currentDateSecond = dateSecondEdit.value;

            // Obtener los valores iniciales de los campos de fecha
            let initialDateFirst = dateFirstEditInput.value;
            let initialDateSecond = dateSecondEditInput.value;

            // Verificar si las fechas han cambiado
            if (currentDateFirst !== initialDateFirst || currentDateSecond !== initialDateSecond) {
                // Si las fechas han cambiado, deshabilitar el campo deadline-edit y mostrar el mensaje
                deadlineEdit.classList.add('hidden');
                deadlineEdit.classList.remove('block');

                deadlineEditDisabled.classList.remove('hidden');
                deadlineEditDisabled.classList.add('block');

                smallDeadlineEdit.textContent = 'No puede cambiar el límite si se cambian las fechas.';
            } else {
                // Si las fechas no han cambiado, habilitar el campo deadline-edit y limpiar el mensaje
                deadlineEdit.classList.add('block');
                deadlineEdit.classList.remove('hidden');

                deadlineEditDisabled.classList.remove('block');
                deadlineEditDisabled.classList.add('hidden');

                smallDeadlineEdit.textContent = '';
            }
        }
        // Agregar eventos para detectar cambios en los campos de fecha
        dateSecondEdit.addEventListener('input', validateDates);

        // Evento para detectar cambios en el campo "repeat"
        repeatEdit.addEventListener('change', function() {
            if (repeatEdit.value !== 'Once') {
                // Si la opción seleccionada no es "Once", mostrar el div
                divDeadlineEdit.classList.remove('hidden');
            } else {
                // Si la opción seleccionada es "Once", ocultar el div
                divDeadlineEdit.classList.add('hidden');
                deadlineEdit.value = '';
            }
        });

        // Validacion de fecha limite con fecha final
        deadlineEdit.addEventListener('blur', function() {            
            let dateSecondValue = parseDate(dateSecondEdit.value);
            let deadlineValue = parseDate(deadlineEdit.value);
            
            if (deadlineValue < dateSecondValue) {
                smallDeadlineEdit.textContent = 'La fecha límite no debe ser menor a la fecha final.';
                
                // Ajustar la fecha de deadline a un día después de dateSecond
                let newDeadline = new Date(dateSecondValue);
                newDeadline.setDate(newDeadline.getDate() + 1);
                
                // Formatear la fecha en YYYY-MM-DD para el input
                let formattedDeadline = formatDate(newDeadline);
                deadlineEdit.value = formattedDeadline;
                deadlineEditDisabled.value = formattedDeadline;
            } else {
                smallDeadlineEdit.textContent = '';
                deadlineEditDisabled.value = deadlineEdit.value
            }
        });

        // Manejador del dropdown
        btnPanelEdit.addEventListener("click", function() {
            if (panelEdit.style.display === 'none') {
                // Oculta todos los paneles de dropdown
                allPanelsEdit.forEach(function(panel) {
                    panel.style.display = 'none';
                });
                panelEdit.style.display = 'block';
            } else {
                panelEdit.style.display = 'none';
            }
        });
    </script>
@endsection
