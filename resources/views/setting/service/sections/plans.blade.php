<form action="{{ panel_route('setting.updateServicePlans') }}" method="POST" class="ajax-form" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <input type="hidden" name="type" value="travel">
  <input type="hidden" name="tab" value="plans">

  <div class="card mb-4 shadow-none border">
    <div class="card-header border-bottom">
      <ul class="nav nav-tabs card-header-tabs" id="plansTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="plans-info-tab" data-bs-toggle="tab" data-bs-target="#plans-info" type="button" role="tab" aria-controls="plans-info" aria-selected="true">
            Thông tin Section
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="plans-list-tab" data-bs-toggle="tab" data-bs-target="#plans-list" type="button" role="tab" aria-controls="plans-list" aria-selected="false">
            Bảng giá
          </button>
        </li>
      </ul>
    </div>
    <div class="card-body p-3">
      <div class="tab-content border-0 p-0 shadow-none">
        {{-- Tab: Thông tin Section --}}
        <div class="tab-pane fade show active p-0" id="plans-info" role="tabpanel" aria-labelledby="plans-info-tab">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small">Tiêu đề chính</label>
              <input type="text" name="title" class="form-control" value="{{ $v['title'] ?? '' }}" placeholder="Choose Your Optimal Plan">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Mô tả</label>
              <textarea name="description" class="form-control" rows="1" placeholder="It is a long established fact...">{{ $v['description'] ?? '' }}</textarea>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
              <div class="card shadow-none border">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center py-2">
                  <h6 class="mb-0 small fw-bold">Danh sách tính năng hệ thống</h6>
                  <button type="button" class="btn btn-xs btn-primary" id="add-feature-pool-item">
                    <i class="ti tabler-plus"></i> Thêm tính năng
                  </button>
                </div>
                <div class="card-body p-3">
                  <p class="text-muted small mb-2">Thêm tính năng ở đây để có thể tích chọn cho từng gói bên tab "Bảng giá".</p>
                  <div id="features-pool-container" class="row g-2">
                    @foreach($v['features_pool'] ?? [] as $fItem)
                    <div class="col-md-4 feature-pool-item">
                      <div class="input-group input-group-sm">
                        <input type="text" name="features_pool[]" class="form-control" value="{{ e($fItem) }}" placeholder="Tên tính năng...">
                        <button type="button" class="btn btn-outline-danger remove-feature-pool-item"><i class="ti tabler-trash"></i></button>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Tab: Danh sách gói (bảng) --}}
        <div class="tab-pane fade p-0" id="plans-list" role="tabpanel" aria-labelledby="plans-list-tab">
          <div class="card shadow-none border">
            <div class="card-header border-bottom d-flex justify-content-end align-items-center py-2">
              <button type="button" class="btn btn-sm btn-primary" id="open-plan-modal">
                <i class="ti tabler-plus"></i> Thêm gói
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-sm" id="plans-table">
                  <thead class="table-dark">
                    <tr>
                      <th class="py-1 px-1" style="width: 32px;">#</th>
                      <th class="py-1 px-1">Tên gói</th>
                      <th class="py-1 px-1">Giá / Chu kỳ</th>
                      <th class="py-1 px-1">Nút bấm & Link</th>
                      <th class="py-1 px-1">Tính năng</th>
                      <th class="py-1 px-1 text-center" style="width: 70px;">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody id="plans-container" class="small">
                    @foreach($v['items'] ?? [] as $index => $item)
                    <tr class="plan-item">
                      <td class="text-muted py-1 px-1 plan-index">{{ $index + 1 }}</td>
                      <td class="py-1 px-1">
                        <div class="d-flex align-items-center">
                          @if(!empty($item['image_url']))
                          <img src="{{ $item['image_url'] }}" width="30" height="30" class="rounded me-2 border object-fit-cover cell-img-preview">
                          @else
                          <div class="me-2 border rounded bg-light d-flex align-items-center justify-content-center cell-img-preview" style="width:30px; height:30px;"><i class="ti tabler-photo small"></i></div>
                          @endif
                          <span class="cell-name fw-bold">{{ $item['name'] ?? '' }}</span>
                        </div>
                        <input type="hidden" name="items[{{ $index }}][name]" value="{{ e($item['name'] ?? '') }}">
                        <input type="hidden" name="items[{{ $index }}][image_url]" value="{{ $item['image_url'] ?? '' }}">
                        <input type="hidden" name="items[{{ $index }}][original_image_url]" value="{{ $item['image_url'] ?? '' }}">
                        <input type="hidden" name="items[{{ $index }}][remove_image]" value="0">
                        <!-- Container chứa input file sẽ được clone vào đây -->
                        <div class="file-input-wrapper" style="display:none"></div>
                      </td>
                      <td class="py-1 px-1">
                        <span class="cell-price text-primary fw-bold">{{ $item['price'] ?? '' }}</span> / <span class="cell-period">{{ $item['period'] ?? '' }}</span>
                        <input type="hidden" name="items[{{ $index }}][price]" value="{{ e($item['price'] ?? '') }}">
                        <input type="hidden" name="items[{{ $index }}][period]" value="{{ e($item['period'] ?? '') }}">
                      </td>
                      <td class="py-1 px-1">
                        <div class="small fw-bold cell-btn-text">{{ $item['btn_text'] ?? '' }}</div>
                        <div class="text-muted cell-btn-link text-truncate" style="max-width: 150px;">{{ $item['btn_link'] ?? '' }}</div>
                        <input type="hidden" name="items[{{ $index }}][btn_text]" value="{{ e($item['btn_text'] ?? '') }}">
                        <input type="hidden" name="items[{{ $index }}][btn_link]" value="{{ e($item['btn_link'] ?? '') }}">
                      </td>
                      <td class="py-1 px-1">
                        @php $featureCount = is_array($item['features'] ?? null) ? count($item['features']) : 0; @endphp
                        <span class="cell-features-count badge bg-label-info">{{ $featureCount }} tính năng</span>

                        @if(is_array($item['features'] ?? null))
                        @foreach($item['features'] as $fIdx => $fValue)
                        <input type="hidden" name="items[{{ $index }}][features][]" value="{{ e($fValue) }}">
                        @endforeach
                        @endif

                        @if(is_array($item['treatment_steps'] ?? null))
                        @foreach($item['treatment_steps'] as $sIdx => $step)
                        <input type="hidden" name="items[{{ $index }}][treatment_steps][{{ $sIdx }}][step_number]" value="{{ e($step['step_number'] ?? '') }}">
                        <input type="hidden" name="items[{{ $index }}][treatment_steps][{{ $sIdx }}][title]" value="{{ e($step['title'] ?? '') }}">
                        <input type="hidden" name="items[{{ $index }}][treatment_steps][{{ $sIdx }}][description]" value="{{ e($step['description'] ?? '') }}">
                        @endforeach
                        @endif
                      </td>
                      <td class="py-1 px-1 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                          <button type="button" class="btn btn-xs btn-outline-primary edit-plan-item btn-action-icon" title="Sửa"><i class="ti tabler-pencil"></i></button>
                          <button type="button" class="btn btn-xs btn-outline-danger btn-remove-plan btn-action-icon" title="Xoá"><i class="ti tabler-trash"></i></button>
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

<!-- Modal thêm/sửa gói -->
<div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="planModalTitle">Thêm gói dịch vụ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-bold small">Tên gói (VD: Miễn phí , 30.000 vnđ)</label>
            <input type="text" class="form-control" id="modal-plan-name">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold small">Giá</label>
            <input type="text" class="form-control" id="modal-plan-price">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-bold small">Chu kỳ (VD: Tháng , Trọn đời)</label>
            <input type="text" class="form-control" id="modal-plan-period">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Text nút bấm</label>
            <input type="text" class="form-control" id="modal-plan-btn-text">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold small">Link nút bấm</label>
            <input type="text" class="form-control" id="modal-plan-btn-link">
          </div>
          <div class="col-12">
            <label class="form-label fw-bold small mb-3">Chọn tính năng (Cập nhật danh sách ở tab "Thông tin Section"):</label>
            <div id="modal-features-pool-selection" class="row g-2 border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
              <!-- Will be populated by JS from features-pool-container inputs -->
            </div>
            <div class="col-12 mt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold small mb-0">
                  Liệu trình điều trị
                </label>

                <button type="button"
                  class="btn btn-xs btn-primary"
                  id="add-treatment-step">
                  <i class="ti tabler-plus"></i>
                  Thêm bước
                </button>
              </div>

              <div id="modal-treatment-steps"
                class="border rounded p-3 bg-light">

                <div class="text-muted small text-center py-2 empty-treatment-steps">
                  Chưa có bước điều trị nào
                </div>

              </div>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label fw-bold small">Hình ảnh đại diện</label>
            <div class="d-flex align-items-start align-items-sm-center gap-3">
              <!-- Chỗ hiển thị ảnh preview -->
              <img src="{{ asset('assets/img/illustrations/page-pricing-basic.png') }}"
                alt="plan-image" class="d-block rounded border"
                id="modal-preview-image" width="80" height="80" style="object-fit: cover;">

              <div class="button-wrapper">
                <label for="modal-plan-image-input" class="btn btn-sm btn-outline-primary me-2 mb-2" tabindex="0">
                  <span class="d-none d-sm-block">Chọn ảnh mới</span>
                  <i class="ti tabler-upload d-block d-sm-none"></i>
                  <input type="file" id="modal-plan-image-input" class="account-file-input" hidden accept="image/png, image/jpeg">
                </label>
                <button type="button" class="btn btn-sm btn-outline-danger mb-2" id="reset-modal-image">
                  <i class="ti tabler-trash"></i>
                  Xóa ảnh
                </button>
                <div class="text-muted small">JPG, PNG, SVG hoặc WEBP. Tối đa 800K</div>
                <!-- Input ẩn để giữ URL ảnh cũ khi sửa -->
                <input type="hidden" id="modal-plan-image-url">
                <input type="hidden" id="modal-plan-original-image-url">
                <input type="hidden" id="modal-plan-remove-image" value="0">
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button>
        <button type="button" class="btn btn-primary btn-sm" id="save-plan-modal">Lưu</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')

<style>
#plans-table td,
#plans-table th {
    padding: 0.4rem 0.5rem !important;
    vertical-align: middle !important;
}

.btn-action-icon {
    width: 28px;
    height: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

#modal-features-pool-selection::-webkit-scrollbar {
    width: 5px;
}

#modal-features-pool-selection::-webkit-scrollbar-thumb {
    background: #4b5075;
    border-radius: 10px;
}

/*
|--------------------------------------------------------------------------
| Treatment Steps UI
|--------------------------------------------------------------------------
*/

#modal-treatment-steps {
    max-height: 340px;
    overflow-y: auto;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 10px;
    padding: 12px;
}

/* scrollbar */

#modal-treatment-steps::-webkit-scrollbar {
    width: 6px;
}

#modal-treatment-steps::-webkit-scrollbar-thumb {
    background: #4b5075;
    border-radius: 10px;
}

#modal-treatment-steps::-webkit-scrollbar-track {
    background: transparent;
}

/* card */

.treatment-step-item {
    margin-bottom: 12px;
}

.treatment-step-item:last-child {
    margin-bottom: 0 !important;
}

.treatment-step-card {
    background: #313553;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 12px !important;
    padding: 14px !important;
    transition: all .2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.treatment-step-card:hover {
    border-color: rgba(0, 255, 200, 0.25) !important;
    transform: translateY(-1px);
}

/* title */

.treatment-step-card .treatment-step-label {
    color: #ffffff;
    font-weight: 600;
    font-size: 13px;
}

/* inputs */

.treatment-step-card .form-label {
    color: #cfd3ec;
    font-size: 12px;
    margin-bottom: 4px;
}

.treatment-step-card .form-control {
    background: #272b45 !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    color: #fff !important;
    border-radius: 8px;
    min-height: 34px;
}

.treatment-step-card textarea.form-control {
    resize: vertical;
    min-height: 38px;
}

.treatment-step-card .form-control:focus {
    border-color: #00cfe8 !important;
    box-shadow: 0 0 0 .15rem rgba(0,207,232,.15);
    background: #272b45 !important;
    color: #fff !important;
}

/* placeholder */

.treatment-step-card textarea.form-control::placeholder,
.treatment-step-card input.form-control::placeholder {
    color: rgba(255,255,255,0.35);
}

/* remove button */

.remove-treatment-step {
    width: 28px;
    height: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* empty state */

.empty-treatment-steps {
    color: rgba(255,255,255,0.45);
    font-size: 13px;
    padding: 18px 0;
}
</style>

<script>
  $(function() {
    var editingPlanRow = null;
    var pModalEl = document.getElementById('planModal');
    var pModal = pModalEl ? new bootstrap.Modal(pModalEl) : null;
    var DEFAULT_IMG = "{{ asset('assets/img/illustrations/page-pricing-basic.jpg') }}";

    function esc(value) {
      return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function slug(value) {
      return String(value || '')
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-');
    }

    function syncModalFeaturesSelection(checkedFeatures) {
      checkedFeatures = checkedFeatures || [];

      var $poolContent = $('#modal-features-pool-selection').empty();
      var poolItems = [];

      $('input[name="features_pool[]"]').each(function() {
        var value = $(this).val();

        if (value) {
          poolItems.push(value);
        }
      });

      if (poolItems.length === 0) {
        $poolContent.html('<div class="col-12 text-center py-3 text-muted small">Hãy thêm tính năng ở tab "Thông tin Section" trước.</div>');
        return;
      }

      poolItems.forEach(function(feature) {
        var id = 'feat-' + slug(feature);
        var checked = checkedFeatures.includes(feature) ? 'checked' : '';

        $poolContent.append(`
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input feature-checkbox"
                               type="checkbox"
                               value="${esc(feature)}"
                               id="${id}"
                               ${checked}>
                        <label class="form-check-label small" for="${id}">
                            ${esc(feature)}
                        </label>
                    </div>
                </div>
            `);
      });
    }

    function renderTreatmentSteps(steps) {
      steps = Array.isArray(steps) ? steps : [];

      var $wrap = $('#modal-treatment-steps');
      $wrap.empty();

      if (steps.length === 0) {
        $wrap.html('<div class="text-muted small text-center py-2 empty-treatment-steps">Chưa có bước điều trị nào</div>');
        return;
      }

      steps.forEach(function(step, index) {
        appendTreatmentStep(step, index);
      });
    }

    function appendTreatmentStep(step, index) {
      step = step || {};

      var $wrap = $('#modal-treatment-steps');
      $wrap.find('.empty-treatment-steps').remove();

      var currentIndex = index !== undefined && index !== null ?
        index :
        $wrap.find('.treatment-step-item').length;

      var stepNumber = step.step_number || (currentIndex + 1);
      var title = step.title || '';
      var description = step.description || '';

      $wrap.append(`
            <div class="treatment-step-item border rounded p-2 mb-2 treatment-step-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small treatment-step-label">Bước ${currentIndex + 1}</strong>
                    <button type="button" class="btn btn-xs btn-outline-danger remove-treatment-step">
                        <i class="ti tabler-trash"></i>
                    </button>
                </div>

                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Số bước</label>
                        <input type="text"
                               class="form-control form-control-sm treatment-step-number"
                               value="${esc(stepNumber)}"
                               placeholder="1">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small mb-1">Tên bước</label>
                        <input type="text"
                               class="form-control form-control-sm treatment-step-title"
                               value="${esc(title)}"
                               placeholder="Thăm khám ban đầu">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small mb-1">Nội dung</label>
                        <textarea class="form-control form-control-sm treatment-step-description"
                                  rows="1"
                                  placeholder="Nhập nội dung bước...">${esc(description)}</textarea>
                    </div>
                </div>
            </div>
        `);

      reindexTreatmentSteps();
    }

    function reindexTreatmentSteps() {
      $('#modal-treatment-steps .treatment-step-item').each(function(index) {
        $(this).find('.treatment-step-label').text('Bước ' + (index + 1));

        var $numberInput = $(this).find('.treatment-step-number');

        if (!$numberInput.val()) {
          $numberInput.val(index + 1);
        }
      });
    }

    function collectTreatmentSteps() {
      return $('#modal-treatment-steps .treatment-step-item').map(function() {
        return {
          step_number: $(this).find('.treatment-step-number').val(),
          title: $(this).find('.treatment-step-title').val(),
          description: $(this).find('.treatment-step-description').val()
        };
      }).get().filter(function(step) {
        return step.step_number || step.title || step.description;
      });
    }

    function getRowTreatmentSteps($row) {
      var stepsMap = {};

      $row.find('input[name*="[treatment_steps]"]').each(function() {
        var name = $(this).attr('name') || '';
        var match = name.match(/\[treatment_steps\]\[(\d+)\]\[(step_number|title|description)\]/);

        if (!match) {
          return;
        }

        var stepIndex = match[1];
        var field = match[2];

        if (!stepsMap[stepIndex]) {
          stepsMap[stepIndex] = {};
        }

        stepsMap[stepIndex][field] = $(this).val();
      });

      return Object.keys(stepsMap)
        .sort(function(a, b) {
          return Number(a) - Number(b);
        })
        .map(function(key) {
          return stepsMap[key];
        });
    }

    function buildTreatmentStepInputs(itemIndex, steps) {
      if (!Array.isArray(steps)) {
        return '';
      }

      return steps.map(function(step, stepIndex) {
        return `
                <input type="hidden" name="items[${itemIndex}][treatment_steps][${stepIndex}][step_number]" value="${esc(step.step_number)}">
                <input type="hidden" name="items[${itemIndex}][treatment_steps][${stepIndex}][title]" value="${esc(step.title)}">
                <input type="hidden" name="items[${itemIndex}][treatment_steps][${stepIndex}][description]" value="${esc(step.description)}">
            `;
      }).join('');
    }

    function buildPlanRowHtml(index, data) {
      var featureInputs = (data.features || []).map(function(feature) {
        return `<input type="hidden" name="items[${index}][features][]" value="${esc(feature)}">`;
      }).join('');

      var treatmentStepInputs = buildTreatmentStepInputs(index, data.treatment_steps || []);

      var imgHtml = data.image_url ?
        `<img src="${esc(data.image_url)}" width="30" height="30" class="rounded me-2 border object-fit-cover cell-img-preview">` :
        `<div class="me-2 border rounded bg-light d-flex align-items-center justify-content-center cell-img-preview" style="width:30px; height:30px;"><i class="ti tabler-photo small"></i></div>`;

      return `
            <tr class="plan-item">
                <td class="text-muted py-1 px-1 plan-index">${index + 1}</td>

                <td class="py-1 px-1">
                    <div class="d-flex align-items-center">
                        ${imgHtml}
                        <span class="cell-name fw-bold">${esc(data.name)}</span>
                    </div>

                    <input type="hidden" name="items[${index}][name]" value="${esc(data.name)}">
                    <input type="hidden" name="items[${index}][image_url]" value="${esc(data.image_url)}">
                    <input type="hidden" name="items[${index}][original_image_url]" value="${esc(data.original_image_url)}">
                    <input type="hidden" name="items[${index}][remove_image]" value="${data.remove_image ? 1 : 0}">
                    <div class="file-input-wrapper" style="display:none"></div>
                </td>

                <td class="py-1 px-1">
                    <span class="cell-price text-primary fw-bold">${esc(data.price)}</span>
                    /
                    <span class="cell-period">${esc(data.period)}</span>

                    <input type="hidden" name="items[${index}][price]" value="${esc(data.price)}">
                    <input type="hidden" name="items[${index}][period]" value="${esc(data.period)}">
                </td>

                <td class="py-1 px-1">
                    <div class="small fw-bold cell-btn-text">${esc(data.btn_text)}</div>
                    <div class="text-muted cell-btn-link text-truncate" style="max-width: 150px;">
                        ${esc(data.btn_link)}
                    </div>

                    <input type="hidden" name="items[${index}][btn_text]" value="${esc(data.btn_text)}">
                    <input type="hidden" name="items[${index}][btn_link]" value="${esc(data.btn_link)}">
                </td>

                <td class="py-1 px-1">
                    <span class="cell-features-count badge bg-label-info">
                        ${(data.features || []).length} tính năng
                    </span>

                    ${featureInputs}
                    ${treatmentStepInputs}
                </td>

                <td class="py-1 px-1 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <button type="button" class="btn btn-xs btn-outline-primary edit-plan-item btn-action-icon" title="Sửa">
                            <i class="ti tabler-pencil"></i>
                        </button>

                        <button type="button" class="btn btn-xs btn-outline-danger btn-remove-plan btn-action-icon" title="Xoá">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    function reindexPlans() {
      $('#plans-container .plan-item').each(function(index) {
        $(this).find('.plan-index').text(index + 1);

        $(this).find('input[name^="items["]').each(function() {
          var oldName = $(this).attr('name');

          if (oldName) {
            $(this).attr('name', oldName.replace(/items\[\d+\]/, 'items[' + index + ']'));
          }
        });
      });
    }

    $('#add-feature-pool-item').on('click', function() {
      $('#features-pool-container').append(`
            <div class="col-md-4 feature-pool-item">
                <div class="input-group input-group-sm">
                    <input type="text"
                           name="features_pool[]"
                           class="form-control"
                           value=""
                           placeholder="Tên tính năng...">

                    <button type="button" class="btn btn-outline-danger remove-feature-pool-item">
                        <i class="ti tabler-trash"></i>
                    </button>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.remove-feature-pool-item', function() {
      $(this).closest('.feature-pool-item').remove();
    });

    $('#add-treatment-step').on('click', function() {
      appendTreatmentStep();
    });

    $(document).on('click', '.remove-treatment-step', function() {
      $(this).closest('.treatment-step-item').remove();

      if ($('#modal-treatment-steps .treatment-step-item').length === 0) {
        renderTreatmentSteps([]);
        return;
      }

      reindexTreatmentSteps();
    });

    $('#open-plan-modal').on('click', function() {
      editingPlanRow = null;

      $('#planModalTitle').text('Thêm gói dịch vụ');
      $('#modal-plan-name').val('');
      $('#modal-plan-price').val('');
      $('#modal-plan-period').val('');
      $('#modal-plan-btn-text').val('');
      $('#modal-plan-btn-link').val('');
      $('#modal-plan-image-url').val('');
      $('#modal-plan-original-image-url').val('');
      $('#modal-plan-remove-image').val('0');
      $('#modal-preview-image').attr('src', DEFAULT_IMG);
      $('#modal-plan-image-input').val('');

      syncModalFeaturesSelection([]);
      renderTreatmentSteps([]);

      if (pModal) {
        pModal.show();
      }
    });

    $('#modal-plan-image-input').on('change', function() {
      if (this.files && this.files[0]) {
        $('#modal-preview-image').attr('src', URL.createObjectURL(this.files[0]));
        $('#modal-plan-remove-image').val('0');
      }
    });

    $('#reset-modal-image').on('click', function() {
      $('#modal-plan-image-input').val('');
      $('#modal-plan-image-url').val('');
      $('#modal-plan-remove-image').val('1');
      $('#modal-preview-image').attr('src', DEFAULT_IMG);
    });

    $(document).on('click', '.edit-plan-item', function() {
      editingPlanRow = $(this).closest('.plan-item');

      $('#planModalTitle').text('Sửa gói dịch vụ');
      $('#modal-plan-name').val(editingPlanRow.find('input[name*="[name]"]').val());
      $('#modal-plan-price').val(editingPlanRow.find('input[name*="[price]"]').val());
      $('#modal-plan-period').val(editingPlanRow.find('input[name*="[period]"]').val());
      $('#modal-plan-btn-text').val(editingPlanRow.find('input[name*="[btn_text]"]').val());
      $('#modal-plan-btn-link').val(editingPlanRow.find('input[name*="[btn_link]"]').val());

      var currentImg = editingPlanRow.find('input[name*="[image_url]"]').val();
      var originalImg = editingPlanRow.find('input[name*="[original_image_url]"]').val() || currentImg;
      var removeImage = editingPlanRow.find('input[name*="[remove_image]"]').val() === '1';
      var currentPreviewSrc = editingPlanRow.find('.cell-img-preview').attr('src');

      $('#modal-plan-image-url').val(currentImg);
      $('#modal-plan-original-image-url').val(originalImg);
      $('#modal-plan-remove-image').val(removeImage ? '1' : '0');
      $('#modal-preview-image').attr('src', removeImage ? DEFAULT_IMG : (currentPreviewSrc || DEFAULT_IMG));
      $('#modal-plan-image-input').val('');

      var checkedFeatures = [];

      editingPlanRow.find('input[name*="[features][]"]').each(function() {
        checkedFeatures.push($(this).val());
      });

      syncModalFeaturesSelection(checkedFeatures);
      renderTreatmentSteps(getRowTreatmentSteps(editingPlanRow));

      if (pModal) {
        pModal.show();
      }
    });

    $('#save-plan-modal').on('click', function() {
      var data = {
        name: $('#modal-plan-name').val(),
        price: $('#modal-plan-price').val(),
        period: $('#modal-plan-period').val(),
        btn_text: $('#modal-plan-btn-text').val(),
        btn_link: $('#modal-plan-btn-link').val(),
        image_url: $('#modal-plan-image-url').val(),
        original_image_url: $('#modal-plan-original-image-url').val(),
        remove_image: $('#modal-plan-remove-image').val() === '1',
        features: $('.feature-checkbox:checked').map(function() {
          return $(this).val();
        }).get(),
        treatment_steps: collectTreatmentSteps()
      };

      if (!data.name) {
        alert('Vui lòng nhập tên gói');
        return;
      }

      var index = editingPlanRow ?
        editingPlanRow.index() :
        $('#plans-container .plan-item').length;

      var $newRow = $(buildPlanRowHtml(index, data));
      var fileInput = document.getElementById('modal-plan-image-input');

      if (fileInput && fileInput.files && fileInput.files.length > 0) {
        var inputName = `items[${index}][image]`;

        var $clonedInput = $(fileInput).clone()
          .attr('name', inputName)
          .removeAttr('id')
          .addClass('d-none');

        $newRow.find('.file-input-wrapper').append($clonedInput);

        $newRow.find('.cell-img-preview').replaceWith(`
                <img src="${URL.createObjectURL(fileInput.files[0])}"
                     width="30"
                     height="30"
                     class="rounded me-2 border object-fit-cover cell-img-preview">
            `);
      }

      if (editingPlanRow) {
        editingPlanRow.replaceWith($newRow);
      } else {
        $('#plans-container').append($newRow);
      }

      reindexPlans();

      if (pModal) {
        pModal.hide();
      }
    });

    $(document).on('click', '.btn-remove-plan', function() {
      if (confirm('Xác nhận xoá gói này?')) {
        $(this).closest('.plan-item').remove();
        reindexPlans();
      }
    });
  });

</script>

@endpush
