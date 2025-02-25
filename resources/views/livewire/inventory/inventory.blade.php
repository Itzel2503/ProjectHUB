<div>
    {{-- Tabla usuarios --}}
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-col justify-between gap-2 text-sm md:flex-row lg:text-base">
            <div class="flex w-full flex-wrap md:inline-flex md:flex-nowrap">
                <!-- SEARCH -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:w-2/5 md:px-0">
                    <div class="relative flex h-full w-full">
                        <div class="absolute z-10 mt-2 flex">
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
                        <input wire:model="search" type="text" placeholder="Buscar reporte" class="inputs"
                            style="padding-left: 3em;">
                    </div>
                </div>
                <!-- DELEGATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedManager" class="inputs">
                        <option value="">Responsables</option>
                        @foreach ($allUsersFiltered as $userFiltered)
                            <option value="{{ $userFiltered['id'] }}">{{ $userFiltered['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- STATE -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:mx-3 md:w-1/5 md:px-0">
                    <select wire:model.lazy="selectedStates" class="inputs">
                        <option value="">Estados</option>
                        <option value="Uso">Uso</option>
                        <option value="No uso">No uso</option>
                        <option value="Almacenado">Almacenado</option>
                        <option value="Uso externo">Uso externo</option>
                    </select>
                </div>
                <!-- BTN NEW -->
                <div class="mb-2 inline-flex h-12 w-1/2 bg-transparent px-2 md:hidden md:px-0">
                    <button class="btnNuevo"
                        wire:click="$set('isOptionsVisibleCreate', {{ $isOptionsVisibleCreate ? 'false' : 'true' }})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Producto
                    </button>
                </div>
            </div>
            <!-- BTN NEW -->
            <div class="mb-2 hidden h-12 w-1/6 bg-transparent md:inline-flex">
                <button class="btnNuevo"
                    wire:click="$set('isOptionsVisibleCreate', {{ $isOptionsVisibleCreate ? 'false' : 'true' }})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Producto
                </button>
            </div>
        </div>
        {{-- END NAVEGADOR --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="whitespace-no-wrap table-hover table w-full">
                <thead class="headTable border-0">
                    <tr class="text-left">
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Marca</th>
                        <th class="px-4 py-3">Modelo</th>
                        <th class="px-4 py-3">Nº serie</th>
                        <th class="px-4 py-3">Estatus</th>
                        <th class="px-4 py-3">Responsable</th>
                        <th class="px-4 py-3">Departamento</th>
                        <th class="w-8 px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="trTable"  id="product-{{ $product->id }}">
                            <td class="cursor-pointer px-4 py-1" wire:click="show({{ $product->id }})">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-1">{{ $product->brand }}</td>
                            <td class="px-4 py-1">{{ $product->model }}</td>
                            <td class="px-4 py-1">{{ $product->serial_number }}</td>
                            <td class="px-4 py-1 relative">
                                <div name="deleted_at" id="deleted_at" class="inpSelectTable text-sm font-semibold
                                    @if ($product->deleted_at == null) 
                                        @if ($product->status == 'Uso') bg-lime-700 text-white
                                        @elseif ($product->status == 'Uso externo') bg-yellow-400
                                        @elseif ($product->status == 'Almacenado') bg-blue-500 text-white
                                        @elseif ($product->status == 'No uso') bg-slate-900 text-white 
                                        @endif
                                    @else
                                    bg-red-600 text-white
                                    @endif">
                                    {{  ($product->deleted_at == null) ? $product->status : 'Inactivo' ; }}
                                </div>
                            </td>
                            <td class="px-4 py-1">
                                {{ ($product->manager) ? $product->manager->name : 'Usuario eliminado' ; }}
                            </td>
                            <td class="px-4 py-1">{{ $product->area->name }}</td>
                            <td class="px-4 py-1">
                                <div class="flex justify-center">
                                    <div id="dropdown-button-{{ $product->id }}" class="relative">
                                        <button onclick="toggleDropdown('{{ $product->id }}')" type="button"
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
                                        <div id="dropdown-panel-{{ $product->id }}" style="display: none;"
                                            class="{{ $loop->last ? '-top-16' : '-top-8' }} absolute right-10 mt-2 w-32 rounded-md bg-gray-200">
                                            @if ($product->deleted_at == null)
                                                <!-- Botón Editar -->
                                                <div wire:click="edit({{ $product->id }})"
                                                    class="flex cursor-pointer content-center px-4 py-2 text-sm text-black">
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
                                                    <!-- Botón Inhabilitar -->
                                                    <div wire:click="$emit('disableProduct',{{ $product->id }})"
                                                        class="flex cursor-pointer content-center px-4 py-2 text-sm text-red-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-trash mr-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
                                                            <path d="M4 7l16 0"></path>
                                                            <path d="M10 11l0 6"></path>
                                                            <path d="M14 11l0 6"></path>
                                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                            </path>
                                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                        </svg>
                                                        Inhabilitar
                                                    </div>
                                                @endif
                                            @else
                                                <!-- Botón Restaurar -->
                                                <div wire:click="$emit('restartProduct',{{ $product->id }})"
                                                    class="@if ($product->deleted_at == null) hidden @endif flex cursor-pointer content-center px-4 py-2 text-sm text-black">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-reload mr-2"
                                                        width="24" height="24" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747">
                                                        </path>
                                                        <path d="M20 4v5h-5"></path>
                                                    </svg>
                                                    Restaurar
                                                </div>
                                                <!-- Botón Eliminar -->
                                                <div wire:click="$emit('deleteProduct',{{ $product->id }})"
                                                    class="flex cursor-pointer content-center px-4 py-2 text-sm text-red-600">
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
    {{-- MODAL EDIT / CREATE PRODUCT --}}
    @if ($isOptionsVisibleCreate)
        <div class="left-0 top-20 z-50 max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                    <div
                        class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                        @if ($showUpdate)
                            <h3
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                                Editar producto</h3>
                        @else
                            <h3
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                                Agregar producto</h3>
                        @endif
                        <svg wire:click="$set('isOptionsVisibleCreate', {{ $isOptionsVisibleCreate ? 'false' : 'true' }})"
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
                        <div
                            class="md-3/4 mb-5 mt-4 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Nombre<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='name' required type="text" placeholder="Nombre" name="name"
                                        id="name" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('name')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="brand">
                                        Marca<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='brand' required type="text" placeholder="Marca" name="brand"
                                        id="brand" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('brand')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="model">
                                        Modelo<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='model' required type="text" name="model"
                                        placeholder="Modelo" id="model" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('model')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="serial_number">
                                        Número de serie<p class="text-red-600">*</p>
                                    </h5>
                                    <input wire:model='serial_number' required type="text" name="serial_number"
                                        placeholder="Número de serie" id="serial_number" class="inputs">
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('serial_number')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="status">
                                        Estatus<p class="text-red-600">*</p>
                                    </h5>
                                    @if ($showUpdate)
                                        <select wire:model='status' required name="status" id="status"
                                            class="inputs">
                                            <option value="Uso"
                                                {{ $this->productEdit && $this->productEdit->status == 'Uso' ? 'selected' : '' }}>
                                                Uso</option>
                                            <option value="No uso"
                                                {{ $this->productEdit && $this->productEdit->status == 'No uso' ? 'selected' : '' }}>
                                                No uso</option>
                                            <option value="Almacenado"
                                                {{ $this->productEdit && $this->productEdit->status == 'Almacenado' ? 'selected' : '' }}>
                                                Almacenado</option>
                                            <option value="Uso externo"
                                                {{ $this->productEdit && $this->productEdit->status == 'Uso externo' ? 'selected' : '' }}>
                                                Uso externo</option>
                                        </select>
                                    @else
                                        <select wire:model='status' required name="status" id="status"
                                            class="inputs">
                                            <option selected>Selecciona...</option>
                                            <option value="Uso">Uso</option>
                                            <option value="No uso">No uso</option>
                                            <option value="Almacenado">Almacenado</option>
                                            <option value="Uso externo">Uso externo</option>
                                        </select>
                                    @endif
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('status')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="department">
                                        Departamento<p class="text-red-600">*</p>
                                    </h5>
                                    @if ($showUpdate)
                                        <select wire:model='department' name="department" id="department"
                                            class="inputs">
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}"
                                                    {{ $this->productEdit && $this->productEdit->department_id == $area->id ? 'selected' : '' }}>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select wire:model='department' required name="department" id="department"
                                            class="inputs">
                                            <option selected>Selecciona...</option>
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('department')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6 flex flex-row">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="manager">
                                        Responsable<p class="text-red-600">*</p>
                                    </h5>
                                    @if ($showUpdate)
                                        <select wire:model='manager' name="manager" id="manager" class="inputs">
                                            @foreach ($allUsers as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $this->productEdit && $this->productEdit->manager_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select wire:model='manager' required name="manager" id="manager"
                                            class="inputs">
                                            <option selected>Selecciona...</option>
                                            @foreach ($allUsers as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('manager')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="purchase_date">
                                        Fecha de compra
                                    </h5>
                                    <input wire:model='purchase_date' type="date" name="purchase_date"
                                        id="purchase_date" class="inputs">
                                </div>
                            </div>
                            <div class="-mx-3">
                                <div class="flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="observations">
                                        Observaciones
                                    </h5>
                                    <textarea wire:model='observations' type="text" rows="6" placeholder="Escribe las observaciones."
                                        name="observations" id="observations" class="textarea"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 w-full px-5 lg:w-1/2">
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="code">Fotografías</h5>
                                    <div class="flex flex-row">
                                        <span wire:click="addInput"
                                            class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-plus mr-2" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                            Agregar archivo
                                        </span>
                                    </div>
                                    @foreach ($files as $index => $file)
                                        <div class="flex flex-row">
                                            <span wire:click="removeInput({{ $index }})"
                                                class="my-auto cursor-pointer text-red-600">
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
                                            </span>
                                            <input wire:model="files.{{ $index }}" required type="file"
                                                name="files" class="inputs mb-2" multiple>
                                        </div>
                                    @endforeach
                                    @if ($showUpdate)
                                        @foreach ($productEdit->files as $file)
                                            <div class="center my-auto flex items-center justify-end py-3">
                                                <label for="backlogFile{{ $file->id }}"
                                                    class="mr-2 text-red-600">Eliminar</label>
                                                <input type="checkbox" id="backlogFile{{ $file->id }}"
                                                    class="delete-checkbox border-gray-300 bg-transparent"
                                                    style="height: 24px; width: 24px; accent-color: #2e4c5f;"
                                                    wire:model="selectedFiles" value="{{ $file->id }}">
                                            </div>
                                            <img src="{{ asset('inventory/' . $file->route) }}" alt="Backlog Image">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalFooter">
                        @if ($showUpdate)
                            <button class="btnSave" wire:click="update({{ $productEdit->id }})">Guardar</button>
                        @else
                            <button class="btnSave" wire:click="create">
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
    @endif
    {{-- END MODAL EDIT / CREATE PRODUCT --}}
    {{-- MODAL SHOW PRODUCT --}}
    @if ($isOptionsVisibleShow)
        <div id="modalProduct"
            class="left-0 top-20 z-50 max-h-full overflow-y-auto">
            <div
                class="fixed left-0 top-0 z-30 flex h-full w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
            </div>
            <div class="text:md smd:px-0 fixed left-0 top-0 z-40 flex h-full w-full items-center justify-center px-2">
                <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-3/4" style="max-height: 90%;">
                    @if ($showProduct)
                        <div
                            class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                            @if ($productShow)
                                <h3
                                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                                    {{ $productShow->name }}
                                </h3>
                            @else
                                <h3
                                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                                    No se ha seleccionado ningún producto.
                                </h3>
                            @endif
                            <svg wire:click="$set('isOptionsVisibleShow', {{ $isOptionsVisibleShow ? 'false' : 'true' }})"
                            class="h-6 w-6 cursor-pointer text-black hover:stroke-2"
                                xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M18 6l-12 12"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </div>
                    @endif
                    @if ($showProduct)
                        <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
                            <div
                                class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-between border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                                <div class="text-justify text-base">
                                    <h3 class="text-text2 mb-2 text-lg font-bold">Detalles</h3>
                                    @if ($productShow)
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Marca:</label>
                                            <p class="w-1/2">{{ $productShow->brand }}</p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Modelo:</label>
                                            <p class="w-1/2">{{ $productShow->model }}</p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Número de serie:</label>
                                            <p class="w-1/2">{{ $productShow->serial_number }}</p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Estatus:</label>
                                            <p class="w-1/2">{{ $productShow->status }}</p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Fecha de compra:</label>
                                            <p class="w-1/2">
                                                {{ $productShow->purchase_date ? \Carbon\Carbon::parse($productShow->purchase_date)->locale('es')->isoFormat('D[-]MMMM[-]YYYY') : 'Sin fecha' }}
                                            </p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Departamento:</label>
                                            <p class="w-1/2">{{ $productShow->area->name }}</p>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <label class="font-bold">Encargado:</label>
                                            <p class="w-1/2">{{ ($productShow->manager) ? $productShow->manager->name : 'Usuario eliminado' ; }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <p class="font-bold w-full">Observaciones:</p>
                                            {!! nl2br($productShow->observations) !!}
                                        </div>
                                    @else
                                        <div class="my-5 text-center row">
                                            <p class="col-12">Sin datos</p>
                                            <div class="flex justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-database-off">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12.983 8.978c3.955 -.182 7.017 -1.446 7.017 -2.978c0 -1.657 -3.582 -3 -8 -3c-1.661 0 -3.204 .19 -4.483 .515m-2.783 1.228c-.471 .382 -.734 .808 -.734 1.257c0 1.22 1.944 2.271 4.734 2.74" />
                                                    <path
                                                        d="M4 6v6c0 1.657 3.582 3 8 3c.986 0 1.93 -.067 2.802 -.19m3.187 -.82c1.251 -.53 2.011 -1.228 2.011 -1.99v-6" />
                                                    <path
                                                        d="M4 12v6c0 1.657 3.582 3 8 3c3.217 0 5.991 -.712 7.261 -1.74m.739 -3.26v-4" />
                                                    <path d="M3 3l18 18" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div id="example" class="photos w-full px-5 lg:w-1/2">
                                <div class="mb-6 w-auto">
                                    <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                                        <div class="text-text2 text-center text-xl font-semibold md:flex">Imagenes</div>
                                    </div>
                                    @if ($productShow->fileEmpty == false)
                                        <div class="flex">
                                            @foreach ($productShow->files as $file)
                                                @if ($file->contentExists)
                                                    <!-- Mostrar la imagen si el archivo existe -->
                                                    <a class="w-1/2" href="{{ asset('inventory/' . $file->route) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('inventory/' . $file->route) }}"
                                                            alt="Inventory Image">
                                                    </a>
                                                @else
                                                    <div
                                                        class="w-1/2 md-3/4 mb-5 mt-3 flex flex-col items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
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
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="my-5 w-full text-center text-lg">
                                            <p class="text-red my-5">Sin archivo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- END MODAL SHOW PRODUCT  --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="show, edit, update, addInput, create, $set('isOptionsVisibleShow')">
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
            // AVISOS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });
            // INPUTS FILES RESET
            window.addEventListener('file-reset', () => {
                document.querySelectorAll('.files').value = null;
            });
            // DROPDOWN
            function toggleDropdown(productId) {
                var panel = document.getElementById('dropdown-panel-' + productId);
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
            // MODALS
            Livewire.on('disableProduct', disablebyId => {
                Swal.fire({
                    title: '¿Seguro que deseas inhabilitar este elemento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#202a33',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Inhabilitar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('disable', disablebyId);
                    }
                })
            });

            Livewire.on('restartProduct', restartbyId => {
                Swal.fire({
                    title: '¿Deseas restaurar este elemento?',
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

            Livewire.on('deleteProduct', deletebyId => {
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
        </script>
    @endpush
</div>
