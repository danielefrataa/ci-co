<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            text-align: center;
        }
        .qr-container {
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>QR Code for Booking Code</h1>
    <p>Berikut adalah QR Code untuk booking Anda:</p>
    <div class="qr-container">
        {!! $qrCode !!}
    </div>
    <p>Silakan tunjukkan QR Code ini saat check-in.</p>
    <p>Terima kasih telah menggunakan layanan kami!</p>
</body>
</html>
