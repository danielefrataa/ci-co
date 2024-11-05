<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Booking List</title>
    <style>
        .custom-blue-table {
            background-color: #091F5B; /* MCC blue color */
            color: white;
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="https://event.mcc.or.id/assets/images/logo.png" width="200" alt="MCC Logo">
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <h2>Booking List</h2>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline mb-3" style="margin: 8px">
            <select name="status" class="form-select w-auto" aria-label="Status Filter" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Check-in" {{ request('status') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                <option value="Booked" {{ request('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
            </select>
        </form>

        <!-- Booking Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="custom-blue-table">
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Event</th>
                        <th>Ruangan dan Waktu</th>
                        <th>Nama</th>
                        <th>Nama PIC</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->kode_booking }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#eventModal{{ $booking->id }}">
                                    {{ $booking->nama_event }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $booking->nama_ruangan }}</strong> <br> Lantai {{ $booking->lantai }}
                                <br>
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
                            <td>{{ $booking->user_name }}</td>
                            <td>{{ $booking->nama_pic }} <br> {{ $booking->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status == 'Check-in' ? 'success' : ($booking->status == 'Booked' ? 'secondary' : 'danger') }}">
                                    {{ $booking->status }}
                                </span>
                                @if($booking->status == 'Booked')
                                    <button class="btn btn-success btn-sm ms-2" onclick="updateStatus({{ $booking->id }}, 'Check-in')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @elseif($booking->status == 'Check-in')
                                    <button class="btn btn-danger btn-sm ms-2" onclick="updateStatus({{ $booking->id }}, 'Check-out')">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal for Booking Details -->
                        <div class="modal fade" id="eventModal{{ $booking->id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content p-4 rounded-3">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title w-100 text-center fw-bold" id="eventModalLabel{{ $booking->id }}">
                                            Detail Acara
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-3 fw-semibold">{{ $booking->nama_event }}</div>
                                        <hr class="my-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Nama PIC:</strong> {{ $booking->nama_pic }}</p>
                                                <p><strong>Kategori Ekraf:</strong> {{ $booking->kategori_ekraf }}</p>
                                                <p><strong>Jumlah Peserta:</strong> {{ $booking->jumlah_peserta }} Orang</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>No Telp:</strong> {{ $booking->no_telp }}</p>
                                                <p><strong>Kategori Event:</strong> {{ $booking->kategori_event }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <table class="table">
        <!-- Your table structure for displaying bookings -->
    </table>
    
    <!-- Custom Pagination -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <!-- Previous Button -->
            <li class="page-item {{ $bookings->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $bookings->previousPageUrl() }}" aria-disabled="{{ $bookings->onFirstPage() }}">Previous</a>
            </li>
    
            <!-- Page Number Links -->
            @for ($i = 1; $i <= $bookings->lastPage(); $i++)
                <li class="page-item {{ $i == $bookings->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $bookings->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
    
            <!-- Next Button -->
            <li class="page-item {{ $bookings->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $bookings->nextPageUrl() }}" aria-disabled="{{ !$bookings->hasMorePages() }}">Next</a>
            </li>
        </ul>
    </nav>
    

    <!-- JavaScript for Updating Booking Status -->
    <script>
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
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Failed to update status', 'error');
                }
            })
            .catch(error => Swal.fire('Error', 'An error occurred while updating the status.', 'error'));
        }
    </script>
</body>
</html>
