<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Room List</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .room-card {
            border: 1px;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #FBFCFF;
            box-shadow: 1px 4px 2px #D1D1D1, -1px 4px 2px #D1D1D1;
        }

        .room-card p {
            margin-bottom: 5px;
        }

        .room-status {
            padding: 5px 10px;
            border-radius: 10px;
            font-weight: bold;
        }

        .room-status[value|="dipesan"] {
            background-color: #2b2b2b;
            color: #c1c1c1
        }

        .room-status[value|="sedang digunakan"] {
            background-color: #A3F1BA60;
            color: #07CF43
        }

        .room-status[value|="kosong"] {
            background-color: #F1A3A450;
            color: #E53235;
        }
    </style>
</head>

<body>
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
    <h1 class="text-center">Room List</h1>

    <div class="container">
        <div class="d-flex justify-content-between">
            <select class="form-select my-5 w-25" style="border-radius: 15px;">
                <option>Semua Lantai</option>
                <option>Lantai 1</option>
                <option>Lantai 2</option>
                <option>Lantai 3</option>
                <option>Lantai 4</option>
                <option>Lantai 5</option>
                <option>Lantai 6</option>
                <option>Lantai 7</option>
                <option>Lantai 8</option>
            </select>
            <div class="mt-5">
                <input type="text" class="py-2 text-center " placeholder="Telusuri" style="border-radius: 21px;">

                <button type="button">
                    <span class="fa fa-search"></span>
                </button>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Studio Musik</span>
                        <span class="room-status shadow shadow-sm" value="dipesan">dipesan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>16:00 - 20:00</p>
                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Studio Musik</span>
                        <span class="room-status shadow shadow-sm" value="dipesan">dipesan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>16:00 - 20:00</p>

                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Studio Musik</span>
                        <span class="room-status shadow shadow-sm" value="dipesan">dipesan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>16:00 - 20:00</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="sedang digunakan">sedang digunakan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>
                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="sedang digunakan">sedang digunakan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>

                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="sedang digunakan">sedang digunakan</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="kosong">kosong</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>

                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="kosong">kosong</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>

                </div>
                <div class="room-card">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold mt-1">Lab Komputer</span>
                        <span class="room-status shadow shadow-sm" value="kosong">kosong</span>
                    </div>
                    <p>Lantai 4</p>
                    <p>12:00 - 17:00</p>

                </div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">3</a></li>
                    <li class="page-item"><a class="page-link">...</a></li>  
                    <li class="page-item"><a class="page-link">10</a></li>
                    <li class="page-item">
                        <a class="page-link">Next</a>  
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    @csrf
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>