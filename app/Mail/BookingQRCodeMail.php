<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingQRCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingCode;
    public $qrCode;

    /**
     * Create a new message instance.
     */
    public function __construct($bookingCode, $qrCode)
    {
        $this->bookingCode = $bookingCode;
        $this->qrCode = $qrCode;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Booking QR Code')
            ->view('emails.qrcode')
            ->with([
                'bookingCode' => $this->bookingCode,
                'qrCode' => $this->qrCode, // Kirim sebagai variabel untuk ditampilkan di email
            ]);
    }
}
