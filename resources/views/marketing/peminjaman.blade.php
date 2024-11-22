<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing</title>
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

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: none;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table-light {
            background-color: #e9ecef;
        }

        .table-bordered {
            border-color: #dee2e6;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
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
            <div class="card-body text-white my-2 shadow-lg" style="background-color:#091F5B; border-radius: 8px;">
                <div class="row align-items-center">
                    <div class="d-none">Aksi</div>
                    <div class="col-md-3 text-left" style="font-weight: bold">Nama Event</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Nama Organisasi</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Tanggal</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Ruangan dan Waktu</div>
                    <div class="col-md-2 text-left" style="font-weight: bold">Nama PIC</div>
                    <div class="col-md-1 text-left" style="font-weight: bold">Aksi</div>
                </div>
            </div>

            @foreach ($bookings as $booking)
                <div class="card-header text-dark my-2 shadow-sm" style="background-color:white; border-radius: 5px;">
                    <div class="row align-items-center">
                        <div class="d-none">
                            {{ $booking['booking_code'] }}
                        </div>
                        <div class="col-md-3 text-left">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#eventModal{{ $booking['id'] }}"
                                class="fw-bold" style="color: #091F5B;">
                                {{ $booking['name'] }}
                            </a>
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            {{ $booking['user_name'] }}
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            {{ $pesan['booking_items'][0]['booking_date'] ?? 'No booking date available' }}
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            @foreach ($booking['ruangans'] as $ruangan)
                                <p>{{ $ruangan['name'] }}<br>
                                    <span>{{ $ruangan['floor'] }}</span><br>
                                    <span>{{ $booking['start_time'] ?? 'N/A' }} - {{ $booking['end_time'] ?? 'N/A' }}
                                    </span>
                                </p>
                            @endforeach
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            {{ $booking['pic_name'] }} <br>
                            <a href="https://wa.me/{{ $booking['pic_phone_number'] }}" target="_blank"
                                style="color: #25D366;">
                                {{ $booking['pic_phone_number'] }}</a>
                        </div>
                        <div class="col-md-1 text-left">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $booking['id'] }}">Edit</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Dropdown untuk memilih jumlah data per halaman -->
            <div class="mb-3">
                <label for="per-page" class="form-label">Jumlah Data Per Halaman:</label>
                <select id="per-page" class="form-select" onchange="updatePerPage()">
                    <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <!-- Pagination Section -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @for ($page = 1; $page <= $totalPages; $page++)
                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ url()->current() }}?page={{ $page }}&per_page={{ $perPage }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </nav>

            <!-- Modal for Event Details -->
            @foreach ($bookings as $booking)
                <div class="modal fade" id="eventModal{{ $booking['id'] }}" tabindex="-1"
                    aria-labelledby="eventModalLabel{{ $booking['id'] }}" aria-hidden="true">
                    <!-- Mengatur ukuran modal agar lebih kecil -->
                    <div class="modal-dialog" style="max-width: 600px;"> <!-- Menyesuaikan ukuran -->
                        <div class="modal-content p-0 rounded-3">
                            <div class="modal-header"
                                style="border: none; padding-bottom: 0px; display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="modal-title w-100 text-center" id="eventModalLabel{{ $booking['id'] }}"
                                    style="color: #091F5B; font-weight: 400;">
                                    Detail Acara
                                </h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body" style="padding-top: 0px;">
                                <!-- Nama Acara dengan garis bawah biru tebal -->
                                <div class="text-center mb-2"
                                    style="border-bottom: 3px solid #091F5B; padding-bottom: 5px; justify-content: center;">
                                    <div style="font-size: 1.5rem;">
                                        {{ $booking['name'] }}
                                    </div>
                                </div>
                                <!-- Isi Detail Acara -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nama PIC:</strong></p>
                                        <p>{{ $booking['pic_name'] }}</p>

                                        <p><strong>Kategori Ekraf:</strong></p>
                                        <p>{{ $booking['kategori_ekraf'] }}</p>

                                        <p><strong>Jumlah Peserta:</strong></p>
                                        <p>{{ $booking['participant'] }} Orang</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>No Telp:</strong></p>
                                        <p>{{ $booking['pic_phone_number'] }}</p>
                                        <p><strong>Kategori Event:</strong></p>
                                        <p>{{ $booking['kategori_event'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            <!-- Modal Edit-->
            @foreach ($bookings as $booking)
                <div class="modal fade" id="editModal{{ $booking['id'] }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $booking['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="min-width: 900px;">
                        <div class="modal-content p-4">
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold" id="editModalLabel">Formulir Peminjaman Barang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <p><strong>Nama Event:</strong> {{ $booking['name'] }}</p>
                                        <p><strong>Ruangan:</strong> {{ $ruangan['name'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>PIC:</strong> {{ $booking['pic_name'] }}</p>
                                        <p><strong>Jam:</strong> {{ $booking['start_time'] ?? 'N/A' }} -
                                            {{ $booking['end_time'] ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <h6 class="fw-bold">List Barang yang Dipinjam</h6>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-align-left" style="text-align: left;">
                                                @if (!empty($booking['tools']))
                                                    {{ $booking['tools'] }}
                                                @else
                                                    No tools
                                                @endif
                                            </td>
                                            <td colspan="3" class="text-center">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary btn-sm">Tambah Barang</button>

                            </div>
                            <div class="modal-footer border-0">
                                <div class="d-flex justify-content-between w-100">
                                    <div class="signature-group mt-4 text-center">
                                        <p class="signature-title">Mengetahui,<br> Marketing</p>
                                        <p><img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                                                style="width: 80px; height: 80px;"></p>
                                        <p>{{ $booking['history'][0]['pic_marketing'] }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p>Mengetahui Peminjam</p>
                                        <p>Peminjam</p>
                                    </div>
                                    <div class="text-center">
                                        <p>Mengetahui Front Office</p>
                                        <p>Front Office</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach



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
