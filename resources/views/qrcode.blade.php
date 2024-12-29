<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
</head>
<body>
    <h1>QR Code for Booking Code</h1>
    <div>
        {!! $qrCode !!}
    </div>

    <!-- Form Input Email -->
    <form id="emailForm" action="{{ route('send.qrcode.email') }}" method="POST">
        @csrf
        <label for="email">Masukkan Email Tujuan:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Kirim</button>
    </form>

    <!-- Pesan Berhasil -->
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif
</body>
</html>
