<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <title>Booking List</title>
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
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Booking List</h2>
            <button class="btn btn-outline-dark">Logout</button>
        </div>
    
        <form method="GET" action="{{ route('front_office.dashboard') }}" class="d-inline">
            <select name="status" class="form-select" aria-label="Status Filter" style="width: 200px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Check-in" {{ request('status') == 'Check-in' ? 'selected' : '' }}>Check-in</option>
                <option value="Booked" {{ request('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                <option value="Check-out" {{ request('status') == 'Check-out' ? 'selected' : '' }}>Check-out</option>
            </select>
        </form>
        
    
                <!-- Booking List Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Nama Event</th>
                                <th>Ruangan dan Waktu</th>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->kode_booking }}</td>
                                    <td>
                                        <!-- Link to open modal -->
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#eventModal{{ $booking->id }}">
                                            {{ $booking->nama_event }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->ruangan }} <br> {{ $booking->waktu }}</td>
                                    <td>{{ $booking->user_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status == 'Check-in' ? 'success' : ($booking->status == 'Booked' ? 'secondary' : 'danger') }}">
                                            {{ $booking->status }}
                                        </span>
                                        
                                        @if($booking->status == 'Check-in')
                                            <button class="btn btn-sm btn-danger ms-2" onclick="updateStatus({{ $booking->id }}, 'Check-out')">Check-out</button>
                                        @endif
                                    </td>
                                </tr>
                    
                                <!-- Modal -->
                                <div class="modal fade" id="eventModal{{ $booking->id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ $booking->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="eventModalLabel{{ $booking->id }}">{{ $booking->nama_event }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display detailed information about the event -->
                                                <p><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</p>
                                                <p><strong>Ruangan:</strong> {{ $booking->ruangan }}</p>
                                                <p><strong>Waktu:</strong> {{ $booking->waktu }}</p>
                                                <p><strong>Nama:</strong> {{ $booking->user_name }}</p>
                                                <p><strong>Status:</strong> {{ $booking->status }}</p>
                                                <!-- Add more details here as needed -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

    <!-- Your existing HTML and Blade code -->

<!-- JavaScript function for updating booking status -->
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
                alert(data.message);  // Display success message
                location.reload();    // Refresh page to show updated status
            } else {
                alert('Failed to update status');  // Display error message
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</body>