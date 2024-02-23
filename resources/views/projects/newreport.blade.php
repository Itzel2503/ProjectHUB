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
        .scrollEdit::-webkit-scrollbar {
            width: 6px;
            /* width of the entire scrollbar */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-track {
            background: #ccc;
            /* color of the tracking area */
            border-radius: 20px;
        }

        .scrollEdit::-webkit-scrollbar-thumb {
            background-color: #324d57;
            /* color of the scroll thumb */
            border-radius: 20px;
            /* roundness of the scroll thumb */
            border: 2.5px solid #324d57;
            /* creates padding around scroll thumb */
        }

        .scrollEdit::-webkit-scrollbar-thumb:hover {
            background: #154854;
            box-shadow: 0 0 2px 1px rgb(0 0 0 / 20%);
            border: #ccc;
        }

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
    <div id="mainMenu" class="flex flex-row items-center justify-center rounded-md p-5 bg-main-fund">
        <a href="{{ route('projects.reports.index', ['project' => $project->id]) }}" class="mx-5 w-auto h-12 flex justify-center items-center text-xl text-main hover:text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>     
            Regresar            
        </a>
        <button id="screenshotButton" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>              
        </button>
        <button id="startButton" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>              
        </button>
        <button id="textButton" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>                       
        </button>
    </div>
    
    <div id="shotMenu" class="flex flex-row items-center justify-center rounded-md p-5 bg-main-fund" style="display: none;">
        <button id="returnButton" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>              
        </button>
        <button id="refreshCanva" class="ml-5 w-10 h-10 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-repeat w-12 h-12 text-main hover:text-secondary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 12v-3a3 3 0 0 1 3 -3h13m-3 -3l3 3l-3 3" />
                <path d="M20 12v3a3 3 0 0 1 -3 3h-13m3 3l-3 -3l3 -3" />
            </svg>             
        </button>
        <input min="1" max="20" value="10" type="range" id="lineWidthSlider" class="mt-5 mx-5">
        
        <button id="downloadShot" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>              
        </button>
    </div>

    <div id="videoMenu" class="flex flex-row items-center justify-center rounded-md p-5 bg-main-fund" style="display: none;">
        <button id="returnButtonVideo" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg> 
        </button>
        <button id="stopButton" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.563C9 9.252 9.252 9 9.563 9h4.874c.311 0 .563.252.563.563v4.874c0 .311-.252.563-.563.563H9.564A.562.562 0 0 1 9 14.437V9.564Z" />
            </svg>              
        </button>
    </div>

    <div id="textMenu" class="flex flex-row items-center justify-center rounded-md p-5 bg-main-fund" style="display: none;">
        <button id="returnButtonText" class="mx-5 w-12 h-12 flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-main hover:text-secondary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg> 
        </button>
    </div>

    <div id="rightBar" class="fixed pb-20 top-0 right-0 h-full w-1/3 z-5 overflow-y-auto scrollEdit bg-main-fund z-20" style="display: none;">
        <div class="px-4 pb-4 pt-10  h-full w-full">
            <div id="viewPhoto" style="display: none;">
                <h2 class="inline-flex font-semibold">
                    Imagen capturada
                </h2>
                <div id="renderedCanvas" class="w-full h-auto my-8"></div>
                {{-- <div class="flex justify-center items-center py-6 bg-main-fund">
                    <button id="downloadButton" class="px-4 py-2 mt-5 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">Guardar captura</button>
                </div> --}}
            </div>
            
            <div id="viewVideo" style="display: none;">
                <video id="recording" width="300" height="200" loop autoplay class="my-8 w-full h-2/5"></video>
                <div class="flex justify-center items-center py-6 bg-main-fund hidden">
                    <a id="downloadVideoButton" class="px-4 py-2 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">Descargar video</a>
                </div>
            </div>
            <form id="formReport" action="{{ route('projects.reports.store', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input hidden type="text" id="user_id" name="user_id" value="{{ $user->id }}">
                <input hidden type="text" id="inputPhoto" name="photo">
                <input hidden type="text" id="inputVideo" name="video">

                <div id="viewText" class=" md:flex mb-6" style="display: none;">
                    <div class="w-full flex flex-col px-3 mb-6">
                        <h5 class="inline-flex font-semibold" for="name">
                            Selecciona un archivo
                        </h5>
                        <input type="file" name="file" id="file" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                    </div>
                </div>

                <div class=" mb-6 bg-main-fund">
                    <div class="w-full flex flex-col px-3 mb-6">
                        <h5 class="inline-flex font-semibold" for="name">
                            Título del reporte
                        </h5>
                        <input required type="text" name="title" id="title" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                        @if ($errors->has('title'))
                            <span class="text-red text-xs italic pl-2">
                                    {{ $errors->first('title') }}
                            </span>
                        @endif
                    </div>
                    <div class="w-full flex flex-col px-3 mt-3">
                        <h5 class="inline-flex font-semibold" for="name">
                            Descripción del reporte
                        </h5>
                        <textarea required type="text" rows="10" placeholder="Describa la observación y especifique el objetivo a cumplir." name="comment" id="report" class="fields1 leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto"></textarea> 
                        @if ($errors->has('comment'))
                            <span class="text-red text-xs italic pl-2">
                                    {{ $errors->first('comment') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <div class="w-full flex flex-col px-3 mb-6">
                        <h5 class="inline-flex font-semibold" for="name">
                            Prioridad
                        </h5>
                        <div class="flex justify-center gap-20">
                            <div class="flex flex-col items-center">
                                <input type="checkbox" name="priority1" id="priority1" class="priority-checkbox border-red-600 bg-red-600" style="height: 24px; width: 24px; accent-color: #dd4231;" />
                                <label for="priority1" class="mt-2">Alto</label>
                            </div>
                            <div class="flex flex-col items-center">
                                <input type="checkbox" name="priority2" id="priority2" class="priority-checkbox border-yellow-400 bg-yellow-400" style="height: 24px; width: 24px; accent-color: #f6c03e;" />
                                <label for="priority2" class="mt-2">Medio</label>
                            </div>
                            <div class="flex flex-col items-center">
                                <input type="checkbox" name="priority3" id="priority3" class="priority-checkbox border-secondary bg-secondary" style="height: 24px; width: 24px; accent-color: #0062cc;" />
                                <label for="priority3" class="mt-2">Bajo</label>
                            </div>                            
                        </div>
                        
                        @if ($errors->has('priority'))
                            <span class="text-red text-xs italic pl-2">
                                    {{ $errors->first('priority') }}
                            </span>
                        @endif
                    </div>
                    <div class="w-full flex flex-col px-3 mt-3">
                        <h5 class="inline-flex font-semibold" for="name">
                            Delegar
                        </h5>
                        <select required name="delegate" id="delegate" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                            <option value="0" selected>Selecciona...</option>
                            @foreach ($allUsers as $allUser)
                                <option value="{{ $allUser->id }}">{{ $allUser->name }} {{ $allUser->lastname }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('delegate'))
                            <span class="text-red text-xs italic pl-2">
                                    {{ $errors->first('delegate') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-6">
                    <div class="w-full flex flex-col px-3 mb-6">
                        <h5 class="inline-flex font-semibold" for="name">
                            Fecha esperada
                        </h5>
                        <input required type="date" name="expected_date" id="expected_date" class="leading-snug border border-none block appearance-none bg-white text-gray-700 py-1 px-4 w-full rounded mx-auto">
                    </div>
                    <div class="w-full flex flex-row px-3 mt-3">
                        <h5 class="inline-flex font-semibold mr-5" for="evidence">
                            Evidencia
                        </h5>
                        <input type="checkbox" name="evidence" id="evidence" style="height: 24px; width: 24px; border-color: rgb(); accent-color: " />
                    </div>
                </div>

                <div class="flex justify-center items-center py-6 bg-main-fund">
                    <button type="submit" class="px-4 py-2 font-semibold bg-secondary-fund hover:bg-secondary rounded cursor-pointer" style="color: white;">Guardar</button>
                </div>
            </form>            
        </div>
    </div>

    <div id="artboard" class="w-full">
        <h3 class="top-10 left-10 px-2 py-4 font-semibold text-3xl text-secondary">Previsualización</h3>
        <div class="w-full flex justify-center items-center">
            <p id="log" class="text-xl font-semibold text-red-600"></p>
            <p id="time" class="mx-3 text-xl font-semibold text-red-600"></p>
        </div>

        <div id="capturedImageContainer" class="flex items-center justify-center"></div>
        <div id="renderCombinedImage"></div>
    
        <video id="preview" width="100%" height="auto" autoplay muted class="mt-2"></video>
    </div>

    @if(session('error'))
    <script>
        toastr['error']("{{ session('error') }}", "Error");
    </script>
    @endif

    {{-- RECORDING --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const formReport = document.getElementById('formReport');

            const startButton = document.getElementById("startButton");
            const stopButton = document.getElementById("stopButton");
            const downloadVideoButton = document.getElementById("downloadVideoButton");
            const returnButtonVideo = document.getElementById('returnButtonVideo');

            let preview = document.getElementById("preview");
            let recording = document.getElementById("recording");
            
            let logElement = document.getElementById("log");
            let time = document.getElementById("time");

            const mainMenu = document.getElementById('mainMenu');
            const shotMenu = document.getElementById('shotMenu');
            const rightBar = document.getElementById('rightBar');
            const videoMenu = document.getElementById('videoMenu');

            const viewPhoto = document.getElementById('viewPhoto');
            const viewVideo = document.getElementById('viewVideo');

            let inputUser = document.getElementById("user_id");
            let inputVideo = document.getElementById("inputVideo");

            let user = @json($user);
            let project = @json($project);

            // variables "globales"
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

            const refresh = () => {
                time.textContent = secondsOnTime((Date.now() - startTime) / 1000);
            }
            
            const startCounting = () => {
                startTime = Date.now();
                intervalId = setInterval(refresh, 500);
            };

            const stopCounting = () => {
                clearInterval(intervalId);
                startTime = null;
                time.textContent = "";
            }
            
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
                    log("Grabación finalizada.");
                    stopCounting();
                    mediaRecorder.stop();  // Detener la grabación si el stream se vuelve inactivo

                    inputUser.value = user.id;
                    downloadVideoButton.download = 'Reporte ' + project.name + ', ' + fechaEnFormato;
                    inputVideo.value = 'Reporte ' + project.name + ', ' + fechaEnFormato;

                    rightBar.style.display = 'flex';
                    viewVideo.style.display = 'block';
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

            startButton.addEventListener("click", function() {
                videoMenu.style.display = 'flex';
                mainMenu.style.display = 'none';

                navigator.mediaDevices.getDisplayMedia({
                    video: { mediaSource: 'screen' },
                    audio: true
                }).then(stream => {
                    preview.srcObject = stream;
                    downloadVideoButton.href = stream;
                    preview.captureStream = preview.captureStream || preview.mozCaptureStream;

                    // Iniciar la grabación automáticamente cuando se obtiene la captura de pantalla
                    startCounting();
                    return startRecording(stream);
                }).then (recordedChunks => {
                    let recordedBlob = new Blob(recordedChunks, { type: "video/mp4" });
                    recording.src = URL.createObjectURL(recordedBlob);
                    downloadVideoButton.href = recording.src;
                    downloadVideoButton.download = 'Reporte ' + project.name + ', ' + fechaEnFormato;
                })
                /* .catch(log); */
            }, false);

            // returnButton from Video
            returnButtonVideo.addEventListener('click', function() {
                // Detener el video
                preview.pause();
                preview.srcObject = null;

                if (mediaRecorder) {
                    mediaRecorder.stop();
                }
                log('');
                preview.src = '';
                downloadVideoButton.href = '';

                // Mostrar el div principal y ocultar otros elementos
                rightBar.style.display = 'none';
                viewPhoto.style.display = 'none';
                viewVideo.style.display = 'none';
                mainMenu.style.display = 'flex';
                videoMenu.style.display = 'none';
                cleanForm();
            });

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
        });
    </script>

    {{-- SCREEN --}}
    <script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const formReport = document.getElementById('formReport');

            const screenshotButton = document.getElementById('screenshotButton');
            const downloadShotButton = document.getElementById('downloadShot'); // button with the id "downloadShot"
            const lineWidthSlider = document.getElementById('lineWidthSlider'); // input
            const refreshCanvaButton = document.getElementById('refreshCanva'); // button with the id "refreshCanva"
            const returnButton = document.getElementById('returnButton');

            const capturedImageContainer = document.getElementById('capturedImageContainer'); // Container to display captured image
            const renderedCanvas = document.getElementById('renderedCanvas');

            const mainMenu = document.getElementById('mainMenu');
            const shotMenu = document.getElementById('shotMenu');
            const rightBar = document.getElementById('rightBar');

            const viewPhoto = document.getElementById('viewPhoto');
            const viewVideo = document.getElementById('viewVideo');

            let inputPhoto = document.getElementById("inputPhoto");
            let inputUser = document.getElementById("user_id");

            let user = @json($user);

            screenshotButton.addEventListener('click', () => {
                mainMenu.style.display = 'none';
                shotMenu.style.display = 'flex';

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
                                inputUser.value = user.id;
                                renderedCanvas.appendChild(renderedImage);
                            };

                            // Add click event listener to the downloadShot button
                            downloadShotButton.addEventListener('click', () => {
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

                                rightBar.style.display = 'flex';
                                viewPhoto.style.display = 'block';
                            });
                            
                            refreshCanvaButton.addEventListener('click', () => {
                                // Obtiene el contexto del canvas de dibujo
                                const drawCtx = drawCanvas.getContext('2d');
                                // Limpia todo el canvas
                                drawCtx.clearRect(0, 0, drawCanvas.width, drawCanvas.height);
                            });

                            // Function to handle the download
                            // const downloadImage = () => {
                            //     const combinedCanvas = document.createElement('canvas');
                            //     combinedCanvas.width = imageBitmap.width;
                            //     combinedCanvas.height = imageBitmap.height;
                            //     const combinedCtx = combinedCanvas.getContext('2d');

                            //     // Draw the screen capture onto the combined canvas
                            //     combinedCtx.drawImage(imageBitmap, 0, 0);

                            //     // Create a new image element for the drawing canvas
                            //     const drawImage = new Image();
                            //     drawImage.src = drawCanvas.toDataURL('image/jpg');

                            //     // When the drawing canvas image is fully loaded, render it onto the combined canvas
                            //     drawImage.onload = () => {
                            //         combinedCtx.drawImage(drawImage, 0, 0);

                            //         // Get the combined canvas data URL and create a download link
                            //         const combinedDataURL = combinedCanvas.toDataURL('image/jpg');
                            //         const a = document.createElement('a');
                            //         a.href = combinedDataURL;
                            //         a.download = 'Reporte.jpg';
                            //         a.click();
                            //     };
                            // };    
                            // // button with the id "downloadButton"
                            // const downloadButton = document.getElementById('downloadButton');
                            // // Add click event listener to the download button
                            // downloadButton.addEventListener('click', downloadImage);
                        };
                    }).catch(error => {
                        console.error('Error grabbing frame:', error);
                    });
                }).catch(error => {
                console.error('Error accessing media devices:', error);
                });
            });

            //returnButton from screenshot
            returnButton.addEventListener('click', function() {
                // Reiniciar el div donde se muestra la captura de pantalla
                capturedImageContainer.innerHTML = '';
                capturedImageContainer.style = '';

                // Eliminar el dibujo realizado en el overlay canvas
                const drawCanvas = document.querySelector('canvas');
                if (drawCanvas) {
                    drawCanvas.parentNode.removeChild(drawCanvas);
                }

                inputPhoto.value = '';
                // Reiniciar el div donde se muestra la vista previa de la imagen combinada
                renderedCanvas.innerHTML = '';

                // Mostrar el div principal y ocultar otros elementos
                mainMenu.style.display = 'flex';
                shotMenu.style.display = 'none';
                rightBar.style.display = 'none';
                viewPhoto.style.display = 'none';
                viewVideo.style.display = 'none';
                cleanForm();
            });

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
        });
    </script>

    {{-- BUTTONS --}}
    <script>
        const formReport = document.getElementById('formReport');

        const mainMenu = document.getElementById('mainMenu');
        const shotMenu = document.getElementById('shotMenu');
        const textMenu = document.getElementById('textMenu');
        const rightBar = document.getElementById('rightBar');
        const videoMenu = document.getElementById('videoMenu');

        const viewPhoto = document.getElementById('viewPhoto');
        const viewVideo = document.getElementById('viewVideo');
        const viewText = document.getElementById('viewText');

        const textButton = document.getElementById('textButton');
        const returnButtonText = document.getElementById('returnButtonText');
        const downloadVideoButton = document.getElementById('downloadVideoButton');

        let inputUser = document.getElementById("user_id");
        let inputVideo = document.getElementById("inputVideo");

        let user = @json($user);

        document.querySelectorAll('.priority-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Desmarcar todos los checkboxes
                document.querySelectorAll('.priority-checkbox').forEach(function(input) {
                    input.checked = false;
                });
                // Marcar el checkbox seleccionado
                checkbox.checked = true;
            });
        });

        formReport.addEventListener('submit', function(e) {
            if (downloadVideoButton.href) {
                setTimeout(function() {
                    downloadVideoButton.click();
                }, 100);
            }
        });

        //text button
        textButton.addEventListener('click', function() {
            rightBar.style.display = 'flex';
            textMenu.style.display = 'flex';
            mainMenu.style.display = 'none';
            viewText.style.display = 'block';
            inputUser.value = user.id;;
        });
        //returnButtonText from text
        returnButtonText.addEventListener('click', function() {
            rightBar.style.display = 'none';
            viewPhoto.style.display = 'none';
            viewVideo.style.display = 'none';
            mainMenu.style.display = 'flex';
            textMenu.style.display = 'none';
            viewText.style.display = 'none';
            cleanForm();
        });

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
    </script>
</body>
</html>