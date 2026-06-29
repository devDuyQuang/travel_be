<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public ?string $accountSetupUrl = null,
        public ?string $bookingUrl = null,
        public string $appName = '',
    ) {}

    public function build()
    {
        return $this->subject('Golfnity đã tiếp nhận booking '.$this->booking->booking_code)
            ->view('emails.bookings.received')
            ->text('emails.bookings.received_plain')
            ->with([
                'booking' => $this->booking,
                'accountSetupUrl' => $this->accountSetupUrl,
                'bookingUrl' => $this->bookingUrl ?: rtrim((string) config('services.frontend.url', config('app.url')), '/'),
                'appName' => $this->appName ?: config('app.name'),
            ]);
    }
}
