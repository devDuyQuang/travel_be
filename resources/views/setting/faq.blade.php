@extends('index')

@section('content')
<h5 class="card-header">Hỏi đáp / Trang chủ</h5>

<div class="card-body">
<form action="{{ panel_route('setting.updateFaq') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Content & Image -->
        <div class="col-md-7">
            <div class="card h-100 shadow-none border">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Nội dung chính & Hình ảnh</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề chính (H2)</label>
                        <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Frequently Asked Questions">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả (P)</label>
                        <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Ảnh minh họa (700x850)</label>
                        <input type="file" name="image_file" class="form-control img-input" data-preview="preview-faq">
                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden;">
                            <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="preview-faq" class="h-100 w-100 object-fit-contain {{ empty($v['image']) ? 'd-none' : '' }}">
                            @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Appointment -->
        <div class="col-md-5">
            <div class="card shadow-none border mb-4">
                <div class="card-header border-bottom bg-light">
                    <h6 class="mb-0">Thẻ liên hệ & Hẹn lịch (Bottom Card)</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Text liên hệ</label>
                            <input type="text" name="contact[text]" class="form-control" value="{{ $v['contact']['text'] ?? '' }}" placeholder="Contact us">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Số điện thoại</label>
                            <input type="text" name="contact[phone]" class="form-control" value="{{ $v['contact']['phone'] ?? '' }}" placeholder="+1 123 456 7890">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Text nút Appointment</label>
                            <input type="text" name="appointment_btn_text" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Link nút</label>
                            <input type="text" name="appointment_btn_link" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic FAQs -->
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="mb-0">Danh sách câu hỏi (FAQs)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-faq"><i class="ti tabler-plus me-1"></i>Thêm câu hỏi</button>
                </div>
                <div class="card-body pt-4">
                    <div id="faq-container">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <div class="faq-item mb-4 p-3 border rounded bg-light position-relative">
                            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Câu hỏi</label>
                                <input type="text" name="items[{{ $index }}][question]" class="form-control form-control-sm" value="{{ $item['question'] ?? '' }}">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold small">Câu trả lời</label>
                                <textarea name="items[{{ $index }}][answer]" class="form-control form-control-sm" rows="3">{{ $item['answer'] ?? '' }}</textarea>
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
<template id="faq-template">
    <div class="faq-item mb-4 p-3 border rounded bg-light position-relative">
        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-item"><i class="ti tabler-trash"></i></button>
        <div class="mb-2">
            <label class="form-label fw-bold small">Câu hỏi</label>
            <input type="text" name="items[__INDEX__][question]" class="form-control form-control-sm">
        </div>
        <div class="mb-0">
            <label class="form-label fw-bold small">Câu trả lời</label>
            <textarea name="items[__INDEX__][answer]" class="form-control form-control-sm" rows="3"></textarea>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteFaqItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá câu hỏi này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-faq">Xoá</button>
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

    // Add handle
    $('#add-faq').on('click', function() {
        const index = $('#faq-container .faq-item').length;
        let html = $('#faq-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#faq-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteFaqItemModal'));

    $(document).on('click', '.remove-item', function() {
        itemToRemove = $(this).closest('.faq-item');
        deleteModal.show();
    });

    $('#confirm-delete-faq').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexItems();
            });
        }
        deleteModal.hide();
    });

    function reIndexItems() {
        $('#faq-container .faq-item').each(function(idx) {
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
