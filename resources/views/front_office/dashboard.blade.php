<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Booking List</title>
    <style>
        .custom-table {
            table-layout: fixed;
            /* Mengatur agar lebar tabel sesuai dengan width yang diberikan */
            width: 100%;
            border-collapse: separate;
            /* Memisahkan baris */
            border-spacing: 0 15px;
            /* Jarak antar baris */
        }

        .custom-table th,
        .custom-table td {
            word-wrap: break-word;
            /* Memastikan teks yang panjang tidak merusak tata letak */
            vertical-align: middle;
        }

        .table-row {
            background-color: #FBFCFF;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* Shadow tiap baris */
            overflow: hidden;
        }

        .responsive-container {
            overflow-x: auto;
            /* Pastikan tabel dapat digeser pada layar kecil */
        }

        .table-header th {
            background-color: #091F5B;
            /* Warna biru header */
            color: white;
            padding: 8px;
            text-align: left;
            border: none;
        }

        /* Styling untuk pagination */
        .pagination .page-item .page-link {
            color: black;
            /* Warna teks hitam */
            border: 1px solid #ddd;
            /* Border ringan untuk pemisah */
            margin: 0 4px;
            /* Jarak antar nomor halaman */
        }

        .pagination .page-item.active .page-link {
            background-color: black;
            /* Warna latar hitam untuk halaman aktif */
            color: white;
            /* Warna teks putih untuk halaman aktif */
            border: 1px solid black;
        }

        .nama-event {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .status {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Media Query untuk Responsivitas */
        @media (max-width: 768px) {

            .nama-event-column,
            .status-column {
                width: auto;
            }

            .table-responsive {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.75rem;
            }

            .d-none {
                display: none !important;
                /* Sembunyikan kolom pada layar kecil */
            }
        }

        .btn:hover {
            background-color: #0056b3;
            /* Warna latar belakang saat hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Efek bayangan */
            transform: scale(1.05);
            /* Efek pembesaran */
        }
    </style>
</head>

<body>
    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success text-center mx-3 mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Navbar -->
    @include ('layouts.app')

    <!-- Main Content -->
    <div class="container my-4">
        @if (session('sukses'))
            <div class="alert alert-success">
                {{ session('sukses') }}
            </div>
        @endif

        @if (session('gagal'))
            <div class="alert alert-danger">
                {{ session('gagal') }}
            </div>
        @endif

        <div class="display-4 flex-column flex-md-row text-center mb-4">
            <h1 class="display-4 mb-4 text-center">Booking List</h1>
        </div>
        <!-- Filter and Search in a Single Row -->
        <div class="d-flex justify-content-between mb-3">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline mb-3" style="margin: 8px">
                <select name="status" class="form-select width-select" style="width: 200px;" aria-label="Status Filter"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Check-in" {{ request('status') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                    <option value="Booked" {{ request('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                    <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Check-out
                    </option>
                </select>
            </form>

            <!-- Search Form -->
            <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline mb-3" style="margin: 8px">
                <input type="text" name="search" class="form-control" placeholder="Search by Event Name or Kode Booking"
                    value="{{ old('search', request('search')) }}" onkeyup="this.form.submit()">
            </form>
        </div>

        <!-- Booking Table -->
        <div class="responsive-container">
            <table class="table custom-table">
                <thead class="table-header">
                    <tr>
                        <th class="d-none">Kode Booking</th>
                        <th style="width: 20%;">Nama Event</th>
                        <th style="width: 20%;">Nama Organisasi</th>
                        <th style="width: 20%;">Ruangan dan Waktu</th>
                        <th style="width: 15%;">Nama PIC</th>
                        <th style="width: 15%;">Duty Officer</th>
                        <th style="width: 15%;">User Check-in</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="table-row">
                            <td class="d-none">{{ $booking['booking_id'] }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#eventModal{{ $booking['id'] }}"
                                    class="fw-bold" style="color: #091F5B;">
                                    {{ $booking['name'] }}
                                </a>
                            </td>
                            <td class="fw-semibold">{{ $booking['user_name'] }}</td>

                            <td>
                                @foreach ($booking['ruangans'] as $ruangan)
                                    <p>{{ $ruangan['name'] }}<br>
                                        <span>{{ $ruangan['floor'] }}</span><br>
                                        <span>{{ $booking['start_time'] ?? 'N/A' }} -
                                            {{ $booking['end_time'] ?? 'N/A' }} </span>
                                    </p>
                                @endforeach
                            </td>
                            <td>{{ $booking['pic_name'] }}<br>
                                <a href="https://wa.me/{{ $booking['pic_phone_number'] }}" target="_blank"
                                    style="color: #25D366;">
                                    <span>{{ $booking['pic_phone_number'] }}</span>
                                </a>
                            </td>
                            <td>
                                @if (!empty($booking['absen']['duty_officer']))
                                    <!-- Tampilkan nama Duty Officer -->
                                    <span class="badge bg-success">
                                        {{ $booking['absen']['duty_officer'] }}
                                    </span>
                                @else
                                    <!-- Tombol untuk memilih Duty Officer -->
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#dutyOfficerModal" data-booking-id="{{ $booking['booking_code'] }}">
                                        Pilih Duty Officer
                                    </button>
                                @endif
                            </td>


                            @if (empty($booking['absen']['duty_officer']))
                                <div class="modal fade" id="dutyOfficerModal" tabindex="-1"
                                    aria-labelledby="dutyOfficerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="dutyOfficerForm" method="POST" action="{{ route('dutyofficer.store') }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="dutyOfficerModalLabel">Pilih Duty Officer</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="bookingId" name="id_booking"
                                                        value="{{ $booking['booking_code'] }}">
                                                    <div class="mb-3">
                                                        <label for="dutyOfficerSelect" class="form-label">Duty Officer</label>
                                                        <select class="form-select" id="dutyOfficerSelect"
                                                            name="duty_officer_id" required>
                                                            @foreach ($dutyOfficers as $officer)
                                                                <option value="{{ $officer->id }}">{{ $officer->nama_do }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <td>
                                @if (!empty($booking['absen']))
                                    {{ $booking['absen']['name'] }} <!-- Menampilkan nama user -->
                                @else
                                    <span class="text-warning">Belum Check-in</span>
                                @endif
                            </td>
                            <td>
                                @if (!empty($booking['absen']))
                                    @if ($booking['absen']['status'] === 'Check-in')
                                        <button
                                            class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center custom-shadow fw-bold"
                                            data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $booking['id'] }}"
                                            style="font-weight: bold;">
                                            Check-In
                                        </button>
                                    @elseif ($booking['absen']['status'] === 'Check-out')
                                        <span class="btn btn-danger btn-sm custom-shadow fw-bold"
                                            style="pointer-events: none; border: 2px solid white;">
                                            <i class="fas fa-times-circle me-2"></i> Check-Out
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('inputkode.match', ['id_booking' => $booking['booking_code']]) }}"
                                        class="btn" style="background-color: #969696; color: #fff;">
                                        Booked
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @foreach ($bookings as $booking)
            @if (!empty($booking['absen']) && $booking['absen']['status'] === 'Check-in')
                <div class="modal fade" id="checkoutModal{{ $booking['id'] }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Check-out</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin melakukan check-out untuk booking ini?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('inputkode.checkout') }}">
                                    @csrf
                                    <input type="hidden" name="kode_booking" value="{{ $booking['booking_code'] }}">
                                    <button type="submit" class="btn btn-danger">Check-out</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Dropdown untuk memilih jumlah data per halaman -->
            <div class="mb-3">
                <label for="per-page" class="form-label"></label>
                <select id="per-page" class="form-select" onchange="updatePerPage()">
                    <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    @for ($page = 1; $page <= $totalPages; $page++)
                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $page }}&per_page={{ $perPage }}">
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        </div>

        <!-- JavaScript for Updating Booking Status -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Ambil semua tombol dengan atribut data-bs-target="#dutyOfficerModal"
                const modalButtons = document.querySelectorAll('[data-bs-target="#dutyOfficerModal"]');

                modalButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        // Ambil booking ID dari data attribute tombol
                        const bookingId = this.getAttribute('data-booking-id');
                        // Cari input hidden di dalam modal
                        const hiddenInput = document.getElementById('bookingId');
                        if (hiddenInput) {
                            hiddenInput.value = bookingId; // Set nilai booking ID
                        }
                    });
                });
            });
            function updateStatus(bookingId, newStatus) {
                fetch(`/bookings/${bookingId}/update-status`, { // Gunakan backticks untuk URL
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Log data untuk debugging
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Reload untuk menampilkan status terbaru
                            });
                        } else {
                            Swal.fire('Error', 'Failed to update status', 'error');
                        }
                    })
                    .catch(error => Swal.fire('Error', 'An error occurred while updating the status.', 'error'));
            }

            function updatePerPage() {
                const perPage = document.getElementById('per-page').value;
                const currentUrl = "{{ url()->current() }}";
                window.location.href = `${currentUrl}?per_page=${perPage}`;
            }


        </script>
</body>

</html>