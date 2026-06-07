@extends('index')
@section('title', 'Quản lý Lịch hẹn')

@section('content')
@include('partials.css.appointment')

@php
$module = module();

$editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

$actionsRenderByKey = <<<JS
  if (type !== 'display') return '';

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const id = row.id ?? '';
  const name = row.name ?? '';

  const editUrl = id ? {$editRouteTplJson}.replace('__ID__', id) : 'javascript:void(0)';
  const deleteUrl = id ? {$deleteRouteTplJson}.replace('__ID__', id) : 'javascript:void(0)';

  return '' +
    '<div class="appointment-action-icons">' +
      '<a href="' + esc(editUrl) + '" class="appointment-action-icon appointment-action-edit" title="Chỉnh sửa">' +
        '<i class="icon-base ti tabler-pencil"></i>' +
      '</a>' +

      '<a href="javascript:void(0)" class="appointment-action-icon appointment-action-delete btn-delete" ' +
        'data-id="' + esc(id) + '" ' +
        'data-name="' + esc(name) + '" ' +
        'data-url="' + esc(deleteUrl) + '" ' +
        'data-bs-toggle="modal" ' +
        'data-bs-target="#deleteModal" ' +
        'title="Xóa">' +
        '<i class="icon-base ti tabler-trash"></i>' +
      '</a>' +
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
    ? '<div><i class="ti tabler-phone ti-xs me-1"></i>' + esc(row.phone) + '</div>'
    : '<div>—</div>';

  const email = row.email
    ? '<div class="small"><i class="ti tabler-mail ti-xs me-1"></i>' + esc(row.email) + '</div>'
    : '';

  return '<div class="appointment-contact">' + phone + email + '</div>';
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
    ? '<span class="appointment-message-text" title="' + esc(fullText) + '">' + esc(text) + '</span>'
    : '<span class="text-muted">—</span>';
JS;

$createdAtRenderByKey = <<<'JS'
  if (type !== 'display') return data;

  if (!data) return '—';

  const date = new Date(data);
  const d = date.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });

  const t = date.toLocaleTimeString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit'
  });

  return '' +
    '<div class="appointment-date">' +
      '<strong>' + d + '</strong>' +
      '<small>' + t + '</small>' +
    '</div>';
JS;

$options = [
  'control' => false,
  'order' => [],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'searchPlaceholder' => 'Tìm tên, số điện thoại...',

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
    'status' => $statusRenderByKey,
    'message' => $messageRenderByKey,
    'created_at' => $createdAtRenderByKey,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-center appointment-index-col'],
    ['targets' => 1, 'className' => 'text-start appointment-customer-col'],
    ['targets' => 2, 'className' => 'text-start appointment-contact-col'],
    ['targets' => 3, 'className' => 'text-start appointment-service-col'],
    ['targets' => 4, 'className' => 'text-center text-nowrap appointment-status-col'],
    ['targets' => 5, 'className' => 'text-start appointment-message-col'],
    ['targets' => 6, 'className' => 'text-center text-nowrap appointment-date-col'],
    ['targets' => 7, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap appointment-action-col'],
  ],
];
@endphp

<main class="main-wrapper appointment-list-page">
  <div class="main-content">

    <div class="appointment-page-header">
      <h5 class="appointment-page-title">
        <span class="material-icons-outlined">calendar_month</span>
        Đặt Lịch Hẹn
      </h5>
    </div>

    <div class="appointment-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key' => 'DT_RowIndex', 'title' => 'STT'],
          ['key' => 'name', 'title' => 'KHÁCH HÀNG'],
          ['key' => 'contact', 'title' => 'LIÊN HỆ'],
          ['key' => 'service', 'title' => 'DỊCH VỤ'],
          ['key' => 'status', 'title' => 'TRẠNG THÁI'],
          ['key' => 'message', 'title' => 'TIN NHẮN'],
          ['key' => 'created_at', 'title' => 'NGÀY ĐĂNG KÝ'],
          ['key' => 'actions', 'title' => 'HÀNH ĐỘNG'],
        ]"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection