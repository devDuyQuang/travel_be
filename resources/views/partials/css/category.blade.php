<style>
/* =====================================================
   Category List Page - same layout as Post page
===================================================== */

.category-list-page .main-content {
  padding-bottom: 48px;
}

.category-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.category-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.category-page-title .material-icons-outlined {
  font-size: 24px;
}

.category-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.category-create-btn .material-icons-outlined {
  font-size: 21px;
}

.category-table-card {
  width: 100%;
  overflow: visible;
}

/* DataTables wrapper */
.category-list-page .dataTables_wrapper,
.category-list-page .card-datatable,
.category-list-page .table-responsive {
  width: 100%;
  max-width: 100%;
  overflow: visible !important;
}

.category-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0 !important;
  margin-right: 0 !important;
}

.category-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.category-list-page .pb-3 {
  padding-bottom: 0 !important;
}

/* Top controls */
.category-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.category-list-page .dataTables_length label,
.category-list-page .dataTables_filter label {
  color: var(--cms-text) !important;
  font-size: 15px !important;
  font-weight: 600;
}

.category-list-page .dataTables_length select,
.category-list-page .dataTables_filter input {
  height: 40px !important;
  min-height: 40px;
  border-radius: 9px !important;
  font-size: 15px !important;
  color: var(--cms-heading) !important;
  background-color: transparent !important;
  border: 1px solid var(--cms-border) !important;
  box-shadow: none !important;
}

.category-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.category-list-page .dataTables_filter {
  text-align: right;
}

.category-list-page .dataTables_filter input {
  width: 190px !important;
  margin-left: 10px;
  padding: 6px 12px !important;
}

/* Table */
.category-list-page #reload-table {
  width: 100% !important;
  table-layout: fixed;
  border-collapse: collapse !important;
  border-spacing: 0 !important;
  margin: 0 !important;
}

.category-list-page #reload-table thead th {
  padding: 15px 14px !important;
  color: var(--cms-heading) !important;
  font-size: 15.5px !important;
  font-weight: 800 !important;
  letter-spacing: .035em;
  text-transform: uppercase;
  vertical-align: middle !important;
  background: transparent !important;
  border-top: 1px solid var(--cms-border) !important;
  border-bottom: 1px solid var(--cms-border) !important;
  border-left: 0 !important;
  border-right: 0 !important;
  white-space: nowrap;
}

.category-list-page #reload-table tbody td {
  padding: 16px 14px !important;
  color: var(--cms-text) !important;
  font-size: 16px !important;
  font-weight: 500;
  vertical-align: middle !important;
  background: transparent !important;
  border-bottom: 1px solid var(--cms-border-soft) !important;
  border-left: 0 !important;
  border-right: 0 !important;
}

.category-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg) !important;
}

/* Hide sorting arrows */
.category-list-page #reload-table thead .sorting::before,
.category-list-page #reload-table thead .sorting::after,
.category-list-page #reload-table thead .sorting_asc::before,
.category-list-page #reload-table thead .sorting_asc::after,
.category-list-page #reload-table thead .sorting_desc::before,
.category-list-page #reload-table thead .sorting_desc::after {
  display: none !important;
  content: none !important;
}

/* Column widths */
.category-list-page .category-index-col {
  width: 90px !important;
}

.category-list-page .category-name-col {
  width: 32% !important;
}

.category-list-page .category-type-col {
  width: 18% !important;
}

.category-list-page .category-home-col {
  width: 16% !important;
}

.category-list-page .category-status-col {
  width: 16% !important;
}

.category-list-page .category-action-col {
  width: 18% !important;
}

/* Name */
.category-name-with-icons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  min-width: 0;
}

.category-name-text {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.category-tree-prefix {
  color: rgba(255, 255, 255, .42);
  margin-right: 6px;
}

.category-name-icons {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex: 0 0 auto;
}

.category-name-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  opacity: .9;
}

.category-name-icon .material-icons-outlined {
  font-size: 18px;
  line-height: 1;
}

.category-name-icon-link {
  color: #22d3ee !important;
}

.category-name-icon-info {
  color: var(--cms-muted) !important;
}

/* Type badge */
.category-type-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  max-width: 100%;
  padding: 6px 10px;
  border-radius: 999px;
  color: #22d3ee;
  background: rgba(34, 211, 238, .12);
  font-size: 13.5px;
  font-weight: 700;
  line-height: 1.2;
}

/* Status */
.category-list-page .status-toggle-wide {
  width: 2.5em !important;
  min-width: 2.5em;
  cursor: pointer;
}

/* Actions */
.category-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
}

.category-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  text-decoration: none !important;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  transition: transform .18s ease, background-color .18s ease, opacity .18s ease;
}

.category-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.category-action-edit {
  color: #22d3ee !important;
}

.category-action-delete {
  color: #f87171 !important;
}

.category-action-icon:hover {
  opacity: .9;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .075);
}

/* Bottom info + pagination */
.category-list-page .dataTables_wrapper > .row:last-child {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  margin-top: 20px !important;
  padding: 0 !important;
  background: transparent !important;
}

.category-list-page .dataTables_wrapper > .row:last-child > div:first-child,
.category-list-page .dataTables_wrapper > .row:last-child > div:last-child {
  width: auto !important;
  max-width: none !important;
  flex: 0 0 auto !important;
  padding: 0 !important;
  margin: 0 !important;
}

.category-list-page .dataTables_info {
  margin: 0 !important;
  padding: 0 !important;
  color: var(--cms-muted) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
  line-height: 34px !important;
  white-space: nowrap !important;
}

.category-list-page .dataTables_paginate {
  float: none !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
  gap: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
  border: 1px solid rgba(255, 255, 255, .14);
  border-radius: 999px;
  overflow: hidden;
}

.category-list-page .dataTables_paginate .paginate_button {
  min-width: 40px !important;
  height: 34px !important;
  padding: 0 12px !important;
  margin: 0 !important;

  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;

  border-radius: 0 !important;
  border: 0 !important;
  border-right: 1px solid rgba(255, 255, 255, .14) !important;
  background: transparent !important;

  color: var(--cms-muted) !important;
  font-size: 15px !important;
  font-weight: 600 !important;
  line-height: 1 !important;
  text-decoration: none !important;
  box-shadow: none !important;
  cursor: pointer !important;
}

.category-list-page .dataTables_paginate .paginate_button:last-child {
  border-right: 0 !important;
}

.category-list-page .dataTables_paginate .paginate_button.current,
.category-list-page .dataTables_paginate .paginate_button.current:hover {
  background: #0d6efd !important;
  color: #ffffff !important;
}

.category-list-page .dataTables_paginate .paginate_button:hover {
  background: rgba(255, 255, 255, .08) !important;
  color: #ffffff !important;
}

.category-list-page .dataTables_paginate .paginate_button.disabled,
.category-list-page .dataTables_paginate .paginate_button.disabled:hover {
  opacity: .35 !important;
  cursor: default !important;
  pointer-events: none !important;
}

/* Mobile */
@media (max-width: 768px) {
  .category-page-header,
  .category-list-page .dataTables_wrapper > .row:first-child,
  .category-list-page .dataTables_wrapper > .row:last-child {
    flex-direction: column;
    align-items: flex-start !important;
    gap: 16px;
  }

  .category-list-page .dataTables_filter,
  .category-list-page .dataTables_filter input {
    width: 100% !important;
    text-align: left;
  }

  .category-list-page .dataTables_paginate {
    flex-wrap: wrap;
    border-radius: 8px;
  }
}

/* =====================================================
   Category Create / Edit Form
===================================================== */

.category-form-page .main-content {
  padding-top: 28px;
  padding-bottom: 48px;
}

.category-form-title {
  margin-bottom: 28px;
  padding-left: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.category-form-card {
  padding: 0;
}

.category-form-page .nav-tabs {
  margin-bottom: 24px;
}

.category-form-page .mb-6,
.category-form-page .mb-3 {
  margin-bottom: 18px;
}

.category-form-page input[type="file"] {
  width: 100%;
  height: 42px;
  border: 1px solid var(--cms-border);
  border-radius: 6px;
  background: transparent;
  color: var(--bs-body-color);
}

.category-form-page input[type="file"]::file-selector-button {
  height: 42px;
  padding: 0 16px;
  margin-right: 12px;
  border: 0;
  border-right: 1px solid rgba(255, 255, 255, .16);
  background: rgba(255, 255, 255, .08);
  color: var(--bs-body-color);
}
.category-drag-col {
  width: 70px;
}

.category-drag-handle {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: grab;
  color: rgba(255, 255, 255, .45);
}

.category-drag-handle i {
  font-size: 20px;
  line-height: 1;
}

.category-drag-handle:active {
  cursor: grabbing;
}

.category-sortable-ghost td {
  background: rgba(34, 211, 238, .08) !important;
}

.category-sortable-chosen td {
  background: rgba(255, 255, 255, .05) !important;
}
</style>