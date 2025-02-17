<div>
    <div class="text-justify text-base">
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
                <div class="{{ $message->user_id == Auth::user()->id ? 'justify-end' : 'justify-start' }} flex">
                    <div class="mx-2 items-center">
                        @if ($message->user_id == Auth::user()->id)
                            <div class="flex items-start justify-end">
                                <!-- Columna para el mensaje -->
                                <div>
                                    <div class="text-right">
                                        <span class="font-light italic">{{ $message->created_at->format('H:i') }}</span>
                                        <span class="text-sm font-semibold text-black">Tú</span>
                                    </div>
                                    @if (!empty($message->content))
                                        <div class="bg-primaryColor rounded-xl p-2 text-right">
                                            @if ($message->contentExists)
                                                @if ($message->image == true)
                                                    <span
                                                        class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                    <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                        <a href="{{ asset('notion/' . $message->content) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('notion/' . $message->content) }}"
                                                                alt="Report Image">
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($message->file == true)
                                                    <span
                                                        class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                    <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                                                        <div class="flex flex-row justify-end">
                                                            @if ($message->fileExtension === 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                                                    <path d="M17 18h2" />
                                                                    <path d="M20 15h-3v6" />
                                                                    <path
                                                                        d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                                                </svg>
                                                            @elseif (in_array($message->fileExtension, ['doc', 'docm', 'docx', 'dot', 'dotx']))
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
                                                            @elseif (in_array($message->fileExtension, ['xlsx', 'xlsm', 'xlsb', 'xltx']))
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
                                                            @endif
                                                            <p class="text-xs w-1/2 break-words mx-2">
                                                                {{ $message->fileName }}</p>
                                                            <a href="{{ asset('notion/' . $message->content) }}"
                                                                download="{{ basename($message->content) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-download"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                                    <path d="M7 11l5 5l5 -5" />
                                                                    <path d="M12 4l0 12" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <span
                                                    class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                <div
                                                    class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-files-off">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                                        <path
                                                            d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                                                        <path
                                                            d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
                                                        <path d="M3 3l18 18" />
                                                    </svg>
                                                    <p>Sin contenido</p>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-primaryColor rounded-xl p-2 text-right">
                                            <span
                                                class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Columna para la foto de perfil -->
                                <div class="ml-1 mt-1 flex w-auto justify-end">
                                    <div class="relative flex justify-center">
                                        @if ($userPhoto == true)
                                            <img class="h-8 w-8 rounded-full object-cover" aria-hidden="true"
                                                src="{{ asset('usuarios/' . Auth::user()->profile_photo) }}"
                                                alt="Avatar" />
                                        @else
                                            <img class="h-8 w-8 rounded-full object-cover" aria-hidden="true"
                                                src="{{ Avatar::create(Auth::user()->name)->toBase64() }}"
                                                alt="Avatar" />
                                        @endif
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
                                            <img class="h-8 w-8 rounded-full object-cover" aria-hidden="true"
                                                src="{{ asset('images/icons/user-off.png') }}" alt="Avatar" />
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
                                    @if (!empty($message->content))
                                        <div class="bg-primaryColor rounded-xl p-2 text-right">
                                            @if ($message->contentExists)
                                                @if ($message->image == true)
                                                    <span
                                                        class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                    <div class="md-3/4 mb-5 mt-3 flex w-full flex-col">
                                                        <a href="{{ asset('notion/' . $message->content) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('notion/' . $message->content) }}"
                                                                alt="Report Image">
                                                        </a>
                                                    </div>
                                                @endif
                                                @if ($message->file == true)
                                                    <span
                                                        class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                    <div class="md-3/4 mb-3 mt-5 flex w-full flex-col">
                                                        <div class="flex flex-row justify-end">
                                                            @if ($message->fileExtension === 'pdf')
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                                                                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
                                                                    <path d="M17 18h2" />
                                                                    <path d="M20 15h-3v6" />
                                                                    <path
                                                                        d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
                                                                </svg>
                                                            @elseif (in_array($message->fileExtension, ['doc', 'docm', 'docx', 'dot', 'dotx']))
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
                                                            @elseif (in_array($message->fileExtension, ['xlsx', 'xlsm', 'xlsb', 'xltx']))
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
                                                            @endif
                                                            <p class="text-xs w-1/2 break-words mx-2">
                                                                {{ $message->fileName }}</p>
                                                            <a href="{{ asset('notion/' . $message->content) }}"
                                                                download="{{ basename($message->content) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-download"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="1.5" stroke="currentColor"
                                                                    fill="none" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none" />
                                                                    <path
                                                                        d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                                    <path d="M7 11l5 5l5 -5" />
                                                                    <path d="M12 4l0 12" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <span
                                                    class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                                <div
                                                    class="md-3/4 mb-5 mt-3 flex w-full flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-files-off">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                                        <path
                                                            d="M17 17h-6a2 2 0 0 1 -2 -2v-6m0 -4a2 2 0 0 1 2 -2h4l5 5v7c0 .294 -.063 .572 -.177 .823" />
                                                        <path
                                                            d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2" />
                                                        <path d="M3 3l18 18" />
                                                    </svg>
                                                    <p>Sin contenido</p>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-primaryColor rounded-xl p-2 text-right">
                                            <span
                                                class="text-base font-extralight text-gray-600">{{ $message->message }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="my-6 flex w-auto flex-row">
        @if (isset($recording->user))
            <input wire:model.defer='message' type="text" name="message" id="message" class="inputs"
                style="border-radius: 0.5rem 0px 0px 0.5rem !important"
                @if (Auth::user()->type_user != 3) placeholder="Mensaje"
                        @else
                            placeholder="Mensaje para Arten" @endif>
            <!-- Input para enviar archivos (documentos e imágenes) -->
            <input type="file" name="file" id="file" class="inputs" wire:model="file"
                style="display: none;">
            <label for="file" class="btnSave"
                style="border-radius: 0rem 0rem 0rem 0rem !important; padding: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" />
                </svg>
            </label>
            <button class="btnSave" wire:click="sendMessage({{ $recording->id }})"
                style="border-radius: 0rem 0.5rem 0.5rem 0rem !important">
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
    <div class="my-6 flex w-auto flex-row">
        @if (isset($recording->user))
            <input disabled type="text" name="file-name" id="file-name"
                class="inputs {{ $fileName ? '' : 'hidden' }}" placeholder="Archivo" value="{{ $fileName }}">
        @endif
    </div>
    {{-- LOADING PAGE --}}
    <div class="absolute left-0 top-0 z-50 h-screen w-full" wire:loading
        wire:target="$set('isOptionsVisibleState'), create, filterDown, filterUp, showReport, togglePanel, editReport, deleteReport, reportRepeat, updateEvidence, finishEvidence">
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
    @push('js')
        <script>
            // MODALS
            window.addEventListener('swal:modal', event => {
                toastr[event.detail.type](event.detail.text, event.detail.title);
            });

            const scrollToBottom = () => {
                const messageContainer = document.getElementById("messageContainer");
                if (messageContainer) {
                    // Añadimos un pequeño retraso para asegurar el render
                    setTimeout(() => {
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                    }, 100); // Ajusta el tiempo si es necesario
                }
            };

            // Escucha el evento desde Livewire
            window.addEventListener('scroll-to-bottom', () => {
                scrollToBottom();
            });
        </script>
    @endpush
</div>
