@extends('index')

@section('content')
<h5 class="card-header">Tại sao chọn chúng tôi / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateWhyChooseUs') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Section: Image & Experience -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Hình ảnh & Kinh nghiệm</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label">Chọn ảnh đại diện (vd: 600x600)</label>
                            <input type="file" name="image_file" id="image-input" class="form-control" accept="image/*">
                            <div id="image-preview-container" class="mt-3 border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden;">
                                @if(!empty($currentImageUrl))
                                    <img src="{{ $currentImageUrl }}" id="image-preview" class="h-100 object-fit-contain rounded" alt="Preview">
                                @else
                                    <div id="image-placeholder" class="text-muted"><i class="ti tabler-photo ti-lg"></i><br>Chưa có ảnh</div>
                                    <img src="" id="image-preview" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Con số kinh nghiệm (vd: 20+)</label>
                                <input type="text" name="experience_number" class="form-control" value="{{ $v['experience_number'] ?? '' }}" placeholder="20+">
                            </div>
                            <div>
                                <label class="form-label fw-bold">Nhãn kinh nghiệm (vd: Years Experienced)</label>
                                <input type="text" name="experience_label" class="form-control" value="{{ $v['experience_label'] ?? '' }}" placeholder="Years Experienced">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Content & Features -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Tiêu đề & Danh sách đặc điểm</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-feature">
                        <i class="ti tabler-plus"></i> Thêm đặc điểm
                    </button>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tiêu đề chính Section</label>
                        <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Ví dụ: Why Choose Us For Your Health Care Needs">
                    </div>

                    <div class="row g-4" id="features-container">
                        @foreach($v['items'] ?? [] as $i => $item)
                        <div class="col-md-6 feature-item">
                            <div class="card h-100 shadow-none border bg-light position-relative">
                                <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-feature" style="z-index: 1;">
                                    <i class="ti tabler-trash"></i>
                                </button>
                                <div class="card-header p-2 bg-secondary text-white text-center">
                                    <small class="fw-bold feature-index">Đặc điểm {{ $i + 1 }}</small>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tiêu đề</label>
                                        <input type="text" name="items[{{ $i }}][title]" class="form-control form-control-sm" value="{{ $item['title'] }}" placeholder="Ví dụ: More Experience">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Mô tả</label>
                                        <textarea name="items[{{ $i }}][description]" class="form-control form-control-sm" rows="3" placeholder="Nhập mô tả chi tiết...">{{ $item['description'] }}</textarea>
                                    </div>
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

<template id="feature-template">
    <div class="col-md-6 feature-item">
        <div class="card h-100 shadow-none border bg-light position-relative">
            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-feature" style="z-index: 1;">
                <i class="ti tabler-trash"></i>
            </button>
            <div class="card-header p-2 bg-secondary text-white text-center">
                <small class="fw-bold feature-index">Đặc điểm New</small>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề</label>
                    <input type="text" name="items[__INDEX__][title]" class="form-control form-control-sm" placeholder="Ví dụ: More Experience">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="items[__INDEX__][description]" class="form-control form-control-sm" rows="3" placeholder="Nhập mô tả chi tiết..."></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteFeatureModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá đặc điểm này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-feature">Xoá</button>
      </div>
    </div>
  </div>
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

    $('#add-feature').on('click', function() {
        const index = $('#features-container .feature-item').length;
        let html = $('#feature-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#features-container').append(html);
        reIndexFeatures();
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFeatureModal'));

    $(document).on('click', '.remove-feature', function() {
        itemToRemove = $(this).closest('.feature-item');
        deleteModal.show();
    });

    $('#confirm-delete-feature').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexFeatures();
            });
        }
        deleteModal.hide();
    });

    function reIndexFeatures() {
        $('#features-container .feature-item').each(function(idx) {
            $(this).find('.feature-index').text('Đặc điểm ' + (idx + 1));
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/items\[\d+\]/, 'items[' + idx + ']'));
                }
            });
        });
    }
});
</script>
@endpush
@endsection
