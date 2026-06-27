<form action="{{ panel_route('setting.updateContactLocations') }}" method="POST" class="ajax-form">
  @csrf
  @method('PUT')

  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <span class="fw-bold small">Tiêu đề section</span>
        </div>
        <div class="card-body p-3">
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label class="form-label fw-bold">Tiêu đề lớn (VD: All Locations)</label>
              <input type="text" name="section_title" class="form-control" value="{{ $v['section_title'] ?? '' }}" placeholder="All Locations">
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Danh sách vị trí dạng table --}}
    <div class="col-md-12">
      <div class="card shadow-none border">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center py-2">
          <h6 class="mb-0 small fw-bold">Danh sách vị trí</h6>
          <button type="button" class="btn btn-sm btn-primary" id="btn-open-location-modal">
            <i class="ti tabler-plus"></i> Thêm vị trí
          </button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-sm" id="locations-table">
              <thead class="table-dark">
                <tr>
                  <th class="py-1 px-2" style="width: 32px;">#</th>
                  <th class="py-1 px-2">Tên địa điểm</th>
                  <th class="py-1 px-2">Địa chỉ</th>
                  <th class="py-1 px-2">Giờ phục vụ</th>
                  <th class="py-1 px-2 text-center" style="width: 80px;">Thao tác</th>
                </tr>
              </thead>
              <tbody id="locations-tbody" class="small">
                @foreach($v['items'] ?? [] as $idx => $loc)
                <tr class="location-item">
                  <td class="text-muted py-1 px-2 loc-index">{{ $idx + 1 }}</td>
                  <td class="py-1 px-2 fw-bold cell-name">{{ $loc['name'] ?? '' }}</td>
                  <td class="py-1 px-2 cell-address text-muted">{{ $loc['address'] ?? '' }}</td>
                  <td class="py-1 px-2 cell-time">{{ $loc['service_time'] ?? '' }}</td>
                  <td class="py-1 px-2 text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      <button type="button" class="btn btn-xs btn-outline-primary btn-edit-location btn-action-icon" title="Sửa" aria-label="Sửa vị trí"><span class="material-icons-outlined" aria-hidden="true">edit</span></button>
                      <button type="button" class="btn btn-xs btn-outline-danger btn-remove-location btn-action-icon" title="Xoá" aria-label="Xoá vị trí"><span class="material-icons-outlined" aria-hidden="true">delete</span></button>
                    </div>
                    {{-- Hidden inputs must stay inside a table cell so the browser does not move them out of the row. --}}
                    <input type="hidden" name="items[{{ $idx }}][name]" value="{{ e($loc['name'] ?? '') }}">
                    <input type="hidden" name="items[{{ $idx }}][address]" value="{{ e($loc['address'] ?? '') }}">
                    <input type="hidden" name="items[{{ $idx }}][service_time]" value="{{ e($loc['service_time'] ?? '') }}">
                    <input type="hidden" name="items[{{ $idx }}][google_link]" value="{{ e($loc['google_link'] ?? '') }}">
                    <input type="hidden" name="items[{{ $idx }}][map_iframe]" value="{{ e($loc['map_iframe'] ?? '') }}">
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
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

{{-- Modal thêm/sửa vị trí --}}
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="locationModalTitle">Thêm vị trí</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Tên địa điểm</label>
            <input type="text" class="form-control" id="modal-loc-name" placeholder="United State">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small"><i class="ti tabler-clock me-1 text-primary"></i>Giờ phục vụ</label>
            <input type="text" class="form-control" id="modal-loc-time" placeholder="Mon-Sat: 7:00 - 17:00">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-map-pin me-1 text-primary"></i>Địa chỉ</label>
            <input type="text" class="form-control" id="modal-loc-address" placeholder="123 Health Way, Suite 456 Goodland, 78910">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-external-link me-1 text-primary"></i>Link Google Maps</label>
            <input type="text" class="form-control" id="modal-loc-google-link" placeholder="https://maps.google.com/?q=...">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-map me-1 text-primary"></i>Mã nhúng bản đồ (Embed iframe)</label>
            <textarea class="form-control font-monospace" id="modal-loc-iframe" rows="3" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." ...></iframe>'></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-location-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  #locations-table td, #locations-table th { padding: 0.4rem 0.6rem !important; vertical-align: middle !important; }
  .btn-action-icon { width: 28px; height: 28px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
  .btn-action-icon .material-icons-outlined { font-size: 18px; line-height: 1; }
</style>
<script>
(function () {
  var editingRow = null;
  var locModalEl = document.getElementById('locationModal');
  var locModal = locModalEl ? new bootstrap.Modal(locModalEl) : null;

  function esc(s) {
    return (s || '').toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  document.getElementById('btn-open-location-modal').addEventListener('click', function () {
    editingRow = null;
    document.getElementById('locationModalTitle').textContent = 'Thêm vị trí';
    document.getElementById('modal-loc-name').value = '';
    document.getElementById('modal-loc-address').value = '';
    document.getElementById('modal-loc-time').value = '';
    document.getElementById('modal-loc-google-link').value = '';
    document.getElementById('modal-loc-iframe').value = '';
    if (locModal) locModal.show();
  });

  document.getElementById('locations-tbody').addEventListener('click', function (e) {
    var editBtn = e.target.closest('.btn-edit-location');
    var removeBtn = e.target.closest('.btn-remove-location');

    if (editBtn) {
      var row = editBtn.closest('.location-item');
      editingRow = row;
      document.getElementById('locationModalTitle').textContent = 'Sửa vị trí';
      document.getElementById('modal-loc-name').value = row.querySelector('input[name*="[name]"]').value;
      document.getElementById('modal-loc-address').value = row.querySelector('input[name*="[address]"]').value;
      document.getElementById('modal-loc-time').value = row.querySelector('input[name*="[service_time]"]').value;
      document.getElementById('modal-loc-google-link').value = row.querySelector('input[name*="[google_link]"]').value;
      document.getElementById('modal-loc-iframe').value = row.querySelector('input[name*="[map_iframe]"]').value;
      if (locModal) locModal.show();
    }

    if (removeBtn) {
      if (confirm('Bạn có chắc muốn xoá vị trí này?')) {
        removeBtn.closest('.location-item').remove();
        reindex();
      }
    }
  });

  document.getElementById('save-location-modal').addEventListener('click', function () {
    var name = document.getElementById('modal-loc-name').value;
    var address = document.getElementById('modal-loc-address').value;
    var time = document.getElementById('modal-loc-time').value;
    var glink = document.getElementById('modal-loc-google-link').value;
    var iframe = document.getElementById('modal-loc-iframe').value;
    var data = { name: name, address: address, service_time: time, google_link: glink, map_iframe: iframe };

    if (editingRow) {
      var idx = editingRow.rowIndex - 1; // tbody row index
      updateRow(editingRow, idx, data);
      editingRow = null;
      if (locModal) locModal.hide();
      return;
    }

    var i = document.querySelectorAll('#locations-tbody .location-item').length;
    document.getElementById('locations-tbody').insertAdjacentHTML('beforeend', buildRow(i, data));
    if (locModal) locModal.hide();
  });

  function buildRow(i, d) {
    return '<tr class="location-item">'
      + '<td class="text-muted py-1 px-2 loc-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-2 fw-bold cell-name">' + esc(d.name) + '</td>'
      + '<td class="py-1 px-2 cell-address text-muted">' + esc(d.address) + '</td>'
      + '<td class="py-1 px-2 cell-time">' + esc(d.service_time) + '</td>'
      + '<td class="py-1 px-2 text-center"><div class="d-flex gap-1 justify-content-center">'
      + '<button type="button" class="btn btn-xs btn-outline-primary btn-edit-location btn-action-icon" title="Sửa" aria-label="Sửa vị trí"><span class="material-icons-outlined" aria-hidden="true">edit</span></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger btn-remove-location btn-action-icon" title="Xoá" aria-label="Xoá vị trí"><span class="material-icons-outlined" aria-hidden="true">delete</span></button>'
      + '</div>'
      + '<input type="hidden" name="items[' + i + '][name]" value="' + esc(d.name) + '">'
      + '<input type="hidden" name="items[' + i + '][address]" value="' + esc(d.address) + '">'
      + '<input type="hidden" name="items[' + i + '][service_time]" value="' + esc(d.service_time) + '">'
      + '<input type="hidden" name="items[' + i + '][google_link]" value="' + esc(d.google_link) + '">'
      + '<input type="hidden" name="items[' + i + '][map_iframe]" value="' + esc(d.map_iframe) + '">'
      + '</td>'
      + '</tr>';
  }

  function updateRow(row, i, d) {
    row.outerHTML = buildRow(i, d);
    reindex();
  }

  function reindex() {
    document.querySelectorAll('#locations-tbody .location-item').forEach(function (row, idx) {
      var idxCell = row.querySelector('.loc-index');
      if (idxCell) idxCell.textContent = idx + 1;
      row.querySelectorAll('input[name^="items["]').forEach(function (el) {
        el.name = el.name.replace(/items\[\d+\]/, 'items[' + idx + ']');
      });
    });
  }
})();
</script>
@endpush
