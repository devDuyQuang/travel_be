<style>
/* =====================================================
   Doctor List Page
===================================================== */

.doctor-list-page .main-content {
  padding-bottom: 48px;
}

.doctor-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.doctor-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.doctor-page-title .material-icons-outlined {
  font-size: 24px;
}

.doctor-create-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  height: 44px;
  padding: 0 18px;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 700;
}

.doctor-create-btn .material-icons-outlined {
  font-size: 21px;
}

.doctor-table-card {
  width: 100%;
  overflow: visible;
}

.doctor-list-page .dataTables_wrapper,
.doctor-list-page .table-responsive {
  width: 100%;
  max-width: 100%;
  overflow-x: visible;
}

.doctor-list-page .dataTables_wrapper .row {
  width: 100%;
  margin-left: 0;
  margin-right: 0;
}

.doctor-list-page .px-3 {
  padding-left: 0;
  padding-right: 0;
}

.doctor-list-page .pb-3 {
  padding-bottom: 0;
}

.doctor-list-page .dataTables_wrapper > .row:first-child {
  align-items: center;
  margin-bottom: 22px;
}

.doctor-list-page .dataTables_length label,
.doctor-list-page .dataTables_filter label {
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 600;
}

.doctor-list-page .dataTables_length select,
.doctor-list-page .dataTables_filter input {
  height: 40px;
  min-height: 40px;
  border-radius: 9px;
  font-size: 15px;
  color: var(--cms-heading);
  background-color: transparent;
  border: 1px solid var(--cms-border);
  box-shadow: none;
}

.doctor-list-page .dataTables_length select {
  min-width: 70px;
  margin: 0 8px;
  text-align: center;
}

.doctor-list-page .dataTables_filter {
  text-align: right;
}

.doctor-list-page .dataTables_filter input {
  width: 190px;
  margin-left: 10px;
  padding: 6px 12px;
}

.doctor-list-page #reload-table {
  width: 100%;
  min-width: 0;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
  margin: 0;
}

.doctor-list-page #reload-table thead th {
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

.doctor-list-page #reload-table tbody td {
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

.doctor-list-page #reload-table tbody tr:hover td {
  background-color: var(--cms-hover-bg);
}

/* Column widths */
.doctor-image-col {
  width: 80px;
}

.doctor-name-col {
  width: 20%;
}

.doctor-specialty-col {
  width: 18%;
}

.doctor-birth-col {
  width: 110px;
}

.doctor-phone-col {
  width: 150px;
}

.doctor-social-col {
  width: 140px;
}

.doctor-meta-col {
  width: 60px;
}

.doctor-action-col {
  width: 120px;
}

/* Avatar */
.doctor-avatar {
  width: 44px;
  height: 44px;
  object-fit: cover;
  border-radius: 8px;
}

.doctor-name-text {
  display: inline-block;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Socials */
.doctor-socials {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.doctor-social-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #22d3ee;
  text-decoration: none;
}

.doctor-social-link i {
  font-size: 18px;
}

.doctor-social-link:hover {
  opacity: .85;
}

/* Meta */
.doctor-meta-trigger {
  color: var(--cms-muted);
  box-shadow: none;
}

.doctor-meta-trigger i {
  font-size: 18px;
}

.doctor-meta-popover {
  min-width: 190px;
  font-size: 13px;
  line-height: 1.5;
}

.doctor-meta-row {
  display: flex;
  gap: 6px;
  white-space: nowrap;
}

.doctor-meta-row + .doctor-meta-row {
  margin-top: 4px;
}

/* Actions */
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

.doctor-action-icon i {
  font-size: 18px;
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

/* Bottom */
.doctor-list-page .dataTables_wrapper > .row:last-child {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 20px 0 0 0;
  padding: 0;
  background: transparent;
}

.doctor-list-page .dataTables_info {
  margin: 0;
  padding: 0;
  color: rgba(255, 255, 255, .55);
  font-size: 15px;
  font-weight: 500;
  line-height: 34px;
  white-space: nowrap;
}

.doctor-list-page .dataTables_paginate {
  float: none;
  display: inline-flex;
  align-items: center;
  justify-content: flex-end;
  gap: 6px;
  margin: 0;
  padding: 0;
}

.doctor-list-page .dataTables_paginate .paginate_button {
  min-width: 34px;
  height: 34px;
  padding: 0 10px;
  margin: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .04);
  color: rgba(255, 255, 255, .68);
  font-size: 14px;
  font-weight: 600;
  line-height: 1;
  text-decoration: none;
  box-shadow: none;
  cursor: pointer;
}

.doctor-list-page .dataTables_paginate .paginate_button.current,
.doctor-list-page .dataTables_paginate .paginate_button.current:hover {
  background: #0d6efd;
  border-color: #0d6efd;
  color: #ffffff;
}

.doctor-list-page .dataTables_paginate .paginate_button.disabled,
.doctor-list-page .dataTables_paginate .paginate_button.disabled:hover {
  opacity: .35;
  cursor: default;
  pointer-events: none;
}

/* =====================================================
   Doctor Create / Edit Form
===================================================== */

.doctor-form-page .main-content {
  padding-top: 28px;
  padding-bottom: 48px;
}

.doctor-form-title {
  margin-bottom: 28px;
  padding-left: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.doctor-form-card {
  padding: 0;
}

.doctor-form-page .nav-tabs {
  margin-bottom: 24px;
}

.doctor-form-page .mb-6,
.doctor-form-page .mb-3 {
  margin-bottom: 18px;
}

.doctor-form-page input[type="file"] {
  width: 100%;
  height: 42px;
  border: 1px solid var(--cms-border);
  border-radius: 6px;
  background: transparent;
  color: var(--bs-body-color);
}

.doctor-form-page input[type="file"]::file-selector-button {
  height: 42px;
  padding: 0 16px;
  margin-right: 12px;
  border: 0;
  border-right: 1px solid rgba(255, 255, 255, .16);
  background: rgba(255, 255, 255, .08);
  color: var(--bs-body-color);
}

@media (max-width: 768px) {
  .doctor-page-header,
  .doctor-list-page .dataTables_wrapper > .row:first-child,
  .doctor-list-page .dataTables_wrapper > .row:last-child {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .doctor-list-page .dataTables_filter input {
    width: 100%;
  }

  .doctor-list-page .dataTables_paginate {
    justify-content: flex-start;
    flex-wrap: wrap;
  }
}
</style>