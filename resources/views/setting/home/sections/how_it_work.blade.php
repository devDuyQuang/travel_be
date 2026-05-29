<form action="{{ panel_route('setting.updateHowItWork') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="howTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="how-main-tab" data-bs-toggle="tab" data-bs-target="#how-main" type="button" role="tab" aria-controls="how-main" aria-selected="true">
                Nội dung & Hình ảnh
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="how-button-stats-tab" data-bs-toggle="tab" data-bs-target="#how-button-stats" type="button" role="tab" aria-controls="how-button-stats" aria-selected="false">
                Nút Hẹn & Stats Card
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="how-features-tab" data-bs-toggle="tab" data-bs-target="#how-features" type="button" role="tab" aria-controls="how-features" aria-selected="false">
                Các bước thực hiện
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body pt-4">
          <div class="tab-content">
            {{-- Tab: Nội dung & hình ảnh (3 dòng) --}}
            <div class="tab-pane fade show active" id="how-main" role="tabpanel" aria-labelledby="how-main-tab">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold">Tiêu đề chính</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="How It Work">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Mô tả</label>
                  <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">Ảnh chính (1200x715)</label>
                  <input type="file" name="image_file" class="form-control img-input" data-preview="preview-hiw-main">
                  <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                    <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="preview-hiw-main" class="h-100 w-100 object-fit-contain {{ empty($v['image']) ? 'd-none' : '' }}">
                    @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Nút hẹn + Stats (bảng) --}}
            <div class="tab-pane fade" id="how-button-stats" role="tabpanel" aria-labelledby="how-button-stats-tab">
              <div class="row g-4">
                <div class="col-md-5">
                  <div class="card shadow-none border mb-0">
                    <div class="card-header border-bottom"><h6 class="mb-0">Nút Hẹn (Button)</h6></div>
                    <div class="card-body pt-4">
                      <div class="mb-3"><label class="form-label fw-bold">Text nút</label><input type="text" name="appointment_btn_text" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment"></div>
                      <div class="mb-0"><label class="form-label fw-bold">Link nút</label><input type="text" name="appointment_btn_link" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="card shadow-none border h-100">
                    <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                      <button type="button" class="btn btn-sm btn-primary" id="open-hiw-stat-modal"><i class="ti tabler-plus me-1"></i>Thêm</button>
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-sm">
                          <thead class="table-dark">
                            <tr>
                              <th class="py-1 px-1" style="width: 32px;">#</th>
                              <th class="py-1 px-1">Con số</th>
                              <th class="py-1 px-1">Nhãn</th>
                              <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                            </tr>
                          </thead>
                          <tbody id="hiw-stats-container" class="small">
                            @foreach($v['stats'] ?? [] as $index => $stat)
                            <tr class="hiw-stat-item">
                              <td class="text-muted py-1 px-1 stat-index">{{ $index + 1 }}</td>
                              <td class="py-1 px-1"><span class="cell-number text-break">{{ $stat['number'] ?? '' }}</span><input type="hidden" name="stats[{{ $index }}][number]" value="{{ e($stat['number'] ?? '') }}"></td>
                              <td class="py-1 px-1"><span class="cell-label text-break">{{ $stat['label'] ?? '' }}</span><input type="hidden" name="stats[{{ $index }}][label]" value="{{ e($stat['label'] ?? '') }}"></td>
                              <td class="py-1 px-1 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                  <button type="button" class="btn btn-xs btn-outline-primary edit-hiw-stat btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                  <button type="button" class="btn btn-xs btn-outline-danger remove-hiw-stat btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
                                </div>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Các bước thực hiện (bảng) --}}
            <div class="tab-pane fade" id="how-features" role="tabpanel" aria-labelledby="how-features-tab">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-hiw-feature-modal"><i class="ti tabler-plus me-1"></i>Thêm bước</button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-sm">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1" style="width: 32px;">#</th>
                          <th class="py-1 px-1">Icon Class</th>
                          <th class="py-1 px-1">Tiêu đề bước</th>
                          <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="hiw-features-container" class="small">
                        @foreach($v['features'] ?? [] as $index => $feature)
                        <tr class="hiw-feature-item">
                          <td class="text-muted py-1 px-1 feature-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1"><span class="cell-icon text-break">{{ $feature['icon'] ?? '' }}</span><input type="hidden" name="features[{{ $index }}][icon]" value="{{ e($feature['icon'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-title text-break">{{ $feature['title'] ?? '' }}</span><input type="hidden" name="features[{{ $index }}][title]" value="{{ e($feature['title'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-hiw-feature btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-hiw-feature btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
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

<!-- Modal Thống kê -->
<div class="modal fade" id="hiwStatModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title" id="hiwStatModalTitle">Thêm thống kê</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Con số</label><input type="text" class="form-control" id="modal-hiw-stat-number" placeholder="200+"></div>
        <div class="mb-0"><label class="form-label fw-bold">Nhãn</label><input type="text" class="form-control" id="modal-hiw-stat-label" placeholder="Specialists"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-hiw-stat-modal">Lưu</button></div>
    </div>
  </div>
</div>
<!-- Modal Các bước -->
<div class="modal fade" id="hiwFeatureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title" id="hiwFeatureModalTitle">Thêm bước</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Icon Class</label><input type="text" class="form-control" id="modal-hiw-icon" placeholder="ti tabler-clock"></div>
        <div class="mb-0"><label class="form-label fw-bold">Tiêu đề bước</label><input type="text" class="form-control" id="modal-hiw-title"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-hiw-feature-modal">Lưu</button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteHiwModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá mục này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-hiw">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  #hiw-stats-container .btn-action-icon, #hiw-features-container .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  .table-sm td, .table-sm th { padding: 0.2rem 0.35rem !important; }
</style>
<script>
$(function() {
  $(document).on('change', '.img-input', function() {
    var f = this.files[0], pid = $(this).data('preview'), $p = $('#' + pid);
    if (f) { var r = new FileReader(); r.onload = function(e) { $p.attr('src', e.target.result).removeClass('d-none'); $p.siblings().addClass('d-none'); }; r.readAsDataURL(f); }
  });

  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

  // --- Stats ---
  var editingStatRow = null;
  var statModal = document.getElementById('hiwStatModal') && new bootstrap.Modal(document.getElementById('hiwStatModal'));
  function reindexStats() {
    $('#hiw-stats-container .hiw-stat-item').each(function(idx) {
      $(this).find('.stat-index').text(idx + 1);
      $(this).find('[name^="stats["]').each(function() { var n = $(this).attr('name'); if (n) $(this).attr('name', n.replace(/stats\[\d+\]/, 'stats[' + idx + ']')); });
    });
  }
  $('#open-hiw-stat-modal').on('click', function() {
    editingStatRow = null;
    $('#hiwStatModalTitle').text('Thêm thống kê');
    $('#modal-hiw-stat-number').val(''); $('#modal-hiw-stat-label').val('');
    if (statModal) statModal.show();
  });
  $(document).on('click', '.edit-hiw-stat', function() {
    var $row = $(this).closest('.hiw-stat-item');
    editingStatRow = $row;
    $('#hiwStatModalTitle').text('Sửa thống kê');
    $('#modal-hiw-stat-number').val(($row.find('input[name*="[number]"]').val() || '').trim());
    $('#modal-hiw-stat-label').val(($row.find('input[name*="[label]"]').val() || '').trim());
    if (statModal) statModal.show();
  });
  $('#save-hiw-stat-modal').on('click', function() {
    var number = $('#modal-hiw-stat-number').val();
    var label = $('#modal-hiw-stat-label').val();
    if (editingStatRow && editingStatRow.length) {
      editingStatRow.find('.cell-number').text(number);
      editingStatRow.find('.cell-label').text(label);
      editingStatRow.find('input[name*="[number]"]').val(number);
      editingStatRow.find('input[name*="[label]"]').val(label);
      editingStatRow = null;
      if (statModal) statModal.hide();
      return;
    }
    var i = $('#hiw-stats-container .hiw-stat-item').length;
    var row = '<tr class="hiw-stat-item"><td class="text-muted py-1 px-1 stat-index">' + (i + 1) + '</td><td class="py-1 px-1"><span class="cell-number text-break">' + esc(number) + '</span><input type="hidden" name="stats[' + i + '][number]" value="' + esc(number) + '"></td><td class="py-1 px-1"><span class="cell-label text-break">' + esc(label) + '</span><input type="hidden" name="stats[' + i + '][label]" value="' + esc(label) + '"></td><td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-hiw-stat btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button><button type="button" class="btn btn-xs btn-outline-danger remove-hiw-stat btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#hiw-stats-container').append(row);
    reindexStats();
    if (statModal) statModal.hide();
  });
  var deleteHiwModal = document.getElementById('deleteHiwModal') && new bootstrap.Modal(document.getElementById('deleteHiwModal'));
  var hiwItemToRemove = null;
  $(document).on('click', '.remove-hiw-stat', function() { hiwItemToRemove = $(this).closest('.hiw-stat-item'); if (deleteHiwModal) deleteHiwModal.show(); });
  $('#confirm-delete-hiw').on('click', function() {
    if (hiwItemToRemove) {
      var isStat = hiwItemToRemove.hasClass('hiw-stat-item');
      hiwItemToRemove.remove();
      if (isStat) reindexStats(); else reindexFeatures();
    }
    if (deleteHiwModal) deleteHiwModal.hide();
  });

  // --- Features (các bước) ---
  var editingFeatureRow = null;
  var featureModal = document.getElementById('hiwFeatureModal') && new bootstrap.Modal(document.getElementById('hiwFeatureModal'));
  function reindexFeatures() {
    $('#hiw-features-container .hiw-feature-item').each(function(idx) {
      $(this).find('.feature-index').text(idx + 1);
      $(this).find('[name^="features["]').each(function() { var n = $(this).attr('name'); if (n) $(this).attr('name', n.replace(/features\[\d+\]/, 'features[' + idx + ']')); });
    });
  }
  $('#open-hiw-feature-modal').on('click', function() {
    editingFeatureRow = null;
    $('#hiwFeatureModalTitle').text('Thêm bước');
    $('#modal-hiw-icon').val(''); $('#modal-hiw-title').val('');
    if (featureModal) featureModal.show();
  });
  $(document).on('click', '.edit-hiw-feature', function() {
    var $row = $(this).closest('.hiw-feature-item');
    editingFeatureRow = $row;
    $('#hiwFeatureModalTitle').text('Sửa bước');
    $('#modal-hiw-icon').val($row.find('input[name*="[icon]"]').val());
    $('#modal-hiw-title').val($row.find('input[name*="[title]"]').val());
    if (featureModal) featureModal.show();
  });
  $('#save-hiw-feature-modal').on('click', function() {
    var icon = $('#modal-hiw-icon').val();
    var title = $('#modal-hiw-title').val();
    if (editingFeatureRow && editingFeatureRow.length) {
      editingFeatureRow.find('.cell-icon').text(icon);
      editingFeatureRow.find('.cell-title').text(title);
      editingFeatureRow.find('input[name*="[icon]"]').val(icon);
      editingFeatureRow.find('input[name*="[title]"]').val(title);
      editingFeatureRow = null;
      if (featureModal) featureModal.hide();
      return;
    }
    var i = $('#hiw-features-container .hiw-feature-item').length;
    var row = '<tr class="hiw-feature-item"><td class="text-muted py-1 px-1 feature-index">' + (i + 1) + '</td><td class="py-1 px-1"><span class="cell-icon text-break">' + esc(icon) + '</span><input type="hidden" name="features[' + i + '][icon]" value="' + esc(icon) + '"></td><td class="py-1 px-1"><span class="cell-title text-break">' + esc(title) + '</span><input type="hidden" name="features[' + i + '][title]" value="' + esc(title) + '"></td><td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-hiw-feature btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button><button type="button" class="btn btn-xs btn-outline-danger remove-hiw-feature btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#hiw-features-container').append(row);
    reindexFeatures();
    if (featureModal) featureModal.hide();
  });
  $(document).on('click', '.remove-hiw-feature', function() { hiwItemToRemove = $(this).closest('.hiw-feature-item'); if (deleteHiwModal) deleteHiwModal.show(); });
});
</script>
@endpush
