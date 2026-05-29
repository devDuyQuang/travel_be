@extends('index')
@section('title', 'Quản lý Bác sĩ')

@section('content')
@php
$module = module();

$editRouteTpl = panel_route($module . '.edit', ['id' => '__ID__']);
$deleteRouteTpl = panel_route($module . '.destroy', ['id' => '__ID__']);

$editRouteTplJson = json_encode($editRouteTpl, JSON_UNESCAPED_SLASHES);
$deleteRouteTplJson = json_encode($deleteRouteTpl, JSON_UNESCAPED_SLASHES);

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
  <div class="doctor-action-icons">
    <a href="\${esc(editUrl)}"
       class="doctor-action-icon doctor-action-edit js-doctor-tooltip"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="Chỉnh sửa">
      <i class="icon-base ti tabler-pencil"></i>
    </a>

    <a href="javascript:void(0)"
       class="doctor-action-icon doctor-action-delete btn-delete js-doctor-tooltip"
       data-id="\${esc(id)}"
       data-name="\${esc(name)}"
       data-url="\${esc(deleteUrl)}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal"
       data-bs-placement="top"
       title="Xóa">
      <i class="icon-base ti tabler-trash"></i>
    </a>
  </div>
`;
JS;

$imageRenderByKey = <<<JS
if (!data) {
  return '<span class="text-muted">—</span>';
}

return `
  <img
    src="\${data}"
    alt="Doctor"
    class="rounded doctor-avatar"
  >
`;
JS;

$socialsRenderByKey = <<<JS
if (!data || typeof data !== 'object') {
  return '<span class="text-muted">—</span>';
}

const icons = {
  linkedin: 'tabler-brand-linkedin',
  facebook: 'tabler-brand-facebook',
  twitter: 'tabler-brand-twitter',
  youtube: 'tabler-brand-youtube'
};

const escapeHtml = (value) => String(value || '')
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;');

const rows = Object.keys(icons)
  .filter((key) => data[key])
  .map((key) => {
    const href = escapeHtml(data[key]);

    return `
      <a href="\${href}"
         target="_blank"
         rel="noopener"
         class="doctor-social-link">
        <i class="icon-base ti \${icons[key]}"></i>
      </a>
    `;
  });

return rows.length
  ? `<div class="doctor-socials">\${rows.join('')}</div>`
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
  '<div class="doctor-meta-popover">' +
    '<div class="doctor-meta-row"><strong>Ngày tạo:</strong> <span>' + createdAt + '</span></div>' +
    '<div class="doctor-meta-row"><strong>Người tạo:</strong> <span>' + creator + '</span></div>' +
  '</div>';

return `
  <button
    type="button"
    class="btn p-0 border-0 bg-transparent doctor-meta-trigger"
    data-bs-toggle="popover"
    data-bs-placement="left"
    data-bs-html="true"
    data-bs-trigger="hover focus"
    data-bs-content="${popoverContent.replace(/"/g, '&quot;')}">
    <i class="icon-base ti tabler-info-circle"></i>
  </button>
`;
JS;

$options = [
  'control' => true,
  'responsiveModal' => false,
  'order' => [[2, 'asc']],

  'rendersByKey' => [
    'image' => $imageRenderByKey,
    'socials' => $socialsRenderByKey,
    'creator' => $metaRenderByKey,
    'id' => $actionsRenderByKey,
  ],

  'columnDefs' => [
  ['targets' => 1, 'className' => 'doctor-image-col text-center align-middle all'],
  ['targets' => 2, 'className' => 'doctor-name-col align-middle all'],
  ['targets' => 3, 'className' => 'doctor-specialty-col align-middle all'],
  ['targets' => 4, 'className' => 'doctor-birth-col text-center align-middle all'],
  ['targets' => 5, 'className' => 'doctor-phone-col align-middle all'],
  ['targets' => 6, 'className' => 'doctor-social-col text-center align-middle min-desktop'],
  ['targets' => 7, 'className' => 'doctor-meta-col text-center align-middle all'],
  ['targets' => 8, 'orderable' => false, 'searchable' => false, 'className' => 'doctor-action-col text-center align-middle text-nowrap all'],
],

  'searchPlaceholder' => 'Tìm kiếm bác sĩ...',
];
@endphp

<x-table-header
  :title="'Danh sách Bác sĩ'"
  icon="tabler-stethoscope"
  :create-route="panel_route(module().'.create')" />

<x-data-table
  id="reload-table"
  :columns="[
    ['key' => 'image', 'title' => 'Ảnh'],
    ['key' => 'name', 'title' => 'Tên bác sĩ'],
    ['key' => 'specialty', 'title' => 'Chuyên khoa'],
    ['key' => 'birth_year', 'title' => 'Năm sinh'],
    ['key' => 'phone', 'title' => 'Số điện thoại'],
    ['key' => 'socials', 'title' => 'Mạng xã hội'],
    ['key' => 'creator', 'title' => ''],
    ['key' => 'id', 'title' => 'Hành động'],
  ]"
  ajax-url="{{ panel_route(module().'.datatable') }}"
  :options="$options"
/>

<style>
#reload-table_wrapper {
  overflow-x: hidden !important;
}

#reload-table {
  width: 100% !important;
  table-layout: auto;
}

#reload-table th,
#reload-table td {
  vertical-align: middle !important;
}

#reload-table th {
  white-space: nowrap !important;
}

/* =========================
   Avatar
========================= */
.doctor-avatar {
  width: 44px;
  height: 44px;
  object-fit: cover;
  border-radius: 8px;
}

/* =========================
   Column sizing
========================= */
.doctor-image-col {
  width: 60px;
  min-width: 60px;
}

.doctor-name-col {
  min-width: 180px;
  white-space: normal !important;
  word-break: break-word;
}

.doctor-specialty-col {
  min-width: 160px;
  white-space: normal !important;
  word-break: break-word;
}

.doctor-birth-col {
  width: 90px;
  min-width: 90px;
}

.doctor-phone-col {
  min-width: 140px;
  white-space: nowrap;
}

.doctor-social-col {
  width: 120px;
  min-width: 120px;
}

.doctor-meta-col {
  width: 50px;
  min-width: 50px;
}

.doctor-action-col {
  width: 90px;
  min-width: 90px;
}

/* =========================
   Social links
========================= */
.doctor-socials {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  flex-wrap: nowrap;
}

.doctor-social-link {
  color: #00cfe8 !important;
  font-size: 1.05rem;
  transition: all 0.2s ease;
}

.doctor-social-link:hover {
  opacity: 0.8;
  transform: translateY(-1px);
}

/* =========================
   Meta info
========================= */
.doctor-meta-trigger {
  color: rgba(255,255,255,0.7) !important;
  box-shadow: none !important;
}

.doctor-meta-trigger i {
  font-size: 1.05rem;
}

.doctor-meta-popover {
  min-width: 190px;
  font-size: 0.8125rem;
  line-height: 1.5;
}

.doctor-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.doctor-meta-row + .doctor-meta-row {
  margin-top: 4px;
}

/* =========================
   Action icons
========================= */
.doctor-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.doctor-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
}

.doctor-action-icon i {
  font-size: 1.1rem;
}

.doctor-action-edit {
  color: #00cfe8 !important;
}

.doctor-action-delete {
  color: #ea5455 !important;
}

.doctor-action-icon:hover {
  opacity: 0.85;
}

/* =========================
   Responsive
========================= */
@media (max-width: 1200px) {
  .doctor-specialty-col {
    min-width: 140px;
  }

  .doctor-phone-col {
    min-width: 120px;
  }
}

@media (max-width: 992px) {
  #reload-table {
    font-size: 0.92rem;
  }

  .doctor-avatar {
    width: 38px;
    height: 38px;
  }

  .doctor-socials {
    gap: 8px;
  }

  .doctor-action-icons {
    gap: 10px;
  }
}

@media (max-width: 768px) {
  .doctor-name-col,
  .doctor-specialty-col {
    min-width: unset;
  }

  .doctor-phone-col {
    white-space: normal;
    word-break: break-word;
  }
}
</style>
@endsection

@push('scripts')
<script>
(function () {
  function initDoctorUI() {
    if (typeof bootstrap === 'undefined') {
      return;
    }

    document.querySelectorAll('.doctor-meta-trigger').forEach(function(el) {
      const oldPopover = bootstrap.Popover.getInstance(el);

      if (oldPopover) {
        oldPopover.dispose();
      }

      new bootstrap.Popover(el, {
        html: true,
        trigger: 'hover focus',
        placement: 'left',
        container: 'body',
        sanitize: false
      });
    });

    document.querySelectorAll('.js-doctor-tooltip').forEach(function(el) {
      const oldTooltip = bootstrap.Tooltip.getInstance(el);

      if (oldTooltip) {
        oldTooltip.dispose();
      }

      new bootstrap.Tooltip(el, {
        trigger: 'hover',
        placement: 'top',
        container: 'body'
      });
    });
  }

  jQuery(document).on('draw.dt', function () {
    initDoctorUI();
  });

  setTimeout(initDoctorUI, 300);
})();
</script>
@endpush