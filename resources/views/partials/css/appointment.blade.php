<style>
.appointment-list-page .main-content {
  padding-bottom: 88px;
}

.appointment-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.appointment-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.appointment-page-title .material-icons-outlined {
  font-size: 24px;
}

.appointment-table-card {
  width: 100%;
  overflow: visible;
}

.appointment-list-page .dataTables_wrapper,
.appointment-list-page #reload-table_wrapper {
  width: 100%;
  max-width: 100%;
  overflow: visible;
}

.appointment-list-page .table-responsive {
  overflow-x: visible;
}

.appointment-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.appointment-list-page .px-3 {
  padding-left: 0 !important;
  padding-right: 0 !important;
}

.appointment-list-page .pb-3 {
  padding-bottom: 0 !important;
}

.appointment-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.appointment-list-page .dataTables_length label,
.appointment-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.appointment-list-page .dataTables_length select,
.appointment-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.appointment-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.appointment-list-page .dataTables_filter {
  text-align: right;
}

.appointment-list-page .dataTables_filter input {
  width: 220px;
  margin-left: 10px;
  padding: 6px 12px;
}

.appointment-list-page #reload-table {
  width: 100% !important;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.appointment-list-page #reload-table thead th {
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

.appointment-list-page #reload-table tbody td {
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

.appointment-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg) !important;
}

.appointment-list-page #reload-table thead th::before,
.appointment-list-page #reload-table thead th::after,
.appointment-list-page #reload-table thead .dt-column-order {
  display: none !important;
  content: none !important;
}

.appointment-index-col {
  width: 70px;
}

.appointment-customer-col {
  width: 18%;
}

.appointment-contact-col {
  width: 20%;
}

.appointment-service-col {
  width: 22%;
}

.appointment-status-col {
  width: 14%;
}

.appointment-message-col {
  width: 18%;
}

.appointment-date-col {
  width: 16%;
}

.appointment-action-col {
  width: 110px;
}

.appointment-contact {
  display: flex;
  flex-direction: column;
  gap: 4px;
  line-height: 1.35;
}

.appointment-contact .small {
  color: rgba(255,255,255,.58) !important;
}

.appointment-message-text {
  display: inline-block;
  max-width: 100%;
  color: var(--cms-text);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.appointment-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
}

.appointment-date strong {
  color: var(--cms-heading);
  font-weight: 800;
}

.appointment-date small {
  color: rgba(255,255,255,.58);
}

.appointment-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: 100%;
  white-space: nowrap;
}

.appointment-action-icon {
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

.appointment-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.appointment-action-edit {
  color: #22d3ee !important;
}

.appointment-action-delete {
  color: #f87171 !important;
}

.appointment-action-icon:hover {
  opacity: .9;
  background: rgba(255, 255, 255, .075);
}

.appointment-list-page .dataTables_info,
.appointment-list-page .dt-info {
  color: var(--cms-muted) !important;
  font-size: 15px !important;
  font-weight: 500 !important;
}
</style>