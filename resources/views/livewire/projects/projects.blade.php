<div>
    {{--Tabla usuarios--}}
    <div class="sm:rounded-lg px-4 py-4">
        {{-- NAVEGADOR --}}
        <div class="flex flex-wrap justify-between text-sm lg:text-base">
            <!-- SEARCH -->
            <div class="inline-flex px-2 md:px-0  w-1/2 md:w-2/5 h-12 mb-2 bg-transparent">
                <div class="flex w-full h-full relative">
                    <div class="flex absolute z-10 mt-2">
                        <span class="flex items-center leading-normal bg-transparent rounded-lg  border-0  border-none lg:px-3 p-2 whitespace-no-wrap">
                            <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                    <input wire:model="search" type="text" placeholder="Buscar" class="inputs" style="padding-left: 3em;">
                </div>
            </div>
            <!-- COUNT -->
            <div class="inline-flex  px-2 md:px-0  w-1/2 sm:w-1/4 h-12 md:mx-3 mb-2 bg-transparent">
                <select wire:model="perPage" id="" class="inputs">
                    <option value="10"> 10 por página</option>
                    <option value="25"> 25 por página</option>
                    <option value="50"> 50 por página</option>
                    <option value="100"> 100 por página</option>
                </select>
            </div>
            <!-- BTN NEW -->
            @if (Auth::user()->type_user == 1)
                <div class="inline-flex px-2 md:px-0  w-1/2 md:w-1/4 h-12 bg-transparent mb-2">
                    <button wire:click="modalCreateEdit()" class="btnNuevo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        <span>Proyecto</span>
                    </button>
                </div>
            @endif
        </div>
        {{-- END NAVEGADOR --}}

        {{-- TABLE --}}
        <div class="tableStyle">
            <table class="w-full whitespace-no-wrap table table-hover ">
                <thead class="border-0 headTable">
                    <tr class="">
                        {{-- <th class="px-4 py-3"></th> LOGO --}}
                        @if(Auth::user()->area_id == 1)
                        <th class="px-4 py-3">Código</th>
                        @endif
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Proyecto</th>
                        <th class="px-4 py-3">Líder y Scrum Master</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr class="trTable">
                        @if(Auth::user()->area_id == 1)
                        <th class="px-4 py-2">{{ $project->code }}</th>
                        @endif

                        <td class="px-4 py-2">
                            <div class="w-32 mx-auto text-justify">
                                <span class="font-bold">{{ $project->customer_name }}</span><br>
                            </div>
                        </td>

                        <td class="px-4 py-2">
                            <div class="w-36 mx-auto text-justify">
                                <span class="font-semibold">{{ $project->name }} </span> <br>
                            <span class="italic">{{ $project->type }}</span>  - K{{ $project->priority }}
                            </div>
                        </td>

                        <td class="px-4 py-2">
                            <div class="w-36 mx-auto text-justify">
                                - {{ $project->leader->name }} {{ $project->leader->lastname }} <br>
                                - {{ $project->programmer->name }} {{ $project->programmer->lastname }}
                            </div>
                        </td>

                        <td class="px-4 py-2 flex justify-center">
                            @if($project->deleted_at == null)
                            <button wire:click="showReports({{ $project->id }})" class="bg-red-500 text-white font-bold py-1 px-2 mt-1 mx-1 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bug" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                            @if ($project->backlog != null)
                                <button wire:click="showActivities({{ $project->id }})" class="bg-yellow-500 text-white font-bold py-1 px-2 mt-1 mx-1 rounded-lg">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-book">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </button>
                            @endif
                            @endif
                            @if (Auth::user()->type_user == 1 || Auth::user()->area_id == 1)
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
                                x-on:keydown.escape.prevent.stop="close($refs.button)" x-id="['dropdown-button']" class="relative">
                                <button x-ref="button" x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" class="flex items-center px-5 py-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-vertical" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                    </svg>
                                </button>
                                <!-- Panel -->
                                <div x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute right-10 top-3 mt-2 w-32 rounded-md bg-gray-200 z-10">
                                    <!-- Botón Editar -->
                                    <div  wire:click="$emit('restartItem',{{ $project->id }})" class="@if($project->deleted_at == null) hidden @endif flex content-center px-4 py-2 text-sm text-black cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-reload mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747"></path>
                                            <path d="M20 4v5h-5"></path>
                                        </svg>
                                        Restaurar</div>
                                    @if($project->deleted_at == null)  
                                    <!-- Botón Editar -->
                                    <div wire:click="showUpdate({{$project->id}})" class="flex content-center px-4 py-2 text-sm text-black cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                            <path d="M16 5l3 3"></path>
                                        </svg>
                                        Editar</div>
                                    <!-- Botón Reincidencia -->
                                    <div wire:click="$emit('deleteItem',{{ $project->id }})" class="@if(Auth::user()->type_user == 1)flex @else hidden @endif content-center px-4 py-2 text-sm text-red-600 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7l16 0"></path>
                                            <path d="M10 11l0 6"></path>
                                            <path d="M14 11l0 6"></path>
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                        </svg>
                                        Eliminar</div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-5">
            {{ $projects->links() }}
        </div>
    </div>
    {{-- END TABLE --}}
    {{-- MODAL EDIT / CREATE --}}
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto @if($modalCreateEdit) block  @else hidden @endif">
        <div class="flex justify-center h-full items-center top-0 opacity-80 left-0 z-30 w-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-full items-center top-0 left-0 z-40 w-full fixed px-2 smd:px-0">
            <div class="flex flex-col md:w-3/4 mx-auto rounded-lg   overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100 text-white rounded-tl-lg rounded-tr-lg">
                    @if($showUpdate)
                    <h3 class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">Editar</h3>
                    @else
                    <h3 class="text-xl text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">Crear</h3>
                    @endif
                    <svg wire:click="modalCreateEdit" wire:loading.remove wire:target="modalCreateEdit" class="w-6 h-6 my-2 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="modalBody">
                    {{-- PROJECT --}}
                    <div class="w-full lg:w-1/2 md-3/4 mb-5 md:mb-0 flex flex-col px-5 lg:border-r-2 border-gray-400">
                        <div class="flex flex-row justify-between mb-10 px-2 py-2 bg-gray-100 text-white rounded-tl-lg rounded-tr-lg">
                            <h4 class="text-base text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">Proyecto</h4>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="code">
                                    Código <p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='code' required type="number" placeholder="Código" name="code" id="code" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('code')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Nombre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='name' required type="text" placeholder="Nombre" name="name" id="name" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('name')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Tipo @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
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
                                    <span class="text-red-600 text-xs italic">
                                        @error('type')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Prioridad <p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='priority' required type="number" placeholder="0 - 99" min="0" max="99" name="priority" id="priority" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('priority')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6 flex flex-row">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Scrum Master @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
                                    <select wire:model='programmer' required name="programmer" id="programmer" class="inputs">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $programmer) selected @endif>{{ $user->name }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='programmer' required name="programmer" id="programmer" class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('programmer')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3 ">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Líder @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
                                    <select wire:model='leader' required name="leader" id="leader" class="inputs">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $leader) selected @endif>{{ $user->name }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select wire:model='leader' required name="leader" id="leader" class="inputs">
                                        <option selected>Selecciona...</option>
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('leader')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Cliente @if(!$showUpdate)<p class="text-red-600">*</p>@endif
                                </h5>
                                @if($showUpdate)
                                <select wire:model='customer' name="customer" id="customer" class="inputs">
                                    @foreach ($allCustomers as $customer)
                                        <option value="{{ $customer->id }}" >{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @else
                                <select wire:model='customer' required name="customer" id="customer" class="inputs">
                                    <option selected>Selecciona...</option>
                                    @foreach ($allCustomers as $allCustomer)
                                        <option value="{{ $allCustomer->id }}">{{ $allCustomer->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('customer')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- BACKLOG --}}
                    <div class="w-full lg:w-1/2 px-5 ">
                        <div class="flex flex-row justify-between mb-10 px-2 py-2 bg-gray-100 text-white rounded-tl-lg rounded-tr-lg">
                            <h4 class="text-base text-secundaryColor font-medium title-font  w-full border-l-4 border-secundaryColor pl-4 py-2">Backlog</h4>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Objetivo general<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='general_objective' required type="text" placeholder="Objetivo general" name="general_objective" id="general_objective" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('general_objective')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="code">
                                    Alcances<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='file' required type="file" name="file" id="file" class="inputs mb-2">
                                <textarea wire:model='scopes' required type="text" rows="10" placeholder="Escriba los alcances." name="scopes" id="scopes" class="inputs"></textarea>                                                                                                                                                                                       
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('scopes')
                                            <span class="pl-2 text-red-600 text-xs italic">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    
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
                                <h5 class="inline-flex font-semibold" for="name">
                                    Fecha de inicio<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='start_date' required type="date" placeholder="Nombre" name="start_date" id="start_date" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('start_date')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Fecha de cierre<p class="text-red-600">*</p>
                                </h5>
                                <input wire:model='closing_date' required type="date" placeholder="Nombre" name="closing_date" id="closing_date" class="inputs">
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('closing_date')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 mb-6">
                            <div class="w-full flex flex-col px-3 mb-6">
                                <h5 class="inline-flex font-semibold" for="code">
                                    Claves de acceso
                                </h5>
                                <textarea wire:model='passwords' required type="text" rows="10" placeholder="Accesos de sistema." name="passwords" id="passwords" class="inputs"></textarea>                                                                                                                                                                                       
                                <div>
                                    <span class="text-red-600 text-xs italic">
                                        @error('passwords')
                                        <span class="pl-2 text-red-600 text-xs italic">
                                            {{$message}}
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
                    <button class="btnSave" wire:click="update({{$projectEdit->id}})" wire:loading.remove wire:target="update({{$projectEdit->id}})">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Guardar
                    </button>
                    @else
                    <button class="btnSave" wire:click="create" wire:loading.remove wire:target="create">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
    {{-- END MODAL EDIT / CREATE --}}

    <div class="absolute w-full h-screen z-50 top-0 left-0 " wire:loading >
        <div class="absolute w-full h-screen bg-gray-200 z-10 opacity-40"></div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>

    @push('js')
    <script>
        window.addEventListener('file-reset', () => {
            document.getElementById('file').value = null;
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
                calcelButtonText: 'Cancelar'
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
                calcelButtonText: 'Cancelar'
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
        // LOAD

        Livewire.on('setCategory', opcion => {
            $('#id_category').val(opcion).trigger('change.select2').trigger('select2:select');
        });
    </script>
    @endpush
</div>
