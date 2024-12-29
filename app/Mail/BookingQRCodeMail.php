<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingQRCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $qrCode;

    /**
     * Create a new message instance.
     *
     * @param string $qrCode
     */
    public function __construct($qrCode)
    {
        $this->qrCode = $qrCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.booking_qrcode')
                    ->subject('QR Code for Your Booking')
                    ->with(['qrCode' => $this->qrCode]);
    }
}
