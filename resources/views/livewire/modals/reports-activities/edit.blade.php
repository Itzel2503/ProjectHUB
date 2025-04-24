<div class="overflow-y-auto">
    <div class="modalBody">
        {{-- REPORT --}}
        <div
            class="md-3/4 @if (Auth::user()->type_user != 3) border-gray-400 lg:w-1/2 lg:border-r-2 @endif mb-5 flex w-full flex-col px-5 md:mb-0">
            @if (Auth::user()->type_user != 3)
                <div
                    class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                    <h4
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                        @if ($type == 'activity')
                            Actividad
                        @else
                            Reporte
                        @endif
                    </h4>
                </div>
            @endif
            <div class="-mx-3 mb-6 flex flex-row">
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="">Icono</h5>
                    <div class="flex justify-between mt-2">
                        <!-- Íconos -->
                        @foreach ([
        '🚀' => 'Propuestas; Lanzamientos',
        '🔵' => 'Seguimiento',
        '💵' => 'Finanzas',
        '📅' => 'Cita',
        '💡' => 'Ideas',
        '📎' => 'Notas',
        '⭐' => 'Top',
        '⏸️' => 'Pausa',
        '✉️' => 'Enviar',
        '🚫' => 'Sin icono',
    ] as $icon => $tooltip)
                            <label class="principal">
                                <input type="radio" name="icon" value="{{ $icon }}" class="icon-checkbox"
                                    wire:model="selectedIcon">
                                <span class="icon-event {{ $selectedIcon === $icon ? 'selected' : '' }}"
                                    wire:click="selectIcon('{{ $icon }}')">
                                    {{ $icon }}
                                </span>
                                <div class="relative">
                                    <div
                                        class="hidden-info absolute -top-12 left-0 z-10 w-auto bg-gray-100 p-2 text-left text-xs">
                                        <p>{{ $tooltip }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="-mx-3 mb-6 flex flex-row">
                <div class="mb-6 flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="title">
                        Titulo<p class="text-red-600">*</p>
                    </h5>
                    <input wire:model.defer='title' required type="text" placeholder="Título" name="title" id="title"
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
            </div>
            <div class="-mx-3 mb-6">
                <div class="mb-6 flex w-full flex-col px-3">
                    @if ($type == 'activity')
                        <h5 class="inline-flex font-semibold" for="description">
                            Descripción
                        </h5>
                        <textarea wire:model.defer='description' type="text" rows="6"
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
                    @else
                        <h5 class="inline-flex font-semibold" for="description">
                            Descripción<p class="text-red-600">*</p>
                        </h5>
                        <textarea wire:model='description' type="text" rows="6"
                            placeholder="Describa la nueva observación y especifique el objetivo a cumplir." name="description" id="description"
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
                    @endif
                </div>
            </div>
            @if (Auth::user()->type_user != 3)
                <div class="-mx-3 mb-6 flex flex-row">
                    @if ($type == 'report')
                        <div class="mb-6 flex w-full flex-col px-3">
                            <h5 class="mr-5 inline-flex font-semibold" for="evidenceEdit">
                                Evidencia
                            </h5>
                            <div class="flex justify-center gap-20">
                                <div class="flex flex-col items-center">
                                    <input type="checkbox" wire:model="evidenceEdit" class="priority-checkbox"
                                        style="height: 24px; width: 24px;" />
                                </div>
                            </div>
                            <div>
                                <span class="text-xs italic text-red-600">
                                    @error('evidenceEdit')
                                        <span class="pl-2 text-xs italic text-red-600">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </span>
                            </div>
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
                    @else
                        <div class="mb-6 flex w-full flex-col px-3">
                            <h5 class="inline-flex font-semibold" for="delegate">
                                Mover a sprint
                            </h5>
                            <select wire:change.defer="moveActivity($event.target.value)" wire:model="moveActivity"
                                class="inputs">
                                @foreach ($sprints as $sprint)
                                    <option value="{{ is_object($sprint) ? $sprint->id : $sprint['id'] }}">
                                        {{ is_object($sprint) ? $sprint->number : $sprint['number'] }} -
                                        {{ is_object($sprint) ? $sprint->name : $sprint['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="pl-2 text-xs italic text-red-600">
                                {{ $moveActivityMessage }}
                            </span>
                            <div>
                                <span class="text-xs italic text-red-600">
                                    @error('moveActivity')
                                        <span class="pl-2 text-xs italic text-red-600">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="mb-6 flex w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="expected_date">
                            Fecha de entrega @if ($chooseEndDate)
                                <p class="text-red-600">*</p>
                            @endif
                        </h5>
                        <input @if ($chooseEndDate) require @endif
                            wire:change="expected_date($event.target.value)" wire:model='expected_date' type="date"
                            name="expected_date" id="expected_date" class="inputs">
                        <span class="text-xs italic text-red-600">{{ $expectedDateMessage }}</span>
                        @error('expected_date')
                            <span class="pl-2 text-xs italic text-red-600">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            @endif
            @if ($type != 'report')
                <div class="-mx-3 flex flex-row">
                    <div class="flex w-full flex-row px-3 justify-end">
                        <h5 class="inline-flex font-semibold" for="repeat_updated">
                            Actualizar
                        </h5>
                        <input wire:change="repeat_updated($event.target.checked)" wire:model="repeat_updated"
                            type="checkbox" name="repeat_updated" id="repeat_updated" class="ml-2">
                    </div>
                </div>
                <div class="-mx-3 mb-6 flex flex-row">
                    <div class="mb-6 flex w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="repeat">
                            Repetir
                        </h5>
                        <select @if ($repeat_updated) required @else disabled @endif
                            wire:change.defer="repeat($event.target.value)" wire:model='repeat' name="repeat"
                            id="repeat" class="inputs">
                            <option value="Once">No se repite</option>
                            <option value="Dairy">Todos los días</option>
                            <option value="Weekly">Todas las semanas</option>
                            <option value="Monthly">Todos los meses</option>
                            <option value="Yearly">Todos los años</option>
                        </select>
                        <span class="pl-2 text-xs italic text-red-600">
                            {{ $repeatMessage }}
                        </span>
                    </div>
                    <div class="@if ($chooseEndDate) flex @else hidden @endif mb-6 w-full flex-col px-3">
                        <h5 class="inline-flex font-semibold" for="end_date">
                            Fecha límite
                        </h5>
                        <input @if ($repeat_updated) required @else disabled @endif
                            wire:change.defer="end_date($event.target.value)" wire:model='end_date' type="date"
                            name="end_date" id="end_date" class="inputs">
                        <span class="pl-2 text-xs italic text-red-600">
                            {{ $endDateMessage }}
                        </span>
                    </div>
                </div>
            @endif
            <div class="-mx-3 mb-6">
                <div class="flex w-full flex-col px-3">
                    <h5 class="inline-flex font-semibold" for="tittle">
                        Prioridad
                    </h5>
                    <div class="flex justify-center gap-20">
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" wire:model="priority" value="Alto"
                                class="priority-checkbox border-red-600 bg-red-600"
                                style="height: 24px; width: 24px; accent-color: #dd4231;" />
                            <label class="mt-2">Alto</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" wire:model="priority" value="Medio"
                                class="priority-checkbox border-yellow-400 bg-yellow-400"
                                style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                            <label class="mt-2">Medio</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="radio" name="priority" wire:model="priority" value="Bajo"
                                class="priority-checkbox border-secondary bg-secondary"
                                style="height: 24px; width: 24px; accent-color: #0062cc;" />
                            <label class="mt-2">Bajo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::user()->type_user != 3)
            {{-- POINTS --}}
            <div class="w-full px-5 lg:w-1/2">
                <!-- PUNTOS DE ESFUERZO -->
                <div
                    class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                    <h4
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                        Story Points</h4>
                </div>
                @if (Auth::user()->type_user == 1 || (isset($recording->user) && Auth::user()->id == $recording->user->id))
                    <div class="mb-6 flex flex-row">
                        <span wire:click="changePoints"
                            class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
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
                        <input @if (Auth::user()->type_user == 1 || (isset($recording->user) && Auth::user()->id == $recording->user->id)) @else disabled @endif wire:model='points' required
                            type="number" placeholder="1, 2, 3, 5, 8, 13" name="points" id="points"
                            class="inputs">
                    </div>
                </div>
                <div class="@if ($changePoints) block @else hidden @endif">
                    <div class="-mx-3 mb-6">
                        <div class="mb-6 flex w-full flex-col px-3">
                            <h5 class="inline-flex font-semibold" for="name">
                                ¿Cuánto se conoce de la tarea?<p class="text-red-600">*</p>
                            </h5>
                            <select @if (Auth::user()->type_user == 1 || (isset($recording->user) && Auth::user()->id == $recording->user->id)) @else disabled @endif wire:model='point_know'
                                required name="point_know" id="point_know" class="inputs">
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
                            <select @if (Auth::user()->type_user == 1 || (isset($recording->user) && Auth::user()->id == $recording->user->id)) @else disabled @endif wire:model='point_many'
                                required name="point_many" id="point_many" class="inputs">
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
                            <select @if (Auth::user()->type_user == 1 || (isset($recording->user) && Auth::user()->id == $recording->user->id)) @else disabled @endif wire:model='point_effort'
                                required name="point_effort" id="point_effort" class="inputs">
                                <option selected>Selecciona...</option>
                                <option value="1">Menos de 2 horas</option>
                                <option value="2">Medio dìa</option>
                                <option value="3">Hasta dos dìas</option>
                                <option value="5">Pocos dìas</option>
                                <option value="8">Alrededor de</option>
                                <option value="13">Mas de una</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- ARCHIVOS -->
                <div
                    class="mb-10 flex flex-row justify-between rounded-tl-lg rounded-tr-lg bg-gray-100 px-2 py-2 text-white">
                    <h4
                        class="text-secundaryColor title-font border-secundaryColor w-full border-l-4 py-2 pl-4 text-base font-medium">
                        Archivos</h4>
                </div>
                <div wire:key="files-section-{{ $recording->id }}" class="-mx-3 mb-6">
                    <div class="mb-6 flex w-full flex-col px-3">
                        <div class="flex flex-row">
                            @if (!$chooseEndDate) 
                                <span wire:click="addInput"
                                    class="align-items-center hover:text-secondary flex w-full cursor-pointer flex-row justify-center py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus mr-2"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Agregar archivo
                                </span>
                            @else
                            <p class="text-red-600">No puedes agregar archivos nuevos. Los archivos existentes no se duplicarán.</p>
                            @endif
                        </div>
                        @foreach ($filesNew as $index => $file)
                            <div class="flex flex-row">
                                <span wire:click="removeInput({{ $index }})"
                                    class="my-auto cursor-pointer text-red-600">
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
                                </span>
                                <input wire:model="filesNew.{{ $index }}" required type="file" name="filesNew[]"
                                    class="inputs mb-2">
                            </div>
                        @endforeach
                        @if ($showExistingContent)
                            @if (!empty($recording->content))
                                @if ($recording->contentExists)
                                    <div class="center my-auto flex items-center justify-end py-3">
                                        <label for="backlogFile" class="mr-2 text-red-600">Eliminar</label>
                                        <input type="checkbox" id="backlogFile"
                                            class="delete-checkbox border-gray-300 bg-transparent"
                                            style="height: 24px; width: 24px; accent-color: #2e4c5f;"
                                            wire:model="selectedFiles" value="">
                                    </div>
                                    @if ($type == 'report')
                                        @if ($recording->image == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <a href="{{ asset('reportes/' . $recording->content) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('reportes/' . $recording->content) }}"
                                                        alt="Report Image">
                                                </a>
                                            </div>
                                        @endif
                                        @if ($recording->video == true)
                                            @if (strpos($recording->content, 'Reporte') === 0)
                                                <div class="my-5 w-full text-center text-lg">
                                                    <p class="text-red my-5">Subir video '{{ $recording->content }}'
                                                    </p>
                                                </div>
                                            @else
                                                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                    <a href="{{ asset('reportes/' . $recording->content) }}"
                                                        target="_blank">
                                                        <video src="{{ asset('reportes/' . $recording->content) }}"
                                                            loop autoplay alt="Report Video"></video>
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                        @if ($recording->file == true)
                                            <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                                                @if ($recording->fileExtension === 'pdf')
                                                    <iframe src="{{ asset('reportes/' . $recording->content) }}"
                                                        width="auto" height="600"></iframe>
                                                @else
                                                    <p class="text-center text-base">Vista previa no disponible para
                                                        este tipo de archivo.</p>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        @if ($recording->content == true)
                                            <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                <a href="{{ asset('activities/' . $recording->content) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('activities/' . $recording->content) }}"
                                                        alt="Report Image">
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-files-off">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                            <path
                                                d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                                            <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                        <p>Sin contenido</p>
                                    </div>
                                @endif
                            @else
                                @if ($recording->files->count() > 0)
                                    @foreach ($recording->files as $file)
                                        @if ($file->contentExists)
                                            <div class="file-container mb-6">
                                                <div class="center my-auto flex items-center justify-end py-3">
                                                    <label for="backlogFile{{ $file->id }}"
                                                        class="mr-2 text-red-600">Eliminar</label>
                                                    <input type="checkbox" id="backlogFile{{ $file->id }}"
                                                        class="delete-checkbox border-gray-300 bg-transparent"
                                                        style="height: 24px; width: 24px; accent-color: #2e4c5f;"
                                                        wire:model="selectedFiles" value="{{ $file->id }}">
                                                </div>
                                                @if ($file->image)
                                                    <div class="image-preview">
                                                        <a href="{{ $file->public_url }}" target="_blank"
                                                            class="block">
                                                            <img src="{{ $file->public_url }}" alt="Imagen adjunta"
                                                                class="max-w-full h-auto rounded shadow">
                                                        </a>
                                                    </div>
                                                @elseif ($file->video)
                                                    <div class="video-preview">
                                                        <video controls class="w-full rounded shadow">
                                                            <source src="{{ $file->public_url }}"
                                                                type="video/{{ $file->fileExtension }}">
                                                            Tu navegador no soporta videos HTML5.
                                                        </video>
                                                    </div>
                                                @elseif ($file->file)
                                                    <div class="file-preview">
                                                        @if ($file->fileExtension === 'pdf')
                                                            <iframe src="{{ $file->public_url }}#toolbar=0"
                                                                width="100%" height="500"
                                                                class="border rounded shadow"></iframe>
                                                        @elseif (in_array($file->fileExtension, ['doc', 'docm', 'docx', 'dot', 'dotx']))
                                                            <div class="p-4 text-center flex flex-col items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-doc">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                                    <path
                                                                        d="M5 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                                                    <path
                                                                        d="M20 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" />
                                                                    <path
                                                                        d="M12.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z" />
                                                                </svg>
                                                                <p class="mt-2 text-gray-600">
                                                                    {{ basename($file->route) }}</p>
                                                            </div>
                                                        @elseif (in_array($file->fileExtension, ['xlsx', 'xlsm', 'xlsb', 'xltx', 'csv']))
                                                            <div class="p-4 text-center flex flex-col items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-xls">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                                    <path d="M4 15l4 6" />
                                                                    <path d="M4 21l4 -6" />
                                                                    <path
                                                                        d="M17 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" />
                                                                    <path d="M11 15v6h3" />
                                                                </svg>
                                                                <p class="mt-2 text-gray-600">
                                                                    {{ basename($file->route) }}</p>
                                                            </div>
                                                        @else
                                                            <div class="p-4 text-center flex flex-col items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path
                                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                                </svg>
                                                                <p class="mt-2 text-gray-600">
                                                                    {{ basename($file->route) }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class=" p-4 mb-4">
                                                <div class="center my-auto flex items-center justify-end py-3">
                                                    <label for="backlogFile{{ $file->id }}"
                                                        class="mr-2 text-red-600">Eliminar</label>
                                                    <input type="checkbox" id="backlogFile{{ $file->id }}"
                                                        class="delete-checkbox border-gray-300 bg-transparent"
                                                        style="height: 24px; width: 24px; accent-color: #2e4c5f;"
                                                        wire:model="selectedFiles" value="{{ $file->id }}">
                                                </div>
                                                <div class="flex items-center text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-files-off mr-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                                        <path
                                                            d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                                                        <path
                                                            d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
                                                        <path d="M3 3l18 18" />
                                                    </svg>
                                                    <span>Archivo no encontrado: {{ basename($file->route) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="p-6 text-center">
                                        <h3 class="mt-2 text-lg font-medium">No hay archivos adjuntos</h3>
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="modalFooter">
        <button class="btnSave" wire:click="update({{ $recording->id }}, {{ $recording->project->id }})">
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
        wire:target="selectPriority, changePoints, update">
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
        document.addEventListener('livewire:load', function() {
            Livewire.on('notify', (data) => {
                // Mostrar notificación
                alert(data.message); // O usa SweetAlert/Toast
            });
        });

        window.addEventListener('file-reset', () => {
            document.getElementById('file').value = null;
        });
    </script>
</div>
