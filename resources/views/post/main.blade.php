@extends('index')
@section('title', 'Bài Viết')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);
$toggleStatusTpl = panel_route($module.'.toggle-status', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$toggleStatusTplJson = json_encode($toggleStatusTpl, JSON_UNESCAPED_SLASHES);

$indexRender = <<<'JS'
  return meta.row + meta.settings._iDisplayStart + 1;
JS;

$categoriesRender = <<<'JS'
  if (type !== 'display') return data;

  const list = Array.isArray(row?.categories) ? row.categories : [];

  if (!list.length) {
    return '<span class="text-muted">—</span>';
  }

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  return list.map(function(name) {
    return '<span class="post-category-badge">' + esc(name) + '</span>';
  }).join('');
JS;

$nameRender = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const buildPublicUrl = (rawPath) => {
    const value = String(rawPath || '').trim();

    if (!value) return '';
    if (/^https?:\/\//i.test(value)) return value;

    const protocol = window.location.protocol;
    let host = window.location.hostname.replace(/^admin\./i, '');

    if (host === 'localhost') {
      host = 'localhost:3000';
    }

    return protocol + '//' + host + '/' + value.replace(/^\/+/, '');
  };

  const name = esc(row?.name || '—');
  const href = buildPublicUrl(row?.public_url || '');

  const createdAt = esc(row?.created_at || '—');
  const creator = esc(row?.creator_name || row?.creator || '—');
  const updatedAt = esc(row?.updated_at || '—');
  const updater = esc(row?.updater_name || row?.updater || '—');

  const popoverContent =
    '<div class="post-meta-popover">' +
      '<div class="post-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
      '<div class="post-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
      '<hr class="my-2">' +
      '<div class="post-meta-row"><strong>Cập nhật cuối:</strong> <span>' + updatedAt + '</span></div>' +
      '<div class="post-meta-row"><strong>Người cập nhật:</strong> <span>' + updater + '</span></div>' +
    '</div>';

  let iconsHtml = '';

  if (href) {
    iconsHtml +=
      '<a href="' + href.replace(/"/g, '&quot;') + '" ' +
        'target="_blank" ' +
        'rel="noopener" ' +
        'class="post-name-icon post-name-icon-link js-post-tooltip" ' +
        'data-bs-toggle="tooltip" ' +
        'data-bs-placement="top" ' +
        'title="Mở đường dẫn">' +
        '<span class="material-icons-outlined">open_in_new</span>' +
      '</a>';
  }

  iconsHtml +=
    '<button type="button" ' +
      'class="btn p-0 border-0 bg-transparent post-name-icon post-name-icon-info post-meta-trigger" ' +
      'data-bs-toggle="popover" ' +
      'data-bs-placement="left" ' +
      'data-bs-html="true" ' +
      'data-bs-trigger="hover focus" ' +
      'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
      '<span class="material-icons-outlined">info</span>' +
    '</button>';

  return '' +
    '<div class="post-name-with-icons">' +
      '<span class="post-name-text">' + name + '</span>' +
      '<span class="post-name-icons">' + iconsHtml + '</span>' +
    '</div>';
JS;

$statusRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.status, 10) === 1;
  const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';

  return '' +
    '<div class="form-check form-switch form-check-inline mb-0">' +
      '<input type="checkbox" class="form-check-input post-status-toggle status-toggle-wide" ' +
        'data-id="' + id + '" ' +
        'data-url="' + toggleUrl.replace(/"/g, '&quot;') + '" ' +
        (on ? 'checked' : '') +
      '>' +
    '</div>';
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

  return '' +
    '<div class="post-action-icons">' +
      '<a href="' + escAction(editUrl) + '" ' +
         'class="post-action-icon post-action-edit js-post-tooltip" ' +
         'data-bs-toggle="tooltip" ' +
         'data-bs-placement="top" ' +
         'title="Chỉnh sửa">' +
        '<span class="material-icons-outlined">edit</span>' +
      '</a>' +

      '<a href="javascript:void(0)" ' +
         'class="post-action-icon post-action-delete btn-delete js-post-tooltip" ' +
         'data-id="' + escAction(id) + '" ' +
         'data-name="' + escAction(name) + '" ' +
         'data-url="' + escAction(deleteUrl) + '" ' +
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
  'ordering' => false,
  'order' => [],
  'responsive' => false,
  'responsiveModal' => false,
  'autoWidth' => false,
  'scrollX' => false,
  'pageLength' => 10,
  'pagingType' => 'full_numbers',
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
    'index' => $indexRender,
    'name' => $nameRender,
    'categories' => $categoriesRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
    ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap post-index-col'],
    ['targets' => 1, 'className' => 'text-start post-name-col'],
    ['targets' => 2, 'className' => 'text-start post-category-col'],
    ['targets' => 3, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap post-status-col'],
    ['targets' => 4, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap post-action-col'],
  ],
];
@endphp

@include('partials.css.post')

<main class="main-wrapper post-list-page">
  <div class="main-content">

    <div class="post-page-header">
      <div>
        <h5 class="post-page-title">
          <span class="material-icons-outlined">article</span>
         Bài Viết
        </h5>
      </div>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary post-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="post-filter-row">
      <select id="post-filter-category" class="form-select">
        <option value="">Tất cả danh mục</option>
        @foreach($filterCategories as $category)
          <option value="{{ $category->id }}">
            {{ $category->name }} ({{ $category->posts_count }} mục)
          </option>
        @endforeach
      </select>

      <select id="post-filter-creator" class="form-select">
        <option value="">Tất cả người tạo</option>
        @foreach($filterCreators as $creator)
          <option value="{{ $creator->id }}">
            {{ $creator->name }} ({{ $creator->posts_count }} mục)
          </option>
        @endforeach
      </select>
    </div>

    <div class="post-table-card">
      <x-data-table
        id="reload-table"
        :columns="[
          ['key' => 'index', 'title' => 'STT'],
          ['key' => 'name', 'title' => 'TÊN DỊCH VỤ'],
          ['key' => 'categories', 'title' => 'DANH MỤC'],
          ['key' => 'status', 'title' => 'TRẠNG THÁI'],
          ['key' => 'actions', 'title' => 'HÀNH ĐỘNG']
        ]"
        ajax-url="{{ panel_route(module().'.datatable') }}"
        :options="$options"
      />
    </div>

  </div>
</main>
@endsection

@include('partials.js.post')