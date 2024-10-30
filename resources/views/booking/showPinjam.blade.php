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
            border-color:#091F5B ;
            padding: 20px;
            width: 600px;
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
            border-color:  #091F5B;
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

        .signature-wrapper {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            margin-top: 25px;
            border-top: 1px solid #ced4da;
            font-size: ;
        }

        .signature-group {
            text-align: center;
            font-size: 14px;
        }

        .signature-title {
            font-weight: 500;
        }
        .btn-primary{
            background-color: #091F5B;
            border: none;
        }
    </style>
</head>

<body>
    @include('layouts.app') <!-- Include navbar -->

    <div class="main-card border">
        <h4 class="text-center  mb-4">Formulir Peminjaman Barang</h4>
    <!-- Info Card untuk Nama Event -->
<div class="info-card border mb-2 p-3">
    <!-- Nama Event dan isinya di samping -->
    <div class="d-flex  align-items-center">
        <p class="mb-0">Nama Event</p>
        <p class="mb-0" style="color: #091F5B; margin-left: 20px;"><strong>{{ $nama_event }}</strong></p>
    </div>
</div>

<!-- Card untuk Ruangan, PIC, Tanggal, dan Jam -->
<div class="info-card border p-3 mb-3">
    <div class="row">
        <!-- Ruangan dan PIC -->
        <div class="col-md-6 d-flex  align-items-center">
            <p class="mb-0">Ruangan</p>
            <p class="mb-0 " style="color: #091F5B; margin-left: 40px;">{{ $ruangan }}</p>
        </div>
        <div class="col-md-6 d-flex  align-items-center">
            <p class="mb-0">PIC</p>
            <p class="mb-0 " style="color: #091F5B; margin-left: 25px;">{{ $pic }}</p>
        </div>
        <!-- Tanggal dan Jam -->
        <div class="col-md-6 d-flex  align-items-center mt-2">
            <p class="mb-0">Tanggal</p>
            <p class="mb-0 text-end" style="color: #091F5B; margin-left: 50px;">{{ $tanggal }}</p>
        </div>
        <div class="col-md-6 d-flex  align-items-center mt-2">
            <p class="mb-0">Jam</p>
            <p class="mb-0 text-end" style="color: #091F5B; margin-left: 20px;">{{ $jam }}</p>
        </div>
    </div>
</div>


            <!-- Tabel List Barang yang Dipinjam -->
        <h5 class="section-title mt-4"  style="color: #091F5B">List Barang yang Dipinjam</h5>
        <table class="table table-bordered table-striped table-custom text-center">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $peminjaman) <!-- Looping untuk setiap item -->
                <tr>
                    <td>{{ $peminjaman->kode_booking }}</td>
                    <td>{{ $peminjaman->nama_item }}</td>
                    <td>{{ $peminjaman->jumlah }}</td>
                    <td>{{ $peminjaman->lokasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <!-- Syarat dan Ketentuan -->
        <h5 class="section-title mt-4" style="color: #091F5B">Syarat dan Ketentuan</h5>
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
            <label class="form-check-label" for="agree">Saya setuju dengan syarat dan ketentuan</label>
        </div>

        <!-- Marketing, FO, dan Tanda Tangan -->
        <div class="signature-wrapper ">
            <div class="signature-group mt-4">
                <p class="signature-title">Mengetahui,<br> Marketing</p>
                <p>(Tanda Tangan)</p>
                <p>{{ $peminjaman->marketing }}</p>
            </div>
            <div class="signature-group mt-4">
                <p class="signature-title">Mengetahui,<br> Peminjam</p>
                <p>(Tanda Tangan)</p>
                <p>{{ $peminjaman->signature ?? 'Tidak Tersedia' }}</p>
            </div>
            <div class="signature-group mt-4">
                <p class="signature-title">Mengetahui,<br> FO</p>
                <p>(Tanda Tangan)</p>
                <p>{{ $peminjaman->FO }}</p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Kirim</a>
        </div>
    </div>
</body>
</html>
