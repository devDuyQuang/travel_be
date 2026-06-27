@extends('index')

@section('title', 'Quản lý Booking')

@section('content')
@php
    use App\Enums\BookingStatus;
    use App\Enums\PaymentStatus;
    use App\Enums\PricingMode;

    $hasFilters = collect([
        request('keyword'),
        request('booking_status'),
        request('payment_status'),
        request('service_category_id'),
        request('created_from'),
        request('created_to'),
        request('start_from'),
        request('start_to'),
    ])->filter(fn ($value) => filled($value))->isNotEmpty();
@endphp

<main class="main-wrapper">
    <div class="main-content commerce-page">

        {{-- Page header --}}
        <div class="commerce-page-header">
            <div class="commerce-page-title">
                <div class="commerce-page-title-icon">
                    <span class="material-icons-outlined">calendar_month</span>
                </div>

                <div>
                    <h4>Quản lý Booking</h4>
                    <p>Theo dõi và xử lý các yêu cầu đặt dịch vụ của khách hàng.</p>
                </div>
            </div>

            <div class="commerce-page-summary">
                Tổng:
                <strong>{{ number_format($bookings->total()) }}</strong>
                booking
            </div>
        </div>

        {{-- Filter --}}
        <div class="card commerce-card commerce-filter-card">
            <div class="card-header commerce-card-header">
                <div>
                    <h6>Bộ lọc tìm kiếm</h6>
                    <small>
                        Tìm theo mã booking, khách hàng, dịch vụ, trạng thái hoặc thời gian.
                    </small>
                </div>

                @if($hasFilters)
                    <a
                        href="{{ panel_route('booking.index') }}"
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
                    action="{{ panel_route('booking.index') }}"
                >
                    <div class="row g-3">

                        <div class="col-12 col-lg-4">
                            <label
                                for="booking-keyword"
                                class="form-label"
                            >
                                Từ khóa
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="material-icons-outlined">search</span>
                                </span>

                                <input
                                    id="booking-keyword"
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
                                for="booking-status"
                                class="form-label"
                            >
                                Trạng thái
                            </label>

                            <select
                                id="booking-status"
                                name="booking_status"
                                class="form-select"
                            >
                                <option value="">Tất cả</option>

                                @foreach($bookingStatuses as $value => $label)
                                    <option
                                        value="{{ $value }}"
                                        @selected((string) request('booking_status') === (string) $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-2">
                            <label
                                for="booking-payment-status"
                                class="form-label"
                            >
                                Thanh toán
                            </label>

                            <select
                                id="booking-payment-status"
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
                                for="booking-service-category"
                                class="form-label"
                            >
                                Loại dịch vụ
                            </label>

                            <select
                                id="booking-service-category"
                                name="service_category_id"
                                class="form-select"
                            >
                                <option value="">Tất cả dịch vụ</option>

                                @foreach($serviceCategories as $id => $name)
                                    <option
                                        value="{{ $id }}"
                                        @selected(
                                            (string) request('service_category_id') ===
                                            (string) $id
                                        )
                                    >
                                        {{ $name }}
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
                            <label class="form-label">
                                Khoảng ngày sử dụng
                            </label>

                            <div class="commerce-date-range">
                                <div class="commerce-date-field">
                                    <span class="commerce-date-field-label">
                                        Từ ngày
                                    </span>

                                    <input
                                        type="date"
                                        name="start_from"
                                        class="form-control"
                                        value="{{ request('start_from') }}"
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
                                        name="start_to"
                                        class="form-control"
                                        value="{{ request('start_to') }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="commerce-filter-actions">
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
                                    href="{{ panel_route('booking.index') }}"
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
                    <h6>Danh sách booking</h6>

                    <small>
                        Hiển thị {{ $bookings->firstItem() ?? 0 }}
                        – {{ $bookings->lastItem() ?? 0 }}
                        trong tổng số {{ number_format($bookings->total()) }} booking.
                    </small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover commerce-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="commerce-code-column">
                                Mã booking
                            </th>

                            <th class="commerce-customer-column">
                                Khách hàng
                            </th>

                            <th class="commerce-content-column">
                                Dịch vụ
                            </th>

                            <th class="commerce-status-column">
                                Loại
                            </th>

                            <th class="commerce-date-column">
                                Ngày sử dụng
                            </th>

                            <th class="commerce-amount-column text-end">
                                Tổng tiền
                            </th>

                            <th class="commerce-status-column">
                                Booking
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
                        @forelse($bookings as $booking)
                            @php
                                $bookingStatus = $booking->booking_status instanceof BookingStatus
                                    ? $booking->booking_status
                                    : BookingStatus::tryFrom((string) $booking->booking_status);

                                $paymentStatus = $booking->payment_status instanceof PaymentStatus
                                    ? $booking->payment_status
                                    : PaymentStatus::tryFrom((string) $booking->payment_status);

                                $customerInitial = filled($booking->customer_name)
                                    ? mb_strtoupper(mb_substr(trim($booking->customer_name), 0, 1))
                                    : '?';
                                $pricingMode = $booking->pricing_mode instanceof PricingMode
                                    ? $booking->pricing_mode
                                    : PricingMode::tryFrom((string) $booking->pricing_mode);
                                $bookingTypeValue = $booking->booking_type?->value ?? (string) $booking->booking_type;
                            @endphp

                            <tr>
                                <td>
                                    <a
                                        href="{{ panel_route('booking.show', ['booking' => $booking->id]) }}"
                                        class="commerce-code"
                                    >
                                        {{ $booking->booking_code }}
                                    </a>
                                </td>

                                <td>
                                    <div class="commerce-customer">
                                        <div class="commerce-customer-avatar">
                                            {{ $customerInitial }}
                                        </div>

                                        <div class="commerce-customer-info">
                                            <strong title="{{ $booking->customer_name }}">
                                                {{ $booking->customer_name ?: 'Chưa có tên' }}
                                            </strong>

                                            @if(filled($booking->customer_email))
                                                <a
                                                    href="mailto:{{ $booking->customer_email }}"
                                                    title="{{ $booking->customer_email }}"
                                                >
                                                    {{ $booking->customer_email }}
                                                </a>
                                            @endif

                                            @if(filled($booking->customer_phone))
                                                <a href="tel:{{ $booking->customer_phone }}">
                                                    <span class="material-icons-outlined">
                                                        call
                                                    </span>
                                                    {{ $booking->customer_phone }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="commerce-primary-info">
                                        <strong title="{{ $booking->service_name_snapshot }}">
                                            {{ $booking->service_name_snapshot ?: 'Chưa có tên dịch vụ' }}
                                        </strong>

                                        <span>
                                            {{ $booking->serviceCategory?->name ?: 'Chưa phân loại' }}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="commerce-status-badge bg-label-info">
                                        <span class="commerce-status-dot"></span>
                                        {{ $bookingTypes[$bookingTypeValue] ?? $bookingTypeValue ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="commerce-meta">
                                        <span class="material-icons-outlined">
                                            event
                                        </span>

                                        <div class="commerce-meta-content">
                                            <strong>
                                                {{ $booking->start_date?->format('d/m/Y') ?: '—' }}
                                            </strong>

                                            @if(filled($booking->start_time))
                                                <small>
                                                    {{ mb_substr((string) $booking->start_time, 0, 5) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <strong class="commerce-amount">
                                        @if($pricingMode === PricingMode::Quote)
                                            Cần báo giá
                                        @else
                                            {{ number_format(
                                                (float) $booking->total_amount,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                            ₫
                                        @endif
                                    </strong>
                                </td>

                                <td>
                                    <span class="commerce-status-badge {{ $bookingStatus?->badgeClass() ?? 'bg-label-secondary' }}">
                                        <span class="commerce-status-dot"></span>

                                        {{ $bookingStatus?->label()
                                            ?? $booking->booking_status
                                            ?? 'Chưa xác định' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="commerce-status-badge {{ $paymentStatus?->badgeClass() ?? 'bg-label-secondary' }}">
                                        <span class="commerce-status-dot"></span>

                                        {{ $paymentStatus?->label()
                                            ?? $booking->payment_status
                                            ?? 'Chưa xác định' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="commerce-meta-content">
                                        <strong>
                                            {{ $booking->created_at?->format('d/m/Y') ?: '—' }}
                                        </strong>

                                        @if($booking->created_at)
                                            <span>
                                                {{ $booking->created_at->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a
                                        href="{{ panel_route('booking.show', ['booking' => $booking->id]) }}"
                                        class="btn btn-sm btn-outline-primary commerce-detail-button"
                                        title="Xem chi tiết booking"
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
                                <td colspan="10">
                                    <div class="commerce-empty-state">
                                        <div class="commerce-empty-icon">
                                            <span class="material-icons-outlined">
                                                event_busy
                                            </span>
                                        </div>

                                        <h6>
                                            {{ $hasFilters
                                                ? 'Không tìm thấy booking phù hợp'
                                                : 'Chưa có booking' }}
                                        </h6>

                                        <p>
                                            {{ $hasFilters
                                                ? 'Không có booking nào khớp với các điều kiện lọc hiện tại.'
                                                : 'Các booking mới từ khách hàng sẽ xuất hiện tại đây.' }}
                                        </p>

                                        @if($hasFilters)
                                            <a
                                                href="{{ panel_route('booking.index') }}"
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

            @if($bookings->hasPages())
                <div class="card-footer commerce-pagination">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</main>
@endsection
