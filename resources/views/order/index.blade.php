@extends('index')

@section('title', 'Quản lý Đơn hàng')

@section('content')
@php
    use App\Enums\OrderStatus;
    use App\Enums\PaymentStatus;

    $hasFilters = collect([
        request('keyword'),
        request('order_status'),
        request('payment_status'),
        request('payment_method'),
        request('created_from'),
        request('created_to'),
    ])->filter(fn ($value) => filled($value))->isNotEmpty();
@endphp

<main class="main-wrapper">
    <div class="main-content commerce-page">

        {{-- Page header --}}
        <div class="commerce-page-header">
            <div class="commerce-page-title">
                <div class="commerce-page-title-icon">
                    <span class="material-icons-outlined">inventory_2</span>
                </div>

                <div>
                    <h4>Quản lý Đơn hàng</h4>
                    <p>Theo dõi đơn hàng, thanh toán và tình trạng xử lý sản phẩm.</p>
                </div>
            </div>

            <div class="commerce-page-summary">
                Tổng:
                <strong>{{ number_format($orders->total()) }}</strong>
                đơn hàng
            </div>
        </div>

        {{-- Filter --}}
        <div class="card commerce-card commerce-filter-card">
            <div class="card-header commerce-card-header">
                <div>
                    <h6>Bộ lọc tìm kiếm</h6>
                    <small>
                        Tìm theo mã đơn, khách hàng, trạng thái, phương thức hoặc thời gian.
                    </small>
                </div>

                @if($hasFilters)
                    <a
                        href="{{ panel_route('order.index') }}"
                        class="btn btn-sm btn-outline-secondary commerce-card-header-action"
                    >
                        <span class="material-icons-outlined">restart_alt</span>
                        Xóa bộ lọc
                    </a>
                @endif
            </div>

            <div class="card-body">
                <form
                    method="GET"
                    action="{{ panel_route('order.index') }}"
                >
                    <div class="row g-3">

                        <div class="col-12 col-lg-4">
                            <label
                                for="order-keyword"
                                class="form-label"
                            >
                                Từ khóa
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="material-icons-outlined">search</span>
                                </span>

                                <input
                                    id="order-keyword"
                                    type="text"
                                    name="keyword"
                                    class="form-control"
                                    value="{{ request('keyword') }}"
                                    placeholder="Mã, tên, email hoặc số điện thoại..."
                                >
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-2">
                            <label
                                for="order-status"
                                class="form-label"
                            >
                                Trạng thái đơn
                            </label>

                            <select
                                id="order-status"
                                name="order_status"
                                class="form-select"
                            >
                                <option value="">Tất cả</option>

                                @foreach($orderStatuses as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected((string) request('order_status') === (string) $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-2">
                            <label
                                for="order-payment-status"
                                class="form-label"
                            >
                                Thanh toán
                            </label>

                            <select
                                id="order-payment-status"
                                name="payment_status"
                                class="form-select"
                            >
                                <option value="">Tất cả</option>

                                @foreach($paymentStatuses as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected((string) request('payment_status') === (string) $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <label
                                for="order-payment-method"
                                class="form-label"
                            >
                                Phương thức thanh toán
                            </label>

                            <select
                                id="order-payment-method"
                                name="payment_method"
                                class="form-select"
                            >
                                <option value="">Tất cả phương thức</option>

                                @foreach($paymentMethods as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected((string) request('payment_method') === (string) $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="form-label">
                                Khoảng ngày tạo
                            </label>

                            <div class="commerce-date-range">
                                <div class="commerce-date-field">
                                    <span class="commerce-date-field-label">
                                        Từ ngày
                                    </span>

                                    <input
                                        type="date"
                                        name="created_from"
                                        class="form-control"
                                        value="{{ request('created_from') }}"
                                    >
                                </div>

                                <div class="commerce-date-separator">
                                    <span class="material-icons-outlined">
                                        arrow_forward
                                    </span>
                                </div>

                                <div class="commerce-date-field">
                                    <span class="commerce-date-field-label">
                                        Đến ngày
                                    </span>

                                    <input
                                        type="date"
                                        name="created_to"
                                        class="form-control"
                                        value="{{ request('created_to') }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="commerce-filter-actions h-100">
                                <button
                                    class="btn btn-primary"
                                    type="submit"
                                >
                                    <span class="material-icons-outlined">
                                        filter_alt
                                    </span>
                                    Áp dụng bộ lọc
                                </button>

                                <a
                                    href="{{ panel_route('order.index') }}"
                                    class="btn btn-outline-secondary"
                                >
                                    Đặt lại
                                </a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card commerce-card">
            <div class="card-header commerce-card-header">
                <div>
                    <h6>Danh sách đơn hàng</h6>

                    <small>
                        Hiển thị {{ $orders->firstItem() ?? 0 }}
                        – {{ $orders->lastItem() ?? 0 }}
                        trong tổng số {{ number_format($orders->total()) }} đơn hàng.
                    </small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover commerce-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="commerce-code-column">
                                Mã đơn
                            </th>

                            <th class="commerce-customer-column">
                                Khách hàng
                            </th>

                            <th class="commerce-content-column">
                                Sản phẩm
                            </th>

                            <th class="commerce-amount-column text-end">
                                Tổng tiền
                            </th>

                            <th class="commerce-status-column">
                                Đơn hàng
                            </th>

                            <th class="commerce-status-column">
                                Thanh toán
                            </th>

                            <th class="commerce-created-column">
                                Ngày tạo
                            </th>

                            <th class="commerce-action-column"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $orderStatus = $order->order_status instanceof OrderStatus
                                    ? $order->order_status
                                    : OrderStatus::tryFrom((string) $order->order_status);

                                $paymentStatus = $order->payment_status instanceof PaymentStatus
                                    ? $order->payment_status
                                    : PaymentStatus::tryFrom((string) $order->payment_status);

                                $customerInitial = filled($order->customer_name)
                                    ? mb_strtoupper(mb_substr(trim($order->customer_name), 0, 1))
                                    : '?';
                            @endphp

                            <tr>
                                <td>
                                    <a
                                        href="{{ panel_route('order.show', ['order' => $order->id]) }}"
                                        class="commerce-code"
                                    >
                                        {{ $order->order_code }}
                                    </a>
                                </td>

                                <td>
                                    <div class="commerce-customer">
                                        <div class="commerce-customer-avatar">
                                            {{ $customerInitial }}
                                        </div>

                                        <div class="commerce-customer-info">
                                            <strong title="{{ $order->customer_name }}">
                                                {{ $order->customer_name ?: 'Chưa có tên' }}
                                            </strong>

                                            @if(filled($order->customer_email))
                                                <a
                                                    href="mailto:{{ $order->customer_email }}"
                                                    title="{{ $order->customer_email }}"
                                                >
                                                    {{ $order->customer_email }}
                                                </a>
                                            @endif

                                            @if(filled($order->customer_phone))
                                                <a href="tel:{{ $order->customer_phone }}">
                                                    <span class="material-icons-outlined">
                                                        call
                                                    </span>
                                                    {{ $order->customer_phone }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="commerce-meta">
                                        <span class="material-icons-outlined">
                                            shopping_bag
                                        </span>

                                        <div class="commerce-meta-content">
                                            <strong>
                                                {{ number_format((int) ($order->items_count ?? 0)) }}
                                            </strong>

                                            <small>
                                                sản phẩm
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <strong class="commerce-amount">
                                        {{ number_format(
                                            (float) $order->total_amount,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                        ₫
                                    </strong>
                                </td>

                                <td>
                                    <span class="commerce-status-badge {{ $orderStatus?->badgeClass() ?? 'bg-label-secondary' }}">
                                        <span class="commerce-status-dot"></span>

                                        {{ $orderStatus?->label()
                                            ?? $order->order_status
                                            ?? 'Chưa xác định' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="commerce-status-badge {{ $paymentStatus?->badgeClass() ?? 'bg-label-secondary' }}">
                                        <span class="commerce-status-dot"></span>

                                        {{ $paymentStatus?->label()
                                            ?? $order->payment_status
                                            ?? 'Chưa xác định' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="commerce-meta-content">
                                        <strong>
                                            {{ $order->created_at?->format('d/m/Y') ?: '—' }}
                                        </strong>

                                        @if($order->created_at)
                                            <span>
                                                {{ $order->created_at->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a
                                        href="{{ panel_route('order.show', ['order' => $order->id]) }}"
                                        class="btn btn-sm btn-outline-primary commerce-detail-button"
                                        title="Xem chi tiết đơn hàng"
                                    >
                                        <span class="material-icons-outlined">
                                            visibility
                                        </span>

                                        <span class="commerce-detail-text">
                                            Chi tiết
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="commerce-empty-state">
                                        <div class="commerce-empty-icon">
                                            <span class="material-icons-outlined">
                                                production_quantity_limits
                                            </span>
                                        </div>

                                        <h6>
                                            {{ $hasFilters
                                                ? 'Không tìm thấy đơn hàng phù hợp'
                                                : 'Chưa có đơn hàng' }}
                                        </h6>

                                        <p>
                                            {{ $hasFilters
                                                ? 'Không có đơn hàng nào khớp với các điều kiện lọc hiện tại.'
                                                : 'Các đơn hàng sản phẩm mới sẽ xuất hiện tại đây.' }}
                                        </p>

                                        @if($hasFilters)
                                            <a
                                                href="{{ panel_route('order.index') }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Xóa bộ lọc
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="card-footer commerce-pagination">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection