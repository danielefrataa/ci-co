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
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .room-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .room-card h5 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .room-card p {
            margin-bottom: 5px;
        }

        .room-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .room-status.belum-digunakan {
            background-color: #ccc;
            color: #666;
        }

        .room-status.dipesan {
            background-color: #ccc;
            color: #666;
        }

        .room-status.sedang-digunakan {
            background-color: #99ff99;
            color: #000;
        }

        .room-status.sudah-digunakan {
            background-color: #ff9999;
            color: #fff;
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
    <h2 class="text-center">Room List</h2>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <select class="form-select">
                    <option>Semua Lantai</option>
                    <option>Lantai 1</option>
                    <option>Lantai 2</option>
                    <option>Lantai 3</option>
                    <option>Lantai 4</option>
                </select>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="room-card">
                            <h5>Studio Musik</h5>
                            <p>Lantai 4</p>
                            <p>16:00 - 20:00</p>
                            <span class="room-status belum-digunakan">belum digunakan</span>
                        </div>
                        <div class="room-card">
                            <h5>Studio Musik</h5>
                            <p>Lantai 4</p>
                            <p>16:00 - 20:00</p>
                            <span class="room-status dipesan">dipesan</span>
                        </div>
                        <div class="room-card">
                            <h5>Studio Musik</h5>
                            <p>Lantai 4</p>
                            <p>16:00 - 20:00</p>
                            <span class="room-status dipesan">dipesan</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sedang-digunakan">sedang digunakan</span>
                        </div>
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sedang-digunakan">sedang digunakan</span>
                        </div>
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sedang-digunakan">sedang digunakan</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sudah-digunakan">sudah digunakan</span>
                        </div>
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sudah-digunakan">sudah digunakan</span>
                        </div>
                        <div class="room-card">
                            <h5>Lab Komputer</h5>
                            <p>Lantai 4</p>
                            <p>12:00 - 17:00</p>
                            <span class="room-status sudah-digunakan">sudah digunakan</span>
                        </div>
                    </div>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item   
 disabled">
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
    </div>
    @csrf
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>