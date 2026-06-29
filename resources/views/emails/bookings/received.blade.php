@php
  $statusText = 'Đang chờ xác nhận';
  $pricingMode = $booking->pricing_mode instanceof \App\Enums\PricingMode ? $booking->pricing_mode->value : (string) $booking->pricing_mode;
  $isQuote = $pricingMode === 'quote' || (float) $booking->total_amount <= 0;
  $totalText = $isQuote ? 'Cần báo giá' : number_format((float) $booking->total_amount, 0, ',', '.') . ' ' . $booking->currency;
  $body = '
    <p>Cảm ơn '.e($booking->customer_name).' đã gửi yêu cầu booking tới Golfnity.</p>
    <p>Golfnity đã tiếp nhận thông tin và đội ngũ tư vấn sẽ kiểm tra để liên hệ xác nhận trong thời gian sớm nhất.</p>
    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;margin:16px 0;">
      <tr><td style="padding:6px 0;color:#6b7280;">Mã booking</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->booking_code).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Dịch vụ</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->service_name_snapshot).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Ngày sử dụng</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e(optional($booking->start_date)->format('d/m/Y')).'</td></tr>'.
      ($booking->end_date ? '<tr><td style="padding:6px 0;color:#6b7280;">Ngày kết thúc</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e(optional($booking->end_date)->format('d/m/Y')).'</td></tr>' : '').
      '<tr><td style="padding:6px 0;color:#6b7280;">Trạng thái</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($statusText).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Tổng tạm tính</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($totalText).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Email đăng nhập</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->customer_email).'</td></tr>
    </table>
    <p>Lưu ý: booking này chưa được xác nhận chính thức và chưa đồng nghĩa với việc đã giữ chỗ.</p>'.
    ($accountSetupUrl
      ? '<p>Một tài khoản quản lý booking đã được tạo bằng địa chỉ email này. Vui lòng thiết lập mật khẩu để đăng nhập và theo dõi booking.</p>'
      : '<p>Bạn có thể dùng email này để theo dõi booking khi cổng khách hàng được kích hoạt.</p>');
@endphp

@include('emails.layouts.message', [
  'titleH1' => 'Booking đã được tiếp nhận',
  'greetingName' => $booking->customer_name,
  'buttonText' => $accountSetupUrl ? 'Thiết lập mật khẩu' : 'Về trang chủ',
  'buttonUrl' => $accountSetupUrl ?: $bookingUrl,
  'appName' => $appName ?? config('app.name'),
  'slot' => $body,
])
