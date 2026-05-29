@php
  $body = '
    <strong>LEAD TƯ VẤN Y TẾ MỚI</strong><br><br>
    - Họ và tên : '.e($fullName).'<br>
    - Điện thoại: '.e($phone).'<br>
    - Nội dung  : '.nl2br(e($note)).'<br><br>
    <!-- Thông tin phụ trợ:<br>
    - UTM source   : '.e($utmSource).'<br>
    - UTM medium   : '.e($utmMedium).'<br>
    - UTM campaign : '.e($utmCampaign).'<br>
    - Landing URL  : '.e($landingUrl).'<br>
    - IP           : '.e($ip).'<br> -->
  ';
@endphp

@include('emails.layouts.message', [
  'titleH1'      => $titleH1 ?? 'Chào',
  'greetingName' => $recipient ?? 'CSKH',
  'buttonText'   => $buttonText ?? 'Vào trang chủ',
  'buttonUrl'    => $buttonUrl ?? config('app.url'),
  'appName'      => $appName ?? config('app.name'),
  'slot'         => $body . '<br>Vui lòng liên hệ bệnh nhân trong giờ làm việc.'
])
