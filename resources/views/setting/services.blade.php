@extends('index')

@section('content')
<h5 class="card-header">Dịch vụ / Trang chủ</h5>

<div class="card-body">
  <form action="{{ panel_route('setting.updateServices') }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')

    <!-- Section Header Cấu hình -->
    <div class="card mb-4 shadow-none border">
        <div class="card-header border-bottom">
            <h6 class="mb-0">Thông tin tiêu đề Section</h6>
        </div>
        <div class="card-body pt-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tiêu đề chính (Title)</label>
                    <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Ví dụ: Start Feeling Your Best">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tiêu đề phụ (Subtitle)</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ $v['subtitle'] ?? '' }}" placeholder="Ví dụ: Explore Our Wellness Services">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-bold">Link Xem tất cả (View All Link)</label>
                    <input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all_link'] ?? '' }}" placeholder="/services">
                </div>
            </div>
        </div>
    </div>

    <!-- Dịch vụ -->
    <div class="card mb-4 shadow-none border">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Danh sách các dịch vụ (Giao diện 4 cột)</h6>
            <button type="button" class="btn btn-sm btn-primary" id="add-service">
                <i class="ti tabler-plus"></i> Thêm dịch vụ
            </button>
        </div>
        <div class="card-body pt-4">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="services-container">
                @foreach($v['items'] ?? [] as $i => $itemData)
                <div class="col service-item">
                    <div class="card h-100 shadow-none border position-relative">
                        <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-service" style="z-index: 1;">
                            <i class="ti tabler-trash"></i>
                        </button>
                        <div class="card-header bg-label-primary p-2 text-center border-bottom">
                            <span class="fw-bold service-index">Dịch vụ {{ $i + 1 }}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên dịch vụ</label>
                                <input type="text" name="items[{{ $i }}][title]" class="form-control form-control-sm" value="{{ $itemData['title'] }}" placeholder="Ví dụ: Angioplasty">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả</label>
                                <textarea name="items[{{ $i }}][description]" class="form-control form-control-sm" rows="3" placeholder="Nhập mô tả ngắn...">{{ $itemData['description'] }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Text phụ</label>
                                <input type="text" name="items[{{ $i }}][doctor_text]" class="form-control form-control-sm" value="{{ $itemData['doctor_text'] }}" placeholder="Ví dụ: 25+ Doctor">
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Link</label>
                                <input type="text" name="items[{{ $i }}][link]" class="form-control form-control-sm" value="{{ $itemData['link'] ?? '' }}" placeholder="/service-detail">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

<template id="service-template">
    <div class="col service-item">
        <div class="card h-100 shadow-none border position-relative">
            <button type="button" class="btn btn-xs btn-danger position-absolute top-0 end-0 m-2 remove-service" style="z-index: 1;">
                <i class="ti tabler-trash"></i>
            </button>
            <div class="card-header bg-label-primary p-2 text-center border-bottom">
                <span class="fw-bold service-index">Dịch vụ New</span>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên dịch vụ</label>
                    <input type="text" name="items[__INDEX__][title]" class="form-control form-control-sm" placeholder="Ví dụ: Angioplasty">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="items[__INDEX__][description]" class="form-control form-control-sm" rows="3" placeholder="Nhập mô tả ngắn..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Text phụ</label>
                    <input type="text" name="items[__INDEX__][doctor_text]" class="form-control form-control-sm" placeholder="Ví dụ: 25+ Doctor">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-bold">Link</label>
                    <input type="text" name="items[__INDEX__][link]" class="form-control form-control-sm" placeholder="/service-detail">
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Bạn có chắc muốn xoá dịch vụ này không?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-service">Xoá</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#add-service').on('click', function() {
        const index = $('#services-container .service-item').length;
        let html = $('#service-template').html();
        html = html.replace(/__INDEX__/g, index);
        $('#services-container').append(html);
        reIndexServices();
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteServiceModal'));

    $(document).on('click', '.remove-service', function() {
        itemToRemove = $(this).closest('.service-item');
        deleteModal.show();
    });

    $('#confirm-delete-service').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                $(this).remove();
                reIndexServices();
            });
        }
        deleteModal.hide();
    });

    function reIndexServices() {
        $('#services-container .service-item').each(function(idx) {
            $(this).find('.service-index').text('Dịch vụ ' + (idx + 1));
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

    <div class="col-12 mt-4 text-end">
        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
        <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </form>
</div>
</div>
@endsection
