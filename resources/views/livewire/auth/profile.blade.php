<div>
    <div class="mt-8">
        <div class="md:grid md:grid-cols-1 md:m-auto w-full mx-auto justify-items-center">
            <div class="mt-5 justify-center flex items-center w-full">
                <div class="shadow rounded-md sm:overflow-hidden w-2/3 bg-primaryColor">
                    <div class="px-4 py-5 space-y-6 sm:p-6 w-full">
                        <div class="flex col-span-6 sm:col-span-3 mt-4 justify-center justify-items-center">
                            <div class="relative mb-4">
                                <div class="h-32 w-32 shadow-xl mx-auto rounded-full overflow-hidden">
                                    @if (Auth::user()->profile_photo)
                                    <img class="h-32 w-32 rounded-full object-cover mx-auto" aria-hidden="true"
                                        src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}" alt="profile" />
                                    @else
                                    <img class="h-32 w-32 rounded-full object-cover mx-auto" aria-hidden="true"
                                        src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="profile" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($editing)
                        <div class="col-span-6 sm:col-span-3 mt-9 xl:text-center">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Cambiar imagen:</h5>
                            <input wire:model='file' type="file" name="file" id="file" class="inputs bg-white">
                        </div>
                        @endif
                        <div class="col-span-6 sm:col-span-3 mt-9 xl:text-center">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Nombre:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ $user->name }} {{ $user->lastname }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9 xl:text-center">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Número de teléfono:
                            </h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->phone) ? "$user->phone" : 'Sin
                                registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Fecha de nacimiento:
                            </h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->date_birthday) ?
                                "$user->date_birthday" : 'Sin registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Área:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($areaUser->name) ? "$areaUser->name" : 'Sin
                                registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Correo electrónico:
                            </h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->email) ? "$user->email" : 'Sin
                                registro' }}</p>
                        </div>
                        @if($editing)
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center text-text1 font-semibold">Cambiar contraseña:
                            </h5>
                            <input wire:model.def='password' name="password" id="password" type="text" class="inputs">
                        </div>
                        @endif
                        @if(!$editing)
                        <div class="flex justify-center items-center py-6">
                            <button class="btnSave" wire:click="edit({{ $user->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit mr-2"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Editar
                            </button>
                        </div>
                        @else
                        <div class="flex justify-between items-center py-6">
                            <button id="buttonSave" class="btnSave" wire:click="update({{ $user->id }})">
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
                            <button class="btnSave" wire:click="edit({{ $user->id }})">
                                Cancelar
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
    <script>

    </script>
    @endpush
</div>