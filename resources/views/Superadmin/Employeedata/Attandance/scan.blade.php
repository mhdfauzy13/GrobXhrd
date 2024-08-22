<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Scan</title>
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

        video {
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
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <p id="status" class="text-center text-muted"></p>
                    <p id="result" style="display: none;"></p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const statusElement = document.getElementById('status');
        const alertContainer = document.getElementById('alert-container');

        // Akses kamera 
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
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

        let isScanning = true;

        function scanQRCode() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code && isScanning) {
                    isScanning = false;
                    sendQRCodeDataToServer(code.data);
                } else {
                    statusElement.innerText = 'Scanning for QR code...';
                }
            }
            requestAnimationFrame(scanQRCode);
        }

        function sendQRCodeDataToServer(qrData) {
            fetch('{{ route('attandance.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        employee_id: qrData
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
                        isScanning = true;
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);

                    alertContainer.innerHTML = '';

                    isScanning = true;
                });
        }
    </script>

</body>

</html>
