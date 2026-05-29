<form method="POST" enctype="multipart/form-data" id="insurance-upload-form"
      data-action="{{ panel_route('setting.updateAboutInsurance') }}"
      data-type="{{ $settingType ?? 'clinic' }}">
  @csrf
  @method('PUT')
  @php $vi = $v ?? []; $logos = $logos ?? []; $settingTypeVal = $settingType ?? 'clinic'; @endphp

  <div class="row g-3 p-2">
    {{-- Section title --}}
    <div class="col-md-12">
      <x-input-field label="Tiêu đề mục" name="title" :value="$vi['title'] ?? ''" placeholder="Our Accepted Insurance" />
    </div>

    {{-- Logo grid --}}
    <div class="col-12">
      <label class="form-label fw-semibold">Thêm logo mới (chọn nhiều)</label>
      <input type="file" name="logo_files[]" id="insurance-logo-input" class="form-control" multiple accept="image/*">
      <div class="form-text">PNG nền trắng/trong suốt khuyến nghị. Tối đa 5MB/logo.</div>
    </div>

    <div class="col-12">
      <label class="form-label fw-semibold mb-2">
        Logo hiện có
        <span class="badge bg-primary ms-1" id="insurance-count">{{ count($logos) }}</span>
      </label>
      <div class="gallery-admin-strip" id="insurance-existing">
        @foreach($logos as $idx => $logo)
        <div class="gallery-admin-card gallery-item" data-path="{{ $logo['path'] }}" data-type="{{ $settingTypeVal }}">
          <div class="gallery-admin-thumb" style="height:90px; background:#fff;">
            <img src="{{ $logo['url'] }}" alt="Logo {{ $idx + 1 }}" style="object-fit:contain; padding:6px;">
            <div class="gallery-admin-overlay">
              <span class="gallery-num">{{ $idx + 1 }}</span>
            </div>
          </div>
          <button type="button" class="gallery-admin-remove js-insurance-remove"
                  data-path="{{ $logo['path'] }}" data-type="{{ $settingTypeVal }}">
            <i class="ti tabler-x"></i>
          </button>
          <div class="gallery-admin-label">
            <span class="gallery-admin-filename">Logo {{ $idx + 1 }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="col-12 mt-2 text-end">
      <button type="button" id="btn-insurance-save" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary" id="btn-insurance-cancel">Hủy</button>
    </div>
  </div>
</form>

{{-- Delete Confirm Modal --}}
<div class="modal fade" id="insuranceConfirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header pb-2" style="border-bottom:1px solid rgba(255,255,255,0.08)">
        <h6 class="modal-title mb-0">
          <i class="ti tabler-alert-triangle text-warning me-1"></i> Xác nhận xoá logo
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body py-3">
        <p class="small text-muted mb-0">Logo sẽ bị xoá vĩnh viễn, <strong>không thể khôi phục</strong>.</p>
      </div>
      <div class="modal-footer pt-2" style="border-top:1px solid rgba(255,255,255,0.08)">
        <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-sm btn-danger" id="insurance-delete-confirm-btn">
          <i class="ti tabler-trash me-1"></i> Xoá
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  if (window._insuranceModuleLoaded) return;
  window._insuranceModuleLoaded = true;

  const CSRF        = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
  const DELETE_URL  = '{{ panel_route("setting.removeAboutInsuranceLogo") }}';
  let pendingDelete = null;

  function getForm()  { return document.getElementById('insurance-upload-form'); }
  function getStrip() { return document.getElementById('insurance-existing'); }

  function updateJsonPanel(data) {
    try {
      const jsonBox = document.querySelector('[id$="-json-preview"]');
      const wrapper  = document.querySelector('[id$="-json-wrapper"]');
      if (!jsonBox) return;
      const pretty = JSON.stringify(data, null, 2);
      jsonBox.textContent = pretty;
      if (wrapper) wrapper.style.display = 'block';
      const prefix = wrapper ? wrapper.id.replace('-json-wrapper', '') : 'about';
      const type   = wrapper?.dataset?.type || 'clinic';
      try { window.localStorage?.setItem(`${prefix}_json_${type}_insurance`, pretty); } catch (_) {}
    } catch (_) {}
  }

  async function reloadInsuranceSection(typeVal) {
    try {
      const url  = '{{ panel_route("setting.aboutPage") }}?section=insurance&type=' + (typeVal || 'clinic');
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const tmp  = document.createElement('div');
      tmp.innerHTML = html;
      const newSec = tmp.querySelector('#about-section-insurance');
      const curSec = document.getElementById('about-section-insurance');
      if (newSec && curSec) curSec.innerHTML = newSec.innerHTML;
    } catch (err) { console.warn('Insurance reload error:', err); }
  }

  /* ── SAVE ─────────────────────────────────────────────────────────── */
  document.addEventListener('click', async function (e) {
    const saveBtn = e.target.closest('#btn-insurance-save');
    if (!saveBtn) return;
    e.preventDefault();

    const form = getForm();
    if (!form) return;

    saveBtn.disabled = true;
    saveBtn.textContent = 'Đang lưu...';

    try {
      const fd     = new FormData(form);
      const action = form.dataset.action || '{{ panel_route("setting.updateAboutInsurance") }}';
      const type   = form.dataset.type || 'clinic';
      fd.set('_method', 'PUT');
      fd.set('type', type);
      fd.delete('keep_logos[]');
      document.querySelectorAll('#insurance-existing .gallery-item[data-path]').forEach(card => {
        fd.append('keep_logos[]', card.dataset.path);
      });

      const res  = await fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: fd,
        credentials: 'same-origin',
      });
      const data = await res.json().catch(() => ({}));

      if (res.ok) {
        showToast({ message: data.message || 'Đã lưu logo bảo hiểm.', type: 'success', delay: 2500 });
        updateJsonPanel(data);
        await reloadInsuranceSection(type);
      } else {
        showToast({ message: data.message || `Lỗi ${res.status}`, type: 'error', delay: 3500 });
      }
    } catch (err) {
      console.error(err);
      showToast({ message: 'Lỗi mạng khi lưu.', type: 'error', delay: 3500 });
    } finally {
      const btn2 = document.getElementById('btn-insurance-save');
      if (btn2) { btn2.disabled = false; btn2.textContent = 'Lưu thay đổi'; }
    }
  });

  /* ── DELETE step 1: click X → show modal ─────────────────────────── */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.js-insurance-remove');
    if (!btn) return;
    e.preventDefault();
    pendingDelete = { path: btn.dataset.path, typeVal: btn.dataset.type || 'clinic' };
    const el = document.getElementById('insuranceConfirmDeleteModal');
    if (el) bootstrap.Modal.getOrCreateInstance(el).show();
  });

  /* ── DELETE step 2: confirm → AJAX ───────────────────────────────── */
  document.addEventListener('click', async function (e) {
    if (!e.target.closest('#insurance-delete-confirm-btn')) return;
    if (!pendingDelete) return;
    const { path, typeVal } = pendingDelete;
    pendingDelete = null;

    const confirmBtn = document.getElementById('insurance-delete-confirm-btn');
    if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xoá...'; }
    const el = document.getElementById('insuranceConfirmDeleteModal');
    if (el) bootstrap.Modal.getOrCreateInstance(el).hide();

    try {
      const fd = new FormData();
      fd.append('_method', 'DELETE');
      fd.append('path', path);
      fd.append('type', typeVal);

      const res  = await fetch(DELETE_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: fd,
        credentials: 'same-origin',
      });
      const data = await res.json().catch(() => ({}));

      if (res.ok) {
        showToast({ message: data.message || 'Đã xoá logo.', type: 'success', delay: 2500 });
        updateJsonPanel(data);
        await reloadInsuranceSection(typeVal);
      } else {
        showToast({ message: data.message || 'Xoá thất bại.', type: 'error', delay: 3000 });
      }
    } catch (err) {
      console.error(err);
      showToast({ message: 'Lỗi mạng khi xoá.', type: 'error', delay: 3000 });
    } finally {
      const btn2 = document.getElementById('insurance-delete-confirm-btn');
      if (btn2) { btn2.disabled = false; btn2.innerHTML = '<i class="ti tabler-trash me-1"></i> Xoá'; }
    }
  });

  /* ── LIVE PREVIEW ─────────────────────────────────────────────────── */
  document.addEventListener('change', function (e) {
    if (e.target.id !== 'insurance-logo-input') return;
    const strip = getStrip();
    if (!strip) return;
    strip.querySelectorAll('.gallery-preview-new').forEach(el => el.remove());
    Array.from(e.target.files || []).forEach(function (file) {
      if (!file.type.startsWith('image/')) return;
      const card  = document.createElement('div');
      card.className = 'gallery-admin-card gallery-preview-new';
      card.style.border = '1.5px dashed rgba(99,179,237,0.6)';
      const fname = file.name.length > 22 ? file.name.substring(0, 20) + '…' : file.name;
      const objUrl = URL.createObjectURL(file);
      card.innerHTML = `
        <div class="gallery-admin-thumb" style="height:90px;background:#fff;">
          <img src="${objUrl}" alt="new" style="object-fit:contain;padding:6px;">
          <div class="gallery-admin-overlay">
            <span class="gallery-num" style="background:rgba(37,99,235,.85)">Mới</span>
          </div>
        </div>
        <div class="gallery-admin-label"><span class="gallery-admin-filename">${fname}</span></div>`;
      card.querySelector('img').onload = () => URL.revokeObjectURL(objUrl);
      strip.appendChild(card);
    });
  });

  /* ── CANCEL ──────────────────────────────────────────────────────── */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('#btn-insurance-cancel')) return;
    const fi = document.getElementById('insurance-logo-input');
    if (fi) fi.value = '';
    getStrip()?.querySelectorAll('.gallery-preview-new').forEach(el => el.remove());
  });
})();
</script>
@endpush
