<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            margin: 10px;
        }

        .modal-header {
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

        .btn-close {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 20px;
        }

        /* Modal Edit */
        .main-card {
            border-radius: 10px;
            background-color: #fff;
            border-color: #091F5B;
            padding: 65px;
            margin: auto;
        }

        .info-card {
            border-radius: 15px;
            border-color: #091F5B;
            font-size: 14px;
        }

        /* Make the layout responsive for smaller screens */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 100%;
                margin: 0;
            }

            .modal-content {
                border-radius: 8px;
            }

            .main-card {
                padding: 30px;
            }

            .table th,
            .table td {
                font-size: 12px;
            }

            .col-md-3,
            .col-md-2,
            .col-md-1 {
                font-size: 12px;
            }

            .d-flex {
                flex-wrap: wrap;
            }

            .d-flex>.col-md-3,
            .d-flex>.col-md-2 {
                flex: 1 1 100%;
                /* Make columns stack on small screens */
                margin-bottom: 10px;
            }

            .modal-body {
                padding: 0px;
            }

            /* pagination */
            .pagination .page-item .page-link {
                color: #091F5B;
                /* Text color for the links */
                background-color: transparent;
                /* Background color for links */
                border: 1px solid #091F5B;
                /* Border color */
            }

            .pagination .page-item.active .page-link {
                color: #fff;
                /* Text color for active link */
                background-color: #091F5B;
                /* Background color for active link */
                border-color: #091F5B;
                /* Border color for active link */
            }

            .pagination .page-item:hover .page-link {
                color: #fff;
                /* Text color on hover */
                background-color: #091F5B;
                /* Background color on hover */
                border-color: #091F5B;
                /* Border color on hover */
            }

            .pagination {
                margin-top: 20px;
                /* Optional: Add some spacing above */
            }

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

        <!-- Combined Filters -->
        <div class="row mb-4">
            <form method="GET" action="{{ route('marketing.peminjaman') }}"
                class="d-flex align-items-center justify-content-between">
                <!-- Date Filter -->
                <div class="me-3">
                    <input type="date" name="date" class="form-control"
                        value="{{ old('date', request('date', $filterDate)) }}" onchange="this.form.submit()">
                </div>
                <!-- Search Filter -->
                <div>
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by Event Name"
                        value="{{ old('search', request('search')) }}" onkeyup="this.form.submit()">
                </div>
            </form>
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
                            {{ $booking['booking_items'][0]['booking_date'] ?? 'No booking date available' }}
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
        <div class="d-flex justify-content-between align-items-center mb-3 mx-3">
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
            <div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        @for ($page = 1; $page <= $totalPages; $page++)
                            <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                                <a class="page-link"
                                    href="{{ url()->current() }}?page={{ $page }}&per_page={{ $perPage }}&date={{ request('date') }}&search={{ request('search') }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            </div>
        </div>

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
            <form method="POST" action="{{ route('marketing.store') }}">
                @csrf
                <div class="modal fade" id="editModal{{ $booking['id'] }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $booking['id'] }}" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 900px;">
                        <div class="modal-content">
                            <div class="main-card border">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <h4 class="text-center mb-4 fw-bold" id="editModalLabel">Formulir Peminjaman
                                    Barang</h4>
                                <div class="info-card border mb-2 p-3">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0">Nama Event</p>
                                        <p class="mb-0" style="color: #091F5B; margin-left: 20px;">
                                            <strong>{{ $booking['name'] }}</strong>
                                        </p>
                                    </div>
                                </div>
                                <div class="info-card border p-3 mb-3">
                                    <div class="row">
                                        <input type="hidden" name="kode_booking"
                                            value="{{ $booking['booking_code'] }}">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <p class="mb-0">Ruangan</p>
                                            <p class="mb-0" style="color: #091F5B; margin-left: 40px;">
                                                {{ $ruangan['name'] }}</p>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center">
                                            <p class="mb-0">PIC</p>
                                            <p class="mb-0" style="color: #091F5B; margin-left: 25px;">
                                                {{ $booking['pic_name'] }}</p>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center mt-2">
                                            <p class="mb-0">Tanggal</p>
                                            <p class="mb-0 text-end" style="color: #091F5B; margin-left: 50px;">
                                                {{ $booking['booking_items'][0]['booking_date'] }}</p>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center mt-2">
                                            <p class="mb-0">Jam</p>
                                            <p class="mb-0 text-end" style="color: #091F5B; margin-left: 20px;">
                                                {{ $booking['start_time'] ?? 'N/A' }} -
                                                {{ $booking['end_time'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="fw-bold">List Barang yang Dipinjam</h6>
                                <table class="table table-bordered" id="barangList{{ $booking['id'] }}">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
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
                                            <td class="text-center">-</td>
                                            <td><button type="button" class="btn btn-danger removeItem"
                                                    onclick="removeItem(this)">Hapus</button></td>
                                        </tr>
                                        @foreach ($booking['database_items'] as $dbItem)
                                            <tr id="row-{{ $dbItem->id }}">
                                                <td>{{ $no++ }}</td>
                                                <td class="text-align-left">{{ $dbItem->nama_item }}</td>
                                                <td class="text-center">{{ $dbItem->jumlah }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger removeItem"
                                                        onclick="removeItem(this)" data-id="{{ $dbItem->id }}"
                                                        data-row-id="row-{{ $dbItem->id }}">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="addItem({{ $booking['id'] }})">Tambah Barang</button>
                                <div class="text-center mt-4 mb-4">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                <div class="modal-footer border-0">
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="signature-group mt-4 text-center">
                                            <p class="signature-title">Mengetahui,<br> Marketing</p>
                                            <p><img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                                                    style="width: 80px; height: 80px;"></p>
                                            <p>{{ $booking['history'][0]['pic_marketing'] }}</p>
                                        </div>
                                        <div class="signature-group mt-4 text-center">
                                            <p>Mengetahui Peminjam</p>
                                            <p>Peminjam</p>
                                        </div>
                                        <div class="signature-group mt-4 text-center">
                                            <p>Mengetahui Front Office</p>
                                            <p>Front Office</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        @endforeach



    </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchData(page = 1) {
            const search = document.getElementById('searchInput').value;
            const date = document.getElementById('dateFilter').value;

            // Build the API URL
            const url = new URL('https://event.mcc.or.id/api/event');
            url.searchParams.set('status', 'booked');
            url.searchParams.set('page', page);
            if (search) url.searchParams.set('search', search);
            if (date) url.searchParams.set('date', date);

            // Fetch data from API
            fetch(url, {
                    headers: {
                        'X-API-KEY': 'your-api-key-here'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateUI(data.data); // Update table rows
                        updatePagination(data.total_pages, page); // Update pagination
                    } else {
                        console.error('API Error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('dateFilter');

            // Add search filter event
            searchInput.addEventListener('input', debounce(() => {
                fetchData(); // Fetch data with updated search term
            }, 500)); // Delay API calls for better performance

            // Add date filter event
            dateFilter.addEventListener('change', () => {
                fetchData(); // Fetch data with updated date filter
            });

            // Initial fetch
            fetchData();
        });

        // Debounce function to delay API calls
        function debounce(func, delay) {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), delay);
            };
        }


        function updatePerPage() {
            const selectElement = document.getElementById('per-page');
            const perPageValue = selectElement.value;

            // Get the current URL
            const currentUrl = new URL(window.location.href);

            // Set or update the 'per_page' query parameter
            currentUrl.searchParams.set('per_page', perPageValue);

            // Redirect to the updated URL
            window.location.href = currentUrl.toString();
        }


        function addItem(bookingId) {
            let table = document.getElementById('barangList' + bookingId);
            let rowCount = table.rows.length;
            let row = table.insertRow(rowCount);

            row.innerHTML = `
                <td>${rowCount}</td>
                <td><input type="text" name="items[${rowCount}][nama_item]" class="form-control"></td>
                <td><input type="number" name="items[${rowCount}][jumlah]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger removeItem" onclick="removeItem(this)">Hapus</button></td>
            `;
        }

        function removeItem(button) {
            let itemId = button.getAttribute('data-id'); // Get the item ID
            let rowId = button.getAttribute('data-row-id'); // Get the row ID

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send DELETE request to the server
                    fetch(`/items/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table if successful
                                document.getElementById(rowId).remove();

                                Swal.fire({
                                    title: "Deleted!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to delete the item: " + data.message,
                                    icon: "error",
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred. Please try again.",
                                icon: "error",
                            });
                        });
                }
            });
        }
    </script>
</body>

</html>
