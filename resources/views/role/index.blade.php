@extends('index')
@section('title', 'Vai Trò')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteTextJson = json_encode('Xoá', JSON_UNESCAPED_UNICODE);

$codeRender = <<<'JS'
    if (type !=='display' ) return data;
    return data ? '<span class="badge bg-label-primary">' + data + '</span>' : '<span class="text-muted">—</span>' ;
    JS;

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
  <div class="role-action-icons">
    <a href="\${esc(editUrl)}"
       class="role-action-icon role-action-edit"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="role-action-icon role-action-delete btn-delete"
       data-id="\${esc(id)}"
       data-name="\${esc(name)}"
       data-url="\${esc(deleteUrl)}"
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
    'order' => [[0, 'asc']],
    'rendersByKey' => [
    'code' => $codeRender,
    'actions' => $actionsRenderByKey,
    ],
    'columnDefs' => [
    ['targets' => 3, 'className' => 'none'],
    ['targets' => -1, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center'],
    ],
    'responsiveModal' => true,
    'responsiveHeaderField' => 'name',
    'modalFields' => ['name', 'code', 'created_at'],
    'modalRenders' => [
    'code' => $codeRender,
    ],
    'searchPlaceholder' => 'Nhập từ khóa...',
    ];
    @endphp

    <x-table-header :title="'Vai Trò'" icon="tabler-user-check" :create-route="panel_route('role.create')" />

    <x-data-table
        id="reload-table"
        :columns="[
    ['key'=>'name','title'=>'TÊN'],
    ['key'=>'code','title'=>'MÃ'],
    ['key'=>'created_at','title'=>'Ngày tạo'],
    ['key'=>'__details','title'=>'','class'=>'none'],
    ['key'=>'actions','title'=>'Hành Động']
  ]"
        ajax-url="{{ panel_route('role.datatable') }}"
        :options="$options" />


        <style>
.role-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
}

.role-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  line-height: 1;
}

.role-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.role-action-edit {
  color: #00cfe8 !important;
}

.role-action-delete {
  color: #ea5455 !important;
}

.role-action-icon:hover {
  opacity: 0.9;
}
</style>
    @endsection
