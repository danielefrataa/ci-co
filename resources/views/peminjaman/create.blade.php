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

        <!-- Form List Barang yang Dipinjam -->
        <h5 class="section-title mt-4" style="color: #091F5B">List Barang yang Dipinjam</h5>
        <form method="POST" action="{{ route('peminjaman.store') }}">
            @csrf
            <table class="table table-striped table-custom text-center mb-4">
            <input type="hidden" name="kode_booking" value="{{ $kode_booking }}">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="barangList">
                    @foreach ($peminjamans as $index => $peminjaman)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><input type="text" name="items[{{ $index }}][nama_item]" value="{{ $peminjaman->nama_item }}" class="form-control"></td>
                        <td><input type="number" name="items[{{ $index }}][jumlah]" value="{{ $peminjaman->jumlah }}" class="form-control"></td>
                        <td><input type="text" name="items[{{ $index }}][lokasi]" value="{{ $peminjaman->lokasi }}" class="form-control"></td>
                        <td><button type="button" class="btn btn-danger removeItem" onclick="removeItem(this)">Hapus</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Tambah Barang -->
            <button type="button" class="btn btn-success" onclick="addItem()">Tambah Barang</button>

            <div class="text-center mt-4 mb-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>

        <!-- Checkbox Syarat dan Ketentuan -->
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
            <label class="form-check-label" for="agree" style="color: #091F5B; font-weight:500;">
                Syarat dan Ketentuan
            </label>
        </div>

    </div>

    <script>
        function addItem() {
            let table = document.getElementById('barangList');
            let rowCount = table.rows.length;
            let row = table.insertRow(rowCount);

            row.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" name="items[${rowCount}][nama_item]" class="form-control"></td>
                <td><input type="number" name="items[${rowCount}][jumlah]" class="form-control"></td>
                <td><input type="text" name="items[${rowCount}][lokasi]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger removeItem" onclick="removeItem(this)">Hapus</button></td>
            `;
        }

        function removeItem(button) {
            let row = button.parentElement.parentElement;
            row.remove();
        }
    </script>
</body>

</html>
