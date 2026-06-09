<style>
.comment-list-page .main-content {
  padding-bottom: 48px;
}

/* Header */
.comment-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.comment-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.comment-page-title .material-icons-outlined {
  font-size: 24px;
}

.comment-table-card {
  width: 100%;
  overflow: visible;
}

/* DataTables wrapper */
.comment-list-page .dataTables_wrapper,
.comment-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.comment-list-page .table-responsive {
  overflow-x: visible;
}

.comment-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.comment-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.comment-list-page .pb-3 {
  padding-bottom: 0 !important;
}

/* Top controls */
.comment-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.comment-list-page .dataTables_length label,
.comment-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.comment-list-page .dataTables_length select,
.comment-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.comment-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.comment-list-page .dataTables_filter {
  text-align: right;
}

.comment-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* Table */
.comment-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.comment-list-page #reload-table thead th {
  padding: 15px 12px;
  color: var(--cms-heading) !important;
  font-size: 15px;
  font-weight: 800;
  letter-spacing: .03em;
  text-transform: uppercase;
  border-top: 1px solid var(--cms-border);
  border-bottom: 1px solid var(--cms-border);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  white-space: nowrap;
}

.comment-list-page #reload-table tbody td {
  padding: 16px 12px;
  color: var(--cms-text) !important;
  font-size: 15px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid var(--cms-border-soft);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  box-sizing: border-box;
}

.comment-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg) !important;
}

/* Bỏ icon sort */
.comment-list-page #reload-table thead th::before,
.comment-list-page #reload-table thead th::after,
.comment-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

/* Columns */
.comment-name-col {
  width: 20%;
}

.comment-content-col {
  width: 38%;
}

.comment-date-col {
  width: 18%;
}

.comment-status-col {
  width: 14%;
}

.comment-action-col {
  width: 110px;
}

/* Content */
.comment-content-text {
  display: inline-block;
  max-width: 100%;
  color: var(--cms-text);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Status */
.comment-status-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 86px;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}

.comment-status-approved {
  color: #86efac;
  background: rgba(34, 197, 94, .14);
}

.comment-status-pending {
  color: #fde68a;
  background: rgba(245, 158, 11, .14);
}

.comment-status-hidden {
  color: #fca5a5;
  background: rgba(248, 113, 113, .14);
}

/* Action dropdown */
.comment-action-wrap {
  display: inline-flex;
  justify-content: center;
  width: 100%;
}

.comment-action-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border: 1px solid rgba(255, 255, 255, .08);
  border-radius: 9px;
  background: rgba(255, 255, 255, .03);
  color: var(--cms-text);
  box-shadow: none;
}

.comment-action-btn:hover,
.comment-action-btn:focus {
  background: rgba(255, 255, 255, .075);
  color: #ffffff;
}

.comment-action-btn .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.comment-list-page .dropdown-menu {
  border: 1px solid rgba(255, 255, 255, .10);
  background: #1f2937;
  box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
}

.comment-list-page .dropdown-item {
  color: var(--cms-text);
  font-size: 14px;
  font-weight: 600;
}

.comment-list-page .dropdown-item:hover {
  color: #ffffff;
  background: rgba(255, 255, 255, .08);
}

.comment-list-page .dropdown-item.text-danger {
  color: #f87171 !important;
}

/* Modal dark */
.comment-modal .modal-content {
  border: 1px solid rgba(255, 255, 255, .10);
  background: #1f2937;
  color: var(--cms-text);
}

.comment-modal .modal-header,
.comment-modal .modal-footer {
  border-color: rgba(255, 255, 255, .10);
}

.comment-modal .form-control,
.comment-modal textarea {
  color: var(--cms-heading);
  background-color: transparent;
  border-color: rgba(255, 255, 255, .16);
}

.comment-parent-box,
.comment-reply-box {
  border: 1px solid rgba(255, 255, 255, .10);
  border-radius: 10px;
  background: rgba(255, 255, 255, .035);
}

.comment-reply-box {
  border-left: 3px solid #22d3ee;
}

/* Info + pagination */
.comment-list-page .dataTables_info,
.comment-list-page .dt-info {
  color: var(--cms-muted) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}

.comment-list-page .dt-paging .pagination {
  display: flex !important;
  align-items: center !important;
  gap: 0 !important;
  margin: 0 !important;
  padding: 0 !important;
  border: 1px solid rgba(255, 255, 255, .12);
  border-radius: 999px;
  overflow: hidden;
}

.comment-list-page .dt-paging .page-link,
.comment-list-page .pagination .page-link {
  width: 42px !important;
  height: 40px !important;
  min-width: 42px !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 0 !important;
  background: transparent !important;
  color: var(--cms-muted) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  box-shadow: none !important;
}

.comment-list-page .dt-paging .page-item.active .page-link,
.comment-list-page .pagination .page-item.active .page-link {
  background: #0d6efd !important;
  color: #ffffff !important;
}
</style>