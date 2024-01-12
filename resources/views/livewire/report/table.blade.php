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
                    <option value="5"> 25 por Página</option>
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
                        <th class=" px-4 py-3">Miniatura</th>
                        <th class=" px-4 py-3">Reporte</th>
                        <th class=" px-4 py-3">Comentario</th>
                        <th class=" px-4 py-3">Estado</th>
                        <th class=" px-4 py-3">Duración</th>
                        <th class=" px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="border-white text-sm">
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2">{{ $report->tittle }}</td>
                        <td class="px-4 py-2">{{ $report->comment }}</td>
                        <td class="px-4 py-2">{{ $report->state }}</td>
                        <td class="px-4 py-2">{{ $report->created_at }}</td>
                        <td class="px-4 py-2 justify-between">
                            <button wire:click="showUpdate({{ $report->id }})" class="bg-yellow text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                    <path d="M16 5l3 3"></path>
                                </svg>
                            </button>
                            <button wire:click="showDelete({{ $report->id }})" class="bg-red text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 7l16 0"></path>
                                    <path d="M10 11l0 6"></path>
                                    <path d="M14 11l0 6"></path>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                </svg>
                            </button>
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
    {{-- MODAL DELETE --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalDelete) block  @else hidden @endif">
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col w-2/6 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-end px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    <svg wire:click="modalDelete" wire:loading.remove wire:target="modalDelete" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                @if($showDelete)
                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="w-full sm:w-3/5 md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="text-lg md:flex mb-6 text-center">
                            <h2 class="text-red font-semibold">¿Esta seguro de eliminar a {{$reportDelete->tittle}}?</h2>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center py-6 px-10 bg-main-fund">
                    <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="modalDelete()" wire:loading.remove wire:target="modalDelete()">Cancelar</button>
                    <button class="px-4 py-2 text-white font-semibold bg-secondary-fund hover:bg-red rounded cursor-pointer" wire:click="destroy({{$userDelete->id}})" wire:loading.remove wire:target="destroy({{$userDelete->id}})">Eliminar</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- END MODAL DELETE --}}
</div>
