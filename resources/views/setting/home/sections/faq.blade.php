<form action="{{ panel_route('setting.updateFaq') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf
  @method('PUT')
  <div class="row g-3">
    <div class="col-md-12">
      <div class="card h-100 shadow-none border-0 bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" id="faqTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="faq-main-tab" data-bs-toggle="tab" data-bs-target="#faq-main" type="button" role="tab" aria-controls="faq-main" aria-selected="true">
                Nội dung & Hình ảnh
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="faq-contact-tab" data-bs-toggle="tab" data-bs-target="#faq-contact" type="button" role="tab" aria-controls="faq-contact" aria-selected="false">
                Thẻ liên hệ & Hẹn lịch
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="faq-list-tab" data-bs-toggle="tab" data-bs-target="#faq-list" type="button" role="tab" aria-controls="faq-list" aria-selected="false">
                Danh sách câu hỏi
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body pt-4">
          <div class="tab-content">
            {{-- Tab: Nội dung chính & hình ảnh --}}
            <div class="tab-pane fade show active" id="faq-main" role="tabpanel" aria-labelledby="faq-main-tab">
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label fw-bold">Tiêu đề chính (H2)</label>
                  <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Frequently Asked Questions">
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Mô tả (P)</label>
                  <textarea name="description" class="form-control" rows="3">{{ $v['description'] ?? '' }}</textarea>
                </div>
                <div class="col-md-12">
                  <label class="form-label fw-bold">Ảnh minh họa (700x850)</label>
                  <input type="file" name="image_file" class="form-control" id="faq-home-img">
                  <div class="mt-2 border rounded d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden;">
                    <img src="{{ !empty($v['image']) ? asset($v['image']) : '' }}" id="faq-home-preview" class="h-100 w-100 object-fit-contain {{ empty($v['image']) ? 'd-none' : '' }}">
                    @if(empty($v['image'])) <i class="ti tabler-photo ti-lg text-muted"></i> @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Tab: Thẻ liên hệ & hẹn lịch --}}
            <div class="tab-pane fade" id="faq-contact" role="tabpanel" aria-labelledby="faq-contact-tab">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label fw-bold small">Text liên hệ</label>
                  <input type="text" name="contact[text]" class="form-control" value="{{ $v['contact']['text'] ?? '' }}" placeholder="Contact us">
                </div>
                <div class="col-12">
                  <label class="form-label fw-bold small">Số điện thoại</label>
                  <input type="text" name="contact[phone]" class="form-control" value="{{ $v['contact']['phone'] ?? '' }}" placeholder="+1 123 456 7890">
                </div>
                <div class="col-6">
                  <label class="form-label fw-bold small">Text nút Appointment</label>
                  <input type="text" name="appointment_btn_text" class="form-control" value="{{ $v['appointment_btn']['text'] ?? '' }}" placeholder="Appointment">
                </div>
                <div class="col-6">
                  <label class="form-label fw-bold small">Link nút</label>
                  <input type="text" name="appointment_btn_link" class="form-control" value="{{ $v['appointment_btn']['link'] ?? '' }}" placeholder="#">
                </div>
              </div>
            </div>

            {{-- Tab: Danh sách câu hỏi (bảng) --}}
            <div class="tab-pane fade" id="faq-list" role="tabpanel" aria-labelledby="faq-list-tab">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
                  <button type="button" class="btn btn-sm btn-primary" id="open-faq-modal">
                    <i class="ti tabler-plus me-1"></i>Thêm câu hỏi
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-sm" id="faq-table">
                      <thead class="table-dark">
                        <tr>
                          <th class="py-1 px-1" style="width: 32px;">#</th>
                          <th class="py-1 px-1">Câu hỏi</th>
                          <th class="py-1 px-1">Câu trả lời</th>
                          <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="faq-home-container" class="small">
                        @foreach($v['items'] ?? [] as $index => $item)
                        <tr class="faq-item">
                          <td class="text-muted py-1 px-1 faq-index">{{ $index + 1 }}</td>
                          <td class="py-1 px-1"><span class="cell-question text-break">{{ $item['question'] ?? '' }}</span><input type="hidden" name="items[{{ $index }}][question]" value="{{ e($item['question'] ?? '') }}"></td>
                          <td class="py-1 px-1"><span class="cell-answer text-break">{{ Str::limit($item['answer'] ?? '', 50) }}</span><input type="hidden" name="items[{{ $index }}][answer]" value="{{ e($item['answer'] ?? '') }}"></td>
                          <td class="py-1 px-1 text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                              <button type="button" class="btn btn-xs btn-outline-primary edit-faq-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-outline-danger remove-faq-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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

<!-- Modal thêm/sửa câu hỏi -->
<div class="modal fade" id="faqModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="faqModalTitle">Thêm câu hỏi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Câu hỏi</label>
          <input type="text" class="form-control" id="modal-faq-question">
        </div>
        <div class="mb-0">
          <label class="form-label fw-bold">Câu trả lời</label>
          <textarea class="form-control" id="modal-faq-answer" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-faq-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteFaqHomeModal" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Xác nhận xoá</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">Bạn có chắc muốn xoá câu hỏi này không?</div>
    <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-danger" id="confirm-delete-faq-home">Xoá</button></div>
  </div></div>
</div>
@push('scripts')
<style>
  #faq-table td, #faq-table th { padding: 0.2rem 0.35rem !important; vertical-align: middle !important; }
  #faq-table .btn-action-icon { width: 30px; min-width: 30px; padding: 0.2rem; display: inline-flex; align-items: center; justify-content: center; }
  #faq-table .cell-answer.text-break { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; }
</style>
<script>
$(function() {
  $('#faq-home-img').on('change', function() {
    var f = this.files[0];
    if (f) { var r = new FileReader(); r.onload = function(e) { $('#faq-home-preview').attr('src', e.target.result).removeClass('d-none').siblings().addClass('d-none'); }; r.readAsDataURL(f); }
  });

  var editingFaqRow = null;
  function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
  function reindexFaq() {
    $('#faq-home-container .faq-item').each(function(idx) {
      $(this).find('.faq-index').text(idx + 1);
      $(this).find('[name^="items["]').each(function() {
        var n = $(this).attr('name');
        if (!n) return;
        $(this).attr('name', n.replace(/items\[\d+\]/, 'items[' + idx + ']'));
      });
    });
  }
  function setFaqRowDisplay($row, data) {
    $row.find('.cell-question').text(data.question || '');
    $row.find('.cell-answer').text((data.answer || '').length > 50 ? (data.answer || '').substring(0, 50) + '...' : (data.answer || ''));
    $row.find('input[name*="[question]"]').val(data.question || '');
    $row.find('input[name*="[answer]"]').val(data.answer || '');
  }

  var faqModalEl = document.getElementById('faqModal');
  var faqModal = faqModalEl ? new bootstrap.Modal(faqModalEl) : null;

  $('#open-faq-modal').on('click', function() {
    editingFaqRow = null;
    $('#faqModalTitle').text('Thêm câu hỏi');
    $('#modal-faq-question').val('');
    $('#modal-faq-answer').val('');
    if (faqModal) faqModal.show();
  });

  $(document).on('click', '.edit-faq-item', function() {
    var $row = $(this).closest('.faq-item');
    editingFaqRow = $row;
    $('#faqModalTitle').text('Sửa câu hỏi');
    $('#modal-faq-question').val($row.find('input[name*="[question]"]').val());
    $('#modal-faq-answer').val($row.find('input[name*="[answer]"]').val());
    if (faqModal) faqModal.show();
  });

  $('#save-faq-modal').on('click', function() {
    var question = $('#modal-faq-question').val();
    var answer = $('#modal-faq-answer').val();
    var data = { question: question, answer: answer };

    if (editingFaqRow && editingFaqRow.length) {
      setFaqRowDisplay(editingFaqRow, data);
      editingFaqRow = null;
      if (faqModal) faqModal.hide();
      return;
    }

    var i = $('#faq-home-container .faq-item').length;
    var answerShort = (answer || '').length > 50 ? (answer || '').substring(0, 50) + '...' : (answer || '');
    var rowHtml = '<tr class="faq-item">'
      + '<td class="text-muted py-1 px-1 faq-index">' + (i + 1) + '</td>'
      + '<td class="py-1 px-1"><span class="cell-question text-break">' + esc(question) + '</span><input type="hidden" name="items[' + i + '][question]" value="' + esc(question) + '"></td>'
      + '<td class="py-1 px-1"><span class="cell-answer text-break">' + esc(answerShort) + '</span><input type="hidden" name="items[' + i + '][answer]" value="' + esc(answer) + '"></td>'
      + '<td class="py-1 px-1 text-center"><div class="d-flex align-items-center justify-content-center gap-1"><button type="button" class="btn btn-xs btn-outline-primary edit-faq-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>'
      + '<button type="button" class="btn btn-xs btn-outline-danger remove-faq-item btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button></div></td></tr>';
    $('#faq-home-container').append(rowHtml);
    reindexFaq();
    if (faqModal) faqModal.hide();
  });

  var deleteModal = document.getElementById('deleteFaqHomeModal') && new bootstrap.Modal(document.getElementById('deleteFaqHomeModal'));
  var faqToRemove = null;
  $(document).on('click', '.remove-faq-item', function() { faqToRemove = $(this).closest('.faq-item'); if (deleteModal) deleteModal.show(); });
  $('#confirm-delete-faq-home').on('click', function() {
    if (faqToRemove) { faqToRemove.remove(); reindexFaq(); }
    if (deleteModal) deleteModal.hide();
  });
});
</script>
@endpush
