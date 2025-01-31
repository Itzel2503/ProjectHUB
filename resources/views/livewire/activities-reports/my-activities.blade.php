<div>
    {{-- Tabla actividades y reportes --}}
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
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
                    <div class="flex w-full justify-center">
                        <div class="relative w-full">
                            <!-- Button -->
                            <button type="button" class="inputs flex h-12 items-center justify-between"
                                wire:click="$set('isOptionsVisible', {{ $isOptionsVisible ? 'false' : 'true' }})">
                                <span>Estados</span>
                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-chevron-down h-3 w-3" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 9l6 6l6 -6" />
                                </svg>
                            </button>
                            <!-- Panel -->
                            @if ($isOptionsVisible)
                                <div class="absolute left-0 z-10 mt-2 w-full rounded-md bg-white">
                                    <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Abierto"
                                            class="mr-2">
                                        Abierto
                                    </label>
                                    <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Proceso"
                                            class="mr-2">
                                        Proceso
                                    </label>
                                    {{-- <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Resuelto"
                                            class="mr-2">
                                        Resuelto
                                    </label> --}}
                                    <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Conflicto"
                                            class="mr-2">
                                        Conflicto
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
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
                                <svg wire:click="filterUp('priority')" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                <svg wire:click="filterUp('state')" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                    <div class="flex flex-row">
                                        <div class="w-12"></div>
                                        <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                            {{ $task->project_name }}
                                        </p>
                                    </div>
                                    <div class="flex flex-row">
                                        @if ($task->priority)
                                            <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor"
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
                                            {{ $task->title }}
                                        </p>
                                    </div>
                                    @if ($task->messages_count >= 1)
                                        {{-- usuario --}}
                                        @if ($task->user_chat != Auth::id() && $task->receiver_chat == Auth::id())
                                            <div class="absolute right-0 top-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                            name="delegate" id="delegate" @if($task->project_activity) @if($task->sprint_state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif @endif
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
                                        <select name="delegate" id="delegate" class="inpSelectTable w-full text-sm">
                                            <option selected value={{ $task->delegate_id }}>
                                                {{ $task->delegate_name }}
                                            </option>
                                        </select>
                                    @endif
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                @if ($task->project_activity || $task->project_report)
                                    <select name="state" id="state" @if($task->project_activity) @if($task->sprint_state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif @endif
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
                                <div class="my-auto text-left">
                                    @if ($task->created_id != null)
                                        @if (Auth::user()->id === $task->created_id && $task->state != 'Resuelto')
                                            <select
                                                @if ($task->project_activity) wire:change="updateExpectedDay({{ $task->id }}, 'activity', $event.target.value)" @elseif($task->project_report) wire:change="updateExpectedDay({{ $task->id }}, 'report', $event.target.value)" @endif
                                                wire:model="expected_day.{{ $task->id }}" name="expected_day"
                                                id="expected_day" class="inpSelectTable">
                                                @for ($day = 1; $day <= 31; $day++)
                                                    <option value={{ $day }}
                                                        {{ $day == \Carbon\Carbon::parse($task->expected_date)->day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select
                                                @if ($task->project_activity) wire:change="updateExpectedMonth({{ $task->id }}, 'activity', $event.target.value)" @elseif($task->project_report) wire:change="updateExpectedMonth({{ $task->id }}, 'report', $event.target.value)" @endif
                                                name="expected_month" id="expected_month" class="inpSelectTable">
                                                @foreach (['01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'] as $monthValue => $monthName)
                                                    <option value="{{ $monthValue }}"
                                                        {{ $monthValue == \Carbon\Carbon::parse($task->expected_date)->format('m') ? 'selected' : '' }}>
                                                        {{ $monthName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select
                                                @if ($task->project_activity) wire:change="updateExpectedYear({{ $task->id }}, 'activity', $event.target.value)" @elseif($task->project_report) wire:change="updateExpectedYear({{ $task->id }}, 'report', $event.target.value)" @endif
                                                name="expected_year" id="expected_year" class="inpSelectTable">
                                                @for ($year = now()->year - 1; $year <= now()->year + 2; $year++)
                                                    <option value="{{ $year }}"
                                                        {{ $year == \Carbon\Carbon::parse($task->expected_date)->year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endfor
                                            </select>
                                        @else
                                            {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                        @endif
                                    @else
                                        {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    @endif
                                </div>
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
                                <div class="flex justify-center">
                                    @if ($task->project_activity)
                                        <a
                                            href="{{ route('projects.activities.index', ['project' => $task->project_id, 'activity' => $task->id, 'highlight' => $task->id]) }}"
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
                                    @elseif($task->project_report)
                                        <a
                                            href="{{ route('projects.reports.index', ['project' => $task->project_id, 'reports' => $task->id, 'highlight' => $task->id]) }}"
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
            {{-- {{ $tasks->links() }} --}}
        </div>
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
                            {{ $taskShow->title }}
                        </h3>
                        <svg wire:click="show(0, 'null')" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                        Para completar tu reporte, haz clic en el botón <strong>"Continuar"</strong>. Serás redirigido a la página donde se encuentra el reporte. Una vez allí, selecciona la opción "Resuelto" y carga la evidencia correspondiente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalFooter">
                        <button class="btnSave" wire:click="finishEvidence({{ $reportEvidence->project->id }}, {{ $reportEvidence->id }})">
                            Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL EVIDENCE --}}
    @push('js')
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

            Livewire.on('deleteReport', (id, project_id) => {
                Swal.fire({
                    title: '¿Seguro que deseas eliminar este elemento?',
                    text: "Esta acción es irreversible",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#202a33',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', id, project_id);
                        Swal.fire(
                            '¡Eliminado!',
                            'Reporte eliminado',
                            'Exito'
                        )
                    }
                })
            });

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
