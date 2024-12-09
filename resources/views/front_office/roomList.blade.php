<!DOCTYPE html>
<html lang="en">
@php
use Carbon\Carbon;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room List</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            min-height: 120px;
            /* Sesuaikan tinggi minimum yang diinginkan */
        }



        .room-card p {
            margin-bottom: 5px;
        }

        .room-status {
            padding: 5px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14px;
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

        .pagination .page-item.active .page-link {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }

        .pagination .page-item .page-link {
            color: #000;
        }

        .pagination .page-item .page-link:hover {
            color: #fff;
            background-color: #000;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
        }
    </style>
</head>

<body>
    @include('layouts.app');
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[placeholder="Telusuri"]');
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

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <h1 class="text-center">Room List</h1>

    <div class="container">
        <div class="d-flex justify-content-start">
            <form method="GET" action="{{ route('front_office.roomList') }}" class="d-inline">
                <select name="lantai" class="form-select my-5 shadow-sm" aria-label="Status Filter" style="border-radius: 15px;" onchange="this.form.submit()">
                    <option value="">Semua Lantai</option>
                    <option value="lantai 1" {{ request('lantai') == 'lantai 1' ? 'selected' : '' }}>Lantai 1</option>
                    <option value="lantai 2" {{ request('lantai') == 'lantai 2' ? 'selected' : '' }}>Lantai 2</option>
                    <option value="lantai 3" {{ request('lantai') == 'lantai 3' ? 'selected' : '' }}>Lantai 3</option>
                    <option value="lantai 4" {{ request('lantai') == 'lantai 4' ? 'selected' : '' }}>Lantai 4</option>
                    <option value="lantai 5" {{ request('lantai') == 'lantai 5' ? 'selected' : '' }}>Lantai 5</option>
                    <option value="lantai 6" {{ request('lantai') == 'lantai 6' ? 'selected' : '' }}>Lantai 6</option>
                    <option value="lantai 7" {{ request('lantai') == 'lantai 7' ? 'selected' : '' }}>Lantai 7</option>
                    <option value="lantai 8" {{ request('lantai') == 'lantai 8' ? 'selected' : '' }}>Lantai 8</option>
                </select>
            </form>
            <form method="GET" action="{{ route('front_office.roomList') }}" class="d-inline" style="padding-left: 16px;">
                <select name="status" class="form-select my-5 shadow-sm" aria-label="Status Filter" style="border-radius: 15px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Kosong</option>
                    <option value="unknown" {{ request('status') == 'unknown' ? 'selected' : '' }}>Dipesan</option>
                    <option value="Check-in" {{ request('status') == 'Check-in' ? 'selected' : '' }}>Sedang Digunakan</option>

                </select>

            </form>
            <div class="mb-5">
                <div class="mt-5" style="position: relative; display: inline-block; width: 100%; padding-left: 716px;">
                    <input type="text" class="py-2 pl-3 pr-5 text-center" placeholder="Telusuri" name="search" value="{{ request('search') }}" style="width: 100%; border-radius: 21px; border: 1px solid #ccc;">

                    <button type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
                        <span class="fa fa-search" style="font-size: 18px;"></span>
                    </button>
                </div>
            </div>
        </div>

        <div id="pagination-container">
            <div class="row d-flex align-items-stretch">
                @foreach ($rooms as $room)
                <div class="col-md-4">
                    <div class="room-card">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold mt-1">{{ $room['name'] }}</span>
                            @php
                            $status = $room['status'];
                            if($status == 'Check-out') {
                            $status = 'kosong';
                            } elseif ($status == 'Check-in') {
                            $status = 'sedang digunakan';
                            } else {
                            $status = 'dipesan';
                            }
                            @endphp
                            <span class="room-status  shadow-sm" value="{{ $status }}">{{ $status }}</span>
                        </div>

                        <p>{{ $room['floor'] }}</p>
                        <p>
                            @if ($room['status'] == 'Check-out')

                            @elseif (!empty($room['start']) && !empty($room['end']))
                            {{ $room['start'] }} - {{ $room['end'] }}

                            @else

                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
                    <?php 
                    dd($room); ?>
            </div>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                @for ($page = 1; $page <= $totalPages; $page++)
                    <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                    <a class="page-link"
                        href="{{ url()->current() }}?page={{ $page }}&search={{ request('search') }}&lantai={{ request('lantai') }}&status={{ request('status') }}&per_page={{ $perPage }}">
                        {{ $page }}
                    </a>
                    </li>
                    @endfor
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