<style>
.role-list-page .main-content,
.role-form-page .main-content {
  padding-bottom: 88px;
}

.role-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.role-page-title,
.role-form-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.role-page-title .material-icons-outlined {
  font-size: 24px;
}

.role-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.role-table-card,
.role-list-page .dataTables_wrapper,
.role-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.role-list-page .table-responsive {
  overflow-x: visible;
}

.role-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.role-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.role-list-page .pb-3 {
  padding-bottom: 0 !important;
}

.role-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.role-list-page .dataTables_length label,
.role-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.role-list-page .dataTables_length select,
.role-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.role-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.role-list-page .dataTables_filter {
  text-align: right;
}

.role-list-page .dataTables_filter input {
  width: 220px;
  margin-left: 10px;
  padding: 6px 12px;
}

.role-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.role-list-page #reload-table thead th {
  padding: 15px 14px;
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

.role-list-page #reload-table tbody td {
  padding: 16px 14px;
  color: var(--cms-text) !important;
  font-size: 15px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid var(--cms-border-soft);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
}

.role-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg) !important;
}

.role-list-page #reload-table thead th::before,
.role-list-page #reload-table thead th::after,
.role-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

.role-name-col {
  width: 34%;
}

.role-code-col {
  width: 26%;
}

.role-date-col {
  width: 24%;
}

.role-action-col {
  width: 140px;
}

.role-code-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 10px;
  border-radius: 999px;
  color: #bfdbfe;
  background: rgba(59, 130, 246, .14);
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
}

.role-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.role-action-icon {
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
}

.role-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.role-action-edit {
  color: #22d3ee !important;
}

.role-action-delete {
  color: #f87171 !important;
}

.role-action-icon:hover {
  opacity: .9;
  background: rgba(255, 255, 255, .075);
}

.role-list-page .dataTables_info,
.role-list-page .dt-info {
  color: var(--cms-muted) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}

.role-form-card {
  padding: 0;
}
</style>