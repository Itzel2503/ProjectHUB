<div>
    {{--Tabla usuarios--}}
    <div class="shadow-xl sm:rounded-lg px-4 py-4">
        {{-- NAVEGADOR --}}
        <div class="flex justify-between text-sm lg:text-base">
            <!-- SEARCH -->
            <div class="inline-flex w-4/5">
                <div class="inline-flex md:w-2/5 h-12 mb-2 bg-transparent">
                    <div class="flex w-full h-full relative">
                        <div class="flex">
                            <span class="flex items-center leading-normal bg-transparent rounded-lg  border-0  border-none lg:px-3 p-2 whitespace-no-wrap">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <input wire:model="search" type="text" placeholder="Buscar" class="flex w-full border-0 border-yellow-400 border-b-2 rounded rounded-l-none relative focus:outline-none text-xxs lg:text-base text-gray-500 font-thin">
                    </div>
                </div>
                <!-- DELEGATE -->
                <div class="inline-flex md:w-1/5 h-12 md:mx-3 mb-2 bg-transparent">
                    <select wire:model.lazy="selectedDelegate" class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                        <option value="">Delegados</option>
                        @foreach ($allUsersFiltered as $key => $userFiltered)
                            <option value="{{ $key }}">{{ $userFiltered }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- STATE -->
                <div class="inline-flex md:w-1/5 h-12 md:mx-3 mb-2 bg-transparent">
                    <div class="flex justify-center w-full">
                        <div
                            x-data="{
                                state: false,
                                toggle() {
                                    if (this.state) {
                                        return this.close()
                                    }
                                    this.$refs.button.focus()
                                    this.state = true
                                },
                                close(focusAfter) {
                                    if (! this.state) return
                                    this.state = false
                                    focusAfter && focusAfter.focus()
                                }
                            }"
                            x-on:keydown.escape.prevent.stop="close($refs.button)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['dropdown-button']"
                            class="relative w-full"
                        >
                            <!-- Button -->
                            <button
                                x-ref="button"
                                x-on:click="toggle()"
                                :aria-expanded="state"
                                :aria-controls="$id('dropdown-button')"
                                type="button"
                                class="flex items-center justify-between w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none"
                            >
                                <span>Estados</span>
                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down w-3 h-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M6 9l6 6l6 -6" />
                                </svg>
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg> --}}
                            </button>
                    
                            <!-- Panel -->
                            <div
                                x-ref="panel"
                                x-show="state"
                                x-on:click.outside="close($refs.button)"
                                :id="$id('dropdown-button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-full rounded-md bg-white shadow-md"
                            >
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
                <!-- COUNT -->
                {{-- <div class="inline-flex w-1/3 sm:w-1/4 h-12 md:mx-3 mb-2 bg-transparent">
                    <select wire:model="perPage" id="" class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                        <option value="10"> 10 por página</option>
                        <option value="25"> 25 por página</option>
                        <option value="50"> 50 por página</option>
                        <option value="100"> 100 por página</option>
                    </select>
                </div> --}}
            </div>
            <!-- BTN NEW -->
            <div class="inline-flex w-1/6 h-12 bg-transparent mb-2">
                <button wire:click="create({{ $project->id }})" class="p-auto text-white font-semibold  bg-main hover:bg-secondary rounded-lg cursor-pointer w-full ">
                    Nuevo reporte
                </button>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        
        {{-- TABLE --}}
        <div class="align-middle inline-block w-full overflow-x-scroll bg-main-fund rounded-lg shadow-xs mt-4">
            <table class="w-full whitespace-no-wrap table table-hover ">
                <thead class="border-0 bg-secondary-fund">
                    <tr class="font-semibold tracking-wide text-center text-white text-base">
                        <th class="px-4 py-3 w-1/5">
                            <div @if($priorityFiltered) wire:click="orderByLowPriority()" @else wire:click="orderByHighPriority()" @endif class="justify-center inline-flex cursor-pointer">
                                Reporte 
                                @if ($priorityFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 cursor-pointer icon icon-tabler icon-tabler-sort-ascending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l7 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l9 0" />
                                        <path d="M15 9l3 -3l3 3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 w-1/6">
                            <div @if($progressFiltered) wire:click="orderByLowDates('progress')" @else wire:click="orderByHighDates('progress')" @endif class="justify-center inline-flex">
                                Delegado
                                @if ($progressFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 cursor-pointer icon icon-tabler icon-tabler-sort-ascending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                            <div class="justify-center inline-flex">
                                Estado
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 icon icon-tabler icon-tabler-sort-ascending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 6l7 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l9 0" />
                                    <path d="M15 9l3 -3l3 3" />
                                    <path d="M18 6l0 12" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 6l9 0" />
                                    <path d="M4 12l7 0" />
                                    <path d="M4 18l7 0" />
                                    <path d="M15 15l3 3l3 -3" />
                                    <path d="M18 6l0 12" />
                                </svg> --}}
                            </div>
                        </th>
                        <th class="px-4 py-3">
                            <div @if($expectedFiltered) wire:click="orderByLowDates('expected_date')" @else wire:click="orderByHighDates('expected_date')" @endif class="justify-center inline-flex">
                                Fecha
                                @if ($expectedFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 cursor-pointer icon icon-tabler icon-tabler-sort-ascending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                            <div @if($createdFiltered) wire:click="orderByLowDates('created_at')" @else wire:click="orderByHighDates('created_at')" @endif class="justify-center inline-flex">
                                Creado
                                @if ($createdFiltered)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l9 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l7 0" />
                                        <path d="M15 15l3 3l3 -3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 cursor-pointer icon icon-tabler icon-tabler-sort-ascending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 6l7 0" />
                                        <path d="M4 12l7 0" />
                                        <path d="M4 18l9 0" />
                                        <path d="M15 9l3 -3l3 3" />
                                        <path d="M18 6l0 12" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 w-1/6">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-white text-sm text-center">
                        <td class="px-4 py-2">
                            <div wire:click="showReport({{$report->id}})" class="flex flex-col items-center text-center cursor-pointer">
                                <p class="text-xs mb-2">{{ $report->title }}</p>
                                <div class="relative h-22 w-full px-3 pt-2">
                                    @if (!empty($report->content))
                                        @if ($report->image == true)
                                            <img src="{{ asset('reportes/' . $report->content) }}" alt="Report Image" class="h-20 w-32 object-cover mx-auto">
                                        @endif
                                        @if ($report->video == true)
                                            @if (strpos($report->content, "Reporte") === 0)
                                                <p class="text-red-600 my-3">Falta subir '{{ $report->content }}'</p>
                                            @else
                                                <video src="{{ asset('reportes/' . $report->content) }}" alt="Report Video" class="h-20 w-32 object-cover mx-auto"></video>
                                            @endif
                                        @endif
                                        @if ($report->file == true)
                                            <p class="my-3 text-secondary font-semibold">Documento</p>
                                        @endif
                                    @else
                                        <p class=""></p>
                                    @endif
                                    @if($report->messages_count >= 1 && $report->user_chat != Auth::id())
                                    <div class="absolute top-0 right-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-filled text-red-600" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" stroke-width="0" fill="currentColor" />
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="my-2 w-32 font-semibold text-center rounded-md px-3
                                    @if($report->priority == 'Alto') bg-red-600 text-white @endif
                                    @if($report->priority == 'Medio') bg-yellow-400 @endif
                                    @if($report->priority == 'Bajo') bg-secondary text-white @endif">
                                    {{ $report->priority }}
                                </div> 
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <p class="@if($report->state == 'Resuelto') font-semibold @else hidden @endif">{{ $report->delegate->name }} {{ $report->delegate->lastname }}</p>
                            <select wire:change='updateDelegate({{ $report->id }}, $event.target.value)'  name="delegate" id="delegate" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto @if($report->state == 'Resuelto') hidden @endif">
                                <option selected value={{ $report->delegate->id }}>{{ $report->delegate->name }} {{ $report->delegate->lastname }}</option>
                                @foreach ($report->usersFiltered as $userFiltered)
                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }} {{ $userFiltered->lastname }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs">
                            @if($report->state == "Proceso" || $report->state == "Conflicto")
                                Progreso {{ $report->progress->diffForHumans(null, false, false, 1) }}
                            @else
                                @if ($report->state == "Resuelto")
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
                        </td>
                        <td class="px-4 py-2">
                            <select wire:change='updateState({{ $report->id }}, {{ $project->id }}, $event.target.value)' name="state" id="state" 
                                class="leading-snug border border-none block appearance-none py-1 px-4 w-full rounded mx-auto
                                @if($report->state == 'Abierto' && $report->state == 'Proceso') bg-white @endif
                                @if($report->state == 'Resuelto') bg-lime-500 @endif
                                @if($report->state == 'Conflicto') bg-red-600 text-white @endif
                                ">
                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                @foreach ($report->filteredActions as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                            @if ($report->count)
                                <p class="text-red-600 text-xs">Reincidencia {{ $report->count }}</p> 
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <p class="font-semibold inline-block">Para:</p> {{ \Carbon\Carbon::parse($report->expected_date)->locale('es')->isoFormat('D [-] MMMM [-] YYYY') }}<br>
                            <p class="font-semibold inline-block">Se delegó:</p> {{ \Carbon\Carbon::parse($report->delegated_date)->locale('es')->isoFormat('D [-] MMMM [-] YYYY') }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $report->user->name }} {{ $report->user->lastname }} <br>
                            {{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D [-] MMMM [-] YYYY') }}<br>
                            {{ $report->created_at->diffForHumans(null, false, false, 1) }}
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
                                            if (! this.open) return
                                            this.open = false
                                            focusAfter && focusAfter.focus()
                                        }
                                    }"
                                    x-on:keydown.escape.prevent.stop="close($refs.button)" x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative"
                                >
                                    <!-- Button -->
                                    <button x-ref="button" x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="flex items-center px-5 py-2.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        </svg>
                                    </button>
                                    <!-- Panel -->
                                    <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute right-10 top-3 mt-2 w-32 rounded-md bg-white shadow-md" >
                                        <!-- Botón Editar -->
                                        <a wire:click="showEdit({{$report->id}})" class="@if($report->state == 'Resuelto') hidden @endif block px-4 py-2 text-sm text-black cursor-pointer">Editar</a>
                                        <!-- Botón Eliminar -->
                                        <a wire:click="showDelete({{$report->id}}, {{$project->id}})" class="@if($report->state != 'Abierto') hidden @endif block px-4 py-2 text-sm text-red-600 cursor-pointer">Eliminar</a>
                                        <!-- Botón Reincidencia -->
                                        <a wire:click="reportRepeat({{ $project->id }}, {{$report->id}})" class="@if($report->repeat != false && $report->state == 'Resuelto')  @else hidden @endif block px-4 py-2 text-sm text-black cursor-pointer">Reincidencia</a>
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
            {{ $reports->links()}}
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL SHOW --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalShow) block  @else hidden @endif">
        <div class="flex justify-center h-full items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-full items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col  @if($evidenceShow) md:w-4/5 @else md:w-3/4 @endif  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
            @if (!empty($reportShow->content))
                @if ($reportShow->image == true)
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <div class="flex justify-center items-center">
                        <a href="{{ asset('reportes/' . $reportShow->content) }}" download="{{ basename($reportShow->content) }}" class="px-4 py-2 mt-5 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">
                            Descargar captura
                        </a>
                    </div>
                    <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @endif
                @if ($reportShow->video == true)
                    @if (strpos($reportShow->content, "Reporte") === 0)
                    <div class="flex flex-row justify-end px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                        <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    @else
                    <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                        <div class="flex justify-center items-center">
                            <a href="{{ asset('reportes/' . $reportShow->content) }}" download="{{ basename($reportShow->content) }}" class="px-4 py-2 mt-5 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">
                                Descargar video
                            </a>
                        </div>
                        <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                        </svg>
                    </div>
                    @endif
                @endif
                @if ($reportShow->file == true)
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <div class="flex justify-center items-center">
                        <a href="{{ asset('reportes/' . $reportShow->content) }}" download="{{ basename($reportShow->content) }}" class="px-4 py-2 mt-5 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">
                            Descargar documento
                        </a>
                    </div>
                    <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @endif
            @else
                <div class="flex flex-row justify-end px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
            @endif
            
            @if($showReport)
            <div class="flex @if($evidenceShow) flex-row @else flex-col @endif px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                <div class="@if($evidenceShow) flex flex-col w-3/6 @endif">
                    <div class="w-full md-3/4 mb-5 mt-3 flex flex-col">
                        <div class="text-xl text-justify">
                            <h3 class="font-bold text-secondary-fund">Descripción</h3>
                            {!! nl2br(e($reportShow->comment)) !!}<br><br>
                            @if ($showChat)
                                @foreach ($messages as $message)
                                <div class="inline-flex">
                                    @if($message->messages_count >= 1 && $reportShow->user_chat != Auth::id() && $message->look == false)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-exclamation-mark text-red-600" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 19v.01" />
                                            <path d="M12 15v-10" />
                                        </svg>
                                    @endif
                                    <label class="text-lg pr-1 font-bold text-black">{{ $message->transmitter->name }}:</label><p class="text-lg">{{ $message->message }}</p>
                                </div>
                                <br>
                                @endforeach
                            @endif
                        </div>
                        <div class="w-auto flex flex-row mx-3 my-6">
                            <input wire:model='message' type="text" name="message" id="message" placeholder="Mensaje a los administradores"class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full mr-5 rounded mx-auto">
                            <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="updateChat({{ $reportShow->id }})">Enviar</button>
                        </div>
                    </div>

                    @if (!empty($reportShow->content))
                        @if ($reportShow->image == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <img src="{{ asset('reportes/' . $reportShow->content) }}" alt="Report Image">
                            </div>
                        @endif
                        @if ($reportShow->video == true)
                            @if (strpos($reportShow->content, "Reporte") === 0)
                                <div class="w-full my-5 text-lg text-center">
                                    <p class="text-red my-5">Falta subir '{{ $reportShow->content }}'</p>
                                </div>
                            @else
                                <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                    <video src="{{ asset('reportes/' . $reportShow->content) }}" loop autoplay alt="Report Video"></video>
                                </div>
                            @endif
                        @endif
                        @if ($reportShow->file == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <iframe src="{{ asset('reportes/' . $reportShow->content) }}" width="auto" height="800"></iframe>
                            </div>
                        @endif
                    @else
                        <div class="w-full my-5 text-lg text-center">
                            <p class="text-red my-5">Sin archivo</p>
                        </div>
                    @endif
                </div>
                
                @if($evidenceShow)
                <div class="flex flex-col w-3/6">
                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="text-2xl md:flex text-center">
                            Evidencia
                        </div>
                    </div>
                    @if (!empty($evidenceShow->content))
                        @if ($evidenceShow->image == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <img src="{{ asset('evidence/' . $evidenceShow->content) }}" alt="Report Image">
                            </div>
                        @endif
                        @if ($evidenceShow->video == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <video src="{{ asset('evidence/' . $evidenceShow->content) }}" loop autoplay alt="Report Video"></video>
                            </div>
                        @endif
                        @if ($evidenceShow->file == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <iframe src="{{ asset('evidence/' . $evidenceShow->content) }}" width="auto" height="800"></iframe>
                            </div>
                        @endif
                    @else
                        <div class="w-full my-5 text-lg text-center">
                            <p class="text-red my-5">Sin evidencia</p>
                        </div>
                    @endif
                </div>
                @endif

            </div>

            <div class="flex justify-center items-center py-6 px-10 bg-main-fund">
                <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="modalShow()" wire:loading.remove wire:target="modalShow()">Cerrar</button>
            </div>
            @endif
            </div>
        </div>
    </div>
    {{-- END MODAL SHOW --}}
    {{-- MODAL EDIT --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalEdit) block  @else hidden @endif">
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full  fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col md:w-4/3 mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <h3 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary pl-4 py-2">Editar reporte</h3>
                    <svg wire:click="modalEdit" wire:loading.remove wire:target="modalEdit" class="w-6 h-6 my-2 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex flex-col px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="mb-6">
                        <div class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="file">
                                Seleccionar archivo
                            </h5>
                            <input wire:model='file' type="file" name="file" id="file" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                        </div>
                        <div class="w-full flex flex-col px-3 ">
                            <h5 class="inline-flex font-semibold" for="comment">
                                Descripción del reporte
                            </h5>
                            <textarea wire:model='comment' type="text" rows="10" placeholder="Describa la nueva observación y especifique el objetivo a cumplir." name="comment" class="fields1 leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto"></textarea>
                        </div>
                    </div>
                    @if (Auth::user()->type_user == 1)
                    <div class="mb-6">
                        <div class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="expected_date">
                                Fecha de entrega
                            </h5>
                            <input wire:model.defer='expected_date' required type="date" name="expected_date" id="expected_date" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex justify-center items-center py-6 bg-main-fund">
                    @if($modalEdit)
                        <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="update({{ $reportEdit->id }}, {{ $project->id }})"> Guardar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EDIT --}}
    {{-- MODAL DELETE --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalDelete) block  @else hidden @endif">
        <div class="flex justify-center h-full items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-full items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col  md:w-2/5 mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-end px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <svg wire:click="modalDelete" wire:loading.remove wire:target="modalDelete" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @if($showDelete)
                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="w-full md-3/4 flex flex-col">
                        <div class="text-lg text-center">
                            <label class="text-red-600 font-semibold">¿Esta seguro de eliminar el reporte {{$reportDelete->title}}?</label>
                            <p class="mt-3">{{$reportDelete->comment}}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center py-6 px-10 bg-main-fund">
                    <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="modalDelete()" wire:loading.remove wire:target="modalDelete()">Cancelar</button>
                    <form method="POST" action="{{ route('projects.reports.destroy', ['project' => $reportDelete->project_id, 'report' => $reportDelete->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-red-600 rounded cursor-pointer">Eliminar</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL DELETE --}}
    {{-- MODAL EVIDENCE --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalEvidence) block  @else hidden @endif">
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full  fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col md:w-2/5 mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Evidencia</h2>
                    <svg id="modalEvidence" data-id="@if($modalEvidence){{ $reportEvidence->id }}@endif" data-project_id="@if($modalEvidence){{ $reportEvidence->project_id }}@endif" data-state="@if($modalEvidence){{ $reportEvidence->state }}@endif" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="px-6 py-2 bg-main-fund text-sm">
                    <div class="md:flex mb-6">
                        <div class="w-full flex flex-col px-3">
                            <h5 class="inline-flex font-semibold mb-3" for="name">
                                Para completar tu reporte, por favor, sube el archivo de evidencia.
                            </h5>
                            <input wire:model='evidence' type="file" name="evidence" id="evidence" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                        </div>
                    </div>
                </div>
                <div class="flex justify-center items-center py-6 bg-main-fund">
                    @if($modalEvidence)
                        <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="updateEvidence({{ $reportEvidence->id }}, {{ $reportEvidence->project_id }})"> Guardar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EVIDENCE --}}
    <script>
        window.addEventListener('swal:modal', event => {
            toastr[event.detail.type](event.detail.text, event.detail.title);
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
    </script>
</div>
