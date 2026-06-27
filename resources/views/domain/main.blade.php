@extends('index')
@section('title', page_title())

@section('content')
@php
$module = module();
$editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);
$toggleStatusTpl = panel_route($module . '.toggle-status', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);
$toggleStatusTplJson = json_encode($toggleStatusTpl, JSON_UNESCAPED_SLASHES);
$deleteTextJson = json_encode('Xóa', JSON_UNESCAPED_UNICODE);

$typeRender = <<<'JS'
if (type !== 'display') return data;
const map = {
  travel: '<span class="badge bg-label-primary">Travel</span>'
};
return map[data] || `<span class="badge bg-label-secondary">${String(data || '—').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>`;
JS;

$statusRender = <<<JS
if (type !== 'display') return data;
const id = row.id;
const on = parseInt(row.status, 10) === 1;
const toggleUrl = id ? {$toggleStatusTplJson}.replace('__ID__', id) : '';
return '<div class="status-toggle-cell"><div class="form-check form-switch form-check-inline mb-0"><input type="checkbox" class="form-check-input domain-status-toggle status-toggle-wide" data-id="' + id + '" data-url="' + toggleUrl.replace(/"/g, '&quot;') + '" ' + (on ? 'checked' : '') + '></div></div>';
JS;

$createdAtRender = <<<'JS'
if (!data) return '<span class="text-muted">—</span>';

const date = new Date(data);

if (Number.isNaN(date.getTime())) {
  return '<span class="text-muted">—</span>';
}

const d = date.toLocaleDateString('vi-VN', {
  day: '2-digit',
  month: '2-digit',
  year: 'numeric'
});

const t = date.toLocaleTimeString('vi-VN', {
  hour: '2-digit',
  minute: '2-digit'
});

return `<div class="d-flex flex-column align-items-center">
  <span class="fw-bold">${d}</span>
  <small class="text-muted">${t}</small>
</div>`;
JS;


$createdAtRender = <<<'JS'
if (!data) return '<span class="text-muted">—</span>';

const date = new Date(data);

if (Number.isNaN(date.getTime())) {
  return '<span class="text-muted">—</span>';
}

const d = date.toLocaleDateString('vi-VN', {
  day: '2-digit',
  month: '2-digit',
  year: 'numeric'
});

const t = date.toLocaleTimeString('vi-VN', {
  hour: '2-digit',
  minute: '2-digit'
});

return `<div class="d-flex flex-column align-items-center">
  <span class="fw-bold">${d}</span>
  <small class="text-muted">${t}</small>
</div>`;
JS;

$metaRender = <<<'JS'
if (type !== 'display') return data;

const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const creator = esc(row?.creator || row?.creator_name || '—');

let createdAt = '—';

if (row?.created_at) {
  const date = new Date(row.created_at);

  const d = date.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  });

  const t = date.toLocaleTimeString('vi-VN', {
    hour: '2-digit',
    minute: '2-digit'
  });

  createdAt = esc(d + ' ' + t);
}

const popoverContent =
  '<div class="domain-meta-popover">' +
    '<div class="domain-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="domain-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return '<button type="button" ' +
  'class="btn p-0 border-0 bg-transparent domain-meta-trigger" ' +
  'data-bs-toggle="popover" ' +
  'data-bs-placement="left" ' +
  'data-bs-html="true" ' +
  'data-bs-trigger="hover focus" ' +
  'data-bs-content="' + popoverContent.replace(/"/g, '&quot;') + '">' +
  '<i class="icon-base ti tabler-info-circle"></i>' +
'</button>';
JS;
$actionsRender = <<<JS
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
  <div class="domain-action-icons">
    <a href="\${esc(editUrl)}"
       class="domain-action-icon domain-action-edit"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="domain-action-icon domain-action-delete btn-delete"
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
  'responsiveModal' => true,
  'order' => [[0, 'asc']],
 'rendersByKey' => [
  'type' => $typeRender,
  'status' => $statusRender,
  'creator' => $metaRender,
  'id' => $actionsRender,
],

'columnDefs' => [
  ['targets' => 3, 'className' => 'all text-center align-middle'],
  ['targets' => 4, 'className' => 'all text-center align-middle domain-meta-col'],
  ['targets' => 5, 'className' => 'none'],
  ['targets' => 6, 'orderable' => false, 'searchable' => false, 'className' => 'all text-center align-middle domain-action-col'],
],
  'responsiveHeaderField' => 'name',
  'modalFields' => ['name', 'type', 'status', 'creator', 'created_at'],
  'modalRenders' => [
    'type' => "const map = { travel: '<span class=\"badge bg-label-primary\">Travel</span>' }; return map[data] || `<span class=\"badge bg-label-secondary\">\${String(data || '—').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>`;",
    'created_at' => $createdAtRender,
  ],
  'searchPlaceholder' => 'Tìm domain...',
];
@endphp

<x-table-header :title="page_title()" icon="tabler-world" :create-route="panel_route(module().'.create')" />

<x-data-table
  id="reload-table"
:columns="[
  ['key' => 'name', 'title' => 'DOMAIN'],
  ['key' => 'type', 'title' => 'TYPE'],
  ['key' => 'status', 'title' => 'TRẠNG THÁI'],
  ['key' => 'creator', 'title' => 'THÔNG TIN'],
  ['key' => '__details', 'title' => '', 'class' => 'none'],
  ['key' => 'id', 'title' => 'HÀNH ĐỘNG']
]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options"
></x-data-table>

<style>
  .status-toggle-wide {
    width: 2.5em !important;
    min-width: 2.5em;
  }

  .status-toggle-cell {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .status-toggle-cell .form-check.form-switch {
    margin: 0;
    display: inline-flex;
  }

.domain-meta-col {
  width: 110px;
  min-width: 110px;
  white-space: nowrap !important;
}

.domain-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
}

.domain-action-icon {
  width: 22px;
  height: 22px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none !important;
}

.domain-action-edit {
  color: #00cfe8 !important;
}

.domain-action-delete {
  color: #ea5455 !important;
}
.domain-meta-trigger {
  color: rgba(255,255,255,0.65) !important;
  box-shadow: none !important;
}

.domain-meta-trigger i {
  font-size: 1.05rem;
  line-height: 1;
}

.domain-meta-popover {
  min-width: 190px;
  font-size: 0.8125rem;
  line-height: 1.5;
}

.domain-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.domain-meta-row + .domain-meta-row {
  margin-top: 4px;
}
</style>
@endsection

@push('scripts')
<script>
(function(){
  jQuery(document).off('change', '.domain-status-toggle').on('change', '.domain-status-toggle', function() {
    var chk = this;
    var url = chk.getAttribute('data-url');
    if (!url) return;

    var dt = jQuery('#reload-table').DataTable();
    if (!dt) return;

    var isOn = chk.checked;

    jQuery.ajax({
      url: url,
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      success: function(res) {
        var rowData = dt.row(jQuery(chk).closest('tr')).data();

        if (rowData) {
          rowData.status = res.status;
        }

        if (typeof window.showToast === 'function') {
          window.showToast({
            message: isOn ? 'Đã bật trạng thái thành công.' : 'Đã tắt trạng thái thành công.',
            type: 'success',
            delay: 2500
          });
        }
      },
      error: function() {
        chk.checked = !chk.checked;

        if (typeof window.showToast === 'function') {
          window.showToast({
            message: 'Không thể cập nhật trạng thái.',
            type: 'error',
            delay: 3000
          });
        }
      }
    });
  });

  function initDomainPopovers() {
  if (typeof bootstrap === 'undefined' || !bootstrap.Popover) return;

  document.querySelectorAll('.domain-meta-trigger').forEach(function(el) {
    var oldInstance = bootstrap.Popover.getInstance(el);
    if (oldInstance) oldInstance.dispose();

    new bootstrap.Popover(el, {
      html: true,
      trigger: 'hover focus',
      placement: 'left',
      container: 'body',
      sanitize: false
    });
  });
}

jQuery(document).on('draw.dt', function() {
  initDomainPopovers();
});

setTimeout(initDomainPopovers, 300);
})();
</script>
@endpush
