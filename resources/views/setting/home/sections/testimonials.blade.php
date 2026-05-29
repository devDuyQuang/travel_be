<form action="{{ panel_route('setting.updateTestimonials') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="testimonialsTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="testimonials-title-tab" data-bs-toggle="tab" data-bs-target="#testimonials-title" type="button" role="tab" aria-controls="testimonials-title" aria-selected="true">
                Tiêu đề Section
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="testimonials-main-tab" data-bs-toggle="tab" data-bs-target="#testimonials-main" type="button" role="tab" aria-controls="testimonials-main" aria-selected="false">
                Ảnh chính & Floating Card
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="testimonials-achievement-tab" data-bs-toggle="tab" data-bs-target="#testimonials-achievement" type="button" role="tab" aria-controls="testimonials-achievement" aria-selected="false">
                Ô Thành tựu
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="testimonials-list-tab" data-bs-toggle="tab" data-bs-target="#testimonials-list" type="button" role="tab" aria-controls="testimonials-list" aria-selected="false">
                Danh sách ý kiến
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body pt-4">
          <div class="tab-content">
            {{-- Tab: Tiêu đề section --}}
            <div class="tab-pane fade show active" id="testimonials-title" role="tabpanel" aria-labelledby="testimonials-title-tab">
              <label class="form-label fw-bold">Tiêu đề chính Section</label>
              <input type="text" name="main_title" class="form-control" value="{{ $v['main_title'] ?? '' }}" placeholder="Real Patients, Real Stories">
            </div>

            {{-- Tab: Ảnh chính & floating card --}}
            <div class="tab-pane fade" id="testimonials-main" role="tabpanel" aria-labelledby="testimonials-main-tab">
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="mb-0">
                    <label class="form-label fw-bold">Ảnh nền lớn (525x640)</label>
                    <input type="file" name="main_image_file" class="form-control img-input" data-preview="preview-tmain">
                    <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                      <img src="{{ !empty($v['main_image']) ? asset($v['main_image']) : '' }}" id="preview-tmain" class="h-100 object-fit-contain {{ empty($v['main_image']) ? 'd-none' : '' }}">
                      @if(empty($v['main_image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="p-3 border rounded h-100">
                    <p class="fw-bold small mb-3 text-primary">Floating Review Card</p>
                    <div class="d-flex flex-column gap-3">
                      <div>
                        <label class="form-label small mb-1">Avatar</label>
                        <div class="d-flex align-items-center gap-3">
                          <img src="{{ !empty($v['floating_review']['avatar']) ? asset($v['floating_review']['avatar']) : '' }}" id="preview-tfloat" class="rounded-circle border flex-shrink-0 {{ empty($v['floating_review']['avatar']) ? 'd-none' : '' }}" width="56" height="56" style="object-fit: cover;">
                          <input type="file" name="floating_review_avatar" class="form-control form-control-sm img-input" data-preview="preview-tfloat" style="max-width: 200px;">
                        </div>
                      </div>
                      <div>
                        <label class="form-label small mb-1">Tên</label>
                        <input type="text" name="floating_review[name]" class="form-control form-control-sm" value="{{ $v['floating_review']['name'] ?? '' }}" placeholder="Tên người đánh giá">
                      </div>
                      <div>
                        <label class="form-label small mb-1">Đánh giá (1-5)</label>
                        <input type="number" name="floating_review[rating]" class="form-control form-control-sm" value="{{ $v['floating_review']['rating'] ?? 5 }}" min="1" max="5" style="max-width: 80px;">
                      </div>
                      <div>
                        <label class="form-label small mb-1">Nội dung đánh giá</label>
                        <textarea name="floating_review[text]" class="form-control form-control-sm" rows="3" placeholder="Nội dung đánh giá...">{{ $v['floating_review']['text'] ?? '' }}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Ô thành tựu --}}
            <div class="tab-pane fade" id="testimonials-achievement" role="tabpanel" aria-labelledby="testimonials-achievement-tab">
              <div class="row g-3">
                <div class="col-6">
                  <label class="form-label fw-bold">Con số</label>
                  <input type="text" name="achievement[number]" class="form-control" value="{{ $v['achievement']['number'] ?? '' }}">
                </div>
                <div class="col-6">
                  <label class="form-label fw-bold">Nội dung</label>
                  <input type="text" name="achievement[text]" class="form-control" value="{{ $v['achievement']['text'] ?? '' }}">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold">4 Avatar nhỏ</label>
                  <div class="row g-2">
                    @for($i=0;$i<4;$i++)
                      <div class="col-3 text-center">
                        <input type="file" name="achievement_avatars[{{ $i }}]" class="form-control form-control-sm img-input" data-preview="preview-tach-{{ $i }}">
                        <img src="{{ !empty($v['achievement']['avatars'][$i]) ? asset($v['achievement']['avatars'][$i]) : '' }}" id="preview-tach-{{ $i }}" class="mt-2 rounded-circle border {{ empty($v['achievement']['avatars'][$i]) ? 'd-none' : '' }}" width="40" height="40">
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Danh sách ý kiến --}}
            <div class="tab-pane fade" id="testimonials-list" role="tabpanel" aria-labelledby="testimonials-list-tab">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-testimonial-modal">
                    <i class="ti tabler-plus me-1"></i>Thêm ý kiến
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 testimonials-table table-sm">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1" style="width: 32px;">#</th>
                          <th class="py-1 px-1" style="width: 44px;">Ảnh</th>
                          <th class="py-1 px-1">Tiêu đề</th>
                          <th class="py-1 px-1">Tên</th>
                          <th class="py-1 px-1">Chức danh</th>
                          <th class="py-1 px-1 d-none">Link video</th>
                          <th class="py-1 px-1 col-review">Nội dung</th>
                          <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="testimonials-items-container" class="small">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <tr class="testimonial-item">
                          <td class="text-muted py-1 px-1 testimonial-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1">
                            <div class="testimonial-avatar-wrap">
                              @if(!empty($item['image']))
                                <img src="{{ asset($item['image']) }}" class="rounded-circle object-fit-cover testimonial-thumb" width="36" height="36" alt="">
                                <span class="no-photo d-none"></span>
                              @else
                                <img src="" class="rounded-circle object-fit-cover testimonial-thumb d-none" width="36" height="36" alt="">
                                <span class="no-photo rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>
                              @endif
                              <input type="file" name="items[{{ $index }}][image_file]" class="d-none testimonial-file-input" accept="image/*">
                            </div>
                          </td>
                          <td class="py-1 px-1"><span class="cell-title text-break">{{ $item['title'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][title]" value="{{ e($item['title'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-name text-break">{{ $item['name'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][name]" value="{{ e($item['name'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-role text-break">{{ $item['role'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][role]" value="{{ e($item['role'] ?? '') }}"></td>
                          <td class="py-1 px-1 d-none"><span class="cell-video text-break">{{ $item['video_link'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][video_link]" value="{{ e($item['video_link'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-review text-break">{{ Str::limit($item['review'] ?? '', 40) }}</span><input type="hidden" name="items[{{ $index }}][review]" value="{{ e($item['review'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-testimonial-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-testimonial-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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
<!-- Modal thêm/sửa ý kiến khách hàng -->
<div class="modal fade" id="testimonialModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testimonialModalTitle">Thêm ý kiến khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-bold">Ảnh</label>
            <div class="d-flex align-items-center gap-3">
              <div class="testimonial-modal-avatar-wrap flex-shrink-0">
                <img src="" id="modal-testimonial-preview" class="rounded-circle object-fit-cover d-none" width="64" height="64" alt="">
                <span id="modal-testimonial-no-photo" class="rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white"><i class="ti tabler-photo ti-lg"></i></span>
              </div>
              <input type="file" class="form-control" id="modal-testimonial-image" accept="image/*" style="max-width: 240px;">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Tiêu đề</label>
            <input type="text" class="form-control" id="modal-testimonial-title">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Tên</label>
            <input type="text" class="form-control" id="modal-testimonial-name">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Chức danh</label>
            <input type="text" class="form-control" id="modal-testimonial-role">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Link video</label>
            <input type="text" class="form-control" id="modal-testimonial-video-link">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Nội dung phản hồi</label>
            <textarea class="form-control" id="modal-testimonial-review" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-testimonial-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="testimonialDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá ý kiến này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-testimonial">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  .testimonials-table td, .testimonials-table th { padding: 0.2rem 0.35rem !important; vertical-align: middle !important; }
  .testimonials-table .testimonial-thumb { width: 36px; height: 36px; object-fit: cover; }
  .testimonials-table .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  .testimonial-modal-avatar-wrap .rounded-circle { width: 64px; height: 64px; }
  .testimonial-modal-avatar-wrap .no-photo { width: 64px; height: 64px; }
  .testimonials-table .col-review { max-width: 180px; }
  .testimonials-table .cell-review.text-break { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 180px; display: inline-block; }
</style>
<script>
$(function() {
  var modalTestimonialDataUrl = null;
  var editingTestimonialRow = null;

  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexTestimonials() {
    $('#testimonials-items-container .testimonial-item').each(function(idx) {
      $(this).find('.testimonial-index').text(idx + 1);
      $(this).find('[name^="items["]').each(function() {
        var n = $(this).attr('name');
        if (!n) return;
        $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']'));
      });
    });
  }
  function setRowDisplay($row, data) {
    $row.find('.cell-title').text(data.title || '');
    $row.find('.cell-name').text(data.name || '');
    $row.find('.cell-role').text(data.role || '');
    $row.find('.cell-video').text(data.video || '');
    $row.find('.cell-review').text((data.review || '').length > 40 ? (data.review || '').substring(0, 40) + '...' : (data.review || ''));
    $row.find('input[name*="[title]"]').val(data.title || '');
    $row.find('input[name*="[name]"]').val(data.name || '');
    $row.find('input[name*="[role]"]').val(data.role || '');
    $row.find('input[name*="[video_link]"]').val(data.video || '');
    $row.find('input[name*="[review]"]').val(data.review || '');
    if (data.thumbSrc) {
      $row.find('.testimonial-thumb').attr('src', data.thumbSrc).removeClass('d-none');
      $row.find('.no-photo').addClass('d-none');
    }
  }

  $('#modal-testimonial-image').on('change', function() {
    var input = this;
    var $img = $('#modal-testimonial-preview');
    var $no = $('#modal-testimonial-no-photo');
    modalTestimonialDataUrl = null;
    if (input.files && input.files[0]) {
      var r = new FileReader();
      r.onload = function(e) {
        modalTestimonialDataUrl = e.target.result;
        $img.attr('src', e.target.result).removeClass('d-none');
        $no.addClass('d-none');
      };
      r.readAsDataURL(input.files[0]);
    } else {
      $img.addClass('d-none').attr('src', '');
      $no.removeClass('d-none');
    }
  });

  var addModalEl = document.getElementById('testimonialModal');
  var addModal = addModalEl ? new bootstrap.Modal(addModalEl) : null;

  $('#open-testimonial-modal').on('click', function() {
    editingTestimonialRow = null;
    $('#testimonialModalTitle').text('Thêm ý kiến khách hàng');
    $('#modal-testimonial-title').val('');
    $('#modal-testimonial-name').val('');
    $('#modal-testimonial-role').val('');
    $('#modal-testimonial-video-link').val('');
    $('#modal-testimonial-review').val('');
    $('#modal-testimonial-image').val('');
    modalTestimonialDataUrl = null;
    $('#modal-testimonial-preview').addClass('d-none').attr('src', '');
    $('#modal-testimonial-no-photo').removeClass('d-none');
    if (addModal) addModal.show();
  });

  $(document).on('click', '.edit-testimonial-item', function() {
    var $row = $(this).closest('.testimonial-item');
    editingTestimonialRow = $row;
    $('#testimonialModalTitle').text('Sửa ý kiến khách hàng');
    $('#modal-testimonial-title').val($row.find('input[name*="[title]"]').val());
    $('#modal-testimonial-name').val($row.find('input[name*="[name]"]').val());
    $('#modal-testimonial-role').val($row.find('input[name*="[role]"]').val());
    $('#modal-testimonial-video-link').val($row.find('input[name*="[video_link]"]').val());
    $('#modal-testimonial-review').val($row.find('input[name*="[review]"]').val());
    $('#modal-testimonial-image').val('');
    modalTestimonialDataUrl = null;
    var src = $row.find('.testimonial-thumb').attr('src');
    if (src) { $('#modal-testimonial-preview').attr('src', src).removeClass('d-none'); $('#modal-testimonial-no-photo').addClass('d-none'); }
    else { $('#modal-testimonial-preview').addClass('d-none'); $('#modal-testimonial-no-photo').removeClass('d-none'); }
    if (addModal) addModal.show();
  });

  $('#save-testimonial-modal').on('click', function() {
    var title = $('#modal-testimonial-title').val();
    var name = $('#modal-testimonial-name').val();
    var role = $('#modal-testimonial-role').val();
    var video = $('#modal-testimonial-video-link').val();
    var review = $('#modal-testimonial-review').val() || '';

    if (editingTestimonialRow && editingTestimonialRow.length) {
      setRowDisplay(editingTestimonialRow, { title: title, name: name, role: role, video: video, review: review });
      if (modalTestimonialDataUrl) {
        editingTestimonialRow.find('.testimonial-thumb').attr('src', modalTestimonialDataUrl).removeClass('d-none');
        editingTestimonialRow.find('.no-photo').addClass('d-none');
      }
      var rowInput = editingTestimonialRow.find('.testimonial-file-input')[0];
      var modalInput = document.getElementById('modal-testimonial-image');
      if (modalInput.files && modalInput.files.length && rowInput) {
        var dt = new DataTransfer();
        dt.items.add(modalInput.files[0]);
        rowInput.files = dt.files;
      }
      editingTestimonialRow = null;
      if (addModal) addModal.hide();
      return;
    }

    var i = $('#testimonials-items-container .testimonial-item').length;
    var thumbSrc = modalTestimonialDataUrl ? ' src="' + modalTestimonialDataUrl + '"' : '';
    var thumbCl = modalTestimonialDataUrl ? ' testimonial-thumb' : ' testimonial-thumb d-none';
    var noCl = 'no-photo rounded-circle d-inline-flex align-items-center justify-content-center bg-secondary text-white' + (modalTestimonialDataUrl ? ' d-none' : '');
    var reviewShort = review.length > 40 ? review.substring(0, 40) + '...' : review;

    var rowHtml = '<tr class="testimonial-item">'
      + '<td class="text-muted py-1 px-1 testimonial-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-1"><div class="testimonial-avatar-wrap"><img' + thumbSrc + ' class="rounded-circle object-fit-cover' + thumbCl + '" width="36" height="36" alt="">'
      + '<span class="' + noCl + '" style="width:36px;height:36px;font-size:0.85rem"><i class="ti tabler-photo"></i></span>'
      + '<input type="file" name="items[' + i + '][image_file]" class="d-none testimonial-file-input" accept="image/*"></div></td>'
      + '<td class="py-1 px-1"><span class="cell-title text-break">' + esc(title) + '</span><input type="hidden" name="items[' + i + '][title]" value="' + esc(title) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-name text-break">' + esc(name) + '</span><input type="hidden" name="items[' + i + '][name]" value="' + esc(name) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-role text-break">' + esc(role) + '</span><input type="hidden" name="items[' + i + '][role]" value="' + esc(role) + '"></td>'
      + '<td class="py-1 px-1 d-none"><span class="cell-video text-break">' + esc(video) + '</span><input type="hidden" name="items[' + i + '][video_link]" value="' + esc(video) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-review text-break">' + esc(reviewShort) + '</span><input type="hidden" name="items[' + i + '][review]" value="' + esc(review) + '"></td>'
      + '<td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-testimonial-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger remove-testimonial-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';

    $('#testimonials-items-container').append(rowHtml);
    var modalInput = document.getElementById('modal-testimonial-image');
    var newRow = $('#testimonials-items-container .testimonial-item').eq(i);
    var rowInput = newRow.find('.testimonial-file-input')[0];
    if (modalInput.files && modalInput.files.length && rowInput) {
      var dt = new DataTransfer();
      dt.items.add(modalInput.files[0]);
      rowInput.files = dt.files;
    }
    reindexTestimonials();
    if (addModal) addModal.hide();
  });

  var deleteModalEl = document.getElementById('testimonialDeleteModal');
  var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
  var itemToRemove = null;
  $(document).on('click', '.remove-testimonial-item', function() {
    itemToRemove = $(this).closest('.testimonial-item');
    if (deleteModal) deleteModal.show();
  });
  $('#confirm-delete-testimonial').on('click', function() {
    if (itemToRemove) {
      itemToRemove.remove();
      reindexTestimonials();
    }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
