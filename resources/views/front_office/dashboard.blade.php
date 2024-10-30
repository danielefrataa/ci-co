<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Booking List</title>
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Booking List</h2>
            <button class="btn btn-outline-dark">Logout</button>
        </div>
    
        <div class="card">
            <div class="card-body">
                <!-- Filter and Search Section -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <select class="form-select" aria-label="Status Filter" style="width: 200px;">
                            <option selected>Semua Status</option>
                            <option value="Check-in">Check-in</option>
                            <option value="Booked">Booked</option>
                            <option value="Check-out">Check-out</option>
                        </select>
                    </div>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search by name or code" aria-label="Search">
                        <button class="btn btn-primary" type="button">Search</button>
                    </div>
                </div>
    
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
                                <td>{{ $booking->nama_event }}</td>
                                <td>{{ $booking->ruangan }} <br> {{ $booking->waktu }}</td>
                                <td>{{ $booking->user_name }}</td>
                                <td>
                                    <span class="badge {{ $booking->status == 'Check-in' ? 'bg-success' : ($booking->status == 'Booked' ? 'bg-secondary' : 'bg-danger') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

