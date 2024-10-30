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
    </style>
</head>

<body class="">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="https://event.mcc.or.id/assets/images/logo.png" width="250" alt="Event Malang Creative Center">
            </a>
        </div>
    </nav>
    <h5 class="text-center my-4 mt-4" style="font-weight:bold;">Check-In Event</h5>
    <form action="{{ route('match') }}" method="POST" id="form">
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
                    <p style="font-size: 21px;" class="card-text text-center py-5 mt-3">Masukkan Kode Booking</p>
                    <div class="mb-3 text-center px-5">
                        @csrf
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