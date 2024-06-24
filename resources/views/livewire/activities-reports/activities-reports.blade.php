<div>
    <div class="px-4 py-4 sm:rounded-lg">
        <div wire:ignore id="chart" class="my-5"></div>
        {{-- PESTAÑAS --}}
        <nav class="-mb-px flex">
            @if (Auth::user()->type_user == 1)
                <button wire:click="setActiveTab('task')"
                    class="border-primaryColor @if ($activeTab === 'task') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                    Mis tareas
                </button>
            @endif
            <button wire:click="setActiveTab('actividades')"
                class="border-primaryColor @if ($activeTab === 'actividades') rounded-t-lg border-x-2 border-t-2 @else border-b-2 @endif whitespace-nowrap px-3 pb-4 text-sm font-medium">
                Actividades
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
                                    <input wire:model="searchTask" type="text" placeholder="Buscar tarea" class="inputs"
                                        style="padding-left: 3em;">
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
                            <th class="w-44 px-1 py-3">Fecha de entrega</th>
                            <th class="w-56 px-1 py-3">Creado</th>
                            <th class="w-16 px-1 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activities as $activity)
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    <div wire:click="showActivity({{ $activity->id }})"
                                        class="flex cursor-pointer flex-col justify-center text-center">
                                        @if ($activity->sprint && $activity->sprint->backlog && $activity->sprint->backlog->project)
                                            <div class="flex flex-row">
                                                <div class="w-12"></div>
                                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                    {{ $activity->sprint->backlog->project->name }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-justify text-xs font-semibold">
                                                Proyecto no disponible
                                            </p>
                                        @endif
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
                                            <p class="my-auto text-left text-xs font-semibold">{{ $activity->title }}
                                            </p>
                                        </div>
                                        @if ($activity->messages_count >= 1)
                                            {{-- usuario --}}
                                            @if ($activity->user_chat != Auth::id() && $activity->receiver_chat == Auth::id())
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
                                                {{-- envio varios mensajes de diversos usuarios --}}
                                            @elseif($activity->noView == true)
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
                                                {{-- administrador --}}
                                            @elseif(Auth::user()->type_user == 1 && $activity->client == false && $activity->user_id == false)
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
                                    <div class="mx-auto w-auto text-left">
                                        <p class="font-semibold">
                                            {{ $activity->delegate->name }}</p>
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
                                    <div wire:change='updateState({{ $activity->id }}, $event.target.value)'
                                        name="state" id="state"
                                        class="inpSelectTable @if ($activity->state == 'Abierto') bg-blue-500 text-white @endif @if ($activity->state == 'Proceso') bg-yellow-400 @endif @if ($activity->state == 'Resuelto') bg-lime-700 text-white @endif @if ($activity->state == 'Conflicto') bg-red-600 text-white @endif flex w-24 text-sm font-semibold">
                                        <option selected value={{ $activity->state }}>{{ $activity->state }}</option>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-left">
                                    <div class="mx-auto">
                                        {{ \Carbon\Carbon::parse($activity->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto text-left">
                                        <span class="font-semibold"> {{ $activity->user->name }}</span> 
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
                                                href="{{ route('projects.activities.index', ['project' => $activity->sprint->backlog->project->id, 'activity' => $activity->id]) }}">
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
            @elseif ($activeTab === 'reportes')
                <table class="whitespace-no-wrap table-hover table w-full">
                    <thead class="headTable border-0">
                        <tr class="text-left">
                            <th class="w-96 px-4 py-3">
                                <div class="flex">
                                    Reporte
                                    {{-- down-up --}}
                                    <svg wire:click='filterDown("report")' xmlns="http://www.w3.org/2000/svg"
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
                                    <svg wire:click='filterUp("report")' xmlns="http://www.w3.org/2000/svg"
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
                            <th class="px-1 py-3 lg:w-48">Delegado</th>
                            <th class="w-48 px-2 py-3">Estado</th>
                            <th class="w-44 px-1 py-3">Fecha de entrega</th>
                            <th class="w-56 px-1 py-3">Creado</th>
                            <th class="w-16 px-1 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    <div wire:click="showReport({{ $report->id }})"
                                        class="flex cursor-pointer flex-col justify-center text-center">
                                        @if ($report->project)
                                            <div class="flex flex-row">
                                                <div class="w-12"></div>
                                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                    {{ $report->project->name }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-justify text-xs font-semibold">
                                                Proyecto no disponible
                                            </p>
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
                                            <p class="my-auto text-left text-xs font-semibold">{{ $report->title }}
                                            </p>
                                        </div>
                                        @if ($report->messages_count >= 1)
                                            {{-- usuario --}}
                                            @if ($report->user_chat != Auth::id() && $report->receiver_chat == Auth::id())
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
                                                {{-- envio varios mensajes de diversos usuarios --}}
                                            @elseif($report->noView == true)
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
                                                {{-- administrador --}}
                                            @elseif(Auth::user()->type_user == 1 && $report->client == false && $report->user_id == false)
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
                                        <p class="font-semibold">
                                            {{ $report->delegate->name }}</p>
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
                                    <div name="state" id="state"
                                        class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex w-24 text-sm font-semibold">
                                        <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                    </div>
                                    @if ($report->count)
                                        <p class="text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                                    @endif
                                </td>
                                <td class="px-2 py-1 text-left">
                                    <div class="mx-auto">
                                        {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto text-left">
                                        <span class="font-semibold"> {{ $report->user->name }}</span> 
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
                                                href="{{ route('projects.reports.index', ['project' => $report->project->id, 'reports' => $report->id]) }}">
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
                            <th class="w-44 px-1 py-3">Fecha de entrega</th>
                            <th class="w-56 px-1 py-3">Creado</th>
                            <th class="w-16 px-1 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportsDukke as $report)
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    <div wire:click="showReport({{ $report->id }})"
                                        class="flex cursor-pointer flex-col justify-center text-center">
                                        @if ($report->project)
                                            <div class="flex flex-row">
                                                <div class="w-12"></div>
                                                <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                    {{ $report->project->name }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-justify text-xs font-semibold">
                                                Proyecto no disponible
                                            </p>
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
                                                {{ $report->title }}
                                            </p>
                                        </div>
                                        @if ($report->messages_count >= 1)
                                            {{-- usuario --}}
                                            @if ($report->user_chat != Auth::id() && $report->receiver_chat == Auth::id())
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
                                                {{-- envio varios mensajes de diversos usuarios --}}
                                            @elseif($report->noView == true)
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
                                                {{-- administrador --}}
                                            @elseif(Auth::user()->type_user == 1 && $report->client == false && $report->user_id == false)
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
                                        <p class="font-semibold">
                                            {{ $report->delegate->name }}</p>
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
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div name="state" id="state"
                                        class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex w-24 text-sm font-semibold">
                                        <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                    </div>
                                    @if ($report->count)
                                        <p class="text-xs text-red-600">Reincidencia {{ $report->count }}</p>
                                    @endif
                                </td>
                                <td class="px-2 py-1 text-left">
                                    <div class="mx-auto">
                                        {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="mx-auto text-left">
                                        <span class="font-semibold"> {{ $report->user->name }}</span> 
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
                                                href="{{ route('projects.reports.index', ['project' => $report->project->id, 'reports' => $report->id]) }}">
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
            @elseif ($activeTab === 'task')
                <table class="whitespace-no-wrap table-hover table w-full">
                    <thead class="headTable border-0">
                        <tr class="text-left">
                            <th class="w-96 px-4 py-3">
                                <div class="flex">
                                    Tareas
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
                            <th class="w-44 px-1 py-3">Fecha de entrega</th>
                            <th class="w-56 px-1 py-3">Creado</th>
                            <th class="w-16 px-1 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr class="trTable">
                                <td class="relative px-2 py-1">
                                    <div @if($task->project_activity) wire:click='showActivity({{ $task->id }})' @elseif($task->project_report) wire:click='showReport({{ $task->id }})' @endif
                                        class="flex cursor-pointer flex-col justify-center text-center">
                                        <div class="flex flex-row">
                                            <div class="w-12"></div>
                                            <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                                {{ $task->project_name }}
                                            </p>
                                        </div>
                                        <div class="flex flex-row">
                                            <div class="my-2 w-auto rounded-md px-3 text-center font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-circle @if ($task->priority == 'Alto') text-red-500 @endif @if ($task->priority == 'Medio') text-yellow-400 @endif @if ($task->priority == 'Bajo') text-blue-500 @endif">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                                                </svg>
                                            </div>
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
                                                {{-- envio varios mensajes de diversos usuarios --}}
                                            @elseif($task->noView == true)
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
                                                {{-- administrador --}}
                                            @elseif(Auth::user()->type_user == 1 && $task->client == false && $task->user_id == false)
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
                                        <p class="font-semibold">{{ $task->user_name }}</p>
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
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div name="state" id="state"
                                        class="inpSelectTable @if ($task->state == 'Abierto') bg-blue-500 text-white @endif @if ($task->state == 'Proceso') bg-yellow-400 @endif @if ($task->state == 'Resuelto') bg-lime-700 text-white @endif @if ($task->state == 'Conflicto') bg-red-600 text-white @endif flex w-24 text-sm font-semibold">
                                        <option selected value={{ $task->state }}>{{ $task->state }}</option>
                                    </div>
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
                                        <span class="font-semibold"> {{ $task->user_created }}</span> 
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
                                            href="{{ route('projects.activities.index', ['project' => $task->project_id, 'activity' => $task->id]) }}">
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
                                            href="{{ route('projects.reports.index', ['project' => $task->project_id, 'reports' => $task->id]) }}">
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
                            @php
                                echo mb_substr($activityShow->title, 0, 25) . ' ...';
                            @endphp
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
                @else
                    <div
                        class="bg-main-fund flex flex-row justify-end rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
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
                                <input wire:model.defer='messageActivity' type="text" name="message"
                                    id="message" placeholder="Mensaje a los administradores" class="inputs"
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
                                            <img src="{{ asset('activities/' . $activityShow->image) }}"
                                                alt="Activity Image">
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
    {{-- MODAL SHOW REPORT --}}
    <div id="modalShowReport"
        class="@if ($modalShowReport) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
            <div class="@if ($evidenceShow) md:w-4/5 @else md:w-3/4 @endif mx-auto flex flex-col overflow-y-auto rounded-lg"
                style="max-height: 90%;">
                @if ($showReport)
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            @if ($reportShow->project)
                                <div class="flex flex-row">
                                    <p class="my-auto text-left text-xs font-semibold text-gray-400">
                                        {{ $reportShow->project->name }}
                                    </p>
                                </div>
                            @else
                                <p class="text-justify text-xs font-semibold">
                                    Proyecto no disponible
                                </p>
                            @endif
                            @php echo mb_substr( $reportShow->title, 0, 25) . " ..."; @endphp
                        </h3>
                        <svg wire:click="modalShowReport" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                            xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                @else
                    <div
                        class="bg-main-fund flex flex-row justify-end rounded-tl-lg rounded-tr-lg px-6 py-4 text-white">
                        <svg wire:click="modalShowReport" class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
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
                                @if ($showChatReport)
                                    <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                                    <div id="messageContainerReport"
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
                                <input wire:model.defer='messageReport' type="text" name="message" id="message"
                                    placeholder="Mensaje a los administradores" class="inputs"
                                    style="border-radius: 0.5rem 0px 0px 0.5rem !important">
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
                        <div class="photos w-full px-5 lg:w-1/2">
                            @if (!empty($reportShow->content))
                                <div id="example" class="mb-6 w-auto">
                                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                        <div class="text-text2 text-center text-xl font-semibold md:flex">Detalle</div>
                                        @if ($evidenceShow)
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
                                    @if ($reportShow->contentExists)
                                        @if ($reportShow->image == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <img src="{{ asset('reportes/' . $reportShow->content) }}"
                                                    alt="Report Image">
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
                                                    <video src="{{ asset('reportes/' . $reportShow->content) }}" loop
                                                        autoplay alt="Report Video"></video>
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
                                </div>
                            @else
                                <div class="my-5 w-full text-center text-lg">
                                    <p class="text-red my-5">Sin archivo</p>
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
                        data-id="@if ($modalEvidence) {{ $reportEvidence->id }} @endif"
                        data-project_id="@if ($modalEvidence) {{ $reportEvidence->project_id }} @endif"
                        data-state="@if ($modalEvidence) {{ $reportEvidence->state }} @endif"
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
            // GRAFICA
            document.addEventListener('livewire:load', function() {
                var chart;

                function renderChart(series, categories, totalEffortPoints) {
                    if (chart) {
                        chart.destroy();
                    }

                    var options = {
                        series: series,
                        chart: {
                            type: 'bar',
                            height: '135px',
                            stacked: true,
                        },
                        colors: ['rgb(77, 124, 15)', 'rgb(250, 204, 21)', 'rgb(220, 38, 38)', 'rgb(59, 130, 246)',
                            'rgb(243, 244, 246)'
                        ],
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                dataLabels: {
                                    position: 'top',
                                    total: {
                                        enabled: true,
                                        formatter: function(val, opt) {
                                            return totalEffortPoints[opt.dataPointIndex];
                                        },
                                        offsetX: 0,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900,
                                            color: function({
                                                seriesIndex
                                            }) {
                                                return seriesIndex === 4 ? '#000' : '#fff';
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ['#fff', '#fff', '#fff', '#fff', '#000']
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        title: {
                            text: 'Puntos de esfuerzo por mes'
                        },
                        xaxis: {
                            categories: categories,
                            labels: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left',
                            offsetX: 40
                        }
                    };

                    chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }

                Livewire.on('updateChart', function() {
                    var series = @json($series);
                    var categories = @json($categories);
                    var totalEffortPoints = @json($totalEffortPoints);
                    renderChart(series, categories, totalEffortPoints);
                });

                var initialSeries = @json($series);
                var initialCategories = @json($categories);
                var initialTotalEffortPoints = @json($totalEffortPoints);
                renderChart(initialSeries, initialCategories, initialTotalEffortPoints);
            });
        </script>
    @endpush
</div>