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
        .bottomBar{
            position: fixed;
            display: flex;
            bottom: 10;
            width: 100%;
            height: 80px;
            justify-content: center;
            z-index: 10;
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
    <div id="bottomBar">
        <div id="mainMenu" class="widget">
            <button id="screenshotButton" class="iconsContainer button"><img src="{{ asset('img/photo.svg') }}" alt=""></button>
            <div id="startButton" class="iconsContainer button">
                <img src="{{ asset('img/video.svg') }}" alt="record">
            </div>
            
        </div>
        <div id="shotMenu" class="widget" style="display: none;">
            <button id="returnButton" class="iconsContainer button"><img src="{{ asset('img/return.svg') }}" alt=""></button>
            <div class="iconsContainer button">
                <img src="{{ asset('img/draw.svg') }}" alt="draw">
            </div>
                <input type="range" id="lineWidthSlider" min="1" max="20" value="10">
            <div class="iconsContainer button">
                <img id="downloadShot" src="{{ asset('img/download.svg') }}" alt="download">
            </div>
            
        </div>
        <div id="videoMenu" class="widget" style="display: none;">
            <button id="returnButtonVideo" class="iconsContainer button"><img src="{{ asset('img/return.svg') }}" alt=""></button>
            <div id="stopButton" class="iconsContainer button">
                <img src="{{ asset('img/stop.svg') }}" alt="stop">
            </div>
        </div>
    </div>
    <div id="rightBar" style="display: none;">
        <div class="rightBarContent">
            <div id="renderedCanvas" class="renderedCanvas"></div>
            <button id="downloadButton" class="button secondaryButton">Guardar captura</button>
            <h2>Video capturado.</h2>
            <video id="recording" width="300" height="200" controls></video>
            
            <form action="{{route('reports.store')}}" method="POST">
            @csrf 
                <p id="log"></p>
                <h3>Título del hallazgo</h3>
                <input type="text" name="title">
                <h3>Descripción del hallazgo</h3>
                <textarea type="text" name="report" style="width:100%; min-height: 100px;">Objetivo: Actualmente: Expectativa:
                </textarea>
                <button type="submit">Guardar</button>
            </form>
            

            <a id="downloadButton" class="button secondaryButton">
                Descargar
            </a>
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
        let downloadButton = document.getElementById("downloadButton");
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
        log(recorder.state + "" + (lengthInMS/1000) + "segundos de video...");
        
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
            downloadButton.href = stream;
            preview.captureStream = preview.captureStream || preview.mozCaptureStream;
            return new Promise(resolve => preview.onplaying = resolve);
        }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
        .then (recordedChunks => {
            let recordedBlob = new Blob(recordedChunks, { type: "video/webm" });
            recording.src = URL.createObjectURL(recordedBlob);
            downloadButton.href = recording.src;
            downloadButton.download = "capturaDeVideo.webm";
            
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