<style>

</style>
<div class="px-4 py-4 sm:rounded-lg">
    <!-- Controles de DataTables: Buscar y Mostrar registros -->
    <div class="mb-4 flex items-center justify-between">
        <div class="dataTables_filter flex">
            <div class="relative flex h-full w-full">
                <div class="absolute z-10 flex">
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
                <input type="search" id="tableSearch" placeholder="Buscar reporte" class="inputs"
                    style="padding-left: 3em;" aria-controls="mytable">
            </div>
            @if (Auth::user()->type_user != 3)
                <!-- DELEGATE -->
                <div class="relative mx-3 flex h-full w-full">
                    <select id="delegateFilter" class="inputs">
                        <option value="">Delegados</option>
                        @foreach ($allUsersFiltered as $userFiltered)
                            <option value="{{ $userFiltered['name'] }}">{{ $userFiltered['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="relative mx-3 flex h-full w-full">
                <div id="stateFilter" class="inputs relative">
                    <span class="select-label block flex justify-between">
                        Estados
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-chevron-down" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 9l6 6l6 -6" />
                        </svg>
                    </span>
                    <div
                        class="select-options absolute left-0 top-full z-10 hidden w-full rounded-md border-0 bg-white shadow-lg">
                        <label class="block px-3 py-2 text-sm hover:bg-gray-100">
                            <input type="checkbox" value="Abierto" class="mr-2"> Abierto
                        </label>
                        <label class="block px-3 py-2 text-sm hover:bg-gray-100">
                            <input type="checkbox" value="Proceso" class="mr-2"> Proceso
                        </label>
                        <label class="block px-3 py-2 text-sm hover:bg-gray-100">
                            <input type="checkbox" value="Resuelto" class="mr-2"> Resuelto
                        </label>
                        <label class="block px-3 py-2 text-sm hover:bg-gray-100">
                            <input type="checkbox" value="Conflicto" class="mr-2"> Conflicto
                        </label>
                    </div>
                </div>
            </div>
            {{-- <div class="relative mx-3 flex h-full w-full">
                <label class="flex">Mostrar
                    <select name="mytable_length" id="tableLength" aria-controls="mytable" class="inputs mx-2">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select> registros
                </label>
            </div> --}}
        </div>

        <div class="dataTables_length">
            <form action="{{ route('projects.reports.create', ['project' => $project->id]) }}" method="GET">
                <button type="submit" class="btnNuevo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Reporte
                </button>
            </form>
        </div>
    </div>

    <table id="mytable" class="whitespace-no-wrap table-hover table w-full">
        <thead class="headTable border-0">
            <tr>
                <th class="w-96 px-4 py-3 text-left">Reporte</th>
                <th class="px-2 py-3 text-left lg:w-48">Delegado</th>
                <th class="w-48 px-2 py-3 text-left">Estado</th>
                <th class="w-44 px-2 py-3 text-left">Fecha de entrega</th>
                <th class="w-56 px-2 py-3 text-left">Creado</th>
                <th class="w-16 px-2 py-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td class="px-2 py-1">
                        <div class="flex cursor-pointer flex-row items-center text-center">
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
                        </div>
                    </td>
                    <td class="px-2 py-1">
                        <livewire:projects.reports.delegate :delegate="$report->delegate->id" :report="$report->id">
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
                    </td>
                    <td class="px-2 py-1">
                        @if (Auth::user()->type_user == 3)
                            <p
                                class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif w-1/2 text-sm font-semibold">
                                {{ $report->state }}
                            </p>
                        @else
                            <select name="state" id="state"
                                class="inpSelectTable @if ($report->state == 'Abierto') bg-blue-500 text-white @endif @if ($report->state == 'Proceso') bg-yellow-400 @endif @if ($report->state == 'Resuelto') bg-lime-700 text-white @endif @if ($report->state == 'Conflicto') bg-red-600 text-white @endif flex text-sm font-semibold">
                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                            </select>
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
                                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                    </svg>
                                </button>
                                <!-- Panel -->
                                <div id="dropdown-panel-{{ $report->id }}" style="display: none;"
                                    class="@if (Auth::user()->type_user == 1 || (isset($report->user) && Auth::user()->id == $report->user->id)) {{ $loop->last ? '-top-16' : 'top-3' }} @else {{ $loop->last ? '-top-8' : 'top-3' }} @endif absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                    <!-- Botón Editar -->
                                    <div wire:click="showEdit({{ $report->id }})"
                                        class="@if ($report->state == 'Resuelto') hidden @endif flex cursor-pointer px-4 py-2 text-sm text-black">
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
                                    @if (Auth::user()->type_user == 1 || (isset($report->user) && Auth::user()->id == $report->user->id))
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
        <tfoot class="headTable border-0">
            <tr>
                <th class="w-96 px-4 py-3 text-left">Reporte</th>
                <th class="px-2 py-3 text-left lg:w-48">Delegado</th>
                <th class="w-48 px-2 py-3 text-left">Estado</th>
                <th class="w-44 px-2 py-3 text-left">Fecha de entrega</th>
                <th class="w-56 px-2 py-3 text-left">Creado</th>
                <th class="w-16 px-2 py-3 text-left">Acciones</th>
            </tr>
        </tfoot>
    </table>
    <div class="my-4 flex w-1/3 justify-start" id="paginationControls">
        <button id="prevPage" class="btnDisabled">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-left">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M13.883 5.007l.058 -.005h.118l.058 .005l.06 .009l.052 .01l.108 .032l.067 .027l.132 .07l.09 .065l.081 .073l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059v12c0 .852 -.986 1.297 -1.623 .783l-.084 -.076l-6 -6a1 1 0 0 1 -.083 -1.32l.083 -.094l6 -6l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01z" />
            </svg>
        </button>
        <span id="pageInfo" class="m-auto w-full text-center">Página 1 de 1</span>
        <button id="nextPage" class="btnSave">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-caret-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M9 6c0 -.852 .986 -1.297 1.623 -.783l.084 .076l6 6a1 1 0 0 1 .083 1.32l-.083 .094l-6 6l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002l-.059 -.002l-.058 -.005l-.06 -.009l-.052 -.01l-.108 -.032l-.067 -.027l-.132 -.07l-.09 -.065l-.081 -.073l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057l-.002 -12.059z" />
            </svg>
        </button>
    </div>
</div>
<script>
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
</script>
