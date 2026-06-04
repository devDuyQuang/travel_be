@push('scripts')
<script>
(function(){
function bindPostFilters() {
  if (!jQuery.fn.DataTable || !jQuery.fn.DataTable.isDataTable('#reload-table')) {
    setTimeout(bindPostFilters, 200);
    return;
  }

  var table = jQuery('#reload-table').DataTable();

  function getBaseAjaxUrl() {
    var ajax = table.ajax.url();

    if (!ajax) {
      return '';
    }

    return ajax.split('?')[0];
  }

  function reloadWithFilters() {
    var categoryId = jQuery('#post-filter-category').val();
    var creatorId = jQuery('#post-filter-creator').val();

    var params = new URLSearchParams();

    if (categoryId) {
      params.set('category_id', categoryId);
    }

    if (creatorId) {
      params.set('created_by', creatorId);
    }

    var url = getBaseAjaxUrl();

    if (params.toString()) {
      url += '?' + params.toString();
    }

    table.ajax.url(url).load();
  }

  jQuery('#post-filter-category, #post-filter-creator')
    .off('change.postFilter')
    .on('change.postFilter', reloadWithFilters);
}

bindPostFilters();
  jQuery(document).off('change', '.post-status-toggle').on('change', '.post-status-toggle', function() {
    var chk = this;
    var url = chk.getAttribute('data-url');

    if (!url) return;

    var dt = jQuery('#reload-table').DataTable();
    var isOn = chk.checked;

    jQuery.ajax({
      url: url,
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      success: function(res) {
        var rowData = dt.row(jQuery(chk).closest('tr')).data();

        if (rowData) {
          rowData.status = res.status;
        }

        if (typeof window.toastSuccess === 'function') {
          window.toastSuccess(isOn ? 'Đã bật hiển thị.' : 'Đã tắt hiển thị.');
        }
      },
      error: function() {
        chk.checked = !chk.checked;

        if (typeof window.toastError === 'function') {
          window.toastError('Không thể cập nhật trạng thái.');
        } else {
          alert('Không thể cập nhật trạng thái.');
        }
      }
    });
  });

  function disposeBootstrapInstance(el, type) {
    if (typeof bootstrap === 'undefined') return;

    var instance = type === 'tooltip'
      ? bootstrap.Tooltip.getInstance(el)
      : bootstrap.Popover.getInstance(el);

    if (instance) {
      instance.dispose();
    }
  }

  function initPostPopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.post-meta-trigger').forEach(function(el) {
      disposeBootstrapInstance(el, 'popover');

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'left',
        container: 'body',
        sanitize: false
      });
    });
  }

  function initPostTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-post-tooltip').forEach(function(el) {
      disposeBootstrapInstance(el, 'tooltip');

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

  function initPostUiHelpers() {
    initPostPopovers();
    initPostTooltips();
  }

  jQuery(document).on('draw.dt', function() {
    initPostUiHelpers();
  });

  setTimeout(initPostUiHelpers, 300);
})();
</script>
@endpush
