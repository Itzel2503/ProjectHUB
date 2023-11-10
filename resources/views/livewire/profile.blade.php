<div>
    <div class="mt-8">
        <div class="md:grid md:grid-cols-1 w-11/12 lg:w-2/4 w-full mx-auto">
            <div class="mt-5 md:mt-0 md:col-span-1 bg-main-fund">
                <div class="shadow rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 space-y-6 sm:p-6 w-full">
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <div class="w-full relative text-center mt-8">
                                <div class="relative h-32 w-32 shadow-xl mx-auto border-yellow rounded-full overflow-hidden border-4 mb-4">
                                    <img class="h-32 w-32 rounded-full object-cover" src="{{ Avatar::create($user->name)->toBase64() }}" alt="profile" />
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <p class="w-full text-center">{{ $user->name }} {{ $user->lastname }}</< /p>
                        </div>
                        @if($editing)
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Nombre:</h5>
                            <input wire:model='name' required type="text" placeholder="{{ $user->name }}" name="name" id="name" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Apellido:</h5>
                            <input wire:model='lastname' require type="text" placeholder="{{ $user->lastname }}" name="lastname" id="lastname" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                        </div>
                        @endif
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Número de teléfono:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($user->phone) ? "$user->phone" : 'Sin registro' }}</p>
                            @else
                            <input wire:model='phone' require type="text" placeholder="{{ $user->phone }}" name="phone" id="phone" pattern="[0-9]+" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">CURP:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($user->curp) ? "$user->curp" : 'Sin registro' }}</p>
                            @else
                            <input wire:model='curp' require maxlength="18" placeholder="{{ $user->curp }}" type="text" name="curp" id="curp" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">RFC:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($user->rfc) ? "$user->rfc" : 'Sin registro' }}</p>
                            @else
                            <input wire:model='rfc' require maxlength="13" placeholder="{{ $user->rfc }}" type="text" name="rfc" id="rfc" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Fecha de nacimiento:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($user->date_birthday) ? "$user->date_birthday" : 'Sin registro' }}</p>
                            @else
                            <input wire:model='date_birthday' require placeholder="{{ $user->date_birthday }}" type="date" name="date_birthday" id="date_birthday" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto" />
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Área:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($areaUser->name) ? "$areaUser->name" : 'Sin registro' }}</p>
                            @else
                            <select wire:model='area' name="area" id="area" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                                <option selected value="{{ $areaUser->name }}">{{ $areaUser->name }}</option>
                                @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Correo electrónico:</h5>
                            @if(!$editing)
                            <p class="mb-2 w-3/4 mx-auto">{{ ($user->email) ? "$user->email" : 'Sin registro' }}</p>
                            @else
                            <input wire:model.defer='email' require placeholder="Correo electrónico." type="email" autofocus="autofocus" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                            <!-- <input wire:model.defer="email" type="email"  required="required" autofocus="autofocus" placeholder="Correo electrónico." class="my-2 border-gray-300 focus:border-eticomblue focus:ring focus:ring-eticomblue focus:ring-opacity-50 rounded-md  shadow-xs appearance-none border rounded w-full py-2 px-3 text-grey-darker"> -->
                            @endif
                        </div>
                        @if($editing)
                        <div class="col-span-6 sm:col-span-3 mt-4">
                            <h5 class="mb-2 w-3/4 mx-auto">Cambiar contraseña:</h5>
                            <input wire:model.def='password' name="password" id="password" type="text" class="leading-snug border border-gray-400 block w-3/4 appearance-none bg-white text-gray-700 py-1 px-4 rounded mx-auto">
                        </div>
                        @endif
                        @if(!$editing)
                        <div class="px-4 py-3 text-center sm:px-6">
                            <button wire:click="edit" id="edit" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg">Editar</button>
                        </div>
                        @else
                        <div class="flex justify-between px-4 py-3 text-center sm:px-6">
                            <button wire:click="update({{$user->id}})" wire:loading.attr="disabled" wire:target="update({{ $user->id }})" id="save" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg">Guardar</button>
                            <span wire:loading wire:target="update({{ $user->id }})">Loading...</span>
                            <button wire:click="edit" id="cancel" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-main  hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg">Cancelar</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
