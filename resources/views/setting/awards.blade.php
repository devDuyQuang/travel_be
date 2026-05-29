@extends('index')

@section('content')
<h5 class="card-header">Giải thưởng / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateAwards') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Content & Years -->
        <div class="col-md-5">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Thông tin chung & Bộ lọc</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề section</label>
                        <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Awards">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ $v['description'] ?? '' }}</textarea>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold mb-0">Bộ lọc năm (Years filter)</label>
                            <button type="button" class="btn btn-xs btn-primary" id="add-year"><i class="ti tabler-plus"></i></button>
                        </div>
                        <div id="years-container" class="d-flex flex-wrap gap-2">
                            @foreach($v['years'] ?? [] as $index => $year)
                            <div class="year-item input-group input-group-sm" style="width: 120px;">
                                <input type="text" name="years[]" class="form-control" value="{{ $year }}">
                                <button type="button" class="btn btn-outline-danger remove-year"><i class="ti tabler-x"></i></button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Awards List -->
        <div class="col-md-7">
            <div class="card shadow-none border h-100">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Danh sách các giải thưởng</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-award"><i class="ti tabler-plus me-1"></i>Thêm giải thưởng</button>
                </div>
                <div class="card-body pt-4">
                    <div id="awards-container">
                        @foreach($v['items'] ?? [] as $index => $award)
                        <div class="award-item mb-4 p-3 border rounded bg-light position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Badge Image</label>
                                    <input type="file" name="award_images[{{ $index }}]" class="form-control form-control-sm img-input" data-preview="preview-award-{{ $index }}">
                                    <div class="mt-2 border rounded bg-white p-2 d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                                        <img src="{{ !empty($award['image']) ? asset($award['image']) : '' }}" id="preview-award-{{ $index }}" class="h-100 w-100 object-fit-contain {{ empty($award['image']) ? 'd-none' : '' }}">
                                        @if(empty($award['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Tiêu đề giải thưởng</label>
                                        <input type="text" name="items[{{ $index }}][title]" class="form-control form-control-sm" value="{{ $award['title'] ?? '' }}" placeholder="ClinicMaster 2024">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Phụ đề</label>
                                        <input type="text" name="items[{{ $index }}][subtitle]" class="form-control form-control-sm" value="{{ $award['subtitle'] ?? '' }}">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-7">
                                            <label class="form-label small fw-bold">Text liên kết</label>
                                            <input type="text" name="items[{{ $index }}][link_text]" class="form-control form-control-sm" value="{{ $award['link_text'] ?? '' }}" placeholder="Save the Childern">
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label small fw-bold">Phân loại năm</label>
                                            <input type="text" name="items[{{ $index }}][year]" class="form-control form-control-sm" value="{{ $award['year'] ?? '' }}" placeholder="2024">
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

<!-- Templates -->
<template id="year-template">
    <div class="year-item input-group input-group-sm" style="width: 120px;">
        <input type="text" name="years[]" class="form-control">
        <button type="button" class="btn btn-outline-danger remove-year"><i class="ti tabler-x"></i></button>
    </div>
</template>

<template id="award-template">
    <div class="award-item mb-4 p-3 border rounded bg-light position-relative">
        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Badge Image</label>
                <input type="file" name="award_images[__INDEX__]" class="form-control form-control-sm img-input" data-preview="preview-award-__INDEX__">
                <div class="mt-2 border rounded bg-white p-2 d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                    <img id="preview-award-__INDEX__" class="h-100 w-100 object-fit-contain d-none">
                    <i class="ti tabler-photo ti-lg text-muted"></i>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-2">
                    <label class="form-label small fw-bold">Tiêu đề giải thưởng</label>
                    <input type="text" name="items[__INDEX__][title]" class="form-control form-control-sm">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Phụ đề</label>
                    <input type="text" name="items[__INDEX__][subtitle]" class="form-control form-control-sm">
                </div>
                <div class="row g-3">
                    <div class="col-7">
                        <label class="form-label small fw-bold">Text liên kết</label>
                        <input type="text" name="items[__INDEX__][link_text]" class="form-control form-control-sm">
                    </div>
                    <div class="col-5">
                        <label class="form-label small fw-bold">Phân loại năm</label>
                        <input type="text" name="items[__INDEX__][year]" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteAwardItemModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn btn-danger" id="confirm-delete-award">Xoá</button>
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

    // Year management
    $('#add-year').on('click', function() {
        $('#years-container').append($('#year-template').html());
    });
    $(document).on('click', '.remove-year', function() {
        $(this).closest('.year-item').remove();
    });

    // Add handle
    $('#add-award').on('click', function() {
        const index = $('#awards-container .award-item').length;
        let html = $('#award-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#awards-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAwardItemModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.award-item');
        deleteModal.show();
    });

    $('#confirm-delete-award').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexItems();
            });
        }
        deleteModal.hide();
    });

    function reIndexItems() {
        $('#awards-container .award-item').each(function(idx) {
            $(this).find('[name]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/items\[\d+\]/, 'items[' + idx + ']')
                                            .replace(/award_images\[\d+\]/, 'award_images[' + idx + ']'));
                }
                if ($(this).hasClass('img-input')) {
                    $(this).attr('data-preview', 'preview-award-' + idx);
                    $(this).siblings('div').find('img').attr('id', 'preview-award-' + idx);
                }
            });
        });
    }
});
</script>
@endpush
@endsection
