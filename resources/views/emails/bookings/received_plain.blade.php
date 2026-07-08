@php
  $bookingType = $booking->booking_type instanceof \App\Enums\BookingType ? $booking->booking_type->value : (string) $booking->booking_type;
  $isHotel = $bookingType === 'hotel';
  $pricingMode = $booking->pricing_mode instanceof \App\Enums\PricingMode ? $booking->pricing_mode->value : (string) $booking->pricing_mode;
  $details = is_array($booking->booking_details) ? $booking->booking_details : [];
@endphp
{{ $isHotel ? 'Yêu cầu đặt phòng đã được tiếp nhận' : 'Yêu cầu booking đã được tiếp nhận' }}

Cảm ơn {{ $booking->customer_name }} đã gửi yêu cầu tới Golfnity.
Chúng tôi sẽ kiểm tra tình trạng dịch vụ và phản hồi xác nhận trong thời gian sớm nhất.

Mã booking: {{ $booking->booking_code }}
Dịch vụ: {{ $booking->service_name_snapshot }}
{{ $isHotel ? 'Ngày nhận phòng' : 'Ngày booking' }}: {{ optional($booking->start_date)->format('d/m/Y') }}
@if($booking->end_date)
{{ $isHotel ? 'Ngày trả phòng' : 'Ngày kết thúc' }}: {{ optional($booking->end_date)->format('d/m/Y') }}
@endif
@if(!empty($details['option_name']))
Tùy chọn dịch vụ: {{ $details['option_name'] }}
Đơn giá: {{ isset($details['unit_price']) && (float) $details['unit_price'] > 0 ? number_format((float) $details['unit_price'], 0, ',', '.') . ' ' . $booking->currency . (!empty($details['unit']) ? ' / '.$details['unit'] : '') : 'Sẽ được tư vấn' }}
@if(!empty($details['quantity_basis']))
Số lượng tính: {{ $details['quantity_basis'] }}
@endif
@endif
Trạng thái: Đang chờ xác nhận
Giá dự kiến: {{ ($pricingMode === 'quote' || (float) $booking->total_amount <= 0) ? 'Sẽ được tư vấn' : number_format((float) $booking->total_amount, 0, ',', '.') . ' ' . $booking->currency }}
Email đăng nhập: {{ $booking->customer_email }}

Lưu ý: yêu cầu này chưa được xác nhận chính thức{{ $isHotel ? ' và chưa đồng nghĩa với việc đã giữ phòng.' : ' cho đến khi chúng tôi kiểm tra tình trạng dịch vụ.' }}

@if($accountSetupUrl)
Một tài khoản quản lý booking đã được tạo bằng địa chỉ email này.
Vui lòng thiết lập mật khẩu để đăng nhập và theo dõi booking:
{{ $accountSetupUrl }}
@else
Bạn có thể dùng email này để theo dõi booking khi cổng khách hàng được kích hoạt.
{{ $bookingUrl }}
@endif

-- {{ $appName ?? config('app.name') }}
