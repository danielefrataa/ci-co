<!DOCTYPE html>
<html lang="id">
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Check-In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Montserrat', sans-serif;
            /* Set Montserrat as the default font */
        }

        #signature {
            border: 1px solid #ccc;
            width: 200px;
            /* Ukuran canvas diperkecil */
            height: 150px;
            cursor: crosshair;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
            /* Card berada di tengah */
        }

        .container {
            max-width: 500px;
        }

        .form-label,
        .form-control {
            display: inline-block;
            width: 48%;
        }

        .form-label {
            width: 25%;
        }

        .form-control {
            margin-bottom: 15px;
            width: 70%;
            background-color: #f0f8ff;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            background-color: #f0f8ff;
            border-color: #091F5B;
            box-shadow: none;
        }

        .text-center {
            text-align: center;
        }

        .judul {
            color: #091F5B;
            font-weight: 800;
            font-size: 20px;
        }

        .small-btn {
            width: 150px;
            display: block;
            margin: 0 auto;
            background-color: #091F5B;
            border-radius: 50px;
            border: none;
            color: #fff;
            font-weight: 800;

        }

        .modal-dialog {
            max-width: 600px;
            /* Ukuran modal diperkecil */
        }

        .modal-footer {
            justify-content: center;
        }

        .btn-center {
            display: block;
            margin: 0 auto;
            background-color: #091F5B;
            border-radius: 50px;
            width: 150px;
            font-weight: 800;
        }

        .bold-text {
            font-weight: 600;
        }

        .detail {
            width: 350px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    @include('layouts.app')

    <div class="container py-4">
        <!-- Card untuk Detail Booking -->
        <div class="card p-4 mb-4">
            <h4 class="text-center mb-4" style="font-weight: 800; font-size: 22px;">Isi Data Check-In</h4>

            <!-- Form -->
            <form id="checkinForm" action="{{ route('booking.completeCheckIn', ['kode_booking' => $booking['booking_code']]) }}" method="POST">
                @csrf
                <input type="hidden" name="kode_booking" value="{{ $booking['booking_code'] }}">
                <input type="hidden" id="signatureData" name="signatureData"> <!-- Hidden input for signature data -->

                <div>
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div>
                    <label for="phone" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <!-- Detail Booking -->
                <h5 class="mb-4 mt-4 text-center" style="font-weight: 800; font-size: 18px;">Detail Booking</h5>
                <div class="card detail p-3 bg-white mb-3">
                    <h4 class="text-center judul">{{ $booking['name'] }}</h4>
                    <p class="text-center">{{ $roomDetails['room_name'] }},  {{ $roomDetails['room_floor'] }}</p>
                    <p class="text-center">Hari  {{ $dayOfWeek }},{{ $formattedDate }}</p>
                    <p class="text-center">
                        <strong>{{ $startTime }} - {{ $endTime }}</strong>
                    </p>
                    <p class="text-center"><strong>{{ $booking['pic_name'] }}</strong></p>
                </div>

                <!-- Tombol Check-In -->
                <button type="button" class="btn btn-primary small-btn" data-bs-toggle="modal" data-bs-target="#termsModal">Check-In</button>
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h5 class="modal-title" style="color: #091F5B; font-weight:800;">Syarat dan Ketentuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul style="font-size: 14px;">
                            <li>Penggunaan ruangan maksimal pukul 21.00 WIB.</li>
                            <li>Menjaga fasilitas ruangan.</li>
                            <!-- Tambahkan poin lainnya sesuai kebutuhan -->
                        </ul>

                        <!-- Signature -->
                        <div class="d-flex flex-column align-items-start">
                            <canvas id="signature" class="mb-3"></canvas>
                            <button type="button" class="btn btn-secondary" id="clearSignature">Clear</button>
                            <p style="font-style: italic; font-size: 12px;">Silahkan menandatangani untuk menyetujui syarat dan ketentuan.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-center" form="checkinForm">Setuju</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var canvas = document.getElementById('signature');
            var ctx = canvas.getContext('2d');
            var isDrawing = false;

            canvas.addEventListener('mousedown', function (e) {
                isDrawing = true;
                ctx.beginPath();
                ctx.moveTo(e.offsetX, e.offsetY);
            });

            canvas.addEventListener('mousemove', function (e) {
                if (isDrawing) {
                    ctx.lineTo(e.offsetX, e.offsetY);
                    ctx.stroke();
                }
            });

            canvas.addEventListener('mouseup', function () {
                isDrawing = false;
            });

            document.getElementById('clearSignature').addEventListener('click', function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });

            document.getElementById('checkinForm').addEventListener('submit', function (e) {
                var signatureData = canvas.toDataURL('image/png');
                if (signatureData.length <= 100) {
                    e.preventDefault();
                    alert("Harap tambahkan tanda tangan untuk melanjutkan.");
                } else {
                    document.getElementById('signatureData').value = signatureData;
                }
            });
        });
    </script>
</body>
</html>
