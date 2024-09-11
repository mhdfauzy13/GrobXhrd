<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Scan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
    <style>
        body {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-weight: bold;
            font-size: 1.5rem;
        }

        #camera-container {
            text-align: center;
            margin-bottom: 20px;
        }

        video,
        #my_camera {
            border-radius: 10px;
            border: 3px solid #343a40;
            width: 100%;
            max-width: 640px;
        }

        #status {
            font-size: 1.2rem;
            margin-top: 20px;
            font-weight: bold;
        }

        .alert {
            border-radius: 10px;
            margin-top: 20px;
            font-size: 1.1rem;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <section class="content">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header bg-black text-white text-center">
                    <h3 class="card-title"><i class="fas fa-qrcode"></i> Attendance Scan</h3>
                </div>
                <div class="card-body">
                    <div id="alert-container"></div>
                    <div id="camera-container">
                        <video id="video" autoplay></video>
                        <div id="my_camera" style="display: none;"></div>
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <p id="status" class="text-center text-muted"></p>
                </div>
            </div>
        </div>
    </section>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const statusElement = document.getElementById('status');
        const alertContainer = document.getElementById('alert-container');

        let employee_id = '';
        let isScanning = true;

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                }
            })
            .then(stream => {
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scanQRCode);
            })
            .catch(err => {
                console.error('Error accessing camera: ', err);
                statusElement.innerText = 'Error accessing camera';
            });

        function scanQRCode() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code && code.data) {
                    if (isScanning) {
                        isScanning = false;
                        employee_id = code.data;

                        // alertContainer.innerHTML = `
                        //     <div class="alert alert-primary text-center" role="alert">
                        //         Scan QR code telah berhasil, mengambil gambar dalam 3 detik...
                        //     </div>`;

                        setTimeout(() => {
                            takeSnapshot();
                        }, 100);
                    }
                } else {
                    if (isScanning) {
                        statusElement.innerText = 'Scanning for QR code...';
                    }
                }
            }
            requestAnimationFrame(scanQRCode);
        }

        function takeSnapshot() {
            Webcam.set({
                width: 640,
                height: 480,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');

            setTimeout(() => {
                Webcam.snap(function(data_uri) {
                    sendImageToServer(data_uri);
                });
            }, 1000); 
        }

        function sendImageToServer(imageData) {
            fetch('{{ route('attandance.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_id: employee_id,
                        image: imageData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alertContainer.innerHTML = '';

                    if (data.message.includes('checked IN')) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-success text-center" role="alert">
                                <i class="fas fa-check-circle"></i> Anda telah berhasil Check-In!
                            </div>`;
                    } else if (data.message.includes('checked OUT')) {
                        alertContainer.innerHTML = `
                            <div class="alert alert-danger text-center" role="alert">
                                <i class="fas fa-times-circle"></i> Anda telah berhasil Check-Out!
                            </div>`;
                    }

                    setTimeout(() => {
                        alertContainer.innerHTML = '';
                        isScanning = true;
                        statusElement.innerText = 'Scanning for QR code...';
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger text-center" role="alert">
                            <i class="fas fa-times-circle"></i> Terjadi kesalahan saat mengirim data. Coba lagi.
                        </div>`;
                    isScanning = true;
                });
        }
    </script>
</body>

</html>
