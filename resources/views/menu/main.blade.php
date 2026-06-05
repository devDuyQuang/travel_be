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

  return '' +
    '<span class="menu-drag-handle" title="Kéo thả để sắp xếp">' +
      '<span class="material-icons-outlined">drag_indicator</span>' +
    '</span>';
JS;

$indexRender = <<<'JS'
  return meta.row + meta.settings._iDisplayStart + 1;
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

    return protocol + '//' + host + '/' + value.replace(/^\/+/, '');
  };

  const name = esc(data || row?.name || '—');
  const path = String(row?.path || row?.public_url || '').trim();
  const href = buildPublicUrl(path);
  const hasLink = path !== '' && href !== '';

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
      '<a href="' + esc(href) + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="menu-name-icon menu-name-icon-link js-menu-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<span class="material-icons-outlined">open_in_new</span>' +
      '</a>';
  }

  iconsHtml +=
    '<button type="button" ' +
      'class="btn p-0 border-0 bg-transparent menu-name-icon menu-name-icon-info menu-meta-trigger" ' +
      'data-bs-toggle="popover" ' +
      'data-bs-placement="left" ' +
      'data-bs-html="true" ' +
      'data-bs-trigger="hover focus" ' +
      'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
      '<span class="material-icons-outlined">info</span>' +
    '</button>';

  const wrapStyle = depth === 0 ? '' : ' style="padding-left:' + (((depth - 1) * 20) + 8) + 'px;"';

  return '' +
    '<div class="menu-name-with-icons"' + wrapStyle + '>' +
      '<span class="menu-name-text">' + labelHtml + '</span>' +
      '<span class="menu-name-icons">' + iconsHtml + '</span>' +
    '</div>';
JS;

$locationRender = <<<'JS'
  if (type !== 'display') return data;

  const loc = row && row.location ? String(row.location) : '';

  if (!loc) {
    return '<span class="text-muted">—</span>';
  }

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

  return '' +
    '<div class="form-check form-switch form-check-inline mb-0">' +
      '<input type="checkbox" class="form-check-input menu-status-toggle status-toggle-wide" ' +
        'data-id="' + id + '" ' +
        'data-url="' + toggleUrl.replace(/"/g, '&quot;') + '" ' +
        (on ? 'checked' : '') +
      '>' +
    '</div>';
JS;

$actionsRenderByKey = <<<JS
  if (type !== 'display') return '';

  const id = row.id ?? "";
  const name = row.name ?? "";
  const editUrl = id ? {$editRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";
  const deleteUrl = id ? {$deleteRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";

  const escAction = (value) => String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");

  return '' +
    '<div class="menu-action-icons">' +
      '<a href="' + escAction(editUrl) + '" ' +
         'class="menu-action-icon menu-action-edit js-menu-tooltip" ' +
         'data-bs-toggle="tooltip" ' +
         'data-bs-placement="top" ' +
         'title="Chỉnh sửa">' +
        '<span class="material-icons-outlined">edit</span>' +
      '</a>' +

      '<a href="javascript:void(0)" ' +
         'class="menu-action-icon menu-action-delete btn-delete js-menu-tooltip" ' +
         'data-id="' + escAction(id) + '" ' +
         'data-name="' + escAction(name) + '" ' +
         'data-url="' + escAction(deleteUrl) + '" ' +
         'data-bs-toggle="modal" ' +
         'data-bs-target="#deleteModal" ' +
         'data-bs-placement="top" ' +
         'title="Xóa">' +
        '<span class="material-icons-outlined">delete</span>' +
      '</a>' +
    '</div>';
JS;

$options = [
  'control' => false,
  'ordering' => false,
  'order' => [],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'pageLength' => 10,
  'pagingType' => 'full_numbers',
  'searchPlaceholder' => 'Nhập từ khóa...',

  'language' => [
    'lengthMenu' => 'Hiển thị _MENU_ dòng',
    'search' => 'Tìm kiếm:',
    'info' => 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
    'infoEmpty' => 'Hiển thị 0 đến 0 của 0 dòng',
    'infoFiltered' => '(lọc từ _MAX_ dòng)',
    'zeroRecords' => 'Không tìm thấy dữ liệu phù hợp',
    'emptyTable' => 'Không có dữ liệu',
    'paginate' => [
      'first' => '«',
      'previous' => '‹',
      'next' => '›',
      'last' => '»',
    ],
  ],

 'rendersByKey' => [
  'drag_handle' => $dragHandleRender,
  'name' => $nameRender,
  'location' => $locationRender,
  'status' => $statusRender,
  'actions' => $actionsRenderByKey,
],

'columnDefs' => [
  ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap menu-drag-col'],
  ['targets' => 1, 'className' => 'text-start menu-name-col'],
  ['targets' => 2, 'className' => 'text-center text-nowrap menu-location-col'],
  ['targets' => 3, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap menu-status-col'],
  ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap menu-action-col'],
],
];
@endphp

@include('partials.css.menu')

<main class="main-wrapper menu-list-page">
  <div class="main-content">

    <div class="menu-page-header">
      <div>
        <h5 class="menu-page-title">
          <span class="material-icons-outlined">menu</span>
          {{ page_title() }}
        </h5>
      </div>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary menu-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="menu-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
         ['key' => 'drag_handle', 'title' => ''],
          ['key' => 'name', 'title' => 'TÊN'],
          ['key' => 'location', 'title' => 'VỊ TRÍ'],
          ['key' => 'status', 'title' => 'TRẠNG THÁI'],
          ['key' => 'actions', 'title' => 'HÀNH ĐỘNG']
        ]"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection

@include('partials.js.menu')