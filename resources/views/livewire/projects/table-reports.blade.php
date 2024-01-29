<div>
    {{--Tabla usuarios--}}
    <div class="shadow-xl sm:rounded-lg px-4 py-4">
        {{-- NAVEGADOR --}}
        <div class="flex justify-between text-sm lg:text-base">
            <!-- SEARCH -->
            <div class="inline-flex w-3/4 h-12 bg-transparent mb-2">
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
            <div class="inline-flex w-1/4 h-12 mx-3 bg-transparent mb-2">
                <select wire:model="perPage" id="" class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                    <option value="25"> 25 por Página</option>
                    <option value="50"> 50 por Página</option>
                    <option value="100"> 100 por Página</option>
                </select>
            </div>
            <!-- BTN NEW -->
            @if ($leader)
            <div class="inline-flex w-1/4 h-12 bg-transparent mb-2">
                <button wire:click="create({{ $project->id }})" class="px-2 py-2 text-white font-semibold  bg-main hover:bg-secondary rounded-lg cursor-pointer w-full ">
                    Nuevo Reporte
                </button>
            </div>
            @endif
        </div>
        {{-- END NAVEGADOR --}}

        {{-- TABLE --}}
        <div class="align-middle inline-block w-full overflow-x-scroll bg-main-fund rounded-lg shadow-xs mt-4">
            <table class="w-full whitespace-no-wrap table table-hover ">
                <thead class="border-0 bg-secondary-fund">
                    <tr class="font-semibold tracking-wide text-center text-white text-base">
                        <th class=" px-4 py-3"></th>
                        <th class=" px-4 py-3">Delegado a</th>
                        <th class=" px-4 py-3">Reporte</th>
                        <th class=" px-4 py-3">Duración</th>
                        <th class=" px-4 py-3">Acciones / Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-white text-sm">
                        <td class="px-4 py-2">
                            <div wire:click="showReport({{$report->id}})" class="flex flex-col items-center text-center cursor-pointer">
                                <img class="h-20 w-20 rounded-full object-cover mx-auto" aria-hidden="true" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="file" />
                                <p class="text-xs">Creado por {{ $report->user->name }} {{ $report->user->lastname }}</p>
                            </div>
                        </td>

                        <td class="px-4 py-2">
                            <select wire:change='updateDelegate({{ $report->id }}, $event.target.value)'  name="delegate" id="delegate" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <option selected value={{ $report->delegate->id }}>{{ $report->delegate->name }} {{ $report->delegate->lastname }}</option>
                                @foreach ($report->usersFiltered as $userFiltered)
                                    <option value="{{ $userFiltered->id }}">{{ $userFiltered->name }} {{ $userFiltered->lastname }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="px-4 py-2">{{ $report->title }}</td>
                        <td class="px-4 py-2">{{ $report->created_at->diffForHumans(null, false, false, 2) }}</td>
                        <td class="px-4 py-2 flex justify-center items-center">
                            @if ($report->user->id == Auth::id())
                                <button wire:click="showEdit({{$report->id}})" class="bg-yellow text-white font-bold py-1 px-2 mt-1 mx-1 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                        <path d="M16 5l3 3"></path>
                                    </svg>
                                </button>
                            @endif
                            <select wire:change='updateState({{ $report->id }}, $event.target.value)' name="state" id="state" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <option selected value={{ $report->state }}>{{ $report->state }}</option>
                                @foreach ($report->filteredActions as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
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
            <div class="flex flex-col w-2/6 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-end px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <svg wire:click="modalShow" wire:loading.remove wire:target="modalShow" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @if($showReport)
                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">

                    @if (!empty($reportShow->content))
                        @if ($reportShow->image == true)
                            <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                <img src="{{ asset('reportes/' . $reportShow->content) }}" alt="Report Image">
                            </div>
                        @endif
                        @if ($reportShow->video == true)
                            @if ($reportShow->content == 'Falta video grabado')
                                <div class="w-full my-5 text-lg text-center">
                                    <p class="text-red">Subir video</p>
                                </div>
                            @else
                                <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                                    <video src="{{ asset('reportes/' . $reportShow->content) }}" loop autoplay alt="Report Video"></video>
                                </div>
                            @endif
                        @endif
                    @endif

                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="text-lg md:flex mb-6 text-justify">
                            <p class="text-base">{{ $reportShow->comment }}</p>
                        </div>
                    </div>
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
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full h-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full h-full fixed">
            <div class="flex flex-col w-2/4 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Editar reporte</h2>
                    <svg wire:click="modalEdit" wire:loading.remove wire:target="modalEdit" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="-mx-3 md:flex">
                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Selecciona un archivo
                                </h5>
                                <input wire:model='file' type="file" name="file" id="file" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center items-center py-6 bg-main-fund">
                    @if($modalEdit)
                        <button class="px-4 py-2 text-white font-semibold text-white bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="update({{ $reportEdit->id }}, {{ $project->id }})"> Guardar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL EDIT --}}
    <script>
        window.addEventListener('swal:modal', event => {
            toastr[event.detail.type](event.detail.text, event.detail.title);
        });
    </script>
</div>
