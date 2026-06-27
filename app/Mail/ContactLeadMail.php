<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $fullName,
        public string $phone,
        public string $note,
        public string $utmSource = '',
        public string $utmMedium = '',
        public string $utmCampaign = '',
        public string $landingUrl = '',
        public string $ip = '',
        public string $buttonText = 'Vào trang chủ',
        public string $buttonUrl = '',
        public string $appName = ''
    ) {}

    public function build()
    {
        return $this->subject('Yêu cầu tư vấn Golfnity mới')
            ->view('emails.lead')          // HTML
            ->text('emails.lead_plain')    // Plain text fallback
            ->with([
                'titleH1'     => 'Chào',
                'recipient'   => 'CSKH',
                'fullName'    => $this->fullName,
                'phone'       => $this->phone,
                'note'        => $this->note,
                'utmSource'   => $this->utmSource,
                'utmMedium'   => $this->utmMedium,
                'utmCampaign' => $this->utmCampaign,
                'landingUrl'  => $this->landingUrl,
                'ip'          => $this->ip,
                'buttonText'  => $this->buttonText,
                'buttonUrl'   => $this->buttonUrl,
                'appName'     => $this->appName,
            ]);
    }
}
