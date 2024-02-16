<div>
    {{--Tabla usuarios--}}
    <div class="shadow-xl sm:rounded-lg px-4 py-4">
        {{-- NAVEGADOR --}}
        <div class="flex justify-between text-sm lg:text-base">
            <!-- SEARCH -->
            <div class="inline-flex sm:w-3/4 h-12 mb-2 bg-transparent">
                <div class="flex w-full h-full relative">
                    <div class="flex">
                        <span class="flex items-center leading-normal bg-transparent rounded-lg  border-0  border-none lg:px-3 p-2 whitespace-no-wrap">
                            <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <input wire:model="search" type="text" placeholder="Buscar" class="flex w-full border-0 border-yellow border-b-2 rounded rounded-l-none relative focus:outline-none text-xxs lg:text-base text-gray-500 font-thin">
                </div>
            </div>
            <!-- COUNT -->
            <div class="inline-flex w-1/3 sm:w-1/4 h-12 md:mx-3 mb-2 bg-transparent">
                <select wire:model="perPage" id="" class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                    <option value="10"> 10 por página</option>
                    <option value="25"> 25 por página</option>
                    <option value="50"> 50 por página</option>
                    <option value="100"> 100 por página</option>
                </select>
            </div>
            <!-- BTN NEW -->
            <div class="inline-flex w-1/4 h-12 bg-transparent mb-2">
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
                        <th class=" px-4 py-3">Reporte</th>
                        <th class=" px-4 py-3 w-1/5">Título</th>
                        <th class=" px-4 py-3">Creado</th>
                        <th class=" px-4 py-3 w-1/5">Delegado a</th>
                        <th class=" px-4 py-3">Duración</th>
                        <th class="px-4 py-3 w-1/6">
                            Acciones / Estado
                            <select wire:model="selectedState" class="mx-2 leading-snug border border-gray-400 appearance-none bg-white text-gray-700 rounded">
                                <option value="">Todos</option>
                                <option value="Abierto">Abierto</option>
                                <option value="Proceso">Proceso</option>
                                <option value="Resuelto">Resuelto</option>
                                <option value="Conflicto">Conflicto</option>
                            </select>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-white text-sm text-center" style="@if($report->state == 'Resuelto') background: #a3cfd1  @endif">
                        <td class="px-4 py-2">
                            <div wire:click="showReport({{$report->id}})" class="flex flex-col items-center text-center cursor-pointer">
                                @if (!empty($report->content))
                                    @if ($report->image == true)
                                        <img src="{{ asset('reportes/' . $report->content) }}" alt="Report Image" class="h-20 w-20 object-cover mx-auto">
                                    @endif
                                    @if ($report->video == true)
                                        @if (strpos($report->content, "Reporte") === 0)
                                            <p class="text-red my-5">Falta subir '{{ $report->content }}'</p>
                                        @else
                                            <video src="{{ asset('reportes/' . $report->content) }}" alt="Report Video" class="h-20 w-20 object-cover mx-auto"></video>
                                        @endif
                                    @endif
                                    @if ($report->file == true)
                                        <p class="my-3 text-secondary font-semibold">Documento</p>
                                    @endif
                                @else
                                    <p class="text-red"></p>
                                @endif
                                <p class="text-xs">Creado por {{ $report->user->name }} {{ $report->user->lastname }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            @if ($report->count)
                                <div class="my-2 font-semibold text-center
                                    @if($report->value == 'Alto') bg-red text-white @endif
                                    @if($report->value == 'Medio') bg-yellow @endif
                                    @if($report->value == 'Bajo') bg-secondary text-white @endif">
                                    {{ $report->value }}
                                </div> 
                                <p class="text-red">Reincidencia {{ $report->count }}</p> 
                                {{ $report->title }}
                            @else
                                <div class="my-2 font-semibold text-center
                                    @if($report->value == 'Alto') bg-red text-white @endif
                                    @if($report->value == 'Medio') bg-yellow @endif
                                    @if($report->value == 'Bajo') bg-secondary text-white @endif">
                                    {{ $report->value }}
                                </div> 
                                {{ $report->title }}
                            @endif
                        </td>

                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($report->created_at)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</td>

                        <td class="px-4 py-2">
                            <p class="@if($report->state == 'Resuelto') @else hidden @endif">{{ $report->delegate->name }} {{ $report->delegate->lastname }}</p>
                            <select wire:change='updateDelegate({{ $report->id }}, $event.target.value)'  name="delegate" id="delegate" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto @if($report->state == 'Resuelto') hidden @endif">
                                <option selected value={{ $report->delegate->id }}>{{ $report->delegate->name }} {{ $report->delegate->lastname }}</option>
                                @foreach ($report->usersFiltered as $userFiltered)
                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }} {{ $userFiltered->lastname }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="px-4 py-2">
                            @if($report->state == "Proceso" || $report->state == "Conflicto")
                                Progreso {{ $report->progress->diffForHumans(null, false, false, 2) }}
                            @else
                                @if ($report->state == "Resuelto")
                                    @if ($report->progress == null)
                                        Sin tiempo de desarrollo
                                    @else
                                        Tiempo de desarrollo {{ $report->timeDifference }}
                                    @endif
                                @else
                                    @if ($report->look == true)
                                        Visto {{ $report->progress->diffForHumans(null, false, false, 2) }}
                                    @else
                                        Creado {{ $report->created_at->diffForHumans(null, false, false, 2) }}
                                    @endif
                                    
                                @endif
                            @endif
                        </td>
                        @if ($report->user->id == Auth::id() || $report->delegate->id == Auth::id() || Auth::user()->type_user == 1)
                            <td class="px-4 py-2 flex-col justify-center items-center lg:flex-row lg:flex-wrap">
                                <div class="flex justify-center items-center flex-wrap">
                                    <button wire:click="showEdit({{$report->id}})" class="@if($report->state == 'Resuelto') hidden @endif bg-yellow text-white font-bold py-1 px-2 mt-1 mx-1 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                            <path d="M16 5l3 3"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="showDelete({{$report->id}}, {{$project->id}})" class="@if($report->state != 'Abierto') hidden @endif bg-red text-white font-bold py-1 px-2 mt-1 mx-1 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7l16 0"></path>
                                            <path d="M10 11l0 6"></path>
                                            <path d="M14 11l0 6"></path>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                        </svg>
                                    </button>
                                    <div class="@if($report->repeat != false && $report->state == 'Resuelto') divSelect @else @endif flex flex-col lg:flex-row mt-3 lg:mt-0 lg:justify-between">
                                        <select wire:change='updateState({{ $report->id }}, {{ $project->id }}, $event.target.value)' name="state" id="state" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                            <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                            @foreach ($report->filteredActions as $action)
                                                <option value="{{ $action }}">{{ $action }}</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="reportRepeat({{ $project->id }}, {{$report->id}})" class="@if($report->repeat != false && $report->state == 'Resuelto')  @else hidden @endif m-2 p-2 text-white font-semibold  bg-main hover:bg-secondary rounded-lg cursor-pointer w-full">
                                            Reincidencia
                                        </button>
                                    </div>
                                </div>
                            </td>
                        @else
                            <td class="px-4 py-2 text-center">
                                <p>{{ $report->state }}</p>
                            </td>
                        @endif
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
                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="text-2xl md:flex text-justify">
                            {!! nl2br(e($reportShow->comment)) !!}
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
            <div class="flex flex-col md:w-2/5 mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Editar reporte</h2>
                    <svg wire:click="modalEdit" wire:loading.remove wire:target="modalEdit" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex flex-col px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="-mx-3 md:flex mb-6">
                        <div class="w-full flex flex-col px-3 mb-6 mb-0">
                            <h5 class="inline-flex font-semibold" for="name">
                                Seleccionar archivo
                            </h5>
                            <input wire:model='file' type="file" name="file" id="file" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                        </div>
                        <div class="w-full flex flex-col px-3 ">
                            <h5 class="inline-flex font-semibold" for="name">
                                Descripción del reporte
                            </h5>
                            <textarea wire:model='comment' type="text" rows="10" placeholder="Describa la nueva observación y especifique el objetivo a cumplir." name="comment" class="fields1 leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto"></textarea>
                        </div>
                    </div>
                    
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
                        <div class="text-lg md:flex text-center">
                            <h2 class="text-red font-semibold">¿Esta seguro de eliminar el reporte {{$reportDelete->title}}?</h2>
                            <p class="mt-3">{{$reportDelete->comment}}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center py-6 px-10 bg-main-fund">
                    <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="modalDelete()" wire:loading.remove wire:target="modalDelete()">Cancelar</button>
                    <form method="POST" action="{{ route('projects.reports.destroy', ['project' => $reportDelete->project_id, 'report' => $reportDelete->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-red rounded cursor-pointer">Eliminar</button>
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
                    <div class="-mx-3 md:flex mb-6">
                        <div class="w-full flex flex-col px-3">
                            <h5 class="inline-flex font-semibold mb-3" for="name">
                                Para completar tu reporte, por favor, sube el archivo de evidencia.
                            </h5>
                            <input required wire:model.defer='evidence' type="file" name="evidence" id="evidence" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
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
