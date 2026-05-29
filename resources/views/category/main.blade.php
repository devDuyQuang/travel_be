@extends('index')
@section('title', page_title())

@section('content')
@php
$module = module();
$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);
$toggleStatusTpl = panel_route($module.'.toggle-status', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$toggleStatusTplJson = json_encode($toggleStatusTpl, JSON_UNESCAPED_SLASHES);

$dragHandleRender = <<<'JS'
  if (type !== 'display') return '';
  return '<span class="drag-handle" style="cursor:grab;"><i class="icon-base ti tabler-grip-vertical" style="font-size:1.1rem;opacity:0.5;"></i></span>';
JS;

$nameRender = <<<'JS'
  if (type !== 'display') return data;

  const depth = Number.parseInt(row?.depth ?? 0, 10) || 0;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const getCategoryPublicPath = () => {
    const publicUrl = String(row?.public_url || '').trim();

    if (publicUrl) return publicUrl;

    const fullSlug = String(row?.full_slug || '').trim();
    if (!fullSlug) return '';

    const slugParts = fullSlug.split('/').filter(Boolean);
    const lastSlug = slugParts.length ? slugParts[slugParts.length - 1] : '';
    if (!lastSlug) return '';

    const type = String(row?.type || '').trim().toLowerCase();

    const prefix = type === 'service'
      ? '/dich-vu'
      : type === 'post'
        ? '/bai-viet'
        : '/danh-muc';

    return prefix + '/' + lastSlug.replace(/^\/+/, '');
  };

  const buildPublicUrl = (rawPath) => {
    const value = String(rawPath || '').trim();
    if (!value) return '';

    if (/^https?:\/\//i.test(value)) return value;

    const protocol = window.location.protocol;
    let host = window.location.hostname.replace(/^admin\./i, '');

    if (host === 'localhost') {
      host = 'localhost:3000';
    }

    return protocol + '//' + host + '/' + value.replace(/^\/+/, '');
  };

  const name = esc(data || '—');
  const rawPath = getCategoryPublicPath();
  const href = buildPublicUrl(rawPath);
  const hasLink = rawPath !== '' && href !== '';

  const createdAt = esc(row?.created_at || '—');
  const creatorName = esc(row?.creator_name || row?.creator || '—');

  const popoverContent =
    '<div class="menu-meta-popover">' +
      '<div class="menu-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
      '<div class="menu-meta-row"><strong>Người tạo:</strong> <span>' + creatorName + '</span></div>' +
    '</div>';

  const labelHtml = depth === 0
    ? '<strong class="menu-item-title">' + name + '</strong>'
    : '<span class="seo-tree-connector">└────</span><span class="menu-item-title">' + name + '</span>';

  let iconsHtml = '';

  if (hasLink) {
    iconsHtml +=
      '<a href="' + href.replace(/"/g, '&quot;') + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="menu-inline-icon menu-inline-icon-link js-category-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<i class="icon-base ti tabler-external-link"></i>' +
      '</a>';
  }

  iconsHtml +=
    '<button type="button" ' +
      'class="btn p-0 border-0 bg-transparent menu-inline-icon menu-inline-icon-info menu-meta-trigger" ' +
      'data-bs-toggle="popover" ' +
      'data-bs-placement="left" ' +
      'data-bs-html="true" ' +
      'data-bs-trigger="hover focus" ' +
      'data-bs-custom-class="menu-meta-popover-wrap" ' +
      'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
      '<i class="icon-base ti tabler-info-circle"></i>' +
    '</button>';

  const wrapClass = depth === 0 ? 'seo-tree-parent' : 'seo-tree-child';
  const wrapStyle = depth === 0 ? '' : 'padding-left:' + ((depth - 1) * 20 + 8) + 'px;';

  return '' +
    '<div class="' + wrapClass + ' menu-name-wrap"' + (wrapStyle ? ' style="' + wrapStyle + '"' : '') + '>' +
      '<div class="menu-name-line">' +
        '<div class="menu-name-label">' + labelHtml + '</div>' +
        '<div class="menu-name-actions ' + (hasLink ? 'has-link' : 'no-link') + '">' + iconsHtml + '</div>' +
      '</div>' +
    '</div>';
JS;

$typeRender = <<<'JS'
  if (type !== 'display') return data;

  const t = row && row.type ? String(row.type) : '';
  if (!t) return '<span class="text-muted">—</span>';

  return '<span class="badge bg-label-info">' +
    t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') +
  '</span>';
JS;

$statusRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.status, 10) === 1;
  const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';

  return '<div class="form-check form-switch form-check-inline mb-0">' +
    '<input type="checkbox" class="form-check-input category-status-toggle status-toggle-wide" ' +
      'data-id="' + id + '" ' +
      'data-url="' + toggleUrl.replace(/"/g, '&quot;') + '" ' +
      (on ? 'checked' : '') +
    '>' +
  '</div>';
JS;

$toggleHomeTpl = panel_route($module.'.toggle-home', ['id' => '__ID__']);
$toggleHomeTplJson = json_encode($toggleHomeTpl, JSON_UNESCAPED_SLASHES);

$homeRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.home, 10) === 1;
  const toggleUrl = id ? {$toggleHomeTplJson}.replace('__ID__', id) : '';

  return '<div class="form-check form-switch form-check-inline mb-0">' +
    '<input type="checkbox" class="form-check-input category-home-toggle status-toggle-wide" ' +
      'data-id="' + id + '" ' +
      'data-url="' + toggleUrl.replace(/"/g, '&quot;') + '" ' +
      (on ? 'checked' : '') +
    '>' +
  '</div>';
JS;

$actionsRenderByKey = <<<JS
  const id = row.id ?? "";
  const name = row.name ?? "";
  const editUrl = id ? {$editRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";
  const deleteUrl = id ? {$deleteRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";

  const escAction = (value) => String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");

  return `
    <div class="category-action-icons">
      <a href="\${escAction(editUrl)}"
         class="category-action-icon category-action-edit js-category-tooltip"
         data-bs-toggle="tooltip"
         data-bs-placement="top"
         title="Chỉnh sửa">
        <i class="icon-base ti tabler-pencil"></i>
      </a>

      <a href="javascript:void(0)"
         class="category-action-icon category-action-delete btn-delete js-category-tooltip"
         data-id="\${escAction(id)}"
         data-name="\${escAction(name)}"
         data-url="\${escAction(deleteUrl)}"
         data-bs-toggle="modal"
         data-bs-target="#deleteModal"
         data-bs-placement="top"
         title="Xóa">
        <i class="icon-base ti tabler-trash"></i>
      </a>
    </div>
  `;
JS;

$columns = [
  ['key'=>'drag_handle', 'title'=>''],
  ['key'=>'name', 'title'=>'TÊN'],
  ['key'=>'type', 'title'=>'LOẠI'],
 ['key'=>'home', 'title'=>'HOME_ICON'],
  ['key'=>'status', 'title'=>'TRẠNG THÁI'],
  ['key'=>'actions', 'title'=>'HÀNH ĐỘNG'],
];

$options = [
  'control' => true,
  'order' => [],
  'rendersByKey' => [
    'home' => $homeRender,
    'drag_handle' => $dragHandleRender,
    'name' => $nameRender,
    'type' => $typeRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],
  'columnDefs' => [
    ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center', 'width' => '30px'],
    ['targets' => 1, 'className' => 'text-start all'],
    ['targets' => 2, 'className' => 'text-center all'],
    ['targets' => 3, 'className' => 'text-center all'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center category-action-col'],
    ],
  'responsiveModal' => false,
  'searchPlaceholder' => 'Tìm danh mục...',
  'pageLength' => 500,
];
@endphp

<x-table-header
  :title="page_title()" icon="tabler-category"
  :create-route="panel_route(module().'.create')"
  :show-update-order="false" />

<x-data-table
  id="reload-table"
  :columns="$columns"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options" />

<style>
  #reload-table tbody tr.seo-row-parent:hover {
    background-color: rgba(113, 221, 55, 0.04) !important;
  }

  #reload-table tbody tr.seo-row-child {
    background-color: rgba(47, 54, 74, 0.25) !important;
  }

  #reload-table tbody tr.seo-row-child:hover {
    background-color: rgba(47, 54, 74, 0.4) !important;
  }

  .seo-tree-parent,
  .seo-tree-child {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 200px;
    width: 100%;
  }

  .seo-tree-parent {
    font-size: 0.9rem;
  }

  .seo-tree-child {
    font-size: 0.85rem;
    opacity: 0.9;
  }

  .seo-tree-parent > div:first-child,
  .seo-tree-child > div:first-child {
    white-space: nowrap;
  }

  .menu-name-wrap {
    width: 100%;
  }

  .menu-name-line {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .menu-name-label {
    min-width: 0;
    flex: 1 1 auto;
    display: inline-flex;
    align-items: center;
    overflow: hidden;
    white-space: nowrap;
  }

  .menu-name-label strong,
  .menu-name-label span:last-child,
  .menu-item-title {
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .menu-name-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
  }

  .menu-name-actions.has-link {
    flex: 0 0 48px;
    min-width: 48px;
  }

  .menu-name-actions.no-link {
    flex: 0 0 20px;
    min-width: 20px;
  }

  .menu-inline-icon {
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    vertical-align: middle;
    line-height: 1;
  }

  .menu-inline-icon i {
    font-size: 0.95rem;
    line-height: 1;
  }

  .menu-inline-icon-link {
    color: #00cfe8 !important;
  }

  .menu-inline-icon-info {
    color: rgba(255,255,255,0.65) !important;
  }

  .menu-inline-icon:hover {
    opacity: 0.9;
  }

  .menu-meta-trigger {
    box-shadow: none !important;
  }

  .menu-meta-popover {
    font-size: 0.8125rem;
    line-height: 1.5;
    min-width: 180px;
  }

  .menu-meta-row + .menu-meta-row {
    margin-top: 4px;
  }

  .seo-tree-connector {
    color: rgba(255,255,255,0.3);
    font-family: monospace;
    font-size: 0.75rem;
    margin-right: 4px;
    user-select: none;
    flex: 0 0 auto;
  }

  .drag-handle {
    cursor: grab !important;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
  }

  .drag-handle:active {
    cursor: grabbing !important;
  }

  .drag-handle:hover i {
    opacity: 1 !important;
  }

  .sortable-ghost {
    opacity: 0.4;
    background-color: rgba(105, 108, 255, 0.1) !important;
  }

  .sortable-chosen {
    background-color: rgba(105, 108, 255, 0.05) !important;
  }

  .status-toggle-wide {
    width: 2.5em !important;
    min-width: 2.5em;
  }

  .category-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    width: 100%;
  }

  .category-action-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    text-decoration: none !important;
    line-height: 1;
  }

  .category-action-icon i {
    font-size: 1.1rem;
    line-height: 1;
  }

  .category-action-edit {
    color: #00cfe8 !important;
  }

  .category-action-delete {
    color: #ea5455 !important;
  }

  .category-action-icon:hover {
    opacity: 0.9;
  }

  #reload-table thead th:last-child,
#reload-table tbody td:last-child {
  text-align: center !important;
  vertical-align: middle !important;
}



#reload-table tbody td:last-child .category-action-icons {
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: auto !important;
  margin: 0 auto;
}

  #reload-table_wrapper .dt-paging,
  #reload-table_wrapper .dt-length,
  #reload-table_wrapper .dt-info,
  #reload-table_wrapper .dt-search,
  #reload-table_wrapper .dt-layout-start .dt-length,
  #reload-table_wrapper .dt-layout-end .dt-paging,
  #reload-table_wrapper .dt-layout-end .dt-search {
    display: none !important;
  }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function() {
  var table = document.getElementById('reload-table');
  if (!table) return;


  

  function styleRows() {
    if (!window.jQuery || !jQuery.fn.dataTable || !jQuery.fn.dataTable.isDataTable('#reload-table')) return;

    var dt = jQuery('#reload-table').DataTable();

    dt.rows().every(function() {
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

    document.querySelectorAll('.menu-meta-trigger').forEach(function(el) {
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

    document.querySelectorAll('.menu-meta-trigger').forEach(function(el) {
      var instance = bootstrap.Popover.getInstance(el);
      if (instance) instance.dispose();
    });
  }

  function initTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-category-tooltip').forEach(function(el) {
      var oldInstance = bootstrap.Tooltip.getInstance(el);
      if (oldInstance) oldInstance.dispose();

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

 function renderHomeHeaderIcon() {

  var headers = document.querySelectorAll('#reload-table thead th');

  headers.forEach(function(th) {

    var text = th.textContent.trim();

    if (text === 'HOME_ICON') {

      th.innerHTML = `
        <div class="d-flex align-items-center justify-content-center">
          <i class="icon-base ti tabler-home"
             style="font-size:1rem;"></i>
        </div>
      `;

      th.setAttribute('title', 'Trang chủ');
    }
  });
}

function initCategoryUiHelpers() {
  styleRows();
  initPopovers();
  initTooltips();
  renderHomeHeaderIcon();
}

  var interval = setInterval(function() {
    if (!window.jQuery || !jQuery.fn.dataTable || !jQuery.fn.dataTable.isDataTable('#reload-table')) return;

    clearInterval(interval);

    jQuery('#reload-table').on('draw.dt', function() {
      initCategoryUiHelpers();
    });

    initCategoryUiHelpers();

    jQuery('#reload-table thead th').css('cursor', 'default').off('click.DT');

    var tbody = document.querySelector('#reload-table tbody');

    if (tbody && window.Sortable) {
      Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
          var dt = jQuery('#reload-table').DataTable();
          var items = [];

          jQuery('#reload-table tbody tr').each(function(index) {
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
            success: function() {
              disposePopovers();
              dt.ajax.reload(null, false);
            },
            error: function() {
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
    .off('change', '.category-status-toggle')
    .on('change', '.category-status-toggle', function() {
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
        success: function(res) {
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
})();

jQuery(document)
  .off('change', '.category-home-toggle')
  .on('change', '.category-home-toggle', function() {

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
          rowData.home = res.home;
        }

        if (typeof window.toastSuccess === 'function') {
          window.toastSuccess(
            isOn
              ? 'Đã bật hiển thị trang chủ.'
              : 'Đã tắt hiển thị trang chủ.'
          );
        }
      },

      error: function() {

        chk.checked = !chk.checked;

        if (typeof window.toastError === 'function') {
          window.toastError('Không thể cập nhật.');
        }
      }
    });
});
</script>
@endpush