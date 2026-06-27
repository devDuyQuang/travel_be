@extends('index')

@section('title', 'Chi tiết Đơn hàng')

@section('content')
@php
  use App\Enums\OrderStatus;
  use App\Enums\PaymentStatus;
  $currentStatus = $order->order_status instanceof OrderStatus ? $order->order_status->value : (string) $order->order_status;
  $currentPaymentStatus = $order->payment_status instanceof PaymentStatus ? $order->payment_status->value : (string) $order->payment_status;
  $payment = $order->payments->first();
  $paymentStatusValue = $payment?->status instanceof PaymentStatus ? $payment->status->value : ($payment?->status ? (string) $payment->status : $currentPaymentStatus);
@endphp

<main class="main-wrapper">
  <div class="main-content">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="mb-0">Đơn hàng {{ $order->order_code }}</h5>
      <a href="{{ panel_route('order.index') }}" class="btn btn-outline-secondary">Quay lại</a>
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
          <div class="card-header"><strong>Khách hàng và giao hàng</strong></div>
          <div class="card-body row g-3">
            <div class="col-md-4"><small class="text-muted">Họ tên</small><div>{{ $order->customer_name }}</div></div>
            <div class="col-md-4"><small class="text-muted">Email</small><div>{{ $order->customer_email }}</div></div>
            <div class="col-md-4"><small class="text-muted">Số điện thoại</small><div>{{ $order->customer_phone }}</div></div>
            <div class="col-md-8"><small class="text-muted">Địa chỉ</small><div>{{ $order->shipping_address_line }}</div></div>
            <div class="col-md-4"><small class="text-muted">Tỉnh/thành</small><div>{{ $order->shipping_province }}</div></div>
            <div class="col-md-4"><small class="text-muted">Phường/xã</small><div>{{ $order->shipping_ward ?: '—' }}</div></div>
            <div class="col-md-4"><small class="text-muted">Quận/huyện</small><div>{{ $order->shipping_district ?: '—' }}</div></div>
            <div class="col-12"><small class="text-muted">Ghi chú khách hàng</small><div>{{ $order->customer_note ?: '—' }}</div></div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Sản phẩm</strong></div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Sản phẩm</th>
                  <th>SKU</th>
                  <th class="text-end">Đơn giá</th>
                  <th class="text-end">SL</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td>{{ $item->product_name_snapshot }}</td>
                    <td>{{ $item->sku_snapshot ?: '—' }}</td>
                    <td class="text-end">{{ number_format((float) $item->unit_price, 0, ',', '.') }} ₫</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format((float) $item->line_total, 0, ',', '.') }} ₫</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Tổng tiền</strong></div>
          <div class="card-body">
            <table class="table mb-0">
              <tbody>
                <tr><td>Tạm tính</td><td class="text-end">{{ number_format((float) $order->subtotal, 0, ',', '.') }} ₫</td></tr>
                <tr><td>Phí vận chuyển</td><td class="text-end">{{ number_format((float) $order->shipping_fee, 0, ',', '.') }} ₫</td></tr>
                <tr><td>Giảm giá</td><td class="text-end">{{ number_format((float) $order->discount_amount, 0, ',', '.') }} ₫</td></tr>
                <tr><th>Tổng tiền</th><th class="text-end">{{ number_format((float) $order->total_amount, 0, ',', '.') }} ₫</th></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><strong>Lịch sử trạng thái</strong></div>
          <div class="card-body">
            @forelse($order->histories as $history)
              <div class="border-bottom pb-2 mb-2">
                <div>
                  <strong>{{ $orderStatuses[$history->from_status] ?? 'Mới' }}</strong>
                  →
                  <strong>{{ $orderStatuses[$history->to_status] ?? $history->to_status }}</strong>
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
            <form method="POST" action="{{ panel_route('order.status', ['order' => $order->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <select name="status" class="form-select" required>
                <option value="">Chọn trạng thái</option>
                @foreach(OrderStatus::allowedTransitions($currentStatus) as $status)
                  <option value="{{ $status }}">{{ $orderStatuses[$status] ?? $status }}</option>
                @endforeach
              </select>
              <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú thao tác"></textarea>
              <textarea name="cancel_reason" class="form-control" rows="2" placeholder="Lý do hủy nếu hủy đơn"></textarea>
              <button class="btn btn-primary" type="submit" @disabled(count(OrderStatus::allowedTransitions($currentStatus)) === 0)>Cập nhật</button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Thanh toán</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('order.payment', ['order' => $order->id]) }}" class="vstack gap-3">
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
                  <option value="{{ $value }}" @selected(($payment?->method ?? $order->payment_method) === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <input type="text" name="transaction_code" class="form-control" value="{{ $payment?->transaction_code }}" placeholder="Mã giao dịch">
              <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú thanh toán"></textarea>
              <button class="btn btn-primary" type="submit">Cập nhật thanh toán</button>
            </form>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header"><strong>Tồn kho</strong></div>
          <div class="card-body">
            <div>Đã trừ kho: {{ $order->stock_deducted_at ? $order->stock_deducted_at->format('d/m/Y H:i') : 'Chưa' }}</div>
            <div>Đã hoàn kho: {{ $order->stock_restored_at ? $order->stock_restored_at->format('d/m/Y H:i') : 'Chưa' }}</div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><strong>Ghi chú nội bộ</strong></div>
          <div class="card-body">
            <form method="POST" action="{{ panel_route('order.internal-note', ['order' => $order->id]) }}" class="vstack gap-3">
              @csrf
              @method('PATCH')
              <textarea name="internal_note" class="form-control" rows="5">{{ old('internal_note', $order->internal_note) }}</textarea>
              <button class="btn btn-primary" type="submit">Lưu ghi chú</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
