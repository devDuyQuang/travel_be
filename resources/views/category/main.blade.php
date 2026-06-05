@extends('index')
@section('title', page_title())

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);
$toggleStatusTpl = panel_route($module.'.toggle-status', ['id' => '__ID__']);
$toggleHomeTpl = panel_route($module.'.toggle-home', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$toggleStatusTplJson = json_encode($toggleStatusTpl, JSON_UNESCAPED_SLASHES);
$toggleHomeTplJson = json_encode($toggleHomeTpl, JSON_UNESCAPED_SLASHES);

$dragHandleRender = <<<'JS'
  if (type !== 'display') return '';

  return '' +
    '<span class="category-drag-handle" title="Kéo thả để sắp xếp">' +
      '<span class="material-icons-outlined">drag_indicator</span>' +
    '</span>';
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

  const name = esc(row?.name || data || '—');
  const href = buildPublicUrl(row?.public_url || '');

  const createdAt = esc(row?.created_at || '—');
  const creatorName = esc(row?.creator_name || row?.creator || '—');

  const popoverContent =
    '<div class="category-meta-popover">' +
      '<div><strong>Ngày tạo:</strong> ' + createdAt + '</div>' +
      '<div><strong>Người tạo:</strong> ' + creatorName + '</div>' +
    '</div>';

  const prefix = depth > 0
    ? '<span class="category-tree-prefix">└────</span>'
    : '';

  let iconsHtml = '';

  if (href) {
    iconsHtml +=
      '<a href="' + href.replace(/"/g, '&quot;') + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="category-name-icon category-name-icon-link js-category-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<span class="material-icons-outlined">open_in_new</span>' +
      '</a>';
  }

  iconsHtml +=
    '<button type="button" ' +
      'class="btn p-0 border-0 bg-transparent category-name-icon category-name-icon-info" ' +
      'data-bs-toggle="popover" ' +
      'data-bs-placement="left" ' +
      'data-bs-html="true" ' +
      'data-bs-trigger="hover focus" ' +
      'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
      '<span class="material-icons-outlined">info</span>' +
    '</button>';

  return '' +
    '<div class="category-name-with-icons" style="padding-left:' + (depth * 18) + 'px">' +
      '<span class="category-name-text">' + prefix + name + '</span>' +
      '<span class="category-name-icons">' + iconsHtml + '</span>' +
    '</div>';
JS;

$typeRender = <<<'JS'
  if (type !== 'display') return data;

  const value = row && row.type ? String(row.type) : '';
  if (!value) return '<span class="text-muted">—</span>';

  const esc = (s) => String(s || '')
    .replace(/&/g,'&amp;')
    .replace(/</g,'&lt;')
    .replace(/>/g,'&gt;');

  return '<span class="category-type-badge">' + esc(value) + '</span>';
JS;

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
    '<div class="category-action-icons">' +
      '<a href="' + escAction(editUrl) + '" ' +
         'class="category-action-icon category-action-edit js-category-tooltip" ' +
         'data-bs-toggle="tooltip" ' +
         'data-bs-placement="top" ' +
         'title="Chỉnh sửa">' +
        '<span class="material-icons-outlined">edit</span>' +
      '</a>' +

      '<a href="javascript:void(0)" ' +
         'class="category-action-icon category-action-delete btn-delete js-category-tooltip" ' +
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

$columns = [
  ['key' => 'drag_handle', 'title' => ''],
  ['key' => 'name', 'title' => 'TÊN'],
  ['key' => 'type', 'title' => 'LOẠI'],
  ['key' => 'home', 'title' => 'HIỂN THỊ'],
  ['key' => 'status', 'title' => 'TRẠNG THÁI'],
  ['key' => 'actions', 'title' => 'HÀNH ĐỘNG'],
];

$options = [
  'control' => false,
  'ordering' => false,
  'order' => [],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'pageLength' => 500,
  'pagingType' => 'full_numbers',
  'searchPlaceholder' => 'Tìm danh mục...',

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
    'type' => $typeRender,
    'home' => $homeRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap category-drag-col'],
    ['targets' => 1, 'className' => 'text-start category-name-col'],
    ['targets' => 2, 'className' => 'text-center text-nowrap category-type-col'],
    ['targets' => 3, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap category-home-col'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap category-status-col'],
    ['targets' => 5, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap category-action-col'],
  ],
];
@endphp

@include('partials.css.category')

<main class="main-wrapper category-list-page">
  <div class="main-content">

    <div class="category-page-header">
      <h5 class="category-page-title">
        <span class="material-icons-outlined">category</span>
        Danh Mục
      </h5>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary category-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="category-table-card">
      <x-data-table
        id="reload-table"
        :columns="$columns"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection

@include('partials.js.category')