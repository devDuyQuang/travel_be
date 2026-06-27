@extends('index')

@section('title', 'Chi tiết Booking')

@section('content')
@php
  use App\Enums\BookingStatus;
  use App\Enums\PaymentStatus;
  use App\Enums\BookingType;
  use App\Enums\PricingMode;
  $currentStatus = $booking->booking_status instanceof BookingStatus ? $booking->booking_status->value : (string) $booking->booking_status;
  $currentPaymentStatus = $booking->payment_status instanceof PaymentStatus ? $booking->payment_status->value : (string) $booking->payment_status;
  $payment = $booking->payments->first();
  $paymentStatusValue = $payment?->status instanceof PaymentStatus ? $payment->status->value : ($payment?->status ? (string) $payment->status : $currentPaymentStatus);
  $bookingType = $booking->booking_type instanceof BookingType ? $booking->booking_type : BookingType::tryFrom((string) $booking->booking_type);
  $pricingMode = $booking->pricing_mode instanceof PricingMode ? $booking->pricing_mode : PricingMode::tryFrom((string) $booking->pricing_mode);
  $details = is_array($booking->booking_details) ? $booking->booking_details : [];
  $snapshot = is_array($booking->pricing_snapshot) ? $booking->pricing_snapshot : [];
@endphp

<main class="main-wrapper">
  <div class="main-content">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="mb-0">Booking {{ $booking->booking_code }}</h5>
      <a href="{{ panel_route('booking.index') }}" class="btn btn-outline-secondary">Quay lại</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header"><strong>Thông tin khách hàng</strong></div>
          <div class="card-body row g-3">
            <div class="col-md-4"><small class="text-muted">Họ tên</small><div>{{ $booking->customer_name }}</div></div>
            <div class="col-md-4"><small class="text-muted">Email</small><div>{{ $booking->customer_email }}</div></div>
            <div class="col-md-4"><small class="text-muted">Số điện thoại</small><div>{{ $booking->customer_phone }}</div></div>
            <div class="col-12"><small class="text-muted">Ghi chú khách hàng</small><div>{{ $booking->customer_note ?: '—' }}</div></div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Thông tin dịch vụ</strong></div>
          <div class="card-body row g-3">
            <div class="col-md-6"><small class="text-muted">Dịch vụ</small><div>{{ $booking->service_name_snapshot }}</div></div>
            <div class="col-md-6"><small class="text-muted">Danh mục</small><div>{{ $booking->serviceCategory?->name ?: '—' }}</div></div>
            <div class="col-md-3"><small class="text-muted">Loại booking</small><div>{{ $bookingType?->label() ?? '—' }}</div></div>
            <div class="col-md-3"><small class="text-muted">Ngày bắt đầu</small><div>{{ $booking->start_date?->format('d/m/Y') }}</div></div>
            <div class="col-md-3"><small class="text-muted">Ngày kết thúc</small><div>{{ $booking->end_date?->format('d/m/Y') ?: '—' }}</div></div>
            <div class="col-md-3"><small class="text-muted">Giờ</small><div>{{ $booking->start_time ?: '—' }}</div></div>
            @if(in_array($bookingType?->value, ['tour', 'attraction'], true))
              <div class="col-md-3"><small class="text-muted">Số khách</small><div>{{ $booking->adults }} người lớn, {{ $booking->children }} trẻ em</div></div>
            @elseif($bookingType?->value === 'transport')
              <div class="col-md-3"><small class="text-muted">Hành khách</small><div>{{ $details['passengers'] ?? $booking->quantity }}</div></div>
              <div class="col-md-6"><small class="text-muted">Điểm đón</small><div>{{ $details['pickup_location'] ?? '—' }}</div></div>
              <div class="col-md-6"><small class="text-muted">Điểm trả</small><div>{{ $details['dropoff_location'] ?? '—' }}</div></div>
              <div class="col-md-6"><small class="text-muted">Loại xe</small><div>{{ $details['vehicle_type'] ?? '—' }}</div></div>
            @elseif($bookingType?->value === 'hotel')
              <div class="col-md-3"><small class="text-muted">Số phòng</small><div>{{ $details['rooms'] ?? '—' }}</div></div>
              <div class="col-md-6"><small class="text-muted">Loại phòng</small><div>{{ $details['room_type'] ?? '—' }}</div></div>
              <div class="col-md-3"><small class="text-muted">Số khách</small><div>{{ $booking->adults }} người lớn, {{ $booking->children }} trẻ em</div></div>
            @else
              <div class="col-md-3"><small class="text-muted">Số lượng</small><div>{{ $booking->quantity }}</div></div>
            @endif
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Snapshot giá</strong></div>
          <div class="card-body">
            <table class="table mb-0">
              <tbody>
                <tr><td>Cách tính giá</td><td class="text-end">{{ $pricingMode?->label() ?? '—' }}</td></tr>
                @if($pricingMode === PricingMode::Quote)
                  <tr><th>Tổng tiền</th><th class="text-end">Cần báo giá</th></tr>
                @else
                  @if(isset($snapshot['adult_price']))
                    <tr><td>Giá người lớn</td><td class="text-end">{{ number_format((float) $snapshot['adult_price'], 0, ',', '.') }} ₫</td></tr>
                  @endif
                  @if(isset($snapshot['child_price']))
                    <tr><td>Giá trẻ em</td><td class="text-end">{{ number_format((float) $snapshot['child_price'], 0, ',', '.') }} ₫</td></tr>
                  @endif
                  <tr><td>Đơn giá</td><td class="text-end">{{ number_format((float) $booking->unit_price, 0, ',', '.') }} ₫</td></tr>
                  <tr><td>Số lượng tính tiền</td><td class="text-end">{{ $booking->quantity }}</td></tr>
                  <tr><td>Tạm tính</td><td class="text-end">{{ number_format((float) $booking->subtotal, 0, ',', '.') }} ₫</td></tr>
                  <tr><td>Giảm giá</td><td class="text-end">{{ number_format((float) $booking->discount_amount, 0, ',', '.') }} ₫</td></tr>
                  <tr><th>Tổng tiền</th><th class="text-end">{{ number_format((float) $booking->total_amount, 0, ',', '.') }} ₫</th></tr>
                @endif
              </tbody>
            </table>
            @if(!empty($details['extras']))
              <div class="mt-3">
                <strong>Dịch vụ bổ sung</strong>
                <ul class="mb-0 mt-2">
                  @foreach($details['extras'] as $extra)
                    <li>{{ $extra['name'] ?? 'Extra' }} - {{ number_format((float) ($extra['price'] ?? 0), 0, ',', '.') }} ₫ ({{ ($extra['pricing_type'] ?? '') === 'per_person' ? 'theo người' : 'theo booking' }})</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>

        <div class="card">
          <div class="card-header"><strong>Lịch sử trạng thái</strong></div>
          <div class="card-body">
            @forelse($booking->histories as $history)
              <div class="border-bottom pb-2 mb-2">
                <div>
                  <strong>{{ $bookingStatuses[$history->from_status] ?? 'Mới' }}</strong>
                  →
                  <strong>{{ $bookingStatuses[$history->to_status] ?? $history->to_status }}</strong>
                </div>
                <small class="text-muted">{{ $history->created_at?->format('d/m/Y H:i') }} bởi {{ $history->changer?->name ?: 'Hệ thống/Khách' }}</small>
                @if($history->note)<div>{{ $history->note }}</div>@endif
              </div>
            @empty
              <div class="text-muted">Chưa có lịch sử.</div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-header"><strong>Cập nhật trạng thái</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('booking.status', ['booking' => $booking->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <select name="status" class="form-select" required>
                <option value="">Chọn trạng thái</option>
                @foreach(BookingStatus::allowedTransitions($currentStatus) as $status)
                  <option value="{{ $status }}">{{ $bookingStatuses[$status] ?? $status }}</option>
                @endforeach
              </select>
              <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú thao tác"></textarea>
              <textarea name="cancel_reason" class="form-control" rows="2" placeholder="Lý do hủy nếu hủy booking"></textarea>
              <button class="btn btn-primary" type="submit" @disabled(count(BookingStatus::allowedTransitions($currentStatus)) === 0)>Cập nhật</button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Báo giá</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('booking.quote', ['booking' => $booking->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <input type="number" min="0" step="1000" name="total_amount" class="form-control" value="{{ old('total_amount', $pricingMode === PricingMode::Quote ? '' : $booking->total_amount) }}" placeholder="Tổng tiền báo giá">
              <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú báo giá"></textarea>
              <button class="btn btn-primary" type="submit">Lưu báo giá</button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Thanh toán</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('booking.payment', ['booking' => $booking->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <select name="payment_status" class="form-select" required>
                @foreach($paymentStatuses as $value => $label)
                  <option value="{{ $value }}" @selected($paymentStatusValue === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <select name="payment_method" class="form-select">
                <option value="">Chưa chọn phương thức</option>
                @foreach($paymentMethods as $value => $label)
                  <option value="{{ $value }}" @selected(($payment?->method ?? $booking->payment_method) === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <input type="text" name="transaction_code" class="form-control" value="{{ $payment?->transaction_code }}" placeholder="Mã giao dịch">
              <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú thanh toán"></textarea>
              <button class="btn btn-primary" type="submit">Cập nhật thanh toán</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><strong>Ghi chú nội bộ</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('booking.internal-note', ['booking' => $booking->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <textarea name="internal_note" class="form-control" rows="5">{{ old('internal_note', $booking->internal_note) }}</textarea>
              <button class="btn btn-primary" type="submit">Lưu ghi chú</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
