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
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .toggle-button {
            display: flex;
            width: 18%;
            border-radius: 25px;
            overflow: hidden;
            background-color: #e0e8ff;
            margin-left: 1244px;
            margin-top: 8px;
            /* Center the toggle */
        }

        .toggle-button div {
            flex: 1;
            text-align: center;
            padding: 10px 20px;
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

        .toggle-button div:hover {
            opacity: 0.9;
            /* Slight effect on hover */
        }
    </style>

</head>

<body class="">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @include('layouts.app')
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
    <p class="text-center" style="margin-top:60px;">*kode booking bisa dicek di halaman riwayat booking</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>