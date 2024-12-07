<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateQRCode($bookingCode)
    {
        // Generate QR Code
        return QrCode::size(150)->generate($bookingCode);
    }
}




// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use App\Mail\BookingQRCodeMail;

// class QRCodeController extends Controller
// {
//     public function sendQRCode($bookingCode)
//     {
//         // Generate QR Code as SVG
//         $qrCode = QrCode::format('svg')->size(150)->generate($bookingCode);

//         // Send email with QR Code
//         Mail::to('dewintaistiqomah@gmail.com')->send(new BookingQRCodeMail($bookingCode, $qrCode));

//         return response()->json(['message' => 'QR Code sent to email.']);
//     }
// }
