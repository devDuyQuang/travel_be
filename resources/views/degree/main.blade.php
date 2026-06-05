@extends('index')
@section('title', 'Quản lý Bằng cấp')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);

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

  if (/^https?:\/\//i.test(value)) return value;

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
    'alt="' + esc(row?.name || 'Degree') + '" ' +
    'class="degree-image" ' +
    'loading="lazy" ' +
    'onerror="this.style.display=\'none\'; this.insertAdjacentHTML(\'afterend\', \'<span class=&quot;text-muted&quot;>—</span>\');"' +
  '>';
JS;

$descriptionRenderByKey = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const text = esc(data || '—');

return '<span class="degree-description-text">' + text + '</span>';
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
  '<div class="degree-meta-popover">' +
    '<div class="degree-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="degree-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return '' +
  '<button type="button" ' +
    'class="btn p-0 border-0 bg-transparent degree-meta-trigger" ' +
    'data-bs-toggle="popover" ' +
    'data-bs-placement="left" ' +
    'data-bs-html="true" ' +
    'data-bs-trigger="hover focus" ' +
    'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
    '<span class="material-icons-outlined">info</span>' +
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
  '<div class="degree-action-icons">' +
    '<a href="' + esc(editUrl) + '" ' +
       'class="degree-action-icon degree-action-edit js-degree-tooltip" ' +
       'data-bs-toggle="tooltip" ' +
       'data-bs-placement="top" ' +
       'title="Chỉnh sửa">' +
      '<span class="material-icons-outlined">edit</span>' +
    '</a>' +

    '<a href="javascript:void(0)" ' +
       'class="degree-action-icon degree-action-delete btn-delete js-degree-tooltip" ' +
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
  'order' => [[0, 'desc']],
  'searchPlaceholder' => 'Tìm kiếm bằng cấp...',

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
    'description' => $descriptionRenderByKey,
    'creator' => $metaRenderByKey,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'className' => 'text-start degree-name-col'],
    ['targets' => 1, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap degree-image-col'],
    ['targets' => 2, 'className' => 'text-start degree-description-col'],
    ['targets' => 3, 'className' => 'text-start degree-link-text-col'],
    ['targets' => 4, 'className' => 'text-center text-nowrap degree-year-col'],
    ['targets' => 5, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap degree-meta-col'],
    ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap degree-action-col'],
  ],
];
@endphp

@include('partials.css.degree')

<main class="main-wrapper degree-list-page">
  <div class="main-content">
    <div class="degree-page-header">
      <h5 class="degree-page-title">
        <span class="material-icons-outlined">workspace_premium</span>
        Danh sách Bằng cấp
      </h5>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary degree-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="degree-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key'=>'name', 'title'=>'Tên bằng cấp'],
          ['key'=>'image', 'title'=>'Hình ảnh'],
          ['key'=>'description', 'title'=>'Mô tả'],
          ['key'=>'link_text', 'title'=>'Text liên kết'],
          ['key'=>'year', 'title'=>'Năm'],
          ['key'=>'creator', 'title'=>'Thông tin'],
          ['key'=>'actions', 'title'=>'Hành động'],
        ]"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>
  </div>
</main>
@endsection

@include('partials.js.degree')