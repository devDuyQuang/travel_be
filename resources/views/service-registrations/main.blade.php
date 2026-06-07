@extends('index')
@section('title', 'Quản lý Đăng ký Gói Dịch vụ')

@section('content')
@include('partials.css.service-registrations')

@php
$module = module();

$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteTextJson = json_encode('Xoá', JSON_UNESCAPED_UNICODE);

$actionsRenderByKey = <<<JS
  if (type !== 'display') return '';

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const id = row.id ?? '';
  const name = row.full_name ?? '';
  const deleteUrl = id ? {$deleteRouteTplJson}.replace('__ID__', id) : 'javascript:void(0)';
  const deleteText = {$deleteTextJson};

  return '' +
    '<div class="service-registration-action-wrap">' +
      '<div class="dropdown">' +
        '<button type="button" class="service-registration-action-btn dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">' +
          '<span class="material-icons-outlined">more_vert</span>' +
        '</button>' +

        '<div class="dropdown-menu dropdown-menu-end">' +
          '<a class="dropdown-item btn-delete text-danger" href="javascript:void(0)" ' +
            'data-id="' + esc(id) + '" ' +
            'data-name="' + esc(name) + '" ' +
            'data-url="' + esc(deleteUrl) + '" ' +
            'data-bs-toggle="modal" ' +
            'data-bs-target="#deleteModal">' +
            '<span class="material-icons-outlined me-2" style="font-size:18px;">delete</span>' +
            deleteText +
          '</a>' +
        '</div>' +
      '</div>' +
    '</div>';
JS;

$statusRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  return row.status_label || '<span class="badge bg-label-secondary">Không xác định</span>';
JS;

$contactRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const phone = row.phone
    ? '<div><span class="material-icons-outlined me-1" style="font-size:16px;vertical-align:middle;">call</span>' + esc(row.phone) + '</div>'
    : '<div>—</div>';

  const email = row.email
    ? '<div class="small"><span class="material-icons-outlined me-1" style="font-size:16px;vertical-align:middle;">mail</span>' + esc(row.email) + '</div>'
    : '';

  return '<div class="service-registration-contact">' + phone + email + '</div>';
JS;

$packageRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const name = row.package_name
    ? '<div class="service-registration-package-name">' + esc(row.package_name) + '</div>'
    : '<div>—</div>';

  const price = row.package_price
    ? '<div class="service-registration-package-price">' + esc(row.package_price) + '</div>'
    : '';

  return name + price;
JS;

$messageRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const fullText = String(data || row.message || '');
  const limit = 70;
  const text = fullText.length > limit ? fullText.substring(0, limit) + '...' : fullText;

  return fullText
    ? '<span class="service-registration-message-text" title="' + esc(fullText) + '">' + esc(text) + '</span>'
    : '<span class="text-muted">—</span>';
JS;

$createdAtRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  if (!data) return '—';

  const parts = String(data).split(' ');

  return '' +
    '<div class="service-registration-date">' +
      '<strong>' + (parts[0] || '') + '</strong>' +
      '<small>' + (parts[1] || '') + '</small>' +
    '</div>';
JS;

$options = [
  'control' => false,
  'order' => [[5, 'desc']],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'searchPlaceholder' => 'Tìm tên, SĐT, gói...',

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
    'contact' => $contactRenderByKey,
    'package_info' => $packageRenderByKey,
    'status' => $statusRenderByKey,
    'message' => $messageRenderByKey,
    'created_at' => $createdAtRenderByKey,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-start service-registration-customer-col'],
    ['targets' => 1, 'className' => 'text-start service-registration-contact-col'],
    ['targets' => 2, 'className' => 'text-start service-registration-package-col'],
    ['targets' => 3, 'className' => 'text-center text-nowrap service-registration-status-col'],
    ['targets' => 4, 'className' => 'text-start service-registration-message-col'],
    ['targets' => 5, 'className' => 'text-center text-nowrap service-registration-date-col'],
    ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap service-registration-action-col'],
  ],
];
@endphp

<main class="main-wrapper service-registration-list-page">
  <div class="main-content">

    <div class="service-registration-page-header">
      <h5 class="service-registration-page-title">
        <span class="material-icons-outlined">inventory_2</span>
        Danh Sách Đăng Ký Gói
      </h5>
    </div>

    <div class="service-registration-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key' => 'full_name', 'title' => 'KHÁCH HÀNG'],
          ['key' => 'contact', 'title' => 'LIÊN HỆ'],
          ['key' => 'package_info', 'title' => 'GÓI DỊCH VỤ'],
          ['key' => 'status', 'title' => 'TRẠNG THÁI'],
          ['key' => 'message', 'title' => 'LỜI NHẮN'],
          ['key' => 'created_at', 'title' => 'NGÀY ĐĂNG KÝ'],
          ['key' => 'actions', 'title' => 'THAO TÁC'],
        ]"
        ajax-url="{{ panel_route($module.'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection