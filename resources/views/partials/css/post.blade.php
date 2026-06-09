<style>
/* ================================
   Scope:
   - .post-list-page: /post
   - .post-form-page: /post/create, /post/edit
   Không ảnh hưởng module khác.
================================ */

/* ================================
   Post / Service List Page
================================ */

.post-list-page .main-content {
  padding-bottom: 48px;
}

.post-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.post-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.post-page-title .material-icons-outlined {
  font-size: 24px;
}

.post-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.post-create-btn .material-icons-outlined {
  font-size: 21px;
}

.post-filter-row {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.post-filter-row .form-select {
  width: 250px;
  height: 42px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-text);
  background-color: transparent;
  border: 1px solid var(--cms-border);
}

.post-table-card {
  width: 100%;
  overflow: visible;
}

/* ================================
   DataTables Wrapper
================================ */

.post-list-page .dataTables_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.post-list-page .table-responsive {
  overflow-x: visible;
}

.post-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

/* Xóa padding dư từ wrapper/component nếu có */
.post-list-page .px-3 {
  padding-left: 0;
  padding-right: 0;
}

.post-list-page .pb-3 {
  padding-bottom: 0;
}

/* ================================
   Top Controls: Show entries + Search
================================ */

.post-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.post-list-page .dataTables_length label,
.post-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.post-list-page .dataTables_length select,
.post-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.post-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.post-list-page .dataTables_filter {
  text-align: right;
}

.post-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* ================================
   Table
================================ */

.post-list-page #reload-table {
  width: 100%;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.post-list-page #reload-table thead th {
  padding: 15px 14px;
  color: var(--cms-heading);
  font-size: 15.5px;
  font-weight: 800;
  letter-spacing: .035em;
  text-transform: uppercase;
  border-top: 1px solid var(--cms-border);
  border-bottom: 1px solid var(--cms-border);
  border-left: 0;
  border-right: 0;
  background: transparent;
  white-space: nowrap;
}

.post-list-page #reload-table tbody td {
  padding: 16px 14px;
  color: var(--cms-text);
  font-size: 16px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid var(--cms-border-soft);
  border-left: 0;
  border-right: 0;
  background: transparent;
  box-sizing: border-box;
}

.post-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg);
}

/* Column widths */
.post-list-page .post-index-col {
  width: 90px;
}

.post-list-page .post-name-col {
  width: 28%;
}

.post-list-page .post-category-col {
  width: 28%;
}

.post-list-page .post-status-col {
  width: 18%;
}

.post-list-page .post-action-col {
  width: 16%;
}

/* ================================
   Name + Meta Icons
================================ */

.post-name-with-icons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  min-width: 0;
}

.post-name-text {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.post-name-icons {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex: 0 0 auto;
}

.post-name-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none;
  opacity: .9;
}

.post-name-icon .material-icons-outlined {
  font-size: 18px;
  line-height: 1;
}

.post-name-icon-link {
  color: #22d3ee;
}

.post-name-icon-info {
  color: var(--cms-muted);
}

.post-name-icon:hover {
  opacity: 1;
}

/* ================================
   Category Badge
================================ */

.post-category-badge {
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  padding: 6px 10px;
  margin: 2px 5px 2px 0;
  border-radius: 999px;
  color: #22d3ee;
  background: rgba(34, 211, 238, .12);
  font-size: 13.5px;
  font-weight: 700;
  line-height: 1.2;
}

/* ================================
   Status + Actions
================================ */

.status-toggle-wide {
  width: 2.5em;
  min-width: 2.5em;
}

.post-status-toggle {
  cursor: pointer;
}

.post-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
}

.post-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  text-decoration: none;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  transition: transform .18s ease, background-color .18s ease, opacity .18s ease;
}

.post-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.post-action-edit {
  color: #22d3ee;
}

.post-action-delete {
  color: #f87171;
}

.post-action-icon:hover {
  opacity: .9;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .075);
}

/* ================================
   DataTable Bottom
   Giống style template cũ hơn:
   - info bên trái
   - pagination bên phải
   - nằm chung 1 thanh nền
================================ */

.post-list-page .dataTables_wrapper > .row:last-child {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 28px;
  padding: 24px 28px;
  background: #2b3046;
  border-radius: 0 0 10px 10px;
}

.post-list-page .dataTables_wrapper > .row:last-child > div:first-child,
.post-list-page .dataTables_wrapper > .row:last-child > div:last-child {
  width: auto;
  max-width: 50%;
  flex: 0 0 auto;
  padding: 0;
  margin: 0;
}

.post-list-page .dataTables_wrapper > .row:last-child > div:first-child {
  display: flex;
  align-items: center;
  justify-content: flex-start;
}

.post-list-page .dataTables_wrapper > .row:last-child > div:last-child {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.post-list-page .dataTables_info {
  margin: 0;
  padding: 0;
  color: var(--cms-muted);
  font-size: 22px;
  font-weight: 500;
  line-height: 54px;
  white-space: nowrap;
}

.post-list-page .dataTables_paginate {
  float: none;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  margin: 0;
  padding: 0;
}

.post-list-page .dataTables_paginate .paginate_button {
  min-width: 54px;
  height: 54px;
  padding: 0 18px;
  margin: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  border: 0;
  background: rgba(255, 255, 255, .055);
  color: var(--cms-muted);
  font-size: 24px;
  font-weight: 700;
  line-height: 1;
  text-decoration: none;
  box-shadow: none;
  cursor: pointer;
}

.post-list-page .dataTables_paginate .paginate_button.current,
.post-list-page .dataTables_paginate .paginate_button.current:hover {
  background: #0f9f95;
  color: #ffffff;
  border: 0;
  box-shadow: 0 0 18px rgba(15, 159, 149, .28);
}

.post-list-page .dataTables_paginate .paginate_button:hover {
  background: rgba(255, 255, 255, .12);
  color: #ffffff;
  border: 0;
}

.post-list-page .dataTables_paginate .paginate_button.disabled,
.post-list-page .dataTables_paginate .paginate_button.disabled:hover {
  opacity: .35;
  background: rgba(255, 255, 255, .045);
  color: rgba(255, 255, 255, .45);
  cursor: default;
  pointer-events: none;
  box-shadow: none;
}

/* ================================
   Post Create / Edit Form
================================ */

.post-form-page .main-content {
  padding-top: 28px;
  padding-bottom: 48px;
}

.post-form-page .card-header {
  margin-bottom: 28px;
  padding-left: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.post-form-page .nav-tabs {
  margin-bottom: 24px;
}

.post-form-page .mb-6,
.post-form-page .mb-3 {
  margin-bottom: 18px;
}

.post-form-page select[name="category_ids[]"],
.post-form-page #post-category_ids {
  height: 42px;
  min-height: 42px;
  max-height: 42px;
  overflow: hidden;
}

.post-form-page .select2-container {
  width: 100%;
}

.post-form-page .select2-container .select2-selection--multiple {
  min-height: 42px;
  max-height: 42px;
  overflow: hidden;
  display: flex;
  align-items: center;
}

.post-form-page .select2-container .select2-selection__rendered {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
  gap: 4px;
  overflow-x: auto;
  padding: 4px 8px;
}

.post-form-page input[type="file"] {
  width: 100%;
  height: 42px;
  border: 1px solid var(--cms-border);
  border-radius: 6px;
  background: transparent;
  color: var(--bs-body-color);
}

.post-form-page input[type="file"]::file-selector-button {
  height: 42px;
  padding: 0 16px;
  margin-right: 12px;
  border: 0;
  border-right: 1px solid rgba(255, 255, 255, .16);
  background: rgba(255, 255, 255, .08);
  color: var(--bs-body-color);
}

/* ================================
   Responsive
================================ */

@media (max-width: 768px) {
  .post-page-header,
  .post-filter-row,
  .post-list-page .dataTables_wrapper > .row:first-child,
  .post-list-page .dataTables_wrapper > .row:last-child {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .post-filter-row .form-select,
  .post-list-page .dataTables_filter input {
    width: 100%;
  }

  .post-list-page .dataTables_wrapper > .row:last-child > div:first-child,
  .post-list-page .dataTables_wrapper > .row:last-child > div:last-child {
    max-width: 100%;
    width: 100%;
  }

  .post-list-page .dataTables_paginate {
    justify-content: flex-start;
    flex-wrap: wrap;
  }
}
/* =====================================================
   FIX Pagination - compact / normal style
===================================================== */

.post-list-page .dataTables_wrapper > .row:last-child {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  margin: 20px 0 0 0 !important;
  padding: 0 !important;
  background: transparent !important;
  border-radius: 0 !important;
}

.post-list-page .dataTables_wrapper > .row:last-child > div:first-child,
.post-list-page .dataTables_wrapper > .row:last-child > div:last-child {
  width: auto !important;
  max-width: none !important;
  flex: 0 0 auto !important;
  padding: 0 !important;
  margin: 0 !important;
}

.post-list-page .dataTables_info {
  margin: 0 !important;
  padding: 0 !important;
  color: rgba(255, 255, 255, 0.55) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
  line-height: 34px !important;
  white-space: nowrap !important;
}

.post-list-page .dataTables_paginate {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
  gap: 6px !important;
  margin: 0 !important;
  padding: 0 !important;
  float: none !important;
}

.post-list-page .dataTables_paginate .paginate_button {
  min-width: 34px !important;
  height: 34px !important;
  padding: 0 10px !important;
  margin: 0 !important;

  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;

  border-radius: 6px !important;
  border: 1px solid rgba(255, 255, 255, 0.08) !important;
  background: rgba(255, 255, 255, 0.04) !important;

  color: rgba(255, 255, 255, 0.68) !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  line-height: 1 !important;
  text-decoration: none !important;

  box-shadow: none !important;
  cursor: pointer !important;
}

.post-list-page .dataTables_paginate .paginate_button.current,
.post-list-page .dataTables_paginate .paginate_button.current:hover {
  background: #0d6efd !important;
  border-color: #0d6efd !important;
  color: #ffffff !important;
  box-shadow: none !important;
}

.post-list-page .dataTables_paginate .paginate_button:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.14) !important;
  color: #ffffff !important;
}

.post-list-page .dataTables_paginate .paginate_button.disabled,
.post-list-page .dataTables_paginate .paginate_button.disabled:hover {
  opacity: 0.35 !important;
  background: rgba(255, 255, 255, 0.03) !important;
  border-color: rgba(255, 255, 255, 0.05) !important;
  color: rgba(255, 255, 255, 0.45) !important;
  cursor: default !important;
  pointer-events: none !important;
}

.post-list-page .dataTables_paginate .paginate_button:focus,
.post-list-page .dataTables_paginate .paginate_button:active {
  outline: none !important;
  box-shadow: none !important;
}


/* =====================================================
   DataTables v2 footer - match template pagination
===================================================== */

.post-list-page .dt-layout-row:last-child,
.post-list-page .dataTables_wrapper > .row:last-child,
.post-list-page .row.mx-3.justify-content-between {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  margin: 22px 0 0 0 !important;
  padding: 0 !important;
  background: transparent !important;
  border-radius: 0 !important;
}

/* Left info */
.post-list-page .dt-layout-start {
  display: flex !important;
  align-items: center !important;
  justify-content: flex-start !important;
  padding: 0 !important;
  margin: 0 !important;
}

/* Right pagination */
.post-list-page .dt-layout-end {
  display: flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
  padding: 0 !important;
  margin: 0 !important;
}

/* Text: Hiển thị 1 đến 10 của 80 dòng */
.post-list-page .dt-info {
  color: rgba(255, 255, 255, 0.55) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
  line-height: 40px !important;
  padding: 0 !important;
  margin: 0 !important;
  white-space: nowrap !important;
}

/* Pagination wrapper */
.post-list-page .dt-paging {
  display: flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
}

/* ul.pagination */
.post-list-page .dt-paging .pagination {
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  margin: 0 !important;
  padding: 0 !important;
}

/* li */
.post-list-page .dt-paging .page-item {
  margin: 0 !important;
  padding: 0 !important;
}

/* button.page-link */
.post-list-page .dt-paging .page-link {
  width: 44px !important;
  height: 44px !important;
  min-width: 44px !important;
  padding: 0 !important;

  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;

  border: 0 !important;
  border-radius: 8px !important;
  background: rgba(255, 255, 255, 0.06) !important;

  color: rgba(255, 255, 255, 0.72) !important;
  font-size: 18px !important;
  font-weight: 700 !important;
  line-height: 1 !important;
  text-decoration: none !important;

  box-shadow: none !important;
  outline: none !important;
}

/* Icon inside first/prev/next/last */
.post-list-page .dt-paging .page-link i {
  font-size: 18px !important;
  line-height: 1 !important;
}

/* Active page */
.post-list-page .dt-paging .page-item.active .page-link {
  background: #0f9f95 !important;
  color: #ffffff !important;
  box-shadow: none !important;
}

/* Hover */
.post-list-page .dt-paging .page-item:not(.disabled):not(.active) .page-link:hover {
  background: rgba(255, 255, 255, 0.12) !important;
  color: #ffffff !important;
}

/* Disabled */
.post-list-page .dt-paging .page-item.disabled .page-link {
  opacity: 0.45 !important;
  background: rgba(255, 255, 255, 0.04) !important;
  color: rgba(255, 255, 255, 0.45) !important;
  cursor: default !important;
  pointer-events: none !important;
}

/* Remove browser focus */
.post-list-page .dt-paging .page-link:focus,
.post-list-page .dt-paging .page-link:active {
  box-shadow: none !important;
  outline: none !important;
}
</style><style>
/* ================================
   Scope:
   - .post-list-page: /post
   - .post-form-page: /post/create, /post/edit
================================ */

/* ================================
   Post / Service List Page
================================ */

.post-list-page .main-content {
  padding-bottom: 48px;
}

.post-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.post-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.post-page-title .material-icons-outlined {
  font-size: 24px;
}

.post-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.post-create-btn .material-icons-outlined {
  font-size: 21px;
}

.post-filter-row {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.post-filter-row .form-select {
  width: 250px;
  height: 42px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-text);
  background-color: transparent;
  border: 1px solid var(--cms-border);
}

.post-table-card {
  width: 100%;
  overflow: visible;
}

/* ================================
   DataTables Wrapper
================================ */

.post-list-page .dt-container,
.post-list-page .dataTables_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.post-list-page .table-responsive {
  overflow-x: visible;
}

.post-list-page .row,
.post-list-page .dt-layout-row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.post-list-page .px-3,
.post-list-page .mx-3 {
  padding-left: 0;
  padding-right: 0;
  margin-left: 0;
  margin-right: 0;
}

.post-list-page .pb-3 {
  padding-bottom: 0;
}

/* ================================
   Top Controls
================================ */

.post-list-page .dt-layout-row:first-child,
.post-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.post-list-page .dt-length label,
.post-list-page .dt-search label,
.post-list-page .dataTables_length label,
.post-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.post-list-page .dt-length select,
.post-list-page .dt-search input,
.post-list-page .dataTables_length select,
.post-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.post-list-page .dt-length select,
.post-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.post-list-page .dt-search,
.post-list-page .dataTables_filter {
  text-align: right;
}

.post-list-page .dt-search input,
.post-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* ================================
   Table
================================ */

.post-list-page #reload-table {
  width: 100%;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.post-list-page #reload-table thead th {
  padding: 15px 14px;
  color: var(--cms-heading);
  font-size: 15.5px;
  font-weight: 800;
  letter-spacing: .035em;
  text-transform: uppercase;
  border-top: 1px solid var(--cms-border);
  border-bottom: 1px solid var(--cms-border);
  border-left: 0;
  border-right: 0;
  background: transparent;
  white-space: nowrap;
}

.post-list-page #reload-table tbody td {
  padding: 16px 14px;
  color: var(--cms-text);
  font-size: 16px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid var(--cms-border-soft);
  border-left: 0;
  border-right: 0;
  background: transparent;
  box-sizing: border-box;
}

.post-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg);
}

/* Column widths */
.post-list-page .post-index-col {
  width: 90px;
}

.post-list-page .post-name-col {
  width: 28%;
}

.post-list-page .post-category-col {
  width: 28%;
}

.post-list-page .post-status-col {
  width: 18%;
}

.post-list-page .post-action-col {
  width: 16%;
}

/* ================================
   Name + Meta Icons
================================ */

.post-name-with-icons {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  width: 100%;
  min-width: 0;
}

.post-name-text {
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.post-name-icons {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  flex: 0 0 auto;
}

.post-name-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none;
  opacity: .9;
}

.post-name-icon .material-icons-outlined {
  font-size: 18px;
  line-height: 1;
}

.post-name-icon-link {
  color: #22d3ee;
}

.post-name-icon-info {
  color: var(--cms-muted);
}

.post-name-icon:hover {
  opacity: 1;
}

/* ================================
   Category Badge
================================ */

.post-category-badge {
  display: inline-flex;
  align-items: center;
  max-width: 100%;
  padding: 6px 10px;
  margin: 2px 5px 2px 0;
  border-radius: 999px;
  color: #22d3ee;
  background: rgba(34, 211, 238, .12);
  font-size: 13.5px;
  font-weight: 700;
  line-height: 1.2;
}

/* ================================
   Status + Actions
================================ */

.status-toggle-wide {
  width: 2.5em;
  min-width: 2.5em;
}

.post-status-toggle {
  cursor: pointer;
}

.post-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
}

.post-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  text-decoration: none;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  transition: transform .18s ease, background-color .18s ease, opacity .18s ease;
}

.post-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.post-action-edit {
  color: #22d3ee;
}

.post-action-delete {
  color: #f87171;
}

.post-action-icon:hover {
  opacity: .9;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .075);
}

/* ================================
   DataTables v2 Footer
   Pagination dạng khung liền nhau
================================ */

.post-list-page .dt-layout-row:last-child,
.post-list-page .row.mx-3.justify-content-between,
.post-list-page .dataTables_wrapper > .row:last-child {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 22px 0 0;
  padding: 0;
  background: transparent;
  border-radius: 0;
}

.post-list-page .dt-layout-start,
.post-list-page .dt-layout-end {
  display: flex;
  align-items: center;
  padding: 0;
  margin: 0;
}

.post-list-page .dt-layout-start {
  justify-content: flex-start;
}

.post-list-page .dt-layout-end {
  justify-content: flex-end;
}

.post-list-page .dt-info {
  color: rgba(255, 255, 255, .55);
  font-size: 15px;
  font-weight: 500;
  line-height: 38px;
  padding: 0;
  margin: 0;
  white-space: nowrap;
}

.post-list-page .dt-paging {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.post-list-page .dt-paging .pagination {
  display: inline-flex;
  align-items: center;
  gap: 0;
  margin: 0;
  padding: 0;
  border: 2px solid rgba(255, 255, 255, .18);
  border-radius: 999px;
  overflow: hidden;
  background: transparent;
}

.post-list-page .dt-paging .page-item {
  margin: 0;
  padding: 0;
}

.post-list-page .dt-paging .page-link {
  width: 46px;
  height: 42px;
  min-width: 46px;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 0;
  border-right: 2px solid rgba(255, 255, 255, .18);
  border-radius: 0;
  background: transparent;
  color: #79b3ff;
  font-size: 18px;
  font-weight: 700;
  line-height: 1;
  text-decoration: none;
  box-shadow: none;
  outline: none;
}

.post-list-page .dt-paging .page-item:last-child .page-link {
  border-right: 0;
}

.post-list-page .dt-paging .page-item.active .page-link {
  background: #0d6efd;
  color: #ffffff;
}

.post-list-page .dt-paging .page-item:not(.disabled):not(.active) .page-link:hover {
  background: rgba(255, 255, 255, .08);
  color: #ffffff;
}

.post-list-page .dt-paging .page-item.disabled .page-link {
  opacity: .45;
  color: rgba(255, 255, 255, .45);
  cursor: default;
  pointer-events: none;
}

.post-list-page .dt-paging .page-link i {
  font-size: 18px;
  line-height: 1;
}

/* ================================
   Post Create / Edit Form
================================ */

.post-form-page .main-content {
  padding-top: 28px;
  padding-bottom: 48px;
}

.post-form-page .card-header {
  margin-bottom: 28px;
  padding-left: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.post-form-page .nav-tabs {
  margin-bottom: 24px;
}

.post-form-page .mb-6,
.post-form-page .mb-3 {
  margin-bottom: 18px;
}

.post-form-page select[name="category_ids[]"],
.post-form-page #post-category_ids {
  height: 42px;
  min-height: 42px;
  max-height: 42px;
  overflow: hidden;
}

.post-form-page .select2-container {
  width: 100%;
}

.post-form-page .select2-container .select2-selection--multiple {
  min-height: 42px;
  max-height: 42px;
  overflow: hidden;
  display: flex;
  align-items: center;
}

.post-form-page .select2-container .select2-selection__rendered {
  display: flex;
  align-items: center;
  flex-wrap: nowrap;
  gap: 4px;
  overflow-x: auto;
  padding: 4px 8px;
}

.post-form-page input[type="file"] {
  width: 100%;
  height: 42px;
  border: 1px solid var(--cms-border);
  border-radius: 6px;
  background: transparent;
  color: var(--bs-body-color);
}

.post-form-page input[type="file"]::file-selector-button {
  height: 42px;
  padding: 0 16px;
  margin-right: 12px;
  border: 0;
  border-right: 1px solid rgba(255, 255, 255, .16);
  background: rgba(255, 255, 255, .08);
  color: var(--bs-body-color);
}

/* ================================
   Responsive
================================ */

@media (max-width: 768px) {
  .post-page-header,
  .post-filter-row,
  .post-list-page .dt-layout-row:first-child,
  .post-list-page .dt-layout-row:last-child {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .post-filter-row .form-select,
  .post-list-page .dt-search input {
    width: 100%;
  }

  .post-list-page .dt-paging .pagination {
    flex-wrap: wrap;
    border-radius: 8px;
  }
}
.doctor-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
}

.doctor-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  text-decoration: none;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  transition: transform .18s ease, background-color .18s ease, opacity .18s ease;
}

.doctor-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.doctor-action-edit {
  color: #22d3ee;
}

.doctor-action-delete {
  color: #f87171;
}

.doctor-action-icon:hover {
  opacity: .9;
  transform: translateY(-1px);
  background: rgba(255, 255, 255, .075);
}

.doctor-avatar {
  width: 44px;
  height: 44px;
  object-fit: cover;
  border-radius: 8px;
  display: inline-block;
}
</style>