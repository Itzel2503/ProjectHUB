<div>
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- PESTAÑAS --}}
        <nav class="-mb-px flex">
            @if (Auth::user()->type_user == 1)
                <button wire:click="setActiveTab('task')"
                    class="border-primaryColor @if ($activeTab === 'task') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                    Mis tareas
                </button>
                <button wire:click="setActiveTab('created')"
                    class="border-primaryColor @if ($activeTab === 'created') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                    Creadas
                </button>
            @endif
            <button wire:click="setActiveTab('actividades')"
                class="border-primaryColor @if ($activeTab === 'actividades') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                Actividades
                {{-- @if ($totalMessagesCount > 0)
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-message text-red-600">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 9h8" />
                    <path d="M8 13h6" />
                    <path
                        d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                </svg>
                @endif --}}
            </button>
            <button wire:click="setActiveTab('reportes')"
                class="border-primaryColor @if ($activeTab === 'reportes') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                Reportes
            </button>
            @if (Auth::user()->area_id == 4)
                <button wire:click="setActiveTab('dukke')"
                    class="border-primaryColor @if ($activeTab === 'dukke') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                    Dukke
                </button>
            @endif
            {{-- NAVEGADOR --}}
            <div
                class="border-primaryColor ml-auto flex w-full flex-col gap-2 border-b-2 text-sm md:flex-row lg:text-base">
                <!-- SEARCH -->
                <div class="flex w-full flex-wrap justify-end md:inline-flex md:flex-nowrap">
                    @if (Auth::user()->type_user == 1)
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
                    <div class="mb-2 inline-flex h-12 bg-transparent px-2 md:px-0">
                        <div class="relative flex h-full w-full">
                            <div class="absolute z-10 mt-2 flex">
                                <span
                                    class="whitespace-no-wrap flex items-center rounded-lg border-0 border-none bg-transparent p-2 leading-normal lg:px-3">
                                    <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z"
                                            stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            @if ($activeTab === 'actividades')
                                <input wire:model="searchActivity" type="text" placeholder="Buscar actividad"
                                    class="inputs" style="padding-left: 3em;">
                            @elseif ($activeTab === 'reportes')
                                <input wire:model="searchReport" type="text" placeholder="Buscar reporte"
                                    class="inputs" style="padding-left: 3em;">
                            @elseif ($activeTab === 'dukke')
                                <input wire:model="searchDukke" type="text" placeholder="Buscar reporte"
                                    class="inputs" style="padding-left: 3em;">
                            @elseif ($activeTab === 'task')
                                @if (Auth::user()->type_user == 1)
                                    <input wire:model="searchTask" type="text" placeholder="Buscar tarea"
                                        class="inputs" style="padding-left: 3em;">
                                @endif
                            @elseif ($activeTab === 'created')
                                @if (Auth::user()->type_user == 1)
                                    <input wire:model="searchCreated" type="text" placeholder="Buscar tarea"
                                        class="inputs" style="padding-left: 3em;">
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        {{-- END PESTAÑAS --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            @if ($activeTab === 'actividades')
                <table class="whitespace-no-wrap table-hover table w-full">
                    <thead class="headTable border-0">
                        <tr class="text-left">
                            <th class="w-96 px-4 py-3">
                                <div class="flex">
                                    Actividad
                                    {{-- down-up --}}
                                    <svg wire:click='filterDown("activity")' xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredActivity) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click='filterUp("activity")' xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredActivity) hidden @else block @endif ml-2 cursor-pointer">
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
                                    <svg wire:click="filterDown('expected_dateActivity')"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredActivity) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click="filterUp('expected_dateActivity')"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredActivity) hidden @else block @endif ml-2 cursor-pointer">
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
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    @if ($activeTab === 'actividades')
                                        <div wire:click="showActivity({{ $activity->id }})"
                                            class="flex cursor-pointer flex-col justify-center text-center">
                                            <div class="flex flex-row">
                                                <div class="w-12"></div>
                                                @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                                    <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                        {{ $activity->sprint->backlog->project->name }} - Sprint
                                                        '{{ $activity->sprint_status }}'
                                                    </p>
                                                @else
                                                    <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                        Proyecto no disponible
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-row">
                                                @if ($activity->priority)
                                                    <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="currentColor"
                                                            class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($activity->priority == 'Alto') text-red-500 @endif @if ($activity->priority == 'Medio') text-yellow-400 @endif @if ($activity->priority == 'Bajo') text-blue-500 @endif">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="w-12"></div>
                                                @endif
                                                <p class="my-auto text-left text-xs font-semibold">
                                                    {{ $activity->title }}
                                                </p>
                                            </div>
                                            @if ($activity->messages_count >= 1)
                                                {{-- usuario --}}
                                                @if ($activity->user_chat != Auth::id() && $activity->receiver_chat == Auth::id())
                                                    <div class="absolute right-0 top-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-message text-red-600">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M8 9h8" />
                                                            <path d="M8 13h6" />
                                                            <path
                                                                d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                                                        </svg>
                                                    </div>
                                                @elseif(Auth::user()->type_user == 1 && $activity->client == false && $activity->user_id == false)
                                                    <div class="absolute right-0 top-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
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
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto w-auto text-left">
                                        @if ($activeTab === 'actividades')
                                            @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                                @if ($activity->delegate)
                                                    <select
                                                        wire:change.defer='updateDelegateActivity({{ $activity->id }}, $event.target.value)'
                                                        name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm"
                                                        @if (Auth::user()->type_user != 1 && Auth::user()->area_id != 1) @if ($activity->sprint_status == 'Pendiente' || $activity->sprint_status == 'Cerrado')
                                            disabled @endif
                                                        @endif>
                                                        <option selected value={{ $activity->delegate->id }}>
                                                            {{ $activity->delegate->name }}
                                                        </option>
                                                        @foreach ($activity->usersFiltered as $userFiltered)
                                                            <option value="{{ $userFiltered->id }}">
                                                                {{ $userFiltered->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select
                                                        wire:change.defer='updateDelegateActivity({{ $activity->id }}, $event.target.value)'
                                                        name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm"
                                                        @if (Auth::user()->type_user != 1 && Auth::user()->area_id != 1) @if ($activity->sprint_status == 'Pendiente' || $activity->sprint_status == 'Cerrado')
                                    disabled @endif
                                                        @endif>
                                                        <option selected>
                                                            Seleccionar...
                                                        </option>
                                                        @foreach ($activity->usersFiltered as $userFiltered)
                                                            <option value="{{ $userFiltered->id }}">
                                                                {{ $userFiltered->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @else
                                                @if ($activity->delegate)
                                                    <select name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm">
                                                        <option selected value={{ $activity->delegate->id }}>
                                                            {{ $activity->delegate->name }}
                                                        </option>
                                                    </select>
                                                @else
                                                    <select name="delegate" id="delegate"
                                                        class="inpSelectTable w-full text-sm">
                                                        <option selected>
                                                            Usuario eliminado
                                                        </option>
                                                    </select>
                                                @endif
                                            @endif
                                            <p class="text-xs">
                                                @if ($activity->state == 'Proceso' || $activity->state == 'Conflicto')
                                                    Progreso
                                                    {{ $activity->progress->diffForHumans(null, false, false, 1) }}
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
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                        <select
                                            wire:change='updateStateActivity({{ $activity->id }}, $event.target.value)'
                                            name="state" id="state"
                                            class="inpSelectTable @if ($activity->state == 'Abierto') bg-blue-500 text-white @endif @if ($activity->state == 'Proceso') bg-yellow-400 @endif @if ($activity->state == 'Resuelto') bg-lime-700 text-white @endif @if ($activity->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                            @if (Auth::user()->type_user != 1 && Auth::user()->area_id != 1) @if ($activity->sprint_status == 'Pendiente' || $activity->sprint_status == 'Cerrado')
                                disabled @endif
                                            @endif>
                                            <option selected value={{ $activity->state }}>{{ $activity->state }}
                                            </option>
                                            @foreach ($activity->filteredActions as $action)
                                                <option value="{{ $action }}">{{ $action }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="state" id="state"
                                            class="inpSelectTable @if ($activity->state == 'Abierto') bg-blue-500 text-white @endif @if ($activity->state == 'Proceso') bg-yellow-400 @endif @if ($activity->state == 'Resuelto') bg-lime-700 text-white @endif @if ($activity->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                            <option selected value={{ $activity->state }}>{{ $activity->state }}
                                            </option>
                                        </select>
                                    @endif
                                </td>
                                <td class="px-2 py-1 text-left">
                                    <div class="mx-auto">
                                        {{ \Carbon\Carbon::parse($activity->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto text-left">
                                        @if ($activity->user)
                                            <span class="font-semibold"> {{ $activity->user->name }}</span>
                                        @else
                                            <span class="font-semibold">Usuario eliminado</span>
                                        @endif
                                        <br>
                                        <span class="font-mono">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-15 px-2">
                                    <div class="flex justify-center">
                                        @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                            <a
                                                href="{{ route('projects.activities.index', ['project' => $activity->sprint->backlog->project->id, 'activity' => $activity->id, 'highlight' => $activity->id]) }}">
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
            @elseif ($activeTab === 'created')
                <table class="whitespace-no-wrap table-hover table w-full">
                    <thead class="headTable border-0">
                        <tr class="text-left">
                            <th class="w-96 px-4 py-3">Tareas</th>
                            <th class="px-1 py-3 lg:w-48">Delegado</th>
                            <th class="w-48 px-2 py-3">Estado</th>
                            <th class="w-44 px-1 py-3">
                                <div class="flex items-center">
                                    Fecha de entrega
                                    {{-- down-up --}}
                                    <svg wire:click="filterDown('expected_dateCreated')"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredCreated) block @else hidden @endif ml-2 cursor-pointer">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M17 3l0 18" />
                                        <path d="M10 18l-3 3l-3 -3" />
                                        <path d="M7 21l0 -18" />
                                        <path d="M20 6l-3 -3l-3 3" />
                                    </svg>
                                    {{-- up-down --}}
                                    <svg wire:click="filterUp('expected_dateCreated')"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredCreated) hidden @else block @endif ml-2 cursor-pointer">
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
                        @foreach ($taskCreated as $task)
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    @if ($activeTab === 'created')
                                        <div @if ($task->project_activity) wire:click='showActivity({{ $task->id }})'
                                @elseif($task->project_report) wire:click='showReport({{ $task->id }})' @endif
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
                                                    {{ $task->title }}
                                                </p>
                                            </div>
                                            @if ($task->messages_count >= 1)
                                                {{-- usuario --}}
                                                @if ($task->user_chat != Auth::id() && $task->receiver_chat == Auth::id())
                                                    <div class="absolute right-0 top-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-message text-red-600">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M8 9h8" />
                                                            <path d="M8 13h6" />
                                                            <path
                                                                d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                                                        </svg>
                                                    </div>
                                                @elseif(Auth::user()->type_user == 1 && $task->client == false && $task->user_id == false)
                                                    <div class="absolute right-0 top-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
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
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto w-full text-left">
                                        @if ($activeTab === 'created')
                                            @if ($task->project_activity || $task->project_report)
                                                <select
                                                    @if ($task->project_activity) wire:change.defer='updateDelegateActivity({{ $task->id }}, $event.target.value)'
                                    @elseif($task->project_report)
                                    wire:change='updateDelegateReport({{ $task->id }}, $event.target.value)' @endif
                                                    name="delegate" id="delegate"
                                                    @if ($task->project_activity) @if (Auth::user()->type_user != 1 && Auth::user()->area_id != 1)
                                    @if ($task->sprint_status == 'Pendiente' || $task->sprint_status == 'Cerrado')
                                    disabled @endif
                                                    @endif
                                            @endif
                                            class="inpSelectTable w-full w-full text-sm text-sm">
                                            @if ($task->delegate_id)
                                                <option selected value={{ $task->delegate_id }}>
                                                    {{ $task->delegate_name }}
                                                </option>
                                            @else
                                                <option selected>
                                                    Seleccionar...
                                                </option>
                                            @endif
                                            @if ($task->state != 'Resuelto')
                                                @foreach ($task->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            @endif
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
                        @endif
        </div>
        </td>
        <td class="px-2 py-1">
            @if ($task->project_activity || $task->project_report)
                <select name="state" id="state"
                    class="inpSelectTable @if ($task->state == 'Abierto') bg-blue-500 text-white @endif @if ($task->state == 'Proceso') bg-yellow-400 @endif @if ($task->state == 'Resuelto') bg-lime-700 text-white @endif @if ($task->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                    @if ($task->project_activity) wire:change.defer='updateStateActivity({{ $task->id }},
                                $event.target.value)'
                                @elseif($task->project_report) wire:change='updateStateReport({{ $task->id }}, {{ $task->project_id }}, $event.target.value)' @endif>
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
        <td class="px-2 py-1 text-left">
            <div class="mx-auto">
                {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
            </div>
        </td>
        <td class="px-2 py-1">
            <div class="mx-auto text-left">
                <span class="font-semibold"> {{ $task->created_name }}</span>
                <br>
                <span class="font-mono">
                    {{ \Carbon\Carbon::parse($task->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                </span>
            </div>
        </td>
        <td class="px-2 py-1">
            <div class="flex justify-center">
                @if ($task->project_activity)
                    <a
                        href="{{ route('projects.activities.index', ['project' => $task->project_id, 'activity' => $task->id, 'highlight' => $task->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path
                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                        </svg>
                    </a>
                @elseif($task->project_report)
                    <a
                        href="{{ route('projects.reports.index', ['project' => $task->project_id, 'reports' => $task->id, 'highlight' => $task->id]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
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
    @elseif ($activeTab === 'reportes')
        <table class="whitespace-no-wrap table-hover table w-full">
            <thead class="headTable border-0">
                <tr class="text-left">
                    <th class="w-96 px-4 py-3">
                        <div class="flex">
                            Reporte
                            {{-- down-up --}}
                            <svg wire:click='filterDown("report")' xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredReport) block @else hidden @endif ml-2 cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 3l0 18" />
                                <path d="M10 18l-3 3l-3 -3" />
                                <path d="M7 21l0 -18" />
                                <path d="M20 6l-3 -3l-3 3" />
                            </svg>
                            {{-- up-down --}}
                            <svg wire:click='filterUp("report")' xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredReport) hidden @else block @endif ml-2 cursor-pointer">
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
                            <svg wire:click="filterDown('expected_dateReport')" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredReport) block @else hidden @endif ml-2 cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 3l0 18" />
                                <path d="M10 18l-3 3l-3 -3" />
                                <path d="M7 21l0 -18" />
                                <path d="M20 6l-3 -3l-3 3" />
                            </svg>
                            {{-- up-down --}}
                            <svg wire:click="filterUp('expected_dateReport')" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredReport) hidden @else block @endif ml-2 cursor-pointer">
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
                    <tr class="trTable">
                        <td class="relative px-2 py-1">
                            @if ($activeTab === 'reportes')
                                <div wire:click="showReport({{ $report->id }})"
                                    class="flex cursor-pointer flex-col justify-center text-center">
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
                                            {{ $report->title }}
                                        </p>
                                    </div>
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
                            @endif
                        </td>
                        <td class="px-2 py-1">
                            <div class="mx-auto w-full text-left">
                                @if ($activeTab === 'reportes')
                                    @if ($report->project)
                                        @if ($report->delegate)
                                            <select
                                                wire:change='updateDelegateReport({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate" class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $report->delegate->id }}>
                                                    {{ $report->delegate->name }} </option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select
                                                wire:change='updateDelegateReport({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate" class="inpSelectTable w-full text-sm">
                                                <option selected>
                                                    Seleccionar...</option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @else
                                        @if ($report->delegate)
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $report->delegate->id }}>
                                                    {{ $report->delegate->name }} </option>
                                            </select>
                                        @else
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected>
                                                    Usuario eliminado</option>
                                            </select>
                                        @endif
                                    @endif
                                    <p class="text-xs">
                                        @if ($report->state == 'Proceso' || $report->state == 'Conflicto')
                                            Progreso
                                            {{ $report->progress->diffForHumans(null, false, false, 1) }}
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
                                @endif
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            @if ($report->project)
                                <select
                                    wire:change='updateStateReport({{ $report->id }}, {{ $report->project->id }}, $event.target.value)'
                                    name="state" id="state"
                                    class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                    <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                    @foreach ($report->filteredActions as $action)
                                        <option value="{{ $action }}">{{ $action }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="state" id="state"
                                    class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                    <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                </select>
                            @endif
                            @if ($report->count)
                                <p class="text-left text-xs text-red-600">Reincidencia {{ $report->count }}
                                </p>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-left">
                            <div class="mx-auto">
                                {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            <div class="mx-auto text-left">
                                @if ($report->user)
                                    <span class="font-semibold"> {{ $report->user->name }}</span>
                                @else
                                    <span class="font-semibold">Usuario eliminado</span>
                                @endif
                                <br>
                                <span class="font-mono">
                                    {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            <div class="flex justify-center">
                                @if ($report->project)
                                    <a
                                        href="{{ route('projects.reports.index', ['project' => $report->project->id, 'reports' => $report->id, 'highlight' => $report->id]) }}">
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
    @elseif ($activeTab === 'dukke')
        <table class="whitespace-no-wrap table-hover table w-full">
            <thead class="headTable border-0">
                <tr class="text-left">
                    <th class="w-96 px-4 py-3">
                        <div class="flex">
                            Reporte
                            {{-- down-up --}}
                            <svg wire:click='filterDown("reportDukke")' xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredDukke) block @else hidden @endif ml-2 cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 3l0 18" />
                                <path d="M10 18l-3 3l-3 -3" />
                                <path d="M7 21l0 -18" />
                                <path d="M20 6l-3 -3l-3 3" />
                            </svg>
                            {{-- up-down --}}
                            <svg wire:click='filterUp("reportDukke")' xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredDukke) hidden @else block @endif ml-2 cursor-pointer">
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
                            <svg wire:click="filterDown('expected_dateDukke')" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredDukke) block @else hidden @endif ml-2 cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 3l0 18" />
                                <path d="M10 18l-3 3l-3 -3" />
                                <path d="M7 21l0 -18" />
                                <path d="M20 6l-3 -3l-3 3" />
                            </svg>
                            {{-- up-down --}}
                            <svg wire:click="filterUp('expected_dateDukke')" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredDukke) hidden @else block @endif ml-2 cursor-pointer">
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
                @foreach ($reportsDukke as $report)
                    <tr class="trTable">
                        <td class="relative px-2 py-1">
                            @if ($activeTab === 'dukke')
                                <div wire:click="showReport({{ $report->id }})"
                                    class="flex cursor-pointer flex-col justify-center text-center">
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
                                            {{ $report->title }}
                                        </p>
                                    </div>
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
                            @endif
                        </td>
                        <td class="px-2 py-1">
                            <div class="mx-auto w-full text-left">
                                @if ($activeTab === 'dukke')
                                    @if ($report->project)
                                        @if ($report->delegate)
                                            <select
                                                wire:change='updateDelegateReport({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate" class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $report->delegate->id }}>
                                                    {{ $report->delegate->name }} </option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select
                                                wire:change='updateDelegateReport({{ $report->id }}, $event.target.value)'
                                                name="delegate" id="delegate" class="inpSelectTable w-full text-sm">
                                                <option selected>
                                                    Seleccionar...</option>
                                                @foreach ($report->usersFiltered as $userFiltered)
                                                    <option value="{{ $userFiltered->id }}">
                                                        {{ $userFiltered->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @else
                                        @if ($report->delegate)
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected value={{ $report->delegate->id }}>
                                                    {{ $report->delegate->name }} </option>
                                            </select>
                                        @else
                                            <select name="delegate" id="delegate"
                                                class="inpSelectTable w-full text-sm">
                                                <option selected>
                                                    Usuario eliminado</option>
                                            </select>
                                        @endif
                                    @endif
                                    <p class="text-xs">
                                        @if ($report->state == 'Proceso' || $report->state == 'Conflicto')
                                            Progreso
                                            {{ $report->progress->diffForHumans(null, false, false, 1) }}
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
                                @endif
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            @if ($report->project)
                                <select
                                    wire:change='updateStateReport({{ $report->id }}, {{ $report->project->id }}, $event.target.value)'
                                    name="state" id="state"
                                    class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                    <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                    @foreach ($report->filteredActions as $action)
                                        <option value="{{ $action }}">{{ $action }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="state" id="state"
                                    class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                    <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                </select>
                            @endif
                            @if ($report->count)
                                <p class="text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-left">
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
                                    <span class="font-semibold"> {{ $report->user->name }}</span>
                                @else
                                    <span class="font-semibold">Usuario eliminado</span>
                                @endif
                                <br>
                                <span class="font-mono">
                                    {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            <div class="flex justify-center">
                                @if ($report->project)
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
                                            <div wire:click="showEditReport({{ $report->id }})"
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
                                            <!-- Botón VER MAS -->
                                            <a @if ($report->report_id == null || ($report->report_id != null && $report->repeat == true)) href="{{ route('projects.reports.index', [
                                                'project' => $report->project->id,
                                                'reports' => $report->id,
                                                'highlight' => $report->id,
                                            ]) }}" @endif
                                                class="flex cursor-pointer px-4 py-2 text-sm text-black">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye mr-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>Ver mas
                                            </a>
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
    @elseif ($activeTab === 'task')
        <table class="whitespace-no-wrap table-hover table w-full">
            <thead class="headTable border-0">
                <tr class="text-left">
                    <th class="w-96 px-4 py-3">Tareas</th>
                    <th class="px-1 py-3 lg:w-48">Delegado</th>
                    <th class="w-48 px-2 py-3">Estado</th>
                    <th class="w-44 px-1 py-3">
                        <div class="flex items-center">
                            Fecha de entrega
                            {{-- down-up --}}
                            <svg wire:click="filterDown('expected_dateTask')"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-down-up @if ($filteredTask) block @else hidden @endif ml-2 cursor-pointer">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 3l0 18" />
                                <path d="M10 18l-3 3l-3 -3" />
                                <path d="M7 21l0 -18" />
                                <path d="M20 6l-3 -3l-3 3" />
                            </svg>
                            {{-- up-down --}}
                            <svg wire:click="filterUp('expected_dateTask')"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-up-down @if ($filteredTask) hidden @else block @endif ml-2 cursor-pointer">
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
                    <tr class="trTable">
                        <td class="relative px-2 py-1">
                            @if ($activeTab === 'task')
                                <div @if ($task->project_activity) wire:click='showActivity({{ $task->id }})'
                                @elseif($task->project_report) wire:click='showReport({{ $task->id }})' @endif
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
                                        @elseif(Auth::user()->type_user == 1 && $task->client == false && $task->user_id == false)
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
                            @endif
                        </td>
                        <td class="px-2 py-1">
                            <div class="mx-auto w-full text-left">
                                @if ($activeTab === 'task')
                                    @if ($task->project_activity || $task->project_report)
                                        <select
                                            @if ($task->project_activity) wire:change.defer='updateDelegateActivity({{ $task->id }}, $event.target.value)'
                                    @elseif($task->project_report) wire:change='updateDelegateReport({{ $task->id }},
                                    $event.target.value)' @endif
                                            name="delegate" id="delegate"
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
                                @endif
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            @if ($task->project_activity || $task->project_report)
                                <select name="state" id="state"
                                    class="inpSelectTable @if ($task->state == 'Abierto') bg-blue-500 text-white @endif @if ($task->state == 'Proceso') bg-yellow-400 @endif @if ($task->state == 'Resuelto') bg-lime-700 text-white @endif @if ($task->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold"
                                    @if ($task->project_activity) wire:change.defer='updateStateActivity({{ $task->id }},
                                $event.target.value)'
                                @elseif($task->project_report) wire:change='updateStateReport({{ $task->id }}, {{ $task->project_id }}, $event.target.value)' @endif>
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
                        <td class="px-2 py-1 text-left">
                            <div class="mx-auto">
                                {{ \Carbon\Carbon::parse($task->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            <div class="mx-auto text-left">
                                <span class="font-semibold"> {{ $task->created_name }}</span>
                                <br>
                                <span class="font-mono">
                                    {{ \Carbon\Carbon::parse($task->created_at)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-1">
                            <div class="flex justify-center">
                                @if ($task->project_activity)
                                    <a
                                        href="{{ route('projects.activities.index', ['project' => $task->project_id, 'activity' => $task->id, 'highlight' => $task->id]) }}">
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
                                        href="{{ route('projects.reports.index', ['project' => $task->project_id, 'reports' => $task->id, 'highlight' => $task->id]) }}">
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
        @endif
    </div>
    {{-- END TABLE --}}
    @if ($activeTab === 'actividades')
        <div class="py-5">
            {{ $activities->links() }}
        </div>
    @elseif ($activeTab === 'reportes')
        <div class="py-5">
            {{ $reports->links() }}
        </div>
    @elseif ($activeTab === 'dukke')
        <div class="py-5">
            {{ $reportsDukke->links() }}
        </div>
    @elseif ($activeTab === 'task')
        @if (Auth::user()->type_user == 1)

        @endif
    @elseif ($activeTab === 'created')
        @if (Auth::user()->type_user == 1)
            <div class="py-5">
                {{ $taskCreated->links() }}
            </div>
        @endif
    @endif
</div>
{{-- MODAL SHOW ACTIVITY --}}
<div id="modalShowActivity"
    class="@if ($modalShowActivity) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
    <div
        class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
    </div>
    <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
        <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
            @if ($showActivity)
                <div
                    class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    <h3
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        @if ($activityShow->sprint && $activityShow->sprint->backlog && $activityShow->sprint->backlog->project)
                            <div class="flex flex-row">
                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                    {{ $activityShow->sprint->backlog->project->name }}
                                </p>
                            </div>
                        @else
                            <p class="text-justify text-xs font-semibold">
                                Proyecto no disponible
                            </p>
                        @endif
                        {{ $activityShow->title }}
                    </h3>
                    <svg wire:click="modalShowActivity()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
            @else
                <div class="bg-main-fund flex flex-row justify-end rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
                    <svg wire:click="modalShowActivity()" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
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
                            {!! nl2br(e($activityShow->description)) !!}<br><br>
                            @if ($showChatActivity)
                                <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                <div id="messageContainerActivity"
                                    class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                    @foreach ($messagesActivity as $index => $message)
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
                                                            <span class="text-sm font-semibold text-black">ARTEN</span>
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
                            <input wire:model.defer='messageActivity' type="text" name="message" id="message"
                                placeholder="Mensaje a los administradores" class="inputs"
                                style="border-radius: 0.5rem 0px 0px 0.5rem !important">
                            <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                wire:click="updateChatActivity({{ $activityShow->id }})">
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
                </div>
            @endif
        </div>
    </div>
</div>
{{-- END MODAL SHOW ACTIVITY --}}
{{-- MODAL SHOW REPORT --}}
<div id="modalShowReport"
    class="@if ($modalShowReport) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
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
                    <svg wire:click="modalShowReport" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
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
                            @if ($showChatReport)
                                <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                <div id="messageContainer"
                                    class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                                    @foreach ($messagesReport as $index => $message)
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
                                                            <span class="text-sm font-semibold text-black">ARTEN</span>
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
                            <input wire:model.defer='messageReport' type="text" name="message" id="message"
                                class="inputs" style="border-radius: 0.5rem 0px 0px 0.5rem !important"
                                @if (Auth::user()->type_user != 3) placeholder="Mensaje"
                            @else
                            placeholder="Mensaje para Arten" @endif>
                            <button class="btnSave" style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                                wire:click="updateChatReport({{ $reportShow->id }})">
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
                        <div class="mb-6 w-auto">
                            <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                <div class="text-text2 text-center text-xl font-semibold md:flex">Detalle</div>
                                @if ($reportShow->evidence == true)
                                    <div class="btnIcon cursor-pointer font-semibold text-blue-500 hover:text-blue-400"
                                        onclick="toogleEvidence()" id="textToogle">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-eye" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                            <a href="{{ asset('reportes/' . $reportShow->content) }}"
                                                target="_blank">
                                                <img src="{{ asset('reportes/' . $reportShow->content) }}"
                                                    alt="Report Image">
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
                                                <a href="{{ asset('reportes/' . $reportShow->content) }}"
                                                    target="_blank">
                                                    <video src="{{ asset('reportes/' . $reportShow->content) }}"
                                                        loop autoplay alt="Report Video"></video>
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
                                            <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
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
                    <div id="evidence" class="photos hidden w-full px-5 lg:w-1/2">
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
{{-- END MODAL SHOW REPORT --}}
{{-- MODAL EDIT / CREATE REPORT --}}
<div
    class="@if ($modalEditReport) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
    <div
        class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
    </div>
    <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
        <div class="@if (Auth::user()->type_user != 3) md:w-3/4 @else md:w-2/5 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
            style="max-height: 90%;">
            <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                <h3
                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                    Editar reporte</h3>
                <svg wire:click="modalEditReport" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
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
                                        <input type="checkbox" wire:model="evidenceEdit" class="priority-checkbox"
                                            style="height: 24px; width: 24px;" />
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
                        @if ($showEditReport)
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
                            <div class="@if ($changePoints) block @else hidden @endif -mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Puntos <p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='points' required type="number"
                                        placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                                        class="inputs">
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
                @if ($modalEditReport)
                    <button class="btnSave"
                        wire:click="updateReport({{ $reportEdit->id }}, {{ $reportEdit->project_id }})">
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
            <div class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                <h3
                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                    Evidencia</h3>
                <svg id="modalEvidence" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
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
                        wire:click="updateEvidence({{ $reportEvidenceReport->id }}, {{ $reportEvidenceReport->project_id }})">
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
    wire:target="setActiveTab, filterDown, filterUp, showActivity, showReport, modalShowActivity, updateChatActivity, modalShowReport, updateChatReport, updateEvidence">
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
        window.addEventListener('file-reset', () => {
            document.getElementById('file').value = null;
        });
        // MODALS
        window.addEventListener('swal:modal', event => {
            toastr[event.detail.type](event.detail.text, event.detail.title);
        });
        // Scroll de Comentrios de modal
        document.addEventListener("DOMContentLoaded", function() {
            var modalActivity = document.getElementById("modalShowActivity");
            var modalReport = document.getElementById("modalShowReport");

            if (modalActivity) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === "class") {
                            var classList = mutation.target.classList;
                            if (classList.contains("block") && !classList.contains("hidden")) {
                                var messageContainer = document.getElementById(
                                    "messageContainerActivity");
                                if (messageContainer) {
                                    messageContainer.scrollTop = messageContainer.scrollHeight;
                                } else {
                                    console.error("Element with ID 'messageContainer' not found.");
                                }
                            }
                        }
                    });
                });

                observer.observe(modalActivity, {
                    attributes: true // Configura el observador para escuchar cambios en los atributos
                });
            } else {
                console.error("Modal element with ID 'modalShow' not found.");
            }

            if (modalReport) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === "class") {
                            var classList = mutation.target.classList;
                            if (classList.contains("block") && !classList.contains("hidden")) {
                                var messageContainer = document.getElementById(
                                    "messageContainerReport");
                                if (messageContainer) {
                                    messageContainer.scrollTop = messageContainer.scrollHeight;
                                } else {
                                    console.error("Element with ID 'messageContainer' not found.");
                                }
                            }
                        }
                    });
                });

                observer.observe(modalReport, {
                    attributes: true // Configura el observador para escuchar cambios en los atributos
                });
            } else {
                console.error("Modal element with ID 'modalShow' not found.");
            }
        });
        // DROPDOWN REPORT
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
        // DROPDOWN REPORT
        let modalEvidence = document.getElementById('modalEvidence');
        if (modalEvidence) {
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
        }
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
