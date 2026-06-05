<style>
/* ================================
   Menu list page
   Scope chỉ trong .menu-list-page / .menu-form-page
================================ */

.menu-list-page .main-content,
.menu-form-page .main-content {
  padding-bottom: 48px;
}

/* ================================
   Header giống Post
================================ */

.menu-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.menu-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #f8fafc;
  font-size: 22px;
  font-weight: 700;
}

.menu-page-title .material-icons-outlined {
  font-size: 24px;
}

.menu-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.menu-create-btn .material-icons-outlined {
  font-size: 21px;
}

.menu-table-card {
  width: 100%;
  overflow: visible;
}

/* ================================
   DataTables wrapper
================================ */

.menu-list-page .dataTables_wrapper,
.menu-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.menu-list-page .table-responsive {
  overflow-x: visible;
}

.menu-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.menu-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.menu-list-page .pb-3 {
  padding-bottom: 0 !important;
}

/* ================================
   Top controls
================================ */

.menu-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.menu-list-page .dataTables_length label,
.menu-list-page .dataTables_filter label {
  color: #e5e7eb;
  font-size: 15px;
  font-weight: 600;
}

.menu-list-page .dataTables_length select,
.menu-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: #f8fafc;
  background-color: transparent;
  border: 1px solid rgba(255, 255, 255, .16);
  box-shadow: none;
}

.menu-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.menu-list-page .dataTables_filter {
  text-align: right;
}

.menu-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* ================================
   Table
================================ */

.menu-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.menu-list-page #reload-table thead th {
  padding: 15px 14px;
  color: #f8fafc !important;
  font-size: 15.5px;
  font-weight: 800;
  letter-spacing: .035em;
  text-transform: uppercase;
  border-top: 1px solid rgba(255, 255, 255, .12);
  border-bottom: 1px solid rgba(255, 255, 255, .12);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  white-space: nowrap;
}

.menu-list-page #reload-table tbody td {
  padding: 16px 14px;
  color: #e5e7eb !important;
  font-size: 16px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid rgba(255, 255, 255, .075);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  box-sizing: border-box;
}

.menu-list-page #reload-table tbody tr:hover td {
  background-color: rgba(255, 255, 255, .035) !important;
}

/* Bỏ mũi tên sort DataTables */
.menu-list-page #reload-table thead th::before,
.menu-list-page #reload-table thead th::after,
.menu-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

/* ================================
   Column widths
================================ */

.menu-list-page .menu-index-col {
  width: 90px;
}

.menu-list-page .menu-name-col {
  width: 36%;
}

.menu-list-page .menu-location-col {
  width: 22%;
}

.menu-list-page .menu-status-col {
  width: 20%;
}

.menu-list-page .menu-action-col {
  width: 16%;
}

/* ================================
   Name + meta icons
================================ */

.menu-name-with-icons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  min-width: 0;
}

.menu-name-text {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.menu-item-title {
  color: inherit;
}

.seo-tree-connector {
  color: rgba(255, 255, 255, .45);
  margin-right: 6px;
}

.menu-name-icons {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex: 0 0 auto;
}

.menu-name-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  opacity: .9;
}

.menu-name-icon .material-icons-outlined,
.menu-name-icon i {
  font-size: 18px;
  line-height: 1;
}

.menu-name-icon-link {
  color: #22d3ee !important;
}

.menu-name-icon-info {
  color: rgba(255, 255, 255, .65) !important;
}

.menu-name-icon:hover {
  opacity: 1;
}

/* ================================
   Status toggle
================================ */

.status-toggle-wide {
  width: 2.5em;
  min-width: 2.5em;
}

.menu-status-toggle {
  cursor: pointer;
}

/* ================================
   Action icons giống Post
================================ */

.menu-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.menu-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  padding: 0;
  margin: 0;
  border-radius: 9px;
  text-decoration: none !important;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .035);
  box-shadow: none !important;
  line-height: 1;
  transition: transform .18s ease, background-color .18s ease, opacity .18s ease;
}

.menu-action-icon .material-icons-outlined,
.menu-action-icon i {
  display: block;
  font-size: 20px;
  line-height: 1;
}

.menu-action-edit {
  color: #22d3ee !important;
}

.menu-action-delete {
  color: #f87171 !important;
}

.menu-action-icon:hover {
  opacity: .95;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .085);
}

.menu-action-icon:focus,
.menu-action-icon:active {
  box-shadow: none !important;
  outline: none !important;
}

/* ================================
   Meta popover
================================ */

.menu-meta-popover {
  min-width: 190px;
  font-size: 13px;
  line-height: 1.5;
}

.menu-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.menu-meta-row + .menu-meta-row {
  margin-top: 4px;
}

/* ================================
   Info + pagination
================================ */

.menu-list-page .dataTables_info,
.menu-list-page .dt-info {
  color: rgba(255, 255, 255, .58) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}

.menu-list-page .dt-paging .pagination {
  display: flex !important;
  align-items: center !important;
  gap: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 999px;
  overflow: hidden;
}

.menu-list-page .dt-paging .page-link,
.menu-list-page .pagination .page-link {
  width: 42px !important;
  height: 40px !important;
  min-width: 42px !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 0 !important;
  background: transparent !important;
  color: rgba(255,255,255,.72) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  box-shadow: none !important;
}

.menu-list-page .dt-paging .page-item.active .page-link,
.menu-list-page .pagination .page-item.active .page-link {
  background: #0d6efd !important;
  color: #ffffff !important;
}

/* ================================
   Light theme fallback
================================ */

html[data-bs-theme="light"] .menu-page-title,
body.light-theme .menu-page-title {
  color: #111827;
}

html[data-bs-theme="light"] .menu-list-page #reload-table thead th,
body.light-theme .menu-list-page #reload-table thead th {
  color: #111827 !important;
  border-color: rgba(17,24,39,.14) !important;
}

html[data-bs-theme="light"] .menu-list-page #reload-table tbody td,
body.light-theme .menu-list-page #reload-table tbody td {
  color: #374151 !important;
  border-color: rgba(17,24,39,.12) !important;
}

.menu-list-page .menu-drag-col {
  width: 90px;
}

.menu-drag-handle {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 26px;
  height: 26px;
  cursor: grab;
  color: rgba(255, 255, 255, .45);
  user-select: none;
}

.menu-drag-handle:active {
  cursor: grabbing;
}

.menu-drag-handle .material-icons-outlined {
  font-size: 22px;
  line-height: 1;
}

.menu-row-dragging td {
  background-color: rgba(13, 110, 253, .12) !important;
}
</style>