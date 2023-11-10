<div>
    <div class="mt-8">
        <div class=" w-full mx-auto">
            <div class="mt-5 md:mt-0 md:col-span-1">
                <div class="px-4 py-5 space-y-6 sm:p-6 w-full">
                    <h1 class="inline-flex w-full text-base font-semibold text-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1"></path>
                            <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M17 10h2a2 2 0 0 1 2 2v1"></path>
                            <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                            <path d="M3 13v-1a2 2 0 0 1 2 -2h2"></path>
                        </svg>
                        <span class="ml-4">Usuarios</span>
                    </h1>
                </div>

                {{--Tabla usuarios--}}
                <div class="shadow-xl sm:rounded-lg px-4 py-4">
                    {{-- NAVEGADOR --}}
                    <div class="flex flex-wrap justify-between text-sm lg:text-xs lg:text-base">
                        <!-- SEARCH -->
                        <div class="inline-flex border-0 w-full md:w-2/5 pl-2 lg:pl-6 h-12 bg-transparent mb-2">
                            <div class="flex flex-wrap w-full h-full mb-6 relative">
                                <div class="flex">
                                    <span class="flex items-center leading-normal bg-transparent rounded-lg  border-0  border-none lg:px-3 p-2 whitespace-no-wrap">
                                        <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                                <input wire:model="search" type="text" placeholder="Buscar" class="flex w-full border-0 border-yellow border-b-2 rounded rounded-l-none relative focus:outline-none text-xxs lg:text-xs lg:text-base text-gray-500 font-thin">   
                            </div>
                        </div>
                        <!-- COUNT -->
                        <div class="inline-flex w-1/4 md:w-1/3 h-12 bg-transparent mb-2">
                            <select wire:model="perPage" id="" class="w-full border-0 rounded-lg px-3 py-2 relative focus:outline-none">
                                <option value="25"> 25 por Página</option>
                                <option value="50"> 50 por Página</option>
                                <option value="100"> 100 por Página</option>
                            </select>
                        </div>
                        <div class="inline-flex flex-row-reverse w-4/12  md:w-2/12 h-12 mb-2 ">
                            <button class="px-2 py-2 text-white font-semibold  bg-main hover:bg-secondary rounded-lg cursor-pointer w-full " wire:click="toggleModal()"> <i class="fas fa-plus mr-2"></i> Nuevo </button>
                        </div>
                    </div>
                    {{-- END NAVEGADOR --}}

                    {{--table--}}
                    <div class="align-middle inline-block w-full overflow-x-scroll  bg-white rounded-lg  shadow-xs mt-4">
                        <table class="w-full whitespace-no-wrap table table-hover ">
                            <thead class="border-2 border-gray-600  bg-gray-600">
                                <tr class="font-semibold tracking-wide text-left text-white   bg-gray-600 text-base ">
                                    <th class=" px-4 py-3">Nombre</th>
                                    <th class=" px-4 py-3">Apellidos </th>
                                    <th class=" px-4 py-3">Correo Electronico</th>
                                    <th class=" px-4 py-3">Área</th>
                                    <th class=" px-4 py-3">Tipo Usuario</th>
                                    <th class=" px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="text-gray-700 dark:text-gray-400 border text-sm">
                                    <td class=" px-4 py-1">{{$user->name}}</td>
                                    <td class="border  px-4 py-1">{{$user->lastname}}</td>
                                    <td class=" px-4 py-1">{{$user->email}}</td>
                                    <td class="border px-4 py-1">{{$areaUser->name}} </td>
                                    <td class=" px-4 py-1">{{$user->type_user}}</td>
                                    <td class="border px-4 py-1 text-center">
                                        <button wire:click="edit({{$user->id}})" class="bg-eticomblue hover:bg-eticombluedark text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button wire:click="$emit('deleteItem',{{$user->id}})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg"><i class="fa-solid fa-trash-can"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--end table--}}

            </div>
        </div>
    </div>

    <!-- <div class="top-20  left-0 z-50 fixed   max-h-full overflow-y-auto" wire:loading wire:target="toggleModal">
        <div class="flex justify-center h-screen items-center  bg-gray-100 antialiased top-0 opacity-70 left-0  z-40 w-full h-full fixed "></div>
        <div class="flex justify-center h-screen items-center   antialiased top-0  left-0  z-50 w-full h-full fixed ">
            <div class="flex justify-center items-center">
                <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32 "></div>
                <div class="absolute">Loading...</div>
            </div>
        </div>
    </div>
    <div class="top-20  left-0 z-50 fixed   max-h-full overflow-y-auto" wire:loading wire:target="createUser, updateUser">
        <div class="flex justify-center h-screen items-center  bg-gray-100 antialiased top-0 opacity-30 left-0  z-40 w-full h-full fixed "></div>
        <div class="flex justify-center h-screen items-center   antialiased top-0  left-0  z-50 w-full h-full fixed ">
            <div class="flex justify-center items-center">
                <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32 "></div>
                <div class="absolute">Guardando...</div>
            </div>
        </div>
    </div>

    <div class="mx-1 sm:mx-10">
        
        {{--Tabla usuarios--}}
        <div class="bg-white  shadow-xl sm:rounded-lg px-4 py-4">
            <div class="flex flex-wrap justify-between text-sm lg:text-xs lg:text-base">
                <div class="inline-flex border rounded-lg w-full md:w-5/12 pl-2 lg:pl-6 h-12 bg-transparent mb-2">
                    <div class="flex flex-wrap items-stretch w-full h-full mb-6 relative">
                        <div class="flex">
                            <span class="flex items-center leading-normal bg-transparent rounded-lg  border  border-none lg:px-3 py-2 whitespace-no-wrap text-grey-dark ">
                                <svg width="18" height="18" class="w-4 lg:w-auto" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.11086 15.2217C12.0381 15.2217 15.2217 12.0381 15.2217 8.11086C15.2217 4.18364 12.0381 1 8.11086 1C4.18364 1 1 4.18364 1 8.11086C1 12.0381 4.18364 15.2217 8.11086 15.2217Z" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.9993 16.9993L13.1328 13.1328" stroke="#455A64" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                        <input type="text" wire:model="search" class="flex-shrink flex-grow flex-auto leading-normal tracking-wide w-px flex-1 border border-none border-l-0 rounded rounded-l-none px-3 relative focus:outline-none text-xxs lg:text-xs lg:text-base text-gray-500 font-thin" placeholder="Search">
                    </div>
                </div>
                <div class="inline-flex w-7/12   md:w-4/12  h-12 bg-transparent mb-2 ">
                    <select wire:model="perPage" id="" class="w-full border  rounded-lg  border-gray-200 px-3 py-2 relative focus:outline-none ">
                        <option value="25"> 25 por Página</option>
                        <option value="50"> 50 por Página</option>
                        <option value="100"> 100 por Página</option>
                    </select>
                </div>
                <div class="inline-flex flex-row-reverse w-4/12  md:w-2/12   h-12  mb-2 ">
                    <button class="px-2 py-2 text-white font-semibold  bg-eticomblue hover:bg-eticombluedark rounded-lg cursor-pointer w-full " wire:click="toggleModal()"> <i class="fas fa-plus mr-2"></i> Nuevo </button>
                </div>
            </div>
            {{-- end navegador --}}

            {{--table--}}
            <div class="align-middle inline-block w-full overflow-x-scroll  bg-white rounded-lg  shadow-xs mt-4">
                <table class="w-full whitespace-no-wrap table table-hover ">
                    <thead class="border-2 border-gray-600  bg-gray-600">
                        <tr class="font-semibold tracking-wide text-left text-white   bg-gray-600 text-base ">
                            <th class=" px-4 py-3">Nombre</th>
                            <th class=" px-4 py-3">Apellidos </th>
                            <th class=" px-4 py-3">Correo Electronico</th>
                            <th class=" px-4 py-3">Área</th>
                            <th class=" px-4 py-3">Tipo Usuario</th>
                            <th class=" px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="text-gray-700 dark:text-gray-400 border text-sm">
                            <td class=" px-4 py-1">{{$user->name}}</td>
                            <td class="border  px-4 py-1">{{$user->paternal_name}} {{$user->maternal_name}}</td>
                            <td class=" px-4 py-1">{{$user->email}}</td>
                            <td class="border px-4 py-1">{{$areaUser->name}} </td>
                            <td class=" px-4 py-1">{{$user->type_user}}</td>
                            <td class="border px-4 py-1 text-center">
                                <button wire:click="edit({{$user->id}})" class="bg-eticomblue hover:bg-eticombluedark text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button wire:click="$emit('deleteItem',{{$user->id}})" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 mt-1 sm:mt-0 rounded-lg"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{--end table--}}
        </div>

        <div class="top-20  left-0 z-50 max-h-full overflow-y-auto @if($modal) block  @else hidden @endif">
            <div class="flex justify-center h-screen items-center  bg-gray-800 antialiased top-0 opacity-70 left-0  z-30 w-full h-full fixed "> </div>
            <div class="flex text:md  justify-center h-screen items-center   antialiased top-0  left-0  z-40 w-full h-full fixed ">
                <div class=" flex flex-col w-11/12 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl    overflow-y-auto" style="max-height: 90%;">
                    <div class="flex flex-row justify-between px-6 py-4 bg-white text-white rounded-tl-lg rounded-tr-lg">
                        @if (!$editar)
                        <h2 class="text-xl text-gray-900 font-medium title-font  w-full border-l-4 border-eticomblue  pl-4 py-2">Nuevo Usuario</h2>
                        @else
                        <h2 class="text-xl text-gray-900 font-medium title-font  w-full border-l-4 border-eticomblue  pl-4 py-2">Editar Usuario</h2>
                        @endif
                        <svg class="w-6 h-6 cursor-pointer text-eticomblue   hover:stroke-2 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" wire:click="toggleModal" wire:loading.remove wire:target="toggleModal">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div class="w-6 h-6 border-l-2 border-white rounded-full animate-spin" wire:click="closeModal" wire:loading wire:target="closeModal"></div>
                    </div>
                    <div class="flex flex-col sm:flex-row px-6 py-2 bg-white overflow-y-auto text-sm scrollEdit">
                        <div class="w-full sm:w-3/5 md-3/4 mb-5 mt-5 flex flex-col">
                            <div class="-mx-3 md:flex   mb-6">
                                <div class="md:w-1/2 flex flex-col px-3   mb-6 md:mb-0">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Nombre *
                                    </p>
                                    <input wire:model.lazy="name" class="mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="text" placeholder="Jose Alberto">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('name')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="md:w-1/2 flex flex-col px-3  ">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Apellido Paterno *
                                    </p>
                                    <input wire:model.lazy="paternal_name" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="text" placeholder="Rosas">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('paternal_name')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>

                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3   mb-6 md:mb-0">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Apellido Materno*
                                    </p>
                                    <input wire:model.lazy="maternal_name" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="text" placeholder="Morales">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('maternal_name')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="md:w-1/2 flex flex-col px-3   ">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Fecha de nacimiento *
                                    </p>
                                    <input wire:model.lazy="birth_date" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="date" placeholder="">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('birth_date')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3   mb-6 md:mb-0">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Área
                                    </p>
                                    <select wire:model.defer="id_area" name="" id="" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker">
                                        <option value="0">Selecciona Área</option>
                                        @foreach($areas as $area)
                                        <option value="{{$area->id}}"> {{$area->area_name}}</option>
                                        @endforeach
                                    </select>
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('id_area')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="md:w-1/2 flex flex-col px-3   ">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Tipo de Usuario *
                                    </p>
                                    <select wire:model='area' name="area" id="area" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                                        <option selected value="{{ $areaUser->name }}">{{ $areaUser->name }}</option>
                                        @foreach ($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('id_user_type')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="-mx-3 md:flex mb-6">
                                <div class="md:w-1/2 flex flex-col px-3   mb-6 md:mb-0">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Correo *
                                    </p>
                                    <input wire:model.lazy="email" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="text" placeholder="jose@eticom.com">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('email')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>

                                            @enderror
                                        </span>
                                    </div>
                                </div>

                                <div class="md:w-1/2 flex flex-col px-3  ">
                                    <p class="    font-semibold text-gray-700" for="company">
                                        Nueva Contraseña*
                                    </p>
                                    <input wire:model.lazy="password" class="  mt-1 0  border-gray-300 focus:border-eticomblue   focus:ring focus:ring-eticomblue  focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="company" type="text" placeholder="A9546!r77">
                                    <div>
                                        <span class="text-red-500 text-xs italic">
                                            @error('password')
                                            <span class="pl-2   text-red-500 text-xs italic">
                                                {{$message}}
                                            </span>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row-reverse items-center  px-6 py-2 bg-white border-t border-gray-200 rounded-bl-lg rounded-br-lg">
                        @if (!$editar)
                        <button class="px-4 py-2 text-white font-semibold text-white  bg-eticomblue  hover:bg-bmwgreen-lighter rounded cursor-pointer" wire:click="createUser" wire:loading.remove wire:target="createUser"> Guardar </button>
                        {{-- <button class="px-4 py-2 text-white font-semibold text-white bg-bmwgreen hover:bg-bmwgreen-lighter rounded cursor-pointer" wire:click="createUser" disabled   wire:loading wire:target="createUser">     Guardando ...  </button> --}}
                        @else
                        <button class="px-4 py-2 text-white font-semibold text-white  bg-eticomblue  hover:bg-bmwgreen-lighter rounded cursor-pointer" wire:click="updateUser" wire:loading.remove wire:target="updateUser"> Guardar </button>
                        {{-- <button class="px-4 py-2 text-white font-semibold text-white bg-bmwgreen hover:bg-bmwgreen-lighter rounded cursor-pointer" wire:click="updateUser" disabled   wire:loading wire:target="updateUser">    Guardandos ...   </button> --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('js')
    <script>
        Livewire.on('deleteItem', deletebyId => {
            Swal.fire({
                title: '¿Seguro que deseas eliminar este elemento?',
                text: "Esta acción es irreversible",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3088d9',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('borrar', deletebyId);
                    // Swal.fire(
                    //   '¡Eliminado!',
                    //   'Tu elemento ha sido eliminado.',
                    //   'Exito'
                    // )
                }
            })
        });

        function returnvalue() {
            console.log(document.getElementById('actual_cost').value);
            return document.getElementById('actual_cost').value;
        }
    </script>
    @endpush
</div>