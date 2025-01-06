<div>
    <div class="modalBody">
        {{-- ACTIVITY --}}
        <div class="md-3/4 mb-5 flex w-full flex-col border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
            <div
                class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                <h4
                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                    Actividad</h4>
            </div>
            <div class="-mx-3 mb-6 flex flex-row">
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="title">
                        Titulo<p class="text-red-600">*</p>
                    </h5>
                    <input wire:model='title' required type="text" placeholder="Título" name="title" id="title"
                        class="inputs">
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('title')
                                <span class="pl-2 text-xs italic text-red-600">
                                    {{ $message }}
                                </span>
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="file">
                        Seleccionar una imagen
                    </h5>
                    <input wire:model='file' required type="file" name="file" id="file" class="inputs">
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('file')
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
                    <h5 class="inline-flex font-semibold" for="description">
                        Descripción
                    </h5>
                    <textarea wire:model='description' type="text" rows="6"
                        placeholder="Describa la observación y especifique el objetivo a cumplir." name="description" id="description"
                        class="textarea"></textarea>
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('description')
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
                    <h5 class="inline-flex font-semibold" for="delegate">
                        Delegar<p class="text-red-600">*</p>
                    </h5>
                    <select wire:model='delegate' required name="delegate" id="delegate" class="inputs">
                        <option selected>Selecciona...</option>
                        @foreach ($allUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('delegate')
                                <span class="pl-2 text-xs italic text-red-600">
                                    {{ $message }}
                                </span>
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="expected_date">
                        Fecha de entrega<p class="text-red-600">*</p>
                    </h5>
                    <input wire:model='expected_date' required type="date" name="expected_date" id="expected_date"
                        class="inputs">
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('expected_date')
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
                    <h5 class="inline-flex font-semibold" for="priority">
                        Prioridad
                    </h5>
                    <div class="flex justify-center gap-20">
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" value="Alto"
                                class="priority-checkbox border-red-600 bg-red-600"
                                style="height: 24px; width: 24px; accent-color: #dd4231;"
                                wire:click="selectPriority('Alto')" {{ $priority === 'Alto' ? 'checked' : '' }} />
                            <label class="mt-2">Alto</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" value="Medio"
                                class="priority-checkbox border-yellow-400 bg-yellow-400"
                                style="height: 24px; width: 24px; accent-color: #f6c03e;"
                                wire:click="selectPriority('Medio')" {{ $priority === 'Medio' ? 'checked' : '' }} />
                            <label class="mt-2">Medio</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" value="Bajo"
                                class="priority-checkbox border-secondary bg-secondary"
                                style="height: 24px; width: 24px; accent-color: #0062cc;"
                                wire:click="selectPriority('Bajo')" {{ $priority === 'Bajo' ? 'checked' : '' }} />
                            <label class="mt-2">Bajo</label>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs italic text-red-600">
                            @error('priority')
                                <span class="pl-2 text-xs italic text-red-600">
                                    {{ $message }}
                                </span>
                            @enderror
                        </span>
                    </div>
                </div>
            </div>
        </div>
        {{-- POINTS --}}
        <div class="w-full px-5 lg:w-1/2">
            <div
                class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                <h4
                    class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                    Story Points</h4>
            </div>
            @if (Auth::user()->type_user == 1 || Auth::id() == $activityEdit->user->id)
                <div class="mb-6 flex flex-row">
                    <span wire:click="changePoints"
                        class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-exchange mr-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 10h14l-4 -4" />
                            <path d="M17 14h-14l4 4" />
                        </svg>
                        @if ($changePoints)
                            Agregar puntos directos
                        @else
                            Cuestionario
                        @endif
                    </span>
                </div>
            @endif
            <div class="@if ($changePoints) hidden @else block @endif -mx-3 mb-6">
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="name">
                        Puntos <p class="text-red-600">*</p>
                    </h5>
                    <input wire:model='points' required type="number" placeholder="1, 2, 3, 5, 8, 13"
                        name="points" id="points" class="inputs">
                </div>
            </div>
            <div class="@if ($changePoints) block @else hidden @endif">
                <div class="-mx-3 mb-6">
                    <div class="mb-6 flex w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="name">
                            ¿Cuánto se conoce de la tarea?<p class="text-red-600">*</p>
                        </h5>
                        <select wire:model='point_know' required name="point_know" id="point_know" class="inputs">
                            <option selected>Selecciona...</option>
                            <option value="1">Todo</option>
                            <option value="2">Casi todo</option>
                            <option value="3">Algunas cosas</option>
                            <option value="5">Poco</option>
                            <option value="8">Casi nada</option>
                            <option value="13">Nada</option>
                        </select>
                    </div>
                </div>
                <div class="-mx-3 mb-6">
                    <div class="mb-6 flex w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="name">
                            ¿De cuántos depende?<p class="text-red-600">*</p>
                        </h5>
                        <select wire:model='point_many' required name="point_many" id="point_many" class="inputs">
                            <option selected>Selecciona...</option>
                            <option value="1">Solo uno</option>
                            <option value="2">Un par</option>
                            <option value="3">Pocos</option>
                            <option value="5">Varios</option>
                            <option value="8">Muchos</option>
                            <option value="13">No se sabe</option>
                        </select>
                    </div>
                </div>
                <div class="-mx-3 mb-6">
                    <div class="mb-6 flex w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="name">
                            ¿Cuánto esfuerzo representa?<p class="text-red-600">*</p>
                        </h5>
                        <select wire:model='point_effort' required name="point_effort" id="point_effort"
                            class="inputs">
                            <option selected>Selecciona...</option>
                            <option value="1">Menos de 2 horas</option>
                            <option value="2">Medio día</option>
                            <option value="3">Hasta dos días</option>
                            <option value="5">Pocos días</option>
                            <option value="8">Alrededor de</option>
                            <option value="13">Mas de una</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modalFooter">
        {{-- @if ($showUpdateActivity)
            <button class="btnSave" wire:click="updateActivity({{ $activityEdit->id }})"> Guardar
            </button>
        @else --}}
        <button class="btnSave" wire:click="create()">
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
    </div>
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="selectPriority, changePoints, create">
        <div class="absolute z-10 h-screen w-full bg-gray-200 opacity-40">
        </div>
        <div class="loadingspinner relative top-1/3 z-20">
            <div id="square1"></div>
            <div id="square2"></div>
            <div id="square3"></div>
            <div id="square4"></div>
            <div id="square5"></div>
        </div>
    </div>
    {{-- END LOADING PAGE --}}
    <script>
        window.addEventListener('file-reset', () => {
            document.getElementById('file').value = null;
        });
    </script>
</div>
