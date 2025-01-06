<div>
    {{-- Tabla actividades --}}
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
                        <input wire:model="search" type="text" placeholder="Buscar actividad" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
                <!-- DELEGATE -->
                @if (Auth::user()->type_user != 3)
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
                </div>
                <!-- BTN NEW -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:hidden md:px-0">
                    <button wire:click="create({{ $project->id }})"
                        class="@if ($sprint->state == 'Pendiente') btnDisabled disabled @else btnNuevo @endif">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Actividad
                    </button>
                </div>
            </div>
            <!-- BTN NEW -->
            <div class="mb-2 hidden h-12 w-1/6 bg-transparent md:inline-flex">
                <button
                    @if ($sprint->state == 'Pendiente') class="btnDisabled" @else
                    wire:click="create({{ $project->id }})" class="btnNuevo" @endif>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Actividad
                </button>
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
                                Actividad
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
                        <th class="w-44 px-1 py-3">
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
                    @foreach ($activities as $activity)
                        <tr id="activity-{{ $activity->id }}" class="trTable">
                            <td class="relative px-2 py-1">
                                <div wire:click="showActivity({{ $activity->id }})"
                                    class="flex cursor-pointer flex-row items-center text-center">
                                    @if ($activity->priority)
                                        <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($activity->priority == 'Alto') text-red-500 @endif @if ($activity->priority == 'Medio') text-yellow-400 @endif @if ($activity->priority == 'Bajo') text-blue-500 @endif">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12"></div>
                                    @endif
                                    <p class="my-auto text-left text-xs font-semibold">{{ $activity->title }}</p>
                                    @if ($activity->messages_count >= 1)
                                        {{-- usuario --}}
                                        @if ($activity->user_id != Auth::id() && $activity->receiver_id == Auth::id())
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
                                        @elseif(Auth::user()->type_user == 1 && $activity->client == true)
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
                                <div class="mx-auto w-auto text-left">
                                    @if ($activity->delegate)
                                        <p
                                            class="@if ($activity->state == 'Resuelto') font-semibold @else hidden @endif">
                                            {{ $activity->delegate->name }}</p>
                                        <select
                                            wire:change.defer='updateDelegate({{ $activity->id }}, $event.target.value)'
                                            name="delegate" id="delegate"
                                            class="inpSelectTable @if ($activity->state == 'Resuelto') hidden @endif w-full text-sm"
                                            @if ($sprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
                                            <option selected value={{ $activity->delegate->id }}>
                                                {{ $activity->delegate->name }}
                                            </option>
                                            @foreach ($activity->usersFiltered as $userFiltered)
                                                <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p
                                            class="@if ($activity->state == 'Resuelto') font-semibold @else hidden @endif">
                                            Usuario eliminado</p>
                                        <select
                                            wire:change.defer='updateDelegate({{ $activity->id }}, $event.target.value)'
                                            name="delegate" id="delegate"
                                            class="inpSelectTable @if ($activity->state == 'Resuelto') hidden @endif w-full text-sm"
                                            @if ($sprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
                                            <option selected>
                                                Seleccionar...
                                            </option>
                                            @foreach ($activity->usersFiltered as $userFiltered)
                                                <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <p class="text-xs">
                                        @if ($activity->state == 'Proceso' || $activity->state == 'Conflicto')
                                            Progreso {{ $activity->progress->diffForHumans(null, false, false, 1) }}
                                        @else
                                            @if ($activity->state == 'Resuelto')
                                                @if ($activity->progress == null)
                                                    Sin desarrollo
                                                @else
                                                    Desarrollo {{ $activity->timeDifference }}
                                                @endif
                                            @else
                                                @if ($activity->look == true)
                                                    Visto
                                                    {{ $activity->progress->diffForHumans(null, false, false, 1) }}
                                                @endif
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <select wire:change='updateState({{ $activity->id }}, $event.target.value)'
                                    name="state" id="state"
                                    class="inpSelectTable @if ($activity->state == 'Abierto') bg-blue-500 text-white @endif @if ($activity->state == 'Proceso') bg-yellow-400 @endif @if ($activity->state == 'Resuelto') bg-lime-700 text-white @endif @if ($activity->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                    @if ($sprint->state == 'Pendiente' && Auth::user()->type_user != 1) disabled @endif>
                                    <option selected value={{ $activity->state }}>{{ $activity->state }}</option>
                                    @foreach ($activity->filteredActions as $action)
                                        <option value="{{ $action }}">{{ $action }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-2 py-1">
                                <div class="my-auto text-left">
                                    {{ \Carbon\Carbon::parse($activity->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <div class="mx-auto text-left">
                                    @if ($activity->user)
                                        <span class="font-semibold"> {{ $activity->user->name }}</span> <br>
                                    @else
                                        <span class="font-semibold"> Usuario eliminado</span> <br>
                                    @endif
                                    <span class="font-mono">
                                        {{ \Carbon\Carbon::parse($activity->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <div class="flex justify-center">
                                    <div class="relative">
                                        <button wire:click="togglePanel({{ $activity->id }})" type="button"
                                            class="flex items-center px-5 py-2.5"
                                            @if ($sprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
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
                                        @if (isset($visiblePanels[$activity->id]) && $visiblePanels[$activity->id])
                                            <div
                                                class="@if (Auth::user()->type_user == 1 || Auth::user()->id == $activity->user->id) {{ $loop->last ? '-top-16' : 'top-3' }} @else {{ $loop->last ? '-top-8' : 'top-3' }} @endif absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                                <!-- Botón Editar -->
                                                <div wire:click="editActivity({{ $activity->id }})"
                                                    class="@if ($activity->state == 'Resuelto') hidden @endif flex cursor-pointer content-center px-4 py-2 text-sm text-black">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-edit mr-2" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                        </path>
                                                        <path
                                                            d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                        </path>
                                                        <path d="M16 5l3 3"></path>
                                                    </svg>
                                                    Editar
                                                </div>
                                                @if (Auth::user()->type_user == 1 || Auth::user()->id == $activity->user->id)
                                                    <!-- Botón Eliminar -->
                                                    <div wire:click="$emit('deleteActivity',{{ $activity->id }})"
                                                        class="@if ($activity->state != 'Abierto') hidden @endif flex cursor-pointer content-center px-4 py-2 text-sm text-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-trash mr-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M4 7l16 0"></path>
                                                            <path d="M10 11l0 6"></path>
                                                            <path d="M14 11l0 6"></path>
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                            </path>
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                        </svg>
                                                        Eliminar
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW ACTIVITY --}}
    <div id="modalShow"
        class="@if ($modalShowActivity) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                @if ($activityShow)
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            {{ $activityShow->title }}
                        </h3>
                        <svg wire:click="modalShowActivity()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
                @if ($showActivity)
                    <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
                        <div
                            class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-between border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                            <div class="text-justify text-base">
                                <h3 class="text-text2 text-lg font-bold">Descripción</h3>
                                {!! nl2br($activityShow->description) !!}<br><br>
                                @if ($showChat)
                                    <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                    <div id="messageContainer"
                                        class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                        @php
                                            $previousDate = null;
                                        @endphp
                                        @foreach ($messages as $index => $message)
                                            @php
                                                // Formato de la fecha para agrupar por día
                                                $currentDate = $message->created_at->format('Y-m-d');
                                            @endphp
                                            @if ($previousDate !== $currentDate)
                                                <!-- Mostrar la fecha solo cuando cambia -->
                                                <div class="text-center text-xs font-bold text-gray-500 py-2">
                                                    {{ $message->created_at->format('d M Y') }}
                                                </div>
                                                @php
                                                    // Actualizar la fecha previa
                                                    $previousDate = $currentDate;
                                                @endphp
                                            @endif
                                            <div
                                                class="{{ $message->user_id == Auth::user()->id ? 'justify-end' : 'justify-start' }} flex">
                                                <div class="mx-2 items-center">
                                                    @if ($message->user_id == Auth::user()->id)
                                                        <div class="flex items-start justify-end">
                                                            <!-- Columna para el mensaje -->
                                                            <div>
                                                                <div class="text-right">
                                                                    <span
                                                                        class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                                                    <span
                                                                        class="text-sm font-semibold text-black">Tú</span>
                                                                </div>
                                                                <div class="bg-primaryColor rounded-xl p-2 text-right">
                                                                    <span
                                                                        class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                                </div>
                                                            </div>
                                                            <!-- Columna para la foto de perfil -->
                                                            <div class="flex justify-end  w-1/6 mt-1 ml-1">
                                                                <div class="relative flex justify-center">
                                                                    @if (Auth::user()->profile_photo)
                                                                        <img class="h-8 w-8 rounded-full object-cover"
                                                                            aria-hidden="true"
                                                                            src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}"
                                                                            alt="Avatar" />
                                                                    @else
                                                                        <img class="h-8 w-8 rounded-full object-cover"
                                                                            aria-hidden="true"
                                                                            src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                                                                            alt="Avatar" />
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        @if (Auth::user()->type_user == 3)
                                                            <div class="flex items-start justify-end">
                                                                <!-- Columna para la foto de perfil -->
                                                                <div class="flex justify-end w-1/6 mt-1 mr-1">
                                                                    <div class="relative flex justify-center">
                                                                        <img class="h-8 w-8 rounded-full object-cover"
                                                                            aria-hidden="true"
                                                                            src="{{ asset('logos/favicon_v2.png') }}"
                                                                            alt="Avatar" />
                                                                    </div>
                                                                </div>
                                                                <!-- Columna para el mensaje -->
                                                                <div>
                                                                    <div class="text-left">
                                                                        <span
                                                                            class="text-sm font-semibold text-black">ARTEN</span>
                                                                        <span
                                                                            class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                                                    </div>
                                                                    <div class="bg-gray-200 rounded-xl p-2 text-left">
                                                                        <span
                                                                            class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="flex items-start justify-end">
                                                                <!-- Columna para la foto de perfil -->
                                                                <div class="flex justify-end w-1/6 mt-1 mr-1">
                                                                    <div class="relative flex justify-center">
                                                                        @if ($message->transmitter)
                                                                            @if ($message->transmitter->profile_photo)
                                                                                <img class="h-8 w-8 rounded-full object-cover"
                                                                                    aria-hidden="true"
                                                                                    src="{{ asset('usuarios/' . $message->transmitter->profile_photo) }}"
                                                                                    alt="Avatar" />
                                                                            @else
                                                                                <img class="h-8 w-8 rounded-full object-cover"
                                                                                    aria-hidden="true"
                                                                                    src="{{ Avatar::create($message->transmitter->name)->toBase64() }}"
                                                                                    alt="Avatar" />
                                                                            @endif
                                                                        @else
                                                                            <img class="h-8 w-8 rounded-full object-cover"
                                                                                aria-hidden="true"
                                                                                src="{{ asset('images/icons/user-off.png') }}"
                                                                                alt="Avatar" />
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <!-- Columna para el mensaje -->
                                                                <div>
                                                                    <div class="text-left">
                                                                        <span
                                                                            class="text-sm font-semibold text-black">{{ $message->transmitter ? $message->transmitter->name : 'Usuario eliminado' }}</span>
                                                                        <span
                                                                            class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                                                    </div>
                                                                    <div class="bg-gray-200 rounded-xl p-2 text-left">
                                                                        <span
                                                                            class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="my-6 flex w-auto flex-row">
                                <input wire:model.defer='message' type="text" name="message" id="message"
                                    placeholder="Mensaje" class="inputs"
                                    style="border-radius: 0.5rem 0px 0px 0.5rem !important">
                                <button class="@if ($activityShow->delegate) btnSave @else btnDisabled @endif"
                                    style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                    @if ($activityShow->delegate) wire:click="updateChat({{ $activityShow->id }})" @endif>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-send mr-2" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 14l11 -11" />
                                        <path
                                            d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                    </svg>
                                    Comentar
                                </button>
                            </div>
                        </div>
                        <div class="photos w-full px-5 lg:w-1/2">
                            @if (!empty($activityShow->image))
                                <div id="example" class="mb-6 w-auto">
                                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                        <div class="text-text2 text-center text-xl font-semibold md:flex">
                                            Detalle
                                        </div>
                                    </div>
                                    @if ($activityShow->imageExists)
                                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                            <a href="{{ asset('activities/' . $activityShow->image) }}"
                                                target="_blank">
                                                <img src="{{ asset('activities/' . $activityShow->image) }}"
                                                    alt="Activity Image">
                                            </a>
                                        </div>
                                    @else
                                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-photo-off">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M15 8h.01" />
                                                <path
                                                    d="M7 3h11a3 3 0 0 1 3 3v11m-.856 3.099a2.991 2.991 0 0 1 -2.144 .901h-12a3 3 0 0 1 -3 -3v-12c0 -.845 .349 -1.608 .91 -2.153" />
                                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                                <path d="M16.33 12.338c.574 -.054 1.155 .166 1.67 .662l3 3" />
                                                <path d="M3 3l18 18" />
                                            </svg>
                                            <p>Sin imagen</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="my-5 w-full text-center text-lg">
                                        <p class="text-red my-5">Sin archivo</p>
                                    </div>
                                </div>
                            @endif
                            @if ($activityShow->image == true)
                                <div class="flex items-center justify-center">
                                    <a href="{{ asset('activities/' . $activityShow->image) }}"
                                        download="{{ basename($activityShow->image) }}" class="btnSecondary"
                                        style="color: white;">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-download" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        &nbsp;
                                        Descargar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW ACTIVITY --}}
    {{-- MODAL EDIT / CREATE ACTIVITY --}}
    @if ($createEdit)
        <div class="block left-0 top-20 z-50 max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            @if ($activityEdit)
                                Editar actividad
                            @else
                                Crear actividad
                            @endif
                        </h3>
                        <svg @if ($activityEdit) wire:click="editActivity(0)" @else wire:click="create({{ $project->id }})" @endif
                            class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    @if ($activityEdit)
                        <livewire:modals.reports-activities.edit :recordingedit="$activityEdit->id" :backlog="$backlog" :sprint="$sprint->id" :type="'activity'">
                    @else
                        <livewire:modals.reports-activities.create :project="$project" :sprint="$sprint->id">
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL EDIT / CREATE ACTIVITY --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="modalCreateSprint, showEditSprint, modalBacklog modalCreateActivity, filterDown, filterUp, updateDelegate, showActivity, showEditActivity, modalBacklog, modalCreateSprint, updateSprint, createSprint, modalShowActivity, updateChat, modalCreateActivity, changePoints, updateActivity, createActivity">
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
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Verifica si la URL contiene el parámetro 'highlight'
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('highlight')) {
                    // Obtiene el ID del activity a resaltar
                    const activityId = urlParams.get('highlight');
                    // Selecciona la fila que deseas resaltar
                    const row = document.getElementById('activity-' + activityId);
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
            // MODALS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });

            Livewire.on('deleteActivity', deletebyId => {
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
                        window.livewire.emit('delete', deletebyId);
                        // Swal.fire(
                        //   '¡Eliminado!',
                        //   'Tu elemento ha sido eliminado.',
                        //   'Exito'
                        // )
                    }
                })
            });
        </script>
    @endpush
</div>
