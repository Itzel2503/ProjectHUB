<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        #capturedImageContainer{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #log{
            color: #1c274c;
        }
        #rightBar{
            position: fixed;
            top: 0;
            right: 0;
            width: 30%;
            height: 100%;
            background-color: white;
            z-index: 5;
        }
        .rightBarContent{
            padding: 1em;
        }
        .widget{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            background-color: white;
            box-shadow: rgba(0, 0, 0, 0.055) 0 -3px 5px 3px;
            border-radius: 200px;
            padding: 10px;
        }
        .iconsContainer{
            display: flex;
            flex-direction: row;
            height: 40px;
            width: 40px;
            border-radius: 90px;
            margin: 0 10px 0 10px;
            border: none;
            background-color: rgb(240, 240, 240);
            padding: 3px;
        }
        video {
            margin-top: 2px;
            border: 0px solid rgba(0, 0, 0, 0.589);
        }
        .renderedCanvas img {
            width: 100%;
            height: auto;
            background-color: #1c274c;
        }
        #recording {
            max-width: 100%;
            max-height: 40%;
        }
        .title{
            position: fixed;
            top: 10;
            left: 10;
            color: white;
            background-color: #07102e62;
            padding: 10px 20px 10px 20px;
            border-radius: 100px;
        }
        .button {
            cursor: pointer;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        #downloadButton{
            margin-top: 10px;
        }
        .secondaryButton {
            box-shadow:inset 0px 1px 0px 0px #ffffff;
            background:linear-gradient(to bottom, #ffffff 5%, #f6f6f6 100%);
            background-color:#ffffff;
            border-radius:6px;
            border:1px solid #dcdcdc;
            display:inline-block;
            cursor:pointer;
            color:#666666;
            font-family:Arial;
            font-size:15px;
            font-weight:bold;
            padding:6px 24px;
            text-decoration:none;
            text-shadow:0px 1px 0px #ffffff;
            
        }
        .secondaryButton:hover {
            background:linear-gradient(to bottom, #f6f6f6 5%, #ffffff 100%);
            background-color:#f6f6f6;
            
        }
        h2 {
            margin-bottom: 4px;
        }
        .left {
            width: 100%;
        }
        .right {
            width: 100%;
        }
        .bottom {
            clear: both;
            padding-top: 10px;
        }
        /* Style for the range slider */
        input[type="range"] {
            -webkit-appearance: none;
            width: 100px;
            height: 10px;
            border-radius: 5px;
            background: #d3d3d3; /* Gray background */
            outline: none;
            opacity: 1;
            transition: opacity 0.2s;
            margin-bottom: 15px;
        }
        /* Style for the slider thumb */
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #1c274c; /* Green thumb */
            cursor: pointer;
        }
        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #1c274c; /* Green thumb */
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="mainMenu" class="widget">
        <button id="screenshotButton" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>              
        </button>
        <button id="startButton" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>              
        </button>
        <button id="textButton" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>                       
        </button>
    </div>
    
    <div id="shotMenu" class="widget" style="display: none;">
        <button id="returnButton" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>              
        </button>
        <button class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
            </svg>              
        </button>

        <input type="range" id="lineWidthSlider" min="1" max="20" value="10">
        
        <button id="downloadShot" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>              
        </button>
    </div>

    <div id="videoMenu" class="widget" style="display: none;">
        <button id="returnButtonVideo" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg> 
        </button>
        <button id="stopButton" class="iconsContainer button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.563C9 9.252 9.252 9 9.563 9h4.874c.311 0 .563.252.563.563v4.874c0 .311-.252.563-.563.563H9.564A.562.562 0 0 1 9 14.437V9.564Z" />
            </svg>              
        </button>
    </div>

    <div id="rightBar" style="display: none;">
        <div class="rightBarContent">
            <h2>Imagen capturada.</h2>
            <div id="renderedCanvas" class="renderedCanvas"></div>
            <button id="downloadButton" class="button secondaryButton">Guardar captura</button>
            
            <h2>Video capturado.</h2>
            <video id="recording" width="300" height="200" controls></video>
            <a id="downloadVideoButton" class="button secondaryButton">
                Descargar video
            </a>
            
            <form action="{{route('reports.store')}}" method="POST">
            @csrf 
                <p id="log"></p>
                <h3>Título del reporte</h3>
                <input type="text" name="title">
                <h3>Descripción del reporte</h3>
                <textarea type="text" name="report" placeholder="Describir observación y especificar objetivo a cumplir" style="width:100%; min-height: 100px;"></textarea>
                <button type="submit">Guardar</button>
            </form>

        </div>
    </div>

    <div id="artboard" class="left">
        <h2 class="title">Previsualización</h2>

        <div id="capturedImageContainer"></div>
        <div id="renderCombinedImage"></div>
    
        <video id="preview" width="100%" height="auto" autoplay muted></video>
    </div>

    {{-- CORDER --}}
    <script>
        let preview = document.getElementById("preview");
        let recording = document.getElementById("recording");
        let startButton = document.getElementById("startButton");
        let stopButton = document.getElementById("stopButton");
        let downloadVideoButton = document.getElementById("downloadVideoButton");
        let logElement = document.getElementById("log");

        let recordingTimeMS = 20000;
        
        function log(msg) {
            logElement.innerHTML += msg + "\n";
        }
    
        function wait(delayInMS) {
            return new Promise(resolve => setTimeout(resolve, delayInMS));
        }

        function startRecording(stream, lengthInMS) {
            let recorder = new MediaRecorder(stream);
            let data = [];
        
            recorder.ondataavailable = event => data.push(event.data);
            recorder.start();
            log(recorder.state + "" + (lengthInMS/1000) + "segundos de video.");
        
            let stopped = new Promise((resolve, reject) => {
                recorder.onstop = resolve;
                recorder.onerror = event => reject(event.name);
            });

            let recorded = wait(lengthInMS)
            .then(() => {
                if (recorder.state == "capturando") {
                recorder.stop();
                }
            });
        
            return Promise.all([
                stopped,
                recorded
            ])
            .then(() => data);
        }

        function stop(stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        startButton.addEventListener("click", function() {
            navigator.mediaDevices.getDisplayMedia({
                video: true,
                audio: true
            }).then(stream => {
                preview.srcObject = stream;
                downloadVideoButton.href = stream;
                preview.captureStream = preview.captureStream || preview.mozCaptureStream;
                return new Promise(resolve => preview.onplaying = resolve);
            }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
            .then (recordedChunks => {
                let recordedBlob = new Blob(recordedChunks, { type: "video/webm" });
                recording.src = URL.createObjectURL(recordedBlob);
                downloadVideoButton.href = recording.src;
                downloadVideoButton.download = "Reporte.mp4";
                
                log("Capturado correctamente " + recordedBlob.size + " bytes de " +
                    recordedBlob.type + " media.");
            })
            /* .catch(log); */
        }, false);

        stopButton.addEventListener("click", function() {
            stop(preview.srcObject);
        }, false);
    </script>

    {{-- SCREEN --}}
    <script>
        // Function to handle drawing on the overlay canvas
        const drawOnCanvas = (prevX, prevY, x, y, lineWidthValue, ctx) => {
            ctx.beginPath();
            ctx.moveTo(prevX, prevY);
            ctx.lineTo(x, y);
            ctx.lineCap = 'round'; // Make lines rounded
            ctx.strokeStyle = 'red'; // Set the color for drawing (change as needed)
            ctx.lineWidth = lineWidthValue; // Set the line width from the slider
            ctx.stroke();
        };

        document.addEventListener('DOMContentLoaded', () => {
            const screenshotButton = document.getElementById('screenshotButton');
            const lineWidthSlider = document.getElementById('lineWidthSlider');
            const capturedImageContainer = document.getElementById('capturedImageContainer'); // Container to display captured image

            screenshotButton.addEventListener('click', () => {
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
                        capturedImage.src = canvas.toDataURL('image/png');

                        capturedImage.onload = () => {
                            capturedImageContainer.innerHTML = ''; // Clear previous content
                            capturedImageContainer.appendChild(capturedImage);

                            // Retrieve the dimensions and position of the captured image
                            const imageRect = capturedImage.getBoundingClientRect();

                            // Create a drawing canvas for overlay
                            const drawCanvas = document.createElement('canvas');
                            drawCanvas.width = imageRect.width; // Use the width of the captured image
                            drawCanvas.height = imageRect.height; // Use the height of the captured image
                            drawCanvas.style.position = 'absolute';
                            drawCanvas.style.top = `${imageRect.top}px`; // Position the canvas at the same top position as the image
                            drawCanvas.style.left = `${imageRect.left}px`; // Position the canvas at the same left position as the image

                            // Append the drawing canvas to the document
                            document.body.appendChild(drawCanvas);

                            // Function to get the canvas context for drawing
                            const getDrawContext = () => drawCanvas.getContext('2d');

                            // Event listener for the line width slider
                            lineWidthSlider.addEventListener('input', function () {
                                const lineWidthValue = parseInt(this.value);
                                // Update line width on drawing canvas
                                const drawCtx = drawCanvas.getContext('2d');
                                drawCtx.lineWidth = lineWidthValue;
                            });

                            // Variables to store previous coordinates
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
                                const renderedCanvas = document.getElementById('renderedCanvas');
                                renderedCanvas.innerHTML = ''; // Clear previous content
                                const renderedImage = new Image();
                                renderedImage.src = combinedDataURL;
                                renderedCanvas.appendChild(renderedImage);
                            };

                            // button with the id "downloadShot"
                            const downloadShotButton = document.getElementById('downloadShot');

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
                                drawImage.src = drawCanvas.toDataURL('image/png');

                                // When the drawing canvas image is fully loaded, render it onto the combined canvas
                                drawImage.onload = () => {
                                    combinedCtx.drawImage(drawImage, 0, 0);

                                    // Get the combined canvas data URL and render the image for preview
                                    const combinedDataURL = combinedCanvas.toDataURL('image/png');
                                    renderCombinedImage(combinedDataURL);
                                };
                            });

                
                            // Function to handle the download
                            const downloadImage = () => {
                                const combinedCanvas = document.createElement('canvas');
                                combinedCanvas.width = imageBitmap.width;
                                combinedCanvas.height = imageBitmap.height;
                                const combinedCtx = combinedCanvas.getContext('2d');

                                // Draw the screen capture onto the combined canvas
                                combinedCtx.drawImage(imageBitmap, 0, 0);

                                // Create a new image element for the drawing canvas
                                const drawImage = new Image();
                                drawImage.src = drawCanvas.toDataURL('image/png');

                                // When the drawing canvas image is fully loaded, render it onto the combined canvas
                                drawImage.onload = () => {
                                    combinedCtx.drawImage(drawImage, 0, 0);

                                    // Get the combined canvas data URL and create a download link
                                    const combinedDataURL = combinedCanvas.toDataURL('image/png');
                                    const a = document.createElement('a');
                                    a.href = combinedDataURL;
                                    a.download = 'combined_image.png';
                                    a.click();
                                };
                            };    
                            // button with the id "downloadButton"
                            const downloadButton = document.getElementById('downloadButton');
                            // Add click event listener to the download button
                            downloadButton.addEventListener('click', downloadImage);
                        };
                    }).catch(error => {
                        console.error('Error grabbing frame:', error);
                    });
                }).catch(error => {
                console.error('Error accessing media devices:', error);
                });
            });
        });
    </script>

    {{-- BUTTONS --}}
    <script>
        //screenshotButton
        document.getElementById('screenshotButton').addEventListener('click', function() {
            document.getElementById('mainMenu').style.display = 'none';
            document.getElementById('shotMenu').style.display = 'flex';
        });
        //returnButton from screen Shot
        document.getElementById('returnButton').addEventListener('click', function() {
            document.getElementById('mainMenu').style.display = 'flex';
            document.getElementById('shotMenu').style.display = 'none';
        });
        //start recording video button
        document.getElementById('startButton').addEventListener('click', function() {
            document.getElementById('mainMenu').style.display = 'none';
            document.getElementById('videoMenu').style.display = 'flex';
        });
        //returnButton from Video
        document.getElementById('returnButtonVideo').addEventListener('click', function() {
            document.getElementById('mainMenu').style.display = 'flex';
            document.getElementById('videoMenu').style.display = 'none';
        });
        //stop recording button
        document.getElementById('stopButton').addEventListener('click', function() {
            document.getElementById('rightBar').style.display = 'flex';
        });
        //Save combined screenshot-canvas button
        document.getElementById('downloadShot').addEventListener('click', function() {
            document.getElementById('rightBar').style.display = 'flex';
        });
    </script>
</body>
</html>