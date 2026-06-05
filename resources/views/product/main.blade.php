@extends('index')
@section('title', 'Sản Phẩm')

@section('content')
@php

$module = module();

$editRouteTpl = panel_route($module.'.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module.'.destroy', ['id' => '__ID__']);
$toggleStatusTpl = panel_route($module.'.toggle-status', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$toggleStatusTplJson = json_encode($toggleStatusTpl, JSON_UNESCAPED_SLASHES);

$priceRender = <<<'JS'
if (type !== 'display') return data;

if (data === null || data === undefined || data === '') {
  return '<span class="text-muted">—</span>';
}

const value = Number(data);

if (Number.isNaN(value)) {
  return '<span class="text-muted">—</span>';
}

return '<span class="product-price">' + value.toLocaleString('vi-VN') + '</span>';
JS;

$priceDiscountRender = <<<'JS'
if (type !== 'display') return data;

if (data === null || data === undefined || data === '') {
  return '<span class="text-muted">—</span>';
}

const value = Number(data);

if (Number.isNaN(value)) {
  return '<span class="text-muted">—</span>';
}

return '<span class="product-price-discount">' + value.toLocaleString('vi-VN') + '</span>';
JS;

$indexRender = <<<'JS'
  return meta.row + meta.settings._iDisplayStart + 1;
JS;

$imageRender = <<<'JS'
  if (type !== 'display') return data;

  if (!data) {
    return '<span class="text-muted">—</span>';
  }

  return '<img src="' + String(data).replace(/"/g, '&quot;') + '" class="product-thumb" alt="Product">';
JS;

$nameRender = <<<'JS'
  if (type !== 'display') return data;

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  const name = esc(row?.name || '—');
  const slug = esc(row?.slug || '—');
  const createdAt = row?.created_at ? esc(new Date(row.created_at).toLocaleString('vi-VN')) : '—';
  const creator = esc(row?.creator || row?.creator_name || '—');

  const popoverContent =
    '<div class="product-meta-popover">' +
      '<div class="product-meta-row"><strong>Slug:</strong> <span>' + slug + '</span></div>' +
      '<hr class="my-2">' +
      '<div class="product-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
      '<div class="product-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
    '</div>';

  return '' +
    '<div class="product-name-with-icons">' +
      '<span class="product-name-text">' + name + '</span>' +
      '<span class="product-name-icons">' +
        '<button type="button" ' +
          'class="btn p-0 border-0 bg-transparent product-name-icon product-name-icon-info product-meta-trigger" ' +
          'data-bs-toggle="popover" ' +
          'data-bs-placement="left" ' +
          'data-bs-html="true" ' +
          'data-bs-trigger="hover focus" ' +
          'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
          '<span class="material-icons-outlined">info</span>' +
        '</button>' +
      '</span>' +
    '</div>';
JS;

$priceRender = <<<'JS'
  if (type !== 'display') return data;

  if (data === null || data === undefined || data === '') {
    return '<span class="text-muted">—</span>';
  }

  const value = Number(data);

  if (Number.isNaN(value)) {
    return '<span class="text-muted">—</span>';
  }

  return '<span class="product-price">' + value.toLocaleString('vi-VN') + '</span>';
JS;

$categoryRender = <<<'JS'
  if (type !== 'display') return data;

  if (!data || data === '—') {
    return '<span class="text-muted">—</span>';
  }

  const esc = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

  return '<span class="product-category-badge">' + esc(data) + '</span>';
JS;

$statusRender = <<<JS
  if (type !== 'display') return data;

  const id = row.id;
  const on = parseInt(row.status, 10) === 1;
  const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';

  return '' +
    '<div class="form-check form-switch form-check-inline mb-0">' +
      '<input type="checkbox" class="form-check-input product-status-toggle status-toggle-wide" ' +
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
    '<div class="product-action-icons">' +
      '<a href="' + escAction(editUrl) + '" ' +
         'class="product-action-icon product-action-edit js-product-tooltip" ' +
         'data-bs-toggle="tooltip" ' +
         'data-bs-placement="top" ' +
         'title="Chỉnh sửa">' +
        '<span class="material-icons-outlined">edit</span>' +
      '</a>' +

      '<a href="javascript:void(0)" ' +
         'class="product-action-icon product-action-delete btn-delete js-product-tooltip" ' +
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
    'image' => $imageRender,
    'name' => $nameRender,
    'category' => $categoryRender,
    'price' => $priceRender,
    'status' => $statusRender,
    'actions' => $actionsRenderByKey,
  ],

  'columnDefs' => [
  ['targets' => 0, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap product-index-col'],
  ['targets' => 1, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap product-image-col'],
  ['targets' => 2, 'className' => 'text-start product-name-col'],
  ['targets' => 3, 'className' => 'text-start product-category-col'],
  ['targets' => 4, 'className' => 'text-end text-nowrap product-price-col'],
  ['targets' => 5, 'className' => 'text-end text-nowrap product-discount-col'],
  ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap product-status-col'],
  ['targets' => 7, 'orderable' => false, 'searchable' => false, 'className' => 'text-center text-nowrap product-action-col'],
],
];
@endphp

@include('partials.css.product')

<main class="main-wrapper product-list-page">
  <div class="main-content">

    <div class="product-page-header">
      <div>
        <h5 class="product-page-title">
          <span class="material-icons-outlined">inventory_2</span>
          Sản Phẩm
        </h5>
      </div>

      <a href="{{ panel_route(module().'.create') }}" class="btn btn-primary product-create-btn">
        <span class="material-icons-outlined">add</span>
        Thêm Mới
      </a>
    </div>

    <div class="product-table-card">
      <x-data-table
        id="reload-table"
       :columns="[
  ['key' => 'index', 'title' => 'STT'],
  ['key' => 'image', 'title' => 'ẢNH'],
  ['key' => 'name', 'title' => 'TÊN SẢN PHẨM'],
  ['key' => 'category', 'title' => 'DANH MỤC'],
  ['key' => 'price', 'title' => 'GIÁ'],
  ['key' => 'price_discount', 'title' => 'GIÁ KM'],
  ['key' => 'status', 'title' => 'TRẠNG THÁI'],
  ['key' => 'actions', 'title' => 'HÀNH ĐỘNG'],
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
(function () {
  function getCsrf() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : '';
  }

  function initProductUI() {
    if (typeof bootstrap === 'undefined') return;

    document.querySelectorAll('.product-meta-trigger').forEach(function(el) {
      const old = bootstrap.Popover.getInstance(el);
      if (old) old.dispose();

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: 'left',
        container: 'body',
        sanitize: false
      });
    });

    document.querySelectorAll('.js-product-tooltip').forEach(function(el) {
      const old = bootstrap.Tooltip.getInstance(el);
      if (old) old.dispose();

      new bootstrap.Tooltip(el, {
        trigger: 'hover',
        placement: 'top',
        container: 'body'
      });
    });
  }

  document.addEventListener('change', async function(e) {
    const input = e.target.closest('.product-status-toggle');
    if (!input) return;

    const url = input.getAttribute('data-url');
    const oldChecked = !input.checked;

    if (!url) {
      input.checked = oldChecked;
      return;
    }

    input.disabled = true;

    try {
      const resp = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': getCsrf(),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
        },
        body: new URLSearchParams({ _method: 'PATCH' })
      });

      if (!resp.ok) {
        input.checked = oldChecked;
        const data = await resp.json().catch(() => null);
        (window.toastError || alert)(data?.message || 'Cập nhật trạng thái thất bại.');
      }
    } catch (err) {
      input.checked = oldChecked;
      (window.toastError || alert)('Không thể kết nối máy chủ.');
    } finally {
      input.disabled = false;
    }
  });

  if (window.jQuery) {
    jQuery(document).on('draw.dt', function () {
      initProductUI();
    });
  }

  setTimeout(initProductUI, 300);
})();
</script>
@endpush