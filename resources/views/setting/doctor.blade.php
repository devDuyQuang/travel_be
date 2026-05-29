@extends('index')

@section('content')
<h5 class="card-header">Bác sĩ / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateDoctor') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Content & Image -->
        <div class="col-md-7">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Thông tin giới thiệu & Hình ảnh</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề (H2)</label>
                        <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Meet Dr. Natali Jackson">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên bác sĩ (In đậm)</label>
                        <input type="text" name="doctor_name" class="form-control" value="{{ $v['doctor_name'] ?? '' }}" placeholder="Dr. Natali jackson">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ảnh bác sĩ (685x720)</label>
                            <input type="file" name="image_file" class="form-control img-input" data-preview="preview-doctor">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Số kinh nghiệm</label>
                            <input type="text" name="experience[number]" class="form-control" value="{{ $v['experience']['number'] ?? '' }}" placeholder="20+">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nhãn kinh nghiệm</label>
                            <input type="text" name="experience[label]" class="form-control" value="{{ $v['experience']['label'] ?? '' }}" placeholder="Years Experienced">
                        </div>
                    </div>
                    <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="height: 350px; overflow: hidden;">
                        <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="preview-doctor" class="h-100 w-100 object-fit-contain {{ empty($v['image']) ? 'd-none' : '' }}">
                        @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills list -->
        <div class="col-md-5">
            <div class="card shadow-none border mb-4">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Kỹ năng (Skills)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-skill"><i class="ti tabler-plus me-1"></i>Thêm</button>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề phần kỹ năng</label>
                        <input type="text" name="skills_header" class="form-control mb-3" value="{{ $v['skills_header'] ?? '' }}" placeholder="About Skills">
                    </div>
                    <div id="skills-container">
                        @foreach($v['skills'] ?? [] as $index => $skill)
                        <div class="skill-item mb-2 input-group input-group-merge">
                            <input type="text" name="skills[]" class="form-control form-control-sm" value="{{ $skill }}">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="ti tabler-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Achievement Cards -->
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Thành tựu (Achievements)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-ach"><i class="ti tabler-plus me-1"></i>Thêm thẻ</button>
                </div>
                <div class="card-body pt-4">
                    <div id="ach-container">
                        @foreach($v['achievements'] ?? [] as $index => $ach)
                        <div class="ach-item mb-4 p-3 border rounded bg-light position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Badge Image (Logo giải thưởng)</label>
                                    <input type="file" name="achievement_images[{{ $index }}]" class="form-control form-control-sm img-input" data-preview="preview-ach-{{ $index }}">
                                    <div class="mt-1 d-flex">
                                        <img src="{{ !empty($ach['image']) ? asset($ach['image']) : '' }}" id="preview-ach-{{ $index }}" class="bg-white border rounded p-1 {{ empty($ach['image']) ? 'd-none' : '' }}" style="height: 50px;">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Tiêu đề (vd: ClinicMaster 2024)</label>
                                    <input type="text" name="achievements[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $ach['title'] ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Phụ đề (Sub-title)</label>
                                    <input type="text" name="achievements[{{ $index }}][subtitle]" class="form-control form-control-sm" value="{{ $ach['subtitle'] ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Text Link (vd: Best Dermatologists)</label>
                                    <input type="text" name="achievements[{{ $index }}][link_text]" class="form-control form-control-sm" value="{{ $ach['link_text'] ?? '' }}">
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
<template id="skill-template">
    <div class="skill-item mb-2 input-group input-group-merge">
        <input type="text" name="skills[]" class="form-control form-control-sm">
        <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="ti tabler-trash"></i></button>
    </div>
</template>

<template id="ach-template">
    <div class="ach-item mb-4 p-3 border rounded bg-light position-relative">
        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label small fw-bold">Badge Image</label>
                <input type="file" name="achievement_images[__INDEX__]" class="form-control form-control-sm img-input" data-preview="preview-ach-__INDEX__">
                <div class="mt-1 d-flex">
                    <img id="preview-ach-__INDEX__" class="bg-white border rounded p-1 d-none" style="height: 50px;">
                </div>
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold">Tiêu đề</label>
                <input type="text" name="achievements[__INDEX__][title]" class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold">Phụ đề</label>
                <input type="text" name="achievements[__INDEX__][subtitle]" class="form-control form-control-sm">
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold">Text Link</label>
                <input type="text" name="achievements[__INDEX__][link_text]" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteDoctorItemModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn btn-danger" id="confirm-delete-doctor">Xoá</button>
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
    $('#add-skill').on('click', function() {
        let html = $('#skill-template').html();
        $('#skills-container').append(html);
    });

    $('#add-ach').on('click', function() {
        const index = $('#ach-container .ach-item').length;
        let html = $('#ach-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#ach-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteDoctorItemModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.skill-item, .ach-item');
        deleteModal.show();
    });

    $('#confirm-delete-doctor').on('click', function() {
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
        if (container.attr('id') === 'ach-container') {
            $('#ach-container .ach-item').each(function(idx) {
                // Fix inputs
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/achievements\[\d+\]/, 'achievements[' + idx + ']')
                                                .replace(/achievement_images\[\d+\]/, 'achievement_images[' + idx + ']'));
                    }
                    // Fix preview id
                    if ($(this).hasClass('img-input')) {
                        $(this).attr('data-preview', 'preview-ach-' + idx);
                        $(this).siblings('div').find('img').attr('id', 'preview-ach-' + idx);
                    }
                });
            });
        }
    }
});
</script>
@endpush
@endsection
