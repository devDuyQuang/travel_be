<form action="{{ panel_route('setting.updateDoctor') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card doctor-card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="doctorTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="doctor-main-tab" data-bs-toggle="tab" data-bs-target="#doctor-main" type="button" role="tab" aria-controls="doctor-main" aria-selected="true">
                Giới thiệu & Hình ảnh
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="doctor-skills-tab" data-bs-toggle="tab" data-bs-target="#doctor-skills" type="button" role="tab" aria-controls="doctor-skills" aria-selected="false">
                Kỹ năng
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="doctor-achievements-tab" data-bs-toggle="tab" data-bs-target="#doctor-achievements" type="button" role="tab" aria-controls="doctor-achievements" aria-selected="false">
                Thành tựu
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-0">
          <div class="tab-content">
            {{-- Tab: Giới thiệu & hình ảnh --}}
            <div class="tab-pane fade show active" id="doctor-main" role="tabpanel" aria-labelledby="doctor-main-tab">
              <div class="row g-4">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề (H2)</label>
                    <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Meet Dr. Natali Jackson">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tên bác sĩ</label>
                    <input type="text" name="doctor_name" class="form-control" value="{{ $v['doctor_name'] ?? '' }}" placeholder="Dr. Natali jackson">
                  </div>
                  <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả ngắn</label>
                    <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-6">
                      <label class="form-label fw-bold">Ảnh bác sĩ (685x720)</label>
                      <input type="file" name="image_file" class="form-control img-input" data-preview="preview-doc-main">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label fw-bold">Số kinh nghiệm</label>
                      <input type="text" name="experience[number]" class="form-control" value="{{ $v['experience']['number'] ?? '' }}" placeholder="20+">
                    </div>
                    <div class="col-md-3">
                      <label class="form-label fw-bold">Năm</label>
                      <input type="text" name="experience[label]" class="form-control" value="{{ $v['experience']['label'] ?? '' }}" placeholder="Years">
                    </div>
                  </div>
                  <div class="mt-3 border rounded d-flex align-items-center justify-content-center w-100" style="max-height: 320px; overflow: hidden;">
                    <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="preview-doc-main" class="img-fluid {{ empty($v['image']) ? 'd-none' : '' }}" style="max-height: 300px; width: auto;">
                    @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Kỹ năng --}}
            <div class="tab-pane fade" id="doctor-skills" role="tabpanel" aria-labelledby="doctor-skills-tab">
              <div class="card shadow-none border mb-0">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">Kỹ năng (Skills)</h6>
                  <button type="button" class="btn btn-sm btn-primary" id="add-doc-skill"><i class="ti tabler-plus me-1"></i>Thêm</button>
                </div>
                <div class="card-body pt-2">
                  <div class="mb-3"><label class="form-label fw-bold">Tiêu đề phần kỹ năng</label><input type="text" name="skills_header" class="form-control" value="{{ $v['skills_header'] ?? '' }}" placeholder="About Skills"></div>
                  <div id="doc-skills-container">
                    @foreach($v['skills'] ?? [] as $skill)
                    <div class="skill-item mb-2 input-group input-group-merge">
                      <input type="text" name="skills[]" class="form-control form-control-sm" value="{{ $skill }}">
                      <button type="button" class="btn btn-sm btn-outline-danger remove-doc-item"><i class="ti tabler-trash"></i></button>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Thành tựu (bảng) --}}
            <div class="tab-pane fade" id="doctor-achievements" role="tabpanel" aria-labelledby="doctor-achievements-tab">
              <div class="card shadow-none border mb-0">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-doc-ach-modal"><i class="ti tabler-plus me-1"></i>Thêm thẻ</button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-sm">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1" style="width: 32px;">#</th>
                          <th class="py-1 px-1" style="width: 50px;">Ảnh</th>
                          <th class="py-1 px-1">Tiêu đề</th>
                          <th class="py-1 px-1">Phụ đề</th>
                          <th class="py-1 px-1">Text Link</th>
                          <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="doc-ach-container" class="small">
                        @foreach($v['achievements'] ?? [] as $index => $ach)
                        <tr class="ach-item">
                          <td class="text-muted py-1 px-1 ach-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1">
                            <div class="ach-thumb-wrap">
                              @if(!empty($ach['image']))
                                <img src="{{ asset($ach['image']) }}" class="ach-thumb rounded object-fit-contain" style="height: 36px; width: 36px;" alt="">
                                <span class="no-photo d-none d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>
                              @else
                                <img src="" class="ach-thumb rounded object-fit-contain d-none" style="height: 36px; width: 36px;" alt="">
                                <span class="no-photo d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>
                              @endif
                              <input type="file" name="achievement_images[{{ $index }}]" class="d-none ach-file-input" accept="image/*">
                            </div>
                          </td>
                          <td class="py-1 px-1"><span class="cell-title text-break">{{ $ach['title'] ?? '' }}</span><input type="hidden" name="achievements[{{ $index }}][title]" value="{{ e($ach['title'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-subtitle text-break">{{ $ach['subtitle'] ?? '' }}</span><input type="hidden" name="achievements[{{ $index }}][subtitle]" value="{{ e($ach['subtitle'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-link-text text-break">{{ $ach['link_text'] ?? '' }}</span><input type="hidden" name="achievements[{{ $index }}][link_text]" value="{{ e($ach['link_text'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-doc-ach btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-doc-item btn-action-icon" title="Xoá" data-target="ach-item"><i class="ti tabler-trash"></i></button>
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
<template id="doc-skill-template">
  <div class="skill-item mb-2 input-group input-group-merge">
    <input type="text" name="skills[]" class="form-control form-control-sm">
    <button type="button" class="btn btn-sm btn-outline-danger remove-doc-item"><i class="ti tabler-trash"></i></button>
  </div>
</template>

<!-- Modal thêm/sửa thành tựu -->
<div class="modal fade" id="docAchModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="docAchModalTitle">Thêm thành tựu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold">Badge Image</label>
            <div class="d-flex align-items-center gap-3">
              <div class="flex-shrink-0">
                <img src="" id="modal-doc-ach-preview" class="rounded object-fit-contain d-none" style="width:64px;height:64px;" alt="">
                <span id="modal-doc-ach-no-photo" class="rounded d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width:64px;height:64px;"><i class="ti tabler-photo"></i></span>
              </div>
              <input type="file" class="form-control" id="modal-doc-ach-image" accept="image/*" style="max-width: 240px;">
            </div>
          </div>
          <div class="col-12"><label class="form-label fw-bold">Tiêu đề</label><input type="text" class="form-control" id="modal-doc-ach-title"></div>
          <div class="col-12"><label class="form-label fw-bold">Phụ đề</label><input type="text" class="form-control" id="modal-doc-ach-subtitle"></div>
          <div class="col-12"><label class="form-label fw-bold">Text Link</label><input type="text" class="form-control" id="modal-doc-ach-link-text"></div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-doc-ach-modal">Lưu</button></div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteDoctorItemModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá mục này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-doctor">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  .ach-thumb, .ach-thumb-wrap .no-photo { width: 36px; height: 36px; object-fit: contain; }
  #doc-ach-container .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  .table-sm td, .table-sm th { padding: 0.2rem 0.35rem !important; }
</style>
<script>
$(function() {
  $(document).on('change', '.img-input', function() {
    var f = this.files[0], pid = $(this).data('preview'), $p = $('#' + pid);
    if (f) { var r = new FileReader(); r.onload = function(e) { $p.attr('src', e.target.result).removeClass('d-none'); }; r.readAsDataURL(f); }
  });

  $('#add-doc-skill').on('click', function() { $('#doc-skills-container').append($('#doc-skill-template').html()); });

  var modalAchDataUrl = null;
  var editingAchRow = null;
  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexAch() {
    $('#doc-ach-container .ach-item').each(function(idx) {
      $(this).find('.ach-index').text(idx + 1);
      $(this).find('[name^="achievements["]').each(function() { var n = $(this).attr('name'); if (n) $(this).attr('name', n.replace(/achievements\[\d+\]/, 'achievements[' + idx + ']')); });
      $(this).find('.ach-file-input').attr('name', 'achievement_images[' + idx + ']');
    });
  }
  function setAchRowDisplay($row, data) {
    $row.find('.cell-title').text(data.title || '');
    $row.find('.cell-subtitle').text(data.subtitle || '');
    $row.find('.cell-link-text').text(data.link_text || '');
    $row.find('input[name*="[title]"]').val(data.title || '');
    $row.find('input[name*="[subtitle]"]').val(data.subtitle || '');
    $row.find('input[name*="[link_text]"]').val(data.link_text || '');
    if (data.thumbSrc) {
      $row.find('.ach-thumb').attr('src', data.thumbSrc).removeClass('d-none').parent().find('.no-photo').addClass('d-none');
    }
  }

  $('#modal-doc-ach-image').on('change', function() {
    var input = this;
    modalAchDataUrl = null;
    if (input.files && input.files[0]) {
      var r = new FileReader();
      r.onload = function(e) { modalAchDataUrl = e.target.result; $('#modal-doc-ach-preview').attr('src', e.target.result).removeClass('d-none'); $('#modal-doc-ach-no-photo').addClass('d-none'); };
      r.readAsDataURL(input.files[0]);
    } else { $('#modal-doc-ach-preview').addClass('d-none').attr('src', ''); $('#modal-doc-ach-no-photo').removeClass('d-none'); }
  });

  var docAchModalEl = document.getElementById('docAchModal');
  var docAchModal = docAchModalEl ? new bootstrap.Modal(docAchModalEl) : null;

  $('#open-doc-ach-modal').on('click', function() {
    editingAchRow = null;
    $('#docAchModalTitle').text('Thêm thành tựu');
    $('#modal-doc-ach-title, #modal-doc-ach-subtitle, #modal-doc-ach-link-text').val('');
    $('#modal-doc-ach-image').val('');
    modalAchDataUrl = null;
    $('#modal-doc-ach-preview').addClass('d-none'); $('#modal-doc-ach-no-photo').removeClass('d-none');
    if (docAchModal) docAchModal.show();
  });

  $(document).on('click', '.edit-doc-ach', function() {
    var $row = $(this).closest('.ach-item');
    editingAchRow = $row;
    $('#docAchModalTitle').text('Sửa thành tựu');
    $('#modal-doc-ach-title').val($row.find('input[name*="[title]"]').val());
    $('#modal-doc-ach-subtitle').val($row.find('input[name*="[subtitle]"]').val());
    $('#modal-doc-ach-link-text').val($row.find('input[name*="[link_text]"]').val());
    $('#modal-doc-ach-image').val('');
    modalAchDataUrl = null;
    var src = $row.find('.ach-thumb').attr('src');
    if (src) { $('#modal-doc-ach-preview').attr('src', src).removeClass('d-none'); $('#modal-doc-ach-no-photo').addClass('d-none'); }
    else { $('#modal-doc-ach-preview').addClass('d-none'); $('#modal-doc-ach-no-photo').removeClass('d-none'); }
    if (docAchModal) docAchModal.show();
  });

  $('#save-doc-ach-modal').on('click', function() {
    var title = $('#modal-doc-ach-title').val();
    var subtitle = $('#modal-doc-ach-subtitle').val();
    var linkText = $('#modal-doc-ach-link-text').val();
    var data = { title: title, subtitle: subtitle, link_text: linkText };

    if (editingAchRow && editingAchRow.length) {
      setAchRowDisplay(editingAchRow, data);
      if (modalAchDataUrl) {
        editingAchRow.find('.ach-thumb').attr('src', modalAchDataUrl).removeClass('d-none');
        editingAchRow.find('.no-photo').addClass('d-none');
      }
      var rowInput = editingAchRow.find('.ach-file-input')[0];
      var modalInput = document.getElementById('modal-doc-ach-image');
      if (modalInput.files && modalInput.files.length && rowInput) { var dt = new DataTransfer(); dt.items.add(modalInput.files[0]); rowInput.files = dt.files; }
      editingAchRow = null;
      if (docAchModal) docAchModal.hide();
      return;
    }

    var i = $('#doc-ach-container .ach-item').length;
    var thumbCl = modalAchDataUrl ? 'ach-thumb rounded object-fit-contain' : 'ach-thumb rounded object-fit-contain d-none';
    var thumbSrc = modalAchDataUrl ? ' src="' + modalAchDataUrl + '"' : '';
    var noCl = 'no-photo d-inline-flex align-items-center justify-content-center bg-secondary text-white rounded' + (modalAchDataUrl ? ' d-none' : '');
    var rowHtml = '<tr class="ach-item"><td class="text-muted py-1 px-1 ach-index">' + (i + 1) + '</td><td class="py-1 px-1"><div class="ach-thumb-wrap"><img' + thumbSrc + ' class="' + thumbCl + '" style="height:36px;width:36px;" alt=""><span class="' + noCl + '" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span><input type="file" name="achievement_images[' + i + ']" class="d-none ach-file-input" accept="image/*"></div></td><td class="py-1 px-1"><span class="cell-title text-break">' + esc(title) + '</span><input type="hidden" name="achievements[' + i + '][title]" value="' + esc(title) + '"></td><td class="py-1 px-1"><span class="cell-subtitle text-break">' + esc(subtitle) + '</span><input type="hidden" name="achievements[' + i + '][subtitle]" value="' + esc(subtitle) + '"></td><td class="py-1 px-1"><span class="cell-link-text text-break">' + esc(linkText) + '</span><input type="hidden" name="achievements[' + i + '][link_text]" value="' + esc(linkText) + '"></td><td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-doc-ach btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button><button type="button" class="btn btn-xs btn-outline-danger remove-doc-item btn-action-icon" title="Xoá" data-target="ach-item"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#doc-ach-container').append(rowHtml);
    var modalInput = document.getElementById('modal-doc-ach-image');
    var newRow = $('#doc-ach-container .ach-item').eq(i);
    var rowInput = newRow.find('.ach-file-input')[0];
    if (modalInput.files && modalInput.files.length && rowInput) { var dt = new DataTransfer(); dt.items.add(modalInput.files[0]); rowInput.files = dt.files; }
    reindexAch();
    if (docAchModal) docAchModal.hide();
  });

  var deleteModal = document.getElementById('deleteDoctorItemModal') && new bootstrap.Modal(document.getElementById('deleteDoctorItemModal'));
  var itemToRemove = null;
  $(document).on('click', '.remove-doc-item', function() {
    var target = $(this).data('target');
    itemToRemove = target ? $(this).closest('.' + target) : $(this).closest('.skill-item, .ach-item');
    if (deleteModal) deleteModal.show();
  });
  $('#confirm-delete-doctor').on('click', function() {
    if (itemToRemove) {
      var container = itemToRemove.parent();
      itemToRemove.remove();
      if (container.attr('id') === 'doc-ach-container') reindexAch();
    }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
