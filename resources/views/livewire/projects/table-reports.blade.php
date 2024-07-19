<div>
    {{-- Tabla usuarios --}}
    <div class="l px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <!-- SEARCH -->
            <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
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
                @if (Auth::user()->type_user != 3)
                    <!-- DELEGATE -->
                    <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                        <select wire:model.lazy="selectedDelegate" class="inputs">
                            <option value="">Delegados</option>
                            @foreach ($allUsersFiltered as $key => $userFiltered)
                                <option value="{{ $key }}">{{ $userFiltered }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <!-- STATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <div class="flex w-full justify-center">
                        <div x-data="{
                            state: false,
                            toggle() {
                                if (this.state) {
                                    return this.close()
                                }
                                this.$refs.button.focus()
                                this.state = true
                            },
                            close(focusAfter) {
                                if (!this.state) return
                                this.state = false
                                focusAfter && focusAfter.focus()
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                            x-id="['dropdown-button']" class="relative w-full">
                            <!-- Button -->
                            <button x-ref="button" x-on:click="toggle()" :aria-expanded="state"
                                :aria-controls="$id('dropdown-button')" type="button"
                                class="inputs flex h-12 items-center justify-between">
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
                            <div x-ref="panel" x-show="state" x-on:click.outside="close($refs.button)"
                                :id="$id('dropdown-button')" style="display: none;"
                                class="absolute left-0 z-10 mt-2 w-full rounded-md bg-white">
                                <label class="block px-4 py-2">
                                    <input type="checkbox" wire:model="selectedStates" value="Abierto" class="mr-2">
                                    Abierto
                                </label>
                                <label class="block px-4 py-2">
                                    <input type="checkbox" wire:model="selectedStates" value="Proceso" class="mr-2">
                                    Proceso
                                </label>
                                <label class="block px-4 py-2">
                                    <input type="checkbox" wire:model="selectedStates" value="Resuelto" class="mr-2">
                                    Resuelto
                                </label>
                                <label class="block px-4 py-2">
                                    <input type="checkbox" wire:model="selectedStates" value="Conflicto" class="mr-2">
                                    Conflicto
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BTN NEW -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:hidden md:px-0">
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
            </div>
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
                                <svg wire:click="filterDown('priority')" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                        <th class="px-1 py-3 lg:w-48">Delegado</th>
                        <th class="w-48 px-2 py-3">Estado</th>
                        <th class="w-44 px-1 py-3">
                            <div class="flex items-center">
                                Fecha de entrega
                                {{-- down-up --}}
                                <svg wire:click="filterDown('expected_date')" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filtered) block @else hidden @endif ml-2 cursor-pointer">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 3l0 18" />
                                    <path d="M10 18l-3 3l-3 -3" />
                                    <path d="M7 21l0 -18" />
                                    <path d="M20 6l-3 -3l-3 3" />
                                </svg>
                                {{-- up-down --}}
                                <svg wire:click="filterUp('expected_date')" xmlns="http://www.w3.org/2000/svg" width="24"
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
                        <th class="w-56 px-1 py-3">Creado</th>
                        <th class="w-16 px-1 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr class="trTable" id="report-{{ $report->id }}">
                            <td class="relative px-2 py-1">
                                <div wire:click="showReport({{ $report->id }})"
                                    class="flex cursor-pointer flex-row items-center text-center">
                                    <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="currentColor"
                                            class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($report->priority == 'Alto') text-red-500 @endif @if ($report->priority == 'Medio') text-yellow-400 @endif @if ($report->priority == 'Bajo') text-blue-500 @endif">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                        </svg>
                                    </div>
                                    <p class="my-auto text-left text-xs font-semibold">{{ $report->title }}</p>
                                    @if ($report->messages_count >= 1)
                                        {{-- usuario --}}
                                        @if ($report->user_chat != Auth::id() && $report->receiver_chat == Auth::id())
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
                                        @elseif(Auth::user()->type_user == 1 && $report->client == false && $report->user_id == false)
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
                                @if (Auth::user()->type_user == 3)
                                    <p
                                        class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif w-1/2 text-sm font-semibold">
                                        {{ $report->state }}
                                    </p>
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
                                @if ($report->count)
                                    <p class="text-left text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                                @endif
                            </td>
                            <td class="px-2 py-1">
                                @if ($report->updated_expected_date == false)
                                    <div class="my-auto text-left">
                                        En espera
                                    </div>
                                @else
                                    <div class="my-auto text-left">
                                        {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-2 py-1">
                                <div class="mx-auto text-left">
                                    @if ($report->user)
                                        <span class="font-semibold"> {{ $report->user->name }} </span> <br>
                                    @else
                                        <span class="font-semibold"> Usuario eliminado </span> <br>
                                    @endif
                                    <span class="font-mono">
                                        {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-2 py-1">
                                <div class="flex justify-center">
                                    <div id="dropdown-button-{{ $report->id }}"
                                        class="@if (Auth::user()->type_user == 3) @if ($report->state != 'Abierto' && $report->state != 'Resuelto') hidden @else relative @endif @else @endif relative">
                                        <!-- Button -->
                                        <button onclick="toggleDropdown('{{ $report->id }}')" type="button"
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
                                        <div id="dropdown-panel-{{ $report->id }}" style="display: none;"
                                            class="@if (Auth::user()->type_user == 1 || Auth::user()->id == $report->user->id) {{ $loop->last ? '-top-16' : 'top-3' }} @else {{ $loop->last ? '-top-8' : 'top-3' }} @endif absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                            <!-- Botón Editar -->
                                            <div wire:click="showEdit({{ $report->id }})"
                                                class="@if ($report->state == 'Resuelto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-edit mr-2" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                                Editar
                                            </div>
                                            @if (Auth::user()->type_user == 1 || Auth::user()->id == $report->user->id)
                                                <!-- Botón Eliminar -->
                                                <div wire:click="$emit('deleteReport',{{ $report->id }}, {{ $project->id }})"
                                                    class="@if ($report->state != 'Abierto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-trash mr-2" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
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
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
                                    </div>
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
    <div id="modalShow"
        class="@if ($modalShow) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="@if ($evidenceShow) md:w-4/5 @else md:w-3/4 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                style="max-height: 90%;">
                @if ($reportShow)
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            {{ $reportShow->title }}
                        </h3>
                        <svg wire:click="modalShow" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
                @if ($showReport)
                    <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
                        <div
                            class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-between border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                            <div class="text-justify text-base">
                                <h3 class="text-text2 text-lg font-bold">Descripción</h3>
                                {!! nl2br(e($reportShow->comment)) !!}<br><br>
                                @if ($showChat)
                                    <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                    <div id="messageContainer"
                                        class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                        @foreach ($messages as $index => $message)
                                            <div
                                                class="{{ $message->user_id == Auth::user()->id ? 'justify-end' : 'justify-start' }} flex">
                                                <div class="mx-2 items-center">
                                                    @if ($message->user_id == Auth::user()->id)
                                                        <div class="text-right">
                                                            <span class="text-sm font-semibold text-black">Tú</span>
                                                        </div>
                                                        <div class="bg-primaryColor rounded-xl p-2 text-right">
                                                            <span
                                                                class="text-blacktext-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                        </div>
                                                        <div class="text-right text-xs text-black">
                                                            <span
                                                                class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                                        </div>
                                                    @else
                                                        @if (Auth::user()->type_user == 3)
                                                            <div class="text-left">
                                                                <span
                                                                    class="text-sm font-semibold text-black">ARTEN</span>
                                                            </div>
                                                            <div class="rounded-xl bg-gray-200 p-2">
                                                                <span
                                                                    class="text-base font-extralight text-black text-gray-600">{{ $message->message }}</span>
                                                            </div>
                                                            <div class="text-left text-xs text-black">
                                                                <span
                                                                    class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                                            </div>
                                                        @else
                                                            <div class="text-left">
                                                                <span
                                                                    class="text-sm font-semibold text-black">{{ $message->transmitter->name }}</span>
                                                            </div>
                                                            <div class="rounded-xl bg-gray-200 p-2">
                                                                <span
                                                                    class="text-base font-extralight text-black text-gray-600">{{ $message->message }}</span>
                                                            </div>
                                                            <div class="text-left text-xs text-black">
                                                                <span
                                                                    class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
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
                                    class="inputs" style="border-radius: 0.5rem 0px 0px 0.5rem !important"
                                    @if (Auth::user()->type_user != 3) placeholder="Mensaje"
                                    @else
                                        placeholder="Mensaje para Arten" @endif>
                                <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                    wire:click="updateChat({{ $reportShow->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 14l11 -11" />
                                        <path
                                            d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                    </svg>
                                    Comentar
                                </button>
                            </div>
                        </div>
                        <div id="example" class="photos w-full px-5 lg:w-1/2">
                            <div  class="mb-6 w-auto">
                                <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                    <div class="text-text2 text-center text-xl font-semibold md:flex">Detalle</div>
                                    @if ($reportShow->evidence == true)
                                        <div class="btnIcon cursor-pointer font-semibold text-blue-500 hover:text-blue-400"
                                            onclick="toogleEvidence()" id="textToogle">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-eye" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path
                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg>
                                            &nbsp; Evidencia
                                        </div>
                                    @endif
                                </div>
                                @if (!empty($reportShow->content))
                                    @if ($reportShow->contentExists)
                                        @if ($reportShow->image == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <a href="{{ asset('reportes/' . $reportShow->content) }}" target="_blank">
                                                    <img src="{{ asset('reportes/' . $reportShow->content) }}" alt="Report Image">
                                                </a>
                                            </div>
                                        @endif
                                        @if ($reportShow->video == true)
                                            @if (strpos($reportShow->content, 'Reporte') === 0)
                                                <div class="my-5 w-full text-center text-lg">
                                                    <p class="text-red my-5">Subir video '{{ $reportShow->content }}'
                                                    </p>
                                                </div>
                                            @else
                                                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                    <a href="{{ asset('reportes/' . $reportShow->content) }}" target="_blank">
                                                        <video src="{{ asset('reportes/' . $reportShow->content) }}" loop autoplay alt="Report Video"></video>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                        @if ($reportShow->file == true)
                                            <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                                                @if ($reportShow->fileExtension === 'pdf')
                                                    <iframe src="{{ asset('reportes/' . $reportShow->content) }}"
                                                        width="auto" height="600"></iframe>
                                                @else
                                                    <p class="text-center text-base">Vista previa no disponible para
                                                        este tipo de archivo.</p>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-files-off">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                                <path
                                                    d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                                                <path
                                                    d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
                                                <path d="M3 3l18 18" />
                                            </svg>
                                            <p>Sin contenido</p>
                                        </div>
                                    @endif
                                    @if (
                                        $reportShow->image == true ||
                                            $reportShow->video == true ||
                                            ($reportShow->file == true && $reportShow->contentExists))
                                        <div class="flex items-center justify-center">
                                            <a href="{{ asset('reportes/' . $reportShow->content) }}"
                                                download="{{ basename($reportShow->content) }}" class="btnSecondary"
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
                                @else
                                    <div class="my-5 w-full text-center text-lg">
                                        <p class="text-red my-5">Sin archivo</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div id="evidence" class="hidden photos w-full px-5 lg:w-1/2">
                            @if ($evidenceShow)
                                <div class="flex flex-col">
                                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                        <div class="text-center text-xl font-semibold text-gray-700 md:flex">
                                            Evidencia
                                        </div>
                                        <div class="btnIcon cursor-pointer font-semibold text-red-500 hover:text-red-500"
                                            onclick="toogleEvidence()" id="textToogle">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-eye-x" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path
                                                    d="M13.048 17.942a9.298 9.298 0 0 1 -1.048 .058c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a17.986 17.986 0 0 1 -1.362 1.975" />
                                                <path d="M22 22l-5 -5" />
                                                <path d="M17 22l5 -5" />
                                            </svg> &nbsp; Evidencia
                                        </div>
                                    </div>
                                    @if (!empty($evidenceShow->content))
                                        @if ($evidenceShow->image == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <img src="{{ asset('evidence/' . $evidenceShow->content) }}"
                                                    alt="Report Image">
                                            </div>
                                        @endif
                                        @if ($evidenceShow->video == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <video src="{{ asset('evidence/' . $evidenceShow->content) }}" loop
                                                    autoplay alt="Report Video"></video>
                                            </div>
                                        @endif
                                        @if ($evidenceShow->file == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <iframe src="{{ asset('evidence/' . $evidenceShow->content) }}"
                                                    width="auto" height="800"></iframe>
                                            </div>
                                        @endif
                                    @else
                                        <div class="my-5 w-full text-center text-lg text-red-500">
                                            <p class="text-red my-5">Sin evidencia</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-end">
                                    <div class="btnIcon cursor-pointer font-semibold text-red-500 hover:text-red-500"
                                        onclick="toogleEvidence()" id="textToogle">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-eye-x" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M13.048 17.942a9.298 9.298 0 0 1 -1.048 .058c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a17.986 17.986 0 0 1 -1.362 1.975" />
                                            <path d="M22 22l-5 -5" />
                                            <path d="M17 22l5 -5" />
                                        </svg> &nbsp; Evidencia
                                    </div>
                                </div>
                                <div class="mt-10 animate-bounce text-center text-2xl font-bold text-red-500">
                                    Sin evidencia
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW --}}
    {{-- MODAL EDIT / CREATE REPORT --}}
    <div
        class="@if ($modalEdit) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
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
                    <svg wire:click="modalEdit" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    {{-- REPORT --}}
                    <div
                        class="md-3/4 @if (Auth::user()->type_user != 3) border-gray-400 lg:w-1/2 lg:border-r-2 @endif mb-5 flex w-full flex-col px-5 md:mb-0">
                        @if (Auth::user()->type_user != 3)
                            <div
                                class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                                <h4
                                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                    Reporte</h4>
                            </div>
                        @endif
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="tittle">
                                    Titulo<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='tittle' required type="text" placeholder="Título"
                                    name="tittle" id="tittle" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('tittle')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="file">
                                    Seleccionar archivo
                                </h5>
                                <input wire:model='file' required type="file" name="file" id="file"
                                    class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('file')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="tittle">
                                    Descripción<p class="text-red-600">*</p>
                                </h5>
                                <textarea wire:model='comment' type="text" rows="6"
                                    placeholder="Describa la nueva observación y especifique el objetivo a cumplir." name="comment" id="comment"
                                    class="textarea"></textarea>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('comment')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->type_user != 3)
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="m-auto mb-6 flex w-full flex-row px-3">
                                    <h5 class="mr-5 inline-flex font-semibold" for="evidenceEdit">
                                        Evidencia
                                    </h5>
                                    <div class="flex justify-center gap-20">
                                        <div class="flex flex-col items-center">
                                            <input type="checkbox" wire:model="evidenceEdit"
                                                class="priority-checkbox" style="height: 24px; width: 24px;" />
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('evidenceEdit')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('delegate')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="expected_date">
                                        Fecha de entrega<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='expected_date' required type="date" name="expected_date"
                                        id="expected_date" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('expected_date')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="-mx-3 mb-6">
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="tittle">
                                    Prioridad<p class="text-red-600">*</p>
                                </h5>
                                <div class="flex justify-center gap-20">
                                    <div class="flex flex-col items-center">
                                        <input type="checkbox" wire:model="priority1"
                                            wire:change="selectPriority($event.target.value)" value="Alto"
                                            class="priority-checkbox border-red-600 bg-red-600"
                                            style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                        <label for="priority1" class="mt-2">Alto</label>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <input type="checkbox" wire:model="priority2"
                                            wire:change="selectPriority($event.target.value)" value="Medio"
                                            class="priority-checkbox border-yellow-400 bg-yellow-400"
                                            style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                        <label for="priority2" class="mt-2">Medio</label>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <input type="checkbox" wire:model="priority3"
                                            wire:change="selectPriority($event.target.value)" value="Bajo"
                                            class="priority-checkbox border-secondary bg-secondary"
                                            style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                        <label for="priority3" class="mt-2">Bajo</label>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('priority')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->type_user != 3)
                        {{-- POINTS --}}
                        <div class="w-full px-5 lg:w-1/2">
                            <div
                                class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                                <h4
                                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                    Story Points</h4>
                            </div>
                            @if ($showEdit)
                                @if (Auth::user()->type_user == 1 || Auth::id() == $reportEdit->user->id)
                                    <div class="mb-6 flex flex-row">
                                        <span wire:click="changePoints"
                                            class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-exchange mr-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 10h14l-4 -4" />
                                                <path d="M17 14h-14l4 4" />
                                            </svg>
                                            @if ($changePoints)
                                                Cuestionario
                                            @else
                                                Agregar puntos directos
                                            @endif
                                        </span>
                                    </div>
                                @endif
                                <div class="@if ($changePoints) block @else hidden @endif -mx-3 mb-6">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="name">
                                            Puntos <p class="text-red-600">*</p>
                                        </h5>
                                        @if (Auth::user()->type_user == 1 || Auth::id() == $reportEdit->user->id)
                                            <input wire:model='points' required type="number"
                                                placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                                class="inputs">
                                        @else
                                            <input disabled wire:model='points' required type="number"
                                                placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                                class="inputs">
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="@if ($changePoints) hidden @else block @endif">
                                <div class="-mx-3 mb-6">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="name">
                                            ¿Cuánto se conoce de la tarea?<p class="text-red-600">*</p>
                                        </h5>
                                        <select wire:model='point_know' required name="point_know" id="point_know"
                                            class="inputs">
                                            <option selected>Selecciona...</option>
                                            <option value="1">Todo</option>
                                            <option value="2">Casi todo</option>
                                            <option value="3">Algunas cosas</option>
                                            <option value="5">Poco</option>
                                            <option value="8">Casi nada</option>
                                            <option value="13">Nada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="name">
                                            ¿De cuántos depende?<p class="text-red-600">*</p>
                                        </h5>
                                        <select wire:model='point_many' required name="point_many" id="point_many"
                                            class="inputs">
                                            <option selected>Selecciona...</option>
                                            <option value="1">Solo uno</option>
                                            <option value="2">Un par</option>
                                            <option value="3">Pocos</option>
                                            <option value="5">Varios</option>
                                            <option value="8">Muchos</option>
                                            <option value="13">No se sabe</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="-mx-3 mb-6">
                                    <div class="mb-6 flex w-full flex-col px-3">
                                        <h5 class="inline-flex font-semibold" for="name">
                                            ¿Cuánto esfuerzo representa?<p class="text-red-600">*</p>
                                        </h5>
                                        <select wire:model='point_effort' required name="point_effort"
                                            id="point_effort" class="inputs">
                                            <option selected>Selecciona...</option>
                                            <option value="1">Menos de 2 horas</option>
                                            <option value="2">Medio dìa</option>
                                            <option value="3">Hasta dos dìas</option>
                                            <option value="5">Pocos dìas</option>
                                            <option value="8">Alrededor de</option>
                                            <option value="13">Mas de una</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modalFooter">
                    @if ($modalEdit)
                        <button class="btnSave" wire:click="update({{ $reportEdit->id }}, {{ $project->id }})">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EDIT / CREATE REPORT --}}
    {{-- END MODAL DELETE --}}
    {{-- MODAL EVIDENCE --}}
    <div
        class="@if ($modalEvidence) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
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
                    <svg id="modalEvidence"
                        class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
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
                                <input wire:model='evidence' required type="file" name="evidence" id="evidence"
                                    class="inputs">
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
                    @if ($modalEvidence)
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EVIDENCE --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="create, filterDown, filterUp updateDelegate,, showReport, showEdit, reportRepeat, modalShow, updateChat, modalEdit, changePoints, update, updateEvidence">
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
            // Scroll de Comentrios de modal
            document.addEventListener("DOMContentLoaded", function() {
                var modal = document.getElementById("modalShow");

                if (modal) {
                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === "class") {
                                var classList = mutation.target.classList;
                                if (classList.contains("block") && !classList.contains("hidden")) {
                                    var messageContainer = document.getElementById("messageContainer");
                                    if (messageContainer) {
                                        messageContainer.scrollTop = messageContainer.scrollHeight;
                                    } else {
                                        console.error("Element with ID 'messageContainer' not found.");
                                    }
                                }
                            }
                        });
                    });

                    observer.observe(modal, {
                        attributes: true // Configura el observador para escuchar cambios en los atributos
                    });
                } else {
                    console.error("Modal element with ID 'modalShow' not found.");
                }
            });
            // DROPDOWN
            function toggleDropdown(reportId) {
                var panel = document.getElementById('dropdown-panel-' + reportId);
                if (panel.style.display === 'none') {
                    // Oculta todos los paneles de dropdown
                    var allPanels = document.querySelectorAll('[id^="dropdown-panel-"]');
                    allPanels.forEach(function(panel) {
                        panel.style.display = 'none';
                    });

                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            }

            window.addEventListener('file-reset', () => {
                document.getElementById('file').value = null;
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

            let banEvidence = false;

            function toogleEvidence() {
                let evidence = document.getElementById('evidence');
                let example = document.getElementById('example');
                evidence.classList.toggle("hidden");
                example.classList.toggle("hidden");
            }
        </script>
    @endpush
</div>
