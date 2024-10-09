<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Scan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
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
                    <h3 class="card-title"><i class="fas fa-camera"></i> Attendance Scan</h3>
                </div>
                <div class="card-body">
                    <div id="alert-container"></div>
                    <div id="camera-container">
                        <video id="video" autoplay></video>
                        <div id="my_camera" style="display: none;"></div>
                    </div>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <div class="button-container">
                        <button onclick="takeSnapshot()" class="btn btn-dark">Ambil Gambar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const alertContainer = document.getElementById('alert-container');

        let attendanceStatus = {
            isCheckIn: {{ json_encode($checkIn) }}
        };

        // Fungsi untuk mengambil status kehadiran saat ini
        function fetchAttendanceState() {
            fetch('/attendance/state') // Endpoint untuk mendapatkan status kehadiran saat ini
                .then(response => response.json())
                .then(data => {
                    attendanceStatus.isCheckIn = data.isCheckIn; // Atur status berdasarkan data terbaru
                })
                .catch(error => {
                    console.error('Error fetching attendance state:', error);
                });
        }

        window.onload = function() {
            fetchAttendanceState();
            // Akses kamera
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user"
                    }
                })
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(err => {
                    console.error('Error accessing camera: ', err);
                    alertContainer.innerHTML =
                        '<div class="alert alert-danger text-center" role="alert">Error accessing camera</div>';
                });
        };

        function takeSnapshot() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            sendImageToServer(imageData);
        }

        function sendImageToServer(imageData) {
            fetch('{{ route('attandance.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: imageData,
                        check_in: attendanceStatus.isCheckIn // Status check-in atau check-out
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alertContainer.innerHTML = '';

                    if (data.success) {
                        if (attendanceStatus.isCheckIn) {
                            alertContainer.innerHTML = `<div class="alert alert-success text-center" role="alert">
                <i class="fas fa-check-circle"></i> Anda telah berhasil Check-In!
            </div>`;
                            attendanceStatus.isCheckIn = false; // Beralih ke mode check-out
                        } else {
                            alertContainer.innerHTML = `<div class="alert alert-danger text-center" role="alert">
                <i class="fas fa-times-circle"></i> Anda telah berhasil Check-Out!
            </div>`;
                            attendanceStatus.isCheckIn = true; // Reset untuk hari berikutnya
                        }
                    } else {
                        alertContainer.innerHTML = `<div class="alert alert-warning text-center" role="alert">
            ${data.message}
        </div>`;
                    }

                    // Hapus alert setelah beberapa detik
                    setTimeout(() => {
                        alertContainer.innerHTML = '';
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertContainer.innerHTML = `<div class="alert alert-danger text-center" role="alert">
        Terjadi kesalahan. Silakan coba lagi.
    </div>`;
                });
        }
    </script>

</body>

</html>
