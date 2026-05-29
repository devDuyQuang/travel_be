<form action="{{ panel_route('setting.updateAboutVisionMission') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  @php $vvm = $v ?? []; $items = $vvm['items'] ?? []; @endphp

  <div class="row g-3 p-2">
    {{-- Nội dung chính --}}
    <div class="col-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vm-content" type="button">
                Nội Dung &amp; Hình Ảnh
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vm-items" type="button">
                Danh sách mục (Sứ mệnh / Tầm nhìn / Giá trị)
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            {{-- Tab 1: content + image --}}
            <div class="tab-pane fade show active" id="vm-content" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-6">
                  <x-input-field label="Tiêu đề phụ (Subtitle)" name="subtitle" :value="$vvm['subtitle'] ?? ''" placeholder="Inspirational Health" />
                </div>
                <div class="col-md-6">
                  <x-input-field label="Tiêu đề chính (Title)" name="title" :value="$vvm['title'] ?? ''" placeholder="Our Vision And Mission" />
                </div>
                <div class="col-md-12">
                  <x-textarea-field label="Mô tả" name="description" :value="$vvm['description'] ?? ''" placeholder="Mô tả ngắn về tầm nhìn..." rows="3" />
                </div>
                <div class="col-md-12">
                  <x-file-input label="Hình ảnh chính (750×1000)" name="vm_image" :multiple="false" :current-url="$currentImageUrl ?? null" />
                </div>
              </div>
            </div>

            {{-- Tab 2: mission/vision/values items --}}
            <div class="tab-pane fade" id="vm-items" role="tabpanel">
              <div class="card shadow-none border mt-2">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center py-2">
                  <h6 class="mb-0 small fw-bold">Danh sách mục</h6>
                  <button type="button" class="btn btn-sm btn-primary" id="btn-open-vm-modal">
                    <i class="ti tabler-plus"></i> Thêm mục
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-sm" id="vm-items-table">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-2" style="width:32px">#</th>
                          <th class="py-1 px-2">Icon Class</th>
                          <th class="py-1 px-2">Tiêu đề</th>
                          <th class="py-1 px-2">Mô tả</th>
                          <th class="py-1 px-2 text-center" style="width:80px">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="vm-items-tbody" class="small">
                        @foreach($items as $idx => $it)
                        <tr class="vm-item-row">
                          <td class="text-muted py-1 px-2 vm-index">{{ $idx + 1 }}</td>
                          <td class="py-1 px-2 cell-icon"><span class="badge bg-label-secondary font-monospace">{{ $it['icon'] ?? '' }}</span></td>
                          <td class="py-1 px-2 fw-bold cell-title">{{ $it['title'] ?? '' }}</td>
                          <td class="py-1 px-2 text-muted cell-desc">{{ Str::limit($it['description'] ?? '', 60) }}</td>
                          <td class="py-1 px-2 text-center">
                            <div class="d-flex gap-1 justify-content-center">
                              <button type="button" class="btn btn-xs btn-outline-primary btn-edit-vm btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger btn-remove-vm btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
                            </div>
                          </td>
                          <input type="hidden" name="items[{{ $idx }}][icon]"        value="{{ e($it['icon'] ?? '') }}">
                          <input type="hidden" name="items[{{ $idx }}][title]"       value="{{ e($it['title'] ?? '') }}">
                          <input type="hidden" name="items[{{ $idx }}][description]" value="{{ e($it['description'] ?? '') }}">
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  @if(empty($items))
                  <p class="text-muted small text-center my-3" id="vm-empty-msg">Chưa có mục nào. Nhấn "+ Thêm mục" để thêm.</p>
                  @else
                  <p class="text-muted small text-center my-3 d-none" id="vm-empty-msg">Chưa có mục nào. Nhấn "+ Thêm mục" để thêm.</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 mt-2 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>

{{-- Modal thêm/sửa mục --}}
<div class="modal fade" id="vmItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vmItemModalTitle">Thêm mục</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-box me-1 text-primary"></i>Icon Class</label>
            <input type="text" class="form-control font-monospace" id="modal-vm-icon" placeholder="ti tabler-target">
            <div class="form-text">Ví dụ: <code>ti tabler-target</code>, <code>ti tabler-eye</code>, <code>ti tabler-heart</code></div>
          </div>
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-text-size me-1 text-primary"></i>Tiêu đề</label>
            <input type="text" class="form-control" id="modal-vm-title" placeholder="Mission">
          </div>
          <div class="col-md-12">
            <label class="form-label fw-bold small"><i class="ti tabler-align-left me-1 text-primary"></i>Mô tả</label>
            <textarea class="form-control" id="modal-vm-desc" rows="3" placeholder="Mô tả ngắn về mục này..."></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-vm-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  #vm-items-table td, #vm-items-table th { padding: 0.4rem 0.6rem !important; vertical-align: middle !important; }
  .btn-action-icon { width: 28px; height: 28px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
</style>
<script>
(function () {
  var editingRow = null;
  var vmModalEl  = document.getElementById('vmItemModal');
  var vmModal    = vmModalEl ? new bootstrap.Modal(vmModalEl) : null;

  function esc(s) {
    return (s || '').toString()
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function toggleEmpty() {
    var msg = document.getElementById('vm-empty-msg');
    if (!msg) return;
    var count = document.querySelectorAll('#vm-items-tbody .vm-item-row').length;
    msg.classList.toggle('d-none', count > 0);
  }

  /* ── Open modal for ADD ── */
  document.getElementById('btn-open-vm-modal')?.addEventListener('click', function () {
    editingRow = null;
    document.getElementById('vmItemModalTitle').textContent = 'Thêm mục';
    document.getElementById('modal-vm-icon').value  = '';
    document.getElementById('modal-vm-title').value = '';
    document.getElementById('modal-vm-desc').value  = '';
    if (vmModal) vmModal.show();
  });

  /* ── Table: edit / remove clicks ── */
  document.getElementById('vm-items-tbody')?.addEventListener('click', function (e) {
    var editBtn   = e.target.closest('.btn-edit-vm');
    var removeBtn = e.target.closest('.btn-remove-vm');

    if (editBtn) {
      var row = editBtn.closest('.vm-item-row');
      editingRow = row;
      document.getElementById('vmItemModalTitle').textContent = 'Sửa mục';
      document.getElementById('modal-vm-icon').value  = row.querySelector('input[name*="[icon]"]').value;
      document.getElementById('modal-vm-title').value = row.querySelector('input[name*="[title]"]').value;
      document.getElementById('modal-vm-desc').value  = row.querySelector('input[name*="[description]"]').value;
      if (vmModal) vmModal.show();
    }

    if (removeBtn) {
      if (confirm('Bạn có chắc muốn xoá mục này?')) {
        removeBtn.closest('.vm-item-row').remove();
        reindex();
        toggleEmpty();
      }
    }
  });

  /* ── Save modal → add/update row ── */
  document.getElementById('save-vm-modal')?.addEventListener('click', function () {
    var icon  = document.getElementById('modal-vm-icon').value.trim();
    var title = document.getElementById('modal-vm-title').value.trim();
    var desc  = document.getElementById('modal-vm-desc').value.trim();
    var data  = { icon: icon, title: title, description: desc };

    if (editingRow) {
      var idx = Array.from(document.querySelectorAll('#vm-items-tbody .vm-item-row')).indexOf(editingRow);
      editingRow.outerHTML = buildRow(idx, data);
      editingRow = null;
      if (vmModal) vmModal.hide();
      reindex();
      return;
    }

    var i = document.querySelectorAll('#vm-items-tbody .vm-item-row').length;
    document.getElementById('vm-items-tbody').insertAdjacentHTML('beforeend', buildRow(i, data));
    if (vmModal) vmModal.hide();
    toggleEmpty();
  });

  function buildRow(i, d) {
    var shortDesc = (d.description || '').length > 60 ? d.description.substring(0, 60) + '…' : d.description;
    return '<tr class="vm-item-row">'
      + '<td class="text-muted py-1 px-2 vm-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-2 cell-icon"><span class="badge bg-label-secondary font-monospace">' + esc(d.icon) + '</span></td>'
      + '<td class="py-1 px-2 fw-bold cell-title">' + esc(d.title) + '</td>'
      + '<td class="py-1 px-2 text-muted cell-desc">' + esc(shortDesc) + '</td>'
      + '<td class="py-1 px-2 text-center"><div class="d-flex gap-1 justify-content-center">'
      + '<button type="button" class="btn btn-xs btn-outline-primary btn-edit-vm btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger btn-remove-vm btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>'
      + '</div></td>'
      + '<input type="hidden" name="items[' + i + '][icon]" value="' + esc(d.icon) + '">'
      + '<input type="hidden" name="items[' + i + '][title]" value="' + esc(d.title) + '">'
      + '<input type="hidden" name="items[' + i + '][description]" value="' + esc(d.description) + '">'
      + '</tr>';
  }

  function reindex() {
    document.querySelectorAll('#vm-items-tbody .vm-item-row').forEach(function (row, idx) {
      var idxCell = row.querySelector('.vm-index');
      if (idxCell) idxCell.textContent = idx + 1;
      row.querySelectorAll('input[name^="items["]').forEach(function (el) {
        el.name = el.name.replace(/items\[\d+\]/, 'items[' + idx + ']');
      });
    });
  }
})();
</script>
@endpush
