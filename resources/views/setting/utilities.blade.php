@extends('index')
@section('title', 'Tiện ích / Trang Chủ')

@section('content')
<h5 class="card-header">Cấu hình Tiện ích / Trang Chủ</h5>

<div class="card-body">
  <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
  
  <form action="{{ panel_route('setting.updateUtilities') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Images Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Hình ảnh Tiện ích</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <x-file-input label="Ảnh 1 (300x400)" name="image_one_file" :multiple="false" :current-url="$currentImageOneUrl ?? null" />
                        </div>
                        <div class="col-md-6">
                            <x-file-input label="Ảnh 2 (795x940)" name="image_two_file" :multiple="false" :current-url="$currentImageTwoUrl ?? null" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Open Hours Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Giờ mở cửa (Open Hours)</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-open-hour">
                        <i class="ti tabler-plus"></i> Thêm giờ
                    </button>
                </div>
                <div class="card-body pt-4">
                    <div id="open-hours-container">
                        @foreach($v['open_hours'] ?? [] as $index => $hour)
                        <div class="input-group mb-2 open-hour-row">
                            <input type="text" name="open_hours[{{ $index }}][day]" class="form-control" value="{{ $hour['day'] ?? '' }}" placeholder="Thứ (Ví dụ: Monday)">
                            <input type="text" name="open_hours[{{ $index }}][time]" class="form-control" value="{{ $hour['time'] ?? '' }}" placeholder="Giờ (Ví dụ: 09:00 - 18:00)">
                            <button class="btn btn-outline-danger remove-open-hour" type="button"><i class="ti tabler-trash"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom">
                    <h6 class="mb-0">Thông tin nội dung</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <x-input-field name="title" label="Tiêu đề (Title)" :value="old('title', data_get($item->value, 'title'))" />
                        </div>
                        <div class="col-md-12">
                            <x-textarea-field name="description" label="Mô tả (Description)" :value="old('description', data_get($item->value, 'description'))" rows="3" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_text" label="Text Button" :value="old('button_text', data_get($item->value, 'button_text'))" />
                        </div>
                        <div class="col-md-6">
                            <x-input-field name="button_link" label="Link Button" :value="old('button_link', data_get($item->value, 'button_link'))" />
                        </div>
                        <div class="col-md-12">
                            <x-input-field name="phone" label="Số điện thoại (Phone)" :value="old('phone', data_get($item->value, 'phone'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilities List Section -->
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Danh sách tiện ích</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-utility">
                        <i class="ti tabler-plus"></i> Thêm tiện ích
                    </button>
                </div>
                <div class="card-body pt-4">
                    <div id="utilities-container">
                        @foreach($v['utilities'] ?? [] as $index => $util)
                        <div class="input-group mb-2 utility-row">
                            <input type="text" name="utilities[]" class="form-control" value="{{ $util }}" placeholder="Nhập tiện ích...">
                            <button class="btn btn-outline-danger remove-utility" type="button"><i class="ti tabler-trash"></i></button>
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
<div class="modal fade" id="deleteUtilityModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn btn-danger" id="confirm-delete-utility">Xoá</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Add Open Hour
    $('#add-open-hour').on('click', function() {
        const index = $('#open-hours-container .open-hour-row').length;
        const html = `
            <div class="input-group mb-2 open-hour-row">
                <input type="text" name="open_hours[${index}][day]" class="form-control" placeholder="Thứ (Ví dụ: Monday)">
                <input type="text" name="open_hours[${index}][time]" class="form-control" placeholder="Giờ (Ví dụ: 09:00 - 18:00)">
                <button class="btn btn-outline-danger remove-open-hour" type="button"><i class="ti tabler-trash"></i></button>
            </div>`;
        $('#open-hours-container').append(html);
    });

    // Add Utility
    $('#add-utility').on('click', function() {
        const html = `
            <div class="input-group mb-2 utility-row">
                <input type="text" name="utilities[]" class="form-control" placeholder="Nhập tiện ích...">
                <button class="btn btn-outline-danger remove-utility" type="button"><i class="ti tabler-trash"></i></button>
            </div>`;
        $('#utilities-container').append(html);
    });

    // Custom Delete Logic
    let itemToRemove = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUtilityModal'));

    $(document).on('click', '.remove-utility, .remove-open-hour', function() {
        itemToRemove = $(this).closest('.utility-row, .open-hour-row');
        deleteModal.show();
    });

    $('#confirm-delete-utility').on('click', function() {
        if (itemToRemove) {
            itemToRemove.fadeOut(300, function() {
                const container = $(this).parent();
                $(this).remove();
                if (container.attr('id') === 'open-hours-container') {
                    reIndexOpenHours();
                }
            });
        }
        deleteModal.hide();
    });

    function reIndexOpenHours() {
        $('#open-hours-container .open-hour-row').each(function(idx) {
            $(this).find('input').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/open_hours\[\d+\]/, 'open_hours[' + idx + ']'));
                }
            });
        });
    }
});
</script>
@endpush
@endsection
