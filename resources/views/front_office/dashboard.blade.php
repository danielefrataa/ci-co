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
    <script src="{{ asset('js/app.js') }}"></script>

    <title>Booking List</title>
</head>

<body>

    <!-- Navbar -->
    @include ('layouts.app')

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success text-center mx-3 mt-3">
            {{ session('success') }}
        </div>
    @endif

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
        <div class="row align-items-center mb-3" style="margin-top: -10px;">
            <!-- Filter -->
            <div class="col-md-3">
                <form method="GET" action="{{ route('front_office.dashboard') }}">
                    <select name="status" class="form-select" style="width: 100%;" aria-label="Status Filter" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Check-in" {{ request('status') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                        <option value="Booked" {{ request('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                        <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
                    </select>
                </form>
            </div>

            <!-- Search (Tengah) -->
            <div class="col-md-6 text-center">
                <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline-block" style="width: 100%;">
                    <input type="text" name="search" class="form-control" placeholder="Search by Event Name or Kode Booking"
                        value="{{ old('search', request('search')) }}" onkeyup="this.form.submit()">
                </form>
            </div>
        
            <!-- Export -->
<div class="col-md-3 text-end">
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-download"></i> Export
        </button>
        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
            <li><a class="dropdown-item" href="{{ route('bookings.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> Export as PDF</a></li>
            <li><a class="dropdown-item" href="{{ route('bookings.export', ['format' => 'csv']) }}"><i class="fas fa-file-csv"></i> Export as CSV</a>
            </li>
        </ul>
    </div>
</div>

        <!-- Booking Table -->
        <div class="responsive-container">
            <table class="table custom-table">
                <thead class="table-header">
                    <tr>
                        <th class="d-none">Kode Booking</th>
                        <th style="width: 15%;">Kode Booking</th>
                        <th style="width: 20%;">Nama Event</th>
                        <th style="width: 20%;">Nama Organisasi</th>
                        <th style="width: 20%;">Ruangan dan Waktu</th>
                        <th style="width: 15%;">Duty Officer</th>
                        <th style="width: 15%;">User Check-in</th>
                        <th style="width: 12%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="table-row">
                            <td class="d-none">{{ $booking['booking_id'] }}</td>
                            <td>
                                <p style="color:#091F5B;">
                                {{ $booking['booking_code'] }}<br>
                            </p>
                            </td>
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
                            <td>
                                @if (!empty($booking['absen']['duty_officer']))
                                    <!-- Tampilkan nama Duty Officer -->
                                    {{ $booking['absen']['duty_officer'] }}
                                @else
                                        <!-- Tombol untuk memilih Duty Officer -->
                                        <button type="button" 
                                        class="btn btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#dutyOfficerModal" 
                                        data-booking-id="{{ $booking['booking_code'] }}"
                                        @if (isset($booking['absen']['status']) && strtolower(trim($booking['absen']['status'])) === 'check-out') 
                                            onclick="showCheckoutAlert(event)"
                                        @endif
                                        style="background-color: #091F5B; color: white;">
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
                                    {{ $booking['absen']['name'] }}<br>
                            @php
                                $phone = preg_replace('/\D/', '', $booking['absen']['phone']);  // Remove any non-numeric characters
                            @endphp
                        
                            {{-- Display the phone number --}}
                            <a href="https://wa.me/{{ $phone }}" target="_blank" style="color: #25D366;">
                                {{ $phone }}
                            </a>
                            @else
                                <span class="text-warning">Belum Check-in</span>
                            @endif
                            </td>                        
                            <td>
                                @if (!empty($booking['absen']))
                                    @if ($booking['absen']['status'] === 'Check-in')
                                        <button class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center custom-shadow fw-bold rounded shadow"
                                                data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $booking['id'] }}" style="font-weight: bold;">
                                            Check-In
                                        </button>
                                    @elseif ($booking['absen']['status'] === 'Check-out')
                                        <span class="btn btn-danger btn-sm d-inline-block custom-shadow fw-bold rounded shadow"
                                              style="pointer-events: none; border: 2px solid white; padding: 5px 10px;">
                                            Check-Out
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('inputkode.match', ['id_booking' => $booking['booking_code']]) }}"
                                       class="btn btn-sm w-100 d-flex align-items-center justify-content-center custom-shadow fw-bold rounded shadow"
                                       style="background-color: #969696; color: #fff;">
                                        Booked
                                    </a>
                                @endif
                            @endforeach
                        </td>
                    </tr>
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
                        @if (empty($booking['absen']['duty_officer']))
                            <!-- Display warning if duty officer is not selected -->
                            <div class="alert alert-warning" role="alert">
                                <strong>Peringatan!</strong> Anda harus memilih Duty Officer terlebih dahulu sebelum melakukan Check-out.
                            </div>
                        @else
                            <!-- Confirmation message if duty officer is selected -->
                            Apakah Anda yakin ingin melakukan check-out untuk booking ini?
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if (!empty($booking['absen']['duty_officer']))
                            <!-- Show the checkout form only if duty officer is selected -->
                            <form method="POST" action="{{ route('inputkode.checkout') }}">
                                @csrf
                                <input type="hidden" name="kode_booking" value="{{ $booking['booking_code'] }}">
                                <button type="submit" class="btn btn-danger">Check-out</button>
                            </form>
                        @endif
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
                            
                            <!-- Pagination and Dropdown for per page selection -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
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
                            </div>
                            
                            <!-- Modal for Event Details -->
                            @foreach ($bookings as $booking)
                                <div class="modal fade" id="eventModal{{ $booking['id'] }}" tabindex="-1"
                                     aria-labelledby="eventModalLabel{{ $booking['id'] }}" aria-hidden="true">
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
                                                <div class="text-center mb-2" style="border-bottom: 3px solid #091F5B; padding-bottom: 5px; justify-content: center;">
                                                    <div style="font-size: 1.5rem;">
                                                        {{ $booking['name'] }}
                                                    </div>
                                                </div>
                            
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Nama PIC:</strong></p>
                                                        <p><strong style="font-weight: bold; font-size: 18px;">{{ $booking['pic_name'] }}</strong></p>
                            
                                                        <p><strong>Kategori Ekraf:</strong></p>
                                                        <p>{{ $booking['kategori_ekraf'] }}</p>
                            
                                                        <p><strong>Jumlah Peserta:</strong></p>
                                                        <p>{{ $booking['participant'] }} Orang</p>
                                                    </div>
                            
                                                    <div class="col-md-6">
                                                        <p><strong>No Telp:</strong></p>
                                                        <p><a href="https://wa.me/{{ $booking['pic_phone_number'] }}" target="_blank" style="color: #25D366;">
                                                            <span>{{ $booking['pic_phone_number'] }}</span>
                                                        </a></p>
                            
                                                        <p><strong>Kategori Event:</strong></p>
                                                        <p>{{ $booking['kategori_event'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
</body>                            
</html>