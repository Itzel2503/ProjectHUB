<div>
    {{--Tabla usuarios--}}
    <div class="sm:rounded-lg px-4 py-4">
        {{-- NAVEGADOR --}}
        <div class="flex flex-wrap justify-between text-sm lg:text-base">
            <!-- SEARCH -->
            <div class="inline-flex px-2 md:px-0  w-1/2 md:w-2/5 h-12 mb-2 bg-transparent">
                <div class="flex w-full h-full relative">
                    <div class="flex absolute z-10 mt-2">
                        <span
                            class="flex items-center leading-normal bg-transparent rounded-lg  border-0  border-none lg:px-3 p-2 whitespace-no-wrap">
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
            {{--
            <!-- COUNT -->
            <div class="inline-flex w-1/3 sm:w-1/4 h-12 md:mx-3 mb-2 bg-transparent">
                <select wire:model="perPage" id=""
                    class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                    <option value="10"> 10 por página</option>
                    <option value="25"> 25 por página</option>
                    <option value="50"> 50 por página</option>
                    <option value="100"> 100 por página</option>
                </select>
            </div> --}}
            <!-- BTN NEW -->
            <div class="inline-flex px-2 md:px-0  w-1/2 md:w-1/4 h-12 bg-transparent mb-2">
                <button wire:click="modalCreateEdit()" class="btnNuevo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    <span>Cliente</span>
                </button>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="w-full whitespace-no-wrap table table-hover ">
                <thead class="border-0 headTable">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="w-8 px-4 py-3">Estatus</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr class="trTable">
                        <td class="px-4 py-1">{{ $customer->name }}</td>
                        <td class="px-4 py-1">
                            <div name="deleted_at" id="deleted_at"
                                class="inpSelectTable inpSelectTable @if ($customer->deleted_at == null) bg-lime-700 text-white @else bg-red-600 text-white @endif w-28 text-sm font-semibold">
                                @if ($customer->deleted_at == null)
                                    <option selected>Activo</option>
                                @else
                                    <option selected>Inactivo</option>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-1 flex justify-center">
                            <div class="flex justify-center">
                                <div id="dropdown-button-{{ $customer->id }}" class="relative">
                                    <button onclick="toggleDropdown('{{ $customer->id }}')" type="button"
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
                                    <div id="dropdown-panel-{{ $customer->id }}" style="display: none;"
                                        class="absolute right-10 mt-2 w-32 rounded-md bg-gray-200 z-10 {{ $loop->last ? '-top-16' : 'top-3' }}">
                                        <!-- Botón Restaurar -->
                                        <div wire:click="$emit('restartItem',{{ $customer->id }})"
                                            class="@if($customer->deleted_at == null) hidden @endif flex content-center px-4 py-2 text-sm text-black cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-reload mr-2" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747">
                                                </path>
                                                <path d="M20 4v5h-5"></path>
                                            </svg>
                                            Restaurar
                                        </div>
                                        @if($customer->deleted_at == null)
                                        <!-- Botón Editar -->
                                        <div wire:click="showUpdate({{$customer->id}})"
                                            class="flex content-center px-4 py-2 text-sm text-black cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-edit mr-2" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                </path>
                                                <path
                                                    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                </path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                            Editar
                                        </div>
                                        <!-- Botón Eliminar -->
                                        <div wire:click="$emit('deleteItem',{{ $customer->id }})"
                                            class="flex content-center px-4 py-2 text-sm text-red-600 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-trash mr-2" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
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
        <div class="py-5">
            {{ $customers->links() }}
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL EDIT / CREATE ACTIVITY --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalCreateEdit) block  @else hidden @endif">
        <div
            class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500">
        </div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full fixed">
            <div class="flex flex-col md:w-2/5 mx-auto rounded-lg   overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100 text-white rounded-tl-lg rounded-tr-lg">
                    @if($showUpdate)
                    <h3
                        class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">
                        Editar cliente</h3>
                    @else
                    <h3
                        class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">
                        Crear cliente</h3>
                    @endif
                    <svg wire:click="modalCreateEdit" class="w-6 h-6 my-2 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    <div class="w-full md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="mx-3">
                            <div class="w-full flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="description">
                                    Nombre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='name' required type="text" placeholder="Nombre" name="name" id="name"
                                    class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('name')
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
                    @if($showUpdate)
                    <button class="btnSave" wire:click="update({{ $customerEdit->id }})"> Guardar </button>
                    @else
                    <button class="btnSave" wire:click="create">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
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
    @push('js')
    <script>
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
        // AVISOS
        window.addEventListener('swal:modal', event => {
            toastr[event.detail.type](event.detail.text, event.detail.title);
        });
        // MODALS
        Livewire.on('deleteItem', deletebyId => {
            Swal.fire({
                title: '¿Seguro que deseas eliminar este elemento?',
                text: "Esta acción es irreversible",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#202a33',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('destroy', deletebyId);
                    // Swal.fire(
                    //   '¡Eliminado!',
                    //   'Tu elemento ha sido eliminado.',
                    //   'Exito'
                    // )
                }
            })
        });

        Livewire.on('restartItem', restartbyId => {
            Swal.fire({
                title: '¿Deseas restaurar este elemento?',
                text: "Esta acción es irreversible",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#202a33',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Restaurar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('restore', restartbyId);
                    // Swal.fire(
                    //   '¡Eliminado!',
                    //   'Tu elemento ha sido eliminado.',
                    //   'Exito'
                    // )
                }
            })
        });
    </script>
    @endpush
</div>