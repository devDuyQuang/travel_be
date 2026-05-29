@extends('index')

@section('content')
<h5 class="card-header">Cách thức hoạt động / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateHowItWork') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Content & Image -->
        <div class="col-md-7">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Nội dung & Hình ảnh</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề chính</label>
                        <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="How It Work">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Ảnh chính (1200x715)</label>
                        <input type="file" name="image_file" class="form-control img-input" data-preview="preview-main">
                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 300px; overflow: hidden;">
                            <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="preview-main" class="h-100 w-100 object-fit-contain {{ empty($v['image']) ? 'd-none' : '' }}">
                            @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Button -->
        <div class="col-md-5">
            <div class="card shadow-none border mb-4">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Nút Hẹn (Button)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Text nút</label>
                        <input type="text" name="appointment_btn_text" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Link nút</label>
                        <input type="text" name="appointment_btn_link" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#">
                    </div>
                </div>
            </div>

            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Thống kê (Stats Card)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-stat"><i class="ti tabler-plus me-1"></i>Thêm</button>
                </div>
                <div class="card-body pt-4">
                    <div id="stats-container">
                        @foreach($v['stats'] ?? [] as $index => $stat)
                        <div class="stat-item mb-3 p-3 border rounded bg-light position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
                            <div class="row g-2">
                                <div class="col-5">
                                    <label class="form-label small">Con số (vd: 180+)</label>
                                    <input type="text" name="stats[{{ $index }}][number]" class="form-control form-control-sm" value="{{ $stat['number'] ?? '' }}">
                                </div>
                                <div class="col-7">
                                    <label class="form-label small">Nhãn (vd: Specialists)</label>
                                    <input type="text" name="stats[{{ $index }}][label]" class="form-control form-control-sm" value="{{ $stat['label'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Steps -->
        <div class="col-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Các bước thực hiện (Features)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-feature"><i class="ti tabler-plus me-1"></i>Thêm bước</button>
                </div>
                <div class="card-body pt-4">
                    <div id="features-container" class="row g-3">
                        @foreach($v['features'] ?? [] as $index => $feature)
                        <div class="col-md-4 feature-item">
                            <div class="p-3 border rounded bg-light position-relative">
                                <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
                                <div class="mb-2">
                                    <label class="form-label small">Icon Class (Tabler/Fontawesome)</label>
                                    <input type="text" name="features[{{ $index }}][icon]" class="form-control form-control-sm" value="{{ $feature['icon'] ?? '' }}" placeholder="ti tabler-clock">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small">Tiêu đề bước</label>
                                    <input type="text" name="features[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $feature['title'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
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

<!-- Templates -->
<template id="stat-template">
    <div class="stat-item mb-3 p-3 border rounded bg-light position-relative">
        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
        <div class="row g-2">
            <div class="col-5">
                <label class="form-label small">Con số</label>
                <input type="text" name="stats[__INDEX__][number]" class="form-control form-control-sm">
            </div>
            <div class="col-7">
                <label class="form-label small">Nhãn</label>
                <input type="text" name="stats[__INDEX__][label]" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</template>

<template id="feature-template">
    <div class="col-md-4 feature-item">
        <div class="p-3 border rounded bg-light position-relative">
            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
            <div class="mb-2">
                <label class="form-label small">Icon Class</label>
                <input type="text" name="features[__INDEX__][icon]" class="form-control form-control-sm">
            </div>
            <div class="mb-0">
                <label class="form-label small">Tiêu đề bước</label>
                <input type="text" name="features[__INDEX__][title]" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteStepModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá mục này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-step">Xoá</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Preview logic
    $(document).on('change', '.img-input', function() {
        const file = this.files[0];
        const previewId = $(this).data('preview');
        const $preview = $('#' + previewId);
        const $placeholder = $preview.siblings('.ti-lg, .ti');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).removeClass('d-none');
                $placeholder.addClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Add handles
    $('#add-stat').on('click', function() {
        const index = $('#stats-container .stat-item').length;
        let html = $('#stat-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#stats-container').append(html);
    });

    $('#add-feature').on('click', function() {
        const index = $('#features-container .feature-item').length;
        let html = $('#feature-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#features-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteStepModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.stat-item, .feature-item');
        deleteModal.show();
    });

    $('#confirm-delete-step').on('click', function() {
        if (itemToRemove) {
            const container = itemToRemove.parent();
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexItems(container);
            });
        }
        deleteModal.hide();
    });

    function reIndexItems(container) {
        if (container.attr('id') === 'stats-container') {
            $('#stats-container .stat-item').each(function(idx) {
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    if (name) $(this).attr('name', name.replace(/stats\[\d+\]/, 'stats[' + idx + ']'));
                });
            });
        } else if (container.attr('id') === 'features-container') {
            $('#features-container .feature-item').each(function(idx) {
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    if (name) $(this).attr('name', name.replace(/features\[\d+\]/, 'features[' + idx + ']'));
                });
            });
        }
    }
});
</script>
@endpush
@endsection
