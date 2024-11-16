<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts and Styles -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .main-card {
            border-radius: 10px;
            background-color: #fff;
            border-color: #091F5B;
            padding: 60px;
            width: 650px;
            margin: auto;
            margin-top: 30px;
        }

        .section-title {
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .info-card {
            border-radius: 15px;
            border-color: #091F5B;
            font-size: 14px;
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            font-size: 12px;
        }

        .table th {
            background-color: #e3f2fd;
            text-align: center;
        }

        .table th,
        .table td {
            border: none;
        }

        .form-check-input {
            border-color: #091F5B;
        }

        .form-check-input:checked {
            background-color: #091F5B;
            border-color: #091F5B;
        }

        .signature-wrapper {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            margin-top: 25px;
            font-size: 14px;
        }

        .signature-group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .signature-img {
            width: 100px;
            height: auto;
        }

        .signature-title {
            margin-bottom: 10px;
        }

        .signature-group p {
            margin: 5px 0;
        }

        .btn-primary {
            background-color: #091F5B;
            border: none;
            width: 200px;
            height: 50px;
            border-radius: 30px;
            font-weight: 600;
            padding-top: 12px;
        }

        .modal-header,
        .modal-footer {
            border: none;
            /* Menghilangkan border */
        }
    </style>
</head>

<body>
    @include('layouts.app') <!-- Include navbar -->

    <div class="main-card border">
        <h4 class="text-center mb-4" style="color: #091F5B;">Formulir Peminjaman Barang</h4>

        <!-- Info Card untuk Nama Event -->
        <div class="info-card border mb-2 p-3">
            <div class="d-flex align-items-center">
                <p class="mb-0">Nama Event</p>
                <p class="mb-0" style="color: #091F5B; margin-left: 20px;"><strong>{{ $nama_event }}</strong></p>
            </div>
        </div>

        <!-- Card untuk Ruangan, PIC, Tanggal, dan Jam -->
        <div class="info-card border p-3 mb-3">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <p class="mb-0">Ruangan</p>
                    <p class="mb-0" style="color: #091F5B; margin-left: 40px;">{{ $ruangan }}</p>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <p class="mb-0">PIC</p>
                    <p class="mb-0" style="color: #091F5B; margin-left: 25px;">{{ $pic }}</p>
                </div>
                <div class="col-md-6 d-flex align-items-center mt-2">
                    <p class="mb-0">Tanggal</p>
                    <p class="mb-0 text-end" style="color: #091F5B; margin-left: 50px;">{{ $tanggal }}</p>
                </div>
                <div class="col-md-6 d-flex align-items-center mt-2">
                    <p class="mb-0">Jam</p>
                    <p class="mb-0 text-end" style="color: #091F5B; margin-left: 20px;">{{ $jam }}</p>
                </div>
            </div>
        </div>

        <!-- Tabel List Barang yang Dipinjam -->
        <h5 class="section-title mt-4" style="color: #091F5B">List Barang yang Dipinjam</h5>
        <table class="table table-striped table-custom text-center mb-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $index => $peminjaman)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $peminjaman->kode_booking }}</td>
                        <td>{{ $peminjaman->nama_item }}</td>
                        <td>{{ $peminjaman->jumlah }}</td>
                        <td>{{ $peminjaman->lokasi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Checkbox Syarat dan Ketentuan -->
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
            <label class="form-check-label" for="agree" style="color: #091F5B; font-weight:500;">
                Syarat dan Ketentuan
            </label>
        </div>

        <!-- Bagian Tanda Tangan -->
        <div class="signature-wrapper d-flex justify-content-between mt-2">
            <div class="signature-group mt-4 text-center">
                <p class="signature-title">Mengetahui,<br> Marketing</p>
                <p><img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                        style="width: 80px; height: 80px;"></p>
                <p>{{ $peminjaman->marketing }}</p>
            </div>
            <div class="d-flex" style="flex-basis: 35%; justify-content: space-between;">
                <div class="signature-group mt-4 text-center">
                    <p class="signature-title">Mengetahui,<br> Peminjam</p>
                    <p>
                        @if(isset($signature) && $signature != 'Tidak Tersedia')
                            <img src="{{$signature}}" alt="Tanda Tangan"
                                style="width: 180px; height: 80px; padding-left: 45px;">
                        @else
                            <p>Tanda tangan tidak tersedia.</p>
                        @endif
                    </p>
                    <p>{{ $name }}</p>
                </div>
                <div class="signature-group mt-4 text-center">
                    <p class="signature-title">Mengetahui,<br> FO</p>
                    <p><img src="{{ asset('images/fo_ttd.png') }}" alt="Tanda Tangan"
                            style=" width: 80px; height: 80px;"></p>
                    <p>{{ $peminjaman->FO }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Setuju -->
    <div class="text-center mt-4 mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Setuju</a>
    </div>

    <!-- Modal Syarat dan Ketentuan -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;"> <!-- Mengatur ukuran modal menjadi 600px -->
            <div class="modal-content">
                <div class="modal-header text-center"
                    style="border-bottom: none; display: flex; justify-content: center; width: 100%;">
                    <h5 class="modal-title" id="termsModalLabel" style="color: #091F5B; text-align: center; margin: 0;">
                        Syarat dan Ketentuan</h5>
                </div>
                <div class="modal-body ">
                    <ul class="list-unstyled mb-1">
                        <li>Peminjam setuju untuk mengembalikan semua pinjaman pada tanggal pengembalian di atas dalam
                            keadaan baik atau lebih baik dari kondisi yang dipinjam </li>
                        <li>Peminjam menyanggupi penggantian bila terjadi kehilangan dan kerusakan</li>
                        <!-- Tambahkan syarat lainnya sesuai kebutuhan -->
                    </ul>
                </div>
                <div class="modal-footer"
                    style="border-top: none; display: flex; justify-content: center; width: 100%;">
                    <!-- Menggunakan flexbox untuk memusatkan tombol -->
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Setuju</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS untuk Modal -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

    <script>
        // Menangani acara saat checkbox diubah
        document.getElementById('agree').addEventListener('change', function () {
            if (this.checked) {
                // Tampilkan modal saat checkbox dicentang
                var modal = new bootstrap.Modal(document.getElementById('termsModal'));
                modal.show();
            }
        });
    </script>
</body>

</html>