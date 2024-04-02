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
                        <input wire:model="search" type="text" placeholder="Buscar" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
                <!-- DELEGATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedDelegate" class="inputs">
                        <option value="">Delegados</option>
                        @foreach ($allUsersFiltered as $key => $userFiltered)
                        <option value="{{ $key }}">{{ $userFiltered }}</option>
                        @endforeach
                    </select>
                </div>
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
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)" x-id="['dropdown-button']"
                            class="relative w-full">
                            <!-- Button -->
                            <button x-ref="button" x-on:click="toggle()" :aria-expanded="state"
                                :aria-controls="$id('dropdown-button')" type="button"
                                class="inputs flex h-12 items-center justify-between">
                                <span>Estados</span>
                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-chevron-down h-3 w-3" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 9l6 6l6 -6" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="state" x-on:click.outside="close($refs.button)"
                                :id="$id('dropdown-button')" style="display: none;"
                                class="absolute left-0 mt-2 w-full rounded-md bg-white z-10">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
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
                    <tr>
                        <th class="w-1/5 px-4 py-3">
                            <div @if ($priorityFiltered) wire:click="orderByLowPriority()" @else
                                wire:click="orderByHighPriority()" @endif class="justify- inline-flex cursor-pointer">
                                Reporte
                                @if ($priorityFiltered)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l9 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l7 0" />
                                    <path d="M15 15l3 3l3 -3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l7 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l9 0" />
                                    <path d="M15 9l3 -3l3 3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @endif
                            </div>
                        </th>

                        <th class="w-1/6 px-4 py-3">
                            <div @if ($progressFiltered) wire:click="orderByLowDates('progress')" @else
                                wire:click="orderByHighDates('progress')" @endif class="inline-flex justify-center">
                                Delegado
                                @if ($progressFiltered)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l9 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l7 0" />
                                    <path d="M15 15l3 3l3 -3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l7 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l9 0" />
                                    <path d="M15 9l3 -3l3 3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div class="inline-flex justify-center">
                                Estado
                            </div>
                        </th>
                        <th class="w-auto px-4 py-3">
                            <div @if ($expectedFiltered) wire:click="orderByLowDates('expected_date')" @else
                                wire:click="orderByHighDates('expected_date')" @endif
                                class="inline-flex justify-center">
                                Fecha
                                @if ($expectedFiltered)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l9 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l7 0" />
                                    <path d="M15 15l3 3l3 -3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l7 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l9 0" />
                                    <path d="M15 9l3 -3l3 3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @endif
                            </div>
                        </th>
                        <th class="w-auto px-4 py-3">
                            <div @if ($createdFiltered) wire:click="orderByLowDates('created_at')" @else
                                wire:click="orderByHighDates('created_at')" @endif class="inline-flex justify-center">
                                Creado
                                @if ($createdFiltered)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-descending ml-2" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l9 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l7 0" />
                                    <path d="M15 15l3 3l3 -3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-sort-ascending ml-2 cursor-pointer" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 6l7 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l9 0" />
                                    <path d="M15 9l3 -3l3 3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                @endif
                            </div>
                        </th>
                        <th class="w-auto px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                    <tr class="trTable">
                        <td class="px-4 py-2">
                            <div wire:click="showReport({{ $report->id }})"
                                class="flex cursor-pointer flex-col items-center text-center">
                                <p class="mb-2 text-justify text-xs">{{ $report->title }}</p>
                                @if (!empty($report->content))
                                <div class="h-22 relative w-full px-3 pt-2">
                                    @if ($report->image == true)
                                    <img src="{{ asset('reportes/' . $report->content) }}" alt="Report Image"
                                        class="mx-auto h-16 w-20 object-cover">
                                    @endif
                                    @if ($report->video == true)
                                    @if (strpos($report->content, 'Reporte') === 0)
                                    <p class="my-3 text-red-600">Falta subir '{{ $report->content }}'
                                    </p>
                                    @else
                                    <video src="{{ asset('reportes/' . $report->content) }}" alt="Report Video"
                                        class="mx-auto h-16 w-20 object-cover"></video>
                                    @endif
                                    @endif
                                    @if ($report->file == true)
                                    <img src="https://static.vecteezy.com/system/resources/previews/007/678/851/non_2x/documents-line-icon-vector.jpg"
                                        alt="Report Image" class="mx-auto h-20 w-20 object-cover">
                                    @endif
                                    @else
                                    <p class=""></p>
                                    @endif
                                    @if ($report->messages_count >= 1 && $report->user_chat != Auth::id())
                                    <div class="absolute right-0 top-0">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-circle-filled text-red-600" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z"
                                                stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                <div
                                    class="@if ($report->priority == 'Alto') bg-red-500 text-white @endif @if ($report->priority == 'Medio') bg-yellow-500 text-white @endif @if ($report->priority == 'Bajo') bg-blue-500 text-white @endif my-2 w-auto rounded-md px-3 text-center font-semibold">
                                    {{ $report->priority }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="mx-auto w-56 text-justify">
                                <p class="@if ($report->state == 'Resuelto') font-semibold @else hidden @endif">
                                    {{ $report->delegate->name }} {{ $report->delegate->lastname }}</p>
                                <select wire:change='updateDelegate({{ $report->id }}, $event.target.value)'
                                    name="delegate" id="delegate"
                                    class="inpSelectTable w-min-full @if ($report->state == 'Resuelto') hidden @endif w-full text-sm">
                                    <option selected value={{ $report->delegate->id }}>
                                        {{ $report->delegate->name }} </option>
                                    @foreach ($report->usersFiltered as $userFiltered)
                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="text-xs">
                                    @if ($report->state == 'Proceso' || $report->state == 'Conflicto')
                                    Progreso {{ $report->progress->diffForHumans(null, false, false, 1) }}
                                    @else
                                    @if ($report->state == 'Resuelto')
                                    @if ($report->progress == null)
                                    Sin tiempo de desarrollo
                                    @else
                                    Tiempo de desarrollo {{ $report->timeDifference }}
                                    @endif
                                    @else
                                    @if ($report->look == true)
                                    Visto {{ $report->progress->diffForHumans(null, false, false, 1) }}
                                    @endif
                                    @endif
                                    @endif
                                </p>
                            </div>

                        </td>
                        <td class="px-4 py-2">
                            <select
                                wire:change='updateState({{ $report->id }}, {{ $project->id }}, $event.target.value)'
                                name="state" id="state"
                                class="inpSelectTable inpSelectTable @if ($report->state == 'Abierto' && $report->state == 'Proceso') bg-white @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif w-28 text-sm font-semibold">
                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                @foreach ($report->filteredActions as $action)
                                <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                            @if ($report->count)
                            <p class="text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-justify">
                            <div class="mx-auto w-36">
                                <span class="inline-block font-semibold">Para:</span>
                                {{
                                \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY')
                                }}<br>
                                <span class="inline-block font-semibold">Se delegó:</span>
                                {{
                                \Carbon\Carbon::parse($report->delegated_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY')
                                }}
                            </div>

                        </td>
                        <td class="px-4 py-2">
                            <div class="mx-auto w-40 text-justify">
                                <span class="font-semibold"> {{ $report->user->name }}
                                    {{ $report->user->lastname }} </span> <br>
                                <span class="font-mono">
                                    {{
                                    \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY')
                                    }}
                                </span><br>
                                <span class="italic">
                                    {{ $report->created_at->diffForHumans(null, false, false, 1) }} </span>
                            </div>
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex justify-center">
                                <div x-data="{
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
                                    }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                                    x-id="['dropdown-button']" class="relative">
                                    <!-- Button -->
                                    <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                        :aria-controls="$id('dropdown-button')" type="button"
                                        class="flex items-center px-5 py-2.5">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        </svg>
                                    </button>
                                    <!-- Panel -->
                                    <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)"
                                        :id="$id('dropdown-button')" style="display: none;"
                                        class="absolute right-10 top-3 mt-2 w-32 rounded-md bg-gray-200">
                                        <!-- Botón Editar -->
                                        <div wire:click="showEdit({{ $report->id }})"
                                            class="@if ($report->state == 'Resuelto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-edit mr-2" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path
                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                            Editar
                                        </div>
                                        <!-- Botón Eliminar -->
                                        <div wire:click="$emit('deleteReport',{{ $report->id }}, {{ $project->id }})"
                                            class="@if ($report->state != 'Abierto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-trash mr-2" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                            Eliminar
                                        </div>
                                        <!-- Botón Reincidencia -->
                                        <div wire:click="reportRepeat({{ $project->id }}, {{ $report->id }})"
                                            class="@if ($report->repeat != false && $report->state == 'Resuelto') @else hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-bug-filled mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 4a4 4 0 0 1 3.995 3.8l.005 .2a1 1 0 0 1 .428 .096l3.033 -1.938a1 1 0 1 1 1.078 1.684l-3.015 1.931a7.17 7.17 0 0 1 .476 2.227h3a1 1 0 0 1 0 2h-3v1a6.01 6.01 0 0 1 -.195 1.525l2.708 1.616a1 1 0 1 1 -1.026 1.718l-2.514 -1.501a6.002 6.002 0 0 1 -3.973 2.56v-5.918a1 1 0 0 0 -2 0v5.917a6.002 6.002 0 0 1 -3.973 -2.56l-2.514 1.503a1 1 0 1 1 -1.026 -1.718l2.708 -1.616a6.01 6.01 0 0 1 -.195 -1.526v-1h-3a1 1 0 0 1 0 -2h3.001v-.055a7 7 0 0 1 .474 -2.173l-3.014 -1.93a1 1 0 1 1 1.078 -1.684l3.032 1.939l.024 -.012l.068 -.027l.019 -.005l.016 -.006l.032 -.008l.04 -.013l.034 -.007l.034 -.004l.045 -.008l.015 -.001l.015 -.002l.087 -.004a4 4 0 0 1 4 -4zm0 2a2 2 0 0 0 -2 2h4a2 2 0 0 0 -2 -2z"
                                                    stroke-width="0" fill="currentColor" />
                                            </svg>
                                            Reincidencia
                                        </div>
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
    <div class="@if ($modalShow) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="@if ($evidenceShow) md:w-4/5 @else md:w-3/4 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                style="max-height: 90%;">
                @if (!empty($reportShow->content))
                <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    <h3
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        @php echo mb_substr( $reportShow->title, 0, 25) . " ..."; @endphp
                    </h3>
                    <svg wire:click="modalShow" wire:target="modalShow"
                        class="h-6 w-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @else
                <div class="bg-main-fund flex flex-row justify-end rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
                    <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow"
                        class="h-6 w-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
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
                            <div
                                class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                @foreach ($messages as $message)
                                <div class="inline-flex">
                                    @if ($message->messages_count >= 1 && $reportShow->user_chat != Auth::id() &&
                                    $message->look == false)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-exclamation-mark text-red-600" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 19v.01" />
                                        <path d="M12 15v-10" />
                                    </svg>
                                    @endif
                                    <p class="pr-1 text-sm text-black"> <span class="font-semibold">
                                            {{ $message->transmitter->name }}: <span></span> <span
                                                class="text-sm font-extralight text-gray-600">{{ $message->message
                                                }}</span>
                                    </p>
                                </div>
                                <br>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="my-6 flex w-auto flex-row">
                            <input wire:model.defer='message' type="text" name="message" id="message"
                                placeholder="Mensaje a los administradores" class="inputs"
                                style="border-radius: 0.5rem 0px 0px 0.5rem !important">
                            <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                wire:click="updateChat({{ $reportShow->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                        @if (!empty($reportShow->content))
                        <div id="example" class="mb-6 w-auto">
                            <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                <div class="text-text2 text-center text-xl font-semibold md:flex">Detalle</div>
                                @if ($evidenceShow)
                                <div class="btnIcon cursor-pointer font-semibold text-blue-500 hover:text-blue-400"
                                    onclick="toogleEvidence()" id="textToogle">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
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

                            @if ($reportShow->image == true)
                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                <img src="{{ asset('reportes/' . $reportShow->content) }}" alt="Report Image">
                            </div>
                            @endif

                            @if ($reportShow->video == true)
                            @if (strpos($reportShow->content, 'Reporte') === 0)
                            <div class="my-5 w-full text-center text-lg">
                                <p class="text-red my-5">Falta subir '{{ $reportShow->content }}'</p>
                            </div>
                            @else
                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                <video src="{{ asset('reportes/' . $reportShow->content) }}" loop autoplay
                                    alt="Report Video"></video>
                            </div>
                            @endif
                            @endif
                            @if ($reportShow->file == true)
                            <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                                <iframe src="{{ asset('reportes/' . $reportShow->content) }}" width="auto"
                                    height="600"></iframe>
                            </div>
                            @endif
                            @else
                            <div class="my-5 w-full text-center text-lg">
                                <p class="text-red my-5">Sin archivo</p>
                            </div>
                            @endif
                            @if ($reportShow->image == true || $reportShow->video == true || $reportShow->file == true)
                            <div class="flex items-center justify-center">
                                <a href="{{ asset('reportes/' . $reportShow->content) }}"
                                    download="{{ basename($reportShow->content) }}" class="btnSecondary"
                                    style="color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-download" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
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

                        <div id="evidence" class="hidden">
                            @if ($evidenceShow)
                            <div class="flex flex-col">
                                <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                    <div class="text-center text-xl font-semibold text-gray-700 md:flex">
                                        Evidencia
                                    </div>
                                    <div class="btnIcon cursor-pointer font-semibold text-red-500 hover:text-red-500"
                                        onclick="toogleEvidence()" id="textToogle">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-eye-x" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
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
                                    <img src="{{ asset('evidence/' . $evidenceShow->content) }}" alt="Report Image">
                                </div>
                                @endif
                                @if ($evidenceShow->video == true)
                                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                    <video src="{{ asset('evidence/' . $evidenceShow->content) }}" loop autoplay
                                        alt="Report Video"></video>
                                </div>
                                @endif
                                @if ($evidenceShow->file == true)
                                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                    <iframe src="{{ asset('evidence/' . $evidenceShow->content) }}" width="auto"
                                        height="800"></iframe>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-x"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
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
                            <div class="mt-10 animate-bounce text-center text-2xl font-bold text-red-500">
                                Sin evidencia
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW --}}
    {{-- MODAL EDIT / CREATE ACTIVITY --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalEdit) block  @else hidden @endif">
        <div
            class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500">
        </div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col md:w-2/5 mx-auto rounded-lg   overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100 text-white rounded-tl-lg rounded-tr-lg">
                    <h3
                        class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">
                        Editar reporte</h3>
                    <svg wire:click="modalEdit()" wire:loading.remove wire:target="modalEdit"
                        class="w-6 h-6 my-2 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full @if (Auth::user()->type_user == 1) flex flex-col @else @endif px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="file">
                                    Seleccionar archivo
                                </h5>
                                <input wire:model='file' required type="file" name="file" id="file" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('file')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            @if (Auth::user()->type_user == 1)
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="expected_date">
                                    Fecha de entrega
                                </h5>
                                <input wire:model='expected_date' required type="date" name="expected_date"
                                    id="expected_date" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('expected_date')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="comment">
                                    Descripción
                                </h5>
                                <textarea wire:model='comment' type="text" rows="10"
                                    placeholder="Describa la nueva observación y especifique el objetivo a cumplir."
                                    name="comment" id="comment" class="inputs"></textarea>
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('comment')
                                        <span class="pl-2 text-red-600 text-xs italic">
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
                    @if ($modalEdit)
                    <button class="btnSave" wire:click="update({{ $reportEdit->id }}, {{ $project->id }})"
                        wire:loading.remove wire:target="update({{ $reportEdit->id }}, {{ $project->id }})"> Guardar
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EDIT / CREATE ACTIVITY --}}
    {{-- END MODAL DELETE --}}
    {{-- MODAL EVIDENCE --}}
    <div class="@if ($modalEvidence) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                <div
                    class="bg-main-fund flex flex-row justify-between rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
                    <h2
                        class="text-secondary title-font border-secondary-fund w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        Evidencia</h2>
                    <svg id="modalEvidence" data-id="@if ($modalEvidence) {{ $reportEvidence->id }} @endif"
                        data-project_id="@if ($modalEvidence) {{ $reportEvidence->project_id }} @endif"
                        data-state="@if ($modalEvidence) {{ $reportEvidence->state }} @endif"
                        class="h-6 w-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="bg-main-fund px-6 py-2 text-sm">
                    <div class="mb-6 md:flex">
                        <div class="flex w-full flex-col px-3">
                            <h5 class="mb-3 inline-flex font-semibold" for="name">
                                Para completar tu reporte, por favor, sube el archivo de evidencia.
                            </h5>
                            <input wire:model='evidence' type="file" name="evidence" id="evidence"
                                class="mx-auto block w-full appearance-none rounded border border-gray-400 bg-white px-4 py-1 leading-snug text-gray-700">
                        </div>
                    </div>
                </div>
                <div class="bg-main-fund flex items-center justify-center py-6">
                    @if ($modalEvidence)
                    <button
                        class="border-secundaryColor hover:bg-secondary cursor-pointer rounded px-4 py-2 font-semibold text-white"
                        wire:click="updateEvidence({{ $reportEvidence->id }}, {{ $reportEvidence->project_id }})">
                        Guardar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EVIDENCE --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading>
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
        window.addEventListener('swal:modal', event => {
        toastr[event.detail.type](event.detail.text, event.detail.title);
    });

    // MODALS
    Livewire.on('deleteReport', (id, project_id) => {
        Swal.fire({
            title: '¿Seguro que deseas eliminar este elemento?',
            text: "Esta acción es irreversible",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#202a33',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Eliminar',
            calcelButtonText: 'Cancelar'
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
        let id = modalEvidence.getAttribute('data-id');
        let project_id = modalEvidence.getAttribute('data-project_id');
        let state = modalEvidence.getAttribute('data-state');
        console.log(modalEvidence, id, project_id, state);

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