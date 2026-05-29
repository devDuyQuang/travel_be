@extends('index')

@section('content')
<h5 class="card-header">Bác sĩ / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateSpecialists') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Section Header -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Thông tin chung Section</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiêu đề chính Section</label>
                            <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Ví dụ: We Employ Only Specialists">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Link Xem tất cả (View All)</label>
                            <input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all_link'] ?? '' }}" placeholder="/specialists">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Specialists List -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Danh sách các bác sĩ (Giao diện 4 cột)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-specialist">
                        <i class="ti tabler-plus"></i> Thêm bác sĩ
                    </button>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-4" id="specialists-container">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <div class="col-md-6 col-lg-3 specialist-item">
                            <div class="card h-100 shadow-none border position-relative">
                                <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-specialist" style="z-index: 1;">
                                    <i class="ti tabler-trash"></i>
                                </button>
                                <div class="card-header p-2 bg-primary text-white text-center">
                                    <small class="fw-bold specialist-index">Bác sĩ {{ $index + 1 }}</small>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Ảnh (300x335)</label>
                                        <input type="file" name="specialist_images[{{ $index }}]" class="form-control form-control-sm img-input" accept="image/*" data-preview="preview-{{ $index }}">
                                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                                            @if(!empty($item['image']))
                                                <img src="{{ asset($item['image']) }}" id="preview-{{ $index }}" class="h-100 object-fit-contain rounded" alt="Preview">
                                            @else
                                                <div id="placeholder-{{ $index }}" class="text-muted"><i class="ti tabler-photo"></i></div>
                                                <img src="" id="preview-{{ $index }}" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold small">Tên bác sĩ</label>
                                        <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-sm" value="{{ $item['name'] ?? '' }}" placeholder="Tên bác sĩ">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-bold small">Chuyên khoa</label>
                                        <input type="text" name="items[{{ $index }}][specialty]" class="form-control form-control-sm" value="{{ $item['specialty'] ?? '' }}" placeholder="Chuyên khoa">
                                    </div>
                                    <div class="mb-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label fw-bold small">Text Nút</label>
                                                <input type="text" name="items[{{ $index }}][button_text]" class="form-control form-control-sm" value="{{ $item['button_text'] ?? '' }}" placeholder="Nút">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-bold small">Link Nút</label>
                                                <input type="text" name="items[{{ $index }}][button_link]" class="form-control form-control-sm" value="{{ $item['button_link'] ?? '' }}" placeholder="Link">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top pt-3">
                                        <label class="form-label fw-bold small mb-2 d-block text-center">Mạng xã hội</label>
                                        @foreach(['linkedin', 'facebook', 'twitter', 'youtube'] as $social)
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text"><i class="ti tabler-brand-{{ $social }}"></i></span>
                                            <input type="text" name="items[{{ $index }}][socials][{{ $social }}]" class="form-control" value="{{ $item['socials'][$social] ?? '' }}" placeholder="{{ ucfirst($social) }} link">
                                        </div>
                                        @endforeach
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

<template id="specialist-template">
    <div class="col-md-6 col-lg-3 specialist-item">
        <div class="card h-100 shadow-none border position-relative">
            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-specialist" style="z-index: 1;">
                <i class="ti tabler-trash"></i>
            </button>
            <div class="card-header p-2 bg-primary text-white text-center">
                <small class="fw-bold specialist-index">Bác sĩ New</small>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Ảnh (300x335)</label>
                    <input type="file" name="specialist_images[__INDEX__]" class="form-control form-control-sm img-input" accept="image/*" data-preview="preview-__INDEX__">
                    <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                        <img src="" id="preview-__INDEX__" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                        <div class="text-muted"><i class="ti tabler-photo"></i></div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small">Tên bác sĩ</label>
                    <input type="text" name="items[__INDEX__][name]" class="form-control form-control-sm" placeholder="Tên bác sĩ">
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small">Chuyên khoa</label>
                    <input type="text" name="items[__INDEX__][specialty]" class="form-control form-control-sm" placeholder="Chuyên khoa">
                </div>
                <div class="mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold small">Text Nút</label>
                            <input type="text" name="items[__INDEX__][button_text]" class="form-control form-control-sm" placeholder="Nút">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Link Nút</label>
                            <input type="text" name="items[__INDEX__][button_link]" class="form-control form-control-sm" placeholder="Link">
                        </div>
                    </div>
                </div>
                <div class="border-top pt-3">
                    <label class="form-label fw-bold small mb-2 d-block text-center">Mạng xã hội</label>
                    @foreach(['linkedin', 'facebook', 'twitter', 'youtube'] as $social)
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text"><i class="ti tabler-brand-{{ $social }}"></i></span>
                        <input type="text" name="items[__INDEX__][socials][{{ $social }}]" class="form-control" placeholder="{{ ucfirst($social) }} link">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteSpecialistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá bác sĩ này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-specialist">Xoá</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $(document).on('change', '.img-input', function() {
        const file = this.files[0];
        const previewId = $(this).data('preview');
        const $preview = $('#' + previewId);
        const $placeholder = $preview.siblings('.text-muted');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).removeClass('d-none');
                $placeholder.addClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    $('#add-specialist').on('click', function() {
        const index = $('#specialists-container .specialist-item').length;
        let html = $('#specialist-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#specialists-container').append(html);
        reIndexSpecialists();
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteSpecialistModal'));

    $(document).on('click', '.remove-specialist', function() {
        itemToRemove = $(this).closest('.specialist-item');
        deleteModal.show();
    });

    $('#confirm-delete-specialist').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexSpecialists();
            });
        }
        deleteModal.hide();
    });

    function reIndexSpecialists() {
        $('#specialists-container .specialist-item').each(function(idx) {
            $(this).find('.specialist-index').text('Bác sĩ ' + (idx + 1));
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/items\[\d+\]/, 'items[' + idx + ']')
                                            .replace(/specialist_images\[\d+\]/, 'specialist_images[' + idx + ']'));
                }
            });
            $(this).find('.img-input').attr('data-preview', 'preview-' + idx);
            $(this).find('img').attr('id', 'preview-' + idx);
        });
    }
});
</script>
@endpush
@endsection
