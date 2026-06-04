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

@include('partials.css.category')
@endsection

@include('partials.js.category')