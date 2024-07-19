<div>
    @php
        $user = DB::table('users')
            ->where('id', Auth::user()->id)
            ->first();
    @endphp
    {{-- Tabla usuarios --}}
    <div class="px-4 py-4 sm:rounded-lg">
        {{-- NAVEGADOR --}}
        <div class="flex flex-wrap justify-between text-sm lg:text-base">
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
                    <input wire:model="search" type="text" placeholder="Buscar" class="inputs"
                        style="padding-left: 3em;">
                </div>
            </div>
            <!-- BTN PRIORITY -->
            @if ($user->type_user != 3)
                <div class="inline-flex h-12 w-1/2 bg-transparent px-2 md:w-1/4 md:px-0">
                    <button wire:click="showProjectPriority()" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-star mr-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z" />
                        </svg>
                        <span>Prioridades</span>
                    </button>
                </div>
            @endif
            <!-- BTN NEW -->
            @if ($user->type_user == 1)
                <div class="inline-flex h-12 w-1/2 bg-transparent px-2 md:w-1/4 md:px-0">
                    <button wire:click="modalCreateEdit()" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <span>Proyecto</span>
                    </button>
                </div>
            @endif
        </div>
        {{-- END NAVEGADOR --}}
        {{-- PESTAÑAS --}}
        @if ($user->type_user != 3)
            <nav class="mt-5 flex">
                <button wire:click="setActiveTab('Activo')"
                    class="text-secundaryColor border-secondary @if ($activeTab === 'Activo') bg-gray-200 @endif mx-2 w-40 cursor-pointer border py-1 font-semibold">
                    Activos
                </button>
                <button wire:click="setActiveTab('No activo')"
                    class="text-secundaryColor @if ($activeTab === 'No activo') bg-gray-200 @endif mx-2 w-40 cursor-pointer border border-gray-500 py-1 font-semibold">
                    No activos
                </button>
                <button wire:click="setActiveTab('Entregado')"
                    class="text-secundaryColor @if ($activeTab === 'Entregado') bg-gray-200 @endif mx-2 w-40 cursor-pointer border border-lime-700 py-1 font-semibold">
                    Entregados
                </button>
                <button wire:click="setActiveTab('Cerrado')"
                    class="text-secundaryColor @if ($activeTab === 'Cerrado') bg-gray-200 @endif mx-2 w-40 cursor-pointer border border-red-600 py-1 font-semibold">
                    Cerrados
                </button>
            </nav>
        @endif
        {{-- END PESTAÑAS --}}
        {{-- TABLE --}}
        <div class="tableStyle">
            @if ($user->type_user != 3)
                <div class="text-text1 bg-primaryColor w-full px-4 pt-2 text-xl font-semibold tracking-wide">
                    @if ($activeTab == 'Activo')
                        <h2>Activos</h2>
                    @endif
                    @if ($activeTab == 'No activo')
                        <h2>No activos</h2>
                    @endif
                    @if ($activeTab == 'Entregado')
                        <h2>Entregados</h2>
                    @endif
                    @if ($activeTab == 'Cerrado')
                        <h2>Cerrados</h2>
                    @endif
                </div>
            @endif
            <table class="whitespace-no-wrap table-hover table w-full">
                <thead class="headTable border-0">
                    <tr class="@if ($user->type_user != 3) text-left @else text-center @endif">
                        @if ($user->type_user != 3)
                            <th class="w-16 px-4 py-3">Prioridad</th>
                        @endif
                        <th class="@if ($user->type_user != 3) w-1/4 @endif px-4 py-3">Proyecto</th>
                        @if ($user->type_user != 3)
                            <th class="w-32 px-4 py-3">Cliente</th>
                            <th class="px-4 py-3">Líder y Product Owner</th>
                            @if ($user->type_user == 1)
                                <th class="w-8 px-4 py-3">Estatus</th>
                            @endif
                        @endif
                        <th class="@if ($user->type_user != 3) w-2 @endif px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr class="trTable">
                            @if ($user->type_user != 3)
                                <th class="px-4 py-2">
                                    <a wire:click="showPriority({{ $project->id }})"
                                        class="hover:text-secondary cursor-pointer p-3 hover:text-xl">K{{ $project->priority }}</a>
                                </th>
                            @endif
                            <td class="px-4 py-2">
                                <div
                                    class="@if ($user->type_user != 3) text-justify @else text-center @endif mx-auto">
                                    <span class="font-semibold">{{ $project->name }} @if (Auth::user()->area_id == 1)
                                            - {{ $project->code }}
                                        @endif
                                    </span>
                                </div>
                            </td>
                            @if ($user->type_user != 3)
                                <td class="px-4 py-2">
                                    <div class="mx-auto text-left">
                                        <span class="font-bold">{{ $project->customer_name }}</span><br>
                                    </div>
                                </td>
                                <td class="principal px-4 py-2">
                                    <div class="mx-auto text-left">
                                        - {{ $project->leader->name }}<br>
                                        - {{ $project->product_owner->name }}
                                    </div>
                                    <div class="relative">
                                        <div
                                            class="hidden-info absolute -top-20 left-36 z-10 w-60 bg-gray-100 p-2 text-left text-xs">
                                            <p>
                                                <strong>Líder/Scrum:</strong>
                                                {{ $project->leader->name }}
                                            </p>
                                            <p>
                                                <strong>Product Owner</strong>
                                                {{ $project->product_owner->name }}
                                            </p>
                                            <p>
                                                <strong>Developer 1:</strong>
                                                @if ($project->developer1)
                                                    {{ $project->developer1->name }}
                                                @else
                                                    Sin asignar
                                                @endif
                                            </p>
                                            <p>
                                                <strong>Developer 2:</strong>
                                                @if ($project->developer2)
                                                    {{ $project->developer2->name }}
                                                @else
                                                    Sin asignar
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                @if ($user->type_user == 1)
                                    <td class="px-4 py-1">
                                        <div name="deleted_at" id="deleted_at"
                                            class="inpSelectTable inpSelectTable @if ($project->deleted_at == null) bg-lime-700 text-white @else bg-red-600 text-white @endif text-sm font-semibold">
                                            @if ($project->deleted_at == null)
                                                <option selected>Activo</option>
                                            @else
                                                <option selected>Inactivo</option>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            @endif
                            <td
                                class="@if ($user->type_user != 3) justify-end @else justify-center @endif flex px-4 py-2">
                                @if ($project->deleted_at == null)
                                    @if ($project->backlog != null)
                                        @if ($user->type_user != 3)
                                            <button wire:click="showActivities({{ $project->id }})"
                                                class="mx-1 mt-1 rounded-lg bg-yellow-500 px-2 py-1 font-bold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                    <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                    <path d="M3 6l0 13" />
                                                    <path d="M12 6l0 13" />
                                                    <path d="M21 6l0 13" />
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                    <button wire:click="showReports({{ $project->id }})"
                                        class="bg-secondary mx-1 mt-1 rounded-lg px-2 py-1 font-bold text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-bug" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                            <path d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                            <path d="M3 13l4 0" />
                                            <path d="M17 13l4 0" />
                                            <path d="M12 20l0 -6" />
                                            <path d="M4 19l3.35 -2" />
                                            <path d="M20 19l-3.35 -2" />
                                            <path d="M4 7l3.75 2.4" />
                                            <path d="M20 7l-3.75 2.4" />
                                        </svg>
                                    </button>
                                @endif
                                @if ($user->type_user == 1 || Auth::user()->area_id == 1)
                                    <div class="justify-righ flex">
                                        <div id="dropdown-button-{{ $project->id }}" class="relative">
                                            <button onclick="toggleDropdown('{{ $project->id }}')" type="button"
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
                                            <div id="dropdown-panel-{{ $project->id }}" style="display: none;"
                                                class="@if ($user->type_user != 3) @if ($activeTab == 'Activo') top-3 @else {{ $loop->last ? '-top-16' : 'top-3' }} @endif @endif absolute right-10 z-10 mt-2 w-32 rounded-md bg-gray-200">
                                                <!-- Botón Restaurar -->
                                                <div wire:click="$emit('restartItem',{{ $project->id }})"
                                                    class="@if ($project->deleted_at == null) hidden @endif flex cursor-pointer content-center px-4 py-2 text-sm text-black">
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
                                                @if ($project->deleted_at == null)
                                                    <!-- Botón Editar -->
                                                    <div wire:click="showUpdate({{ $project->id }})"
                                                        class="flex cursor-pointer content-center px-4 py-2 text-sm text-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit mr-2"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                            </path>
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
                                                    <!-- Botón Eliminar -->
                                                    <div wire:click="$emit('deleteItem',{{ $project->id }})"
                                                        class="@if ($user->type_user == 1) flex @else hidden @endif cursor-pointer content-center px-4 py-2 text-sm text-red-600">
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
                                                        Eliminar
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($user->type_user != 3)
                @if ($activeTab == 'Activo')
                    <div class="text-text1 bg-primaryColor w-full px-4 pt-2 text-xl font-semibold tracking-wide">
                        <h2>Soportes</h2>
                    </div>
                    <table class="whitespace-no-wrap table-hover table w-full">
                        <thead class="headTable border-0">
                            <tr class="text-left">
                                <th class="w-16 px-4 py-3">Prioridad</th>
                                <th class="w-1/4 px-4 py-3">Proyecto</th>
                                <th class="w-32 px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Líder y Product Owner</th>
                                @if ($user->type_user == 1)
                                    <th class="w-8 px-4 py-3">Estatus</th>
                                @endif
                                <th class="w-2 px-4 py-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projectsSoporte as $project)
                                <tr class="trTable">
                                    <th class="px-4 py-2">
                                        <a wire:click="showPriority({{ $project->id }})"
                                            class="hover:text-secondary cursor-pointer p-3 hover:text-xl">K{{ $project->priority }}</a>
                                    </th>
                                    <td class="px-4 py-2">
                                        <div class="mx-auto text-justify">
                                            <span class="font-semibold">{{ $project->name }} @if (Auth::user()->area_id == 1)
                                                    - {{ $project->code }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="mx-auto text-left">
                                            <span class="font-bold">{{ $project->customer_name }}</span><br>
                                        </div>
                                    </td>
                                    <td class="principal px-4 py-2">
                                        <div class="mx-auto text-left">
                                            - {{ $project->leader->name }}<br>
                                            - {{ $project->product_owner->name }}
                                        </div>
                                        <div class="relative">
                                            <div
                                                class="hidden-info absolute -top-20 left-36 z-10 w-60 bg-gray-100 p-2 text-left text-xs">
                                                <p>
                                                    <strong>Líder/Scrum:</strong>
                                                    {{ $project->leader->name }}
                                                </p>
                                                <p>
                                                    <strong>Product Owner</strong>
                                                    {{ $project->product_owner->name }}
                                                </p>
                                                <p>
                                                    <strong>Developer 1:</strong>
                                                    @if ($project->developer1)
                                                        {{ $project->developer1->name }}
                                                    @else
                                                        Sin asignar
                                                    @endif
                                                </p>
                                                <p>
                                                    <strong>Developer 2:</strong>
                                                    @if ($project->developer2)
                                                        {{ $project->developer2->name }}
                                                    @else
                                                        Sin asignar
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($user->type_user == 1)
                                        <td class="px-4 py-1">
                                            <div name="deleted_at" id="deleted_at"
                                                class="inpSelectTable inpSelectTable @if ($project->deleted_at == null) bg-lime-700 text-white @else bg-red-600 text-white @endif text-sm font-semibold">
                                                @if ($project->deleted_at == null)
                                                    <option selected>Activo</option>
                                                @else
                                                    <option selected>Inactivo</option>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                    <td class="flex justify-end px-4 py-2">
                                        @if ($project->deleted_at == null)
                                            @if ($project->backlog != null)
                                                <button wire:click="showActivities({{ $project->id }})"
                                                    class="mx-1 mt-1 rounded-lg bg-yellow-500 px-2 py-1 font-bold text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                                        <path d="M3 6l0 13" />
                                                        <path d="M12 6l0 13" />
                                                        <path d="M21 6l0 13" />
                                                    </svg>
                                                </button>
                                            @endif
                                            <button wire:click="showReports({{ $project->id }})"
                                                class="bg-secondary mx-1 mt-1 rounded-lg px-2 py-1 font-bold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-bug" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 9v-1a3 3 0 0 1 6 0v1" />
                                                    <path
                                                        d="M8 9h8a6 6 0 0 1 1 3v3a5 5 0 0 1 -10 0v-3a6 6 0 0 1 1 -3" />
                                                    <path d="M3 13l4 0" />
                                                    <path d="M17 13l4 0" />
                                                    <path d="M12 20l0 -6" />
                                                    <path d="M4 19l3.35 -2" />
                                                    <path d="M20 19l-3.35 -2" />
                                                    <path d="M4 7l3.75 2.4" />
                                                    <path d="M20 7l-3.75 2.4" />
                                                </svg>
                                            </button>
                                        @endif
                                        @if ($user->type_user == 1 || Auth::user()->area_id == 1)
                                            <div class="justify-righ flex">
                                                <div id="dropdown-button-{{ $project->id }}" class="relative">
                                                    <button onclick="toggleDropdown('{{ $project->id }}')"
                                                        type="button" class="flex items-center px-5 py-2.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-dots-vertical"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                        </svg>
                                                    </button>
                                                    <!-- Panel -->
                                                    <div id="dropdown-panel-{{ $project->id }}"
                                                        style="display: none;"
                                                        class="{{ $loop->last ? '-top-16' : 'top-3' }} absolute right-10 z-10 mt-2 w-32 rounded-md bg-gray-200">
                                                        <!-- Botón Restaurar -->
                                                        <div wire:click="$emit('restartItem',{{ $project->id }})"
                                                            class="@if ($project->deleted_at == null) hidden @endif flex cursor-pointer content-center px-4 py-2 text-sm text-black">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-reload mr-2"
                                                                width="24" height="24" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                </path>
                                                                <path
                                                                    d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747">
                                                                </path>
                                                                <path d="M20 4v5h-5"></path>
                                                            </svg>
                                                            Restaurar
                                                        </div>
                                                        @if ($project->deleted_at == null)
                                                            <!-- Botón Editar -->
                                                            <div wire:click="showUpdate({{ $project->id }})"
                                                                class="flex cursor-pointer content-center px-4 py-2 text-sm text-black">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-edit mr-2"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none">
                                                                    </path>
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
                                                            <!-- Botón Eliminar -->
                                                            <div wire:click="$emit('deleteItem',{{ $project->id }})"
                                                                class="@if ($user->type_user == 1) flex @else hidden @endif cursor-pointer content-center px-4 py-2 text-sm text-red-600">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-trash mr-2"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none">
                                                                    </path>
                                                                    <path d="M4 7l16 0"></path>
                                                                    <path d="M10 11l0 6"></path>
                                                                    <path d="M14 11l0 6"></path>
                                                                    <path
                                                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                                                    </path>
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                                                    </path>
                                                                </svg>
                                                                Eliminar
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL EDIT / CREATE PROJECT --}}
    <div
        class="@if ($modalCreateEdit) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
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
                            Editar</h3>
                    @else
                        <h3
                            class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                            Crear</h3>
                    @endif
                    <svg wire:click="modalCreateEdit" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    {{-- PROJECT --}}
                    <div class="md-3/4 mb-5 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
                        <div
                            class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                            <h4
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                Proyecto</h4>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="code">
                                    Código <p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='code' required type="number" placeholder="Código" name="code"
                                    id="code" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('code')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full flex-col px-3">
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
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Estatus @if (!$showUpdate)
                                        <p class="text-red-600">*</p>
                                    @endif
                                </h5>
                                @if ($showUpdate)
                                    <select wire:model='type' required name="type" id="type" class="inputs">
                                        @foreach ($allType as $type)
                                            <option value='{{ $type }}'>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='type' required name="type" id="type" class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allType as $type)
                                            <option value='{{ $type }}'>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('type')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Cliente @if (!$showUpdate)
                                        <p class="text-red-600">*</p>
                                    @endif
                                </h5>
                                @if ($showUpdate)
                                    <select wire:model='customer' name="customer" id="customer" class="inputs">
                                        @foreach ($allCustomers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='customer' required name="customer" id="customer"
                                        class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allCustomers as $allCustomer)
                                            <option value="{{ $allCustomer->id }}">{{ $allCustomer->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('customer')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Líder @if (!$showUpdate)
                                        <p class="text-red-600">*</p>
                                    @endif
                                </h5>
                                @if ($showUpdate)
                                    <select wire:model='leader' required name="leader" id="leader"
                                        class="inputs">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}"
                                                @if ($user->id == $leader) selected @endif>{{ $user->name }}
                                                {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='leader' required name="leader" id="leader"
                                        class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('leader')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Product Owner @if (!$showUpdate)
                                        <p class="text-red-600">*</p>
                                    @endif
                                </h5>
                                @if ($showUpdate)
                                    <select wire:model='product_owner' required name="product_owner"
                                        id="product_owner" class="inputs">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}"
                                                @if ($user->id == $product_owner) selected @endif>{{ $user->name }}
                                                {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='product_owner' required name="product_owner"
                                        id="product_owner" class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('product_owner')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if (!$showUpdate)
                            <div
                                class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                                <h4
                                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                    Prioridad</h4>
                            </div>
                            <div class="-mx-3 mb-6">
                                <div class="mb-6 flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Gravedad<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointKnow" id="pointKnow" class="inputs" wire:model='severity'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - No afecta de ninguna manera</option>
                                        <option value="6">Bajo - Se le queda mal al cliente</option>
                                        <option value="18">Alto - Daña la posibilidad de trabajar con el cliente
                                        </option>
                                        <option value="24">Crítico - Se pierde la relación con el cliente</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('severity')
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
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Impacto<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs" wire:model='impact'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - No tiene beneficio</option>
                                        <option value="10">Medio - Representa beneficio sin impacto</option>
                                        <option value="15">Alto - Representa beneficio con impacto</option>
                                        <option value="20">Crítico - Para no tener perdidas</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('impact')
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
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Satisfacción<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs"
                                        wire:model='satisfaction'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - No afecta en ninguna manera</option>
                                        <option value="6">Bajo - Entregamos más de los esperado</option>
                                        <option value="12">Medio - Mantenemos relación</option>
                                        <option value="18">Alto - Abre la posibilidad a más proyectos</option>
                                        <option value="24">Crítico- Ganamos un nuevo cliente / generamos lealtad
                                        </option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('satisfaction')
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
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Temporalidad<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs" wire:model='temporality'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - Indefinido</option>
                                        <option value="4">2 o más veces el tiempo que se requiere</option>
                                        <option value="12">Tiempo exacto para hacerlo</option>
                                        <option value="16">Menos del tiempo que se requiere para hacer</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('temporality')
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
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Magnitud<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs" wire:model='magnitude'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - 1 persona</option>
                                        <option value="2">Bajo - Departamento</option>
                                        <option value="6">Alto - Gente externa</option>
                                        <option value="8">Crítico - Cliente</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('magnitude')
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
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Estrategia<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs" wire:model='strategy'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Cero - No se alinea en ninguna manera</option>
                                        <option value="6">Medio - Es lo que hacemos</option>
                                        <option value="9">Alto - Nos ayuda a avanzar</option>
                                        <option value="12">Crítico - Nos lleva a donde queremos llegar</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('strategy')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 mb-6">
                                <div class="flex w-full flex-col px-3">
                                    <h5 class="inline-flex font-semibold" for="name">
                                        Etapa<p class="text-red-600">*</p>
                                    </h5>
                                    <select name="pointMany" id="pointMany" class="inputs" wire:model='stage'>
                                        <option selected>Selecciona...</option>
                                        <option value="0">Iniciativa - Idea que se empieza a desarrollar que no
                                            tiene
                                            el cliente</option>
                                        <option value="1">Actualización - Cambios que no pide el cliente, que
                                            Kircof
                                            propone</option>
                                        <option value="2">Cambios - Cambios por decisión del cliente</option>
                                        <option value="3">Correción - Cambios por error interno</option>
                                        <option value="4">Nuevo - Primera vez que se hace</option>
                                    </select>
                                    <div>
                                        <span class="text-xs italic text-red-600">
                                            @error('stage')
                                                <span class="pl-2 text-xs italic text-red-600">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- BACKLOG --}}
                    <div class="w-full px-5 lg:w-1/2">
                        <div
                            class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                            <h4
                                class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                                Backlog</h4>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Objetivo general<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='general_objective' required type="text"
                                    placeholder="Objetivo general" name="general_objective" id="general_objective"
                                    class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('general_objective')
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
                                <h5 class="inline-flex font-semibold" for="code">
                                    Alcances<p class="text-red-600">*</p>
                                </h5>
                                @if ($showUpdate && $backlogEdit)
                                    @foreach ($backlogEdit->files as $file)
                                        <div class="center my-auto flex items-center justify-end py-3">
                                            <label for="backlogFile{{ $file->id }}"
                                                class="mr-2 text-red-600">Eliminar</label>
                                            <input type="checkbox" id="backlogFile{{ $file->id }}"
                                                class="delete-checkbox border-gray-300 bg-transparent"
                                                style="height: 24px; width: 24px; accent-color: #2e4c5f;"
                                                wire:model="selectedFiles" value="{{ $file->id }}">
                                        </div>
                                        <img src="{{ asset('backlogs/' . $file->route) }}" alt="Backlog Image">
                                    @endforeach
                                @endif
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
                                <textarea wire:model='scopes' required type="text" rows="6" placeholder="Escriba los alcances."
                                    name="scopes" id="scopes" class="textarea"></textarea>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('scopes')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        @error('file')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Fecha de inicio<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='start_date' required type="date" placeholder="Nombre"
                                    name="start_date" id="start_date" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('start_date')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Fecha de cierre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='closing_date' required type="date" placeholder="Nombre"
                                    name="closing_date" id="closing_date" class="inputs">
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('closing_date')
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
                                <h5 class="inline-flex font-semibold" for="code">
                                    Claves de acceso
                                </h5>
                                <textarea wire:model='passwords' required type="text" rows="6" placeholder="Accesos de sistema."
                                    name="passwords" id="passwords" class="textarea"></textarea>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('passwords')
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
                    @if ($showUpdate)
                        <button class="btnSave" wire:click="update({{ $projectEdit->id }})">
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
    {{-- END MODAL EDIT / CREATE PROJECT --}}
    {{-- MODAL EDIT / CREATE PRIORITY --}}
    <div
        class="@if ($modalPriority) block  @else hidden @endif left-0 top-20 z-50 max-h-full overflow-y-auto">
        <div
            class="fixed left-0 top-0 z-30 flex h-screen w-full items-center justify-center bg-gray-500 bg-cover bg-no-repeat opacity-80">
        </div>
        <div class="text:md fixed left-0 top-0 z-40 flex h-screen w-full items-center justify-center">
            <div class="mx-auto flex flex-col overflow-y-auto rounded-lg md:w-2/5" style="max-height: 90%;">
                <div
                    class="flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-6 py-4 text-white">
                    <h3
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-xl font-medium">
                        Editar prioridad @if ($showPriority)
                            "{{ $projectPriority->name }}"@endif
                    </h3>
                    <svg wire:click="modalPriority()" class="my-2 h-6 w-6 cursor-pointer text-black hover:stroke-2"
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    <div class="md-3/4 mb-5 flex w-full flex-col px-5 md:mb-0">
                        <div class="-mx-3 mb-6">
                            <div class="mb-6 flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Gravedad<p class="text-red-600">*</p>
                                </h5>
                                <select name="severity" id="severity" class="inputs" wire:model='severity'>
                                    <option value="0">Cero - No afecta de ninguna manera</option>
                                    <option value="6">Bajo - Se le queda mal al cliente</option>
                                    <option value="18">Alto - Daña la posibilidad de trabajar con el cliente
                                    </option>
                                    <option value="24">Crítico - Se pierde la relación con el cliente</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('severity')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Impacto<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='impact'>
                                    <option value="0">Cero - No tiene beneficio</option>
                                    <option value="10">Medio - Representa beneficio sin impacto</option>
                                    <option value="15">Alto - Representa beneficio con impacto</option>
                                    <option value="20">Crítico - Para no tener perdidas</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('impact')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Satisfacción<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='satisfaction'>
                                    <option value="0">Cero - No afecta en ninguna manera</option>
                                    <option value="6">Bajo - Entregamos más de los esperado</option>
                                    <option value="12">Medio - Mantenemos relación</option>
                                    <option value="18">Alto - Abre la posibilidad a más proyectos</option>
                                    <option value="24">Crítico- Ganamos un nuevo cliente / generamos lealtad
                                    </option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('satisfaction')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Temporalidad<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='temporality'>
                                    <option value="0">Cero - Indefinido</option>
                                    <option value="4">2 o más veces el tiempo que se requiere</option>
                                    <option value="12">Tiempo exacto para hacerlo</option>
                                    <option value="16">Menos del tiempo que se requiere para hacer</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('temporality')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Magnitud<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='magnitude'>
                                    <option value="0">Cero - 1 persona</option>
                                    <option value="2">Bajo - Departamento</option>
                                    <option value="6">Alto - Gente externa</option>
                                    <option value="8">Crítico - Cliente</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('magnitude')
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Estrategia<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='strategy'>
                                    <option value="0">Cero - No se alinea en ninguna manera</option>
                                    <option value="6">Medio - Es lo que hacemos</option>
                                    <option value="9">Alto - Nos ayuda a avanzar</option>
                                    <option value="12">Crítico - Nos lleva a donde queremos llegar</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('strategy')
                                            <span class="pl-2 text-xs italic text-red-600">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="flex w-full flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Etapa<p class="text-red-600">*</p>
                                </h5>
                                <select name="pointMany" id="pointMany" class="inputs" wire:model='stage'>
                                    <option value="0">Iniciativa - Idea que se empieza a desarrollar que no tiene
                                        el cliente</option>
                                    <option value="1">Actualización - Cambios que no pide el cliente, que Kircof
                                        propone</option>
                                    <option value="2">Cambios - Cambios por decisión del cliente</option>
                                    <option value="3">Correción - Cambios por error interno</option>
                                    <option value="4">Nuevo - Primera vez que se hace</option>
                                </select>
                                <div>
                                    <span class="text-xs italic text-red-600">
                                        @error('stage')
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
                    @if ($showPriority)
                        <button class="btnSave" wire:click="updatePriority({{ $projectPriority->id }})">
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
                    @else
                        <button class="btnSave" wire:click="modalPriority">
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
    {{-- END MODAL EDIT / CREATE PRIORITY --}}
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="showProjectPriority,modalCreateEdit, showReports, showActivities, showUpdate, modalCreateEdit, addInput, removeInput, update, create, modalPriority, updatePriority">
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
            // INPUTS FILES RESET
            window.addEventListener('file-reset', () => {
                document.querySelectorAll('.files').value = null;
            });
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
