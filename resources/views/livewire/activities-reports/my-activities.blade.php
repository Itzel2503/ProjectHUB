<div>
    {{-- Tabla actividades y reportes --}}
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <div class="flex w-full flex-wrap md:inline-flex md:flex-nowrap">
                <!-- SEARCH -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-2/5 md:px-0">
                    <div class="relative flex h-full w-full">
                        <div class="absolute z-10 mt-2 flex">
                            <span
                                class="whitespace-no-wrap flex items-center rounded-lg border-0 border-none bg-transparent p-2 leading-normal lg:px-3">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z"
                                        stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <input wire:model="search" type="text" placeholder="Buscar tarea" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
                <!-- DELEGATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedDelegate" class="inputs">
                        <option value="">Creado por</option>
                        @foreach ($allUsersFiltered as $userFiltered)
                            <option value="{{ $userFiltered['id'] }}">{{ $userFiltered['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- STATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedStates" class="inputs">
                        <option value="">Estados</option>
                        <option value="Abierto">Abierto</option>
                        <option value="Proceso">Proceso</option>
                        <option value="Resuelto">Resuelto</option>
                        <option value="Conflicto">Conflicto</option>
                    </select>
                </div>
                <!-- PROYECTOS -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedProjects" class="inputs">
                        <option value="">Proyectos</option>
                        @foreach ($allProjectsFiltered as $projectFiltered)
                            <option value="{{ $projectFiltered['id'] }}">{{ $projectFiltered['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        @if (!$mode)
            <div class="tableStyle">
                <table class="whitespace-no-wrap table-hover table w-full">
                    <thead class="headTable border-0">
                        <tr class="text-left">
                            <th class="w-96 px-4 py-3">
                                <div class="flex">
                                    Tareas
                                    {{-- down-up --}}
                                    <svg wire:click="filterDown('priority')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filtered) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click="filterUp('priority')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filtered) hidden @else block @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 3l0 18" />
                                        <path d="M10 6l-3 -3l-3 3" />
                                        <path d="M20 18l-3 3l-3 -3" />
                                        <path d="M17 21l0 -18" />
                                    </svg>
                                </div>
                            </th>
                            <th class="px-1 py-3 lg:w-48">
                                <div class="flex">
                                    Delegado
                                    {{-- down-up --}}
                                    {{-- <svg wire:click="filterDown('delegate')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filtered) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg> --}}
                                    {{-- up-down --}}
                                    {{-- <svg wire:click="filterUp('delegate')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filtered) hidden @else block @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 3l0 18" />
                                        <path d="M10 6l-3 -3l-3 3" />
                                        <path d="M20 18l-3 3l-3 -3" />
                                        <path d="M17 21l0 -18" />
                                    </svg> --}}
                                </div>
                            </th>
                            <th class="w-48 px-2 py-3">
                                <div class="flex">
                                    Estado
                                    {{-- down-up --}}
                                    <svg wire:click="filterDown('state')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filtered) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click="filterUp('state')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filtered) hidden @else block @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 3l0 18" />
                                        <path d="M10 6l-3 -3l-3 3" />
                                        <path d="M20 18l-3 3l-3 -3" />
                                        <path d="M17 21l0 -18" />
                                    </svg>
                                </div>
                            </th>
                            <th class="@if (Auth::user()->type_user == '1') w-1/4 @else w-44 @endif px-1 py-3">
                                <div class="flex items-center">
                                    Fecha de entrega
                                    {{-- down-up --}}
                                    <svg wire:click="filterDown('expected_date')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filtered) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click="filterUp('expected_date')" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filtered) hidden @else block @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 3l0 18" />
                                        <path d="M10 6l-3 -3l-3 3" />
                                        <path d="M20 18l-3 3l-3 -3" />
                                        <path d="M17 21l0 -18" />
                                    </svg>
                                </div>
                            </th>
                            <th class="w-56 px-1 py-3">Creado</th>
                            <th class="w-16 px-1 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr class="trTable" id="report-{{ $task->id }}">
                                <td class="relative px-2 py-1">
                                    <div @if ($task->project_activity) wire:click="show({{ $task->id }}, 'activity')" @elseif($task->project_report) wire:click="show({{ $task->id }}, 'report')" @endif
                                        class="flex cursor-pointer flex-col justify-center text-center">
                                        <div class="flex flex-row justify-between">
                                            <p class="pl-3 my-auto text-left text-xs font-semibold text-gray-400">
                                                {{ $task->project_name }}
                                            </p>
                                            <div class="principal text-gray-400">
                                                @if ($task->project_activity)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6l0 13" />
                                                        <path d="M12 6l0 13" />
                                                        <path d="M21 6l0 13" />
                                                    </svg>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Actividad</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-bug" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                        <path
                                                            d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                        <path d="M3 13l4 0" />
                                                        <path d="M17 13l4 0" />
                                                        <path d="M12 20l0 -6" />
                                                        <path d="M4 19l3.35 -2" />
                                                        <path d="M20 19l-3.35 -2" />
                                                        <path d="M4 7l3.75 2.4" />
                                                        <path d="M20 7l-3.75 2.4" />
                                                    </svg>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Reporte</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-row">
                                            @if ($task->priority)
                                                <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="currentColor"
                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($task->priority == 'Alto') text-red-500 @elseif ($task->priority == 'Medio') text-yellow-400 @elseif ($task->priority == 'Bajo') text-blue-500 @endif">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-12"></div>
                                            @endif
                                            <p class="my-auto text-left text-xs font-semibold">
                                                {{ $task->icon }} {{ $task->title }}
                                            </p>
                                        </div>
                                        @if ($task->messages_count >= 1)
                                            {{-- usuario --}}
                                            @if ($task->user_chat != Auth::id() && $task->receiver_chat == Auth::id())
                                                <div class="absolute right-0 top-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-message text-red-600">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 9h8" />
                                                        <path d="M8 13h6" />
                                                        <path
                                                            d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                                                    </svg>
                                                </div>
                                            @elseif(Auth::user()->type_user == 1 && $task->client == true)
                                                <div class="absolute right-0 top-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-message text-red-600">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M8 9h8" />
                                                        <path d="M8 13h6" />
                                                        <path
                                                            d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto w-full text-left">
                                        @if ($task->project_activity || $task->project_report)
                                            <select
                                                @if ($task->project_activity) wire:change.defer="updateDelegate({{ $task->id }}, $event.target.value, 'activity')" @elseif($task->project_report) wire:change="updateDelegate({{ $task->id }},$event.target.value, 'report')" @endif
                                                name="delegate" id="delegate"
                                                @if ($task->project_activity) @if ($task->sprint_state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                @endif
                                                class="inpSelectTable @if ($task->state == 'Resuelto') hidden @endif w-full text-sm">
                                                <option selected value={{ $task->delegate_id }}>
                                                    {{ $task->delegate_name }}
                                                </option>
                                                @foreach ($task->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-xs">
                                                @if ($task->state == 'Proceso' || $task->state == 'Conflicto')
                                                    Progreso
                                                    {{ $task->progress->diffForHumans(null, false, false, 1) }}
                                                @else
                                                    @if ($task->state == 'Resuelto')
                                                        @if ($task->progress == null)
                                                            Sin desarrollo
                                                        @else
                                                            Desarrollo {{ $task->timeDifference }}
                                                        @endif
                                                    @else
                                                        @if ($task->look == true)
                                                            Visto
                                                            {{ $task->progress->diffForHumans(null, false, false, 1) }}
                                                        @endif
                                                    @endif
                                                @endif
                                            </p>
                                        @else
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $task->delegate_id }}>
                                                    {{ $task->delegate_name }}
                                                </option>
                                            </select>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @if ($task->project_activity || $task->project_report)
                                        <select name="state" id="state"
                                            @if ($task->project_activity) @if ($task->sprint_state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                            @endif
                                            class="inpSelectTable @if ($task->state == 'Abierto') bg-blue-500 text-white @endif @if ($task->state == 'Proceso') bg-yellow-400 @endif @if ($task->state == 'Resuelto') bg-lime-700 text-white @endif @if ($task->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                            @if ($task->project_activity) wire:change.defer="updateState({{ $task->id }}, null, $event.target.value)" @elseif($task->project_report) wire:change="updateState({{ $task->id }}, {{ $task->project_id }}, $event.target.value)" @endif>
                                            <option selected value={{ $task->state }}>{{ $task->state }}</option>
                                            @foreach ($task->filteredActions as $action)
                                                <option value="{{ $action }}">{{ $action }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="state" id="state"
                                            class="inpSelectTable @if ($task->state == 'Abierto') bg-blue-500 text-white @endif @if ($task->state == 'Proceso') bg-yellow-400 @endif @if ($task->state == 'Resuelto') bg-lime-700 text-white @endif @if ($task->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                            <option selected value={{ $task->state }}>{{ $task->state }}</option>
                                            </d>
                                    @endif
                                    @if ($task->count)
                                        <p class="text-xs text-red-600">Reincidencia {{ $task->count }}</p>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    @if ($task->project_report)
                                        @if ($task->updated_expected_date == false && $task->state != 'Resuelto')
                                            @if (Auth::user()->type_user != 3)
                                                <div class="my-auto text-left">
                                                    <input type="date"
                                                        wire:model='expected_day.{{ $task->id }}'
                                                        @if ($task->project_report) wire:change="updateExpectedDay({{ $task->id }}, 'report', $event.target.value)" @endif>
                                                    <p class="text-xs text-red-600">Cliente en espera de fecha</p>
                                                </div>
                                            @else
                                                <div class="my-auto text-left">
                                                    En espera
                                                </div>
                                            @endif
                                        @else
                                            <div class="my-auto text-left">
                                                @if (Auth::user()->type_user === 1 && $task->state != 'Resuelto')
                                                    <input type="date"
                                                        wire:model='expected_day.{{ $task->id }}'
                                                        @if ($task->project_activity) wire:change="updateExpectedDay({{ $task->id }}, 'activity', $event.target.value)" @elseif($task->project_report) wire:change="updateExpectedDay({{ $task->id }}, 'report', $event.target.value)" @endif>
                                                @else
                                                    {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="my-auto text-left">
                                            @if (Auth::user()->type_user === 1 && $task->state != 'Resuelto')
                                                <input type="date" wire:model='expected_day.{{ $task->id }}'
                                                    @if ($task->project_activity) wire:change="updateExpectedDay({{ $task->id }}, 'activity', $event.target.value)" @elseif($task->project_report) wire:change="updateExpectedDay({{ $task->id }}, 'report', $event.target.value)" @endif>
                                            @else
                                                {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto text-left">
                                        <span class="font-semibold"> {{ $task->created_name }} </span> <br>
                                        <span class="font-mono">
                                            {{ \Carbon\Carbon::parse($task->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="principal flex justify-center">
                                        @if ($task->project_activity)
                                            <a href="{{ route('projects.activities.index', ['project' => $task->project_id, 'activity' => $task->id, 'highlight' => $task->id]) }}"
                                                target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                            <div class="relative">
                                                <div
                                                    class="hidden-info absolute -top-3 right-5 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                    <p>Ver mas</p>
                                                </div>
                                            </div>
                                        @elseif($task->project_report)
                                            <a href="{{ route('projects.reports.index', ['project' => $task->project_id, 'reports' => $task->id, 'highlight' => $task->id]) }}"
                                                target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                            <div class="relative">
                                                <div
                                                    class="hidden-info absolute -top-3 right-5 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                    <p>Ver mas</p>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-justify text-xs font-semibold">
                                                Proyecto no disponible
                                            </p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="py-5">
                {{ $tasks->links() }}
            </div>
        @else
            {{-- KANVAN --}}
            <div class="relative">
                <!-- Botón para desplazar a la izquierda -->
                <button id="scroll-left"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white p-2 rounded-full shadow-lg hover:bg-secundaryColor z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </button>
                <div id="kanvan-my-activities" class="h-[500px] overflow-x-scroll w-full">
                    <div class="flex">
                        {{-- Sin fecha --}}
                        <div class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-container @endif bg-neutral-300 p-2 mx-2 w-full rounded-xl mb-2">
                            <div class="title-container bg-white m-auto text-center rounded-md p-1 w-48">
                                <picture>Sin fecha</p>
                                    <br>
                            </div>
                            @if ($seeProjects)
                                @foreach ($tareasSinFecha as $key => $project)
                                    <div
                                        class="title-proyect bg{{ $key }} flex justify-start p-2 text-left text-xs rounded-xl my-2">
                                        <p class="font-bold mr-1 my-auto">{{ $key }}</p>
                                        <p>{{ $project['project_name'] }}</p>
                                    </div>
                                    @foreach ($project['tasks'] as $task)
                                        <div id="task-{{ $task['id'] }}"
                                            class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                            <div class="flex justify-between">
                                                <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                                <div class="principal w-6 cursor-pointer"
                                                    @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                    @if (array_key_exists('project_activity', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6l0 13" />
                                                            <path d="M12 6l0 13" />
                                                            <path d="M21 6l0 13" />
                                                        </svg>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-bug" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                            <path
                                                                d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                            <path d="M3 13l4 0" />
                                                            <path d="M17 13l4 0" />
                                                            <path d="M12 20l0 -6" />
                                                            <path d="M4 19l3.35 -2" />
                                                            <path d="M20 19l-3.35 -2" />
                                                            <path d="M4 7l3.75 2.4" />
                                                            <path d="M20 7l-3.75 2.4" />
                                                        </svg>
                                                    @endif
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Detalles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <div>
                                                    @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                        <select name="state" id="state"
                                                            @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                            @endif
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                            @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            @foreach ($task['filteredActions'] as $action)
                                                                <option value="{{ $action }}">
                                                                    {{ $action }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="state" id="state"
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            </d>
                                                    @endif
                                                    @if (array_key_exists('report_id', $task))
                                                        @if ($task['count'] != null)
                                                            <p class="text-xs text-red-600">Reincidencia
                                                                {{ $task['count'] }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="principal">
                                                    @if (array_key_exists('project_activity', $task))
                                                        <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-justify text-xs font-semibold">
                                                            Proyecto no disponible
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                <select
                                                    @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                    name="delegate" id="delegate"
                                                    @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                    @endif
                                                    class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                    @foreach ($task['usersFiltered'] as $userFiltered)
                                                        <option value="{{ $userFiltered->id }}">
                                                            {{ $userFiltered->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="delegate" id="delegate"
                                                    class="inpSelectTable w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($tareasSinFecha as $task)
                                    <div id="task-{{ $task['id'] }}"
                                        class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                        <div class="flex justify-between text-xs">
                                            <div class="flex items-center">
                                                <p
                                                    class="font-bold bg{{ $task['project_priority'] }} rounded-full mr-1 p-1">
                                                    {{ $task['project_priority'] }}</p>
                                                <p class="my-auto text-left font-semibold text-gray-400">
                                                    {{ $task['project_name'] }}
                                                </p>
                                            </div>
                                            <div class="principal w-6 cursor-pointer"
                                                @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                @if (array_key_exists('project_activity', $task))
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6l0 13" />
                                                        <path d="M12 6l0 13" />
                                                        <path d="M21 6l0 13" />
                                                    </svg>
                                                @elseif(array_key_exists('project_report', $task))
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-bug" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                        <path
                                                            d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                        <path d="M3 13l4 0" />
                                                        <path d="M17 13l4 0" />
                                                        <path d="M12 20l0 -6" />
                                                        <path d="M4 19l3.35 -2" />
                                                        <path d="M20 19l-3.35 -2" />
                                                        <path d="M4 7l3.75 2.4" />
                                                        <path d="M20 7l-3.75 2.4" />
                                                    </svg>
                                                @endif
                                                <div class="relative">
                                                    <div
                                                        class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                        <p>Detalles</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                        <div class="flex justify-between">
                                            <div>
                                                @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                    <select name="state" id="state"
                                                        @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                        @endif
                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                        @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                        <option selected value={{ $task['state'] }}>
                                                            {{ $task['state'] }}
                                                        </option>
                                                        @foreach ($task['filteredActions'] as $action)
                                                            <option value="{{ $action }}">
                                                                {{ $action }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="state" id="state"
                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                        <option selected value={{ $task['state'] }}>
                                                            {{ $task['state'] }}
                                                        </option>
                                                        </d>
                                                @endif
                                                @if (array_key_exists('report_id', $task))
                                                    @if ($task['count'] != null)
                                                        <p class="text-xs text-red-600">Reincidencia
                                                            {{ $task['count'] }}</p>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="principal">
                                                @if (array_key_exists('project_activity', $task))
                                                    <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path
                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                    </a>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Ver mas</p>
                                                        </div>
                                                    </div>
                                                @elseif(array_key_exists('project_report', $task))
                                                    <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path
                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                    </a>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Ver mas</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <p class="text-justify text-xs font-semibold">
                                                        Proyecto no disponible
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                            <select
                                                @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                name="delegate" id="delegate"
                                                @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                @endif
                                                class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                <option selected value={{ $task['delegate_id'] }}>
                                                    {{ $task['delegate_name'] }}
                                                </option>
                                                @foreach ($task['usersFiltered'] as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $task['delegate_id'] }}>
                                                    {{ $task['delegate_name'] }}
                                                </option>
                                            </select>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        {{-- atrasadas --}}
                        <div class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-container @endif bg-red-200 p-2 mx-2 w-full rounded-xl mb-2">
                            <div class="title-container bg-white m-auto text-center rounded-md p-1 w-48">
                                <p>Atrasados</p>
                                <br>
                            </div>
                            @if ($seeProjects)
                                @foreach ($tareasAtrasadas as $key => $project)
                                    <div
                                        class="title-proyect bg{{ $key }} flex justify-start p-2 text-left text-xs rounded-xl my-2">
                                        <p class="font-bold mr-1 my-auto">{{ $key }}</p>
                                        <p>{{ $project['project_name'] }}</p>
                                    </div>
                                    @foreach ($project['tasks'] as $task)
                                        <div id="task-{{ $task['id'] }}"
                                            class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                            <div class="flex justify-between">
                                                <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                                <div class="principal w-6 cursor-pointer"
                                                    @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                    @if (array_key_exists('project_activity', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6l0 13" />
                                                            <path d="M12 6l0 13" />
                                                            <path d="M21 6l0 13" />
                                                        </svg>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-bug" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                            <path
                                                                d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                            <path d="M3 13l4 0" />
                                                            <path d="M17 13l4 0" />
                                                            <path d="M12 20l0 -6" />
                                                            <path d="M4 19l3.35 -2" />
                                                            <path d="M20 19l-3.35 -2" />
                                                            <path d="M4 7l3.75 2.4" />
                                                            <path d="M20 7l-3.75 2.4" />
                                                        </svg>
                                                    @endif
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Detalles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <div>
                                                    @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                        <select name="state" id="state"
                                                            @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                            @endif
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                            @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            @foreach ($task['filteredActions'] as $action)
                                                                <option value="{{ $action }}">
                                                                    {{ $action }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="state" id="state"
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            </d>
                                                    @endif
                                                    @if (array_key_exists('report_id', $task))
                                                        @if ($task['count'] != null)
                                                            <p class="text-xs text-red-600">Reincidencia
                                                                {{ $task['count'] }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="principal">
                                                    @if (array_key_exists('project_activity', $task))
                                                        <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-justify text-xs font-semibold">
                                                            Proyecto no disponible
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                <select
                                                    @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                    name="delegate" id="delegate"
                                                    @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                    @endif
                                                    class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                    @foreach ($task['usersFiltered'] as $userFiltered)
                                                        <option value="{{ $userFiltered->id }}">
                                                            {{ $userFiltered->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="delegate" id="delegate"
                                                    class="inpSelectTable w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($tareasAtrasadas as $key => $task)
                                    <div id="task-{{ $task['id'] }}"
                                        class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                        <div class="flex justify-between text-xs">
                                            <div class="flex items-center">
                                                <p
                                                    class="font-bold bg{{ $task['project_priority'] }} rounded-full mr-1 p-1">
                                                    {{ $task['project_priority'] }}</p>
                                                <p class="my-auto text-left font-semibold text-gray-400">
                                                    {{ $task['project_name'] }}
                                                </p>
                                            </div>
                                            <div class="principal w-6 cursor-pointer"
                                                @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                @if (array_key_exists('project_activity', $task))
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6l0 13" />
                                                        <path d="M12 6l0 13" />
                                                        <path d="M21 6l0 13" />
                                                    </svg>
                                                @elseif(array_key_exists('project_report', $task))
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-bug" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                        <path
                                                            d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                        <path d="M3 13l4 0" />
                                                        <path d="M17 13l4 0" />
                                                        <path d="M12 20l0 -6" />
                                                        <path d="M4 19l3.35 -2" />
                                                        <path d="M20 19l-3.35 -2" />
                                                        <path d="M4 7l3.75 2.4" />
                                                        <path d="M20 7l-3.75 2.4" />
                                                    </svg>
                                                @endif
                                                <div class="relative">
                                                    <div
                                                        class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                        <p>Detalles</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                        <div class="flex justify-between">
                                            <div>
                                                @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                    <select name="state" id="state"
                                                        @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                        @endif
                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                        @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                        <option selected value={{ $task['state'] }}>
                                                            {{ $task['state'] }}
                                                        </option>
                                                        @foreach ($task['filteredActions'] as $action)
                                                            <option value="{{ $action }}">
                                                                {{ $action }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="state" id="state"
                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                        <option selected value={{ $task['state'] }}>
                                                            {{ $task['state'] }}
                                                        </option>
                                                        </d>
                                                @endif
                                                @if (array_key_exists('report_id', $task))
                                                    @if ($task['count'] != null)
                                                        <p class="text-xs text-red-600">Reincidencia
                                                            {{ $task['count'] }}</p>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="principal">
                                                @if (array_key_exists('project_activity', $task))
                                                    <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path
                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                    </a>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Ver mas</p>
                                                        </div>
                                                    </div>
                                                @elseif(array_key_exists('project_report', $task))
                                                    <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                        target="_blank" rel="noopener noreferrer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                            <path
                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                        </svg>
                                                    </a>
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Ver mas</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <p class="text-justify text-xs font-semibold">
                                                        Proyecto no disponible
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                            <select
                                                @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                name="delegate" id="delegate"
                                                @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                @endif
                                                class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                <option selected value={{ $task['delegate_id'] }}>
                                                    {{ $task['delegate_name'] }}
                                                </option>
                                                @foreach ($task['usersFiltered'] as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $task['delegate_id'] }}>
                                                    {{ $task['delegate_name'] }}
                                                </option>
                                            </select>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        {{-- Fecha actual --}}
                        <div class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-container @endif bg-orange-200 p-2 mx-2 w-full rounded-xl mb-2">
                            <div class="title-container bg-white m-auto text-center rounded-md p-1 w-48">
                                <p>{{ $fechaActual }}</p>
                                <p class="my-auto font-semibold text-gray-400 text-xs">Hoy</p>
                            </div>
                            @if ($seeProjects)
                                @foreach ($tareasActuales as $key => $project)
                                    <div
                                        class="title-proyect bg{{ $key }} flex justify-start p-2 text-left text-xs rounded-xl my-2">
                                        <p class="font-bold mr-1 my-auto">{{ $key }}</p>
                                        <p>{{ $project['project_name'] }}</p>
                                    </div>
                                    @foreach ($project['tasks'] as $task)
                                        <div id="task-{{ $task['id'] }}"
                                            class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                            <div class="flex justify-between">
                                                <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}
                                                </p>
                                                <div class="principal w-6 cursor-pointer"
                                                    @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                    @if (array_key_exists('project_activity', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6l0 13" />
                                                            <path d="M12 6l0 13" />
                                                            <path d="M21 6l0 13" />
                                                        </svg>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-bug" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                            <path
                                                                d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                            <path d="M3 13l4 0" />
                                                            <path d="M17 13l4 0" />
                                                            <path d="M12 20l0 -6" />
                                                            <path d="M4 19l3.35 -2" />
                                                            <path d="M20 19l-3.35 -2" />
                                                            <path d="M4 7l3.75 2.4" />
                                                            <path d="M20 7l-3.75 2.4" />
                                                        </svg>
                                                    @endif
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Detalles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <div>
                                                    @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                        <select name="state" id="state"
                                                            @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                            @endif
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                            @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            @foreach ($task['filteredActions'] as $action)
                                                                <option value="{{ $action }}">
                                                                    {{ $action }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="state" id="state"
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            </d>
                                                    @endif
                                                    @if (array_key_exists('report_id', $task))
                                                        @if ($task['count'] != null)
                                                            <p class="text-xs text-red-600">Reincidencia
                                                                {{ $task['count'] }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="principal">
                                                    @if (array_key_exists('project_activity', $task))
                                                        <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-justify text-xs font-semibold">
                                                            Proyecto no disponible
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                <select
                                                    @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                    name="delegate" id="delegate"
                                                    @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                    @endif
                                                    class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                    @foreach ($task['usersFiltered'] as $userFiltered)
                                                        <option value="{{ $userFiltered->id }}">
                                                            {{ $userFiltered->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="delegate" id="delegate"
                                                    class="inpSelectTable w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($tareasActualesFuturas as $task)
                                    @if (\Carbon\Carbon::parse($task['expected_date'])->isToday())
                                        <div id="task-{{ $task['id'] }}"
                                            class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                            <div class="flex justify-between text-xs">
                                                <div class="flex items-center">
                                                    <p
                                                        class="font-bold bg{{ $task['project_priority'] }} rounded-full mr-1 p-1">
                                                        {{ $task['project_priority'] }}</p>
                                                    <p class="my-auto text-left font-semibold text-gray-400">
                                                        {{ $task['project_name'] }}
                                                    </p>
                                                </div>
                                                <div class="principal w-6 cursor-pointer"
                                                    @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                    @if (array_key_exists('project_activity', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6l0 13" />
                                                            <path d="M12 6l0 13" />
                                                            <path d="M21 6l0 13" />
                                                        </svg>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-bug" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                            <path
                                                                d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                            <path d="M3 13l4 0" />
                                                            <path d="M17 13l4 0" />
                                                            <path d="M12 20l0 -6" />
                                                            <path d="M4 19l3.35 -2" />
                                                            <path d="M20 19l-3.35 -2" />
                                                            <path d="M4 7l3.75 2.4" />
                                                            <path d="M20 7l-3.75 2.4" />
                                                        </svg>
                                                    @endif
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Detalles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                            <div class="flex justify-between">
                                                <div>
                                                    @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                        <select name="state" id="state"
                                                            @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                            @endif
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                            @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            @foreach ($task['filteredActions'] as $action)
                                                                <option value="{{ $action }}">
                                                                    {{ $action }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="state" id="state"
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            </d>
                                                    @endif
                                                    @if (array_key_exists('report_id', $task))
                                                        @if ($task['count'] != null)
                                                            <p class="text-xs text-red-600">Reincidencia
                                                                {{ $task['count'] }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="principal">
                                                    @if (array_key_exists('project_activity', $task))
                                                        <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-justify text-xs font-semibold">
                                                            Proyecto no disponible
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                <select
                                                    @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                    name="delegate" id="delegate"
                                                    @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                    @endif
                                                    class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                    @foreach ($task['usersFiltered'] as $userFiltered)
                                                        <option value="{{ $userFiltered->id }}">
                                                            {{ $userFiltered->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="delegate" id="delegate"
                                                    class="inpSelectTable w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        {{-- Fechas futuras --}}
                        @foreach ($fechasFuturas as $fecha)
                            <div class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-container @endif bg-sky-200 p-2 mx-2 w-full rounded-xl mb-2">
                                <div class="title-container bg-white m-auto text-center rounded-md p-1 w-48">
                                    <p>{{ $fecha['fecha'] }}</p>
                                    <p class="my-auto font-semibold text-gray-400 text-xs">{{ $fecha['dia_semana'] }}
                                    </p>
                                    <!-- Día de la semana -->
                                </div>
                                @if ($seeProjects)
                                    @foreach ($tareasProximas as $key => $day)
                                        @if ($key === $fecha['fecha'])
                                            @foreach ($day as $key => $project)
                                                <div
                                                    class="title-proyect bg{{ $key }} flex justify-start p-2 text-left text-xs rounded-xl my-2">
                                                    <p class="font-bold mr-1 my-auto">{{ $key }}</p>
                                                    <p>{{ $project['project_name'] }}</p>
                                                </div>
                                                @foreach ($project['tasks'] as $task)
                                                    <div id="task-{{ $task['id'] }}"
                                                        class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                                        <div class="flex justify-between">
                                                            <p class="text-left">{{ $task['icon'] }}
                                                                {{ $task['title'] }}
                                                            </p>
                                                            <div class="principal w-6 cursor-pointer"
                                                                @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                                @if (array_key_exists('project_activity', $task))
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                        <path d="M3 6l0 13" />
                                                                        <path d="M12 6l0 13" />
                                                                        <path d="M21 6l0 13" />
                                                                    </svg>
                                                                @elseif(array_key_exists('project_report', $task))
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-bug"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" stroke-width="1.5"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                                        <path
                                                                            d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                                        <path d="M3 13l4 0" />
                                                                        <path d="M17 13l4 0" />
                                                                        <path d="M12 20l0 -6" />
                                                                        <path d="M4 19l3.35 -2" />
                                                                        <path d="M20 19l-3.35 -2" />
                                                                        <path d="M4 7l3.75 2.4" />
                                                                        <path d="M20 7l-3.75 2.4" />
                                                                    </svg>
                                                                @endif
                                                                <div class="relative">
                                                                    <div
                                                                        class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                        <p>Detalles</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <div>
                                                                @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                                    <select name="state" id="state"
                                                                        @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                                        @endif
                                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                                        @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                                        <option selected value={{ $task['state'] }}>
                                                                            {{ $task['state'] }}
                                                                        </option>
                                                                        @foreach ($task['filteredActions'] as $action)
                                                                            <option value="{{ $action }}">
                                                                                {{ $action }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <select name="state" id="state"
                                                                        class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                                        <option selected value={{ $task['state'] }}>
                                                                            {{ $task['state'] }}
                                                                        </option>
                                                                        </d>
                                                                @endif
                                                                @if (array_key_exists('report_id', $task))
                                                                    @if ($task['count'] != null)
                                                                        <p class="text-xs text-red-600">Reincidencia
                                                                            {{ $task['count'] }}</p>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="principal">
                                                                @if (array_key_exists('project_activity', $task))
                                                                    <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                        target="_blank" rel="noopener noreferrer">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                            <path
                                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                        </svg>
                                                                    </a>
                                                                    <div class="relative">
                                                                        <div
                                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                            <p>Ver mas</p>
                                                                        </div>
                                                                    </div>
                                                                @elseif(array_key_exists('project_report', $task))
                                                                    <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                        target="_blank" rel="noopener noreferrer">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path
                                                                                d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                            <path
                                                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                        </svg>
                                                                    </a>
                                                                    <div class="relative">
                                                                        <div
                                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                            <p>Ver mas</p>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <p class="text-justify text-xs font-semibold">
                                                                        Proyecto no disponible
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                            <select
                                                                @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                                name="delegate" id="delegate"
                                                                @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                                @endif
                                                                class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                                <option selected value={{ $task['delegate_id'] }}>
                                                                    {{ $task['delegate_name'] }}
                                                                </option>
                                                                @foreach ($task['usersFiltered'] as $userFiltered)
                                                                    <option value="{{ $userFiltered->id }}">
                                                                        {{ $userFiltered->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select name="delegate" id="delegate"
                                                                class="inpSelectTable w-full text-sm">
                                                                <option selected value={{ $task['delegate_id'] }}>
                                                                    {{ $task['delegate_name'] }}
                                                                </option>
                                                            </select>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($tareasActualesFuturas as $task)
                                        @if (\Carbon\Carbon::parse($task['expected_date'])->isoFormat('D MMM YYYY') === $fecha['fecha'])
                                            <div id="task-{{ $task['id'] }}"
                                                class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                                <div class="flex justify-between text-xs">
                                                    <div class="flex items-center">
                                                        <p
                                                            class="font-bold bg{{ $task['project_priority'] }} rounded-full mr-1 p-1">
                                                            {{ $task['project_priority'] }}</p>
                                                        <p class="my-auto text-left font-semibold text-gray-400">
                                                            {{ $task['project_name'] }}
                                                        </p>
                                                    </div>
                                                    <div class="principal w-6 cursor-pointer"
                                                        @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                        @if (array_key_exists('project_activity', $task))
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                <path d="M3 6l0 13" />
                                                                <path d="M12 6l0 13" />
                                                                <path d="M21 6l0 13" />
                                                            </svg>
                                                        @elseif(array_key_exists('project_report', $task))
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-bug"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                                <path
                                                                    d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                                <path d="M3 13l4 0" />
                                                                <path d="M17 13l4 0" />
                                                                <path d="M12 20l0 -6" />
                                                                <path d="M4 19l3.35 -2" />
                                                                <path d="M20 19l-3.35 -2" />
                                                                <path d="M4 7l3.75 2.4" />
                                                                <path d="M20 7l-3.75 2.4" />
                                                            </svg>
                                                        @endif
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Detalles</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                                <div class="flex justify-between">
                                                    <div>
                                                        @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                            <select name="state" id="state"
                                                                @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                                @endif
                                                                class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                                @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                                <option selected value={{ $task['state'] }}>
                                                                    {{ $task['state'] }}
                                                                </option>
                                                                @foreach ($task['filteredActions'] as $action)
                                                                    <option value="{{ $action }}">
                                                                        {{ $action }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select name="state" id="state"
                                                                class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                                <option selected value={{ $task['state'] }}>
                                                                    {{ $task['state'] }}
                                                                </option>
                                                                </d>
                                                        @endif
                                                        @if (array_key_exists('report_id', $task))
                                                            @if ($task['count'] != null)
                                                                <p class="text-xs text-red-600">Reincidencia
                                                                    {{ $task['count'] }}</p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="principal">
                                                        @if (array_key_exists('project_activity', $task))
                                                            <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                target="_blank" rel="noopener noreferrer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                    <path
                                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                </svg>
                                                            </a>
                                                            <div class="relative">
                                                                <div
                                                                    class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                    <p>Ver mas</p>
                                                                </div>
                                                            </div>
                                                        @elseif(array_key_exists('project_report', $task))
                                                            <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                target="_blank" rel="noopener noreferrer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                    <path
                                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                </svg>
                                                            </a>
                                                            <div class="relative">
                                                                <div
                                                                    class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                    <p>Ver mas</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p class="text-justify text-xs font-semibold">
                                                                Proyecto no disponible
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                    <select
                                                        @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                        name="delegate" id="delegate"
                                                        @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                        @endif
                                                        class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                        <option selected value={{ $task['delegate_id'] }}>
                                                            {{ $task['delegate_name'] }}
                                                        </option>
                                                        @foreach ($task['usersFiltered'] as $userFiltered)
                                                            <option value="{{ $userFiltered->id }}">
                                                                {{ $userFiltered->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm">
                                                        <option selected value={{ $task['delegate_id'] }}>
                                                            {{ $task['delegate_name'] }}
                                                        </option>
                                                    </select>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                        {{-- Tareas de más de un mes agrupadas por fecha --}}
                        @foreach ($tareasAgrupadasPorFecha as $fecha => $tasks)
                            <div class="@if (Auth::user()->type_user === 1 || Auth::user()->area_id === 4) task-container @endif bg-neutral-300 p-2 mx-2 w-full rounded-xl mb-2">
                                <div class="title-container bg-white m-auto text-center rounded-md p-1 w-48">
                                    <p>{{ $fecha }}</p> <!-- Fecha -->
                                    <br>
                                </div>
                                @if ($seeProjects)
                                    @foreach ($tasks as $key => $project)
                                        <div
                                            class="title-proyect bg{{ $key }} flex justify-start p-2 text-left text-xs rounded-xl my-2">
                                            <p class="font-bold mr-1 my-auto">{{ $key }}</p>
                                            <p>{{ $project['project_name'] }}</p>
                                        </div>
                                        @foreach ($project['tasks'] as $task)
                                            <div id="task-{{ $task['id'] }}"
                                                class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                                <div class="flex justify-between">
                                                    <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}
                                                    </p>
                                                    <div class="principal w-6 cursor-pointer"
                                                        @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                        @if (array_key_exists('project_activity', $task))
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                                <path d="M3 6l0 13" />
                                                                <path d="M12 6l0 13" />
                                                                <path d="M21 6l0 13" />
                                                            </svg>
                                                        @elseif(array_key_exists('project_report', $task))
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-bug"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                                <path
                                                                    d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                                <path d="M3 13l4 0" />
                                                                <path d="M17 13l4 0" />
                                                                <path d="M12 20l0 -6" />
                                                                <path d="M4 19l3.35 -2" />
                                                                <path d="M20 19l-3.35 -2" />
                                                                <path d="M4 7l3.75 2.4" />
                                                                <path d="M20 7l-3.75 2.4" />
                                                            </svg>
                                                        @endif
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Detalles</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between">
                                                    <div>
                                                        @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                            <select name="state" id="state"
                                                                @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                                @endif
                                                                class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                                @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                                <option selected value={{ $task['state'] }}>
                                                                    {{ $task['state'] }}
                                                                </option>
                                                                @foreach ($task['filteredActions'] as $action)
                                                                    <option value="{{ $action }}">
                                                                        {{ $action }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <select name="state" id="state"
                                                                class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                                <option selected value={{ $task['state'] }}>
                                                                    {{ $task['state'] }}
                                                                </option>
                                                                </d>
                                                        @endif
                                                        @if (array_key_exists('report_id', $task))
                                                            @if ($task['count'] != null)
                                                                <p class="text-xs text-red-600">Reincidencia
                                                                    {{ $task['count'] }}</p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="principal">
                                                        @if (array_key_exists('project_activity', $task))
                                                            <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                target="_blank" rel="noopener noreferrer">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                    <path
                                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                </svg>
                                                            </a>
                                                            <div class="relative">
                                                                <div
                                                                    class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                    <p>Ver mas</p>
                                                                </div>
                                                            </div>
                                                        @elseif(array_key_exists('project_report', $task))
                                                            <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                                target="_blank" rel="noopener noreferrer">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                    <path
                                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                                </svg>
                                                            </a>
                                                            <div class="relative">
                                                                <div
                                                                    class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                    <p>Ver mas</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p class="text-justify text-xs font-semibold">
                                                                Proyecto no disponible
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                    <select
                                                        @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                        name="delegate" id="delegate"
                                                        @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                        @endif
                                                        class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                        <option selected value={{ $task['delegate_id'] }}>
                                                            {{ $task['delegate_name'] }}
                                                        </option>
                                                        @foreach ($task['usersFiltered'] as $userFiltered)
                                                            <option value="{{ $userFiltered->id }}">
                                                                {{ $userFiltered->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm">
                                                        <option selected value={{ $task['delegate_id'] }}>
                                                            {{ $task['delegate_name'] }}
                                                        </option>
                                                    </select>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    @foreach ($tasks as $task)
                                        <div id="task-{{ $task['id'] }}"
                                            class="@if (Auth::user()->type_user === 1) task-item @endif @if (array_key_exists('project_activity', $task)) activity @elseif (array_key_exists('project_report', $task)) report @endif bg-white border-l-8 @if ($task['priority'] == 'Alto') border-red-500 @elseif ($task['priority'] == 'Medio') border-yellow-400 @elseif ($task['priority'] == 'Bajo') border-blue-500 @endif p-2 my-2 rounded-md">
                                            <div class="flex justify-between text-xs">
                                                <div class="flex items-center">
                                                    <p
                                                        class="font-bold bg{{ $task['project_priority'] }} rounded-full mr-1 p-1">
                                                        {{ $task['project_priority'] }}</p>
                                                    <p class="my-auto text-left font-semibold text-gray-400">
                                                        {{ $task['project_name'] }}
                                                    </p>
                                                </div>
                                                <div class="principal w-6 cursor-pointer"
                                                    @if (array_key_exists('project_activity', $task)) wire:click="show({{ $task['id'] }}, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:click="show({{ $task['id'] }}, 'report')" @endif>
                                                    @if (array_key_exists('project_activity', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                            <path d="M3 6l0 13" />
                                                            <path d="M12 6l0 13" />
                                                            <path d="M21 6l0 13" />
                                                        </svg>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-bug" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                            <path
                                                                d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                            <path d="M3 13l4 0" />
                                                            <path d="M17 13l4 0" />
                                                            <path d="M12 20l0 -6" />
                                                            <path d="M4 19l3.35 -2" />
                                                            <path d="M20 19l-3.35 -2" />
                                                            <path d="M4 7l3.75 2.4" />
                                                            <path d="M20 7l-3.75 2.4" />
                                                        </svg>
                                                    @endif
                                                    <div class="relative">
                                                        <div
                                                            class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                            <p>Detalles</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-left">{{ $task['icon'] }} {{ $task['title'] }}</p>
                                            <div class="flex justify-between">
                                                <div>
                                                    @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                        <select name="state" id="state"
                                                            @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                            @endif
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                                            @if (array_key_exists('project_activity', $task)) wire:change.defer="updateState({{ $task['id'] }}, null, $event.target.value)" @elseif(array_key_exists('project_report', $task)) wire:change="updateState({{ $task['id'] }}, {{ $task['project_id'] }}, $event.target.value)" @endif>
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            @foreach ($task['filteredActions'] as $action)
                                                                <option value="{{ $action }}">
                                                                    {{ $action }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="state" id="state"
                                                            class="inpSelectTable @if ($task['state'] == 'Abierto') bg-blue-500 text-white @endif @if ($task['state'] == 'Proceso') bg-yellow-400 @endif @if ($task['state'] == 'Resuelto') bg-lime-700 text-white @endif @if ($task['state'] == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                            <option selected value={{ $task['state'] }}>
                                                                {{ $task['state'] }}
                                                            </option>
                                                            </d>
                                                    @endif
                                                    @if (array_key_exists('report_id', $task))
                                                        @if ($task['count'] != null)
                                                            <p class="text-xs text-red-600">Reincidencia
                                                                {{ $task['count'] }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="principal">
                                                    @if (array_key_exists('project_activity', $task))
                                                        <a href="{{ route('projects.activities.index', ['project' => $task['project_id'], 'activity' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @elseif(array_key_exists('project_report', $task))
                                                        <a href="{{ route('projects.reports.index', ['project' => $task['project_id'], 'reports' => $task['id'], 'highlight' => $task['id']]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                        <div class="relative">
                                                            <div
                                                                class="hidden-info absolute -top-3 left-4 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                                                <p>Ver mas</p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <p class="text-justify text-xs font-semibold">
                                                            Proyecto no disponible
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (array_key_exists('project_activity', $task) || array_key_exists('project_report', $task))
                                                <select
                                                    @if (array_key_exists('project_activity', $task)) wire:change.defer="updateDelegate({{ $task['id'] }}, $event.target.value, 'activity')" @elseif(array_key_exists('project_report', $task)) wire:change="updateDelegate({{ $task['id'] }},$event.target.value, 'report')" @endif
                                                    name="delegate" id="delegate"
                                                    @if (array_key_exists('project_activity', $task)) @if ($task['sprint_state'] == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif
                                                    @endif
                                                    class="inpSelectTable @if ($task['state'] == 'Resuelto') hidden @endif w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                    @foreach ($task['usersFiltered'] as $userFiltered)
                                                        <option value="{{ $userFiltered->id }}">
                                                            {{ $userFiltered->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <select name="delegate" id="delegate"
                                                    class="inpSelectTable w-full text-sm">
                                                    <option selected value={{ $task['delegate_id'] }}>
                                                        {{ $task['delegate_name'] }}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                        <!-- Botón para mostrar mas meses -->
                        <div class="mx-5">
                            <button wire:click="cargarMasMeses"
                                class="principal right-0 top-0 transform -translate-x-1/2 text-white p-2 rounded-full shadow-lg z-10 hover:bg-secundaryColor bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v5" />
                                    <path d="M16 3v4" />
                                    <path d="M8 3v4" />
                                    <path d="M4 11h16" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                </svg>
                                <div class="relative">
                                    <div
                                        class="hidden-info absolute top-0 left-0 z-10 w-auto bg-gray-100 p-2 text-left text-xs text-black">
                                        <p>Mostrar mas meses</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Botón para desplazar a la derecha -->
                <button id="scroll-right"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white p-2 rounded-full shadow-lg hover:bg-secundaryColor z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 6l6 6l-6 6" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW --}}
    @if ($show && $taskShow)
        <div class="left-0 top-20 z-50 block max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="@if ($taskShow->evidence == 1) md:w-4/5 @else md:w-3/4 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                    style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            {{ $taskShow->icon }} {{ $taskShow->title }}
                        </h3>
                        <svg wire:click="show(0, 'null')" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    @if ($taskShowType == 'activity')
                        <livewire:modals.reports-activities.show :recordingshow="$taskShow->id" :type="'activity'">
                        @else
                            <livewire:modals.reports-activities.show :recordingshow="$taskShow->id" :type="'report'">
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL SHOW --}}
    {{-- MODAL EVIDENCE --}}
    @if ($evidenceActRep)
        <div class="left-0 top-20 z-50 block max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Reporte con evidencia</h3>
                    </div>
                    <div class="modalBody">
                        <div class="md-3/4 mt-5 flex w-full flex-col">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-full px-3">
                                    <p class="text-justify">
                                        Para completar tu reporte, haz clic en el botón <strong>"Continuar"</strong>.
                                        Serás redirigido a la página donde se encuentra el reporte. Una vez allí,
                                        selecciona la opción "Resuelto" y carga la evidencia correspondiente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalFooter">
                        <button class="btnSave"
                            wire:click="finishEvidence({{ $reportEvidence->project->id }}, {{ $reportEvidence->id }})">
                            Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading>
        <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40"></div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>
    {{-- END LOADING PAGE --}}
    {{-- END MODAL EVIDENCE --}}
    @push('js')
        <script src="{{ asset('js/kanvan-my-activities.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Verifica si la URL contiene el parámetro 'highlight'
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('highlight')) {
                    // Obtiene el ID del reporte a resaltar
                    const reportId = urlParams.get('highlight');
                    // Selecciona la fila que deseas resaltar
                    const row = document.getElementById('report-' + reportId);
                    if (row) {
                        // Cambia el color de la fila a rojo
                        row.style.backgroundColor = 'rgb(215 229 231)';
                        // Después de 30 segundos, restaura el color original
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 15000); // segundos
                    }
                }
            });

            window.addEventListener('file-reset', () => {
                document.getElementById('evidence').value = null;
            });
            // MODALS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });

            // BOTONES DE DESPLAZAMIENTO DERECHA - IZQUIERDA
            function initializeScrollButtons() {
                const kanbanContainer = document.getElementById('kanvan-my-activities');
                const scrollLeftButton = document.getElementById('scroll-left');
                const scrollRightButton = document.getElementById('scroll-right');

                if (!kanbanContainer || !scrollLeftButton || !scrollRightButton) {
                    console.error('No se encontraron los elementos necesarios.');
                    return; // Salir si no se encuentran los elementos
                }

                // Verificar si hay más contenido para desplazar
                const checkScroll = () => {
                    if (kanbanContainer.scrollLeft === 0) {
                        scrollLeftButton.style.display = 'none';
                    } else {
                        scrollLeftButton.style.display = 'block';
                    }

                    if (kanbanContainer.scrollLeft + kanbanContainer.clientWidth >= kanbanContainer.scrollWidth) {
                        scrollRightButton.style.display = 'none';
                    } else {
                        scrollRightButton.style.display = 'block';
                    }
                };

                // Verificar el desplazamiento inicial
                checkScroll();

                // Verificar el desplazamiento al mover el scroll
                kanbanContainer.addEventListener('scroll', checkScroll);

                // Desplazar a la izquierda
                scrollLeftButton.addEventListener('click', () => {
                    kanbanContainer.scrollBy({
                        left: -200,
                        behavior: 'smooth'
                    });
                });

                // Desplazar a la derecha
                scrollRightButton.addEventListener('click', () => {
                    kanbanContainer.scrollBy({
                        left: 200,
                        behavior: 'smooth'
                    });
                });
            }
            // Escuchar el evento de Livewire para inicializar los botones
            window.addEventListener('initializeScrollButtons', initializeScrollButtons);
            // Inicializar los botones cuando la página se carga
            document.addEventListener('DOMContentLoaded', initializeScrollButtons);

            let modalEvidence = document.getElementById('modalEvidence');
            modalEvidence.addEventListener('click', function() {
                console.log('click');

                Swal.fire({
                    title: 'Confirmación de cierre',
                    text: "Si cierras sin subir evidencia, el reporte permanecerá sin actualizar. ¿Seguro que quieres continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4faead',
                    cancelButtonColor: '#0062cc',
                    confirmButtonText: 'Sí, cerrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    } else {

                    }
                });
            });
        </script>
    @endpush
</div>
