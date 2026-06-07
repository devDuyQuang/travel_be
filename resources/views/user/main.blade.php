@extends('index')
@section('title', page_title())

@section('content')
@include('partials.css.user')

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

return `
  <div class="user-action-icons">
    <a href="\${escAction(editUrl)}"
       class="user-action-icon user-action-edit js-user-tooltip"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="Chỉnh sửa">
      <span class="material-icons-outlined">edit</span>
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
      <span class="material-icons-outlined">delete</span>
    </a>
  </div>
`;
JS;

$options = [
  'control' => false,
  'order' => [[0, 'asc']],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
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
    'creator' => $metaRenderByKey,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-start user-name-col'],
    ['targets' => 1, 'className' => 'text-start user-email-col'],
    ['targets' => 2, 'className' => 'text-start user-role-col'],
    ['targets' => 3, 'className' => 'text-center user-meta-col'],
    ['targets' => 4, 'className' => 'none'],
    ['targets' => 5, 'orderable' => false, 'searchable' => false, 'className' => 'text-center user-action-col'],
  ],
];
@endphp

<main class="main-wrapper user-list-page">
  <div class="main-content">

    <div class="user-page-header">
      <h5 class="user-page-title">
        <span class="material-icons-outlined">group</span>
        {{ page_title() }}
      </h5>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary user-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm mới
      </a>
    </div>

    <div class="user-table-card">
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
    </div>

  </div>
</main>
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