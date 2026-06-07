<style>
/* ================================
   Product Module
   Scope:
   - .product-list-page: /product
   - .product-form-page: /product/create, /product/edit
================================ */

/* ================================
   Layout
================================ */

.product-list-page .main-content,
.product-form-page .main-content {
  padding-bottom: 48px;
}

.product-table-card {
  width: 100%;
  overflow: visible;
}

/* ================================
   Page Header
================================ */

.product-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.product-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #f8fafc;
  font-size: 22px;
  font-weight: 700;
}

.product-page-title .material-icons-outlined {
  font-size: 24px;
}

.product-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.product-create-btn .material-icons-outlined {
  font-size: 21px;
}

/* ================================
   DataTables Wrapper
================================ */

.product-list-page .dataTables_wrapper,
.product-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.product-list-page .table-responsive {
  overflow-x: visible;
}

.product-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.product-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.product-list-page .pb-3 {
  padding-bottom: 0 !important;
}

/* ================================
   Top Controls
================================ */

.product-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.product-list-page .dataTables_length label,
.product-list-page .dataTables_filter label {
  color: #e5e7eb;
  font-size: 15px;
  font-weight: 600;
}

.product-list-page .dataTables_length select,
.product-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: #f8fafc;
  background-color: transparent;
  border: 1px solid rgba(255, 255, 255, .16);
  box-shadow: none;
}

.product-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.product-list-page .dataTables_filter {
  text-align: right;
}

.product-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* ================================
   Table
================================ */

.product-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.product-list-page #reload-table thead th {
  padding: 15px 10px;
  color: #f8fafc !important;
  font-size: 14.5px;
  font-weight: 800;
  letter-spacing: .025em;
  text-transform: uppercase;
  border-top: 1px solid rgba(255, 255, 255, .12);
  border-bottom: 1px solid rgba(255, 255, 255, .12);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  white-space: nowrap;
  text-align: center;
}

.product-list-page #reload-table tbody td {
  padding: 16px 10px;
  color: #e5e7eb !important;
  font-size: 15px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid rgba(255, 255, 255, .075);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  box-sizing: border-box;
}

.product-list-page #reload-table tbody tr:hover td {
  background-color: rgba(255, 255, 255, .035) !important;
}

.product-list-page #reload-table thead th::before,
.product-list-page #reload-table thead th::after,
.product-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

/* ================================
   Column Widths
================================ */

.product-list-page .product-index-col {
  width: 64px;
}

.product-list-page .product-image-col {
  width: 76px;
}

.product-list-page .product-name-col {
  width: 30%;
}

.product-list-page .product-category-col {
  width: 16%;
}

.product-list-page .product-price-col,
.product-list-page .product-price-discount-col {
  width: 110px;
}

.product-list-page .product-status-col {
  width: 120px;
}

.product-list-page .product-action-col {
  width: 110px;
}

/* ================================
   Image
================================ */

.product-image,
.product-thumb {
  width: 54px;
  height: 54px;
  object-fit: cover;
  border-radius: 10px;
}

.product-image-placeholder {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 54px;
  height: 54px;
  border-radius: 10px;
  color: rgba(255, 255, 255, .5);
  background: rgba(255, 255, 255, .05);
}

/* ================================
   Product Name + Meta Icons
================================ */

.product-name-with-icons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  min-width: 0;
}

.product-name-text {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.product-name-icons {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex: 0 0 auto;
}

.product-name-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  opacity: .9;
}

.product-name-icon .material-icons-outlined {
  font-size: 18px;
  line-height: 1;
}

.product-name-icon-link {
  color: #22d3ee !important;
}

.product-name-icon-info {
  color: rgba(255, 255, 255, .65) !important;
}

.product-name-icon:hover {
  opacity: 1;
}

/* ================================
   Category + Price
================================ */

.product-category-badge {
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  padding: 5px 10px;
  border-radius: 999px;
  color: #bfdbfe;
  background: rgba(59, 130, 246, .14);
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-price {
  color: #facc15;
  font-weight: 800;
  white-space: nowrap;
}

.product-price-discount {
  color: #fb7185;
  font-weight: 800;
  white-space: nowrap;
}

.product-list-page .product-price-col,
.product-list-page .product-price-discount-col {
  text-align: center !important;
}

/* ================================
   Status
================================ */

.status-toggle-wide {
  width: 2.5em;
  min-width: 2.5em;
}

.product-status-toggle {
  cursor: pointer;
}

/* ================================
   Action Icons
================================ */

.product-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.product-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  text-decoration: none !important;
  line-height: 1;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  transition: background-color .18s ease, opacity .18s ease, transform .18s ease;
}

.product-action-icon .material-icons-outlined,
.product-action-icon i {
  font-size: 20px;
  line-height: 1;
}

.product-action-edit {
  color: #22d3ee !important;
}

.product-action-delete {
  color: #f87171 !important;
}

.product-action-icon:hover {
  opacity: .9;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .075);
}

/* ================================
   Meta Popover
================================ */

.product-meta-popover {
  min-width: 210px;
  font-size: 13px;
  line-height: 1.5;
}

.product-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.product-meta-row + .product-meta-row {
  margin-top: 4px;
}

/* ================================
   Info + Pagination
================================ */

.product-list-page .dataTables_info,
.product-list-page .dt-info {
  color: rgba(255, 255, 255, .58) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}

.product-list-page .dt-paging .pagination {
  display: flex !important;
  align-items: center !important;
  gap: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
  border: 1px solid rgba(255, 255, 255, .12);
  border-radius: 999px;
  overflow: hidden;
}

.product-list-page .dt-paging .page-link,
.product-list-page .pagination .page-link {
  width: 42px !important;
  height: 40px !important;
  min-width: 42px !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 0 !important;
  background: transparent !important;
  color: rgba(255, 255, 255, .72) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  box-shadow: none !important;
}

.product-list-page .dt-paging .page-item.active .page-link,
.product-list-page .pagination .page-item.active .page-link {
  background: #0d6efd !important;
  color: #ffffff !important;
}

/* ================================
   Form
================================ */

.product-form-title {
  margin-bottom: 20px;
  color: #f8fafc;
  font-size: 22px;
  font-weight: 700;
}

.product-form-card {
  padding: 0;
}
/* ================================
   Product Form - File Input
================================ */

.product-form-page .product-file-field {
  width: 100%;
}

.product-form-page .product-file-field label,
.product-form-page .product-file-field .form-label {
  display: block;
  margin-bottom: 8px;
  color: #e5e7eb;
  font-size: 15px;
  font-weight: 600;
}

.product-form-page .product-file-field input[type="file"] {
  display: block;
  width: 100%;
  min-height: 40px;
  padding: 7px 12px;
  color: #e5e7eb;
  background-color: transparent;
  border: 1px solid rgba(255, 255, 255, .16);
  border-radius: 9px;
  font-size: 15px;
  line-height: 1.5;
  box-shadow: none;
}

.product-form-page .product-file-field input[type="file"]::file-selector-button {
  margin-right: 12px;
  padding: 6px 14px;
  color: #f8fafc;
  background: rgba(255, 255, 255, .08);
  border: 0;
  border-radius: 7px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.product-form-page .product-file-field input[type="file"]::file-selector-button:hover {
  background: rgba(255, 255, 255, .14);
}

.product-form-page .product-file-field img {
  display: block;
  max-width: 140px;
  max-height: 100px;
  object-fit: cover;
  margin-top: 10px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, .12);

}

/* ================================
   Product table info popover
================================ */

.product-form-page .popover,
.product-page .popover,
.popover {
  max-width: 420px;
}

.product-form-page .popover-body,
.product-page .popover-body,
.popover-body {
  white-space: normal !important;
  word-break: break-word !important;
  overflow-wrap: anywhere !important;
  line-height: 1.5;
}

.product-form-page .popover-body div,
.product-page .popover-body div,
.popover-body div {
  white-space: normal !important;
  word-break: break-word !important;
  overflow-wrap: anywhere !important;
}

.product-section {
    padding: 18px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.02);
}

.product-section-title {
    font-size: 15px;
    font-weight: 700;
    color: #e6c76f;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 10px;
}

.product-form-page .form-label {
    margin-bottom: 6px;
    font-weight: 600;
}

.product-form-page .form-control,
.product-form-page .form-select {
    min-height: 42px;
}

/* ================================
   Responsive
================================ */

@media (max-width: 1200px) {
  .product-list-page #reload-table {
    min-width: 980px;
  }

  .product-list-page .table-responsive {
    overflow-x: auto;
  }
}

@media (max-width: 992px) {
  .product-list-page #reload-table thead th,
  .product-list-page #reload-table tbody td {
    font-size: 14px;
  }

  .product-image,
  .product-thumb,
  .product-image-placeholder {
    width: 44px;
    height: 44px;
  }
}
</style>