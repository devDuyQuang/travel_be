@extends('index')
@section('title', 'Quản lý Bằng cấp')

@section('content')
@php
$module = module();

$editRouteTpl   = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

$editRouteTplJson   = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

$actionsRenderByKey = <<<JS
const id = row.id ?? "";
const name = row.name ?? "";
const editUrl = id ? {$editRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";
const deleteUrl = id ? {$deleteRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";

const esc = (value) => String(value ?? "")
  .replace(/&/g, "&amp;")
  .replace(/</g, "&lt;")
  .replace(/>/g, "&gt;")
  .replace(/"/g, "&quot;");

return `
  <div class="degree-action-icons">
    <a href="\${esc(editUrl)}"
       class="degree-action-icon degree-action-edit js-degree-tooltip"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="degree-action-icon degree-action-delete btn-delete js-degree-tooltip"
       data-id="\${esc(id)}"
       data-name="\${esc(name)}"
       data-url="\${esc(deleteUrl)}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal"
       data-bs-placement="top"
       title="Xóa">
      <i class="icon-base ti tabler-trash"></i>
    </a>
  </div>
`;
JS;

$imageRenderByKey = <<<JS
if (!data) return '<span class="text-muted">No image</span>';
return `<img src="\${data}" alt="Image" class="rounded degree-image">`;
JS;

$metaRenderByKey = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const creator = esc(row?.creator || row?.creator_name || '—');

let createdAt = '—';

if (row?.created_at) {
  const date = new Date(row.created_at);

  const d = date.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });

  const t = date.toLocaleTimeString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit'
  });

  createdAt = esc(d + ' ' + t);
}

const popoverContent =
  '<div class="degree-meta-popover">' +
    '<div class="degree-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="degree-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return '<button type="button" ' +
  'class="btn p-0 border-0 bg-transparent degree-meta-trigger" ' +
  'data-bs-toggle="popover" ' +
  'data-bs-placement="left" ' +
  'data-bs-html="true" ' +
  'data-bs-trigger="hover focus" ' +
  'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
  '<i class="icon-base ti tabler-info-circle"></i>' +
'</button>';
JS;

$createdAtRenderByKey = <<<JS
if (!data) return '';
const date = new Date(data);
const d = date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
const t = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
return `<div class="d-flex flex-column"><span class="fw-bold">\${d}</span><small class="text-muted">\${t}</small></div>`;
JS;

$options = [
  'control' => true,
  'order' => [[0, 'desc']],
  'rendersByKey' => [
    'image' => $imageRenderByKey,
    'creator' => $metaRenderByKey,
    'id' => $actionsRenderByKey,
  ],
'columnDefs' => [
  ['targets' => 1, 'orderable' => false, 'searchable' => false, 'className' => 'degree-image-col text-center align-middle'],
  ['targets' => 5, 'className' => 'degree-meta-col text-center align-middle'],
  ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'degree-action-col text-center align-middle text-nowrap'],
],

  'responsiveModal' => true,
  'responsiveHeaderField' => 'name',
  'modalFields' => ['name', 'image', 'description', 'creator', 'created_at'],
  'modalRenders' => [
    'image' => "if (!data) return '<span class=\"text-muted\">No image</span>'; return `<img src=\"\${data}\" class=\"rounded\" style=\"width: 120px; height: 120px; object-fit: cover;\">`;",
    'created_at' => $createdAtRenderByKey,
  ],
  'searchPlaceholder' => 'Tìm kiếm bằng cấp...',
];
@endphp

<x-table-header
  :title="'Danh sách Bằng cấp'"
  icon="tabler-award"
  :create-route="panel_route(module().'.create')" />

<x-data-table
  id="reload-table"
:columns="[
  ['key'=>'name', 'title'=>'Tên bằng cấp'],
  ['key'=>'image', 'title'=>'Hình ảnh'],
  ['key'=>'description', 'title'=>'Mô tả'],
  ['key'=>'link_text', 'title'=>'Text liên kết'],
  ['key'=>'year', 'title'=>'Năm'],
  ['key'=>'creator', 'title'=>'Thông tin'],
  ['key'=>'id', 'title'=>'Hành động']
]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options"
/>

<style>
  .degree-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
  }

  .degree-meta-trigger {
    color: rgba(255,255,255,0.65) !important;
    box-shadow: none !important;
  }

  .degree-meta-trigger i {
    font-size: 1.05rem;
    line-height: 1;
  }

  .degree-meta-trigger:hover {
    opacity: 0.9;
  }

  .degree-meta-popover {
    min-width: 190px;
    font-size: 0.8125rem;
    line-height: 1.5;
  }

  .degree-meta-row {
    display: flex;
    gap: 6px;
    align-items: center;
    white-space: nowrap;
  }

  .degree-meta-row + .degree-meta-row {
    margin-top: 4px;
  }

  .degree-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    width: 100%;
  }

  .degree-action-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    text-decoration: none !important;
    line-height: 1;
  }

  .degree-action-icon i {
    font-size: 1.1rem;
    line-height: 1;
  }

  .degree-action-edit {
    color: #00cfe8 !important;
  }

  .degree-action-delete {
    color: #ea5455 !important;
  }

  .degree-action-icon:hover {
    opacity: 0.9;
  }

  #reload-table th.degree-meta-col,
  #reload-table td.degree-meta-col,
  #reload-table th.degree-action-col,
  #reload-table td.degree-action-col {
    text-align: center !important;
    vertical-align: middle !important;
  }
  #reload-table thead th:last-child,
#reload-table tbody td:last-child {
  width: 120px !important;
  min-width: 120px !important;
  max-width: 120px !important;
  text-align: center !important;
  vertical-align: middle !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}

#reload-table tbody td:last-child .degree-action-icons {
  width: 70px;
  min-width: 70px;
  height: 32px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
}

#reload-table tbody td:last-child .degree-action-icon {
  width: 24px;
  height: 24px;
  padding: 0;
  margin: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

#reload-table tbody td:last-child .degree-action-icon i {
  display: block;
  font-size: 1.1rem;
  line-height: 1;
}
</style>
@endsection

@push('scripts')
<script>
(function() {
  function disposeBootstrapInstance(el, type) {
    if (typeof bootstrap === 'undefined') return;

    var instance = type === 'tooltip'
      ? bootstrap.Tooltip.getInstance(el)
      : bootstrap.Popover.getInstance(el);

    if (instance) instance.dispose();
  }

  function initDegreePopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.degree-meta-trigger').forEach(function(el) {
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

  function initDegreeTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-degree-tooltip').forEach(function(el) {
      disposeBootstrapInstance(el, 'tooltip');

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

  function initDegreeUiHelpers() {
    initDegreePopovers();
    initDegreeTooltips();
  }

  jQuery(document).on('draw.dt', function() {
    initDegreeUiHelpers();
  });

  setTimeout(initDegreeUiHelpers, 300);
})();
</script>
@endpush