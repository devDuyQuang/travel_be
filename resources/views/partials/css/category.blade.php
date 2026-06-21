<style>
:root {
    --category-primary: #0d6efd;
    --category-primary-dark: #0957cf;
    --category-primary-soft: #eef5ff;

    --category-success: #19a56f;
    --category-danger: #ef4d56;
    --category-cyan: #08a8d5;

    --category-heading: #172033;
    --category-text: #354052;
    --category-muted: #6b7280;

    --category-border: #e1e7ef;
    --category-border-soft: #edf1f5;

    --category-page-bg: #f5f7fb;
    --category-card: #ffffff;

    --category-radius: 16px;
    --category-shadow:
        0 10px 30px rgba(22, 34, 51, 0.06);
}

/* =====================================================
   PAGE LAYOUT
===================================================== */

.category-list-page .main-content,
.category-form-page .main-content {
    min-height: calc(100vh - 70px);
    padding: 28px 28px 48px;
    background: var(--category-page-bg);
}

.category-page-shell {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
}

/* =====================================================
   PAGE HEADER
===================================================== */

.category-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 24px;
}

.category-page-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.category-page-icon {
    width: 48px;
    height: 48px;
    flex: 0 0 48px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    color: var(--category-primary);
    background: var(--category-primary-soft);
}

.category-page-icon .material-icons-outlined {
    font-size: 25px;
}

.category-page-title {
    margin: 0;

    color: var(--category-heading);

    font-size: 24px;
    font-weight: 700;
    line-height: 1.3;
    letter-spacing: -0.3px;
}

.category-page-subtitle {
    margin: 4px 0 0;

    color: var(--category-muted);

    font-size: 14px;
    line-height: 1.5;
}

.category-create-btn {
    min-height: 44px;
    padding: 10px 18px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    border: 0;
    border-radius: 11px;

    font-size: 14px;
    font-weight: 600;

    box-shadow:
        0 8px 18px rgba(13, 110, 253, 0.2);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.category-create-btn:hover {
    transform: translateY(-1px);

    box-shadow:
        0 11px 22px rgba(13, 110, 253, 0.25);
}

.category-create-btn .material-icons-outlined {
    font-size: 20px;
}

.category-back-btn {
    min-height: 40px;
    padding: 8px 14px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    border: 1px solid var(--category-border);
    border-radius: 10px;

    color: #4b5563;
    background: #ffffff;

    font-size: 13px;
    font-weight: 600;

    transition:
        color 0.2s ease,
        border-color 0.2s ease,
        background 0.2s ease;
}

.category-back-btn:hover {
    color: var(--category-primary);
    border-color: #bad1f7;
    background: var(--category-primary-soft);
}

.category-back-btn .material-icons-outlined {
    font-size: 18px;
}

/* =====================================================
   TABLE CARD
===================================================== */

.category-table-card {
    position: relative;
    z-index: 1;

    overflow: hidden;

    border: 1px solid var(--category-border);
    border-radius: var(--category-radius);

    background: var(--category-card);

    box-shadow: var(--category-shadow);
}

.category-table-card-header {
    min-height: 76px;
    padding: 17px 22px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;

    border-bottom: 1px solid var(--category-border);
}

.category-table-title {
    margin: 0;

    color: var(--category-heading);

    font-size: 16px;
    font-weight: 700;
}

.category-table-description {
    margin: 4px 0 0;

    color: var(--category-muted);

    font-size: 13px;
}

.category-table-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    white-space: nowrap;

    color: #526071;

    font-size: 12px;
    font-weight: 600;
}

.category-table-status-dot {
    width: 8px;
    height: 8px;

    display: inline-block;

    border-radius: 50%;

    background: var(--category-success);

    box-shadow:
        0 0 0 4px rgba(25, 165, 111, 0.12);
}

.category-table-body {
    width: 100%;
}

/* =====================================================
   DATATABLE
===================================================== */

.category-table-card .dataTables_wrapper,
.category-table-card .dt-container {
    padding: 0;
}

.category-table-card .dataTables_wrapper > .row,
.category-table-card .dt-container > .row {
    width: auto;
    margin-left: 0;
    margin-right: 0;
}

.category-table-card
.dataTables_wrapper > .row:first-child,
.category-table-card
.dt-container > .row:first-child {
    padding: 16px 20px;

    align-items: center;

    border-bottom: 1px solid var(--category-border);
}

.category-table-card
.dataTables_wrapper > .row:last-child,
.category-table-card
.dt-container > .row:last-child {
    padding: 15px 20px;

    align-items: center;

    border-top: 1px solid var(--category-border);
}

.category-table-card .dataTables_length,
.category-table-card .dataTables_filter,
.category-table-card .dt-length,
.category-table-card .dt-search {
    color: #556070;

    font-size: 13px;
    font-weight: 500;
}

.category-table-card .dataTables_length select,
.category-table-card .dt-length select {
    min-width: 70px;
    min-height: 38px;
    margin: 0 6px;

    border: 1px solid #dce3ec;
    border-radius: 9px;

    color: var(--category-heading);
    background-color: #ffffff;

    box-shadow: none;
}

.category-table-card .dataTables_filter input,
.category-table-card .dt-search input {
    width: 240px;
    min-height: 40px;
    margin-left: 8px;
    padding: 8px 12px;

    border: 1px solid #dce3ec;
    border-radius: 9px;

    color: var(--category-heading);
    background: #ffffff;

    outline: none;
    box-shadow: none;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.category-table-card .dataTables_filter input:focus,
.category-table-card .dt-search input:focus {
    border-color: var(--category-primary);

    box-shadow:
        0 0 0 3px rgba(13, 110, 253, 0.1);
}

.category-table-card table.dataTable {
    width: 100% !important;
    min-width: 1060px;
    margin: 0 !important;

    table-layout: fixed;

    border-collapse: separate !important;
    border-spacing: 0;
}

.category-table-card table.dataTable thead th {
    padding: 15px 14px !important;

    border-top: 0 !important;
    border-bottom:
        1px solid var(--category-border) !important;

    color: #667284;
    background: #f8fafc;

    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.035em;
    text-transform: uppercase;

    vertical-align: middle;
    white-space: nowrap;
}

.category-table-card table.dataTable tbody td {
    padding: 15px 14px !important;

    border-top: 0 !important;
    border-bottom:
        1px solid var(--category-border-soft) !important;

    color: #293244;
    background: #ffffff;

    font-size: 14px;

    vertical-align: middle;
}

.category-table-card
table.dataTable
tbody tr:last-child td {
    border-bottom: 0 !important;
}

.category-table-card
table.dataTable
tbody tr:hover td {
    background: #f8fbff;
}

.category-table-card .dataTables_info,
.category-table-card .dt-info {
    color: var(--category-muted);

    font-size: 13px;
}

/* Tắt toàn bộ icon sort vì ordering đang false */

.category-table-card
table.dataTable
thead
.sorting::before,
.category-table-card
table.dataTable
thead
.sorting::after,
.category-table-card
table.dataTable
thead
.sorting_asc::before,
.category-table-card
table.dataTable
thead
.sorting_asc::after,
.category-table-card
table.dataTable
thead
.sorting_desc::before,
.category-table-card
table.dataTable
thead
.sorting_desc::after {
    display: none !important;
    content: none !important;
}

/* =====================================================
   COLUMN WIDTHS
===================================================== */

.category-drag-col {
    width: 52px !important;
}

.category-name-col {
    width: auto !important;
}

.category-type-col {
    width: 112px !important;
}

.category-layout-col {
    width: 168px !important;
}

.category-home-col {
    width: 108px !important;
}

.category-status-col {
    width: 116px !important;
}

.category-action-col {
    width: 136px !important;
}

/* =====================================================
   DRAG HANDLE
===================================================== */

.category-drag-handle {
    width: 32px;
    height: 32px;
    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 0;
    border-radius: 8px;

    color: #9aa5b5;
    background: transparent;

    cursor: grab;

    transition:
        color 0.2s ease,
        background 0.2s ease;
}

.category-drag-handle:hover {
    color: var(--category-primary);
    background: var(--category-primary-soft);
}

.category-drag-handle:active {
    cursor: grabbing;
}

.category-drag-handle
.material-icons-outlined {
    font-size: 20px;
}

.category-sortable-ghost td {
    background: #eef5ff !important;
    opacity: 0.65;
}

.category-sortable-chosen td {
    background: #f8fbff !important;
}

/* =====================================================
   NAME COLUMN
===================================================== */

.category-name-with-icons {
    width: 100%;
    min-width: 0;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
}

.category-name-content {
    min-width: 0;

    display: flex;
    align-items: center;
    gap: 5px;
}

.category-name-text {
    min-width: 0;

    overflow: hidden;

    color: #20293a;

    font-size: 14px;
    font-weight: 650;

    text-overflow: ellipsis;
    white-space: nowrap;
}

.category-tree-prefix {
    display: inline-flex;
    align-items: center;

    color: #a1acba;
}

.category-tree-prefix
.material-icons-outlined {
    font-size: 18px;
}

.category-name-icons {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    flex: 0 0 auto;

    opacity: 0.72;

    transition: opacity 0.2s ease;
}

tr:hover .category-name-icons {
    opacity: 1;
}

.category-name-icon {
    width: 28px;
    height: 28px;
    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 0;
    border-radius: 7px;

    color: #778195;
    background: transparent;

    text-decoration: none;

    transition:
        color 0.2s ease,
        background 0.2s ease;
}

.category-name-icon:hover {
    color: var(--category-primary);
    background: var(--category-primary-soft);
}

.category-name-icon
.material-icons-outlined {
    font-size: 18px;
}

.category-name-icon-link {
    color: var(--category-cyan);
}

.category-name-icon-info {
    color: #8792a5;
}

/* =====================================================
   POPOVER CONTENT
===================================================== */

.category-meta-popover {
    min-width: 230px;
}

.category-meta-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;

    margin: 5px 0;

    font-size: 13px;
}

.category-meta-row strong {
    color: var(--category-heading);
    white-space: nowrap;
}

.category-meta-row span {
    color: var(--category-muted);
    text-align: right;
}

/* =====================================================
   TYPE BADGE
===================================================== */

.category-type-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 74px;
    padding: 6px 10px;

    border-radius: 999px;

    font-size: 12px;
    font-weight: 700;
    line-height: 1;
}

.category-type-badge.is-service {
    color: #1260c9;
    background: #e6f0ff;
}

.category-type-badge.is-post {
    color: #8a5b12;
    background: #fff4d6;
}

/* =====================================================
   LAYOUT BADGE
===================================================== */

.category-layout-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    max-width: 100%;
    min-width: 92px;
    padding: 7px 12px;

    overflow: hidden;

    border: 1px solid transparent;
    border-radius: 999px;

    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.category-layout-badge.has-layout {
    color: #1260c9;
    border-color: #d6e6ff;
    background: #eef5ff;
}

.category-layout-badge.is-default {
    color: #667284;
    border-color: #e2e8f0;
    background: #f8fafc;
}

.category-layout-col,
.category-home-col,
.category-status-col,
.category-action-col {
    overflow: hidden;
}

/* =====================================================
   SWITCH
===================================================== */

.category-switch-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-switch {
    width: 42px !important;
    height: 22px !important;
    margin: 0 !important;

    cursor: pointer;

    border-color: #d2d9e3;

    box-shadow: none !important;
}

.category-switch:focus {
    border-color: var(--category-primary);

    box-shadow:
        0 0 0 3px rgba(13, 110, 253, 0.12) !important;
}

/* =====================================================
   ACTIONS
===================================================== */

.category-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
}

.category-action-icon {
    width: 38px;
    height: 38px;
    padding: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid var(--category-border);
    border-radius: 10px;

    background: #ffffff;

    text-decoration: none;

    box-shadow:
        0 3px 8px rgba(31, 41, 55, 0.04);

    transition:
        transform 0.2s ease,
        border-color 0.2s ease,
        background 0.2s ease,
        color 0.2s ease;
}

.category-action-icon:hover {
    transform: translateY(-1px);
}

.category-action-edit {
    color: var(--category-cyan);
}

.category-action-edit:hover {
    color: #078db3;
    border-color: #b9e8f5;
    background: #effbfe;
}

.category-action-delete {
    color: var(--category-danger);
}

.category-action-delete:hover {
    color: #d83943;
    border-color: #ffc9cd;
    background: #fff3f4;
}

.category-action-icon
.material-icons-outlined {
    font-size: 19px;
}

/* =====================================================
   PAGINATION
===================================================== */

.category-table-card .pagination {
    gap: 5px;
    margin-bottom: 0;
}

.category-table-card .page-link {
    min-width: 34px;
    height: 34px;
    padding: 5px 9px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid var(--category-border);
    border-radius: 8px !important;

    color: #566173;
    background: #ffffff;

    font-size: 13px;

    box-shadow: none;
}

.category-table-card .page-link:hover {
    color: var(--category-primary);
    border-color: #bfd5f8;
    background: var(--category-primary-soft);
}

.category-table-card
.page-item.active
.page-link {
    color: #ffffff;
    border-color: var(--category-primary);
    background: var(--category-primary);

    box-shadow:
        0 5px 12px rgba(13, 110, 253, 0.2);
}

.category-table-card
.page-item.disabled
.page-link {
    opacity: 0.45;
    background: #f3f5f8;
}

/* =====================================================
   FORM CARD
===================================================== */

.category-form-card {
    overflow: hidden;

    border: 1px solid var(--category-border);
    border-radius: var(--category-radius);

    background: #ffffff;

    box-shadow: var(--category-shadow);
}

.category-form-card-header {
    padding: 20px 24px;

    border-bottom: 1px solid var(--category-border);
}

.category-form-card-title {
    margin: 0;

    color: var(--category-heading);

    font-size: 17px;
    font-weight: 700;
}

.category-form-card-description {
    margin: 5px 0 0;

    color: var(--category-muted);

    font-size: 13px;
}

.category-form-card-body {
    padding: 24px;
}

.category-field-group {
    margin-bottom: 18px;
}

.category-form-card .nav-tabs {
    gap: 8px;
    padding: 6px;

    border: 0;
    border-radius: 11px;

    background: #f3f6fa;
}

.category-form-card
.nav-tabs
.nav-link {
    border: 0;
    border-radius: 8px;

    color: #647083;

    font-size: 14px;
    font-weight: 600;
}

.category-form-card
.nav-tabs
.nav-link.active {
    color: var(--category-primary);
    background: #ffffff;

    box-shadow:
        0 4px 12px rgba(31, 41, 55, 0.07);
}

.category-form-card .tab-content {
    padding-top: 24px;
}

.category-form-card .form-label {
    margin-bottom: 7px;

    color: #364052;

    font-size: 13px;
    font-weight: 600;
}

.category-form-card .form-control,
.category-form-card .form-select {
    min-height: 44px;

    border-color: #dce3ec;
    border-radius: 10px;

    color: var(--category-heading);
    background-color: #ffffff;

    box-shadow: none;
}

.category-form-card
textarea.form-control {
    min-height: 100px;
}

.category-form-card .form-control:focus,
.category-form-card .form-select:focus {
    border-color: var(--category-primary);

    box-shadow:
        0 0 0 3px rgba(13, 110, 253, 0.1);
}

/* =====================================================
   SELECT2
===================================================== */

.category-form-card .select2-container {
    width: 100% !important;
    display: block;
}

.category-form-card
.select2-container--default
.select2-selection--single {
    height: 44px;

    display: flex;
    align-items: center;

    border: 1px solid #dce3ec;
    border-radius: 10px;

    background: #ffffff;

    box-shadow: none;
}

.category-form-card
.select2-container--default
.select2-selection--single
.select2-selection__rendered {
    width: 100%;
    padding: 0 42px 0 14px;

    color: var(--category-heading);

    font-size: 14px;
    line-height: 42px;
}

.category-form-card
.select2-container--default
.select2-selection--single
.select2-selection__arrow {
    width: 38px;
    height: 42px;
    top: 0;
    right: 4px;
}

.category-form-card
.select2-container--default.select2-container--focus
.select2-selection--single,
.category-form-card
.select2-container--default.select2-container--open
.select2-selection--single {
    border-color: var(--category-primary);

    box-shadow:
        0 0 0 3px rgba(13, 110, 253, 0.1);
}

/* =====================================================
   FILE INPUT
===================================================== */

.category-form-card input[type="file"] {
    width: 100%;
    min-height: 44px;

    border: 1px solid #dce3ec;
    border-radius: 10px;

    color: var(--category-text);
    background: #ffffff;
}

.category-form-card
input[type="file"]::file-selector-button {
    min-height: 42px;
    padding: 0 16px;
    margin-right: 12px;

    border: 0;
    border-right: 1px solid #dce3ec;

    color: #354052;
    background: #f5f7fa;

    font-weight: 600;
}

/* =====================================================
   CKEDITOR
===================================================== */

.category-form-card .cke {
    width: 100% !important;

    overflow: hidden;

    border: 1px solid #dce3ec !important;
    border-radius: 10px;

    box-shadow: none !important;
}

.category-form-card .cke_contents {
    height: 360px !important;
}

.category-form-card .cke_top {
    border-bottom:
        1px solid var(--category-border) !important;

    background: #f8fafc !important;
}

.category-form-card .cke_bottom {
    border-top:
        1px solid var(--category-border) !important;

    background: #f8fafc !important;
}

/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991.98px) {
    .category-list-page .main-content,
    .category-form-page .main-content {
        padding: 22px 18px 36px;
    }

    .category-page-header {
        align-items: flex-start;
    }

    .category-table-card {
        overflow-x: auto;
    }

    .category-table-card table.dataTable {
        min-width: 1060px;
    }
}

@media (max-width: 767.98px) {
    .category-page-header {
        flex-direction: column;
    }

    .category-create-btn,
    .category-back-btn {
        width: 100%;
    }

    .category-table-card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .category-table-card
    .dataTables_filter input,
    .category-table-card
    .dt-search input {
        width: 100%;
        margin: 8px 0 0;
    }

    .category-form-card-body {
        padding: 18px;
    }

    .category-form-card .cke_contents {
        height: 300px !important;
    }
}
</style>
