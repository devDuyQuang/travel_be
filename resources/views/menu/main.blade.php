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
  return '<span class="drag-handle"><i class="icon-base ti tabler-grip-vertical"></i></span>';
JS;

$nameRender = <<<'JS'
  if (type !== 'display') return data;

  const depth = Number.parseInt(row?.depth ?? 0, 10) || 0;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const buildPublicUrl = (rawPath) => {
    const value = String(rawPath || '').trim();
    if (!value) return '';
    if (/^https?:\/\//i.test(value)) return value;

    const protocol = window.location.protocol;
    let host = window.location.hostname.replace(/^admin\./i, '');

    if (host === 'localhost') {
      host = 'localhost:3000';
    }

    return protocol + '//' + host + '/' + value.replace(/^\//, '');
  };

  const name = esc(data || '—');
  const path = String(row?.path || row?.public_url || '').trim();
  const href = buildPublicUrl(path);

  const hasLink = !!row?.part_id && path !== '' && href !== '';

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

  let actionsHtml = '';

  if (hasLink) {
    actionsHtml +=
      '<a href="' + esc(href) + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="menu-inline-icon menu-inline-icon-link js-menu-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<i class="icon-base ti tabler-external-link"></i>' +
      '</a>';
  }

  actionsHtml +=
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
  const wrapStyle = depth === 0 ? '' : 'padding-left:' + (((depth - 1) * 20) + 8) + 'px;';

  return '' +
    '<div class="' + wrapClass + ' menu-name-wrap"' + (wrapStyle ? ' style="' + wrapStyle + '"' : '') + '>' +
      '<div class="menu-name-line">' +
        '<div class="menu-name-label">' + labelHtml + '</div>' +
        '<div class="menu-name-actions ' + (hasLink ? 'has-link' : 'no-link') + '">' + actionsHtml + '</div>' +
      '</div>' +
    '</div>';
JS;

$locationRender = <<<'JS'
  if (type !== 'display') return data;

  const loc = row && row.location ? String(row.location) : '';
  if (!loc) return '<span class="text-muted">—</span>';

  const cls = loc.toLowerCase() === 'header'
    ? 'bg-label-primary'
    : loc.toLowerCase() === 'footer'
      ? 'bg-label-warning'
      : 'bg-label-secondary';

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  return '<span class="badge ' + cls + ' text-capitalize">' + esc(loc) + '</span>';
JS;

$statusRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.status, 10) === 1;
  const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';

  return '<div class="form-check form-switch form-check-inline mb-0">' +
    '<input type="checkbox" class="form-check-input menu-status-toggle status-toggle-wide" ' +
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
    <div class="menu-action-icons">
      <a href="\${escAction(editUrl)}"
         class="menu-action-icon menu-action-edit js-menu-tooltip"
         data-bs-toggle="tooltip"
         data-bs-placement="top"
         title="Chỉnh sửa">
        <i class="icon-base ti tabler-pencil"></i>
      </a>

      <a href="javascript:void(0)"
         class="menu-action-icon menu-action-delete btn-delete js-menu-tooltip"
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

$options = [
  'control' => true,
  'order' => [],
  'rendersByKey' => [
    'drag_handle' => $dragHandleRender,
    'name' => $nameRender,
    'location' => $locationRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],
  'columnDefs' => [
    ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center align-middle', 'width' => '30px'],
    ['targets' => 1, 'className' => 'text-start all align-middle'],
    ['targets' => 2, 'className' => 'text-center all align-middle'],
    ['targets' => 3, 'className' => 'text-center all align-middle'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center align-middle'],
  ],
  'responsiveModal' => false,
  'searchPlaceholder' => 'Tìm menu...',
  'pageLength' => 500,
];
@endphp

<x-table-header
  :title="page_title()" icon="ti tabler-menu-2"
  :create-route="panel_route(module().'.create')"
  :show-update-order="false" />

<x-data-table
  id="reload-table"
  :columns="[
    ['key'=>'drag_handle', 'title'=>''],
    ['key'=>'name', 'title'=>'TÊN'],
    ['key'=>'location', 'title'=>'VỊ TRÍ'],
    ['key'=>'status', 'title'=>'TRẠNG THÁI'],
    ['key'=>'actions', 'title'=>'HÀNH ĐỘNG']
  ]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options" />

<style>
  #reload-table td {
    vertical-align: middle;
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

  #reload-table tbody tr.seo-row-parent:hover {
    background-color: rgba(113, 221, 55, 0.04) !important;
  }

  #reload-table tbody tr.seo-row-child {
    background-color: rgba(47, 54, 74, 0.25) !important;
  }

  #reload-table tbody tr.seo-row-child:hover {
    background-color: rgba(47, 54, 74, 0.4) !important;
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
    flex: 0 0 auto;
    min-width: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
  }

  .menu-name-actions.has-link {
    min-width: 48px;
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
    justify-content: center;
  }

  .drag-handle i {
    font-size: 1.1rem;
    opacity: 0.5;
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

  .menu-status-toggle:disabled {
    cursor: not-allowed;
    opacity: 0.35;
  }

  .menu-action-icons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
  }

  .menu-action-icon {
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    line-height: 1;
  }

  .menu-action-icon i {
    font-size: 1rem;
    line-height: 1;
  }

  .menu-action-edit {
    color: #00cfe8 !important;
  }

  .menu-action-delete {
    color: #ea5455 !important;
  }

  .menu-action-icon:hover {
    opacity: 0.9;
  }
  #reload-table th.menu-action-col,
#reload-table td.menu-action-col {
  text-align: center !important;
  vertical-align: middle !important;
}

.menu-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: auto;
  min-width: 52px;
  margin: 0 auto;
}

.menu-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  line-height: 1;
}

.menu-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.menu-action-edit {
  color: #00cfe8 !important;
}

.menu-action-delete {
  color: #ea5455 !important;
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
