@extends('index')
@section('title', page_title())

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

$metaRenderByKey = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const creator = esc(row?.creator || row?.creator_name || '—');
const createdAt = esc(row?.created_at || '—');

const popoverContent =
  '<div class="user-meta-popover">' +
    '<div class="user-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="user-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return '<button type="button" ' +
  'class="btn p-0 border-0 bg-transparent user-meta-trigger" ' +
  'data-bs-toggle="popover" ' +
  'data-bs-placement="left" ' +
  'data-bs-html="true" ' +
  'data-bs-trigger="hover focus" ' +
  'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
  '<i class="icon-base ti tabler-info-circle"></i>' +
'</button>';
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
  <div class="user-action-icons">
    <a href="\${escAction(editUrl)}"
       class="user-action-icon user-action-edit js-user-tooltip"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="user-action-icon user-action-delete btn-delete js-user-tooltip"
       data-id="\${escAction(id)}"
       data-name="\${escAction(name)}"
       data-url="\${escAction(deleteUrl)}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal"
       data-bs-placement="top"
       title="Xoá">
      <i class="icon-base ti tabler-trash"></i>
    </a>
  </div>
`;
JS;

$options = [
  'control' => true,
  'order' => [[0, 'asc']],
  'rendersByKey' => [
    'creator' => $metaRenderByKey,
    'actions' => $actionsRenderByKey,
  ],
  'columnDefs' => [
    ['targets' => 4, 'className' => 'all text-center align-middle user-meta-col'],
    ['targets' => 5, 'className' => 'none'],
    ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center align-middle user-action-col'],
  ],
  'responsiveModal' => true,
  'responsiveHeaderField' => 'name',
  'modalFields' => ['name', 'email', 'role', 'creator', 'created_at'],
  'searchPlaceholder' => 'Nhập từ khóa...',
];
@endphp

<x-table-header
  :title="page_title()"
  icon="tabler-users"
  :create-route="panel_route(module().'.create')" />

<x-data-table
  id="reload-table"
  :columns="[
    ['key'=>'name', 'title'=>'NAME'],
    ['key'=>'email', 'title'=>'EMAIL'],
    ['key'=>'role', 'title'=>'ROLE'],
    ['key'=>'creator', 'title'=>'THÔNG TIN'],
    ['key'=>'__details', 'title'=>'', 'class'=>'none'],
    ['key'=>'actions', 'title'=>'HÀNH ĐỘNG']
  ]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options"
/>

<style>
  .user-meta-col {
    width: 110px;
    min-width: 110px;
    white-space: nowrap !important;
  }

  .user-meta-trigger {
    color: rgba(255,255,255,0.65) !important;
    box-shadow: none !important;
  }

  .user-meta-trigger i {
    font-size: 1.05rem;
    line-height: 1;
  }

  .user-meta-trigger:hover {
    opacity: 0.9;
  }

  .user-meta-popover {
    min-width: 200px;
    font-size: 0.8125rem;
    line-height: 1.5;
  }

  .user-meta-row {
    display: flex;
    gap: 6px;
    align-items: center;
    white-space: nowrap;
  }

  .user-meta-row + .user-meta-row {
    margin-top: 4px;
  }

  .user-action-col {
    width: 120px;
    min-width: 120px;
  }

  .user-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    width: 100%;
  }

  .user-action-icon {
    width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    line-height: 1;
  }

  .user-action-icon i {
    font-size: 1.1rem;
    line-height: 1;
  }

  .user-action-edit {
    color: #00cfe8 !important;
  }

  .user-action-delete {
    color: #ea5455 !important;
  }

  .user-action-icon:hover {
    opacity: 0.9;
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

  function initUserPopovers() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

    document.querySelectorAll('.user-meta-trigger').forEach(function(el) {
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

  function initUserTooltips() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) return;

    document.querySelectorAll('.js-user-tooltip').forEach(function(el) {
      disposeBootstrapInstance(el, 'tooltip');

      new bootstrap.Tooltip(el, {
        trigger: 'hover focus',
        placement: el.getAttribute('data-bs-placement') || 'top',
        container: 'body'
      });
    });
  }

  function initUserUiHelpers() {
    initUserPopovers();
    initUserTooltips();
  }

  jQuery(document).on('draw.dt', function() {
    initUserUiHelpers();
  });

  setTimeout(initUserUiHelpers, 300);
})();
</script>
@endpush