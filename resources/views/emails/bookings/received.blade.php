@php
  $statusText = 'Đang chờ xác nhận';
  $bookingType = $booking->booking_type instanceof \App\Enums\BookingType ? $booking->booking_type->value : (string) $booking->booking_type;
  $isHotel = $bookingType === 'hotel';
  $isTeeTime = $bookingType === 'tee_time';
  $titleText = $isHotel ? 'Yêu cầu đặt phòng đã được tiếp nhận' : ($isTeeTime ? 'Yêu cầu đặt tee time đã được tiếp nhận' : 'Yêu cầu booking đã được tiếp nhận');
  $startLabel = $isHotel ? 'Ngày nhận phòng' : ($isTeeTime ? 'Ngày chơi' : 'Ngày booking');
  $endLabel = $isHotel ? 'Ngày trả phòng' : 'Ngày kết thúc';
  $pricingMode = $booking->pricing_mode instanceof \App\Enums\PricingMode ? $booking->pricing_mode->value : (string) $booking->pricing_mode;
  $isQuote = $pricingMode === 'quote' || (float) $booking->total_amount <= 0;
  $totalText = $isQuote ? 'Sẽ được tư vấn' : number_format((float) $booking->total_amount, 0, ',', '.') . ' ' . $booking->currency;
  $details = is_array($booking->booking_details) ? $booking->booking_details : [];
  $paymentLabels = [
      'cash' => 'Thanh toán tại nơi sử dụng dịch vụ',
      'bank_transfer' => 'Chuyển khoản sau khi xác nhận',
  ];
  $paymentText = $paymentLabels[$booking->payment_method] ?? ($booking->payment_method ? $booking->payment_method : 'Chưa chọn');
  $optionRows = '';
  if (! empty($details['option_name'])) {
      $optionLabel = $isTeeTime ? 'Gói tee time' : 'Tùy chọn dịch vụ';
      $optionRows .= '<tr><td style="padding:6px 0;color:#6b7280;">'.e($optionLabel).'</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($details['option_name']).'</td></tr>';
      $unitText = ! empty($details['unit']) ? ' / '.e($details['unit']) : '';
      $unitPriceText = isset($details['unit_price']) && (float) $details['unit_price'] > 0
          ? number_format((float) $details['unit_price'], 0, ',', '.') . ' ' . e($booking->currency) . $unitText
          : 'Sẽ được tư vấn';
      $optionRows .= '<tr><td style="padding:6px 0;color:#6b7280;">Đơn giá</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.$unitPriceText.'</td></tr>';
      if ($isTeeTime && ! empty($details['golfers'])) {
          $optionRows .= '<tr><td style="padding:6px 0;color:#6b7280;">Số golfer</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($details['golfers']).' golfer</td></tr>';
      } elseif (! empty($details['quantity_basis'])) {
          $optionRows .= '<tr><td style="padding:6px 0;color:#6b7280;">Số lượng tính</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($details['quantity_basis']).'</td></tr>';
      }
  }
  $body = '
    <p>Cảm ơn '.e($booking->customer_name).' đã gửi yêu cầu tới Golfnity.</p>
    <p>Chúng tôi sẽ kiểm tra tình trạng dịch vụ và phản hồi xác nhận trong thời gian sớm nhất.</p>
    <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;margin:16px 0;">
      <tr><td style="padding:6px 0;color:#6b7280;">Mã booking</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->booking_code).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Dịch vụ</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->service_name_snapshot).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">'.e($startLabel).'</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e(optional($booking->start_date)->format('d/m/Y')).'</td></tr>'.
      ($booking->end_date ? '<tr><td style="padding:6px 0;color:#6b7280;">'.e($endLabel).'</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e(optional($booking->end_date)->format('d/m/Y')).'</td></tr>' : '').
      $optionRows.
      '<tr><td style="padding:6px 0;color:#6b7280;">Phương thức thanh toán</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($paymentText).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Trạng thái</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($statusText).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Giá dự kiến</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($totalText).'</td></tr>
      <tr><td style="padding:6px 0;color:#6b7280;">Email đăng nhập</td><td style="padding:6px 0;text-align:right;font-weight:600;color:#111827;">'.e($booking->customer_email).'</td></tr>
    </table>
    <p>Lưu ý: yêu cầu này chưa được xác nhận chính thức'.($isHotel ? ' và chưa đồng nghĩa với việc đã giữ phòng.' : ' cho đến khi chúng tôi kiểm tra tình trạng dịch vụ.').'</p>'.
    ($accountSetupUrl
      ? '<p>Một tài khoản quản lý booking đã được tạo bằng địa chỉ email này. Vui lòng thiết lập mật khẩu để đăng nhập và theo dõi booking.</p>'
      : '<p>Bạn có thể dùng email này để theo dõi booking khi cổng khách hàng được kích hoạt.</p>');
@endphp

@include('emails.layouts.message', [
  'titleH1' => $titleText,
  'greetingName' => $booking->customer_name,
  'buttonText' => $accountSetupUrl ? 'Thiết lập mật khẩu' : 'Về trang chủ',
  'buttonUrl' => $accountSetupUrl ?: $bookingUrl,
  'appName' => $appName ?? config('app.name'),
  'slot' => $body,
])
