<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ARTEN/KIRCOF</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    {{-- https://tabler.io/icons --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- fullcalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

    <!-- alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Toastr -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        /* Style for the range slider */
        input[type="range"] {
            -webkit-appearance: none;
            width: 150px;
            height: 10px;
            border-radius: 5px;
            background: rgb(50 77 87);
            outline: none;
            opacity: 1;
            transition: opacity 0.2s;
        }

        /* Style for the slider thumb */
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgb(221 66 49);
            cursor: pointer;
        }

        input[type="range"]::-moz-range-thumb {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgb(221 66 49);
            cursor: pointer;
        }
    </style>
</head>

<body>
    {{-- MENUS --}}
    <div class="flex items-center justify-center rounded-md py-2 text-text1 bg-primaryColor">
        <div id="mainMenu" class="flex flex-row">
            <a href="{{ route('projects.reports.index', ['project' => $project->id]) }}"
                class="mx-3 w-auto flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back mr-1 w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                </svg>
                Regresar
            </a>
            <button id="screenButton" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-camera w-10 h-10 ">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                    <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                </svg>
            </button>
            <button id="videoButton" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-video w-12 h-12">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z" />
                    <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                </svg>
            </button>
            <button id="textButton" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-file-description w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <path d="M9 17h6" />
                    <path d="M9 13h6" />
                </svg>
            </button>
        </div>
        <div id="screenMenu" class="flex flex-row" style="display: none;">
            <button id="returnButtonImage" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                </svg>
            </button>
            <button id="refreshCanva" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-eraser w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" />
                    <path d="M18 13.3l-6.3 -6.3" />
                </svg>
            </button>
            <input min="1" max="20" value="10" type="range" id="lineWidthSlider" class="mt-5 mx-5">
            <button id="downloadScreen" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-download w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                    <path d="M7 11l5 5l5 -5" />
                    <path d="M12 4l0 12" />
                </svg>
            </button>
        </div>
        <div id="videoMenu" class="flex flex-row" style="display: none;">
            <button id="returnButtonVideo" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
                </svg>
            </button>
            <button id="stopButton" class="mx-3 flex justify-center items-center text-xl hover:opacity-70">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-player-stop w-10 h-10">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 5m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                </svg>
            </button>
        </div>
    </div>
    {{-- VIEW REPORT --}}
    <div id="viewReport" class="w-full" style="display: block;">
        <div class="px-4 pb-4 pt-10 flex h-full w-full">
            {{-- ARCHIVO --}}
            <div class="w-2/4 mr-3">
                <div id="viewPhoto" style="display: none;">
                    <h3 class="text-text2 text-lg font-bold inline-flex">Imagen capturada</h3>
                    <div id="renderedCanvas" class="w-full h-auto"></div>
                </div>
                <div id="viewVideo" style="display: none;">
                    <h3 class="text-text2 text-lg font-bold inline-flex">Video capturado</h3>
                    <video id="recording" width="300" height="200" loop autoplay class="w-full h-2/5"></video>
                    {{-- hidden --}}
                    <div class="justify-center items-center  ">
                        <a id="downloadVideo" class="btnSecondary" style="color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            &nbsp;Descargar
                        </a>
                    </div>
                </div>
            </div>
            {{-- FORMULARIO --}}
            <div class="w-2/4 pl-3 lg:border-l-2 border-gray-400">
                <form id="formReport" action="{{ route('projects.reports.store', ['project' => $project->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- hidden --}}
                    <input  type="text" id="user_id" name="user_id" value="{{ $user->id }}">
                    <input  type="text" id="inputPhoto" name="photo">
                    <input  type="text" id="inputVideo" name="video">
                    <div class="-mx-3 mb-6 flex flex-row">
                        <div id="viewText" class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="code">
                                Selecciona un archivo
                            </h5>
                            <input type="file" name="file" id="file" class="inputs">
                        </div>
                        <div class="w-full flex flex-col px-3">
                            <h5 class="inline-flex font-semibold" for="name">
                                Título del reporte <p class="text-red-600">*</p>
                            </h5>
                            <input required type="text" placeholder="Título del reporte" name="title" id="title"
                                class="inputs">
                            @if ($errors->has('title'))
                            <span class="text-red-600 text-xs italic pl-2">
                                {{ $errors->first('title') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="-mx-3 mb-6">
                        <div class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="name">
                                Descripción del reporte <p class="text-red-600">*</p>
                            </h5>
                            <textarea required type="text" rows="10"
                                placeholder="Describa la observación y especifique el objetivo a cumplir."
                                name="comment" class="inputs"></textarea>
                            @if ($errors->has('comment'))
                            <span class="text-red-600 text-xs italic pl-2">
                                {{ $errors->first('comment') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="-mx-3 mb-6 flex flex-row">
                        <div id="viewText" class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="code">
                                Prioridad <p class="text-red-600">*</p>
                            </h5>
                            <div class="flex justify-center gap-20">
                                <div class="flex flex-col items-center">
                                    <input type="checkbox" name="priority1" id="priority1" value="Alto"
                                        class="priority-checkbox border-red-600 bg-red-600"
                                        style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                    <label for="priority1" class="mt-2">Alto</label>
                                </div>
                                <div class="flex flex-col items-center">
                                    <input type="checkbox" name="priority2" id="priority2" value="Medio"
                                        class="priority-checkbox border-yellow-400 bg-yellow-400"
                                        style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                    <label for="priority2" class="mt-2">Medio</label>
                                </div>
                                <div class="flex flex-col items-center">
                                    <input type="checkbox" name="priority3" id="priority3" value="Bajo"
                                        class="priority-checkbox border-secondary bg-secondary"
                                        style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                    <label for="priority3" class="mt-2">Bajo</label>
                                </div>
                            </div>
                            @if ($errors->has('priority'))
                            <span class="text-red-600 text-xs italic pl-2">
                                {{ $errors->first('priority') }}
                            </span>
                            @endif
                        </div>
                        <div class="w-full flex flex-col px-3">
                            <h5 class="inline-flex font-semibold" for="name">
                                Delegar <p class="text-red-600">*</p>
                            </h5>
                            <select required name="delegate" id="delegate" class="inputs">
                                <option value="0" selected>Selecciona...</option>
                                @foreach ($allUsers as $allUser)
                                    <option value="{{ $allUser->id }}">{{ $allUser->name }} {{ $allUser->lastname }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('delegate'))
                            <span class="text-red-600 text-xs italic pl-2">
                                {{ $errors->first('delegate') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="-mx-3 mb-6 flex flex-row">
                        <div class="w-full flex flex-col px-3 mb-6">
                            <h5 class="inline-flex font-semibold" for="code">
                                Fecha esperada <p class="text-red-600">*</p>
                            </h5>
                            <input required type="date" name="expected_date" id="expected_date" class="inputs">
                        </div>
                        <div class="w-full flex flex-row px-3 m-auto">
                            <h5 class="inline-flex font-semibold" for="name">
                                Evidencia
                            </h5>
                            <input type="checkbox" name="evidence" id="evidence"
                            class="ml-4"
                            style="height: 24px; width: 24px; accent-color: #0062cc;">
                        </div>
                    </div>
                    <div class="flex justify-center items-center mb-6">
                        <button type="submit" class="btnSave">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- PREVISUALIZACION --}}
    <div id="artboard" class="w-full" style="display: none;">
        <h2 class="top-10 left-10 px-2 py-4 font-semibold text-3xl text-text2">Previsualización</h2>
        <div class="w-full flex justify-center items-center">
            <p id="log" class="text-xl font-semibold text-red-600"></p>
            <p id="time" class="mx-3 text-xl font-semibold text-red-600"></p>
        </div>
        {{-- IMAGEN --}}
        <div id="capturedImageContainer" class="flex items-center justify-center" style="display: none;"></div>
        <div id="renderCombinedImage"></div>
        {{-- VIDEO --}}
        <video id="preview" width="100%" height="auto" autoplay muted class="mt-2" style="display: none;"></video>
    </div>
    
    @if(session('error'))
    <script>
        toastr['error']("{{ session('error') }}", "Error");
    </script>
    @endif

    <script>
        // MENUS
        let viewReport = document.getElementById('viewReport');
        let artboard = document.getElementById('artboard');
        let mainMenu = document.getElementById('mainMenu');
        let screenMenu = document.getElementById('screenMenu');
        let videoMenu = document.getElementById('videoMenu');
        // MENU BUTTONS
        let screenButton = document.getElementById('screenButton');
        let videoButton = document.getElementById("videoButton");
        let textButton = document.getElementById('textButton');
        let returnButtonImage = document.getElementById('returnButtonImage');
        // SCREEN
        let refreshCanvaButton = document.getElementById('refreshCanva'); // button with the id "refreshCanva"
        let lineWidthSlider = document.getElementById('lineWidthSlider');
        let downloadScreen = document.getElementById('downloadScreen'); // button with the id "downloadShot"
        let capturedImageContainer = document.getElementById('capturedImageContainer'); // Container to display captured image
        let renderedCanvas = document.getElementById('renderedCanvas');
        let drawCanvas = document.querySelector('canvas');
        // VIDEO
        let returnButtonVideo = document.getElementById('returnButtonVideo');
        let stopButton = document.getElementById("stopButton");
        let logElement = document.getElementById("log");
        let time = document.getElementById("time");
        let preview = document.getElementById("preview");
        let recording = document.getElementById("recording");
        // INPUTS
        let inputUser = document.getElementById("user_id");
        let inputVideo = document.getElementById("inputVideo");
        let inputPhoto = document.getElementById("inputPhoto");
        let file = document.getElementById("file");
        let delegateSelect = document.getElementById('delegate');
        // FORM
        let formReport = document.getElementById('formReport');
        let viewPhoto = document.getElementById('viewPhoto');
        let viewVideo = document.getElementById('viewVideo');
        let viewText = document.getElementById('viewText');
        // BUTTONS FORM
        let checkboxes = document.querySelectorAll('.priority-checkbox');
        let downloadVideo = document.getElementById('downloadVideo');
        // VARIABLES GLOBALES
        let user = @json($user->id);
        let project = @json($project->name);
        // REVISION DE CHECKBOX
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Desmarcar todos los checkboxes
                checkboxes.forEach(function(input) {
                    input.checked = false;
                });
                // Marcar el checkbox seleccionado
                checkbox.checked = true;
            });
        });
        // Función para reiniciar el div y eliminar el dibujo del canvas
        function reiniciarCaptura() {
            capturedImageContainer.innerHTML = ''; // Reiniciar el div de la captura de pantalla
            capturedImageContainer.style.display = 'none';
            if (drawCanvas) {
                drawCanvas.parentNode.removeChild(drawCanvas); // Eliminar el dibujo del overlay canvas
            }
        }
        // Funcion de limpiar formulario
        function cleanForm() {
            const elementosFormulario = formReport.querySelectorAll('input, textarea');
            const selectores = formReport.querySelectorAll('select');

            // Establece los valores de los elementos input y textarea en vacío
            elementosFormulario.forEach(elemento => {
                if (elemento.type !== 'button' && elemento.type !== 'submit') {
                    elemento.value = '';
                }
            });

            // Establece el valor de todos los elementos select en '0'
            selectores.forEach(select => {
                select.value = '0';
            });
        }
        // Vaidacion de formulario
        formReport.addEventListener('submit', function(e) {
            // Verificar si se ha seleccionado una opción diferente de "Selecciona..."
            const selectedValue = delegateSelect.value;
            if (selectedValue === '0') {
                toastr['error']("Debes seleccionar un delegado");
                e.preventDefault();
                return;
            }
            // Verifica si selecciono un checkbox de prioridad
            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            if (!isChecked) {
                toastr['error']("Selecciona la prioridad");
                
                e.preventDefault();
                return; 
            }
            // Verifica si el botón de descarga tiene una URL y descárgalo
            if (downloadVideo.href) {
                setTimeout(function() {
                    downloadVideo.click();
                }, 100);
            }
        });
        // ------------------------------ GRABACION DE VIDEO ------------------------------
        let isManuallyStopped = false; // Variable de control
        // variables "globales VIDEO"
        let startTime, intervalId, mediaRecorder;
        // Nombre del Video con fecha y hora
        let fechaActual = new Date();
        let dia = ("0" + fechaActual.getDate()).slice(-2);
        let mes = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
        let año = fechaActual.getFullYear();
        let horas = ("0" + fechaActual.getHours()).slice(-2);
        let minutos = ("0" + fechaActual.getMinutes()).slice(-2);
        let segundos = ("0" + fechaActual.getSeconds()).slice(-2);
        let fechaEnFormato = año + '-' + mes + '-' + dia + ' ' + horas + '_' + minutos + '_' + segundos;
        // Ayudante para la duración; no ayuda en nada pero muestra algo informativo
        const secondsOnTime = numeroDeSegundos => {
            let horas = Math.floor(numeroDeSegundos / 60 / 60);
            numeroDeSegundos -= horas * 60 * 60;
            let minutos = Math.floor(numeroDeSegundos / 60);
            numeroDeSegundos -= minutos * 60;
            numeroDeSegundos = parseInt(numeroDeSegundos);
            if (horas < 10) horas = "0" + horas;
            if (minutos < 10) minutos = "0" + minutos;
            if (numeroDeSegundos < 10) numeroDeSegundos = "0" + numeroDeSegundos;

            return `${horas}:${minutos}:${numeroDeSegundos}`;
        };
        // Refrescar conteo
        let refresh = () => {
            time.textContent = secondsOnTime((Date.now() - startTime) / 1000);
        }
        // Iniciar conteo
        let startCounting = () => {
            startTime = Date.now();
            intervalId = setInterval(refresh, 500);
        };
        // Detener conteo
        let stopCounting = () => {
            clearInterval(intervalId);
            startTime = null;
            time.textContent = "";
        }
        // Funcion para mostrar mensajes en log
        function log(msg) {
            logElement.innerHTML = msg;
        }

        function startRecording(stream, lengthInMS) {
            mediaRecorder = new MediaRecorder(stream);
            let data = [];

            mediaRecorder.ondataavailable = event => data.push(event.data);

            let stopped = new Promise((resolve, reject) => {
                mediaRecorder.onstop = resolve;
                mediaRecorder.onerror = event => reject(event.name);
            });
            // Manejar el evento oninactive del MediaStream
            stream.oninactive = () => {
                if (!isManuallyStopped) { // Ejecutar solo si no se ha detenido manualmente
                    log("Grabación finalizada.");
                    stopCounting();
                    mediaRecorder.stop();  // Detener la grabación si el stream se vuelve inactivo
                    // INPUTS BUTTONS
                    inputUser.value = user;
                    downloadVideo.download = 'Reporte ' + project + ', ' + fechaEnFormato;
                    inputVideo.value = 'Reporte ' + project + ', ' + fechaEnFormato;
                    // VIEWS
                    viewReport.style.display = 'block';
                    artboard.style.display = 'none';
                    // MENUS
                    mainMenu.style.display = 'flex';
                    videoMenu.style.display = 'none';
                    // FORM
                    viewPhoto.style.display = 'none';
                    viewVideo.style.display = 'block';
                    viewText.style.display = 'none';
                } else {
                    // Posiblemente reiniciar isManuallyStopped para futuras grabaciones
                    isManuallyStopped = false;
                }
            };
            mediaRecorder.start();
            log("Grabación iniciada.");
            // return stopped;
            return stopped.then(() => data);
        }

        function stop(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        // Event listener for the stopButton
        stopButton.addEventListener("click", function() {
            stop(preview.srcObject);
        }, false);

        videoButton.addEventListener("click", function() {
            // VIEW
            viewReport.style.display = 'none';
            artboard.style.display = 'block';
            // MENU
            mainMenu.style.display = 'none';
            videoMenu.style.display = 'flex';
            // VIDEO
            preview.style.display = 'block';
            // SCRENN
            reiniciarCaptura();
            // INPUT
            inputPhoto.value = '';
            file.value = null;

            navigator.mediaDevices.getDisplayMedia({
                video: { 
                    mediaSource: 'screen',
                    width: { ideal: 1024 }, // Ancho deseado del video
                    height: { ideal: 1024 } // Altura deseada del video
                },
                audio: true
            }).then(stream => {
                preview.srcObject = stream;
                downloadVideo.href = stream;
                preview.captureStream = preview.captureStream || preview.mozCaptureStream;
                // Iniciar la grabación automáticamente cuando se obtiene la captura de pantalla
                startCounting();
                return startRecording(stream);
            }).then (recordedChunks => {
                let recordedBlob = new Blob(recordedChunks, { type: "video/mp4" });
                recording.src = URL.createObjectURL(recordedBlob);
                downloadVideo.href = recording.src;
                downloadVideo.download = 'Reporte ' + project + ', ' + fechaEnFormato;
            })
            /* .catch(log); */
        }, false);
        // returnButton from Video
        returnButtonVideo.addEventListener('click', function() {
            preview.src = '';
            downloadVideo.href = '';
            inputVideo.value= '';
            // VIEW
            viewReport.style.display = 'block';
            artboard.style.display = 'none';
            // MENU
            mainMenu.style.display = 'flex';
            videoMenu.style.display = 'none';
            // FORM
            viewVideo.style.display = 'none';
            viewText.style.display = 'block';
        });
        // ------------------------------ CAPTURA DE IMAGEN ------------------------------
        // Function to handle drawing on the overlay canvas
        const drawOnCanvas = (prevX, prevY, x, y, lineWidthValue, ctx) => {
            ctx.beginPath();
            ctx.moveTo(prevX, prevY);
            ctx.lineTo(x, y);
            ctx.lineCap = 'round'; // Make lines rounded
            ctx.strokeStyle = 'rgb(221 66 49)'; // Set the color for drawing (change as needed)
            ctx.lineWidth = lineWidthValue; // Set the line width from the slider
            ctx.stroke();
        };

        screenButton.addEventListener('click', () => {
            // SCREEN
            reiniciarCaptura();
            capturedImageContainer.style.display = 'block';
            // VIEWS
            viewReport.style.display = 'none';
            artboard.style.display = 'block';
            // MENUS
            mainMenu.style.display = 'none';
            screenMenu.style.display = 'flex';
            // VIDEO
            log("");
            preview.style.display = 'none';
            downloadVideo.href = '';
            // INPUT
            inputVideo.value = '';
            file.value = null;

            navigator.mediaDevices.getDisplayMedia({
                video: true,
                audio: false // We don't need audio for screenshots
            }).then(stream => {
                const videoTrack = stream.getVideoTracks()[0];
                const imageCapture = new ImageCapture(videoTrack);

                imageCapture.grabFrame().then(imageBitmap => {
                    const canvas = document.createElement('canvas');
                    canvas.width = imageBitmap.width;
                    canvas.height = imageBitmap.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(imageBitmap, 0, 0);
                    // Create an image element and set the source to the captured image
                    const capturedImage = new Image();
                    capturedImage.src = canvas.toDataURL('image/jpg');

                    capturedImage.onload = () => {
                        capturedImageContainer.innerHTML = ''; // Clear previous content
                        // Asegúrate de que capturedImageContainer tenga una posición relativa
                        capturedImageContainer.style.position = 'relative';
                        capturedImageContainer.style.width = `${canvas.width}px`;
                        capturedImageContainer.style.height = `${canvas.height}px`;
                        capturedImage.style.position = 'relative';
                        capturedImageContainer.appendChild(capturedImage);

                        const drawCanvas = document.createElement('canvas');
                        drawCanvas.width = canvas.width; // Use the width of the captured image
                        drawCanvas.height = canvas.height; // Use the height of the captured image
                        drawCanvas.style.position = 'absolute';
                        drawCanvas.style.top = '0';
                        drawCanvas.style.left = '0';
                        // Append the drawing canvas to the document
                        capturedImageContainer.appendChild(drawCanvas);
                        // Function to get the canvas context for drawing
                        const getDrawContext = () => drawCanvas.getContext('2d');
                        // Event listener for the line width slider
                        lineWidthSlider.addEventListener('input', function () {
                            const lineWidthValue = parseInt(this.value);
                            // Update line width on drawing canvas
                            const drawCtx = drawCanvas.getContext('2d');
                            drawCtx.lineWidth = lineWidthValue;
                        });
                        // letiables to store previous coordinates
                        let prevX, prevY;
                        // Event listeners to handle drawing on mouse interactions
                        let isDrawing = false;
                        drawCanvas.addEventListener('mousedown', e => {
                            isDrawing = true;
                            const rect = drawCanvas.getBoundingClientRect();
                            prevX = e.clientX - rect.left;
                            prevY = e.clientY - rect.top;
                        });

                        drawCanvas.addEventListener('mousemove', e => {
                            if (isDrawing) {
                            const rect = drawCanvas.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;
                            const drawCtx = getDrawContext();
                            drawOnCanvas(prevX, prevY, x, y, parseInt(lineWidthSlider.value), drawCtx);
                            prevX = x;
                            prevY = y;
                            }
                        });

                        drawCanvas.addEventListener('mouseup', () => {
                            isDrawing = false;
                        });

                        drawCanvas.addEventListener('mouseleave', () => {
                            isDrawing = false;
                        });
                        // Function to render the combined image for preview
                        const renderCombinedImage = (combinedDataURL) => {
                            renderedCanvas.innerHTML = ''; // Clear previous content
                            const renderedImage = new Image();
                            renderedImage.src = combinedDataURL;
                            inputPhoto.value = combinedDataURL;
                            inputUser.value = user;
                            renderedCanvas.appendChild(renderedImage);
                        };

                        // Add click event listener to the downloadShot button
                        downloadScreen.addEventListener('click', () => {
                            // VIEWS
                            viewReport.style.display = 'block';
                            artboard.style.display = 'none';
                            // MENUS
                            mainMenu.style.display = 'flex';
                            screenMenu.style.display = 'none';
                            // FORM
                            viewPhoto.style.display = 'block';
                            viewVideo.style.display = 'none';
                            viewText.style.display = 'none';

                            const combinedCanvas = document.createElement('canvas');
                            combinedCanvas.width = imageBitmap.width;
                            combinedCanvas.height = imageBitmap.height;
                            const combinedCtx = combinedCanvas.getContext('2d');
                            // Draw the screen capture onto the combined canvas
                            combinedCtx.drawImage(imageBitmap, 0, 0);
                            // Create a new image element for the drawing canvas
                            const drawImage = new Image();
                            drawImage.src = drawCanvas.toDataURL('image/jpg');
                            // When the drawing canvas image is fully loaded, render it onto the combined canvas
                            drawImage.onload = () => {
                                combinedCtx.drawImage(drawImage, 0, 0);
                                // Get the combined canvas data URL and render the image for preview
                                const combinedDataURL = combinedCanvas.toDataURL('image/jpg');
                                renderCombinedImage(combinedDataURL);
                            };
                            drawCanvas.hidden = true;
                        });
                        // Add click event listener to the refreshCanva button
                        refreshCanvaButton.addEventListener('click', () => {
                            // Obtiene el contexto del canvas de dibujo
                            const drawCtx = drawCanvas.getContext('2d');
                            // Limpia todo el canvas
                            drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
                        });
                    };
                }).catch(error => {
                    console.error('Error grabbing frame:', error);
                });
            }).catch(error => {
            console.error('Error accessing media devices:', error);
            });
        });
        // RETURNBUTTONIMAGE
        returnButtonImage.addEventListener('click', function() {
            reiniciarCaptura();
            // INPUT
            inputPhoto.value = '';
            // Reiniciar el div donde se muestra la vista previa de la imagen combinada
            renderedCanvas.innerHTML = '';
            // VIEW
            viewReport.style.display = 'block';
            artboard.style.display = 'none';
            // MENU
            mainMenu.style.display = 'flex';
            screenMenu.style.display = 'none';
            // FORM
            viewPhoto.style.display = 'none';
            viewText.style.display = 'block';
        });
        // ------------------------------ FORMULARIO NORMAL ------------------------------
        textButton.addEventListener('click', function() {
            reiniciarCaptura();
            downloadVideo.href = '';
            // INPUT
            inputPhoto.value = '';
            inputVideo.value = '';
            // FORM
            viewVideo.style.display = 'none';
            viewPhoto.style.display = 'none';
            viewText.style.display = 'block';
        });
    </script>
</body>

</html>