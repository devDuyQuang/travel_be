@extends('index')

@section('content')
<h5 class="card-header">Lịch hẹn / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateAppointment') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Hình ảnh Section (600x600)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <label class="form-label">Chọn ảnh hiển thị bên trái</label>
                            <input type="file" name="image_file" id="image-input" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4 text-center">
                            <div id="image-preview-container" class="border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                                @if(!empty($currentImageUrl))
                                    <img src="{{ $currentImageUrl }}" id="image-preview" class="h-100 object-fit-contain rounded" alt="Preview">
                                @else
                                    <div id="image-placeholder" class="text-muted"><i class="ti tabler-photo ti-lg"></i><br>Chưa có ảnh</div>
                                    <img src="" id="image-preview" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Nội dung văn bản</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiêu đề chính (Title)</label>
                            <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Ví dụ: Make An Appointment">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiêu đề phụ (Subtitle)</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $v['subtitle'] ?? '' }}" placeholder="Ví dụ: Apply For Treatments">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dòng chữ dọc (Vertical Text)</label>
                            <input type="text" name="appointment_now_text" class="form-control" value="{{ $v['appointment_now_text'] ?? '' }}" placeholder="Ví dụ: APPOINTMENT NOW">
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Text nút (Button Text)</label>
                                    <input type="text" name="button_text" class="form-control" value="{{ $v['button_text'] ?? '' }}" placeholder="Ví dụ: Appointment">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">Link nút (Button Link)</label>
                                    <input type="text" name="button_link" class="form-control" value="{{ $v['button_link'] ?? '' }}" placeholder="/appointment">
                                </div>
                            </div>
                        </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#image-input').on('change', function() {
        const file = this.files[0];
        const $preview = $('#image-preview');
        const $placeholder = $('#image-placeholder');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).removeClass('d-none');
                if ($placeholder.length) $placeholder.addClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
@endsection
