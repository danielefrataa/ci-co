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
                    value="{{ old('search', request('search')) }}" onkeyup="this.form.submit()">
            </form>


        </div>

        <!-- Booking Table -->
        <div class="table-responsive">
            <table class="table custom-table">
                <thead class="table-header">
                    <tr>
                        <th class="d-none">Kode Booking</th>
                        <th class="nama-event-column">Nama Event</th>
                        <th >Nama Organisasi</th>
                        <th>Ruangan dan Waktu</th>
                        <th>Nama PIC</th>
                        <th>User Check-in</th>
                        <th class="status-column">Status</th>
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
                                        <span>{{ $booking['start_time'] ?? 'N/A' }} - {{ $booking['end_time'] ?? 'N/A' }} </span>
                                    </p>
                                @endforeach
                            </td>
                            <td>{{ $booking['pic_name'] }}</td>
                            <td>
                                <!-- Check-in Badge (Jika diperlukan tetap tampilkan di kolom ini) -->
                                @if (!empty($booking['absen']))
                                    <span id="status-badge-{{ $booking['id'] }}"
                                        class="badge status-badge-{{ last($booking['absen'])['status'] }}">
                                        {{ last($booking['absen'])['status'] }}
                                    </span>
                                @else
                                    <span class="badge bg-warning">Belum Check-in</span>
                                @endif
                            </td>
                            <td>
                                <!-- Status Column -->
                                @if (!empty($booking['absen']))
                                    <span class="badge bg-success">{{ last($booking['absen'])['status'] }}</span>
                                @else
                                    <span class="badge bg-success">Booked</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                            <a class="page-link" href="{{ url()->current() }}?page={{ $page }}&per_page={{ $perPage }}">
                                {{ $page }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </nav>


            <!-- Modal for Event Details -->
            @foreach($bookings as $booking)
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


            <!-- JavaScript for Updating Booking Status -->
            <script>
                function updateStatus(bookingId, newStatus) {
                    fetch(`/bookings/${bookingId}/update-status`, {
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
                    window.location.href = `{{ url()->current() }}?per_page=${perPage}`;
                }
            </script>
        </div>
</body>

</html>