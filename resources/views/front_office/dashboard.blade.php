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

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2>Booking List</h2>
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
                    <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
                </select>
            </form>

            <!-- Search Form -->
            <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline mb-3" style="margin: 8px">
                <input type="text" name="search" class="form-control" placeholder="Search by Event Name or Kode Booking"
                    value="{{ request('search') }}" onkeyup="this.form.submit()">
            </form>
        </div>

        <!-- Booking Table -->
        <div class="table-responsive">
            <table class="table custom-table">
                <thead class="table-header">
                    <tr>
                        <th class="d-none">Kode Booking</th>
                        <th class="nama-event-column">Nama Event</th>
                        <th>Nama Organisasi</th>
                        <th>Ruangan dan Waktu</th>
                        <th>Nama PIC</th>
                        <th>User Checkin</th>
                        <th class="status-column">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                                        <tr class="table-row">
                                            <td class="d-none">{{ $booking->kode_booking }}</td>
                                            <td>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#eventModal{{ $booking->id }}"
                                                    class="fw-bold" style="color: #091F5B;">
                                                    {{ $booking->nama_event }}
                                                </a>
                                            </td>
                                            <td class="fw-semibold">{{ $booking->nama_organisasi }}</td>
                                            <td>
                                                <strong>{{ $booking->nama_ruangan }}</strong> <br>
                                                Lantai {{ $booking->lantai }}<br>
                                                @php
                                                    $timeRange = explode(' - ', $booking->waktu);
                                                    if (count($timeRange) === 2) {
                                                        $startTime = \Carbon\Carbon::parse($timeRange[0])->format('H:i');
                                                        $endTime = \Carbon\Carbon::parse($timeRange[1])->format('H:i');
                                                        echo $startTime . ' - ' . $endTime;
                                                    } else {
                                                        echo \Carbon\Carbon::parse($booking->waktu)->format('H:i');
                                                    }
                                                @endphp
                                            </td>
                                            <td>{{ $booking->nama_pic }}</td>
                                            <td>
                                                @foreach($booking->absen as $absen)
                                                    <p>{{ $absen->name }} <br> {{ $absen->phone }}</p>
                                                @endforeach
                                            </td>
                                            <td>
                                            @if($booking->absen->isNotEmpty())
    <span id="status-badge-{{ $booking->id }}" class="badge status-badge-{{ $booking->absen->last()->status }}">
        {{ $booking->absen->last()->status }}
    </span>
    @if($booking->absen->last()->status == 'Booked')
        <button id="status-btn-{{ $booking->id }}" class="btn btn-success btn-sm ms-2"
            onclick="updateStatus({{ $booking->id }}, 'Check-in')">
            <i class="fas fa-check"></i>
        </button>
    @elseif($booking->absen->last()->status == 'Check-in')
        <button id="status-btn-{{ $booking->id }}" class="btn btn-danger btn-sm ms-2"
            onclick="updateStatus({{ $booking->id }}, 'Check-out')">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    @endif
@else
    <span class="badge bg-success">Booked</span>
    <button id="status-btn-{{ $booking->id }}" class="btn btn-success btn-sm ms-2"
        onclick="updateStatus({{ $booking->id }}, 'Check-in')">
        <i class="fas fa-check"></i>
    </button>
@endif

                                            </td>
                                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Dropdown untuk memilih jumlah data per halaman -->
            <div>
                <select id="per-page" class="form-select" onchange="updatePerPage()">
                    <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination mb-0">
                    <!-- Page Number Links Only -->
                    @for ($i = 1; $i <= $bookings->lastPage(); $i++)
                        <li class="page-item {{ $i == $bookings->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $bookings->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </nav>


        </div>

        <!-- Modal for Event Details -->
        @foreach($bookings as $booking)
            <div class="modal fade" id="eventModal{{ $booking->id }}" tabindex="-1"
                aria-labelledby="eventModalLabel{{ $booking->id }}" aria-hidden="true">
                <!-- Mengatur ukuran modal agar lebih kecil -->
                <div class="modal-dialog" style="max-width: 600px;"> <!-- Menyesuaikan ukuran -->
                    <div class="modal-content p-0 rounded-3">
                        <div class="modal-header"
                            style="border: none; padding-bottom: 0px; display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="modal-title w-100 text-center" id="eventModalLabel{{ $booking->id }}"
                                style="color: #091F5B; font-weight: 400;">
                                Detail Acara
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body" style="padding-top: 0px;">
                            <!-- Nama Acara dengan garis bawah biru tebal -->
                            <div class="text-center mb-2"
                                style="border-bottom: 3px solid #091F5B; padding-bottom: 5px; justify-content-center">
                                <div class="" style="font-size: 1.5rem;"> <!-- Ukuran font lebih besar -->
                                    {{ $booking->nama_event }}
                                </div>
                            </div>
                            <!-- Isi Detail Acara -->
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nama PIC:</strong></p>
                                    <p>{{ $booking->nama_pic }}</p>

                                    <p><strong>Kategori Ekraf:</strong></p>
                                    <p>{{ $booking->kategori_ekraf }}</p>

                                    <p><strong>Jumlah Peserta:</strong></p>
                                    <p>{{ $booking->jumlah_peserta }} Orang</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>No Telp:</strong></p>
                                    <p>{{ $booking->no_pic }}</p>

                                    <p><strong>Kategori Event:</strong></p>
                                    <p>{{ $booking->kategori_event }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach



        <!-- JavaScript for Updating Booking Status -->
        <script>
            // Make sure this is defined before it is used in the HTML
            function updateStatus(bookingId, newStatus) {
    fetch(`/bookings/${bookingId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);  // Log data untuk debugging
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();  // Reload untuk menampilkan status terbaru
            });
        } else {
            Swal.fire('Error', 'Failed to update status', 'error');
        }
    })
    .catch(error => Swal.fire('Error', 'An error occurred while updating the status.', 'error'));
}


            $(document).ready(function () {
                // Now you can safely use the updateStatus function after it's been defined
            });
            function updatePerPage() {
                var perPage = document.getElementById('per-page').value;
                window.location.href = '?per_page=' + perPage; // Mengarahkan kembali dengan query string per_page
            }

        </script>


</body>

</html>