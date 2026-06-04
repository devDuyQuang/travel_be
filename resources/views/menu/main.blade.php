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

@include('partials.css.menu')
@endsection

@include('partials.js.menu')
