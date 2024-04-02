<div>
    <div class="mt-8">
        <div class="md:grid md:grid-cols-1 md:m-auto w-full mx-auto justify-items-center">
            <div class="mt-5 justify-center flex items-center w-full">
                <div class="shadow rounded-md sm:overflow-hidden w-2/3 bg-main-fund">
                    <div class="px-4 py-5 space-y-6 sm:p-6 w-full">
                        <div class="flex col-span-6 sm:col-span-3 mt-4 justify-center justify-items-center">
                            <div class="relative h-32 w-32 shadow-xl mx-auto border-yellow-400 rounded-full overflow-hidden border-4 mb-4">
                                @if (Auth::user()->profile_photo)
                                    <img class="h-32 w-32 rounded-full object-cover mx-auto" aria-hidden="true" src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}" alt="profile" />
                                @else
                                    <img class="h-32 w-32 rounded-full object-cover mx-auto" aria-hidden="true" src="{{ Avatar::create(Auth::user()->name)->toBase64() }}" alt="profile" />
                                @endif
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <p class="w-full text-center">{{ $user->name }} {{ $user->lastname }}</< /p>
                        </div>

                        <div class="col-span-6 sm:col-span-3 mt-9 xl:text-center">
                            <h5 class="mb-2 mx-auto w-full text-center">Número de teléfono:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->phone) ? "$user->phone" : 'Sin registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center">Fecha de nacimiento:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->date_birthday) ? "$user->date_birthday" : 'Sin registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center">Área:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($areaUser->name) ? "$areaUser->name" : 'Sin registro' }}</p>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center">Correo electrónico:</h5>
                            <p class="mb-2 mx-auto w-full text-center">{{ ($user->email) ? "$user->email" : 'Sin registro' }}</p>
                        </div>
                        @if($editing)
                        <div class="col-span-6 sm:col-span-3 mt-9">
                            <h5 class="mb-2 mx-auto w-full text-center">Cambiar contraseña:</h5>
                            <input wire:model.def='password' name="password" id="password" type="text" class="leading-snug border border-none block w-3/4 appearance-none bg-white text-gray-700 text-center py-1 px-4 rounded mx-auto">
                        </div>
                        @endif
                        @if(!$editing)
                        <div class="px-4 py-4 mt-5 text-center sm:px-6">
                            <button wire:click="edit({{ $user->id }})" id="edit" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Editar</button>
                        </div>
                        @else
                        <div class="flex justify-between px-4 py-5 mt-5 text-center sm:px-6">
                            <button wire:click="update({{$user->id}})" wire:loading.attr="disabled" wire:target="update({{ $user->id }})" id="save" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Guardar</button>
                            <span wire:loading wire:target="update({{ $user->id }})">Loading...</span>
                            <button wire:click="edit({{$user->id}})" id="cancel" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancelar</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>