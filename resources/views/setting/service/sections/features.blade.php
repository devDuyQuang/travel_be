<form action="{{ panel_route('setting.updateServiceFeatures') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')
  <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
  <input type="hidden" name="tab" value="features">

  <div class="card mb-4 shadow-none border">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Danh sách tính năng dùng chung</h6>
      <button type="button" class="btn btn-sm btn-primary" id="add-feature-pool-item">
        <i class="ti tabler-plus"></i> Thêm tính năng
      </button>
    </div>
    <div class="card-body p-3">
      <p class="text-muted small mb-3">Thêm các tính năng ở đây, sau đó bạn có thể chọn chúng trong mục "Bảng giá dịch vụ".</p>
      
      <div id="features-pool-container" class="row g-2">
        @foreach($v['items'] ?? [] as $index => $item)
          <div class="col-md-4 feature-pool-item">
            <div class="input-group input-group-sm">
              <input type="text" name="items[]" class="form-control" value="{{ e($item) }}" placeholder="Tên tính năng...">
              <button type="button" class="btn btn-outline-danger remove-feature-pool-item"><i class="ti tabler-trash"></i></button>
            </div>
          </div>
        @endforeach
      </div>

      @if(empty($v['items']))
        <div id="no-features-msg" class="text-center py-4 text-muted">
            <i class="ti tabler-info-circle d-block mb-1 opacity-50" style="font-size: 2rem;"></i>
            Chưa có tính năng nào. Hãy bấm "Thêm tính năng" để bắt đầu.
        </div>
      @endif
    </div>
  </div>

  <div class="col-12 mt-3 text-end">
    <button type="submit" class="btn btn-primary me-2">Lưu danh sách</button>
    <button type="reset" class="btn btn-label-secondary">Hủy</button>
  </div>
</form>

@push('scripts')
<script>
$(function() {
    $('#add-feature-pool-item').on('click', function() {
        $('#no-features-msg').hide();
        var html = '<div class="col-md-4 feature-pool-item">'
            + '<div class="input-group input-group-sm">'
            + '<input type="text" name="items[]" class="form-control" value="" placeholder="Tên tính năng...">'
            + '<button type="button" class="btn btn-outline-danger remove-feature-pool-item"><i class="ti tabler-trash"></i></button>'
            + '</div></div>';
        $('#features-pool-container').append(html);
    });

    $(document).on('click', '.remove-feature-pool-item', function() {
        $(this).closest('.feature-pool-item').remove();
        if ($('#features-pool-container .feature-pool-item').length === 0) {
            $('#no-features-msg').show();
        }
    });
});
</script>
@endpush
