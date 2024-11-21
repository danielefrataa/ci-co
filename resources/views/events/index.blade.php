@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Booking List</h2>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table custom-table">
            <theead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Event</th>
                    <th>Nama Organisasi</th>
                    <th>Ruangan dan Waktu</th>
                    <th>Nama PIC</th>
                    <th>User Checkin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking['kode_booking'] }}</td>
                        <td>{{ $booking['nama_event'] }}</td>
                        <td>{{ $booking['nama_organisasi'] }}</td>
                        <td>
                            {{ $booking['nama_ruangan'] }} (Lantai {{ $booking['lantai'] }})<br>
                        </td>
                        <td>{{ $booking['nama_pic'] }}</td>
                        <td>
                           
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $booking['status'] ?? 'Booked' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data booking.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
