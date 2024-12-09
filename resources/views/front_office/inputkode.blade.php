<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Booking</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
        <style>
    /* Reset dan gaya dasar */
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
    }

    h5 {
        font-weight: bold;
        margin-top: 20px;
    }

    /* Toggle button */
    .toggle-button {
        position: absolute; /* Tetap di atas dan terlihat saat scroll */
        top: 80px;
        right: 10px; /* Posisikan di kanan atas */
        display: flex;
        width: 200px; /* Lebar tetap untuk semua ukuran */
        border-radius: 25px;
        overflow: hidden;
        background-color: #e0e8ff;
        z-index: 1000;
    }

    .toggle-button div {
        flex: 1;
        text-align: center;
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-button .active {
        background-color: #002855;
        color: #fff;
        font-weight: bold;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .toggle-button .inactive {
        background-color: #e0e8ff;
        color: #002855;
        font-weight: bold;
    }

    /* Kartu check-in */
    .container {
        max-width: 100%; /* Pastikan lebar tidak melebihi layar */
        padding: 15px; /* Tambahkan padding untuk jarak */
    }

    .card {
        max-width: 500px; /* Batasi lebar maksimal */
        margin: 0 auto; /* Pusatkan kartu */
        border-radius: 10px;
    }

    .card-text {
        font-size: 1.2rem; /* Gunakan ukuran yang responsif */
    }

    input {
        font-size: 1rem; /* Ukuran font responsif */
    }

    button {
        font-size: 1rem; /* Sesuaikan ukuran font */
    }

    /* Responsif */
    @media (max-width: 768px) {
        .toggle-button {
            width: 90%; /* Sesuaikan lebar pada layar kecil */
            right: 5%; /* Tambahkan jarak */
        }

        h5 {
            font-size: 1.5rem;
        }

        .card-text {
            font-size: 1rem;
        }

        input {
            font-size: 0.9rem;
        }

        button {
            font-size: 0.9rem;
            padding: 12px 30px; /* Kurangi padding */
        }
    }

    @media (max-width: 576px) {
        .toggle-button {
            width: 100%;
            right: 0; /* Hapus margin kanan */
        }

        h5 {
            font-size: 1.2rem;
        }

        .card {
            max-width: 90%; /* Sesuaikan lebar pada layar sangat kecil */
        }

        .card-text {
            font-size: 0.9rem;
        }

        input {
            font-size: 0.8rem;
        }

        button {
            font-size: 0.8rem;
            padding: 10px 20px;
        }
    }
</style>

</head>

<body class="">
   
    
    @include('layouts.app')
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
  

    <div class="toggle-button">
        <div id="barcodeButton" class="inactive" onclick="navigateTo('barcode')">BARCODE</div>
        <div id="inputButton" class="active" onclick="navigateTo('input')">INPUT</div>
    </div>

    <script>
        function navigateTo(view) {
            if (view === 'barcode') {
                window.location.href = "{{ route('dashboard') }}";
            } else if (view === 'input') {
                window.location.href = "{{ route('inputkode.show') }}";
            }
        }
    </script>
    <h5 class="text-center my-4 mt-4" style="font-weight:bold;">Check-In Event</h5>
    <form action="{{ route('inputkode.match') }}" method="POST">
    @csrf
    <div class="container">
        <div class="card w-50 justify-content-center mx-auto shadow" style="border-color: #091F5B; border-radius:10px;">
            
            <div class="card-body">
            @if (session()->has('gagal'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session()->get('gagal') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
                <p style="font-size: 21px;" class="card-text text-center py-5 mt-3">Masukkan Kode Booking atau Pindai Barcode</p>
                <div class="mb-3 text-center px-5">
                    <input type="text" class="form-control py-2" name="id_booking" id="id_booking" placeholder="Masukkan Kode Booking" style="background-color: #E1E9FF; font-style: italic; text-align:center; border-radius:13px; border-color:#091F5B;">
                </div>
                <div class="text-center p-5">
                    <button type="submit" class="btn" style="background-color: #091F5B; color:white; border-radius:30px; padding:16px 50px; font-weight:bold;">Check - In</button>
                </div>
            </div>
        </div>
    </div>
</form>
    <p class="text-center">*kode booking bisa dicek di halaman riwayat booking</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>