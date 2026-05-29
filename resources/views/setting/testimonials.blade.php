@extends('index')

@section('content')
<h5 class="card-header">Ý kiến khách hàng / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateTestimonials') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Section Title -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <label class="form-label fw-bold">Tiêu đề chính Section</label>
                    <input type="text" name="main_title" class="form-control" value="{{ $v['main_title'] ?? '' }}" placeholder="Real Patients, Real Stories. And Our Achievements">
                </div>
            </div>
        </div>

        <!-- Main Image & Floating Review -->
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Ảnh chính & Ô đánh giá bay</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ảnh nền lớn bên trái (525x640)</label>
                        <input type="file" name="main_image_file" class="form-control img-input" data-preview="preview-main">
                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                            <img src="{{ !empty($v['main_image']) ? asset($v['main_image']) : '' }}" id="preview-main" class="h-100 object-fit-contain {{ empty($v['main_image']) ? 'd-none' : '' }}">
                            @if(empty($v['main_image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                        </div>
                    </div>
                    
                    <div class="p-3 border rounded bg-light shadow-sm">
                        <p class="fw-bold small mb-2 text-primary">Floating Review Card</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Avatar (50x50)</label>
                                <input type="file" name="floating_review_avatar" class="form-control form-control-sm img-input" data-preview="preview-float">
                                <img src="{{ !empty($v['floating_review']['avatar']) ? asset($v['floating_review']['avatar']) : '' }}" id="preview-float" class="mt-2 rounded-circle border {{ empty($v['floating_review']['avatar']) ? 'd-none' : '' }}" width="50" height="50">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small">Tên bác sĩ</label>
                                <input type="text" name="floating_review[name]" class="form-control form-control-sm" value="{{ $v['floating_review']['name'] ?? '' }}">
                                <label class="form-label small mt-2">Đánh giá (1-5 sao)</label>
                                <input type="number" name="floating_review[rating]" class="form-control form-control-sm" value="{{ $v['floating_review']['rating'] ?? 5 }}" min="1" max="5">
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Nội dung đánh giá ngắn</label>
                                <textarea name="floating_review[text]" class="form-control form-control-sm" rows="2">{{ $v['floating_review']['text'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Ô Thành tựu (Achievement)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Con số (vd: 150k)</label>
                            <input type="text" name="achievement[number]" class="form-control" value="{{ $v['achievement']['number'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nội dung (vd: Patient recovers)</label>
                            <input type="text" name="achievement[text]" class="form-control" value="{{ $v['achievement']['text'] ?? '' }}">
                        </div>
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold d-block">4 Avatar nhỏ (50x50)</label>
                            <div class="row g-2">
                                @for($i=0; $i<4; $i++)
                                <div class="col-3 text-center">
                                    <input type="file" name="achievement_avatars[{{ $i }}]" class="form-control form-control-sm img-input" data-preview="preview-achieve-{{ $i }}">
                                    <img src="{{ !empty($v['achievement']['avatars'][$i]) ? asset($v['achievement']['avatars'][$i]) : '' }}" id="preview-achieve-{{ $i }}" class="mt-2 rounded-circle border {{ empty($v['achievement']['avatars'][$i]) ? 'd-none' : '' }}" width="40" height="40">
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Items (Add/Remove) -->
        <div class="col-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Danh sách ý kiến phản hồi</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-item"><i class="ti tabler-plus me-1"></i>Thêm ý kiến</button>
                </div>
                <div class="card-body pt-4">
                    <div id="items-container" class="row g-4">
                        @foreach($v['items'] as $index => $item)
                        <div class="col-md-6 testimonial-item" data-index="{{ $index }}">
                            <div class="card h-100 shadow-none border bg-light">
                                <div class="card-header p-2 bg-secondary text-white d-flex justify-content-between align-items-center">
                                    <small class="fw-bold">Ý kiến #{{ $index + 1 }}</small>
                                    <button type="button" class="btn btn-xs btn-danger remove-item"><i class="ti tabler-trash"></i></button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <label class="form-label small">Ảnh minh họa (320x380)</label>
                                            <input type="file" name="items[{{ $index }}][image_file]" class="form-control form-control-sm img-input" data-preview="preview-item-{{ $index }}">
                                            <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                                                <img src="{{ !empty($item['image']) ? asset($item['image']) : '' }}" id="preview-item-{{ $index }}" class="h-100 object-fit-contain {{ empty($item['image']) ? 'd-none' : '' }}">
                                                @if(empty($item['image'])) <i class="ti tabler-photo text-muted"></i> @endif
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="mb-2">
                                                <label class="form-label small">Tiêu đề (vd: Best Treatment)</label>
                                                <input type="text" name="items[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $item['title'] ?? '' }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small">Link Video (Watch Video)</label>
                                                <input type="text" name="items[{{ $index }}][video_link]" class="form-control form-control-sm" value="{{ $item['video_link'] ?? '' }}">
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">Tên KH/BS</label>
                                                    <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $item['name'] ?? '' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">Chức danh</label>
                                                    <input type="text" name="items[{{ $index }}][role]" class="form-control form-control-sm" value="{{ $item['role'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label class="form-label small">Nội dung phản hồi</label>
                                            <textarea name="items[{{ $index }}][review]" class="form-control form-control-sm" rows="3">{{ $item['review'] ?? '' }}</textarea>
                                        </div>
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

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="testimonialDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá ý kiến này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-item">Xoá</button>
      </div>
    </div>
  </div>
</div>

<!-- Template for New Item -->
<template id="item-template">
    <div class="col-md-6 testimonial-item" data-index="__INDEX__">
        <div class="card h-100 shadow-none border bg-light">
            <div class="card-header p-2 bg-secondary text-white d-flex justify-content-between align-items-center">
                <small class="fw-bold">Ý kiến mới</small>
                <button type="button" class="btn btn-xs btn-danger remove-item"><i class="ti tabler-trash"></i></button>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label small">Ảnh minh họa (320x380)</label>
                        <input type="file" name="items[__INDEX__][image_file]" class="form-control form-control-sm img-input" data-preview="preview-item-__INDEX__">
                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                            <img src="" id="preview-item-__INDEX__" class="h-100 object-fit-contain d-none">
                            <i class="ti tabler-photo text-muted"></i>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="mb-2">
                            <label class="form-label small">Tiêu đề</label>
                            <input type="text" name="items[__INDEX__][title]" class="form-control form-control-sm">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Link Video</label>
                            <input type="text" name="items[__INDEX__][video_link]" class="form-control form-control-sm">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">Tên</label>
                                <input type="text" name="items[__INDEX__][name]" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Role</label>
                                <input type="text" name="items[__INDEX__][role]" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <label class="form-label small">Nội dung phản hồi</label>
                        <textarea name="items[__INDEX__][review]" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

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

    // Add item logic
    $('#add-item').on('click', function() {
        const index = $('.testimonial-item').length;
        let html = $('#item-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#items-container').append(html);
    });

    // Remove item logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('testimonialDeleteModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.testimonial-item');
        deleteModal.show();
    });

    $('#confirm-delete-item').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexItems();
            });
        }
        deleteModal.hide();
    });

    function reIndexItems() {
        $('.testimonial-item').each(function(index) {
            $(this).find('input, textarea, select').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/items\[\d+\]/, 'items[' + index + ']');
                    $(this).attr('name', newName);
                }
                const dataPreview = $(this).attr('data-preview');
                if (dataPreview && dataPreview.startsWith('preview-item-')) {
                    const newPreview = 'preview-item-' + index;
                    $(this).attr('data-preview', newPreview);
                    $(this).siblings().find('img').attr('id', newPreview);
                }
            });
            $(this).find('.card-header small').text('Ý kiến #' + (index + 1));
        });
    }
});
</script>
@endpush
@endsection
