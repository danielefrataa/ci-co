<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
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

    <body>
    @include('layouts.app') <!-- Include your navbar here -->

    <div class="container col-lg-5 py-5">
        <div class="d-flex justify-content-center align-items-center ">
            <h4 class="text-center mt-4 " style="font-size: 32px; color: #091F5B;">Silahkan tunjukkan barcode Anda ke
                Kamera</h4>
        </div>
        <div class="card bg-white shadow rounded-3 p-3 border-0 mt-4">
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
            <video id="preview"></video>
            <form action="{{ route('store') }}" method="POST" id="form">
                @csrf
                <input type="hidden" name="id_booking" id="id_booking"></input>
            </form>
        </div>
        <h5 class="text-center mt-4 " style="font-size: 14px; color: #091F5B;">*barcode dikirimkan ke email anda apabila
            sudah di approve</h5>

        <!-- <div class="table-responsive">
            <table class="table table-bordered table-hover mt-5">
                <tr>
                    <th>Nama</th>
                    <th>Tanggal </th>
                </tr>
                @foreach ($absen as $item)
                <tr>
                    <td>{{ $item->booking->nama }}</td>
                    <td>{{ $item->tanggal }}</td>
                </tr>
@endforeach
            </table>
        </div> -->
    </div>

    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(c) {
            document.getElementById('id_booking').value = c;
            document.getElementById('form').submit();
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>