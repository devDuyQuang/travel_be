@extends('index')
@section('title', 'Quản lý Lịch hẹn')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteTextJson = json_encode('Xoá', JSON_UNESCAPED_UNICODE);

// Render cột thao tác
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
  <div class="appointment-action-icons">
    <a href="\${esc(editUrl)}"
       class="appointment-action-icon appointment-action-edit"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="appointment-action-icon appointment-action-delete btn-delete"
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

// Render Badge trạng thái (Khớp với status_label từ Controller)
$statusRenderByKey = <<<JS
return row.status_label || '<span class="badge bg-label-secondary">Không xác định</span>';
JS;

// Render thông tin liên hệ (Email & Phone)
$contactRenderByKey = <<<JS
const email = row.email ? `<div class="small text-muted"><i class="ti tabler-mail ti-xs me-1"></i>\${row.email}</div>` : '';
const phone = `<div><i class="ti tabler-phone ti-xs me-1"></i>\${row.phone}</div>`;
return `\${phone}\${email}`;
JS;

// Render ngày tạo
$createdAtRenderByKey = <<<JS
if (!data) return '';
const date = new Date(data);
const d = date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
const t = date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
return `<div class="d-flex flex-column"><span class="fw-bold">\${d}</span><small class="text-muted">\${t}</small></div>`;
JS;

$options = [
    'control' => false,
    'order' => [],
    'rendersByKey' => [
        'contact' => $contactRenderByKey,
        'status' => $statusRenderByKey,
        'created_at' => $createdAtRenderByKey,
        'actions' => $actionsRenderByKey,
    ],
    'columnDefs' => [
        ['targets' => 0, 'orderable' => false, 'searchable' => false],
        ['targets' => 4, 'className' => 'none'], // ẩn status
        ['targets' => 5, 'className' => 'none'], // Ẩn message vào row details
       [
    'targets' => 7,
    'orderable' => false,
    'searchable' => false,
    'className' => 'text-center'
],
    ],
    'responsiveModal' => true,
    'responsiveHeaderField' => 'name',
    'modalFields' => ['name', 'phone', 'email', 'service', 'status_label', 'message', 'created_at'],
    'modalRenders' => [
        'created_at' => $createdAtRenderByKey,
        'message' => "return `<div class='p-2 bg-light rounded'>\${data || 'Không có ghi chú'}</div>`;",
    ],
    'searchPlaceholder' => 'Tìm tên, số điện thoại...',
];
@endphp

<x-table-header
:title="'Đặt Lịch Khám'"
icon="tabler-calendar-time"
{{-- :create-route="panel_route(module().'.create')"  --}}
/>

{{-- Stats Overview (Tận dụng biến stats truyền từ Controller) --}}
{{-- <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Tổng lịch hẹn</span>
                        <h4 class="mb-0 mt-2">{{ $stats['total'] }}</h4>
                    </div>
                    <span class="badge bg-label-primary rounded p-2">
                        <i class="ti tabler-users ti-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Chờ xử lý</span>
                        <h4 class="mb-0 mt-2 text-warning">{{ $stats['pending'] }}</h4>
                    </div>
                    <span class="badge bg-label-warning rounded p-2">
                        <i class="ti tabler-alert-circle ti-sm"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<x-data-table
    id="reload-table"
    :columns="[
        ['key' => 'DT_RowIndex', 'title' => 'STT', 'class' => 'text-center'],
        ['key' => 'name', 'title' => 'Khách hàng'],
        ['key' => 'contact', 'title' => 'Liên hệ'],
        ['key' => 'service', 'title' => 'Dịch vụ'],
        ['key' => 'status', 'title' => 'Trạng thái'],
        ['key' => 'message', 'title' => 'Tin nhắn', 'class' => 'none'],
        ['key' => 'created_at', 'title' => 'Ngày đăng ký'],
       ['key' => 'actions', 'title' => 'Hành động']
    ]"
    ajax-url="{{ panel_route(module().'.datatable') }}"
    :options="$options"
></x-data-table>
<style>
.appointment-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
}

.appointment-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  line-height: 1;
}

.appointment-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.appointment-action-edit {
  color: #00cfe8 !important;
}

.appointment-action-delete {
  color: #ea5455 !important;
}

.appointment-action-icon:hover {
  opacity: 0.9;
}
</style>
@endsection
