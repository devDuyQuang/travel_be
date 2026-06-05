<form action="{{ panel_route('setting.updateSpecialists') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="specialistsTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="specialists-info-tab" data-bs-toggle="tab" data-bs-target="#specialists-info" type="button" role="tab" aria-controls="specialists-info" aria-selected="true">
                Thông tin chung Section
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="specialists-list-tab" data-bs-toggle="tab" data-bs-target="#specialists-list" type="button" role="tab" aria-controls="specialists-list" aria-selected="false">
                Danh sách nhân viên
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content specialists-tab-content" id="specialistsSectionTabContent">
            {{-- Tab: Thông tin chung --}}
            <div class="tab-pane fade show active" id="specialists-info" role="tabpanel" aria-labelledby="specialists-info-tab">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Tiêu đề chính Section</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="We Employ Only Specialists">
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Link Xem tất cả (View All)</label>
                  <input type="text" name="view_all_link" class="form-control" value="{{ $v['view_all_link'] ?? '' }}" placeholder="/specialists">
                </div>
              </div>
            </div>

            {{-- Tab: Danh sách bác sĩ --}}
            <div class="tab-pane fade" id="specialists-list" role="tabpanel" aria-labelledby="specialists-list-tab">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-specialist-modal">
                    <i class="ti tabler-plus"></i> Thêm bác sĩ
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 specialists-table table-sm" id="specialists-table">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1 col-num">#</th>
                          <th class="py-1 px-1 col-img">Ảnh</th>
                          <th class="py-1 px-1 col-name">Tên bác sĩ</th>
                          <th class="py-1 px-1 col-specialty">Chuyên khoa</th>
                          <th class="py-1 px-1 d-none">Text nút</th>
                          <th class="py-1 px-1 d-none">Link nút</th>
                          <th class="py-1 px-1 col-socials">Mạng xã hội</th>
                          <th class="py-1 px-1 text-center col-actions">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="specialists-container" class="small">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <tr class="specialist-item">
                          <td class="text-muted py-1 px-1 specialist-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1">
                            <div class="specialist-avatar-wrap">
                              @if(!empty($item['image']))
                                <img src="{{ asset($item['image']) }}" class="rounded-circle object-fit-cover specialist-thumb" width="36" height="36" alt="">
                                <span class="no-photo d-none"></span>
                              @else
                                <img src="" class="rounded-circle object-fit-cover specialist-thumb d-none" width="36" height="36" alt="">
                                <span class="no-photo rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>
                              @endif
                              <input type="file" name="items[{{ $index }}][image_file]" class="d-none specialist-file-input" accept="image/*">
                            </div>
                          </td>
                          <td class="py-1 px-1"><span class="cell-name text-break">{{ $item['name'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][name]" value="{{ e($item['name'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-specialty text-break">{{ $item['specialty'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][specialty]" value="{{ e($item['specialty'] ?? '') }}"></td>
                          <td class="py-1 px-1 d-none"><span class="cell-btn-text text-break">{{ $item['button_text'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][button_text]" value="{{ e($item['button_text'] ?? '') }}"></td>
                          <td class="py-1 px-1 d-none"><span class="cell-btn-link text-break">{{ $item['button_link'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][button_link]" value="{{ e($item['button_link'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-socials text-break">@php $socialKeys = []; foreach(['linkedin','facebook','twitter','youtube'] as $s) { if (!empty($item['socials'][$s] ?? '')) $socialKeys[] = $s; } @endphp {{ implode(', ', $socialKeys) }}</span>
                            @foreach(['linkedin', 'facebook', 'twitter', 'youtube'] as $social)
                              <input type="hidden" name="items[{{ $index }}][socials][{{ $social }}]" value="{{ e(data_get($item, 'socials.' . $social, '')) }}">
                            @endforeach
                          </td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-specialist-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-specialist btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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
<!-- Modal thêm/sửa bác sĩ -->
<div class="modal fade" id="specialistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="specialistModalTitle">Thêm bác sĩ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold">Ảnh (300x335)</label>
            <div class="d-flex align-items-center gap-3">
              <div class="specialist-modal-avatar-wrap flex-shrink-0">
                <img src="" id="modal-specialist-preview" class="rounded-circle object-fit-cover d-none" width="64" height="64" alt="">
                <span id="modal-specialist-no-photo" class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white"><i class="ti tabler-photo ti-lg"></i></span>
              </div>
              <input type="file" class="form-control" id="modal-specialist-image" accept="image/*" style="max-width: 240px;">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Tên bác sĩ</label>
            <input type="text" class="form-control" id="modal-specialist-name">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Chuyên khoa</label>
            <input type="text" class="form-control" id="modal-specialist-specialty">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Text nút</label>
            <input type="text" class="form-control" id="modal-specialist-button-text">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Link nút</label>
            <input type="text" class="form-control" id="modal-specialist-button-link">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold mb-2 d-block">Mạng xã hội</label>
            <div class="row g-2">
              @foreach(['linkedin', 'facebook', 'twitter', 'youtube'] as $social)
              <div class="col-md-6">
                <div class="input-group mb-1">
                  <span class="input-group-text"><i class="ti tabler-brand-{{ $social }}"></i></span>
                  <input type="text" class="form-control" id="modal-specialist-social-{{ $social }}" placeholder="{{ ucfirst($social) }}">
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-specialist-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteSpecialistModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá bác sĩ này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-specialist">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>

  .specialists-tab-content.tab-content,
  #specialistsSectionTabContent.tab-content {
    padding: 0 !important;
  }
  .specialists-table td, .specialists-table th { padding: 0.2rem 0.35rem !important; vertical-align: middle !important; }
  .specialists-table .specialist-thumb { width: 36px; height: 36px; object-fit: cover; }
  .specialists-table .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  .specialist-modal-avatar-wrap .rounded-circle { width: 64px; height: 64px; }
  .specialist-modal-avatar-wrap .no-photo { width: 64px; height: 64px; }
  /* Cột đều size, cột ảnh rộng và cách cột tên */
  #specialists-table { table-layout: fixed; width: 100%; }
  #specialists-table .col-num { width: 3%; }
  #specialists-table .col-img { width: 10%; min-width: 56px; padding-left: 0.5rem; padding-right: 0.75rem !important; }
  #specialists-table .col-name { width: 18%; padding-left: 0.5rem !important; }
  #specialists-table .col-specialty { width: 18%; }
  #specialists-table .col-socials { width: 35%; }
  #specialists-table .col-actions { width: 12%; }
  #specialists-table td .text-break { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; display: inline-block; }
  #specialists-table .specialist-avatar-wrap { display: inline-block; }
  #specialists-table tbody tr td:nth-child(2) { padding-left: 0.5rem; padding-right: 0.75rem !important; }
  #specialists-table tbody tr td:nth-child(3) { padding-left: 0.5rem !important; }
</style>
<script>
$(function() {
  var modalSpecialistDataUrl = null;
  var editingSpecialistRow = null;

  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexSpecialists() {
    $('#specialists-container .specialist-item').each(function(idx) {
      $(this).find('.specialist-index').text(idx + 1);
      $(this).find('[name^="items["]').each(function() {
        var n = $(this).attr('name');
        if (!n) return;
        $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']'));
      });
    });
  }
  function socialsSummary(s) {
    var parts = [];
    ['linkedin','facebook','twitter','youtube'].forEach(function(k){ if (s[k]) parts.push(k); });
    return parts.join(', ');
  }
  function setSpecialistRowDisplay($row, data) {
    $row.find('.cell-name').text(data.name || '');
    $row.find('.cell-specialty').text(data.specialty || '');
    $row.find('.cell-btn-text').text(data.button_text || '');
    $row.find('.cell-btn-link').text(data.button_link || '');
    $row.find('.cell-socials').text(socialsSummary(data.socials || {}));
    $row.find('input[name*="[name]"]').val(data.name || '');
    $row.find('input[name*="[specialty]"]').val(data.specialty || '');
    $row.find('input[name*="[button_text]"]').val(data.button_text || '');
    $row.find('input[name*="[button_link]"]').val(data.button_link || '');
    ['linkedin','facebook','twitter','youtube'].forEach(function(s) {
      $row.find('input[name*="[socials][' + s + ']"]').val((data.socials && data.socials[s]) || '');
    });
    if (data.thumbSrc) {
      $row.find('.specialist-thumb').attr('src', data.thumbSrc).removeClass('d-none');
      $row.find('.no-photo').addClass('d-none');
    }
  }

  $('#modal-specialist-image').on('change', function() {
    var input = this;
    var $img = $('#modal-specialist-preview');
    var $no = $('#modal-specialist-no-photo');
    modalSpecialistDataUrl = null;
    if (input.files && input.files[0]) {
      var r = new FileReader();
      r.onload = function(e) {
        modalSpecialistDataUrl = e.target.result;
        $img.attr('src', e.target.result).removeClass('d-none');
        $no.addClass('d-none');
      };
      r.readAsDataURL(input.files[0]);
    } else {
      $img.addClass('d-none').attr('src', '');
      $no.removeClass('d-none');
    }
  });

  var addModalEl = document.getElementById('specialistModal');
  var addModal = addModalEl ? new bootstrap.Modal(addModalEl) : null;

  $('#open-specialist-modal').on('click', function() {
    editingSpecialistRow = null;
    $('#specialistModalTitle').text('Thêm bác sĩ');
    $('#modal-specialist-name').val('');
    $('#modal-specialist-specialty').val('');
    $('#modal-specialist-button-text').val('');
    $('#modal-specialist-button-link').val('');
    $('#modal-specialist-social-linkedin, #modal-specialist-social-facebook, #modal-specialist-social-twitter, #modal-specialist-social-youtube').val('');
    $('#modal-specialist-image').val('');
    modalSpecialistDataUrl = null;
    $('#modal-specialist-preview').addClass('d-none').attr('src', '');
    $('#modal-specialist-no-photo').removeClass('d-none');
    if (addModal) addModal.show();
  });

  $(document).on('click', '.edit-specialist-item', function() {
    var $row = $(this).closest('.specialist-item');
    editingSpecialistRow = $row;
    $('#specialistModalTitle').text('Sửa bác sĩ');
    $('#modal-specialist-name').val($row.find('input[name*="[name]"]').val());
    $('#modal-specialist-specialty').val($row.find('input[name*="[specialty]"]').val());
    $('#modal-specialist-button-text').val($row.find('input[name*="[button_text]"]').val());
    $('#modal-specialist-button-link').val($row.find('input[name*="[button_link]"]').val());
    $('#modal-specialist-social-linkedin').val($row.find('input[name*="[socials][linkedin]"]').val());
    $('#modal-specialist-social-facebook').val($row.find('input[name*="[socials][facebook]"]').val());
    $('#modal-specialist-social-twitter').val($row.find('input[name*="[socials][twitter]"]').val());
    $('#modal-specialist-social-youtube').val($row.find('input[name*="[socials][youtube]"]').val());
    $('#modal-specialist-image').val('');
    modalSpecialistDataUrl = null;
    var src = $row.find('.specialist-thumb').attr('src');
    if (src) { $('#modal-specialist-preview').attr('src', src).removeClass('d-none'); $('#modal-specialist-no-photo').addClass('d-none'); }
    else { $('#modal-specialist-preview').addClass('d-none'); $('#modal-specialist-no-photo').removeClass('d-none'); }
    if (addModal) addModal.show();
  });

  $('#save-specialist-modal').on('click', function() {
    var name = $('#modal-specialist-name').val();
    var specialty = $('#modal-specialist-specialty').val();
    var btnText = $('#modal-specialist-button-text').val();
    var btnLink = $('#modal-specialist-button-link').val();
    var socials = {
      linkedin: $('#modal-specialist-social-linkedin').val(),
      facebook: $('#modal-specialist-social-facebook').val(),
      twitter: $('#modal-specialist-social-twitter').val(),
      youtube: $('#modal-specialist-social-youtube').val(),
    };

    if (editingSpecialistRow && editingSpecialistRow.length) {
      setSpecialistRowDisplay(editingSpecialistRow, { name: name, specialty: specialty, button_text: btnText, button_link: btnLink, socials: socials });
      if (modalSpecialistDataUrl) {
        editingSpecialistRow.find('.specialist-thumb').attr('src', modalSpecialistDataUrl).removeClass('d-none');
        editingSpecialistRow.find('.no-photo').addClass('d-none');
      }
      var rowInput = editingSpecialistRow.find('.specialist-file-input')[0];
      var modalInput = document.getElementById('modal-specialist-image');
      if (modalInput.files && modalInput.files.length && rowInput) {
        var dt = new DataTransfer();
        dt.items.add(modalInput.files[0]);
        rowInput.files = dt.files;
      }
      editingSpecialistRow = null;
      if (addModal) addModal.hide();
      return;
    }

    var i = $('#specialists-container .specialist-item').length;
    var thumbSrc = modalSpecialistDataUrl ? ' src="' + modalSpecialistDataUrl + '"' : '';
    var thumbCl = modalSpecialistDataUrl ? ' specialist-thumb' : ' specialist-thumb d-none';
    var noCl = 'no-photo rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white' + (modalSpecialistDataUrl ? ' d-none' : '');

    var rowHtml = '<tr class="specialist-item">'
      + '<td class="text-muted py-1 px-1 specialist-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-1"><div class="specialist-avatar-wrap"><img' + thumbSrc + ' class="rounded-circle object-fit-cover' + thumbCl + '" width="36" height="36" alt="">'
      + '<span class="' + noCl + '" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>'
      + '<input type="file" name="items[' + i + '][image_file]" class="d-none specialist-file-input" accept="image/*"></div></td>'
      + '<td class="py-1 px-1"><span class="cell-name text-break">' + esc(name) + '</span><input type="hidden" name="items[' + i + '][name]" value="' + esc(name) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-specialty text-break">' + esc(specialty) + '</span><input type="hidden" name="items[' + i + '][specialty]" value="' + esc(specialty) + '"></td>'
      + '<td class="py-1 px-1 d-none"><span class="cell-btn-text text-break">' + esc(btnText) + '</span><input type="hidden" name="items[' + i + '][button_text]" value="' + esc(btnText) + '"></td>'
      + '<td class="py-1 px-1 d-none"><span class="cell-btn-link text-break">' + esc(btnLink) + '</span><input type="hidden" name="items[' + i + '][button_link]" value="' + esc(btnLink) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-socials text-break">' + esc(socialsSummary(socials)) + '</span>'
      + '<input type="hidden" name="items[' + i + '][socials][linkedin]" value="' + esc(socials.linkedin) + '">'
      + '<input type="hidden" name="items[' + i + '][socials][facebook]" value="' + esc(socials.facebook) + '">'
      + '<input type="hidden" name="items[' + i + '][socials][twitter]" value="' + esc(socials.twitter) + '">'
      + '<input type="hidden" name="items[' + i + '][socials][youtube]" value="' + esc(socials.youtube) + '"></td>'
      + '<td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-specialist-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger remove-specialist btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';

    $('#specialists-container').append(rowHtml);
    var modalInput = document.getElementById('modal-specialist-image');
    var newRow = $('#specialists-container .specialist-item').eq(i);
    var rowInput = newRow.find('.specialist-file-input')[0];
    if (modalInput.files && modalInput.files.length && rowInput) {
      var dt = new DataTransfer();
      dt.items.add(modalInput.files[0]);
      rowInput.files = dt.files;
    }
    reindexSpecialists();
    if (addModal) addModal.hide();
  });

  var deleteModalEl = document.getElementById('deleteSpecialistModal');
  var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  var itemToRemove = null;
  $(document).on('click', '.remove-specialist', function() {
    itemToRemove = $(this).closest('.specialist-item');
    if (deleteModal) deleteModal.show();
  });
  $('#confirm-delete-specialist').on('click', function() {
    if (itemToRemove) {
      itemToRemove.remove();
      reindexSpecialists();
    }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
