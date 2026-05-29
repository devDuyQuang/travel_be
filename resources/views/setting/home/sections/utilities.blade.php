<form action="{{ panel_route('setting.updateUtilities') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent p-0">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="utilitiesTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="utilities-images-tab" data-bs-toggle="tab" data-bs-target="#utilities-images" type="button" role="tab" aria-controls="utilities-images" aria-selected="true">
                Hình ảnh Tiện ích
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="utilities-open-hours-tab" data-bs-toggle="tab" data-bs-target="#utilities-open-hours" type="button" role="tab" aria-controls="utilities-open-hours" aria-selected="false">
                Giờ mở cửa
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="utilities-content-tab" data-bs-toggle="tab" data-bs-target="#utilities-content" type="button" role="tab" aria-controls="utilities-content" aria-selected="false">
                Thông tin nội dung
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="utilities-list-tab" data-bs-toggle="tab" data-bs-target="#utilities-list" type="button" role="tab" aria-controls="utilities-list" aria-selected="false">
                Danh sách tiện ích
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab: Hình ảnh tiện ích --}}
            <div class="tab-pane fade show active" id="utilities-images" role="tabpanel" aria-labelledby="utilities-images-tab">
              <div class="row g-3">
                <div class="col-md-6"><x-file-input label="Ảnh 1 (300x400)" name="image_one_file" :multiple="false" :current-url="$currentImageOneUrl ?? null" /></div>
                <div class="col-md-6"><x-file-input label="Ảnh 2 (795x940)" name="image_two_file" :multiple="false" :current-url="$currentImageTwoUrl ?? null" /></div>
              </div>
            </div>

            {{-- Tab: Giờ mở cửa --}}
            <div class="tab-pane fade" id="utilities-open-hours" role="tabpanel" aria-labelledby="utilities-open-hours-tab">
              <div class="d-flex justify-content-end align-items-center mb-3">
                <button type="button" class="btn btn-sm btn-primary" id="add-open-hour"><i class="ti tabler-plus"></i> Thêm giờ</button>
              </div>
              <div id="open-hours-container">
                @foreach($v['open_hours'] ?? [] as $index => $hour)
                <div class="input-group mb-2 open-hour-row">
                  <input type="text" name="open_hours[{{ $index }}][day]" class="form-control" value="{{ $hour['day'] ?? '' }}" placeholder="Thứ">
                  <input type="text" name="open_hours[{{ $index }}][time]" class="form-control" value="{{ $hour['time'] ?? '' }}" placeholder="Giờ">
                  <button class="btn btn-outline-danger remove-open-hour" type="button"><i class="ti tabler-trash"></i></button>
                </div>
                @endforeach
              </div>
            </div>

            {{-- Tab: Thông tin nội dung --}}
            <div class="tab-pane fade" id="utilities-content" role="tabpanel" aria-labelledby="utilities-content-tab">
              <div class="row g-3">
                <div class="col-md-12"><x-input-field name="title" label="Tiêu đề" :value="old('title', data_get($item->value, 'title'))" /></div>
                <div class="col-md-12"><x-textarea-field name="description" label="Mô tả" :value="old('description', data_get($item->value, 'description'))" rows="3" /></div>
                <div class="col-md-6"><x-input-field name="button_text" label="Text Button" :value="old('button_text', data_get($item->value, 'button_text'))" /></div>
                <div class="col-md-6"><x-input-field name="button_link" label="Link Button" :value="old('button_link', data_get($item->value, 'button_link'))" /></div>
                <div class="col-md-12"><x-input-field name="phone" label="Số điện thoại (Phone)" :value="old('phone', data_get($item->value, 'phone'))" /></div>
              </div>
            </div>

            {{-- Tab: Danh sách tiện ích --}}
            <div class="tab-pane fade" id="utilities-list" role="tabpanel" aria-labelledby="utilities-list-tab">
              <div class="d-flex justify-content-end align-items-center mb-3">
                <button type="button" class="btn btn-sm btn-primary" id="add-utility"><i class="ti tabler-plus"></i> Thêm tiện ích</button>
              </div>
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
      </div>
    </div>
    <div class="col-12 mt-3 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>
<div class="modal fade" id="deleteUtilityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Xác nhận xoá</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">Bạn có chắc muốn xoá mục này không?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-utility">Xoá</button>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script>
$(function() {
  $('#add-open-hour').on('click', function() {
    var i = $('#open-hours-container .open-hour-row').length;
    $('#open-hours-container').append(
      '<div class="input-group mb-2 open-hour-row">' +
      '<input type="text" name="open_hours['+i+'][day]" class="form-control" placeholder="Thứ">' +
      '<input type="text" name="open_hours['+i+'][time]" class="form-control" placeholder="Giờ">' +
      '<button class="btn btn-outline-danger remove-open-hour" type="button"><i class="ti tabler-trash"></i></button></div>'
    );
  });
  $('#add-utility').on('click', function() {
    $('#utilities-container').append(
      '<div class="input-group mb-2 utility-row"><input type="text" name="utilities[]" class="form-control" placeholder="Nhập tiện ích...">' +
      '<button class="btn btn-outline-danger remove-utility" type="button"><i class="ti tabler-trash"></i></button></div>'
    );
  });
  var itemToRemove = null;
  var deleteModal = document.getElementById('deleteUtilityModal') && new bootstrap.Modal(document.getElementById('deleteUtilityModal'));
  $(document).on('click', '.remove-utility, .remove-open-hour', function() { itemToRemove = $(this).closest('.utility-row, .open-hour-row'); if (deleteModal) deleteModal.show(); });
  $('#confirm-delete-utility').on('click', function() {
    if (itemToRemove) itemToRemove.remove();
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
