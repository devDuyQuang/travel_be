@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function () {
  var table = document.getElementById('reload-table');
  if (!table) return;

  function styleRows() {
    if (!window.jQuery || !jQuery.fn.dataTable || !jQuery.fn.dataTable.isDataTable('#reload-table')) return;

    var dt = jQuery('#reload-table').DataTable();

    dt.rows().every(function () {
      var data = this.data();
      var node = this.node();

      if (!data || !node) return;

      node.classList.remove('seo-row-parent', 'seo-row-child');

      if (parseInt(data.depth || 0, 10) === 0) {
        node.classList.add('seo-row-parent');
      } else {
        node.classList.add('seo-row-child');
      }
    });
  }

  function initPopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.menu-meta-trigger').forEach(function (el) {
      var oldInstance = bootstrap.Popover.getInstance(el);
      if (oldInstance) oldInstance.dispose();

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'left',
        container: 'body',
        sanitize: false
      });
    });
  }

  function disposePopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.menu-meta-trigger').forEach(function (el) {
      var instance = bootstrap.Popover.getInstance(el);
      if (instance) instance.dispose();
    });
  }

  function initTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-menu-tooltip').forEach(function (el) {
      var oldInstance = bootstrap.Tooltip.getInstance(el);
      if (oldInstance) oldInstance.dispose();

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

  function initMenuUiHelpers() {
    styleRows();
    initPopovers();
    initTooltips();
  }

  var interval = setInterval(function () {
    if (!window.jQuery || !jQuery.fn.dataTable || !jQuery.fn.dataTable.isDataTable('#reload-table')) return;

    clearInterval(interval);

    jQuery('#reload-table').on('draw.dt', function () {
      initMenuUiHelpers();
    });

    initMenuUiHelpers();

    jQuery('#reload-table thead th').css('cursor', 'default').off('click.DT');

    var tbody = document.querySelector('#reload-table tbody');

    if (tbody && window.Sortable) {
      Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function () {
          var dt = jQuery('#reload-table').DataTable();
          var items = [];

          jQuery('#reload-table tbody tr').each(function (index) {
            var rowData = dt.row(this).data();

            if (rowData && rowData.id) {
              items.push({
                id: rowData.id,
                position: index
              });
            }
          });

          jQuery.ajax({
            url: '{{ panel_route(module().".reorder") }}',
            method: 'POST',
            data: JSON.stringify({ items: items }),
            contentType: 'application/json',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success: function () {
              disposePopovers();
              dt.ajax.reload(null, false);
            },
            error: function () {
              disposePopovers();
              alert('Lỗi khi cập nhật thứ tự!');
              dt.ajax.reload(null, false);
            }
          });
        }
      });
    }
  }, 200);

  jQuery(document)
    .off('change', '.menu-status-toggle')
    .on('change', '.menu-status-toggle', function () {
      var chk = this;

    

      var url = chk.getAttribute('data-url');
      if (!url) return;

      var dt = jQuery('#reload-table').DataTable();
      if (!dt) return;

      var isOn = chk.checked;

      jQuery.ajax({
        url: url,
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        success: function (res) {
          var rowData = dt.row(jQuery(chk).closest('tr')).data();

          if (rowData) {
            rowData.status = res.status;
          }

          if (typeof window.toastSuccess === 'function') {
            window.toastSuccess(isOn ? 'Đã bật hiển thị.' : 'Đã tắt hiển thị.');
          } else {
            alert(isOn ? 'Đã bật hiển thị.' : 'Đã tắt hiển thị.');
          }
        },
        error: function () {
          chk.checked = !chk.checked;

          if (typeof window.toastError === 'function') {
            window.toastError('Không thể cập nhật trạng thái.');
          } else {
            alert('Không thể cập nhật trạng thái.');
          }
        }
      });
    });
})();
</script>
@endpush
