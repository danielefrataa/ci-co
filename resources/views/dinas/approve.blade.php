<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DISKOPINDAG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css">
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

        <h1 class="display-4 mb-4 text-center">Approval List</h1>

        <!-- Combined Filters -->
        <div class="row mb-4">
            <form method="GET" action="{{ route('dinas.approve') }}"
                class="d-flex align-items-center justify-content-between">
                <!-- Date Filter -->
                <div class="me-3">
                    <input type="date" name="date" class="form-control"
                        value="{{ old('date', request('date', $filterDate)) }}" onchange="this.form.submit()">
                </div>
                <!-- Search Filter -->
                <div>
                    <input type="text" name="search" class="form-control" placeholder="Search"
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
                    <div class="col-md-1 text-left" style="font-weight: bold">Approval</div>
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
                            {{ $filterDate ?? 'No booking date available' }}
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            @foreach ($booking['ruangans'] as $ruangan)
                                <p>{{ $ruangan['name'] }}<br>
                                    <span>{{ $ruangan['floor'] }}</span><br>
                                    <span>{{ $booking['start_time'] ?? 'N/A' }} -
                                        {{ $booking['end_time'] ?? 'N/A' }}</span>
                                </p>
                            @endforeach
                        </div>
                        <div class="col-md-2 text-left" style="color:#091F5B; font-weight: 600;">
                            {{ $booking['pic_name'] }} <br>
                        </div>
                        <div class="col-md-1 text-left">
                            <!-- Checkbox (Triggers Modal) -->
                            <input type="checkbox" class="form-check-input trigger-modal"
                                id="checkBooking{{ $booking['id'] }}" data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $booking['id'] }}" onclick="return false;">
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
            <form method="POST" action="{{ route('approval.store') }}">
                @csrf
                <div class="modal fade" id="editModal{{ $booking['id'] }}" tabindex="-1"
                    aria-labelledby="editModalLabel{{ $booking['id'] }}" aria-hidden="true">
                    <div class="modal-dialog" style="max-width: 900px;">
                        <div class="modal-content">
                            <div class="main-card border">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <h4 class="text-center mb-4 fw-bold" id="editModalLabel">Formulir Peminjaman Barang
                                </h4>
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
                                        <!-- Hidden booking code -->
                                        <input type="hidden" name="kode_booking"
                                            value="{{ $booking['booking_code'] }}">

                                        <!-- Ruangan Section -->
                                        <div class="col-md-6 d-flex align-items-center">
                                            <p class="mb-0 fw-bold flex-shrink-0" style="width: 100px;">Ruangan</p>
                                            <p class="mb-0" style="color: #091F5B;">
                                                @foreach ($booking['ruangans'] as $ruangan)
                                                    {{ $ruangan['name'] }} <br>
                                                @endforeach
                                            </p>
                                        </div>

                                        <!-- PIC Section -->
                                        <div class="col-md-6 d-flex align-items-center">
                                            <p class="mb-0 fw-bold flex-shrink-0" style="width: 100px;">PIC</p>
                                            <p class="mb-0" style="color: #091F5B;">
                                                {{ $booking['pic_name'] }}
                                            </p>
                                        </div>

                                        <!-- Tanggal Section -->
                                        <div class="col-md-6 d-flex align-items-center mt-2">
                                            <p class="mb-0 fw-bold flex-shrink-0" style="width: 100px;">Tanggal</p>
                                            <p class="mb-0 text-end ms-3" style="color: #091F5B;">
                                                @php
                                                    // Filter booking_items to match the filtered date
                                                    $matchingBookingItem = collect(
                                                        $booking['booking_items'],
                                                    )->firstWhere('booking_date', $filterDate);
                                                @endphp

                                                {{ $matchingBookingItem['booking_date'] ?? 'N/A' }}
                                            </p>
                                        </div>

                                        <!-- Jam Section -->
                                        <div class="col-md-6 d-flex align-items-center mt-2">
                                            <p class="mb-0 fw-bold flex-shrink-0" style="width: 100px;">Jam</p>
                                            <p class="mb-0 text-end" style="color: #091F5B;">
                                                {{ $booking['start_time'] ?? 'N/A' }} -
                                                {{ $booking['end_time'] ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <input type="hidden" name="id_booking" value="{{ $booking['id'] }}">
                                        <input type="hidden" name="kadin_approval"
                                            value="1"><!-- Add this for kadin approval -->
                                        <input type="hidden" name="kabin_approval"
                                            value="1"><!-- Add this for kabin approval -->
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary simpanButton">
                                    Approve
                                </button>

                                <div class="modal-footer border-0">
                                    <div class="d-flex justify-content-between w-100">
                                        <!-- Marketing Approval -->
                                        <div class="signature-group mt-4 text-center">
                                            <p class="signature-title">Mengetahui,<br> Marketing</p>
                                            <p>
                                                <img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                                                    style="width: 80px; height: 80px;">
                                            </p>
                                            <p>{{ $booking['history'][0]['pic_marketing'] }}</p>
                                        </div>

                                        <!-- Kepala Dinas Approval -->
                                        <div class="signature-group mt-4 text-center">
                                            <p class="signature-title">Mengetahui,<br> Kepala Dinas</p>
                                            <p>
                                                <img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                                                    style="width: 80px; height: 80px;">
                                            </p>
                                            @foreach ($booking['database_items'] as $dbItem)
                                                <!-- Check if booking_id matches id_booking -->
                                                @if ($dbItem->id_booking == $booking['booking_id'])
                                                    <!-- Then check kadin_approval -->
                                                    <p>
                                                        @if ($dbItem->kadin_approval == 1)
                                                            ok
                                                        @else
                                                            bad
                                                        @endif
                                                    </p>
                                                @endif
                                            @endforeach

                                            <p>blabla</p>
                                        </div>

                                        <!-- Kepala Bidang Keuangan Approval -->
                                        <div class="signature-group mt-4 text-center">
                                            <p class="signature-title">Mengetahui,<br> Kepala Bidang Keuangan</p>
                                            <p>
                                                <img src="{{ asset('images/marketing_ttd.png') }}" alt="Tanda Tangan"
                                                    style="width: 80px; height: 80px;">
                                            </p>
                                            <!-- Check if kabin_approval is 1 or 0 -->
                                            @php
                                                $approvalStatus = $booking['dinas_approval']->kabin_approval ?? 0;
                                            @endphp
                                            <p>
                                                @if ($approvalStatus == 0)
                                                    <em class="text-danger">Belum Disetujui</em>
                                                @else
                                                    <em class="text-success">Sudah Disetujui</em>
                                                @endif
                                            </p>
                                            <p>blabla</p>
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
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
        // Toggle checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.simpanButton');

            if (buttons.length > 0) {
                buttons.forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        // Prevent the form submission
                        event.preventDefault();

                        // Get the booking ID and user name
                        const bookingId = this.getAttribute('data-booking-id');
                        let userName = this.getAttribute('data-user-name');

                        // Default to 'Unknown User' if userName is null or empty
                        if (!userName || userName === 'null') {
                            userName = 'Unknown User';
                        }

                        // Find the checkbox element
                        const checkbox = document.getElementById('checkBooking' + bookingId);

                        if (checkbox) {
                            // Replace checkbox with "Approved by user_name" text
                            const approvedText = document.createElement('p');
                            approvedText.classList.add('text-success', 'fw-bold', 'mb-0');
                            approvedText.innerText = `Approved by ${userName}`;

                            checkbox.parentNode.replaceChild(approvedText, checkbox);
                        }

                        // Close the modal programmatically
                        const modalElement = this.closest('.modal');
                        if (modalElement) {
                            if (bootstrap.Modal && bootstrap.Modal.getInstance) {
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                if (modalInstance) {
                                    modalInstance.hide();
                                }
                            } else {
                                // Bootstrap 4 fallback
                                $(modalElement).modal('hide');
                            }
                        }

                        // Submit the form programmatically
                        const form = this.closest('form');
                        if (form) {
                            form.submit();
                        }
                    });
                });
            } else {
                console.warn('No buttons with class "simpanButton" found.');
            }
        });

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
    </script>
</body>

</html>
