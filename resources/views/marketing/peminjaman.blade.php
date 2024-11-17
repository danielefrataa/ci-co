<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

<body class="bg-light">
    <!-- Header -->
    @include('layouts.app')

    <div class="d-flex justify-content-between align-items-center mb-4">
        
    </div>
    <div class="container py-4">

        <h1 class="display-4 mb-4 text-center">Peminjaman List</h1>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Tanggal Booking</label>
                    <input type="date" class="form-control" name="tanggal">
                </div>
            </div>
            <div class="col-md-4 ms-auto">
                <div class="mb-3">
                    <label class="form-label">&nbsp;</label>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="container mt-4">
            <div class="card-body text-white my-2 shadow-lg" style="background-color:#091F5B; border-radius: 5px;">
                <div class="row align-items-center">
                    <div class="col-md-3 text-left" style="font-weight: bold">Nama Event</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Nama Organisasi</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Tanggal</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Ruangan dan Waktu</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Nama PIC</div>
                    <div class="col-md-1 text-left" style="font-weight: bold">Aksi</div>
                </div>
            </div>

            @foreach ($booking as $pesan)
                <div class="card-header text-dark my-2 shadow-sm" style="background-color:white; border-radius: 5px;">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-left">
                            <a href="" style="color: #091F5B; font-weight: 600;">{{ $pesan->nama_event }}</a>
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            {{ $pesan->nama_organisasi }}
                        </div>
                        <div class="col-md-2 text-left">{{ $pesan->tanggal }}</div>
                        <div class="col-md-2 text-left">
                            {{ $pesan->ruangan }}<br>
                            {{ $pesan->waktu_mulai ? date('H:i', strtotime($pesan->waktu_mulai)) : '-' }} -
                            {{ $pesan->waktu_selesai ? date('H:i', strtotime($pesan->waktu_selesai)) : '-' }}
                        </div>
                        <div class="col-md-2 text-left">{{ $pesan->nama_pic }} <br> 
                            <p> 
                                <a href="https://wa.me/{{ $pesan->no_pic }}" target="_blank" style="color: #25D366;">{{ $pesan->no_pic }}</a></p></div> 
                        <div class="col-md-1 text-left">
                            <a href="{{ route('peminjaman.create', ['nama_event' => $pesan->nama_event]) }}" class="btn btn-warning btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-md justify-content-end">
                    {{ $booking->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>

        
    </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date filter handler
            const dateFilter = document.querySelector('input[name="tanggal"]');
            dateFilter.addEventListener('change', function() {
                const url = new URL(window.location);
                url.searchParams.set('tanggal', this.value);
                window.location = url;
            });

            // Search handler
            const searchInput = document.querySelector('input[placeholder="Search..."]');
            let timeout = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const url = new URL(window.location);
                    url.searchParams.set('search', this.value);
                    window.location = url;
                }, 500);
            });
        });
    </script>
</body>

</html>
