@extends('index')
@section('title', 'Vai Trò')

@section('content')
@include('partials.css.role')

@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

$codeRender = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

return data
  ? '<span class="role-code-badge">' + esc(data) + '</span>'
  : '<span class="text-muted">—</span>';
JS;

$actionsRenderByKey = <<<JS
if (type !== 'display') return '';

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
  <div class="role-action-icons">
    <a href="\${esc(editUrl)}"
       class="role-action-icon role-action-edit"
       title="Chỉnh sửa">
      <span class="material-icons-outlined">edit</span>
    </a>

    <a href="javascript:void(0)"
       class="role-action-icon role-action-delete btn-delete"
       data-id="\${esc(id)}"
       data-name="\${esc(name)}"
       data-url="\${esc(deleteUrl)}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal"
       title="Xóa">
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
    'code' => $codeRender,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-start role-name-col'],
    ['targets' => 1, 'className' => 'text-start role-code-col'],
    ['targets' => 2, 'className' => 'text-start role-date-col'],
    ['targets' => 3, 'className' => 'none'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'text-center role-action-col'],
  ],
];
@endphp

<main class="main-wrapper role-list-page">
  <div class="main-content">

    <div class="role-page-header">
      <h5 class="role-page-title">
        <span class="material-icons-outlined">how_to_reg</span>
        Vai Trò
      </h5>

      <a href="{{ panel_route('role.create') }}" class="btn btn-primary role-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm mới
      </a>
    </div>

    <div class="role-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key'=>'name','title'=>'TÊN'],
          ['key'=>'code','title'=>'MÃ'],
          ['key'=>'created_at','title'=>'NGÀY TẠO'],
          ['key'=>'__details','title'=>'','class'=>'none'],
          ['key'=>'actions','title'=>'HÀNH ĐỘNG']
        ]"
        ajax-url="{{ panel_route('role.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection