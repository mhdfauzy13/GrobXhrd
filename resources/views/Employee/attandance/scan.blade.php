<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance/scan</title>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />

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

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .btn-checkout {
            display: none;
        }

        #camera-container,
        #btn-checkin,
        #btn-checkout {
            display: block;
        }

        video {
            border-radius: 10px;
            border: 3px solid #343a40;
            width: 100%;
            max-width: 640px;
            transform: scaleX(-1);
            /* Membalikkan video agar tidak mirror */
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

                    <!-- Jika karyawan sedang cuti, tampilkan alert -->
                    @if (isset($onLeave) && $onLeave)
                        <div class="alert alert-warning text-center">
                            You are on leave today.
                        </div>
                        <!-- Sembunyikan tombol check-in dan check-out jika cuti -->
                        <script>
                            document.getElementById('btn-checkin').style.display = 'none';
                            document.getElementById('btn-checkout').style.display = 'none';
                            document.getElementById('camera-container').style.display = 'none';
                        </script>
                    @else
                        <div id="camera-container">
                            <video id="video" autoplay playsinline></video>
                        </div>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div class="button-container">
                            <button id="btn-checkin" onclick="takeSnapshot('checkin')" class="btn btn-dark btn-checkin">
                                Capture Image
                            </button>
                            <button id="btn-checkout" onclick="takeSnapshot('checkout')"
                                class="btn btn-dark btn-checkout">
                                Capture Image
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const alertContainer = document.getElementById('alert-container');
        const btnCheckIn = document.getElementById('btn-checkin');
        const btnCheckOut = document.getElementById('btn-checkout');

        let attendanceStatus = {
            isCheckIn: {!! isset($hasCheckedIn) ? json_encode($hasCheckedIn) : 'false' !!},
            isCheckOut: {!! isset($hasCheckedOut) ? json_encode($hasCheckedOut) : 'false' !!},
        };

        // Fungsi untuk memperbarui visibilitas tombol
        function updateButtonVisibility() {
            if (attendanceStatus.isCheckIn) {
                btnCheckIn.style.display = 'none';
                btnCheckOut.style.display = 'block';
            } else {
                btnCheckIn.style.display = 'block';
                btnCheckOut.style.display = 'none';
            }
        }

        window.onload = function() {
            updateButtonVisibility();

            // Akses kamera menggunakan MediaDevices API
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
                    console.error('Error accessing camera:', err);
                    alertContainer.innerHTML =
                        '<div class="alert alert-danger text-center">Error accessing camera</div>';
                });
        };

        function takeSnapshot(action) {
            const url = action === 'checkin' ? '{{ route('attandance.checkIn') }}' : '{{ route('attandance.checkOut') }}';

            // Set ukuran canvas sesuai video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Gambar frame dari video ke canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Konversi ke base64
            const imageData = canvas.toDataURL('image/jpeg');

            // Kirim ke server
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
                    if (data.success) {
                        if (data.early && action === 'checkout') {
                            // Tampilkan konfirmasi SweetAlert untuk early check-out
                            Swal.fire({
                                title: 'Early Checkout',
                                text: data.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, proceed',
                                cancelButtonText: 'Cancel',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    finalizeCheckOut(imageData, url);
                                } else {
                                    Swal.fire('Cancelled', 'Your check-out was cancelled.', 'error');
                                }
                            });
                        } else {
                            // Tampilkan pesan sukses
                            Swal.fire('Success', data.message, 'success');
                            // Redirect ke dashboard setelah beberapa detik
                            setTimeout(() => {
                                window.location.href = '{{ route('dashboardemployee.index') }}';
                            }, 3000);
                        }
                    } else {
                        // Tampilkan alert warning jika ada masalah
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error processing request', 'error');
                });
        }

        function finalizeCheckOut(imageData, url) {
            // Lanjutkan proses check-out setelah konfirmasi
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        image: imageData,
                        confirmedEarly: true, // Menandakan user sudah konfirmasi
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Success', data.message, 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route('dashboardemployee.index') }}';
                        }, 3000);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error processing request', 'error');
                });
        }

        function showAlert(message, color) {
            // Tambahkan elemen alert ke dalam container
            alertContainer.innerHTML = `<div class="alert alert-${color} text-center">${message}</div>`;

            // Hilangkan alert setelah 3 detik
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 3000);
        }
    </script>
</body>

</html>

</html>
