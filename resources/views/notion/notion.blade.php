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
                                            <div class="flex w-2/5 flex-col px-3">
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
                                            <div class="mb-6 flex w-full flex-col px-3">
                                                <h5 class="inline-flex font-semibold" for="file">
                                                    Color
                                                </h5>
                                                <div class="flex justify-between">
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
                                                <div class="flex justify-between">
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
                                                </div>
                                                <div class="mb-6 flex w-full flex-col px-3">
                                                    <input required type="time" name="endTime" id="endTime-edit"
                                                        class="inputs">
                                                </div>
                                            </div>
                                            <div class="-mx-3 mb-6 flex flex-row">
                                                <div class="mb-6 flex w-full flex-col px-3">
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
    {{-- MODAL CREATE NOTE --}}
    <div id="modal-edit-create" class="left-0 top-20 z-50 hidden max-h-full overflow-y-auto">
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
                                <div class="flex justify-between">
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
                                <div class="flex justify-between">
                                    <!--  🚀 Cohete: Propuestas; Lanzamientos -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F680;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F680;
                                        </span>
                                    </label>
                                    <!-- 🔵 Círculo morado grande: Seguimiento -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F535;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F535;</span>
                                    </label>
                                    <!-- 💵 Dólar: Finanzas -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F4B5;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F4B5;</span>
                                    </label>
                                    <!-- 📅 Calendario: Cita -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F4C5;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F4C5;</span>
                                    </label>
                                    <!-- 💡 Bombilla: Ideas -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F4A1;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F4A1;</span>
                                    </label>
                                    <!-- 📎 Clip: Notas -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x1F4CE;" class="icon-checkbox">
                                        <span class="icon-event">&#x1F4CE;</span>
                                    </label>
                                    <!-- ⭐ Estrella: Top -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x2B50;" class="icon-checkbox">
                                        <span class="icon-event">&#x2B50;</span>
                                    </label>
                                    <!-- ⏸️ Doble barra vertical: Pausa -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x23F8;" class="icon-checkbox">
                                        <span class="icon-event">&#x23F8;</span>
                                    </label>
                                    <!-- ✉️ Correo: Enviar -->
                                    <label>
                                        <input type="checkbox" name="icon" value="&#x2709;" class="icon-checkbox">
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
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <input required type="date" name="dateSecond" id="dateSecond" class="inputs">
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <input required type="time" name="endTime" id="endTime" class="inputs">
                                </div>
                            </div>
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
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
                            </div>
                            <div class="-mx-3 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
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
                                <div class="mb-6 flex w-full flex-col px-3">
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
        // FECHA Y HORA
        const now = new Date(); // Obtener la fecha y hora actuales
        const formattedDate = now.toISOString().split('T')[0]; // Formatear la fecha para los campos de tipo 'date'
        
        let dateSecond = document.getElementById('dateSecond');
        let starTime = document.getElementById('starTime');
        let endTime = document.getElementById('endTime');
        // Definir las horas mínimas y máximas
        const MIN_TIME = '07:00'; // 7:00 AM
        const MAX_TIME = '21:00'; // 9:00 PM
        // REVISION DE CHECKBOX
        let checkboxAllDay = document.getElementById('allDay');
        let checkboxes = document.querySelectorAll('.priority-checkbox');
        // JavaScript para manejar la selección de círculos
        const circles = document.querySelectorAll('.circle');
        let checkboxesCircle = document.querySelectorAll('.circle-checkbox');
        // JavaScript para manejar la selección de iconos
        const icons = document.querySelectorAll('.icon-event');
        let checkboxesIcon = document.querySelectorAll('.icon-checkbox');
        // FORMULARIO
        var form = document.getElementById('form-new-notion');
        var loadingPage = document.querySelector('.loadingspinner').parentElement; // Contenedor de la animación

        // Función para formatear la hora en HH:mm
        function formatTime(date) {
            return date.toTimeString().split(' ')[0].substring(0, 5);
        }

        // Calcular la hora actual y 1 hora después
        // Función para formatear la hora sin minutos
        function formatTime(date) {
            const hours = date.getHours().toString().padStart(2, '0'); // Obtener horas (HH)
            return `${hours}:00`; // Formato HH:00
        }

        // Obtener la hora actual sin minutos
        now.setMinutes(0, 0, 0); // Redondear la hora actual hacia abajo (minutos y segundos a 0)

        const startTimeNow = formatTime(now); // Hora actual sin minutos (HH:00)
        const endTimeNow = formatTime(new Date(now.getTime() + 60 * 60 * 1000)); // 1 hora después sin minutos (HH:00)


        $(document).ready(function() {
            $("#btn-new").on("click", function() {
                $("#modal-edit-create").removeClass("hidden").addClass("show");

                // FECHA Y HORA
                // Establecer la fecha en los campos de tipo 'date'
                dateFirst.value = formattedDate;
                dateSecond.value = formattedDate;

                // Establecer los valores en los campos de tipo 'time'
                checkboxAllDay.checked = true;
                starTime.value = '';
                endTime.value = '';
                starTime.disabled = true;
                endTime.disabled = true;
            });
            $("#btn-close-modal-create").on("click", function() {
                $("#modal-edit-create").removeClass("show").addClass("hidden");

                // FORMATEAR INPUTS
                checkboxesCircle.forEach(function(input, i) {
                    input.checked = false;
                    circles[i].classList.remove('selected');
                });

                checkboxesIcon.forEach(function(input, i) {
                    input.checked = false;
                    icons[i].classList.remove('selected');
                });

                document.getElementById("title").value = '';
                checkboxAllDay.checked = '';
                // Desmarcar todos los checkboxes DE PRIORIDAD
                checkboxes.forEach(function(input) {
                    input.checked = false;
                });

                document.getElementById("repeat").value = '';
                document.getElementById("project_id").value = '';
                // Actualiza los delegados (checkboxes)
                const delegateCheckboxes = document.querySelectorAll('.delegate');
                delegateCheckboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
            });
        });

        // Evento para detectar cambios en dateFirst
        dateFirst.addEventListener('input', function() {
            // Asignar el valor de dateFirst a dateSecond
            dateSecond.value = dateFirst.value;
        });

        // Función para sumar una hora a una hora dada
        function addOneHour(time) {
            // Convertir la hora a un objeto Date
            let date = new Date(`2000-01-01T${time}:00`);
            // Sumar una hora (3600 segundos * 1000 milisegundos)
            date.setTime(date.getTime() + 3600 * 1000);
            // Formatear la hora resultante en formato HH:MM
            return date.toTimeString().slice(0, 5);
        }

        // Validar starTime y actualizar endTime
        starTime.addEventListener('input', function() {
            // Validar que starTime no sea menor que 7:00 AM
            if (starTime.value < MIN_TIME) {
                starTime.value = MIN_TIME; // Ajustar a la hora mínima
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
        endTime.addEventListener('input', function() {
            if (endTime.value > MAX_TIME) {
                endTime.value = MAX_TIME; // Ajustar a la hora máxima
            }
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
        checkboxAllDay.addEventListener('change', function() {
            if (checkboxAllDay.checked == false) {
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

        // SELECTS DE USUARIOS Y PROYECTOS
        function toggleDropdownUser() {
            var panel = document.getElementById('dropdown-panel-users');
            if (panel.style.display === 'none') {
                // Oculta todos los paneles de dropdown
                var allPanels = document.querySelectorAll('[id^="dropdown-panel-users"]');
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
            var endTimeValue = endTime.value;   // Valor de endTime (HH:MM)

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
        $("#btn-close-modal-show").on("click", function() {
            $("#modal-show").removeClass("show").addClass("hidden");
            $("#body-show").removeClass("hidden").addClass("block");
            $("#body-edit").removeClass("block").addClass("hidden");
            $("#div-edit-btn").removeClass("hidden").addClass("block");
            // Actualiza los colores (checkboxes)
            const colorCheckboxes = document.querySelectorAll('.circle-color-edit');
            colorCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
                // Remueve la clase 'selected' del span relacionado
                checkbox.nextElementSibling.classList.remove('selected');

            });
            // Actualiza los inputs del formulario con la información de la nota
            document.getElementById("title-edit").value = '';
            document.getElementById("allDay-edit").checked = '';
            document.getElementById("dateFirst-edit").value = '';
            document.getElementById("dateSecond-edit").value = '';

            const startTimeInput = document.getElementById("starTime-edit");
            const endTimeInput = document.getElementById("endTime-edit");
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
            startTimeInput.value = '';
            endTimeInput.value = '';

            document.getElementById("repeat-edit").value = '';
            document.getElementById("project_id-edit").value = '';
            // Actualiza la prioridad
            document.getElementById("priority1-edit").checked = false;
            document.getElementById("priority2-edit").checked = false;
            document.getElementById("priority3-edit").checked = false;

            // Actualiza los delegados (checkboxes)
            const delegateCheckboxes = document.querySelectorAll('.delegate-edit');
            delegateCheckboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
        });
        // DIVS DE EDITAR
        $("#btn-edit").on("click", function() {
            $("#body-show").removeClass("block").addClass("hidden");
            $("#body-edit").removeClass("hidden").addClass("block");
            $("#div-edit-btn").removeClass("block").addClass("hidden");
        });

        $("#btn-edit-cancel").on("click", function() {
            $("#body-show").removeClass("hidden").addClass("block");
            $("#body-edit").removeClass("block").addClass("hidden");
            $("#div-edit-btn").removeClass("hidden").addClass("block");
        });

        let dateFirstEdit = document.getElementById('dateFirst-edit');
        let dateSecondEdit = document.getElementById('dateSecond-edit');
        let starTimeEdit = document.getElementById('starTime-edit');
        let endTimeEdit = document.getElementById('endTime-edit');

        // Evento para detectar cambios en dateFirst
        dateFirstEdit.addEventListener('input', function() {
            // Asignar el valor de dateFirst a dateSecond
            dateSecondEdit.value = dateFirstEdit.value;
        });

        
        // Función para sumar una hora a una hora dada
        function addOneHour(time) {
            // Convertir la hora a un objeto Date
            let date = new Date(`2000-01-01T${time}:00`);
            // Sumar una hora (3600 segundos * 1000 milisegundos)
            date.setTime(date.getTime() + 3600 * 1000);
            // Formatear la hora resultante en formato HH:MM
            return date.toTimeString().slice(0, 5);
        }

        // Validar starTime y actualizar endTime
        starTimeEdit.addEventListener('input', function() {
            // Validar que starTime no sea menor que 7:00 AM
            if (starTimeEdit.value < MIN_TIME) {
                starTimeEdit.value = MIN_TIME; // Ajustar a la hora mínima
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
        endTimeEdit.addEventListener('input', function() {
            if (endTimeEdit.value > MAX_TIME) {
                endTimeEdit.value = MAX_TIME; // Ajustar a la hora máxima
            }
        });

        // Manejador del dropdown
        $("#dropdown-button-users-edit").on("click", function() {
            var panelEdit = document.getElementById('dropdown-panel-users-edit');
            if (panelEdit.style.display === 'none') {
                // Oculta todos los paneles de dropdown
                var allPanelsEdit = document.querySelectorAll('[id^="dropdown-panel-users-edit"]');
                allPanelsEdit.forEach(function(panel) {
                    panel.style.display = 'none';
                });
                panelEdit.style.display = 'block';
            } else {
                panelEdit.style.display = 'none';
            }
        });

        document.getElementById("allDay-edit").addEventListener("change", function() {
            const startTimeInput = document.getElementById("starTime-edit");
            const endTimeInput = document.getElementById("endTime-edit");

            if (this.checked) {
                startTimeInput.value = "";
                startTimeInput.disabled = true;
                endTimeInput.value = "";
                endTimeInput.disabled = true;
            } else {
                startTimeInput.disabled = false;
                endTimeInput.disabled = false;
            }
        });
    </script>
@endsection
