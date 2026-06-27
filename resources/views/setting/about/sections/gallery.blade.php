<form method="POST" enctype="multipart/form-data" id="gallery-upload-form"
      data-action="{{ panel_route('setting.updateAboutGallery') }}"
      data-type="travel">
  @csrf
  @method('PUT')

  <div class="row g-3 p-2">
    {{-- Upload new images --}}
    <div class="col-12">
      <label class="form-label fw-semibold">Thêm ảnh mới (chọn nhiều ảnh)</label>
      <input type="file" name="gallery_files[]" class="form-control" multiple accept="image/*" id="gallery-file-input">
      <div class="form-text">Có thể chọn nhiều ảnh cùng lúc. Tối đa 5MB/ảnh.</div>
    </div>

    {{-- Existing images grid --}}
    <div class="col-12">
      <label class="form-label fw-semibold mb-2">
        Ảnh hiện có
        <span class="badge bg-primary ms-1" id="gallery-count">{{ count($images ?? []) }}</span>
      </label>
      <div class="gallery-admin-strip" id="gallery-existing">
        @foreach($images ?? [] as $idx => $img)
        <div class="gallery-admin-card gallery-item" data-path="{{ $img['path'] }}" data-type="travel">
          <div class="gallery-admin-thumb">
            <img src="{{ $img['url'] }}" alt="Gallery {{ $idx + 1 }}">
            <div class="gallery-admin-overlay">
              <span class="gallery-num">{{ $idx + 1 }}</span>
            </div>
          </div>
          <button type="button" class="gallery-admin-remove js-gallery-remove"
                  data-path="{{ $img['path'] }}" data-type="travel">
            <i class="ti tabler-x"></i>
          </button>
          <div class="gallery-admin-label">
            <span class="gallery-admin-filename">Ảnh {{ $idx + 1 }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="col-12 mt-2 text-end">
      <button type="button" id="btn-gallery-save" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary" id="btn-gallery-cancel">Hủy</button>
    </div>
  </div>
</form>

{{-- Delete Confirm Modal --}}
<div class="modal fade" id="galleryConfirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header pb-2" style="border-bottom:1px solid rgba(255,255,255,0.08)">
        <h6 class="modal-title mb-0">
          <i class="ti tabler-alert-triangle text-warning me-1"></i> Xác nhận xoá ảnh
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body py-3">
        <p class="small text-muted mb-0">Ảnh sẽ bị xoá khỏi thư viện và hệ thống file, <strong>không thể khôi phục</strong>.</p>
      </div>
      <div class="modal-footer pt-2" style="border-top:1px solid rgba(255,255,255,0.08)">
        <button type="button" class="btn btn-sm btn-label-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-sm btn-danger" id="gallery-delete-confirm-btn">
          <i class="ti tabler-trash me-1"></i> Xoá
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
.gallery-admin-strip {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 12px;
  min-height: 60px;
}
.gallery-admin-card {
  position: relative; width: 100%; border-radius: 10px; overflow: hidden;
  background: #1a1e2e; border: 1.5px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 16px rgba(0,0,0,0.35);
  transition: box-shadow .22s, transform .22s; flex-shrink: 0;
}
.gallery-admin-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.55); transform: translateY(-3px); border-color: rgba(255,255,255,0.22); }
.gallery-admin-thumb { position: relative; width: 100%; height: 110px; overflow: hidden; background: #12151f; }
.gallery-admin-thumb img { width:100%;height:100%;object-fit:cover;display:block;transition:transform .28s; }
.gallery-admin-card:hover .gallery-admin-thumb img { transform: scale(1.07); }
.gallery-admin-overlay { position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.18) 0%,transparent 50%,rgba(0,0,0,.55) 100%);pointer-events:none; }
.gallery-num { position:absolute;top:6px;left:8px;background:rgba(0,0,0,.55);color:#fff;font-size:.7rem;font-weight:600;border-radius:4px;padding:1px 6px; }
.gallery-admin-remove {
  position:absolute;top:6px;right:6px;background:rgba(220,38,38,.85);color:#fff;border:none;border-radius:50%;
  width:22px;height:22px;display:flex;align-items:center;justify-content:center;
  font-size:.7rem;cursor:pointer;opacity:0;transition:opacity .18s;z-index:4;padding:0;
}
.gallery-admin-card:hover .gallery-admin-remove { opacity:1; }
.gallery-admin-remove:hover { background:rgba(185,20,20,1)!important; }
.gallery-admin-label { padding:6px 8px 7px;background:#1a1e2e;border-top:1px solid rgba(255,255,255,.07); }
.gallery-admin-filename { display:block;font-size:.72rem;color:rgba(255,255,255,.55);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;text-align:center; }
</style>

<script>
/* ====================================================================
 * GALLERY MODULE — init once, all listeners use document delegation
 * ==================================================================== */
(function () {
  if (window._galleryModuleLoaded) return; // prevent double-init
  window._galleryModuleLoaded = true;

  const CSRF       = () => document.querySelector('meta[name="csrf-token"]')?.content || '';
  const DELETE_URL = '{{ panel_route("setting.removeAboutGalleryImage") }}';
  let pendingDelete = null; // { path, typeVal }

  /* ── helpers ─────────────────────────────────────────────────────── */
  function getForm()   { return document.getElementById('gallery-upload-form'); }
  function getStrip()  { return document.getElementById('gallery-existing'); }
  function getCount()  { return document.getElementById('gallery-count'); }
  function getDeleteModal() {
    const el = document.getElementById('galleryConfirmDeleteModal');
    return el ? bootstrap.Modal.getOrCreateInstance(el) : null;
  }

  function updateJsonPanel(data) {
    try {
      const jsonBox = document.querySelector('[id$="-json-preview"]');
      const wrapper  = document.querySelector('[id$="-json-wrapper"]');
      if (!jsonBox) return;
      const pretty = JSON.stringify(data, null, 2);
      jsonBox.textContent = pretty;
      if (wrapper) wrapper.style.display = 'block';
      const prefix = wrapper ? wrapper.id.replace('-json-wrapper', '') : 'about';
      const type   = wrapper?.dataset?.type || 'travel';
      const sec    = wrapper?.dataset?.section || 'gallery';
      try { window.localStorage?.setItem(`${prefix}_json_${type}_${sec}`, pretty); } catch (_) {}
    } catch (_) {}
  }

  async function reloadGallerySection(typeVal) {
    try {
      const url  = '{{ panel_route("setting.aboutPage") }}?section=gallery&type=' + (typeVal || 'travel');
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const html = await res.text();
      const tmp  = document.createElement('div');
      tmp.innerHTML = html;
      const newSec = tmp.querySelector('#about-section-gallery');
      const curSec = document.getElementById('about-section-gallery');
      if (newSec && curSec) curSec.innerHTML = newSec.innerHTML;
    } catch (err) {
      console.warn('Gallery reload error:', err);
    }
  }

  /* ── SAVE: click delegation on button#btn-gallery-save ─────────── */
  document.addEventListener('click', async function (e) {
    const saveBtn = e.target.closest('#btn-gallery-save');
    if (!saveBtn) return;
    e.preventDefault();

    const form = getForm();
    if (!form) { console.error('Gallery form not found!'); return; }

    saveBtn.disabled = true;
    saveBtn.textContent = 'Đang lưu...';

    try {
      const fd     = new FormData(form);
      const action = form.dataset.action || '{{ panel_route("setting.updateAboutGallery") }}';
      const type   = form.dataset.type || 'travel';
      fd.set('_method', 'PUT');
      fd.set('type', type);
      // keep_images: collect from existing cards
      fd.delete('keep_images[]');
      document.querySelectorAll('#gallery-existing .gallery-item[data-path]').forEach(card => {
        fd.append('keep_images[]', card.dataset.path);
      });

      const res  = await fetch(action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: fd,
        credentials: 'same-origin',
      });
      const data = await res.json().catch(() => ({}));

      if (res.ok) {
        showToast({ message: data.message || 'Đã lưu thư viện ảnh.', type: 'success', delay: 2500 });
        updateJsonPanel(data);
        await reloadGallerySection(type);
      } else {
        showToast({ message: data.message || `Lỗi ${res.status}`, type: 'error', delay: 3500 });
      }
    } catch (err) {
      console.error('Gallery save error:', err);
      showToast({ message: 'Lỗi mạng khi lưu.', type: 'error', delay: 3500 });
    } finally {
      // Button may have been replaced by reload; find it again
      const btn2 = document.getElementById('btn-gallery-save');
      if (btn2) { btn2.disabled = false; btn2.textContent = 'Lưu thay đổi'; }
    }
  });

  /* ── DELETE step 1: click X → show confirm modal ───────────────── */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.js-gallery-remove');
    if (!btn) return;
    e.preventDefault();
    const path    = btn.dataset.path;
    const typeVal = btn.dataset.type || 'travel';
    if (!path) return;
    pendingDelete = { path, typeVal };
    const m = getDeleteModal();
    if (m) m.show();
  });

  /* ── DELETE step 2: confirm button in modal → AJAX ─────────────── */
  document.addEventListener('click', async function (e) {
    if (!e.target.closest('#gallery-delete-confirm-btn')) return;
    if (!pendingDelete) return;
    const { path, typeVal } = pendingDelete;
    pendingDelete = null;

    const confirmBtn = document.getElementById('gallery-delete-confirm-btn');
    if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xoá...'; }
    const m = getDeleteModal();
    if (m) m.hide();

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
        showToast({ message: data.message || 'Đã xoá ảnh.', type: 'success', delay: 2500 });
        updateJsonPanel(data);
        await reloadGallerySection(typeVal);
      } else {
        showToast({ message: data.message || 'Xoá thất bại.', type: 'error', delay: 3000 });
      }
    } catch (err) {
      console.error('Delete error:', err);
      showToast({ message: 'Lỗi mạng khi xoá.', type: 'error', delay: 3000 });
    } finally {
      const btn2 = document.getElementById('gallery-delete-confirm-btn');
      if (btn2) { btn2.disabled = false; btn2.innerHTML = '<i class="ti tabler-trash me-1"></i> Xoá'; }
    }
  });

  /* ── LIVE PREVIEW: delegation (works after partial reload) ──────── */
  document.addEventListener('change', function (e) {
    if (e.target.id !== 'gallery-file-input') return;
    const strip = getStrip();
    if (!strip) return;
    strip.querySelectorAll('.gallery-preview-new').forEach(el => el.remove());
    const files = Array.from(e.target.files || []);
    files.forEach(function (file) {
      if (!file.type.startsWith('image/')) return;
      const card = document.createElement('div');
      card.className = 'gallery-admin-card gallery-preview-new';
      card.style.border = '1.5px dashed rgba(99,179,237,0.6)';
      const fname = file.name.length > 22 ? file.name.substring(0, 20) + '…' : file.name;
      // Create img via Object URL (instant, no FileReader async issues)
      const objUrl = URL.createObjectURL(file);
      card.innerHTML = `
        <div class="gallery-admin-thumb">
          <img src="${objUrl}" alt="new">
          <div class="gallery-admin-overlay">
            <span class="gallery-num" style="background:rgba(37,99,235,.85)">Mới</span>
          </div>
        </div>
        <div class="gallery-admin-label">
          <span class="gallery-admin-filename">${fname}</span>
        </div>`;
      strip.appendChild(card);
      // Revoke after render to free memory
      card.querySelector('img').onload = () => URL.revokeObjectURL(objUrl);
    });
  });

  /* ── CANCEL button: clear file input + remove previews ─────────── */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('#btn-gallery-cancel')) return;
    const fi = document.getElementById('gallery-file-input');
    if (fi) fi.value = '';
    getStrip()?.querySelectorAll('.gallery-preview-new').forEach(el => el.remove());
  });

})();
</script>
@endpush
