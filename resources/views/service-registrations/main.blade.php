@extends('index')
@section('title', 'Quản lý Đăng ký Gói Dịch vụ')

@section('content')
@php
    $module = module();

    $editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
    $deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);

    $editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
    $deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
    $deleteTextJson = json_encode('Xoá', JSON_UNESCAPED_UNICODE);

    // 1. Render cột thao tác
    $actionsRenderByKey = <<<JS
        const id = row.id ?? "";
        const name = row.full_name ?? "";
        const editUrl = id ? {$editRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";
        const deleteUrl = id ? {$deleteRouteTplJson}.replace("__ID__", id) : "javascript:void(0)";
        const deleteText = {$deleteTextJson};
        return `
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item btn-delete text-danger"
                href="javascript:void(0)"
                data-id="\${id}"
                data-name="\${name}"
                data-url="\${deleteUrl}"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal">
                <i class="icon-base ti tabler-trash me-2"></i> \${deleteText}
              </a>
            </div>
          </div>
        `;
JS;

    // 2. Render Badge trạng thái (Sử dụng trực tiếp status_label từ Controller)
    $statusRenderByKey = <<<JS
        return row.status_label || '<span class="badge bg-label-secondary">Không xác định</span>';
JS;

    // 3. Render thông tin liên hệ (Email & Phone)
    $contactRenderByKey = <<<JS
        const email = row.email ? `<div class="small text-muted"><i class="ti tabler-mail ti-xs me-1"></i>\${row.email}</div>` : '';
        const phone = `<div><i class="ti tabler-phone ti-xs me-1"></i>\${row.phone}</div>`;
        return `\${phone}\${email}`;
JS;

    // 4. Render thông tin Gói (Tên gói & Giá)
    $packageRenderByKey = <<<JS
        const price = row.package_price ? `<div class="badge bg-label-primary mt-1">\${row.package_price}</div>` : '';
        return `<div class="fw-bold text-primary">\${row.package_name}</div>\${price}`;
JS;

    // 5. Render ngày tạo (Format d/m/Y H:i)
    $createdAtRenderByKey = <<<JS
        if (!data) return '';
        // Vì Controller đã format 'd/m/Y H:i', ta tách chuỗi để hiển thị đẹp hơn
        const parts = data.split(' ');
        return `<div class="d-flex flex-column"><span class="fw-bold">\${parts[0]}</span><small class="text-muted">\${parts[1] || ''}</small></div>`;
JS;

    $options = [
        'control' => true,
        'order' => [[5, 'desc']], // Sắp xếp theo ngày tạo (cột số 5)
        'rendersByKey' => [
            'contact' => $contactRenderByKey,
            'package_info' => $packageRenderByKey,
            'status' => $statusRenderByKey,
            'created_at' => $createdAtRenderByKey,
            'actions' => $actionsRenderByKey,
        ],
        'columnDefs' => [
            ['targets' => 4, 'className' => 'none'], // Ẩn lời nhắn (message) vào details
            ['targets' => -1, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center'],
        ],
        'responsiveModal' => true,
        'responsiveHeaderField' => 'full_name',
        'modalFields' => ['full_name', 'phone', 'email', 'package_name', 'package_price', 'status_label', 'message', 'created_at'],
        'modalRenders' => [
            'created_at' => $createdAtRenderByKey,
            'message' => "return `<div class='p-2 bg-light rounded'>\${data || 'Không có ghi chú'}</div>`;",
        ],
        'searchPlaceholder' => 'Tìm tên, SĐT, gói...',
    ];
@endphp

<x-table-header
    :title="'Danh Sách Đăng Ký Gói'"
    icon="tabler-package"
/>

<x-data-table
    id="reload-table"
    :columns="[
        ['key' => 'full_name',    'title' => 'Khách hàng'],
        ['key' => 'contact',      'title' => 'Liên hệ'],
        ['key' => 'package_info', 'title' => 'Gói dịch vụ'],
        ['key' => 'status',       'title' => 'Trạng thái'],
        ['key' => 'message',      'title' => 'Lời nhắn', 'class' => 'none'],
        ['key' => 'created_at',   'title' => 'Ngày đăng ký'],
        ['key' => 'actions',      'title' => '']
    ]"
    ajax-url="{{ panel_route($module.'.datatable') }}"
    :options="$options"
></x-data-table>

@endsection
