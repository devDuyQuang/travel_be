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

$indexRender = <<<'JS'
  return meta.row + meta.settings._iDisplayStart + 1;
JS;

$categoriesRender = <<<'JS'
  if (type !== 'display') return data;

  const list = row && Array.isArray(row.categories) ? row.categories : [];
  if (!list.length) return '<span class="text-muted">—</span>';

  const esc = (s) => String(s || '')
    .replace(/&/g,'&amp;')
    .replace(/</g,'&lt;')
    .replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;');

  return list.map(n =>
    '<span class="badge bg-label-primary me-1 mb-1">' + esc(n) + '</span>'
  ).join('');
JS;

$nameRender = <<<'JS'
  if (type !== 'display') return data;

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

  const name = esc(row?.name || '—');
  const href = buildPublicUrl(row?.public_url || '');

  const createdAt = esc(row?.created_at || '—');
  const creator = esc(row?.creator_name || row?.creator || '—');

  const updatedAt = esc(row?.updated_at || '—');
  const updater = esc(row?.updater_name || row?.updater || '—');

  const popoverContent =
    '<div class="menu-meta-popover">' +
      '<div class="menu-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
      '<div class="menu-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
      '<hr class="my-2">' +
      '<div class="menu-meta-row"><strong>Cập nhật cuối:</strong> <span>' + updatedAt + '</span></div>' +
      '<div class="menu-meta-row"><strong>Người cập nhật:</strong> <span>' + updater + '</span></div>' +
    '</div>';

  let iconsHtml = '';

  if (href) {
    iconsHtml +=
      '<a href="' + href.replace(/"/g, '&quot;') + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="post-name-icon post-name-icon-link js-post-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<i class="icon-base ti tabler-external-link"></i>' +
      '</a>';
  }

  iconsHtml +=
    '<button type="button" ' +
      'class="btn p-0 border-0 bg-transparent post-name-icon post-name-icon-info post-meta-trigger" ' +
      'data-bs-toggle="popover" ' +
      'data-bs-placement="left" ' +
      'data-bs-html="true" ' +
      'data-bs-trigger="hover focus" ' +
      'data-bs-custom-class="menu-meta-popover-wrap" ' +
      'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
      '<i class="icon-base ti tabler-info-circle"></i>' +
    '</button>';

  return '<div class="post-name-with-icons">' +
    '<span class="post-name-text">' + name + '</span>' +
    '<span class="post-name-icons">' + iconsHtml + '</span>' +
  '</div>';
JS;

$statusRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.status, 10) === 1;
  const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';

  return '<div class="form-check form-switch form-check-inline mb-0">' +
    '<input type="checkbox" class="form-check-input post-status-toggle status-toggle-wide" ' +
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

return `
  <div class="post-action-icons">
    <a href="\${editUrl}"
       class="post-action-icon post-action-edit js-post-tooltip"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="post-action-icon post-action-delete btn-delete"
       data-id="\${id}"
       data-name="\${String(name).replace(/"/g, '&quot;')}"
       data-url="\${deleteUrl}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal"
       title="Xóa">
      <i class="icon-base ti tabler-trash"></i>
    </a>
  </div>
`;
JS;

$options = [
  'control' => true,
  'order' => [[0, 'desc']],
'rendersByKey' => [
    'index' => $indexRender,
    'name' => $nameRender,
    'categories' => $categoriesRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
],
'columnDefs' => [
  ['targets' => 0, 'className' => 'text-center all text-nowrap post-index-col'],
  ['targets' => 1, 'className' => 'text-start all post-name-col'],
  ['targets' => 2, 'className' => 'text-start min-tablet post-category-col'],
  ['targets' => 3, 'className' => 'text-center all text-nowrap post-status-col'],
  ['targets' => 4, 'className' => 'text-center all text-nowrap post-action-col'],
],
  'responsiveModal' => false,
  'searchPlaceholder' => 'Nhập từ khóa...',
];
@endphp

<x-table-header :title="page_title()" icon="tabler-news" :create-route="panel_route(module().'.create')" />
<div class="post-toolbar-filters">
  <select id="post-filter-category" class="form-select form-select-sm">
    <option value="">Tất cả danh mục</option>
    @foreach($filterCategories as $category)
      <option value="{{ $category->id }}">
        {{ $category->name }} ({{ $category->posts_count }} bài)
      </option>
    @endforeach
  </select>

 <select id="post-filter-creator" class="form-select form-select-sm">
  <option value="">Tất cả người tạo</option>
  @foreach($filterCreators as $creator)
    <option value="{{ $creator->id }}">
      {{ $creator->name }} ({{ $creator->posts_count }} bài)
    </option>
  @endforeach
</select>
</div>
<x-data-table
  id="reload-table"
  :columns="[
    ['key' => 'index', 'title' => 'STT'],
    ['key' => 'name', 'title' => 'TÊN BÀI VIẾT'],
    ['key' => 'categories', 'title' => 'DANH MỤC'],
    ['key' => 'status', 'title' => 'TRẠNG THÁI'],
    ['key' => 'actions', 'title' => 'HÀNH ĐỘNG']
  ]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options"
/>

<style>
.post-toolbar-filters {
  position: absolute;
  top: 24px;
  right: 170px;
  z-index: 5;
  display: flex;
  gap: 8px;
}
.post-index-col {
  width: 60px;
  min-width: 60px;
}

.post-toolbar-filters .form-select {
  width: 220px;
}

@media (max-width: 991px) {
  .post-toolbar-filters {
    position: static;
    margin-bottom: 12px;
    justify-content: flex-end;
  }

  .post-toolbar-filters .form-select {
    width: 100%;
  }
}

.status-toggle-wide {
  width: 2.5em !important;
  min-width: 2.5em;
}

#reload-table {
  width: 100% !important;
}

#reload-table th {
  white-space: nowrap;
}

#reload-table td {
  vertical-align: middle;
}

.post-name-with-icons {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 52px;
  align-items: center;
  gap: 12px;
  width: 100%;
}

.post-name-text {
  min-width: 0;
  line-height: 1.45;
  word-break: break-word;
}

.post-name-icons {
  display: inline-flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  width: 52px;
  flex-shrink: 0;
}

.post-name-icon {
  width: 18px;
  height: 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none !important;
  line-height: 1;
}

.post-name-icon i {
  font-size: 1.05rem;
  line-height: 1;
}

.post-name-icon-link {
  color: #00cfe8 !important;
}

.post-name-icon-info {
  color: rgba(255,255,255,0.65) !important;
}

.post-name-icon:hover {
  opacity: 0.9;
}

.post-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
}

.post-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  line-height: 1;
}

.post-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.post-action-edit {
  color: #00cfe8 !important;
}

.post-action-delete {
  color: #ea5455 !important;
}

.post-action-icon:hover {
  opacity: 0.9;
}

.menu-meta-popover {
  min-width: 220px;
}

.menu-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.menu-meta-row + .menu-meta-row {
  margin-top: 4px;
}
</style>
@endsection

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