@extends('index')
@section('title', 'Quản lý Bác sĩ')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

$imageRenderByKey = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const buildImageUrl = (raw) => {
  const value = String(raw || '').trim();

  if (!value) return '';

  if (/^https?:\/\//i.test(value)) {
    return value;
  }

  if (value.startsWith('/storage/')) {
    return window.location.origin + value;
  }

  if (value.startsWith('storage/')) {
    return window.location.origin + '/' + value;
  }

  if (value.startsWith('/uploads/')) {
    return window.location.origin + '/storage' + value;
  }

  if (value.startsWith('uploads/')) {
    return window.location.origin + '/storage/' + value;
  }

  return window.location.origin + '/' + value.replace(/^\/+/, '');
};

const src = buildImageUrl(data || row?.image || row?.image_url || '');

if (!src) {
  return '<span class="text-muted">—</span>';
}

return '' +
  '<img ' +
    'src="' + esc(src) + '" ' +
    'alt="' + esc(row?.name || 'Doctor') + '" ' +
    'class="doctor-avatar" ' +
    'loading="lazy" ' +
    'onerror="this.style.display=\'none\'; this.insertAdjacentHTML(\'afterend\', \'<span class=&quot;text-muted&quot;>—</span>\');"' +
  '>';
JS;

$socialsRenderByKey = <<<'JS'
if (type !== 'display') return data;

if (!data || typeof data !== 'object') {
  return '<span class="text-muted">—</span>';
}

const icons = {
  linkedin: 'tabler-brand-linkedin',
  facebook: 'tabler-brand-facebook',
  twitter: 'tabler-brand-twitter',
  youtube: 'tabler-brand-youtube'
};

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const items = Object.keys(icons)
  .filter((key) => data[key])
  .map((key) => {
    return '' +
      '<a href="' + esc(data[key]) + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="doctor-social-link js-doctor-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="' + esc(key) + '">' +
        '<i class="icon-base ti ' + icons[key] + '"></i>' +
      '</a>';
  });

return items.length
  ? '<div class="doctor-socials">' + items.join('') + '</div>'
  : '<span class="text-muted">—</span>';
JS;

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
  '<div class="doctor-meta-popover">' +
    '<div class="doctor-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="doctor-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return '' +
  '<button type="button" ' +
    'class="btn p-0 border-0 bg-transparent doctor-meta-trigger" ' +
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

const esc = (value) => String(value ?? "")
  .replace(/&/g, "&amp;")
  .replace(/</g, "&lt;")
  .replace(/>/g, "&gt;")
  .replace(/"/g, "&quot;");

return '' +
  '<div class="doctor-action-icons">' +
    '<a href="' + esc(editUrl) + '" ' +
       'class="doctor-action-icon doctor-action-edit js-doctor-tooltip" ' +
       'data-bs-toggle="tooltip" ' +
       'data-bs-placement="top" ' +
       'title="Chỉnh sửa">' +
      '<span class="material-icons-outlined">edit</span>' +
    '</a>' +

    '<a href="javascript:void(0)" ' +
       'class="doctor-action-icon doctor-action-delete btn-delete js-doctor-tooltip" ' +
       'data-id="' + esc(id) + '" ' +
       'data-name="' + esc(name) + '" ' +
       'data-url="' + esc(deleteUrl) + '" ' +
       'data-bs-toggle="modal" ' +
       'data-bs-target="#deleteModal" ' +
       'data-bs-placement="top" ' +
       'title="Xóa">' +
      '<span class="material-icons-outlined">delete</span>' +
    '</a>' +
  '</div>';
JS;

$options = [
  'control' => false,
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'pageLength' => 10,
  'pagingType' => 'full_numbers',
  'order' => [[1, 'asc']],
  'searchPlaceholder' => 'Tìm kiếm bác sĩ...',

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
    'image' => $imageRenderByKey,
    'socials' => $socialsRenderByKey,
    'creator' => $metaRenderByKey,
    'actions' => $actionsRenderByKey,
],

  'columnDefs' => [
    ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap doctor-image-col'],
    ['targets' => 1, 'className' => 'text-start doctor-name-col'],
    ['targets' => 2, 'className' => 'text-start doctor-specialty-col'],
    ['targets' => 3, 'className' => 'text-center text-nowrap doctor-birth-col'],
    ['targets' => 4, 'className' => 'text-start text-nowrap doctor-phone-col'],
    ['targets' => 5, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap doctor-social-col'],
    ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap doctor-meta-col'],
    ['targets' => 7, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap doctor-action-col'],
  ],
];
@endphp

@include('partials.css.doctor')

<main class="main-wrapper doctor-list-page">
  <div class="main-content">
    <div class="doctor-page-header">
      <h5 class="doctor-page-title">
        <span class="material-icons-outlined">medical_services</span>
        Danh sách Nhân Viên
      </h5>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary doctor-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="doctor-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key' => 'image', 'title' => 'Ảnh'],
          ['key' => 'name', 'title' => 'Tên nhân viên'],
          ['key' => 'specialty', 'title' => 'Chuyên ngành'],
          ['key' => 'birth_year', 'title' => 'Năm sinh'],
          ['key' => 'phone', 'title' => 'Số điện thoại'],
          ['key' => 'socials', 'title' => 'Mạng xã hội'],
          ['key' => 'creator', 'title' => ''],
        ['key' => 'actions', 'title' => 'Hành động'],
        ]"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>
  </div>
</main>
@endsection

@include('partials.js.doctor')