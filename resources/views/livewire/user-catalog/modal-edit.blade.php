<div>
    <div class="top-20 left-0 z-50 max-h-full overflow-y-auto block">
        <div class="flex justify-center h-screen items-center top-0 opacity-80 left-0 z-30 w-full h-full fixed bg-no-repeat bg-cover bg-gray-500"></div>
        <div class="flex text:md justify-center h-screen items-center top-0 left-0 z-40 w-full h-full fixed">
            <div class="flex flex-col w-2/4 sm:w-5/6 lg:w-3/5  mx-auto rounded-lg  shadow-xl overflow-y-auto " style="max-height: 90%;">
                <div class="flex flex-row justify-between px-6 py-4 bg-main-fund text-white rounded-tl-lg rounded-tr-lg">
                    @if (!$editar)
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Nuevo Usuario</h2>
                    @else
                    <h2 class="text-xl text-secondary font-medium title-font  w-full border-l-4 border-secondary-fund pl-4 py-2">Editar Usuario</h2>
                    @endif
                    <svg wire:click="closeModal" wire:loading.remove wire:target="closeModal" class="w-6 h-6 cursor-pointer text-black hover:stroke-2" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M18 6l-12 12"></path>
                        <path d="M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex flex-col sm:flex-row px-6 py-2 bg-main-fund overflow-y-auto text-sm">
                    <div class="w-full sm:w-3/5 md-3/4 mb-5 mt-5 flex flex-col">
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Nombre <p class="text-red">*</p>
                                </h5>
                                <input wire:model='name' required type="text" placeholder="{{ $user->name }}" name="name" id="name" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red text-xs italic">
                                        @error('name')
                                        <span class="pl-2 text-red text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="md:w-1/2 flex flex-col px-3 ">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Apellidos <p class="text-red">*</p>
                                </h5>
                                <input wire:model='lastname' required type="text" placeholder="{{ $user->lastname }}" name="lastname" id="lastname" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red text-xs italic">
                                        @error('lastname')
                                        <span class="pl-2 text-red text-xs italic">
                                            {{$message}}
                                        </span>

                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Fecha de nacimiento <p class="text-red">*</p>
                                </h5>
                                <input wire:model='date_birthday' required type="date" placeholder="{{ $user->date_birthday }}" name="date_birthday" id="date_birthday" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red-500 text-xs italic">
                                        @error('date_birthday')
                                        <span class="pl-2   text-red-500 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    CURP <p class="text-red">*</p>
                                </h5>
                                <input wire:model='curp' required type="text" maxlength="18" placeholder="{{ $user->curp }}" name="curp" id="curp" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red-500 text-xs italic">
                                        @error('curp')
                                        <span class="pl-2   text-red-500 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                <h5 class="inline-flex font-semibold" for="name">
                                    RFC <p class="text-red">*</p>
                                </h5>
                                <input wire:model='rfc' required type="text" maxlength="13" placeholder="{{ $user->rfc }}" name="rfc" id="rfc" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red-500 text-xs italic">
                                        @error('rfc')
                                        <span class="pl-2   text-red-500 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Número de teléfono: <p class="text-red">*</p>
                                </h5>
                                <input wire:model='phone' required type="text" placeholder="{{ $user->phone }}" name="phone" id="phone" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red-500 text-xs italic">
                                        @error('phone')
                                        <span class="pl-2   text-red-500 text-xs italic">
                                            {{$message}}
                                        </span>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Área: <p class="text-red">*</p>
                                </h5>
                                <select wire:model='area' name="area" id="area" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                    <option selected>Selecciona...</option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Correo electrónico: <p class="text-red">*</p>
                                </h5>
                                <input wire:model='email' required type="text" placeholder="{{ $user->email }}" name="email" id="email" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
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
                        </div>

                        <div class="-mx-3 md:flex mb-6">
                            <div class="md:w-1/2 flex flex-col px-3 mb-6 md:mb-0">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Contraseña <p class="text-red">*</p>
                                </h5>
                                <input wire:model='password' required type="text" placeholder="{{ $user->password }}" name="password" id="password" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
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
                            <div class="md:w-1/2 flex flex-col px-3">
                                <h5 class="inline-flex font-semibold" for="name">
                                    Confirma tu contraseña <p class="text-red">*</p>
                                </h5>
                                <input wire:model='password_confirmation' required type="text" placeholder="Confirma tu contraseña" name="password_confirmation" id="password_confirmation" class="leading-snug border border-gray-400 block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                                <div>
                                    <span class="text-red-500 text-xs italic">
                                        @error('password_confirmation')
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
                <div class="flex justify-center items-center py-6 bg-main-fund">
                    @if (!$editar)
                    <button class="px-4 py-2 text-white font-semibold text-white bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="createUser" wire:loading.remove wire:target="createUser"> Guardar </button>
                    @else
                    <button class="px-4 py-2 text-white font-semibold text-white bg-secondary-fund hover:bg-secondary rounded cursor-pointer" wire:click="updateUser" wire:loading.remove wire:target="updateUser"> Guardar </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
    </script>
    @endpush
</div>