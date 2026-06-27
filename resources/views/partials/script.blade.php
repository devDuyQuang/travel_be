<!-- <script src="../../assets/vendor/libs/jquery/jquery.js"></script>

<script src="../../assets/vendor/libs/popper/popper.js"></script>
<script src="../../assets/vendor/js/bootstrap.js"></script>
<script src="../../assets/vendor/libs/node-waves/node-waves.js"></script>

<script src="../../assets/vendor/libs/pickr/pickr.js"></script>

<script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../../assets/vendor/libs/hammer/hammer.js"></script>

<script src="../../assets/vendor/libs/i18n/i18n.js"></script>

<script src="../../assets/vendor/js/menu.js"></script> -->

<!-- endbuild -->

<!-- Vendors JS -->
<!-- <script src="../../assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script src="../../assets/vendor/libs/sortablejs/sortable.js"></script>
<script src="../../assets/vendor/libs/select2/select2.js"></script>
<script src="../../assets/vendor/libs/tagify/tagify.js"></script>
<script src="../../assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
<script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="../../assets/vendor/libs/bloodhound/bloodhound.js"></script> -->

<!-- Flat Picker -->
<!-- <script src="../../assets/vendor/libs/moment/moment.js"></script>
<script src="../../assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="../../assets/vendor/libs/dropzone/dropzone.js"></script> -->

<!-- Main JS -->
<!-- <script src="../../assets/js/main.js"></script> -->

<!-- Page JS -->
<!-- <script src="../../assets/js/dashboards-analytics.js"></script>
<script src="../../assets/js/extended-ui-drag-and-drop.js"></script>
<script src="../../assets/js/fileinput.js"></script>
<script src="../../assets/js/forms-selects.js"></script> -->
<!-- <script src="../../assets/js/forms-tagify.js"></script> -->
<!-- <script src="../../assets/js/forms-typeahead.js"></script> -->

<!--bootstrap js-->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/peity/jquery.peity.min.js') }}"></script>
<script>
  $(".data-attributes span").peity("donut")
</script>
<script src="{{ asset('assets/js/dashboard2.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
$(document).ready(function() {
  const $file = $("#file");

  if ($file.length && typeof $file.fileinput === 'function') {
    $file.fileinput({
      showUpload: false,
      dropZoneEnabled: false,
      maxFileCount: 10,
      inputGroupClass: "input-group-md"
    });
  }
});
</script>

<style>
  .cke_notifications_area {
    display: none;
  }
</style>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
  // Chỉ khởi tạo CKEditor nếu element content tồn tại và chưa được khởi tạo
  (function() {
    const contentElement = document.getElementById('content');
    if (contentElement && typeof CKEDITOR !== 'undefined' && !CKEDITOR.instances.content) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      const ckOptions = {
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=' + csrfToken,
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=' + csrfToken,
        height: 500,
        contentsCss: [
          CKEDITOR.basePath + 'contents.css',
          'data:text/css;charset=utf-8,' + encodeURIComponent(`
                    img {
                        max-width: 100% !important;
                        height: auto !important;
                        width: auto !important;
                        display: inline-block;
                    }
                `)
        ]
      };
      CKEDITOR.replace('content', ckOptions);
    }
  })();
</script>

<script>
  // type: 'success' | 'error' | 'info' | 'warning'
  function showToast({
    message = '',
    type = 'info',
    delay = 3000
  }) {
    const container = document.getElementById('app-toast-container') || (() => {
      const c = document.createElement('div');
      c.id = 'app-toast-container';
      c.className = 'toast-container position-fixed top-0 end-0 p-3';
      c.style.zIndex = '9999';
      document.body.appendChild(c);
      return c;
    })();

    const bgMap = {
      success: 'bg-success',
      error: 'bg-danger',
      info: 'bg-info',
      warning: 'bg-warning'
    };
    const bgClass = bgMap[type] || bgMap.info;

    const toastEl = document.createElement('div');
    toastEl.className = `toast show align-items-center text-white ${bgClass} border-0 mb-2`;
    toastEl.setAttribute('role', 'alert');
    toastEl.style.display = 'block';
    toastEl.style.opacity = '0';
    toastEl.style.transition = 'opacity 0.3s ease';

    toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        ${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;

    container.appendChild(toastEl);

    // Fade in
    setTimeout(() => toastEl.style.opacity = '1', 10);

    // Close function
    const closeToast = () => {
      toastEl.style.opacity = '0';
      setTimeout(() => toastEl.remove(), 300);
    };

    // Auto hide
    setTimeout(closeToast, delay);

    // Manual close
    toastEl.querySelector('.btn-close').addEventListener('click', closeToast);
  }
</script>

<script>
  // Chuyển FormData thành object để dễ debug
  function formDataToObject(formData) {
    let obj = {};
    formData.forEach((value, key) => {
      // Nếu có nhiều field cùng name (ví dụ checkbox, multiple select)
      if (obj[key]) {
        if (!Array.isArray(obj[key])) obj[key] = [obj[key]];
        obj[key].push(value);
      } else {
        obj[key] = value;
      }
    });
    return obj;
  }

  document.addEventListener('submit', async function(e) {
    const form = e.target;
    if (!form.matches('form.ajax-form')) return;

    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    try {
      if (window.CKEDITOR && CKEDITOR.instances) {
        Object.keys(CKEDITOR.instances).forEach(name => CKEDITOR.instances[name].updateElement());
      }
    } catch (err) {
      console.warn('CKEditor update skipped:', err);
    }

    const action = form.getAttribute('action') || window.location.href;
    // Luôn gửi multipart bằng POST; Laravel xử lý PUT/PATCH/DELETE qua
    // hidden field `_method`. PHP không parse multipart PUT ổn định.
    const method = 'POST';
    if (typeof CKEDITOR !== 'undefined') {
  for (const instance in CKEDITOR.instances) {
    CKEDITOR.instances[instance].updateElement();
  }
}
    let formData = new FormData(form);

    // normalize checkbox
    form.querySelectorAll('input[type="checkbox"][name]').forEach(cb => {
      formData.set(cb.name, cb.checked ? (cb.value || 1) : 0);
    });

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // clear lỗi cũ
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => (el.innerHTML = ''));

    try {
      const response = await fetch(action, {
        method,
        body: formData,
        headers: {
          ...(csrf ? {
            'X-CSRF-TOKEN': csrf
          } : {}),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });

      let data;
      try {
        data = await response.json();
      } catch {
        data = null;
      }

      // Ghi log JSON trả về vào khung debug (generic: tìm bất kỳ [id$="-json-preview"] trên trang)
      try {
        const jsonBox = document.querySelector('[id$="-json-preview"]');
        const wrapper = document.querySelector('[id$="-json-wrapper"]');
        if (jsonBox) {
          const settingType = wrapper?.dataset?.type || 'travel';
          // Lấy prefix từ ID: "home-json-wrapper" → "home", "about-json-wrapper" → "about"
          const prefix = wrapper ? wrapper.id.replace('-json-wrapper', '') : 'home';
          let storageKey = `${prefix}_json_${settingType}_preview`;
          if (wrapper?.dataset?.section) {
            storageKey = `${prefix}_json_${settingType}_${wrapper.dataset.section}`;
          }
          const pretty = data ? JSON.stringify(data, null, 2) : '(Không có JSON trong response)';
          jsonBox.textContent = pretty;
          if (wrapper) wrapper.style.display = 'block';
          try {
            window.localStorage && window.localStorage.setItem(storageKey, pretty);
          } catch (e) {
            console.warn('Không thể lưu JSON response vào localStorage:', e);
          }
        }
      } catch (e) {
        console.warn('Không thể hiển thị JSON response:', e);
      }

      if (response.ok) {
        if (
          form.dataset.requirePersist === 'true' &&
          (!data || typeof data !== 'object' || !Object.prototype.hasOwnProperty.call(data, 'value'))
        ) {
          showToast({
            message: 'Máy chủ chưa xác nhận dữ liệu đã lưu. Vui lòng thử lại.',
            type: 'error',
            delay: 4000
          });
          return;
        }

        if (form.dataset.requirePersist === 'true') {
          const persistedValue = data.value || {};
          const mismatchedField = Array.from(form.elements).find((element) => {
            if (!(element instanceof HTMLInputElement || element instanceof HTMLTextAreaElement)) return false;
            if (!element.name || element.disabled || element.type === 'file') return false;
            if (element.name.startsWith('_') || element.name === 'type' || element.name.startsWith('remove_')) return false;
            if (element.name.includes('[')) return false;

            const expected = (element.value || '').trim();
            const actual = String(persistedValue[element.name] ?? '').trim();

            return expected !== actual;
          });

          if (mismatchedField) {
            showToast({
              message: `Trường "${mismatchedField.name}" chưa được lưu đúng. Hệ thống đã dừng chuyển trang để tránh mất dữ liệu.`,
              type: 'error',
              delay: 5000
            });
            mismatchedField.classList.add('is-invalid');
            return;
          }
        }

        const msg = data?.message || 'Thực hiện thành công.';
        const redirectUrl = data?.redirect_url || null;
        showToast({
          message: msg,
          type: 'success',
          delay: 2500
        });
        if (redirectUrl) {
          setTimeout(() => {
            window.location.href = redirectUrl;
          }, 300);
        }
        return;
      }

      // 422: hiển thị field errors + toast tổng quát
      if (response.status === 422 && data?.errors) {
        const errors = data.errors;
        let firstMsg = null;

        for (const key in errors) {
          const msgs = errors[key];
          if (!firstMsg) firstMsg = msgs?.[0] || 'Dữ liệu không hợp lệ.';
          const nameSelector = key
            .replace(/\.(\d+)/g, '[$1]')
            .replace(/\.([^.[]+)/g, '[$1]');
          const input = form.querySelector(`[name="${CSS.escape(nameSelector)}"]`);
          if (input) {
            input.classList.add('is-invalid');
            const feedback = form.querySelector(`#error-${CSS.escape(nameSelector)}`);
            if (feedback) feedback.innerHTML = msgs?.[0] || '';
          }
        }

        // ❌ Toast lỗi validate
        showToast({
          message: firstMsg || data?.message || 'Vui lòng kiểm tra lại các trường.',
          type: 'error',
          delay: 3500
        });
        return;
      }

      if (response.status === 419) {
        showToast({
          message: 'Phiên CSRF đã hết hạn. Vui lòng tải lại trang và thử lại.',
          type: 'error',
          delay: 4000
        });
        return;
      }

      // Lỗi khác
      const generic = data?.message || `Có lỗi xảy ra (HTTP ${response.status}). Vui lòng thử lại.`;
      showToast({
        message: generic,
        type: 'error',
        delay: 3500
      });
      console.error('Fetch error:', response.status, data);

    } catch (err) {
      console.error(err);
      showToast({
        message: 'Có lỗi mạng hoặc lỗi JavaScript. Kiểm tra Console/Network.',
        type: 'error',
        delay: 4000
      });
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });

  // === helper: tạo modal BS5 nếu chưa có, rồi hiển thị ===
  function showSuccessDialog({
    message = 'Thêm thành công.',
    redirectUrl = null,
    form,
    resetOnContinue = null
  }) {
    // Tạo modal nếu chưa có
    let modalEl = document.getElementById('ajaxSuccessModal');
    if (!modalEl) {
      modalEl = document.createElement('div');
      modalEl.id = 'ajaxSuccessModal';
      modalEl.className = 'modal fade';
      modalEl.tabIndex = -1;
      modalEl.innerHTML = `
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Thông báo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="ajaxSuccessMessage" class="mb-0"></p>
          </div>
          <div class="modal-footer">
            <button type="button" id="btn-continue" class="btn btn-secondary">Tiếp tục</button>
            <button type="button" id="btn-goto" class="btn btn-primary">Đến bảng dữ liệu</button>
          </div>
        </div>
      </div>`;
      document.body.appendChild(modalEl);
    }

    // Set message
    modalEl.querySelector('#ajaxSuccessMessage').textContent = message || 'Thao tác thành công.';

    // Bootstrap instance (fallback nếu không có bootstrap)
    const modal = (window.bootstrap && bootstrap.Modal) ?
      bootstrap.Modal.getOrCreateInstance(modalEl) :
      null;

    // Buttons
    const btnContinue = modalEl.querySelector('#btn-continue');
    const btnGoto = modalEl.querySelector('#btn-goto');

    // Clear old listeners
    btnContinue.replaceWith(btnContinue.cloneNode(true));
    btnGoto.replaceWith(btnGoto.cloneNode(true));

    // Re-select
    const btnContinueNew = modalEl.querySelector('#btn-continue');
    const btnGotoNew = modalEl.querySelector('#btn-goto');

    // --- detect create/update ---
    const isUpdate = typeof resetOnContinue === 'boolean' ?
      !resetOnContinue // resetOnContinue=false => isUpdate=true
      :
      detectUpdateMode(form); // auto-detect

    // Continue: reset chỉ khi không phải update
    btnContinueNew.addEventListener('click', () => {
      if (!isUpdate && typeof resetAjaxForm === 'function' && form) {
        resetAjaxForm(form);
      }
      modal ? modal.hide() : modalEl.classList.remove('show');
    });

    // Go to list
    btnGotoNew.addEventListener('click', () => {
      const url =
        redirectUrl ||
        form?.getAttribute?.('data-index-url') ||
        form?.getAttribute?.('data-list-url') ||
        null;

      if (url) {
        window.location.href = url;
      } else {
        modal ? modal.hide() : modalEl.classList.remove('show');
        alert('Không tìm thấy URL bảng dữ liệu.');
      }
    });

    // Show
    if (modal) modal.show();
    else modalEl.classList.add('show');
  }

  function detectUpdateMode(form) {
    if (!form) return false;

    // 1) data-mode
    const mode = (form.getAttribute('data-mode') || '').toLowerCase();
    if (mode === 'update' || mode === 'edit') return true;

    // 2) method PUT/PATCH
    const method = (form.getAttribute('method') || '').toUpperCase();
    if (method === 'PUT' || method === 'PATCH') return true;
    const methodOverride = form.querySelector('input[name="_method"]');
    if (methodOverride) {
      const val = (methodOverride.value || '').toUpperCase();
      if (val === 'PUT' || val === 'PATCH') return true;
    }

    // 3) hidden id
    const idField = form.querySelector('input[name="id"], input[name="Id"], input[name="ID"]');
    if (idField && String(idField.value || '').trim() !== '') return true;

    return false;
  }

  // === helper: reset form (kể cả CKEditor & feedbacks) ===
  function resetAjaxForm(form) {
    try {
      // reset inputs
      form.reset();

      // clear file inputs (tránh một số trình duyệt giữ lại)
      form.querySelectorAll('input[type="file"]').forEach((fi) => (fi.value = ''));

      // clear invalid states
      form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
      form.querySelectorAll('.invalid-feedback').forEach((el) => (el.innerHTML = ''));

      // reset CKEditor 4 chỉ trong form này
      if (window.CKEDITOR && CKEDITOR.instances) {
        Object.keys(CKEDITOR.instances).forEach((name) => {
          const inst = CKEDITOR.instances[name];
          // chỉ reset nếu textarea thuộc form hiện tại
          const textarea = document.getElementById(name);
          if (textarea && form.contains(textarea)) {
            inst.setData('');
          }
        });
      }
    } catch (e) {
      console.warn('Reset form warning:', e);
    }
  }
</script>

<!-- Title to slug -->
<script>
  let isSlugChanged = false;

  function convertToSlug(str) {
    const from = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđĐ";
    const to = "aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydD";

    let slug = str.split('').map(char => {
      const idx = from.indexOf(char);
      return idx !== -1 ? to[idx] : char;
    }).join('');

    slug = slug.toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '') // bỏ ký tự không hợp lệ
      .trim()
      .replace(/\s+/g, '-') // khoảng trắng → -
      .replace(/-+/g, '-') // nhiều dấu - liền → 1
      .replace(/^-+|-+$/g, ''); // bỏ - ở đầu/cuối

    return slug;
  }

  $('#name').on('input', function() {
    if (!isSlugChanged) {
      $('#slug').val(convertToSlug($(this).val()));
    }
  });

  $('#slug').on('input', function() {
    if ($(this).val().length === 0) {
      isSlugChanged = false;
      $('#slug').val(convertToSlug($('#name').val()));
    } else {
      isSlugChanged = true;
    }
  });
</script>

<!-- Sắp xếp thứ tự -->
<script>
  // 0) Reload lại cây nested list từ server
  async function reloadNestedList() {
    const ul = document.getElementById('handle-list-1');
    if (!ul) return;

    const url = ul.getAttribute('data-refresh-url');
    if (!url) {
      console.warn('Thiếu data-refresh-url trên #handle-list-1');
      return;
    }

    try {
      const resp = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const html = await resp.text();

      // Nếu server trả về <ul id="handle-list-1">...</ul>
      const temp = document.createElement('div');
      temp.innerHTML = html.trim();
      const ulNew = temp.querySelector('#handle-list-1');

      if (ulNew) {
        ul.replaceWith(ulNew);
      } else {
        // Nếu server chỉ trả về <li>...</li>
        ul.innerHTML = html;
      }

      // Re-init Sortable vì DOM đã thay đổi
      initNestedSortables();

    } catch (e) {
      console.error('reloadNestedList error:', e);
      // Fallback
      window.location.reload();
    }
  }

  // 1) Init Sortable cho tất cả UL trong cây
  function initNestedSortables() {
    document.querySelectorAll('ul.nested-list').forEach(ul => {
      // Tránh init trùng
      if (ul._sortableInited) return;
      ul._sortableInited = true;

      Sortable.create(ul, {
        group: {
          name: 'tree',
          pull: true,
          put: true
        },
        handle: '.drag-handle',
        animation: 150,
        swapThreshold: 0.5,
        emptyInsertThreshold: 8,
        fallbackOnBody: true,
        forceFallback: true,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag'
      });
    });
  }

  document.addEventListener('DOMContentLoaded', initNestedSortables);

  // 2) Lấy [{id, parent_id, sort}] từ DOM
  function getNestedOrder(rootSelector = '#handle-list-1') {
    let root = document.querySelector(rootSelector);
    if (!root) return [];
    if (!root.querySelector(':scope > li[data-id]')) {
      const inner = root.querySelector(':scope > ul.nested-list');
      if (inner) root = inner;
    }
    const rows = [];

    function walk(ul, parentId = null) {
      const items = ul.querySelectorAll(':scope > li[data-id]');
      items.forEach((li, idx) => {
        const id = Number(li.dataset.id);
        rows.push({
          id,
          parent_id: parentId,
          sort: idx + 1
        });
        const childUl = li.querySelector(':scope > ul.nested-list');
        if (childUl) walk(childUl, id);
      });
    }
    walk(root, null);
    return rows;
  }

  // 3) Gửi AJAX khi bấm Confirm
  document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('updateOrderModal-confirm');
    if (!confirmBtn) return;

    confirmBtn.addEventListener('click', async function() {
      const list = document.getElementById('handle-list-1');
      if (!list) {
        alert('Không tìm thấy danh sách (#handle-list-1).');
        return;
      }

      const updateUrl = list.getAttribute('data-update-url');
      if (!updateUrl) {
        alert('Thiếu data-update-url trên #handle-list-1.');
        return;
      }

      const order = getNestedOrder();

      confirmBtn.disabled = true;
      const oldText = confirmBtn.textContent;
      confirmBtn.textContent = 'Đang lưu...';

      try {
        const res = await fetch(updateUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            items: order
          })
        });
        const data = await res.json().catch(() => ({}));

        if (res.ok) {
          // Đóng modal
          const modalEl = document.getElementById('updateOrderModal');
          if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();

          // Thông báo
          (window.toastSuccess || alert)(data.message || 'Đã lưu thứ tự.');

          // >>> Reload lại cây nested list
          await reloadNestedList();
        } else if (res.status === 419) {
          alert('Phiên CSRF đã hết hạn. Tải lại trang và thử lại.');
        } else {
          console.error('Update order failed', res.status, data);
          (window.toastError || alert)(data.message || 'Lỗi khi lưu thứ tự.');
        }
      } catch (err) {
        console.error(err);
        (window.toastError || alert)('Lỗi mạng khi lưu thứ tự.');
      } finally {
        confirmBtn.disabled = false;
        confirmBtn.textContent = oldText;
      }
    });
  });

  // (tuỳ chọn) xem trước thứ tự khi mở modal
  document.addEventListener('shown.bs.modal', function(e) {
    if (e.target.id !== 'updateOrderModal') return;
    const preview = e.target.querySelector('[data-order-preview]');
    if (!preview) return;
    const order = getNestedOrder();
    preview.innerHTML = order
      .map(o => `<li>ID: ${o.id} | parent: ${o.parent_id ?? 'null'} | sort: ${o.sort}</li>`)
      .join('');
  });
</script>

<!-- modal delete -->
<script>
  (function() {
  const deleteModalEl = document.getElementById('deleteModal');
  if (!deleteModalEl || !window.bootstrap?.Modal) return;

  const deleteNameEl = document.getElementById('deleteName');
  const confirmBtn = document.getElementById('confirmDelete');

  if (!deleteNameEl || !confirmBtn) return;

  const modal = bootstrap.Modal.getOrCreateInstance(deleteModalEl);
    function getCsrf() {
      const m = document.querySelector('meta[name="csrf-token"]');
      return m ? m.content : '';
    }

    // === RELOAD DATATABLE (nếu có) ===
    async function reloadDataTableIfAny() {
      try {
        // DataTables v2 (vanilla)
        if (window.DataTable && typeof DataTable.get === 'function') {
          document.querySelectorAll('table.dataTable').forEach(function(tbl) {
            const api = DataTable.get(tbl);
            if (api) {
              if (api.ajax && typeof api.ajax.reload === 'function') {
                api.ajax.reload(null, false);
              } else if (typeof api.draw === 'function') {
                api.draw(false);
              }
            }
          });
        }

        // DataTables jQuery (1.x)
        if (window.jQuery && jQuery.fn && jQuery.fn.dataTable) {
          if (jQuery.fn.dataTable.isDataTable('#reload-table')) {
            const dt = jQuery('#reload-table').DataTable();
            if (dt.ajax && dt.ajax.reload) dt.ajax.reload(null, false);
            else dt.draw(false);
          }
        }
      } catch (e) {
        console.warn('reloadDataTableIfAny error:', e);
      }
    }

    // === RELOAD NESTED LIST ===
    async function reloadNestedList() {
      const ul = document.getElementById('handle-list-1');
      if (!ul) return;

      const url = ul.getAttribute('data-refresh-url');
      if (!url) {
        console.warn('Thiếu data-refresh-url trên #handle-list-1');
        return;
      }

      try {
        // lấy lại HTML (server trả về snippet của list)
        const resp = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        const html = await resp.text();

        // Tạo một DOM tạm để parse
        const temp = document.createElement('div');
        temp.innerHTML = html.trim();

        // Lấy danh sách UL mới từ response: bạn có thể trả về <ul>…</ul> hoặc chỉ <li>…</li>
        // 1) nếu route trả về TRỌN <ul>:
        const ulNew = temp.querySelector('#handle-list-1');
        if (ulNew) {
          ul.replaceWith(ulNew);
        } else {
          // 2) nếu route chỉ trả về danh sách <li>…</li>:
          ul.innerHTML = html;
        }

        // (Tuỳ chọn) Re-init drag/drop hoặc các event khác:
        if (typeof window.initNestedList === 'function') {
          window.initNestedList(); // ví dụ khởi tạo SortableJS
        }
      } catch (e) {
        console.error('reloadNestedList error:', e);
        // Fallback tối thiểu:
        window.location.reload();
      }
    }

    let current = {
      url: null,
      btn: null
    };

    // Clear state when modal is hidden
    deleteModalEl.addEventListener('hidden.bs.modal', function() {
      current = {
        url: null,
        btn: null
      };
      confirmBtn.disabled = false;
      confirmBtn.textContent = 'Xoá';
    });

    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-delete');
      if (!btn) return;

      const url = btn.getAttribute('data-url');
      const name = btn.getAttribute('data-name') || 'bản ghi';
      if (!url) {
        alert('Thiếu URL xoá.');
        return;
      }

      deleteNameEl.textContent = name;
      current.url = url;
      current.btn = btn;

      modal.show();
    });

    confirmBtn.addEventListener('click', async () => {
      if (!current.url) return;

      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Đang xoá...';

      const body = new URLSearchParams();
      body.set('_method', 'DELETE');

      try {
        const resp = await fetch(current.url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': getCsrf(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
          },
          body
        });

        let data = null,
          text = null;
        try {
          data = await resp.json();
        } catch {
          text = await resp.text();
        }

        if (resp.ok) {
          modal.hide();
          toastSuccess((data && (data.message || data.msg)) || 'Xoá thành công.');

          // Dispatch custom event for specific page handling
          window.dispatchEvent(new CustomEvent('delete-success', {
            detail: {
              url: current.url,
              btn: current.btn,
              data: data
            }
          }));

          // >>> Reload lại nested list
          await reloadNestedList();

          // >>> Reload DataTables nếu có
          await reloadDataTableIfAny();
        } else {
          const msg = (data && (data.message || data.error || data.msg)) || text || `Xoá thất bại (HTTP ${resp.status}).`;
          toastError(msg);
        }
      } catch (err) {
        console.error(err);
        toastError('Không thể kết nối máy chủ.');
      } finally {
        // Logic moved to hidden.bs.modal for better cleanup
      }
    });

    // Toast helpers
    window.toastSuccess = window.toastSuccess || (msg => showToast({
      message: msg,
      type: 'success'
    }));
    window.toastError = window.toastError || (msg => showToast({
      message: msg,
      type: 'error'
    }));
  })();
</script>

<!-- multi select -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const initTagify = (selector, whitelist) => {
    const el = document.querySelector(selector);
    if (!el || typeof Tagify === 'undefined') return null;

    return new Tagify(el, {
      whitelist,
      enforceWhitelist: true,
      dropdown: {
        maxItems: 10000,
        classname: 'tags-inline',
        enabled: 0,
        closeOnSelect: false
      }
    });
  };

  @if(!empty($tags))
    const tagWhitelist = @json($tags->pluck('name')->values());
    const tagifyTags = initTagify('#tag_names', tagWhitelist);
    tagifyTags?.addTags(@json(
      old('tag_names')
        ? collect(json_decode(old('tag_names'), true) ?: [])->map(fn($v) => is_array($v) ? ($v['value'] ?? null) : $v)->filter()->values()
        : (isset($item) ? $item->tags?->pluck('name')->values() : [])
    ));
  @endif

  @if(!empty($authors))
    const authorWhitelist = @json($authors->pluck('name')->values());
    const tagifyAuthors = initTagify('#author_names', authorWhitelist);
    tagifyAuthors?.addTags(@json(
      old('author_names')
        ? collect(json_decode(old('author_names'), true) ?: [])->map(fn($v) => is_array($v) ? ($v['value'] ?? null) : $v)->filter()->values()
        : (isset($item) ? $item->authors?->pluck('name')->values() : [])
    ));
  @endif

  @if(!empty($genres))
    const genreWhitelist = @json($genres->pluck('name')->values());
    const tagifyGenres = initTagify('#genre_names', genreWhitelist);
    tagifyGenres?.addTags(@json(
      old('genre_names')
        ? collect(json_decode(old('genre_names'), true) ?: [])->map(fn($v) => is_array($v) ? ($v['value'] ?? null) : $v)->filter()->values()
        : (isset($item) ? $item->genres?->pluck('name')->values() : [])
    ));
  @endif

  @if(!empty($countries))
    const countryWhitelist = @json($countries->pluck('name')->values());
    const tagifyCountries = initTagify('#country_names', countryWhitelist);
    tagifyCountries?.addTags(@json(
      old('country_names')
        ? collect(json_decode(old('country_names'), true) ?: [])->map(fn($v) => is_array($v) ? ($v['value'] ?? null) : $v)->filter()->values()
        : (isset($item) ? $item->countries?->pluck('name')->values() : [])
    ));
  @endif
});
</script>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
  (function() {
    let hls, videoEl;
    document.addEventListener('DOMContentLoaded', () => {
      videoEl = document.getElementById('hlsVideo');
      const modal = document.getElementById('hlsPlayerModal');
      modal?.addEventListener('hidden.bs.modal', () => {
        // cleanup để giải phóng kết nối/ram
        if (hls) {
          hls.destroy();
          hls = null;
        }
        if (videoEl) {
          videoEl.pause();
          videoEl.removeAttribute('src');
          videoEl.load();
        }
      });
    });

    window.showHlsPlayer = function(src) {
      if (!src) return;
      const modalEl = document.getElementById('hlsPlayerModal');
      if (!modalEl || !videoEl || !window.bootstrap?.Modal) return;

      const modal = new bootstrap.Modal(modalEl);
      modal.show();
      // Safari/iOS phát HLS native, các trình duyệt khác dùng hls.js
      if (Hls.isSupported()) {
        if (hls) {
          hls.destroy();
        }
        hls = new Hls({
          // tuỳ chọn nếu cần:
          // maxBufferLength: 30,
          // xhrSetup: (xhr) => { xhr.withCredentials = true; }
        });
        hls.loadSource(src);
        hls.attachMedia(videoEl);
        hls.on(Hls.Events.MANIFEST_PARSED, () => videoEl.play().catch(() => {}));
        hls.on(Hls.Events.ERROR, (ev, data) => {
          console.error('[HLS ERROR]', data);
        });
      } else if (videoEl.canPlayType('application/vnd.apple.mpegurl')) {
        videoEl.src = src;
        videoEl.play().catch(() => {});
      } else {
        // fallback mở tab mới (ít gặp)
        window.open(src, '_blank', 'noopener');
      }
    };
  })();
</script>
