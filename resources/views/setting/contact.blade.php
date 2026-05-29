@extends('index')

@section('content')
<h5 class="card-header">Liên hệ / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateContact') }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Content & Information -->
        <div class="col-md-7">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Thông tin chung & Liên hệ</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Tiêu đề lớn (H1/H2)</label>
                            <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Get In Touch With Us">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả (P)</label>
                            <input type="text" name="description" class="form-control" value="{{ $v['description'] ?? '' }}" placeholder="Lorem Ipsum is simply dummy">
                        </div>

                        <hr class="my-3">

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-map-pin me-1"></i>Địa chỉ (Address)</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" name="address[label]" class="form-control form-control-sm" value="{{ $v['address']['label'] ?? '' }}" placeholder="Label: Address">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="address[value]" class="form-control form-control-sm" value="{{ $v['address']['value'] ?? '' }}" placeholder="Value: 234 Oak Drive...">
                                </div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-phone mx-1"></i>Trực tuyến (Call Us)</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" name="phone[label]" class="form-control form-control-sm" value="{{ $v['phone']['label'] ?? '' }}" placeholder="Label: Call Us">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="phone[value]" class="form-control form-control-sm" value="{{ $v['phone']['value'] ?? '' }}" placeholder="Value: +1 123 456 7890">
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-mail me-1"></i>Thư điện tử (Email)</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" name="email[label]" class="form-control form-control-sm" value="{{ $v['email']['label'] ?? '' }}" placeholder="Label: Send us a Mail">
                                </div>
                                <div class="col-8">
                                    <input type="text" name="email[value]" class="form-control form-control-sm" value="{{ $v['email']['value'] ?? '' }}" placeholder="Value: email@domain.com">
                                </div>
                            </div>
                        </div>

                        <!-- Opening Time -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold d-block text-primary"><i class="ti tabler-clock me-1"></i>Giờ làm việc (Opening Time)</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="text" name="time[label]" class="form-control form-control-sm" value="{{ $v['time']['label'] ?? '' }}" placeholder="Label: Opening Time">
                                </div>
                                <div class="col-8">
                                    <textarea name="time[value]" class="form-control form-control-sm" rows="3" placeholder="Mon-Thu: 8:00am-5:00pm&#10;Fri: 8:00am-1:00pm">{{ $v['time']['value'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="col-md-5">
            <!-- Appointment Button -->
            <div class="card shadow-none border mb-4">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Nút Hẹn lịch (Appointment Button)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Text hiển thị</label>
                        <input type="text" name="appointment_btn[text]" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Link liên kết</label>
                        <input type="text" name="appointment_btn[link]" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#">
                    </div>
                </div>
            </div>

            <!-- Map Iframe -->
            <div class="card shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Bản đồ Google Maps (Iframe)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold">Mã nhúng (Embed Code)</label>
                        <textarea name="map_iframe" class="form-control text-muted font-monospace" rows="5" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ $v['map_iframe'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4 text-end">
            <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
            <button type="reset" class="btn btn-label-secondary">Hủy</button>
        </div>
    </div>
</form>
</div>
@endsection
