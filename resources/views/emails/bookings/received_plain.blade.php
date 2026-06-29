Booking đã được tiếp nhận

Cảm ơn {{ $booking->customer_name }} đã gửi yêu cầu booking tới Golfnity.

Mã booking: {{ $booking->booking_code }}
Dịch vụ: {{ $booking->service_name_snapshot }}
Ngày sử dụng: {{ optional($booking->start_date)->format('d/m/Y') }}
@if($booking->end_date)
Ngày kết thúc: {{ optional($booking->end_date)->format('d/m/Y') }}
@endif
Trạng thái: Đang chờ xác nhận
@php
  $pricingMode = $booking->pricing_mode instanceof \App\Enums\PricingMode ? $booking->pricing_mode->value : (string) $booking->pricing_mode;
@endphp
Tổng tạm tính: {{ ($pricingMode === 'quote' || (float) $booking->total_amount <= 0) ? 'Cần báo giá' : number_format((float) $booking->total_amount, 0, ',', '.') . ' ' . $booking->currency }}
Email đăng nhập: {{ $booking->customer_email }}

Lưu ý: booking này chưa được xác nhận chính thức và chưa đồng nghĩa với việc đã giữ chỗ.

@if($accountSetupUrl)
Một tài khoản quản lý booking đã được tạo bằng địa chỉ email này.
Vui lòng thiết lập mật khẩu để đăng nhập và theo dõi booking:
{{ $accountSetupUrl }}
@else
Bạn có thể dùng email này để theo dõi booking khi cổng khách hàng được kích hoạt.
{{ $bookingUrl }}
@endif

-- {{ $appName ?? config('app.name') }}
