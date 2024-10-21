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

        .btn-checkout {
            display: none;
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
                        <button id="btn-checkin" onclick="takeSnapshot('checkin')"
                            class="btn btn-dark btn-checkin">Ambil Gambar</button>
                        <button id="btn-checkout" onclick="takeSnapshot('checkout')"
                            class="btn btn-dark btn-checkout">Ambil Gambar</button>
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
        const btnCheckIn = document.getElementById('btn-checkin');
        const btnCheckOut = document.getElementById('btn-checkout');

        // Mengambil status check-in dan check-out dari backend
        let attendanceStatus = {
            isCheckIn: {{ json_encode($hasCheckedIn) }},
            isCheckOut: {{ json_encode($hasCheckedOut) }} // Tambahkan status check-out
        };

        // Fungsi untuk memperbarui visibilitas tombol
        function updateButtonVisibility() {
            if (attendanceStatus.isCheckIn) {
                btnCheckIn.style.display = 'none'; // Sembunyikan tombol check-in
                btnCheckOut.style.display = 'block'; // Tampilkan tombol check-out
            } else {
                btnCheckIn.style.display = 'block'; // Tampilkan tombol check-in
                btnCheckOut.style.display = 'none'; // Sembunyikan tombol check-out
            }
        }

        window.onload = function() {
            updateButtonVisibility(); // Perbarui tampilan tombol saat halaman dimuat
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

        function takeSnapshot(action) {
            // Tentukan URL untuk check-in atau check-out berdasarkan action
            const url = action === 'checkin' ? '{{ route('attandance.checkIn') }}' : '{{ route('attandance.checkOut') }}';

            // Atur ukuran canvas berdasarkan video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Ambil gambar dari video dan gambar di canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Konversi canvas ke data URL (gambar)
            const imageData = canvas.toDataURL('image/jpeg');

            // Kirim gambar ke server
            sendImageToServer(imageData, url, action);
        }

        function sendImageToServer(imageData, url, action) {
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: imageData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alertContainer.innerHTML = '';

                    // Tangani respon dari server
                    if (data.success) {
                        if (action === 'checkin') {
                            alertContainer.innerHTML = `<div class="alert alert-success text-center" role="alert">
                    <i class="fas fa-check-circle"></i> Anda telah berhasil Check-In!
                </div>`;
                            attendanceStatus.isCheckIn = true; // Ubah status menjadi check-in
                        } else if (action === 'checkout') {
                            alertContainer.innerHTML = `<div class="alert alert-danger text-center" role="alert">
                    <i class="fas fa-check-circle"></i> Anda telah berhasil Check-Out!
                </div>`;
                            attendanceStatus.isCheckIn = false; // Ubah status menjadi check-out
                        }
                    } else {
                        alertContainer.innerHTML = `<div class="alert alert-warning text-center" role="alert">
                ${data.message}
            </div>`;
                    }

                    // Perbarui visibilitas tombol
                    updateButtonVisibility();

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
