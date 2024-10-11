<div>
    {{-- SPRINTS --}}
    <div class="px-4 py-4 sm:rounded-lg">
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            @if ($sprints->isEmpty())
                @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                    <!-- BTN NEW -->
                    <div class="flex w-40 flex-wrap md:inline-flex md:flex-nowrap">
                        <button wire:click="modalCreateSprint()" class="btnNuevo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Sprint
                        </button>
                    </div>
                @endif
            @else
                <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
                    {{-- NOMBRE --}}
                    <div class="mb-2 inline-flex h-12 w-2/6 bg-transparent px-2 md:mx-3 md:px-0">
                        @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                            <span class="m-auto mr-5">
                                <span class="inline-block font-semibold">Avance</span>
                                {{ $percentageResolved }}%
                            </span>
                        @endif
                        <select wire:model="selectSprint" wire:change="selectSprint($event.target.value)"
                            class="inputs">
                            @foreach ($sprints as $sprint)
                                <option value="{{ $sprint->id }}">{{ $sprint->number }} - {{ $sprint->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                        {{-- ESTADO --}}
                        <div class="mb-2 inline-flex h-12 w-36 bg-transparent px-2 md:mx-3 md:px-0">
                            <select wire:change='updateStateSprint({{ $idSprint }}, $event.target.value)'
                                name="state" id="state" class="inputs">
                                <option selected value={{ $firstSprint->state }}>{{ $firstSprint->state }}</option>
                                @foreach ($filteredState as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    {{-- FECHAS --}}
                    <div class="mx-2 w-auto">
                        <span class="inline-block font-semibold">Fecha de inicio:</span>
                        {{ \Carbon\Carbon::parse($startDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}<br>
                        <span class="inline-block font-semibold">Fecha de cierre:</span>
                        {{ \Carbon\Carbon::parse($endDate)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                    </div>
                    @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
                        <div class="hidden h-12 w-auto bg-transparent md:inline-flex">
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
                                            class="icon icon-tabler icon-tabler-dots-vertical" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        </svg>
                                    </button>
                                    <!-- Panel -->
                                    <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)"
                                        :id="$id('dropdown-button')" style="display: none;"
                                        class="absolute right-10 top-3 z-10 mt-2 w-32 rounded-md bg-gray-200">
                                        <!-- Botón Nuevo -->
                                        <div wire:click="modalCreateSprint()"
                                            class="flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Nuevo
                                        </div>
                                        <!-- Botón Editar -->
                                        <div wire:click="showEditSprint({{ $idSprint }})"
                                            class="@if ($firstSprint->state == 'Cerrado') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-edit mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path
                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                            Editar
                                        </div>
                                        <!-- Botón Eliminar -->
                                        <div wire:click="$emit('deleteSprint',{{ $idSprint }})"
                                            class="@if ($firstSprint->state == 'Cerrado' || $firstSprint->state == 'Curso') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-red-600">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mb-2 h-12 w-1/6 bg-transparent md:inline-flex">
                <button wire:click="modalBacklog()" class="btnNuevo">
                    Backlog
                </button>
            </div>
        </div>
    </div>
    {{-- Tabla actividades --}}
    <div class="@if ($sprints->isEmpty()) hidden @endif px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <!-- SEARCH -->
            <div class="flex w-full flex-wrap md:inline-flex md:w-4/5 md:flex-nowrap">
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-2/5 md:px-0">
                    <div class="relative flex h-full w-full">
                        <div class="absolute z-10 mt-2 flex">
                            <span
                                class="whitespace-no-wrap flex items-center rounded-lg border-0 border-none bg-transparent p-2 leading-normal lg:px-3">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedDelegate" class="inputs">
                        <option value="">Delegados</option>
                        @foreach ($allUsersFiltered as $userFiltered)
                            <option value="{{ $userFiltered['id'] }}">{{ $userFiltered['name'] }}</option>
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
                        </div>
                    </div>
                </div>
                <!-- BTN NEW -->
                @if ($sprints->isEmpty())
                @else
                    @if ($firstSprint->state != 'Cerrado')
                        <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:hidden md:px-0">
                            <button wire:click="modalCreateActivity()"
                                @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled class="btnDisabled"@else
                        class="btnNuevo" @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 5l0 14" />
                                    <path d="M5 12l14 0" />
                                </svg>
                                Actividad
                            </button>
                        </div>
                    @endif
                @endif
            </div>
            <!-- BTN NEW -->
            @if ($sprints->isEmpty())
            @else
                @if ($firstSprint->state != 'Cerrado')
                    <div class="mb-2 h-12 w-1/6 bg-transparent md:inline-flex">
                        <button wire:click="modalCreateActivity()"
                            @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled class="btnDisabled"@else
                    class="btnNuevo" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Actividad
                        </button>
                    </div>
                @endif
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
                                Actividad
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
                        <th class="px-1 py-3 lg:w-48">
                            <div class="flex">
                                Delegado
                                {{-- down-up --}}
                                <svg wire:click="filterDown('delegate')" xmlns="http://www.w3.org/2000/svg" width="24"
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
                                <svg wire:click="filterUp('delegate')" xmlns="http://www.w3.org/2000/svg" width="24"
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
                        <th class="w-48 px-2 py-3">
                            <div class="flex">
                                Estado
                                {{-- down-up --}}
                                <svg wire:click="filterDown('state')" xmlns="http://www.w3.org/2000/svg" width="24"
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
                                        @if ($activity->user_chat != Auth::id() && $activity->receiver_chat == Auth::id())
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
                                        @elseif(Auth::user()->type_user == 1 && $activity->client == false && $activity->user_id == false)
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
                                        <p class="@if ($activity->state == 'Resuelto') font-semibold @else hidden @endif">
                                            {{ $activity->delegate->name }}</p>
                                        <select
                                            wire:change.defer='updateDelegate({{ $activity->id }}, $event.target.value)'
                                            name="delegate" id="delegate"
                                            class="inpSelectTable @if ($activity->state == 'Resuelto') hidden @endif w-full text-sm"
                                            @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
                                            <option selected value={{ $activity->delegate->id }}>
                                                {{ $activity->delegate->name }}
                                            </option>
                                            @foreach ($activity->usersFiltered as $userFiltered)
                                                <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p class="@if ($activity->state == 'Resuelto') font-semibold @else hidden @endif">
                                            Usuario eliminado</p>
                                        <select
                                            wire:change.defer='updateDelegate({{ $activity->id }}, $event.target.value)'
                                            name="delegate" id="delegate"
                                            class="inpSelectTable @if ($activity->state == 'Resuelto') hidden @endif w-full text-sm"
                                            @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
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
                                    @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1)  disabled @endif>
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
                                <div class="@if ($activity->state == 'Resuelto') hidden @endif flex justify-center">
                                    <div id="dropdown-button-{{ $activity->id }}" class="relative">
                                        <button onclick="toggleDropdown('{{ $activity->id }}')" type="button"
                                            class="flex items-center px-5 py-2.5"
                                            @if ($firstSprint->state == 'Pendiente' && Auth::user()->type_user != 1 && Auth::user()->area_id != 1) disabled @endif>
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
                                        <div id="dropdown-panel-{{ $activity->id }}" style="display: none;"
                                            class="@if (Auth::user()->type_user == 1 || Auth::user()->id == $activity->user->id) {{ $loop->last ? '-top-16' : 'top-3' }} @else {{ $loop->last ? '-top-8' : 'top-3' }} @endif absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                            <!-- Botón Editar -->
                                            <div wire:click="showEditActivity({{ $activity->id }})"
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
                                                        class="icon icon-tabler icon-tabler-trash mr-2" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 7l16 0"></path>
                                                        <path d="M10 11l0 6"></path>
                                                        <path d="M14 11l0 6"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                    </svg>
                                                    Eliminar
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
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW BACKLOG --}}
    <div
        class="@if ($modalBacklog) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                <div
                    class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    <h3
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        Backlog</h3>
                    <svg wire:click="modalBacklog()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    <div class="md-3/4 mb-5 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="text-text2 text- font-bold">
                                    Fecha de inicio
                                </h5>
                                <p>{{ $backlog->start_date }}</p>
                            </div>
                            <div class="flex w-full flex-col px-3">
                                <h5 class="text-text2 text-lg font-bold">
                                    Fecha de cierre
                                </h5>
                                <p>{{ $backlog->closing_date }}</p>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="text-text2 text-lg font-bold">
                                    Objetivo generales
                                </h5>
                                <p>{{ $backlog->general_objective }}</p>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="text-text2 text-lg font-bold">
                                    Claves de acceso
                                </h5>
                                <textarea required disabled type="text" rows="10" name="scopes" id="scopes" class="textarea">{{ $backlog->passwords }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-5 lg:w-1/2">
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="text-text2 text-lg font-bold">
                                    Alcances
                                </h5>
                                @if ($backlog->scopes)
                                    <textarea required disabled type="text" rows="10" name="scopes" id="scopes" class="textarea">{{ $backlog->scopes }}</textarea>
                                @endif
                                @if ($backlog->files)
                                    @foreach ($backlog->files as $file)
                                        <img src="{{ asset('backlogs/' . $file->route) }}" alt="Backlog Image"
                                            class="mb-3">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW BACKLOG --}}
    {{-- MODAL EDIT / CREATE SPRINT --}}
    <div
        class="@if ($modalCreateSprint) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                <div
                    class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    @if ($showUpdateSprint)
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Editar sprint {{ $sprintEdit->number }}</h3>
                    @else
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Crear sprint</h3>
                    @endif
                    <svg wire:click="modalCreateSprint()"
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
                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-col">
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name_sprint">
                                    Nombre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='name_sprint' required type="text" placeholder="Nombre"
                                    name="name_sprint" id="name_sprint" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('name_sprint')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="start_date">
                                    Fecha de inicio<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='start_date' required type="date" name="start_date"
                                    id="start_date" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('start_date')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="end_date">
                                    Fecha de cierre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='end_date' required type="date" name="end_date" id="end_date"
                                    class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('end_date')
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
                    @if ($showUpdateSprint)
                        <button class="btnSave" wire:click="updateSprint({{ $sprintEdit->id }})"> Guardar </button>
                    @else
                        <button class="btnSave" wire:click="createSprint()">
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
    {{-- END MODAL EDIT / CREATE SPRINT --}}
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
                                {!! nl2br( $activityShow->description ) !!}<br><br>
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
                                    placeholder="Mensaje" class="inputs"
                                    style="border-radius: 0.5rem 0px 0px 0.5rem !important">
                                <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                    wire:click="updateChat({{ $activityShow->id }})">
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
                                            <a href="{{ asset('activities/' . $activityShow->image) }}" target="_blank">
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
    <div
        class="@if ($modalCreateActivity) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                <div
                    class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    @if ($showUpdateActivity)
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Editar actividad</h3>
                    @else
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Crear actividad</h3>
                    @endif
                    <svg wire:click="modalCreateActivity"
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
                    {{-- ACTIVITY --}}
                    <div class="md-3/4 mb-5 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                        <div
                            class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                            <h4
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                Actividad</h4>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="title">
                                    Titulo<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='title' required type="text" placeholder="Título"
                                    name="title" id="title" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('title')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="file">
                                    Imagen
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
                                <h5 class="inline-flex font-semibold" for="description">
                                    Descripción
                                </h5>
                                <textarea wire:model='description' type="text" rows="6"
                                    placeholder="Describa la observación y especifique el objetivo a cumplir." name="description" id="description"
                                    class="textarea"></textarea>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('description')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            @if ($showUpdateActivity)
                                <div
                                    class="@if ($showUpdateActivity) @else hidden @endif mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="delegate">
                                        Mover a sprint
                                    </h5>
                                    <select wire:model="moveActivity" class="inputs">
                                        @foreach ($sprints as $sprint)
                                            <option value="{{ $sprint->id }}">{{ $sprint->number }} -
                                                {{ $sprint->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('moveActivity')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="@if ($showUpdateActivity) hidden @else @endif mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="delegate">
                                        Delegar<p class="text-red-600">*</p>
                                    </h5>
                                    <select wire:model='delegate' required name="delegate" id="delegate"
                                        class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
                            @endif
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
                        <div class="-mx-3 mb-6">
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="priority">
                                    Prioridad
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
                    {{-- POINTS --}}
                    <div class="w-full px-5 lg:w-1/2">
                        <div
                            class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                            <h4
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                Story Points</h4>
                        </div>
                        @if ($showUpdateActivity)
                            @if (Auth::user()->type_user == 1 || Auth::id() == $activityEdit->user->id)
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
                        @else
                            <div class="mb-6 flex flex-row">
                                <span wire:click="changePoints"
                                    class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
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
                                @if ($showUpdateActivity)
                                    @if (Auth::user()->type_user == 1 || Auth::id() == $activityEdit->user->id)
                                        <input wire:model='points' required type="number"
                                            placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                            class="inputs">
                                    @else
                                        <input disabled wire:model='points' required type="number"
                                            placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                            class="inputs">
                                    @endif
                                @else
                                    <input wire:model='points' required type="number"
                                        placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                        class="inputs">
                                @endif
                            </div>
                        </div>
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
                                    <select wire:model='point_effort' required name="point_effort" id="point_effort"
                                        class="inputs">
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
                </div>
                <div class="modalFooter">
                    @if ($showUpdateActivity)
                        <button class="btnSave" wire:click="updateActivity({{ $activityEdit->id }})"> Guardar
                        </button>
                    @else
                        <button class="btnSave" wire:click="createActivity()">
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
                    // Obtiene el ID del reporte a resaltar
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
            function toggleDropdown(activityId) {
                var panel = document.getElementById('dropdown-panel-' + activityId);
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
                        window.livewire.emit('destroyActivity', deletebyId);
                        // Swal.fire(
                        //   '¡Eliminado!',
                        //   'Tu elemento ha sido eliminado.',
                        //   'Exito'
                        // )
                    }
                })
            });

            Livewire.on('deleteSprint', deletebyId => {
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
                        window.livewire.emit('destroySprint', deletebyId);
                        Swal.fire(
                            '¡Eliminado!',
                            'Sprint eliminado',
                            'Exito'
                        )
                    }
                })
            });
        </script>
    @endpush
</div>
