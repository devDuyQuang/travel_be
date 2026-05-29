<form action="{{ panel_route('setting.updateStats') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="statsTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="tab-banner-tab" data-bs-toggle="tab" data-bs-target="#tab-banner" type="button" role="tab" aria-controls="tab-banner" aria-selected="true">
                Ảnh nền Banner
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-avatar-tab" data-bs-toggle="tab" data-bs-target="#tab-avatar" type="button" role="tab" aria-controls="tab-avatar" aria-selected="false">
                Nhóm Avatar
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-stats-list-tab" data-bs-toggle="tab" data-bs-target="#tab-stats-list" type="button" role="tab" aria-controls="tab-stats-list" aria-selected="false">
                Danh sách Thống kê
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab 1: Ảnh nền Banner --}}
            <div class="tab-pane fade show active" id="tab-banner" role="tabpanel" aria-labelledby="tab-banner-tab">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <label class="form-label">Chọn ảnh nền (1920x800)</label>
                  <input type="file" name="background" id="bg-input" class="form-control" accept="image/*">
                </div>
                <div class="col-md-4 text-center">
                  <div id="bg-preview-container" class="border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height: 150px; overflow: hidden;">
                    @if(!empty($backgroundUrl))
                      <img src="{{ $backgroundUrl }}" id="bg-preview" class="w-100 h-100 object-fit-cover rounded" alt="Background">
                    @else
                      <div id="bg-placeholder" class="text-muted"><i class="ti tabler-photo ti-lg"></i><br>Chưa có ảnh</div>
                      <img src="" id="bg-preview" class="w-100 h-100 object-fit-cover rounded d-none" alt="Background">
                    @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab 2: Nhóm Avatar --}}
            <div class="tab-pane fade" id="tab-avatar" role="tabpanel" aria-labelledby="tab-avatar-tab">
              <div class="mb-3">
                <label class="form-label">Tiêu đề nhóm</label>
                <input type="text" name="avatar_group_text" class="form-control" value="{{ $v['avatar_group']['text'] ?? '' }}" placeholder="Ví dụ: 300+ Appointment Booking...">
              </div>
              <div class="mb-2">
                <label class="form-label">Upload thêm ảnh</label>
                <input type="file" name="avatar_group_files[]" class="form-control" multiple accept="image/*">
              </div>
              @if(!empty($currentAvatarUrls))
                <label class="form-label">Ảnh hiện tại:</label>
                <div class="row g-2">
                  @foreach($currentAvatarUrls as $index => $url)
                    <div class="col-6 col-md-3 avatar-item">
                      <div class="card shadow-none border overflow-hidden">
                        <img src="{{ $url }}" class="w-100" style="height: 80px; object-fit: cover;" alt="Avatar">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete w-100" data-index="{{ $index }}" data-url="{{ panel_route('setting.removeStatAvatar', ['index' => $index]) }}"><i class="ti tabler-trash"></i> Xóa</button>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>

            {{-- Tab 3: Danh sách Thống kê (bảng) --}}
            <div class="tab-pane fade" id="tab-stats-list" role="tabpanel" aria-labelledby="tab-stats-list-tab">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-primary btn-sm" id="open-stat-modal"><i class="ti tabler-plus"></i> Thêm mục</button>
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
                      <tbody id="stats-container" class="small">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <tr class="stat-item">
                          <td class="text-muted py-1 px-1 stat-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1"><span class="cell-number text-break">{{ $item['number'] ?? '' }}</span><input type="hidden" name="stat_numbers[]" value="{{ e($item['number'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-label text-break">{{ $item['label'] ?? '' }}</span><input type="hidden" name="stat_labels[]" value="{{ e($item['label'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-stat-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-stat-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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

<!-- Modal thêm/sửa thống kê -->
<div class="modal fade" id="statModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statModalTitle">Thêm mục thống kê</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Con số</label><input type="text" class="form-control" id="modal-stat-number" placeholder="200+"></div>
        <div class="mb-0"><label class="form-label fw-bold">Nhãn</label><input type="text" class="form-control" id="modal-stat-label" placeholder="Specialists"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-stat-modal">Lưu</button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteStatModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-stat">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  #stats-container .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  .table-sm td, .table-sm th { padding: 0.2rem 0.35rem !important; }
</style>
<script>
$(function() {
  $('#bg-input').on('change', function() {
    var f = this.files[0];
    if (f) { var r = new FileReader(); r.onload = function(e) { $('#bg-preview').attr('src', e.target.result).removeClass('d-none'); $('#bg-placeholder').addClass('d-none'); }; r.readAsDataURL(f); }
  });

  var editingStatRow = null;
  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexStats() {
    $('#stats-container .stat-item').each(function(idx) {
      $(this).find('.stat-index').text(idx + 1);
    });
  }

  var statModalEl = document.getElementById('statModal');
  var statModal = statModalEl ? new bootstrap.Modal(statModalEl) : null;

  $('#open-stat-modal').on('click', function() {
    editingStatRow = null;
    $('#statModalTitle').text('Thêm mục thống kê');
    $('#modal-stat-number').val('');
    $('#modal-stat-label').val('');
    if (statModal) statModal.show();
  });

  $(document).on('click', '.edit-stat-item', function() {
    var $row = $(this).closest('.stat-item');
    editingStatRow = $row;
    $('#statModalTitle').text('Sửa mục thống kê');
    $('#modal-stat-number').val($row.find('input[name="stat_numbers[]"]').val());
    $('#modal-stat-label').val($row.find('input[name="stat_labels[]"]').val());
    if (statModal) statModal.show();
  });

  $('#save-stat-modal').on('click', function() {
    var number = $('#modal-stat-number').val();
    var label = $('#modal-stat-label').val();
    if (editingStatRow && editingStatRow.length) {
      editingStatRow.find('.cell-number').text(number);
      editingStatRow.find('.cell-label').text(label);
      editingStatRow.find('input[name="stat_numbers[]"]').val(number);
      editingStatRow.find('input[name="stat_labels[]"]').val(label);
      editingStatRow = null;
      if (statModal) statModal.hide();
      return;
    }
    var row = '<tr class="stat-item"><td class="text-muted py-1 px-1 stat-index">' + ($('#stats-container .stat-item').length + 1) + '</td><td class="py-1 px-1"><span class="cell-number text-break">' + esc(number) + '</span><input type="hidden" name="stat_numbers[]" value="' + esc(number) + '"></td><td class="py-1 px-1"><span class="cell-label text-break">' + esc(label) + '</span><input type="hidden" name="stat_labels[]" value="' + esc(label) + '"></td><td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-stat-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button><button type="button" class="btn btn-xs btn-outline-danger remove-stat-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#stats-container').append(row);
    reindexStats();
    if (statModal) statModal.hide();
  });

  var deleteModal = document.getElementById('deleteStatModal') && new bootstrap.Modal(document.getElementById('deleteStatModal'));
  var statToRemove = null;
  $(document).on('click', '.remove-stat-item', function() { statToRemove = $(this).closest('.stat-item'); if (deleteModal) deleteModal.show(); });
  $(document).on('click', '.btn-delete', function() { statToRemove = $(this).closest('.avatar-item'); if (deleteModal) deleteModal.show(); });
  $('#confirm-delete-stat').on('click', function() {
    if (statToRemove) { statToRemove.remove(); if (statToRemove.hasClass('stat-item')) reindexStats(); }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
