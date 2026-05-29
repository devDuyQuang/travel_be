<form action="{{ panel_route('setting.updateWhyChooseUs') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="whyTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="why-image-tab" data-bs-toggle="tab" data-bs-target="#why-image" type="button" role="tab" aria-controls="why-image" aria-selected="true">
                Hình ảnh & Kinh nghiệm
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="why-features-tab" data-bs-toggle="tab" data-bs-target="#why-features" type="button" role="tab" aria-controls="why-features" aria-selected="false">
                Tiêu đề & Đặc điểm
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab: Hình ảnh & kinh nghiệm --}}
            <div class="tab-pane fade show active" id="why-image" role="tabpanel" aria-labelledby="why-image-tab">
              <div class="row g-4 align-items-center">
                <div class="col-md-6">
                  <label class="form-label">Chọn ảnh đại diện (vd: 600x600)</label>
                  <input type="file" name="image_file" id="whyus-image-input" class="form-control" accept="image/*">
                  <div id="whyus-image-preview-container" class="mt-3 border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height: 250px; overflow: hidden;">
                    @if(!empty($currentImageUrl))
                      <img src="{{ $currentImageUrl }}" id="whyus-image-preview" class="h-100 object-fit-contain rounded" alt="Preview">
                    @else
                      <div id="whyus-image-placeholder" class="text-muted"><i class="ti tabler-photo ti-lg"></i><br>Chưa có ảnh</div>
                      <img src="" id="whyus-image-preview" class="h-100 object-fit-contain rounded d-none" alt="Preview">
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Con số kinh nghiệm (vd: 20+)</label>
                    <input type="text" name="experience_number" class="form-control" value="{{ $v['experience_number'] ?? '' }}" placeholder="20+">
                  </div>
                  <div>
                    <label class="form-label fw-bold">Nhãn kinh nghiệm</label>
                    <input type="text" name="experience_label" class="form-control" value="{{ $v['experience_label'] ?? '' }}" placeholder="Years Experienced">
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Tiêu đề & Danh sách đặc điểm (bảng) --}}
            <div class="tab-pane fade" id="why-features" role="tabpanel" aria-labelledby="why-features-tab">
              <div class="mb-4">
                <label class="form-label fw-bold">Tiêu đề chính Section</label>
                <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Why Choose Us For Your Health Care Needs">
              </div>
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-feature-modal">
                    <i class="ti tabler-plus"></i> Thêm đặc điểm
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-sm" id="features-table">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1" style="width: 32px;">#</th>
                          <th class="py-1 px-1">Tiêu đề</th>
                          <th class="py-1 px-1">Mô tả</th>
                          <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="features-container" class="small">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <tr class="feature-item">
                          <td class="text-muted py-1 px-1 feature-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1"><span class="cell-title text-break">{{ $item['title'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][title]" value="{{ e($item['title'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-description text-break">{{ Str::limit($item['description'] ?? '', 50) }}</span><input type="hidden" name="items[{{ $index }}][description]" value="{{ e($item['description'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-feature-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-feature-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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

<!-- Modal thêm/sửa đặc điểm -->
<div class="modal fade" id="featureModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="featureModalTitle">Thêm đặc điểm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold">Tiêu đề</label>
            <input type="text" class="form-control" id="modal-feature-title" placeholder="More Experience">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea class="form-control" id="modal-feature-description" rows="3" placeholder="Mô tả..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-feature-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteFeatureModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá đặc điểm này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-feature">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<script>
$(function() {
  $('#whyus-image-input').on('change', function() {
    var f = this.files[0];
    if (f) { var r = new FileReader(); r.onload = function(e) { $('#whyus-image-preview').attr('src', e.target.result).removeClass('d-none'); $('#whyus-image-placeholder').addClass('d-none'); }; r.readAsDataURL(f); }
  });

  var editingFeatureRow = null;
  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexFeatures() {
    $('#features-container .feature-item').each(function(idx) {
      $(this).find('.feature-index').text(idx + 1);
      $(this).find('[name^="items["]').each(function() {
        var n = $(this).attr('name');
        if (!n) return;
        $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']'));
      });
    });
  }
  function setFeatureRowDisplay($row, data) {
    $row.find('.cell-title').text(data.title || '');
    $row.find('.cell-description').text((data.description || '').substring(0, 50) + ((data.description || '').length > 50 ? '...' : ''));
    $row.find('input[name*="[title]"]').val(data.title || '');
    $row.find('input[name*="[description]"]').val(data.description || '');
  }

  var featureModalEl = document.getElementById('featureModal');
  var featureModal = featureModalEl ? new bootstrap.Modal(featureModalEl) : null;

  $('#open-feature-modal').on('click', function() {
    editingFeatureRow = null;
    $('#featureModalTitle').text('Thêm đặc điểm');
    $('#modal-feature-title').val('');
    $('#modal-feature-description').val('');
    if (featureModal) featureModal.show();
  });

  $(document).on('click', '.edit-feature-item', function() {
    var $row = $(this).closest('.feature-item');
    editingFeatureRow = $row;
    $('#featureModalTitle').text('Sửa đặc điểm');
    $('#modal-feature-title').val($row.find('input[name*="[title]"]').val());
    $('#modal-feature-description').val($row.find('input[name*="[description]"]').val());
    if (featureModal) featureModal.show();
  });

  $('#save-feature-modal').on('click', function() {
    var title = $('#modal-feature-title').val();
    var description = $('#modal-feature-description').val();
    var data = { title: title, description: description };

    if (editingFeatureRow && editingFeatureRow.length) {
      setFeatureRowDisplay(editingFeatureRow, data);
      editingFeatureRow = null;
      if (featureModal) featureModal.hide();
      return;
    }

    var i = $('#features-container .feature-item').length;
    var rowHtml = '<tr class="feature-item">'
      + '<td class="text-muted py-1 px-1 feature-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-1"><span class="cell-title text-break">' + esc(title) + '</span><input type="hidden" name="items[' + i + '][title]" value="' + esc(title) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-description text-break">' + esc(description).substring(0, 50) + (description.length > 50 ? '...' : '') + '</span><input type="hidden" name="items[' + i + '][description]" value="' + esc(description) + '"></td>'
      + '<td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-feature-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger remove-feature-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#features-container').append(rowHtml);
    reindexFeatures();
    if (featureModal) featureModal.hide();
  });

  var deleteModal = document.getElementById('deleteFeatureModal') && new bootstrap.Modal(document.getElementById('deleteFeatureModal'));
  var featureToRemove = null;
  $(document).on('click', '.remove-feature-item', function() { featureToRemove = $(this).closest('.feature-item'); if (deleteModal) deleteModal.show(); });
  $('#confirm-delete-feature').on('click', function() {
    if (featureToRemove) { featureToRemove.remove(); reindexFeatures(); }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
<style>
  #features-table td, #features-table th { padding: 0.2rem 0.35rem !important; vertical-align: middle !important; }
  #features-table .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
</style>
@endpush
