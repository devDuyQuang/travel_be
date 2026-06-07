<style>
.service-registration-list-page .main-content {
  padding-bottom: 88px;
}

/* Header */
.service-registration-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.service-registration-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: #f8fafc;
  font-size: 22px;
  font-weight: 700;
}

.service-registration-page-title .material-icons-outlined {
  font-size: 24px;
}

.service-registration-table-card {
  width: 100%;
  overflow: visible;
}

/* DataTables wrapper */
.service-registration-list-page .dataTables_wrapper,
.service-registration-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.service-registration-list-page .table-responsive {
  overflow-x: visible;
}

.service-registration-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.service-registration-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.service-registration-list-page .pb-3 {
  padding-bottom: 0 !important;
}

/* Top controls */
.service-registration-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.service-registration-list-page .dataTables_length label,
.service-registration-list-page .dataTables_filter label {
  color: #e5e7eb;
  font-size: 15px;
  font-weight: 600;
}

.service-registration-list-page .dataTables_length select,
.service-registration-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: #f8fafc;
  background-color: transparent;
  border: 1px solid rgba(255, 255, 255, .16);
  box-shadow: none;
}

.service-registration-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.service-registration-list-page .dataTables_filter {
  text-align: right;
}

.service-registration-list-page .dataTables_filter input {
  width: 220px;
  margin-left: 10px;
  padding: 6px 12px;
}

/* Table */
.service-registration-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.service-registration-list-page #reload-table thead th {
  padding: 15px 12px;
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

.service-registration-list-page #reload-table tbody td {
  padding: 16px 12px;
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

.service-registration-list-page #reload-table tbody tr:hover td {
  background-color: rgba(255, 255, 255, .035) !important;
}

/* Bỏ icon sort */
.service-registration-list-page #reload-table thead th::before,
.service-registration-list-page #reload-table thead th::after,
.service-registration-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

/* Columns */
.service-registration-customer-col {
  width: 18%;
}

.service-registration-contact-col {
  width: 20%;
}

.service-registration-package-col {
  width: 22%;
}

.service-registration-status-col {
  width: 14%;
}

.service-registration-message-col {
  width: 18%;
}

.service-registration-date-col {
  width: 16%;
}

.service-registration-action-col {
  width: 90px;
}

/* Text */
.service-registration-message-text {
  display: inline-block;
  max-width: 100%;
  color: #e5e7eb;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.service-registration-contact {
  display: flex;
  flex-direction: column;
  gap: 4px;
  line-height: 1.35;
}

.service-registration-contact .small {
  color: rgba(255,255,255,.58) !important;
}

.service-registration-package-name {
  color: #93c5fd;
  font-weight: 800;
}

.service-registration-package-price {
  display: inline-flex;
  margin-top: 6px;
  padding: 5px 10px;
  border-radius: 999px;
  color: #bfdbfe;
  background: rgba(59, 130, 246, .14);
  font-size: 13px;
  font-weight: 700;
}

.service-registration-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
}

.service-registration-date strong {
  color: #f8fafc;
  font-weight: 800;
}

.service-registration-date small {
  color: rgba(255,255,255,.58);
}

/* Action */
.service-registration-action-wrap {
  display: inline-flex;
  justify-content: center;
  width: 100%;
}

.service-registration-action-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border: 1px solid rgba(255, 255, 255, .08);
  border-radius: 9px;
  background: rgba(255, 255, 255, .03);
  color: #e5e7eb;
  box-shadow: none;
}

.service-registration-action-btn:hover,
.service-registration-action-btn:focus {
  background: rgba(255, 255, 255, .075);
  color: #ffffff;
}

.service-registration-action-btn .material-icons-outlined {
  font-size: 20px;
  line-height: 1;
}

.service-registration-list-page .dropdown-menu {
  border: 1px solid rgba(255, 255, 255, .10);
  background: #1f2937;
  box-shadow: 0 12px 30px rgba(0, 0, 0, .35);
}

.service-registration-list-page .dropdown-item {
  color: #e5e7eb;
  font-size: 14px;
  font-weight: 600;
}

.service-registration-list-page .dropdown-item:hover {
  color: #ffffff;
  background: rgba(255, 255, 255, .08);
}

.service-registration-list-page .dropdown-item.text-danger {
  color: #f87171 !important;
}

/* Info + pagination */
.service-registration-list-page .dataTables_info,
.service-registration-list-page .dt-info {
  color: rgba(255, 255, 255, .58) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}
</style>