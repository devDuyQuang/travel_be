<form action="{{ panel_route('setting.updateServices') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="card mb-4 shadow-none border">
    <div class="card-header border-bottom">
      <ul class="nav nav-tabs card-header-tabs" id="servicesTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="services-info-tab" data-bs-toggle="tab" data-bs-target="#services-info" type="button" role="tab" aria-controls="services-info" aria-selected="true">
            Thông tin tiêu đề Section
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="services-list-tab" data-bs-toggle="tab" data-bs-target="#services-list" type="button" role="tab" aria-controls="services-list" aria-selected="false">
            Danh sách dịch vụ
          </button>
        </li>
      </ul>
    </div>
    <div class="card-body p-3">
      <div class="tab-content">
        {{-- Tab: Thông tin Section --}}
        <div class="tab-pane fade show active" id="services-info" role="tabpanel" aria-labelledby="services-info-tab">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Tiêu đề chính</label>
              <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Start Feeling Your Best">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Tiêu đề phụ (Subtitle)</label>
              <input type="text" name="subtitle" class="form-control" value="{{ $v['subtitle'] ?? '' }}" placeholder="Explore Our Wellness Services">
            </div>
            <div class="col-md-12">
              <label class="form-label fw-bold">Link Xem tất cả</label>
              <input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all_link'] ?? '' }}" placeholder="/services">
            </div>
          </div>
        </div>

        {{-- Tab: Danh sách dịch vụ (bảng) --}}
        <div class="tab-pane fade" id="services-list" role="tabpanel" aria-labelledby="services-list-tab">
          <div class="card shadow-none border">
            <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
              <button type="button" class="btn btn-sm btn-primary" id="open-service-modal">
                <i class="ti tabler-plus"></i> Thêm dịch vụ
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-sm" id="services-table">
                  <thead class="table-dark">
                    <tr>
                      <th class="py-1 px-1" style="width: 32px;">#</th>
                      <th class="py-1 px-1">Tên dịch vụ</th>
                      <th class="py-1 px-1">Mô tả</th>
                      <th class="py-1 px-1">Text phụ</th>
                      <th class="py-1 px-1">Link</th>
                      <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody id="services-container" class="small">
                    @foreach($v['items'] ?? [] as $index => $item)
                    <tr class="service-item">
                      <td class="text-muted py-1 px-1 service-index">{{ $index + 1 }}</td>
                      <td class="py-1 px-1"><span class="cell-title text-break">{{ $item['title'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][title]" value="{{ e($item['title'] ?? '') }}"></td>
                      <td class="py-1 px-1"><span class="cell-description text-break">{{ Str::limit($item['description'] ?? '', 40) }}</span><input type="hidden" name="items[{{ $index }}][description]" value="{{ e($item['description'] ?? '') }}"></td>
                      <td class="py-1 px-1"><span class="cell-doctor-text text-break">{{ $item['doctor_text'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][doctor_text]" value="{{ e($item['doctor_text'] ?? '') }}"></td>
                      <td class="py-1 px-1"><span class="cell-link text-break">{{ $item['link'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][link]" value="{{ e($item['link'] ?? '') }}"></td>
                      <td class="py-1 px-1 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                          <button type="button" class="btn btn-xs btn-outline-primary edit-service-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                          <button type="button" class="btn btn-xs btn-outline-danger remove-service-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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

  <div class="col-12 mt-3 text-end">
    <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
    <button type="reset" class="btn btn-label-secondary">Hủy</button>
  </div>
</form>

<!-- Modal thêm/sửa dịch vụ -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalTitle">Thêm dịch vụ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold">Tên dịch vụ</label>
            <input type="text" class="form-control" id="modal-service-title">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Mô tả</label>
            <textarea class="form-control" id="modal-service-description" rows="3"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Text phụ</label>
            <input type="text" class="form-control" id="modal-service-doctor-text">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Link</label>
            <input type="text" class="form-control" id="modal-service-link">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-service-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteServiceModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá dịch vụ này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-service">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  #services-table td, #services-table th { padding: 0.2rem 0.35rem !important; vertical-align: middle !important; }
  #services-table .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
</style>
<script>
$(function() {
  var editingServiceRow = null;

  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexServices() {
    $('#services-container .service-item').each(function(idx) {
      $(this).find('.service-index').text(idx + 1);
      $(this).find('[name^="items["]').each(function() {
        var n = $(this).attr('name');
        if (!n) return;
        $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']'));
      });
    });
  }
  function setServiceRowDisplay($row, data) {
    $row.find('.cell-title').text(data.title || '');
    $row.find('.cell-description').text((data.description || '').substring(0, 40) + ((data.description || '').length > 40 ? '...' : ''));
    $row.find('.cell-doctor-text').text(data.doctor_text || '');
    $row.find('.cell-link').text(data.link || '');
    $row.find('input[name*="[title]"]').val(data.title || '');
    $row.find('input[name*="[description]"]').val(data.description || '');
    $row.find('input[name*="[doctor_text]"]').val(data.doctor_text || '');
    $row.find('input[name*="[link]"]').val(data.link || '');
  }

  var serviceModalEl = document.getElementById('serviceModal');
  var serviceModal = serviceModalEl ? new bootstrap.Modal(serviceModalEl) : null;

  $('#open-service-modal').on('click', function() {
    editingServiceRow = null;
    $('#serviceModalTitle').text('Thêm dịch vụ');
    $('#modal-service-title').val('');
    $('#modal-service-description').val('');
    $('#modal-service-doctor-text').val('');
    $('#modal-service-link').val('');
    if (serviceModal) serviceModal.show();
  });

  $(document).on('click', '.edit-service-item', function() {
    var $row = $(this).closest('.service-item');
    editingServiceRow = $row;
    $('#serviceModalTitle').text('Sửa dịch vụ');
    $('#modal-service-title').val($row.find('input[name*="[title]"]').val());
    $('#modal-service-description').val($row.find('input[name*="[description]"]').val());
    $('#modal-service-doctor-text').val($row.find('input[name*="[doctor_text]"]').val());
    $('#modal-service-link').val($row.find('input[name*="[link]"]').val());
    if (serviceModal) serviceModal.show();
  });

  $('#save-service-modal').on('click', function() {
    var title = $('#modal-service-title').val();
    var description = $('#modal-service-description').val();
    var doctorText = $('#modal-service-doctor-text').val();
    var link = $('#modal-service-link').val();
    var data = { title: title, description: description, doctor_text: doctorText, link: link };

    if (editingServiceRow && editingServiceRow.length) {
      setServiceRowDisplay(editingServiceRow, data);
      editingServiceRow = null;
      if (serviceModal) serviceModal.hide();
      return;
    }

    var i = $('#services-container .service-item').length;
    var rowHtml = '<tr class="service-item">'
      + '<td class="text-muted py-1 px-1 service-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-1"><span class="cell-title text-break">' + esc(title) + '</span><input type="hidden" name="items[' + i + '][title]" value="' + esc(title) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-description text-break">' + esc(description).substring(0, 40) + (description.length > 40 ? '...' : '') + '</span><input type="hidden" name="items[' + i + '][description]" value="' + esc(description) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-doctor-text text-break">' + esc(doctorText) + '</span><input type="hidden" name="items[' + i + '][doctor_text]" value="' + esc(doctorText) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-link text-break">' + esc(link) + '</span><input type="hidden" name="items[' + i + '][link]" value="' + esc(link) + '"></td>'
      + '<td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-service-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger remove-service-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#services-container').append(rowHtml);
    reindexServices();
    if (serviceModal) serviceModal.hide();
  });

  var deleteModalEl = document.getElementById('deleteServiceModal');
  var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  var serviceToRemove = null;
  $(document).on('click', '.remove-service-item', function() {
    serviceToRemove = $(this).closest('.service-item');
    if (deleteModal) deleteModal.show();
  });
  $('#confirm-delete-service').on('click', function() {
    if (serviceToRemove) { serviceToRemove.remove(); reindexServices(); }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
