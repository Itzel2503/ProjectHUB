<div class="overflow-y-auto">
    <div class="flex flex-col items-stretch overflow-y-auto bg-white px-6 py-2 text-sm lg:flex-row">
        <div
            class="md-3/4 mb-5 mt-3 flex w-full flex-col justify-between border-gray-400 px-5 md:mb-0 lg:w-1/2 lg:border-r-2">
            <div class="text-justify text-base">
                <h3 class="text-text2 text-lg font-bold">Descripción</h3>
                {!! nl2br($recording->description) !!}
                <br><br>
                @if ($showChat)
                <h3 class="text-text2 text-base font-semibold">Comentarios</h3>
                <div id="messageContainer"
                    class="border-primaryColor max-h-80 overflow-y-scroll rounded-br-lg border-4 px-2 py-2">
                    @php
                    $previousDate = null;
                    @endphp
                    @foreach ($chat as $index => $message)
                    @php
                    // Formato de la fecha para agrupar por día
                    $currentDate = $message->created_at->format('Y-m-d');
                    @endphp
                    @if ($previousDate !== $currentDate)
                    <!-- Mostrar la fecha solo cuando cambia -->
                    <div class="py-2 text-center text-xs font-bold text-gray-500">
                        {{ $message->created_at->format('d M Y') }}
                    </div>
                    @php
                    // Actualizar la fecha previa
                    $previousDate = $currentDate;
                    @endphp
                    @endif
                    <div
                        class="{{ $message->user_id == Auth::user()->id ? 'justify-end' : 'justify-start' }} flex">
                        <div class="mx-2 items-center">
                            @if ($message->user_id == Auth::user()->id)
                            <div class="flex items-start justify-end">
                                <!-- Columna para el mensaje -->
                                <div>
                                    <div class="text-right">
                                        <span
                                            class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                        <span class="text-sm font-semibold text-black">Tú</span>
                                    </div>
                                    <div class="bg-primaryColor rounded-xl p-2 text-right">
                                        <span
                                            class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                    </div>
                                </div>
                                <!-- Columna para la foto de perfil -->
                                <div class="ml-1 mt-1 flex w-auto justify-end">
                                    <div class="relative flex justify-center">
                                        @if ($userPhoto == true)
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            aria-hidden="true"
                                            src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}"
                                            alt="Avatar" />
                                        @else
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            aria-hidden="true"
                                            src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                                            alt="Avatar" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                            @if (Auth::user()->type_user == 3)
                            <div class="flex items-start justify-end">
                                <!-- Columna para la foto de perfil -->
                                <div class="mr-1 mt-1 flex w-1/6 justify-end">
                                    <div class="relative flex justify-center">
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            aria-hidden="true" src="{{ asset('logos/favicon_v2.png') }}"
                                            alt="Avatar" />
                                    </div>
                                </div>
                                <!-- Columna para el mensaje -->
                                <div>
                                    <div class="text-left">
                                        <span class="text-sm font-semibold text-black">ARTEN</span>
                                        <span
                                            class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="rounded-xl bg-gray-200 p-2 text-left">
                                        <span
                                            class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="flex items-start justify-end">
                                <!-- Columna para la foto de perfil -->
                                <div class="mr-1 mt-1 flex w-auto justify-end">
                                    <div class="relative flex justify-center">
                                        @if ($message->transmitter)
                                        @if ($message->transmitterContentExists == true)
                                        <img class="h-8 w-8 rounded-full object-cover" aria-hidden="true"
                                            src="{{ asset('usuarios/' . $message->transmitter->profile_photo) }}"
                                            alt="Avatar" />
                                        @else
                                        <img class="h-8 w-8 rounded-full object-cover" aria-hidden="true"
                                            src="{{ Avatar::create($message->transmitter->name)->toBase64() }}"
                                            alt="Avatar" />
                                        @endif
                                        @else
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            aria-hidden="true"
                                            src="{{ asset('images/icons/user-off.png') }}"
                                            alt="Avatar" />
                                        @endif
                                    </div>
                                </div>
                                <!-- Columna para el mensaje -->
                                <div>
                                    <div class="text-left">
                                        <span
                                            class="text-sm font-semibold text-black">{{ $message->transmitter ? $message->transmitter->name : 'Usuario eliminado' }}</span>
                                        <span
                                            class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="rounded-xl bg-gray-200 p-2 text-left">
                                        <span
                                            class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="my-6 flex w-auto flex-row">
                @if (isset($recording->user))
                <input wire:model.defer='message' type="text" name="message" id="message" class="inputs"
                    style="border-radius: 0.5rem 0px 0px 0.5rem !important"
                    @if (Auth::user()->type_user != 3) placeholder="Mensaje"
                @else
                placeholder="Mensaje para Arten" @endif>
                <button class="@if ($recording->delegate) btnSave @else btnDisabled @endif"
                    style="border-radius: 0rem 0.5rem 0.5rem 0rem !important"
                    @if ($recording->delegate) wire:click="sendMessage({{ $recording->id }})" @endif>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 14l11 -11" />
                        <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                    </svg>
                    Comentar
                </button>
                @endif
            </div>
        </div>
        <div class="photos w-full px-5 lg:w-1/2 @if ($showEvidence) hidden @endif">
            <div class="mb-6 w-auto">
                <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                    <div class="text-text2 text-center text-xl font-semibold md:flex">Detalle</div>
                    @if ($recording->evidence == true)
                    <div class="btnIcon cursor-pointer font-semibold text-blue-500 hover:text-blue-400" wire:click="toggleEvidence">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path
                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                        </svg>
                        &nbsp; Evidencia
                    </div>
                    @endif
                </div>
                @if (!empty($recording->content))
                @if ($recording->contentExists)
                @if ($type == 'report')
                @if ($recording->image == true)
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                    <a href="{{ asset('reportes/' . $recording->content) }}" target="_blank">
                        <img src="{{ asset('reportes/' . $recording->content) }}" alt="Report Image">
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
                    <a href="{{ asset('reportes/' . $recording->content) }}" target="_blank">
                        <video src="{{ asset('reportes/' . $recording->content) }}" loop autoplay
                            alt="Report Video"></video>
                    </a>
                </div>
                @endif
                @endif
                @if ($recording->file == true)
                <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                    @if ($recording->fileExtension === 'pdf')
                    <iframe src="{{ asset('reportes/' . $recording->content) }}" width="auto"
                        height="600"></iframe>
                    @else
                    <p class="text-center text-base">Vista previa no disponible para
                        este tipo de archivo.</p>
                    @endif
                </div>
                @endif
                @else
                @if ($recording->content == true)
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                    <a href="{{ asset('activities/' . $recording->content) }}" target="_blank">
                        <img src="{{ asset('activities/' . $recording->content) }}" alt="Report Image">
                    </a>
                </div>
                @endif
                @endif
                @else
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
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
                @if ($recording->image == true || $recording->video == true || $recording->file == true || $recording->content == true)
                @if ($recording->contentExists)
                <div class="flex items-center justify-center mt-2">
                    <a href="{{ asset('reportes/' . $recording->content) }}"
                        download="{{ basename($recording->content) }}" class="btnSecondary"
                        style="color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                            <path d="M7 11l5 5l5 -5" />
                            <path d="M12 4l0 12" />
                        </svg>
                        &nbsp;
                        Descargar
                    </a>
                </div>
                @endif
                @endif
                @else
                @if ($recording->files->count() > 0)
                @foreach ($recording->files as $file)
                @if ($file->contentExists)
                <div class="file-container mb-6">
                    @if ($file->image)
                    <div class="image-preview">
                        <a href="{{ $file->public_url }}" target="_blank" class="block">
                            <img src="{{ $file->public_url }}" alt="Imagen adjunta" class="max-w-full h-auto rounded shadow">
                        </a>
                    </div>
                    @elseif ($file->video)
                    <div class="video-preview">
                        <video controls class="w-full rounded shadow">
                            <source src="{{ $file->public_url }}" type="video/{{ $file->fileExtension }}">
                            Tu navegador no soporta videos HTML5.
                        </video>
                    </div>
                    @elseif ($file->file)
                    <div class="file-preview">
                        @if ($file->fileExtension === 'pdf')
                        <iframe src="{{ $file->public_url }}#toolbar=0" width="100%" height="500" class="border rounded shadow"></iframe>
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
                            <p class="mt-2 text-gray-600">{{ basename($file->route) }}</p>
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
                            <p class="mt-2 text-gray-600">{{ basename($file->route) }}</p>
                        </div>
                        @else
                        <div class="p-4 text-center flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-600">{{ basename($file->route) }}</p>
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="flex items-center justify-center mt-2">
                        <a href="{{ $file->public_url }}" download="{{ basename($file->route) }}" class="btnSecondary"
                            style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            &nbsp;
                            Descargar
                        </a>
                    </div>
                </div>
                @else
                <div class=" p-4 mb-4">
                    <div class="flex items-center text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-files-off mr-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                            <path
                                d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                            <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
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
            </div>
        </div>
        <div class="photos w-full px-5 lg:w-1/2 @if (!$showEvidence) hidden @endif">
            <div class="flex flex-col">
                <div class="md-3/4 mb-5 mt-5 flex w-full flex-row justify-between">
                    <div class="text-center text-xl font-semibold text-gray-700 md:flex">
                        Evidencia
                    </div>
                    <div class="btnIcon cursor-pointer font-semibold text-red-500 hover:text-red-500" wire:click="toggleEvidence">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-x"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path
                                d="M13.048 17.942a9.298 9.298 0 0 1 -1.048 .058c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6a17.986 17.986 0 0 1 -1.362 1.975" />
                            <path d="M22 22l-5 -5" />
                            <path d="M17 22l5 -5" />
                        </svg> &nbsp; Evidencia
                    </div>
                </div>
                @if (!empty($evidenceShow->content))
                @if ($evidenceShow->image == true)
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                    <img src="{{ asset('evidence/' . $evidenceShow->content) }}" alt="Report Image">
                </div>
                @endif
                @if ($evidenceShow->video == true)
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                    <video src="{{ asset('evidence/' . $evidenceShow->content) }}" loop autoplay
                        alt="Report Video"></video>
                </div>
                @endif
                @if ($evidenceShow->file == true)
                <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                    <iframe src="{{ asset('evidence/' . $evidenceShow->content) }}" width="auto"
                        height="800"></iframe>
                </div>
                @endif
                @else
                <div class="my-5 w-full text-center text-lg text-red-500">
                    <p class="text-red my-5">Sin evidencia</p>
                </div>
                @endif
            </div>
        </div>
        {{-- LOADING PAGE --}}
        <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
            wire:target="sendMessage, toggleEvidence">
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
    </div>
    <script>
        // Scroll de Comentrios de modal
        const scrollToBottom = () => {
            const messageContainer = document.getElementById("messageContainer");
            if (messageContainer) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        };
        // Desplázate al final cuando la página cargue
        scrollToBottom();

        let banEvidence = false;

        function toogleEvidence() {
            let evidence = document.getElementById('evidence');
            let example = document.getElementById('example');
            evidence.classList.toggle("hidden");
            example.classList.toggle("hidden");
        }
    </script>
</div>