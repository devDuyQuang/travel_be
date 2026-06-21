<style>
.product-list-page,
.product-form-page {
    --p-primary: #0d6efd;
    --p-primary-soft: #eaf2ff;
    --p-heading: #172033;
    --p-text: #344054;
    --p-muted: #7a8496;
    --p-border: #dce4ef;
    --p-border-soft: #e9eef5;
    --p-surface: #fff;
    --p-soft: #f8fafc;
    --p-success: #18a66f;
    --p-danger: #ef4444;
}

.product-list-page .main-content,
.product-form-page .main-content {
    padding: 28px 32px 48px;
}

.product-page-shell {
    width: 100%;
    max-width: 100%;
}

/* Page header */
.product-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 24px;
}

.product-page-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.product-page-icon {
    width: 58px;
    height: 58px;
    flex: 0 0 58px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    color: var(--p-primary);
    background: var(--p-primary-soft);
}

.product-page-icon .material-icons-outlined {
    font-size: 30px;
}

.product-page-title {
    margin: 0 0 5px;
    color: var(--p-heading);
    font-size: 28px;
    font-weight: 750;
    line-height: 1.2;
}

.product-page-subtitle {
    max-width: 760px;
    margin: 0;
    color: var(--p-muted);
    font-size: 15px;
    line-height: 1.55;
}

.product-create-btn,
.product-back-btn {
    min-height: 46px;
    padding: 0 19px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex: 0 0 auto;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    white-space: nowrap;
}

.product-create-btn {
    border-color: var(--p-primary);
    background: var(--p-primary);
    box-shadow: 0 8px 18px rgba(13, 110, 253, .2);
}

.product-create-btn:hover {
    border-color: #0b5ed7;
    background: #0b5ed7;
    transform: translateY(-1px);
}

.product-back-btn {
    border: 1px solid var(--p-border);
    color: var(--p-text);
    background: var(--p-surface);
}

.product-back-btn:hover {
    border-color: #c7d2e2;
    color: var(--p-primary);
    background: var(--p-soft);
}

.product-create-btn .material-icons-outlined,
.product-back-btn .material-icons-outlined {
    font-size: 20px;
}

/* Cards */
.product-table-card,
.product-form-card {
    overflow: hidden;
    border: 1px solid var(--p-border);
    border-radius: 18px;
    background: var(--p-surface);
    box-shadow: 0 10px 30px rgba(31, 50, 81, .06);
}

.product-table-card-header,
.product-form-card-header {
    min-height: 82px;
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    border-bottom: 1px solid var(--p-border);
}

.product-table-title,
.product-form-card-title {
    margin: 0 0 5px;
    color: var(--p-heading);
    font-size: 17px;
    font-weight: 750;
}

.product-table-description,
.product-form-card-description {
    margin: 0;
    color: var(--p-muted);
    font-size: 14px;
}

.product-table-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #596579;
    font-size: 13px;
    font-weight: 650;
    white-space: nowrap;
}

.product-table-status-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--p-success);
    box-shadow: 0 0 0 5px rgba(24, 166, 111, .11);
}

.product-table-body,
.product-form-card-body {
    width: 100%;
}

/* DataTable controls */
.product-list-page .product-table-card .px-3 {
    padding: 0 !important;
}

.product-list-page .product-table-card .pb-3 {
    padding-bottom: 0 !important;
}

.product-list-page .card-datatable,
.product-list-page .dataTables_wrapper,
.product-list-page .dt-container,
.product-list-page #reload-table_wrapper {
    width: 100% !important;
    max-width: 100% !important;
}

.product-list-page .dataTables_wrapper > .row,
.product-list-page .dt-container > .row {
    width: auto;
    margin-right: 0;
    margin-left: 0;
}

.product-list-page .dataTables_wrapper > .row:first-child,
.product-list-page .dt-container > .row:first-child {
    padding: 17px 24px;
    align-items: center;
    border-bottom: 1px solid var(--p-border);
}

.product-list-page .dataTables_wrapper > .row:last-child,
.product-list-page .dt-container > .row:last-child {
    padding: 16px 24px;
    align-items: center;
    border-top: 1px solid var(--p-border);
}

.product-list-page .dataTables_length,
.product-list-page .dataTables_filter,
.product-list-page .dt-length,
.product-list-page .dt-search {
    color: #596579;
    font-size: 13px;
    font-weight: 650;
}

.product-list-page .dataTables_length select,
.product-list-page .dt-length select {
    min-width: 76px;
    min-height: 40px;
    margin: 0 8px;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    color: var(--p-heading);
    background: var(--p-surface);
    box-shadow: none;
}

.product-list-page .dataTables_filter,
.product-list-page .dt-search {
    text-align: right;
}

.product-list-page .dataTables_filter input,
.product-list-page .dt-search input {
    width: 240px;
    min-height: 40px;
    margin-left: 9px;
    padding: 8px 12px;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    color: var(--p-heading);
    background: var(--p-surface);
    outline: none;
    box-shadow: none;
}

.product-list-page .dataTables_filter input:focus,
.product-list-page .dt-search input:focus {
    border-color: var(--p-primary);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
}

.product-list-page .table-responsive {
    width: 100%;
    overflow-x: auto;
    overflow-y: visible;
}

/* Table */
.product-list-page #reload-table,
.product-list-page table.dataTable {
    width: 100% !important;
    min-width: 1040px;
    margin: 0 !important;
    table-layout: fixed;
    border-collapse: separate !important;
    border-spacing: 0;
}

.product-list-page #reload-table thead th {
    padding: 15px 12px !important;
    border: 0 !important;
    border-bottom: 1px solid var(--p-border) !important;
    color: #667284 !important;
    background: var(--p-soft) !important;
    font-size: 12px;
    font-weight: 750;
    letter-spacing: .035em;
    text-transform: uppercase;
    vertical-align: middle;
    white-space: nowrap;
}

.product-list-page #reload-table tbody td {
    height: 82px;
    padding: 13px 12px !important;
    border: 0 !important;
    border-bottom: 1px solid var(--p-border-soft) !important;
    color: var(--p-text) !important;
    background: var(--p-surface) !important;
    font-size: 14px;
    vertical-align: middle;
}

.product-list-page #reload-table tbody tr:last-child td {
    border-bottom: 0 !important;
}

.product-list-page #reload-table tbody tr:hover td {
    background: #f8fbff !important;
}

.product-list-page #reload-table thead th::before,
.product-list-page #reload-table thead th::after,
.product-list-page #reload-table thead .dt-column-order {
    display: none !important;
    content: none !important;
}

.product-index-col { width: 58px !important; }
.product-image-col { width: 78px !important; }
.product-name-col { width: auto !important; }
.product-category-col { width: 210px !important; }
.product-price-col,
.product-discount-col { width: 126px !important; }
.product-status-col { width: 112px !important; }
.product-action-col { width: 126px !important; }

/* Table values */
.product-index-col {
    color: #7a8496 !important;
    font-weight: 700;
}

.product-thumb,
.product-image,
.product-image-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 11px;
}

.product-thumb,
.product-image {
    display: inline-block;
    object-fit: cover;
    border: 1px solid var(--p-border-soft);
    background: var(--p-soft);
}

.product-image-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px dashed #cfd8e6;
    color: #a5afbf;
    background: var(--p-soft);
}

.product-image-placeholder .material-icons-outlined {
    font-size: 22px;
}

.product-name-with-icons {
    width: 100%;
    min-width: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.product-name-text {
    min-width: 0;
    overflow: hidden;
    color: var(--p-heading);
    font-size: 14px;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-name-icon {
    width: 26px;
    height: 26px;
    flex: 0 0 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #8b96a7 !important;
}

.product-name-icon:hover {
    color: var(--p-primary) !important;
    background: var(--p-primary-soft) !important;
}

.product-name-icon .material-icons-outlined {
    font-size: 18px;
}

.product-category-badge {
    max-width: 100%;
    padding: 7px 12px;
    display: inline-flex;
    align-items: center;
    overflow: hidden;
    border: 1px solid #d7e6ff;
    border-radius: 999px;
    color: #2463cc;
    background: #edf4ff;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-price,
.product-price-discount {
    display: inline-block;
    max-width: 100%;
    overflow: hidden;
    font-size: 13px;
    font-weight: 750;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-price { color: #0f766e; }
.product-price-discount { color: #dc2626; }

.product-status-col .form-check {
    min-height: auto;
    display: inline-flex;
    padding-left: 0;
}

.product-status-toggle {
    width: 2.65em !important;
    min-width: 2.65em;
    height: 1.35em;
    margin: 0 !important;
    cursor: pointer;
}

.product-action-icons {
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
}

.product-action-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    background: var(--p-surface);
    text-decoration: none !important;
    box-shadow: 0 3px 10px rgba(31, 50, 81, .06);
}

.product-action-icon .material-icons-outlined { font-size: 19px; }
.product-action-edit { color: #0ea5c6 !important; }
.product-action-delete { color: var(--p-danger) !important; }

.product-action-edit:hover {
    border-color: #a5e4f0;
    background: #ecfbfe;
    transform: translateY(-1px);
}

.product-action-delete:hover {
    border-color: #fecaca;
    background: #fff1f2;
    transform: translateY(-1px);
}

/* Popover */
.product-meta-popover {
    min-width: 245px;
    font-size: 13px;
    line-height: 1.45;
}

.product-meta-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
}

.product-meta-row + .product-meta-row { margin-top: 7px; }
.product-meta-row strong { color: var(--p-heading); white-space: nowrap; }
.product-meta-row span {
    max-width: 230px;
    color: var(--p-muted);
    text-align: right;
    overflow-wrap: anywhere;
}

.popover { max-width: 430px; }
.popover-body { white-space: normal !important; overflow-wrap: anywhere !important; }

/* Info and pagination */
.product-list-page .dataTables_info,
.product-list-page .dt-info {
    color: var(--p-muted) !important;
    font-size: 13px !important;
}

.product-list-page .pagination,
.product-list-page .dt-paging .pagination {
    display: flex !important;
    align-items: center;
    justify-content: flex-end;
    gap: 6px !important;
    margin: 0 !important;
}

.product-list-page .page-link,
.product-list-page .dt-paging .page-link {
    width: 38px !important;
    height: 38px !important;
    min-width: 38px !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--p-border) !important;
    border-radius: 9px !important;
    color: #667284 !important;
    background: var(--p-surface) !important;
    box-shadow: none !important;
}

.product-list-page .page-item.active .page-link {
    border-color: var(--p-primary) !important;
    color: #fff !important;
    background: var(--p-primary) !important;
}

.product-list-page .page-item.disabled .page-link { opacity: .45; }

/* Form */
.product-form-card-body { padding: 24px 28px 28px; }

.product-form-page .nav-tabs {
    gap: 5px;
    margin-bottom: 24px;
    border-bottom: 1px solid var(--p-border);
}

.product-form-page .nav-tabs .nav-link {
    padding: 11px 17px;
    border: 0;
    border-bottom: 2px solid transparent;
    border-radius: 0;
    color: var(--p-muted);
    background: transparent;
    font-size: 14px;
    font-weight: 700;
}

.product-form-page .nav-tabs .nav-link.active {
    border-bottom-color: var(--p-primary);
    color: var(--p-primary);
    background: transparent;
}

.product-section {
    padding: 22px;
    border: 1px solid var(--p-border-soft);
    border-radius: 14px;
    background: var(--p-soft);
}

.product-section-heading {
    margin-bottom: 18px;
    padding-bottom: 12px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid var(--p-border);
}

.product-section-title {
    margin: 0;
    padding: 0;
    border: 0;
    color: var(--p-heading);
    font-size: 15px;
    font-weight: 750;
}

.product-section-description,
.product-field-help {
    margin: 5px 0 0;
    color: var(--p-muted);
    font-size: 12px;
    line-height: 1.5;
}

.product-form-page .form-label {
    margin-bottom: 7px;
    color: var(--p-text);
    font-size: 13px;
    font-weight: 700;
}

.product-form-page .form-control,
.product-form-page .form-select {
    min-height: 44px;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    color: var(--p-heading);
    background: var(--p-surface);
    font-size: 14px;
    box-shadow: none;
}

.product-form-page textarea.form-control {
    min-height: 108px;
    resize: vertical;
}

.product-form-page .form-control:focus,
.product-form-page .form-select:focus {
    border-color: var(--p-primary);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
}

.product-form-page .form-control::placeholder { color: #a0a9b8; }

.product-switch-field {
    min-height: 44px;
    margin-top: 28px;
    padding: 10px 13px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    background: var(--p-surface);
}

.product-switch-field .form-check { min-height: auto; padding-left: 0; }
.product-switch-field .form-check-input {
    width: 2.55em;
    margin: 0;
    cursor: pointer;
}

.product-attribute-group {
    padding: 18px;
    border: 1px solid var(--p-border);
    border-radius: 12px;
    background: var(--p-surface);
}

.product-attribute-group-title {
    margin: 0 0 16px;
    color: var(--p-primary);
    font-size: 14px;
    font-weight: 750;
}

.product-attributes-empty {
    margin: 0;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px dashed #cbd6e5;
    border-radius: 10px;
    color: var(--p-muted);
    background: var(--p-surface);
    font-size: 13px;
}

.product-attributes-empty .material-icons-outlined {
    color: var(--p-primary);
    font-size: 24px;
}

.product-attributes-empty strong {
    display: block;
    margin-bottom: 2px;
    color: var(--p-heading);
}

.product-attributes-empty p {
    margin: 0;
    color: var(--p-muted);
}

/* Media fields */
.product-media-card {
    height: 100%;
    padding: 17px;
    border: 1px solid var(--p-border);
    border-radius: 12px;
    background: var(--p-surface);
}

.custom-file-row { display: flex; align-items: center; gap: 10px; }

.custom-file-name {
    min-width: 0;
    overflow: hidden;
    color: var(--p-muted);
    font-size: 12px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.custom-file-btn {
    min-height: 40px;
    padding: 0 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #c9d4e3;
    border-radius: 9px;
    color: var(--p-text);
    background: var(--p-soft);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
}

.custom-file-btn:hover {
    border-color: #9fc1f7;
    color: var(--p-primary);
    background: var(--p-primary-soft);
}

.product-media-preview-wrapper { position: relative; width: fit-content; }

.product-media-preview {
    display: block;
    width: 150px;
    height: 100px;
    object-fit: cover;
    border: 1px solid var(--p-border);
    border-radius: 10px;
    background: var(--p-soft);
}

.product-gallery-preview {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 12px;
}

.product-gallery-preview-item {
    position: relative;
    width: 112px;
    height: 78px;
    flex: 0 0 112px;
}

.product-gallery-preview-item img {
    width: 112px;
    height: 78px;
    object-fit: cover;
    border: 1px solid var(--p-border);
    border-radius: 9px;
}

.product-gallery-delete-btn {
    position: absolute;
    top: -7px;
    right: -7px;
    width: 24px;
    height: 24px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    border-radius: 50%;
    color: #fff;
    background: var(--p-danger);
    cursor: pointer;
    box-shadow: 0 3px 8px rgba(239, 68, 68, .24);
}

.product-form-page .cke_chrome {
    overflow: hidden;
    border: 1px solid var(--p-border) !important;
    border-radius: 10px;
    box-shadow: none !important;
}

.product-form-page .cke_top,
.product-form-page .cke_bottom {
    border-color: var(--p-border) !important;
    background: var(--p-soft) !important;
}

.product-form-page .nav-tabs .nav-item {
    flex: 0 0 auto !important;
}

.product-form-page .tab-content {
    padding-top: 0 !important;
}

.product-form-page .row.mt-4:last-child {
    padding-top: 20px;
    border-top: 1px solid var(--p-border);
}

.product-form-page #btnSubmit,
.product-form-page .btn-label-secondary {
    min-height: 44px;
    padding: 0 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 1199.98px) {
    .product-list-page .main-content,
    .product-form-page .main-content { padding: 24px 22px 42px; }
}

@media (max-width: 991.98px) {
    .product-page-header { align-items: flex-start; }
    .product-page-title { font-size: 25px; }
    .product-form-card-body { padding: 20px; }
}

@media (max-width: 767.98px) {
    .product-list-page .main-content,
    .product-form-page .main-content { padding: 20px 15px 36px; }

    .product-page-header { flex-direction: column; }
    .product-create-btn,
    .product-back-btn { width: 100%; }

    .product-page-icon {
        width: 50px;
        height: 50px;
        flex-basis: 50px;
        border-radius: 14px;
    }

    .product-page-title { font-size: 23px; }

    .product-table-card-header,
    .product-form-card-header {
        padding: 18px 19px;
        flex-direction: column;
        align-items: flex-start;
    }

    .product-list-page .dataTables_wrapper > .row:first-child,
    .product-list-page .dt-container > .row:first-child,
    .product-list-page .dataTables_wrapper > .row:last-child,
    .product-list-page .dt-container > .row:last-child { padding: 14px 16px; }

    .product-list-page .dataTables_filter,
    .product-list-page .dt-search { margin-top: 12px; text-align: left; }

    .product-list-page .dataTables_filter input,
    .product-list-page .dt-search input {
        width: 100%;
        margin: 7px 0 0;
    }

    .product-form-card-body { padding: 16px; }
    .product-section { padding: 17px; }
    .product-switch-field { margin-top: 0; }
}
</style>
