<style>
.user-list-page .main-content,
.user-form-page .main-content {
  padding-bottom: 88px;
}

.user-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.user-page-title,
.user-form-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #f8fafc;
  font-size: 22px;
  font-weight: 700;
}

.user-page-title .material-icons-outlined {
  font-size: 24px;
}

.user-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.user-table-card,
.user-list-page .dataTables_wrapper,
.user-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.user-list-page .table-responsive {
  overflow-x: visible;
}

.user-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.user-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.user-list-page .pb-3 {
  padding-bottom: 0 !important;
}

.user-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.user-list-page .dataTables_length label,
.user-list-page .dataTables_filter label {
  color: #e5e7eb;
  font-size: 15px;
  font-weight: 600;
}

.user-list-page .dataTables_length select,
.user-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: #f8fafc;
  background-color: transparent;
  border: 1px solid rgba(255, 255, 255, .16);
  box-shadow: none;
}

.user-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.user-list-page .dataTables_filter {
  text-align: right;
}

.user-list-page .dataTables_filter input {
  width: 220px;
  margin-left: 10px;
  padding: 6px 12px;
}

.user-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.user-list-page #reload-table thead th {
  padding: 15px 14px;
  color: #f8fafc !important;
  font-size: 15px;
  font-weight: 800;
  letter-spacing: .03em;
  text-transform: uppercase;
  border-top: 1px solid rgba(255, 255, 255, .12);
  border-bottom: 1px solid rgba(255, 255, 255, .12);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
  white-space: nowrap;
}

.user-list-page #reload-table tbody td {
  padding: 16px 14px;
  color: #e5e7eb !important;
  font-size: 15px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid rgba(255, 255, 255, .075);
  border-left: 0 !important;
  border-right: 0 !important;
  background: transparent !important;
}

.user-list-page #reload-table tbody tr:hover td {
  background-color: rgba(255, 255, 255, .035) !important;
}

.user-list-page #reload-table thead th::before,
.user-list-page #reload-table thead th::after,
.user-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

.user-name-col {
  width: 22%;
}

.user-email-col {
  width: 30%;
}

.user-role-col {
  width: 18%;
}

.user-meta-col {
  width: 120px;
}

.user-action-col {
  width: 130px;
}

.user-meta-trigger {
  color: rgba(255,255,255,0.65) !important;
  box-shadow: none !important;
}

.user-meta-trigger i {
  font-size: 1.05rem;
  line-height: 1;
}

.user-meta-trigger:hover {
  opacity: 0.9;
}

.user-meta-popover {
  min-width: 200px;
  font-size: 13px;
  line-height: 1.5;
}

.user-meta-row {
  display: flex;
  gap: 6px;
  align-items: center;
  white-space: nowrap;
}

.user-meta-row + .user-meta-row {
  margin-top: 4px;
}

.user-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.user-action-icon {
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

.user-action-icon .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.user-action-edit {
  color: #22d3ee !important;
}

.user-action-delete {
  color: #f87171 !important;
}

.user-action-icon:hover {
  opacity: .9;
  background: rgba(255, 255, 255, .075);
}

.user-list-page .dataTables_info,
.user-list-page .dt-info {
  color: rgba(255, 255, 255, .58) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}

.user-form-card {
  padding: 0;
}
</style>