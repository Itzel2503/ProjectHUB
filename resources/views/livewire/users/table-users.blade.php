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
            <!-- COUNT -->
            {{-- <div class="inline-flex w-1/3 sm:w-1/4 h-12 md:mx-3 mb-2 bg-transparent">
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
                    <span>Usuario</span>
                </button>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="w-full whitespace-no-wrap table table-hover ">
                <thead class="border-0 headTable">
                    <tr>
                        <th></th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Apellidos </th>
                        <th class="px-4 py-3">Correo electónico</th>
                        <th class="px-4 py-3">Área</th>
                        <th class="px-4 py-3">Tipo Usuario</th>
                        <th class="w-8 px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="trTable">
                        <td class="px-4 py-1 flex justify-center">
                            @if ($user->profile_photo)
                            <img class="h-14 w-14 rounded-full object-cover mx-auto" aria-hidden="true"
                                src="{{ asset('usuarios/' . $user->profile_photo) }}" alt="Avatar" />
                            @else
                            <img class="h-14 w-14 rounded-full object-cover mx-auto" aria-hidden="true"
                                src="{{ Avatar::create($user->name)->toBase64() }}" alt="Avatar" />
                            @endif
                        </td>
                        <td class="px-4 py-1">{{$user->name}}</td>
                        <td class="px-4 py-1">{{$user->lastname}}</td>
                        <td class="px-4 py-1">{{$user->email}}</td>
                        <td class="px-4 py-1">{{$user->area_name}}</td>
                        <td class="px-4 py-1">{{($user->type_user == 1 ) ? 'Administrador' : 'Usuario'}}</td>
                        <td class="px-4 py-1">
                            <div class="flex justify-center">
                                <div id="dropdown-button-{{ $user->id }}" class="relative">
                                    <button onclick="toggleDropdown('{{ $user->id }}')" type="button"
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
                                    <div id="dropdown-panel-{{ $user->id }}" style="display: none;"
                                        class="absolute right-10 top-3 mt-2 w-32 rounded-md bg-gray-200 z-10">
                                        <!-- Botón Restaurar -->
                                        <div wire:click="$emit('restartItem',{{ $user->id }})"
                                            class="@if($user->deleted_at == null) hidden @endif flex content-center px-4 py-2 text-sm text-black cursor-pointer">
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
                                        @if($user->deleted_at == null)
                                        <!-- Botón Editar -->
                                        <div wire:click="showUpdate({{$user->id}})"
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
                                        <div wire:click="$emit('deleteItem',{{ $user->id }})"
                                            class="@if(Auth::user()->type_user == 1)flex @else hidden @endif content-center px-4 py-2 text-sm text-red-600 cursor-pointer">
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
            {{ $users->links() }}
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
                        Editar usuario</h3>
                    @else
                    <h3
                        class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">
                        Crear usuario</h3>
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
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="description">
                                    Foto de perfil
                                </h5>
                                <input wire:model='file' required type="file" placeholder="Título" name="file" id="file"
                                    class="inputs">
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
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="file">
                                    Nombre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='name' required type="text" placeholder="Nombre/s" name="name"
                                    id="name" class="inputs">
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
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="lastname">
                                    Apellidos<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='lastname' required type="text" name="lastname"
                                    placeholder="Apellidos" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('lastname')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="date_birthday">
                                    Fecha de nacimiento @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                <input wire:model='date_birthday' required type="date" name="date_birthday"
                                    id="date_birthday" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('date_birthday')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="phone">
                                    Número de teléfono<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='phone' required type="number" name="phone" placeholder="444 444 4444"
                                    class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('phone')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="date_birthday">
                                    Área @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
                                <select wire:model.defer='area' name="area" id="area" class="inputs">
                                    <option selected value="{{ $areaUser->id }}">{{ $areaUser->name }}</option>
                                    @foreach ($allAreas as $allArea)
                                    <option value="{{ $allArea->id }}">{{ $allArea->name }}</option>
                                    @endforeach
                                </select>
                                @else
                                <select wire:model.defer='area' required name="area" id="area" class="inputs">
                                    <option selected>Selecciona...</option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <div>
                                    <span class="text-red-600  text-xs italic">
                                        @error('area')
                                        <span class="pl-2 text-red-600  text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="phone">
                                    Tipo de usuario @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
                                <select wire:model.defer='type_user' name="type_user" id="type_user" class="inputs">
                                    @foreach ($allTypes as $allType)
                                    <option value="{{ $allType }}">{{($allType== 1 ) ? 'Administrador' : 'Usuario'}}
                                    </option>
                                    @endforeach
                                </select>
                                @else
                                <select wire:model.defer='type_user' required name="type_user" id="type_user"
                                    class="inputs">
                                    <option selected>Selecciona...</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Usuario</option>
                                </select>
                                @endif
                                <div>
                                    <span class="text-red-600  text-xs italic">
                                        @error('type_user')
                                        <span class="pl-2 text-red-600  text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="email">
                                    Correo electrónico<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='email' required type="email" placeholder="Correo electrónico"
                                    name="email" id="email" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('email')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="password">
                                    Contraseña @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                <input wire:model='password' required type="text" placeholder="Nueva contraseña"
                                    name="password" id="password" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('password')
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
                    <button class="btnSave" wire:click="update({{ $userEdit->id }})"> Guardar </button>
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
        // RECARGAR IMAGEN
        window.addEventListener('refresh', () => {
            setInterval(function() {
                location.reload();
            }, 1000);
        });
        // INPUTS FILES RESET
        window.addEventListener('file-reset', () => {
            document.getElementById('file').value = null;
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