<form action="{{ panel_route('setting.updateAboutValues') }}" method="POST" class="ajax-form" data-require-persist="true">
  @csrf
  @method('PUT')
  @php $values = $v ?? []; $items = $values['items'] ?? []; @endphp

  <div class="card shadow-none border">
    <div class="card-body">
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <x-input-field label="Tiêu đề phụ" name="choose_subtitle" :value="$values['choose_subtitle'] ?? ''" placeholder="What We Do" />
        </div>
        <div class="col-md-6">
          <x-input-field label="Tiêu đề chính" name="choose_title" :value="$values['choose_title'] ?? ''" placeholder="We Arrange The Best Tour Ever Possible" />
        </div>
        <div class="col-12">
          <x-textarea-field label="Mô tả" name="choose_description" :value="$values['choose_description'] ?? ''" placeholder="Nhập mô tả cho nhóm giá trị nổi bật..." rows="3" />
        </div>
      </div>

      <div class="card shadow-none border mb-0">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center py-2">
          <h6 class="mb-0 small fw-bold">Danh sách giá trị</h6>
          <button type="button" class="btn btn-sm btn-primary" id="btn-open-value-modal">
            <span class="material-icons-outlined" aria-hidden="true">add</span>
            Thêm giá trị
          </button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-sm" id="value-items-table">
              <thead class="table-dark">
                <tr>
                  <th style="width:42px">#</th>
                  <th>Icon class</th>
                  <th>Tiêu đề</th>
                  <th>Mô tả</th>
                  <th class="text-center" style="width:90px">Thao tác</th>
                </tr>
              </thead>
              <tbody id="value-items-tbody" class="small">
                @foreach($items as $idx => $it)
                  <tr class="value-item-row">
                    <td class="text-muted value-index">{{ $idx + 1 }}</td>
                    <td class="cell-icon"><span class="badge bg-label-secondary font-monospace">{{ $it['icon'] ?? '' }}</span></td>
                    <td class="fw-bold cell-title">{{ $it['title'] ?? '' }}</td>
                    <td class="text-muted cell-desc">{{ Str::limit($it['description'] ?? '', 60) }}</td>
                    <td class="text-center">
                      <div class="d-flex gap-1 justify-content-center">
                        <button type="button" class="btn btn-xs btn-outline-primary btn-edit-value btn-action-icon" title="Sửa" aria-label="Sửa giá trị">
                          <span class="material-icons-outlined" aria-hidden="true">edit</span>
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-danger btn-remove-value btn-action-icon" title="Xoá" aria-label="Xoá giá trị">
                          <span class="material-icons-outlined" aria-hidden="true">delete</span>
                        </button>
                      </div>
                      <input type="hidden" name="items[{{ $idx }}][icon]" value="{{ e($it['icon'] ?? '') }}">
                      <input type="hidden" name="items[{{ $idx }}][title]" value="{{ e($it['title'] ?? '') }}">
                      <input type="hidden" name="items[{{ $idx }}][description]" value="{{ e($it['description'] ?? '') }}">
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <p class="text-muted small text-center my-3 {{ empty($items) ? '' : 'd-none' }}" id="value-empty-msg">
            Chưa có giá trị nào. Nhấn “Thêm giá trị” để bắt đầu.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3 text-end">
    <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
    <button type="reset" class="btn btn-label-secondary">Hủy</button>
  </div>
</form>

<div class="modal fade" id="valueItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="valueItemModalTitle">Thêm giá trị</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold small">Icon class</label>
            <input type="text" class="form-control font-monospace" id="modal-value-icon" placeholder="ti tabler-target">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small">Tiêu đề</label>
            <input type="text" class="form-control" id="modal-value-title" placeholder="Ultimate flexibility">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small">Mô tả</label>
            <textarea class="form-control" id="modal-value-desc" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-value-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  #value-items-table td, #value-items-table th { padding: .45rem .65rem !important; vertical-align: middle !important; }
  .btn-action-icon { width: 28px; height: 28px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
  .btn-action-icon .material-icons-outlined { font-size: 18px; line-height: 1; }
  #btn-open-value-modal { display: inline-flex; align-items: center; gap: .3rem; }
  #btn-open-value-modal .material-icons-outlined { font-size: 18px; }
</style>
<script>
(function () {
  var editingRow = null;
  var modalElement = document.getElementById('valueItemModal');
  var modal = modalElement ? new bootstrap.Modal(modalElement) : null;

  function escapeHtml(value) {
    return (value || '').toString()
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function toggleEmpty() {
    var emptyMessage = document.getElementById('value-empty-msg');
    var count = document.querySelectorAll('#value-items-tbody .value-item-row').length;
    if (emptyMessage) emptyMessage.classList.toggle('d-none', count > 0);
  }

  function reindex() {
    document.querySelectorAll('#value-items-tbody .value-item-row').forEach(function (row, index) {
      row.querySelector('.value-index').textContent = index + 1;
      row.querySelectorAll('input[name^="items["]').forEach(function (input) {
        input.name = input.name.replace(/items\[\d+\]/, 'items[' + index + ']');
      });
    });
  }

  function buildRow(index, data) {
    var shortDescription = data.description.length > 60 ? data.description.substring(0, 60) + '…' : data.description;
    return '<tr class="value-item-row">'
      + '<td class="text-muted value-index">' + (index + 1) + '</td>'
      + '<td class="cell-icon"><span class="badge bg-label-secondary font-monospace">' + escapeHtml(data.icon) + '</span></td>'
      + '<td class="fw-bold cell-title">' + escapeHtml(data.title) + '</td>'
      + '<td class="text-muted cell-desc">' + escapeHtml(shortDescription) + '</td>'
      + '<td class="text-center"><div class="d-flex gap-1 justify-content-center">'
      + '<button type="button" class="btn btn-xs btn-outline-primary btn-edit-value btn-action-icon" title="Sửa" aria-label="Sửa giá trị"><span class="material-icons-outlined" aria-hidden="true">edit</span></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger btn-remove-value btn-action-icon" title="Xoá" aria-label="Xoá giá trị"><span class="material-icons-outlined" aria-hidden="true">delete</span></button>'
      + '</div>'
      + '<input type="hidden" name="items[' + index + '][icon]" value="' + escapeHtml(data.icon) + '">'
      + '<input type="hidden" name="items[' + index + '][title]" value="' + escapeHtml(data.title) + '">'
      + '<input type="hidden" name="items[' + index + '][description]" value="' + escapeHtml(data.description) + '">'
      + '</td>'
      + '</tr>';
  }

  document.getElementById('btn-open-value-modal')?.addEventListener('click', function () {
    editingRow = null;
    document.getElementById('valueItemModalTitle').textContent = 'Thêm giá trị';
    document.getElementById('modal-value-icon').value = '';
    document.getElementById('modal-value-title').value = '';
    document.getElementById('modal-value-desc').value = '';
    modal?.show();
  });

  document.getElementById('value-items-tbody')?.addEventListener('click', function (event) {
    var editButton = event.target.closest('.btn-edit-value');
    var removeButton = event.target.closest('.btn-remove-value');

    if (editButton) {
      editingRow = editButton.closest('.value-item-row');
      document.getElementById('valueItemModalTitle').textContent = 'Sửa giá trị';
      document.getElementById('modal-value-icon').value = editingRow.querySelector('input[name*="[icon]"]').value;
      document.getElementById('modal-value-title').value = editingRow.querySelector('input[name*="[title]"]').value;
      document.getElementById('modal-value-desc').value = editingRow.querySelector('input[name*="[description]"]').value;
      modal?.show();
    }

    if (removeButton && confirm('Bạn có chắc muốn xoá giá trị này?')) {
      removeButton.closest('.value-item-row').remove();
      reindex();
      toggleEmpty();
    }
  });

  document.getElementById('save-value-modal')?.addEventListener('click', function () {
    var data = {
      icon: document.getElementById('modal-value-icon').value.trim(),
      title: document.getElementById('modal-value-title').value.trim(),
      description: document.getElementById('modal-value-desc').value.trim()
    };

    if (editingRow) {
      var index = Array.from(document.querySelectorAll('#value-items-tbody .value-item-row')).indexOf(editingRow);
      editingRow.outerHTML = buildRow(index, data);
      editingRow = null;
    } else {
      var rowCount = document.querySelectorAll('#value-items-tbody .value-item-row').length;
      document.getElementById('value-items-tbody').insertAdjacentHTML('beforeend', buildRow(rowCount, data));
    }

    modal?.hide();
    reindex();
    toggleEmpty();
  });
})();
</script>
@endpush
