<!-- resources/views/booking/api_details.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
</head>
<body>
    <h1>Booking Details</h1>

    @forelse ($data as $booking)
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px;">
            <p><strong>ID Booking:</strong> {{ $booking['id'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Nama Pengguna:</strong> {{ $booking['user_name'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Event:</strong> {{ $booking['name'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Kategori Event:</strong> {{ $booking['kategori_event'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Jumlah Partisipan:</strong> {{ $booking['participant'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Nama PIC:</strong> {{ $booking['pic_name'] ?? 'Tidak tersedia' }}</p>
            <p><strong>Status:</strong> {{ $booking['status'] ?? 'Tidak tersedia' }}</p>

            <h3>Booking Items</h3>
            <ul>
                @forelse ($booking['booking_items'] ?? [] as $item)
                    <li>Tanggal: {{ $item['booking_date'] ?? 'Tidak tersedia' }}, Jam: {{ $item['booking_hour'] ?? 'Tidak tersedia' }}</li>
                @empty
                    <li>Data booking items tidak tersedia</li>
                @endforelse
            </ul>

            <h3>Ruangan</h3>
            <ul>
                @forelse ($booking['ruangans'] ?? [] as $ruangan)
                    <li>{{ $ruangan['name'] ?? 'Nama ruangan tidak tersedia' }} - Kapasitas: {{ $ruangan['capacity'] ?? 'Tidak tersedia' }}</li>
                @empty
                    <li>Data ruangan tidak tersedia</li>
                @endforelse
            </ul>

            <h3>History</h3>
            <ul>
                @forelse ($booking['history'] ?? [] as $history)
                    <li>Status: {{ $history['status'] ?? 'Tidak tersedia' }}, PIC Marketing: {{ $history['pic_marketing'] ?? 'Tidak tersedia' }}</li>
                @empty
                    <li>Data history tidak tersedia</li>
                @endforelse
            </ul>
        </div>
    @empty
        <p>Tidak ada data booking tersedia.</p>
    @endforelse
</body>
</html>
