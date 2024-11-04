<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet"> <!-- Include Montserrat font -->

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
        .modal-body{
            
        }
        .modal-dialog {
            max-width: 600px;
            /* Ukuran modal diperkecil */
        }

        .modal-content {
            text-align: ;
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
    @include('layouts.app') <!-- Include your navbar here -->

    <div class="container py-4">
        <!-- Card untuk Detail Booking -->
        <div class="card p-4 mb-4">
            <h4 class="text-center mb-4" style="font-weight: 800; font-size: 22px;">Isi Data Check In</h4>

            <!-- Form untuk input nama dan nomor telepon -->
        <form id="checkinForm" action="{{ route('checkin.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
            <input type="hidden" id="signatureData" name="signatureData"> <!-- Hidden input for signature data -->

                <div>
                    <label for="name" class="form-label bold-text">Nama </label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div>
                    <label for="phone" class="form-label bold-text">No Telepon </label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <!-- Detail Booking -->
                <h5 class="mb-4 mt-4" style="font-weight: 800; font-size: 18px;">Detail Booking</h5> <!-- Tambahkan text-center untuk judul -->
                <div class="card detail p-3 bg-white mb-3">
                    <h4 class="text-center judul"> Global Visionary {{ $booking->event_name }}</h4>
                    <p class="text-center mt-2 mb-1">Ruang Kelas {{ $booking->ruangan }}, <span>Lantai</span></p> <!-- Tambahkan mb-1 -->
                    <p class="text-center mb-1">Kamis, 3 Oktober 2024 {{ $booking->hari }}, {{ $booking->tanggal }}</p> <!-- Tambahkan mb-1 -->
                    <p class="text-center mb-1"><strong>19.00-21.00</strong> {{ $booking->jam }}</p> <!-- Tambahkan mb-1 -->
                    <p class="text-center mt-2 mb-1"><strong>Nama PIC</strong> {{ $booking->pic_name }}</p> <!-- Tambahkan mb-1 -->
                </div>

                <!-- Tombol Check In -->
                <button type="button" class="btn btn-primary small-btn" data-bs-toggle="modal" data-bs-target="#termsModal">Check In</button>
            </form>
        </div>

        <!-- Modal untuk Terms and Conditions -->
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="border: none;"> <!-- Menghilangkan garis pada modal -->
                    <div class="modal-header justify-content-center" style="border-bottom: none;"> <!-- Menghilangkan garis bawah dan posisi tengah -->
                        <h5 class="modal-title" id="termsModalLabel" style="color: #091F5B; text-align: center; font-weight:800;">Syarat dan Ketentuan</h5> <!-- Warna biru dan center -->
                        <button type="button" class="btn-close position-absolute" style="right: 25px;" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul style=" padding-left: 20px; font-size:14px;"> 
                            <li>Penggunaan Ruangan maksimal pada pukul 21.00 WIB. Lebih dari jam tersebut Manajemen berhak untuk menghentikan acara.</li>
                            <li>Menjaga fasilitas, sarana, dan prasarana yang tersedia dalam Ruangan/Gedung MCC.</li>
                            <li>Melengkapi sendiri kebutuhan yang tidak tersedia/kurang seperti kabel roll, alat tulis, kursi, meja, level stage, dekorasi, dll.</li>
                            <li>Menjaga ketertiban, kebersihan dan keamanan penyelenggaraan acara.</li>
                            <li>Dilarang menempel, memaku benda apapun pada dinding Ruangan/Gedung MCC.</li>
                            <li>Dilarang memasang atribut Partai Politik, atau Ormas Keagamaan yang berbau politik di Ruangan/Gedung MCC.</li>
                            <li>Loading in barang dilakukan pada H-1 Jam 22.00-06.00 WIB.</li>
                            <li>Loading out barang dilakukan di hari yang sama setelah rundown acara selesai.</li>
                            <li>Jika proses loading out melebihi batas waktu yang ditentukan, Manajemen MCC berhak memindahkan properti dan tidak bertanggung jawab atas kerusakan properti.</li>
                            <li>Mengumpulkan sampah pada titik/tempat sampah yang tersedia. Petugas Kebersihan MCC akan melakukan pembuangan sampah yang telah terkumpul.</li>
                            <li>Ruangan yang sudah selesai digunakan serta peralatannya wajib dikembalikan pada posisi semula dan memberikan konfirmasi ke Customer Service pada saat Check-out.</li>
                        </ul>

                        <!-- Signature Pad dan Teks di bawahnya -->
                        <div class="d-flex flex-column align-items-start position-relative">
                            <!-- Canvas signature -->
                            <canvas id="signature" class="mb-3"></canvas>
                            <!-- Clear button di samping kanan canvas -->
                            <button type="button" class="btn btn-secondary" id="clearSignature" style="margin-left: px;">Clear</button>
                            <p class="text-start" style="font-style: italic; font-size: 12px;">Silahkan menandatangani untuk menyetejui syarat dan ketentuan</p>
                        </div>
                    </div>
                    <div class="modal-footer " style="border: none;">
                        <button type="submit" class="btn btn-primary btn-center" form="checkinForm">Setuju</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- External JS Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/3.0.2/signature_pad.umd.min.js"></script>

<<<<<<< Updated upstream
        <!-- Signature Pad Initialization -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var canvas = document.getElementById('signature');
                var ctx = canvas.getContext('2d');
                var isDrawing = false;

                canvas.addEventListener('mousedown', function(e) {
                    isDrawing = true;
                    ctx.beginPath();
                    ctx.moveTo(e.offsetX, e.offsetY);
                });

                canvas.addEventListener('mousemove', function(e) {
                    if (isDrawing) {
                        ctx.lineTo(e.offsetX, e.offsetY);
                        ctx.stroke();
                    }
                });

                canvas.addEventListener('mouseup', function() {
                    isDrawing = false;
                });

                document.getElementById('clearSignature').addEventListener('click', function() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                });

                document.getElementById('checkinForm').addEventListener('submit', function(e) {
                    var signatureData = canvas.toDataURL('image/png');
                    document.getElementById('signatureData').value = signatureData;
                });
            });
        </script>
=======
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var canvas = document.getElementById('signature');
    var ctx = canvas.getContext('2d');
    var isDrawing = false;

    canvas.addEventListener('mousedown', function(e) {
        isDrawing = true;
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
    });

    canvas.addEventListener('mousemove', function(e) {
        if (isDrawing) {
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
        }
    });

    canvas.addEventListener('mouseup', function() {
        isDrawing = false;
    });

    document.getElementById('clearSignature').addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });

    document.getElementById('checkinForm').addEventListener('submit', function(e) {
        var signatureData = canvas.toDataURL('image/png');
        if (signatureData.length <= 100) { 
            e.preventDefault();
            alert("Please provide a signature to proceed.");
        } else {
            document.getElementById('signatureData').value = signatureData;
        }
    });
});
</script>
>>>>>>> Stashed changes
</body>

</html>