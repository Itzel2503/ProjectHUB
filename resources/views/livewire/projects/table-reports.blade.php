<div>
    {{-- Tabla usuarios --}}
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
                        <input wire:model="search" type="text" placeholder="Buscar reporte" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
                @if (Auth::user()->type_user == 1)
                    <!-- DELEGATE -->
                    <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                        <select wire:model.lazy="selectedDelegate" class="inputs">
                            <option value="">Delegados</option>
                            @foreach ($allUsersFiltered as $userFiltered)
                                <option value="{{ $userFiltered['id'] }}">{{ $userFiltered['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
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
                {{-- <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <div class="flex w-full justify-center">
                        <div class="relative w-full">
                            <!-- Button -->
                            <button type="button" class="inputs flex h-12 items-center justify-between"
                                wire:click="$set('isOptionsVisibleState', {{ $isOptionsVisibleState ? 'false' : 'true' }})">
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
                            @if ($isOptionsVisibleState)
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
                                    <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Resuelto"
                                            class="mr-2">
                                        Resuelto
                                    </label>
                                    <label class="block px-4 py-2">
                                        <input type="checkbox" wire:model="selectedStates" value="Conflicto"
                                            class="mr-2">
                                        Conflicto
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
                <!-- PROYECTOS -->
                @if ($project == null)
                    <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                        <select wire:model.lazy="selectedProjects" class="inputs">
                            <option value="">Proyectos</option>
                            @foreach ($allProjectsFiltered as $projectFiltered)
                                <option value="{{ $projectFiltered['id'] }}">{{ $projectFiltered['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($project != null)
                    <!-- BTN NEW -->
                    <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:hidden md:px-0">
                        <button wire:click="create({{ $project->id }})" class="btnNuevo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Reporte
                        </button>
                    </div>
                @endif
            </div>
            @if ($project != null)
                <!-- BTN NEW -->
                <div class="mb-2 hidden h-12 w-1/6 bg-transparent md:inline-flex">
                    <button wire:click="create({{ $project->id }})" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Reporte
                    </button>
                </div>
            @endif
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="whitespace-no-wrap table-hover table w-full">
                <thead class="headTable border-0">
                    <tr class="text-left">
                        <th class="w-96 px-4 py-3">
                            <div class="flex">
                                Reporte
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
                                <svg wire:click="filterDown('delegate')" xmlns="http://www.w3.org/2000/svg"
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
                                <svg wire:click="filterUp('delegate')" xmlns="http://www.w3.org/2000/svg"
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
                    @foreach ($reports as $report)
                        <tr class="trTable" id="report-{{ $report->id }}">
                            <td class="relative px-2 py-1">
                                <div wire:click="showReport({{ $report->id }})"
                                    class="@if ($project != null) flex @endif cursor-pointer flex-row items-center text-center">
                                    @if ($project == null)
                                        <div class="flex flex-row">
                                            <div class="w-12"></div>
                                            @if ($report->project)
                                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                    {{ $report->project->name }}
                                                </p>
                                            @else
                                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                    Proyecto no disponible
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="flex flex-row">
                                        <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($report->priority == 'Alto') text-red-500 @endif @if ($report->priority == 'Medio') text-yellow-400 @endif @if ($report->priority == 'Bajo') text-blue-500 @endif">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                            </svg>
                                        </div>
                                        <p class="my-auto text-left text-xs font-semibold">
                                            {{ $report->icon }} {{ $report->title }}
                                        </p>
                                    </div>

                                    @if ($report->contentExists == false)
                                        <small class="ml-2 text-red-600">(Falta archivo)</small>
                                    @endif
                                    @if ($report->messages_count >= 1)
                                        {{-- usuario --}}
                                        @if ($report->user_id != Auth::id() && $report->receiver_id == Auth::id())
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
                                            {{-- administrador --}}
                                        @elseif(Auth::user()->type_user == 1 && $report->client == true)
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
                                    @if (Auth::user()->type_user == 3)
                                        <p class="my-auto text-left text-xs font-semibold">Arten</p>
                                    @else
                                        @if ($report->delegate)
                                            <p
                                                class="@if ($report->state == 'Resuelto') font-semibold @else hidden @endif">
                                                {{ $report->delegate->name }}</p>
                                            <select
                                                wire:change='updateDelegate({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate"
                                                class="inpSelectTable @if ($report->state == 'Resuelto') hidden @endif w-full text-sm">
                                                <option selected value={{ $report->delegate->id }}>
                                                    {{ $report->delegate->name }} </option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <p
                                                class="@if ($report->state == 'Resuelto') font-semibold @else hidden @endif">
                                                Usuario eliminado</p>
                                            <select
                                                wire:change='updateDelegate({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate"
                                                class="inpSelectTable @if ($report->state == 'Resuelto') hidden @endif w-full text-sm">
                                                <option selected>
                                                    Seleccionar...</option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @endif
                                    <p class="text-xs">
                                        @if ($report->state == 'Proceso' || $report->state == 'Conflicto')
                                            Progreso {{ $report->progress->diffForHumans(null, false, false, 1) }}
                                        @else
                                            @if ($report->state == 'Resuelto')
                                                @if ($report->progress == null)
                                                    Sin desarrollo
                                                @else
                                                    Desarrollo {{ $report->timeDifference }}
                                                @endif
                                            @else
                                                @if ($report->look == true)
                                                    Visto
                                                    {{ $report->progress->diffForHumans(null, false, false, 1) }}
                                                @endif
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <div>
                                    @if (Auth::user()->type_user == 3)
                                        <p
                                            class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif w-1/2 text-sm font-semibold">
                                            {{ $report->state }}
                                        </p>
                                    @else
                                        @if ($project == null)
                                            <select wire:change='updateState({{ $report->id }}, 0, $event.target.value)'
                                                name="state" id="state"
                                                class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                                @foreach ($report->filteredActions as $action)
                                                    <option value="{{ $action }}">{{ $action }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select
                                                wire:change='updateState({{ $report->id }}, {{ $project->id }}, $event.target.value)'
                                                name="state" id="state"
                                                class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                                @foreach ($report->filteredActions as $action)
                                                    <option value="{{ $action }}">{{ $action }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @endif
                                    @if ($report->count)
                                        <p class="text-left text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                @if ($report->updated_expected_date == false && $report->state != 'Resuelto')
                                    @if (Auth::user()->type_user != 3)
                                        <div class="my-auto text-left">
                                            <input type="date" wire:model='expected_day.{{ $report->id }}'
                                                wire:change="updateExpectedDay({{ $report->id }}, $event.target.value)">
                                            <p class="text-xs text-red-600">Cliente en espera de fecha</p>
                                        </div>
                                    @else
                                        <div class="my-auto text-left">
                                            En espera
                                        </div>
                                    @endif
                                @else
                                    <div class="my-auto text-left">
                                        @if (Auth::user()->type_user === 1 && $report->state != 'Resuelto')
                                            <input type="date" wire:model='expected_day.{{ $report->id }}'
                                                wire:change="updateExpectedDay({{ $report->id }}, $event.target.value)">
                                            {{-- Mostrar mensaje de advertencia si la fecha es inválida --}}
                                            @if (isset($this->errorMessages[$report->id]))
                                                <p class="pl-2 text-xs italic text-red-600">{{ $this->errorMessages[$report->id] }}</p>
                                            @endif
                                        @else
                                            @if($report->expected_date == '')
                                                <p class="text-red-600">Sin fecha</p>
                                            @else
                                                {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-2 py-1">
                                <div class="mx-auto text-left">
                                    @if ($report->user)
                                        <span class="font-semibold"> {{ $report->user->name }}</span> <br>
                                    @else
                                        <span class="font-semibold"> Usuario eliminado </span> <br>
                                    @endif
                                    <span class="font-mono">
                                        {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <div class="principal flex justify-center">
                                    @if ($project == null)
                                        @if ($report->project)
                                            <a href="{{ route('projects.reports.index', ['project' => $report->project->id, 'reports' => $report->id, 'highlight' => $report->id]) }}"
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
                                    @else
                                        <div
                                            class="@if (Auth::user()->type_user == 3) @if ($report->state != 'Abierto' && $report->state != 'Resuelto') hidden @else relative @endif
                                                @else
                                                @endif relative">
                                            <!-- Button -->
                                            <button wire:click="togglePanel({{ $report->id }})" type="button"
                                                class="flex items-center px-5 py-2.5">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-dots-vertical" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                </svg>
                                            </button>
                                            <!-- Panel -->
                                            @if (isset($visiblePanels[$report->id]) && $visiblePanels[$report->id])
                                                <div
                                                    class="@if (Auth::user()->type_user == 1 || (isset($report->user) && Auth::user()->id == $report->user->id)) {{ $loop->last ? '-top-16' : 'top-3' }} @else {{ $loop->last ? '-top-8' : 'top-3' }} @endif absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                                    <!-- Botón Editar -->
                                                    <div wire:click="editReport({{ $report->id }})"
                                                        class="@if ($report->state == 'Resuelto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit mr-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                            <path
                                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                            <path d="M16 5l3 3" />
                                                        </svg>
                                                        Editar
                                                    </div>
                                                    @if (Auth::user()->type_user == 1 || (isset($report->user) && Auth::user()->id == $report->user->id))
                                                        <!-- Botón Eliminar -->
                                                        <div wire:click="$emit('deleteReport',{{ $report->id }}, {{ $project->id }})"
                                                            class="@if ($report->state != 'Abierto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-red-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-trash mr-2"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor"
                                                                fill="none" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z"
                                                                    fill="none" />
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path
                                                                    d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                            Eliminar
                                                        </div>
                                                    @endif
                                                    <!-- Botón Reincidencia -->
                                                    @if ($report->state == 'Resuelto')
                                                        <div @if ($report->report_id == null || ($report->report_id != null && $report->repeat == true)) wire:click="reportRepeat({{ $project->id }}, {{ $report->id }})" @endif
                                                            class="flex cursor-pointer px-4 py-2 text-sm text-black">
                                                            @if ($report->report_id == null || ($report->report_id != null && $report->repeat == true))
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-bug-filled mr-2"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M12 4a4 4 0 0 1 3.995 3.8l.005 .2a1 1 0 0 1 .428 .096l3.033 -1.938a1 1 0 1 1 1.078 1.684l-3.015 1.931a7.17 7.17 0 0 1 .476 2.227h3a1 1 0 0 1 0 2h-3v1a6.01 6.01 0 0 1 -.195 1.525l2.708 1.616a1 1 0 1 1 -1.026 1.718l-2.514 -1.501a6.002 6.002 0 0 1 -3.973 2.56v-5.918a1 1 0 0 0 -2 0v5.917a6.002 6.002 0 0 1 -3.973 -2.56l-2.514 1.503a1 1 0 1 1 -1.026 -1.718l2.708 -1.616a6.01 6.01 0 0 1 -.195 -1.526v-1h-3a1 1 0 0 1 0 -2h3.001v-.055a7 7 0 0 1 .474 -2.173l-3.014 -1.93a1 1 0 1 1 1.078 -1.684l3.032 1.939l.024 -.012l.068 -.027l.019 -.005l.016 -.006l.032 -.008l.04 -.013l.034 -.007l.034 -.004l.045 -.008l.015 -.001l.015 -.002l.087 -.004a4 4 0 0 1 4 -4zm0 2a2 2 0 0 0 -2 2h4a2 2 0 0 0 -2 -2z"
                                                                        stroke-width="0" fill="currentColor" />
                                                                </svg>Reincidencia
                                                            @else
                                                                Sin acciones
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-5">
            {{ $reports->links() }}
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW --}}
    @if ($showReport && $reportShow)
        <div class="left-0 top-20 z-50 block max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="@if ($reportShow->evidence == 1) md:w-4/5 @else md:w-3/4 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                    style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            {{ $reportShow->icon }} {{ $reportShow->title }}
                        </h3>
                        <svg wire:click="showReport(0)" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <livewire:modals.reports-activities.show :recordingshow="$reportShow->id" :type="'report'">
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL SHOW --}}
    {{-- MODAL EDIT --}}
    @if ($editReport && $reportEdit)
        <div class="left-0 top-20 z-50 block max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="@if (Auth::user()->type_user != 3) md:w-3/4 @else md:w-2/5 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                    style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Editar reporte</h3>
                        <svg wire:click="editReport(0)" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <livewire:modals.reports-activities.edit :recordingedit="$reportEdit->id" :type="'report'">
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL EDIT --}}
    {{-- MODAL EVIDENCE --}}
    @if ($showEvidence && $reportEvidence)
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
                            Evidencia</h3>
                        <svg id="showEvidence" wire:click="$emit('evidenceReport',{{ $reportEvidence->id }})"
                            class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="modalBody">
                        <div class="md-3/4 mt-5 flex w-full flex-col">
                            <div class="-mx-3 flex flex-row">
                                <div class="w-full px-3">
                                    <h5 class="inline-flex font-semibold" for="evidence">
                                        Para completar tu reporte, por favor, sube el archivo de evidencia.
                                    </h5>
                                    <input wire:model='evidence' required type="file" name="evidence"
                                        id="evidence" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('evidence')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalFooter">
                        <button class="btnSave"
                            wire:click="updateEvidence({{ $reportEvidence->id }}, {{ $reportEvidence->project_id }})">
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
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL EVIDENCE --}}
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
                            Reporte finalizado</h3>
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
    {{-- END MODAL EVIDENCE --}}
    {{-- LOADING PAGE --}}
    @if ($project != null)
        <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
            wire:target="$set('isOptionsVisibleState'), create, filterDown, filterUp, showReport, togglePanel, editReport, deleteReport, reportRepeat, updateEvidence, finishEvidence">
            <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40"></div>
            <div class="loadingspinner relative top-1/3 z-20">
                <div id="square1"></div>
                <div id="square2"></div>
                <div id="square3"></div>
                <div id="square4"></div>
                <div id="square5"></div>
            </div>
        </div>
    @else
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
    @endif
    {{-- END LOADING PAGE --}}
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Livewire.on('reportHighlighted', (reportId) => {
                    setTimeout(() => {
                        const row = document.getElementById('report-' + reportId);
                        if (row) {
                            // Resalta el reporte cambiando su fondo
                            row.style.backgroundColor = 'rgb(215 229 231)';
                            
                            // Hace scroll al elemento
                            row.scrollIntoView({ behavior: 'smooth', block: 'center' });

                            // Elimina el resaltado después de 15 segundos
                            setTimeout(() => {
                                row.style.backgroundColor = '';
                            }, 20000);
                        }
                    }, 800); // Espera breve para asegurar que la tabla se renderiza
                });

                // Verifica si hay un parámetro 'highlight' en la URL y lo envía a Livewire
                const urlParams = new URLSearchParams(window.location.search);
                const reportId = urlParams.get('highlight');
                if (reportId) {
                    Livewire.emit('reportHighlighted', reportId);
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

            Livewire.on('evidenceReport', (id) => {
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
                    }
                });
            });
        </script>
    @endpush
</div>
