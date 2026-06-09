<style>
html[data-bs-theme="semi-dark"] {
  --cms-page-bg: #f4f6f9;
  --cms-card-bg: #ffffff;

  --cms-text: #0f172a;
  --cms-heading: #020617;
  --cms-muted: #334155;

  --cms-border: #d6dee8;
  --cms-border-soft: #e2e8f0;
  --cms-hover-bg: #eef4fb;

  --cms-input-bg: #ffffff;
}

html[data-bs-theme="dark"],
html[data-bs-theme="blue-theme"] {
  --cms-page-bg: #151a1f;
  --cms-card-bg: transparent;
  --cms-text: #e5e7eb;
  --cms-heading: #f8fafc;
  --cms-muted: rgba(255, 255, 255, .58);
  --cms-border: rgba(255, 255, 255, .16);
  --cms-border-soft: rgba(255, 255, 255, .075);
  --cms-hover-bg: rgba(255, 255, 255, .035);
  --cms-input-bg: transparent;
}
</style>

<style>
html[data-bs-theme="semi-dark"] body,
html[data-bs-theme="semi-dark"] .main-wrapper,
html[data-bs-theme="semi-dark"] .main-wrapper .main-content {
  background: var(--cms-page-bg) !important;
}
</style>
<style>
html[data-bs-theme="semi-dark"] .main-wrapper {
  --cms-text: #0f172a;
  --cms-heading: #020617;
  --cms-muted: #475569;
  --cms-border: #dbe3ef;
  --cms-border-soft: #e9eef5;
  --cms-hover-bg: #f8fafc;
}

/* Chữ đậm và rõ hơn */
html[data-bs-theme="semi-dark"] .main-wrapper h1,
html[data-bs-theme="semi-dark"] .main-wrapper h2,
html[data-bs-theme="semi-dark"] .main-wrapper h3,
html[data-bs-theme="semi-dark"] .main-wrapper h4,
html[data-bs-theme="semi-dark"] .main-wrapper h5,
html[data-bs-theme="semi-dark"] .main-wrapper h6 {
  color: var(--cms-heading) !important;
  font-weight: 800 !important;
}

html[data-bs-theme="semi-dark"] .main-wrapper table thead th {
  color: var(--cms-heading) !important;
  font-weight: 800 !important;
}

html[data-bs-theme="semi-dark"] .main-wrapper table tbody td {
  color: var(--cms-text) !important;
  font-weight: 650 !important;
}

html[data-bs-theme="semi-dark"] .main-wrapper .dataTables_info,
html[data-bs-theme="semi-dark"] .main-wrapper .dt-info,
html[data-bs-theme="semi-dark"] .main-wrapper label {
  color: var(--cms-muted) !important;
  font-weight: 600 !important;
}

/* Cột hành động: bọc nút edit/delete */
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child a,
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child button {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 36px !important;
  height: 36px !important;
  margin: 0 4px !important;
  border-radius: 10px !important;
  border: 1px solid var(--cms-border) !important;
  background: #ffffff !important;
  text-decoration: none !important;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06) !important;
  transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-color .18s ease !important;
}

/* Hover phình nhẹ */
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child a:hover,
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child button:hover {
  transform: scale(1.12) !important;
  box-shadow: 0 10px 22px rgba(15, 23, 42, 0.14) !important;
}

/* Edit hover */
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child a[href*="edit"]:hover {
  background: rgba(6, 182, 212, .10) !important;
  border-color: rgba(6, 182, 212, .45) !important;
}

/* Delete hover */
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child button:hover,
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child .delete-btn:hover,
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child .btn-delete:hover {
  background: rgba(239, 68, 68, .10) !important;
  border-color: rgba(239, 68, 68, .45) !important;
}

/* Giữ icon rõ */
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child i,
html[data-bs-theme="semi-dark"] .main-wrapper table tbody td:last-child .material-icons-outlined {
  font-size: 19px !important;
  line-height: 1 !important;
  opacity: 1 !important;
}
/* CMS Light Table Polish */
.main-content,
.main-wrapper {
  color: #111827;
}

.main-content h1,
.main-content h2,
.main-content h3,
.main-content h4,
.main-content h5,
.page-title,
.product-page-title,
.menu-page-title,
.doctor-page-title {
  color: #111827 !important;
  font-weight: 800 !important;
}

.dataTables_length label,
.dataTables_filter label,
.dt-length label,
.dt-search label {
  color: #334155 !important;
  font-weight: 700 !important;
}

#reload-table thead th {
  color: #111827 !important;
  font-weight: 900 !important;
  opacity: 1 !important;
  letter-spacing: .35px;
  border-color: #dbe3ef !important;
}

#reload-table tbody td {
  color: #1f2937 !important;
  font-weight: 700 !important;
  opacity: 1 !important;
  border-color: #e5edf6 !important;
}

#reload-table tbody tr:hover td {
  background: rgba(59, 130, 246, .045) !important;
}

#reload-table .text-muted,
.text-muted {
  color: #64748b !important;
  opacity: 1 !important;
  font-weight: 600 !important;
}

.badge,
.bg-label-primary,
.product-category-badge,
.category-type-badge,
.menu-position-badge {
  background: #dbeafe !important;
  color: #1d4ed8 !important;
  font-weight: 800 !important;
  opacity: 1 !important;
}

.product-price {
  color: #eab308 !important;
  font-weight: 900 !important;
}

.product-price-discount {
  color: #374151 !important;
  font-weight: 800 !important;
}

.product-name-text,
.menu-name,
.doctor-name-text,
#reload-table tbody td:nth-child(2) {
  color: #111827 !important;
  font-weight: 900 !important;
}

.product-meta-trigger i,
.menu-meta-trigger i,
.doctor-meta-trigger i,
.product-name-icon-info,
.menu-name-icon-info {
  color: #475569 !important;
  opacity: 1 !important;
}

.product-meta-trigger:hover i,
.menu-meta-trigger:hover i,
.doctor-meta-trigger:hover i,
.product-name-icon-info:hover,
.menu-name-icon-info:hover {
  color: #2563eb !important;
}

.popover,
.popover-body {
  color: #111827 !important;
  font-weight: 600 !important;
}

.popover-body strong {
  color: #111827 !important;
  font-weight: 900 !important;
}

.product-action-icon,
.menu-action-icon,
.doctor-action-icon,
.category-action-icon,
.post-action-icon,
.user-action-icon,
.role-action-icon,
.degree-action-icon,
.contact-action-icon,
.comment-action-icon,
.service-registration-action-icon {
  width: 42px !important;
  height: 42px !important;
  border-radius: 12px !important;
  background: #ffffff !important;
  border: 1px solid #dbe3ef !important;
  box-shadow: 0 4px 12px rgba(15, 23, 42, .06) !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  text-decoration: none !important;
  transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background-color .2s ease !important;
}

.product-action-icon:hover,
.menu-action-icon:hover,
.doctor-action-icon:hover,
.category-action-icon:hover,
.post-action-icon:hover,
.user-action-icon:hover,
.role-action-icon:hover,
.degree-action-icon:hover,
.contact-action-icon:hover,
.comment-action-icon:hover,
.service-registration-action-icon:hover {
  transform: scale(1.12);
  box-shadow: 0 10px 24px rgba(15, 23, 42, .14) !important;
  border-color: #bfdbfe !important;
  background: #f8fbff !important;
}

.product-action-edit,
.menu-action-edit,
.doctor-action-edit,
.category-action-edit,
.post-action-edit,
.user-action-edit,
.role-action-edit,
.degree-action-edit,
.contact-action-edit,
.comment-action-edit,
.service-registration-action-edit {
  color: #06b6d4 !important;
}

.product-action-delete,
.menu-action-delete,
.doctor-action-delete,
.category-action-delete,
.post-action-delete,
.user-action-delete,
.role-action-delete,
.degree-action-delete,
.contact-action-delete,
.comment-action-delete,
.service-registration-action-delete {
  color: #ef4444 !important;
}

.product-action-icon i,
.product-action-icon span,
.menu-action-icon i,
.menu-action-icon span,
.doctor-action-icon i,
.doctor-action-icon span {
  font-size: 20px !important;
  line-height: 1 !important;
}

.dataTables_info,
.dt-info {
  color: #475569 !important;
  font-weight: 700 !important;
  opacity: 1 !important;
}

/* ================================
   CMS Light Surface / Card System
================================ */

html[data-bs-theme="semi-dark"] .main-wrapper,
html[data-bs-theme="semi-dark"] .main-content {
  background: #f4f6f9 !important;
}

/* Card chính của form/list */
html[data-bs-theme="semi-dark"] .card,
html[data-bs-theme="semi-dark"] .product-table-card,
html[data-bs-theme="semi-dark"] .doctor-table-card,
html[data-bs-theme="semi-dark"] .category-table-card,
html[data-bs-theme="semi-dark"] .menu-table-card,
html[data-bs-theme="semi-dark"] .post-table-card,
html[data-bs-theme="semi-dark"] .role-table-card,
html[data-bs-theme="semi-dark"] .user-table-card,
html[data-bs-theme="semi-dark"] .degree-table-card {
  background: #ffffff !important;
  border: 1px solid #dbe3ef !important;
  border-radius: 16px !important;
  box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06) !important;
}

/* Nội dung card */
html[data-bs-theme="semi-dark"] .card-body {
  background: transparent !important;
}

/* Section trong form */
html[data-bs-theme="semi-dark"] .product-section,
html[data-bs-theme="semi-dark"] .doctor-section,
html[data-bs-theme="semi-dark"] .degree-section,
html[data-bs-theme="semi-dark"] .category-section,
html[data-bs-theme="semi-dark"] .post-section,
html[data-bs-theme="semi-dark"] .menu-section,
html[data-bs-theme="semi-dark"] .setting-section {
  background: #ffffff !important;
  border: 1px solid #dbe3ef !important;
  border-radius: 14px !important;
  padding: 20px !important;
  box-shadow: 0 6px 18px rgba(15, 23, 42, 0.045) !important;
}

/* Title section */
html[data-bs-theme="semi-dark"] .product-section-title,
html[data-bs-theme="semi-dark"] .doctor-section-title,
html[data-bs-theme="semi-dark"] .degree-section-title,
html[data-bs-theme="semi-dark"] .category-section-title,
html[data-bs-theme="semi-dark"] .post-section-title,
html[data-bs-theme="semi-dark"] .menu-section-title,
html[data-bs-theme="semi-dark"] .setting-section-title {
  color: #b7791f !important;
  font-size: 15px !important;
  font-weight: 900 !important;
  padding-bottom: 12px !important;
  margin-bottom: 18px !important;
  border-bottom: 1px solid #e5eaf2 !important;
}

/* Input nhìn nổi trên card */
html[data-bs-theme="semi-dark"] .form-control,
html[data-bs-theme="semi-dark"] .form-select,
html[data-bs-theme="semi-dark"] textarea {
  background: #ffffff !important;
  border: 1px solid #d5deea !important;
  color: #0f172a !important;
  border-radius: 10px !important;
  font-weight: 600 !important;
}

html[data-bs-theme="semi-dark"] .form-control:focus,
html[data-bs-theme="semi-dark"] .form-select:focus,
html[data-bs-theme="semi-dark"] textarea:focus {
  border-color: #0d6efd !important;
  box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12) !important;
}

/* Label rõ hơn */
html[data-bs-theme="semi-dark"] label,
html[data-bs-theme="semi-dark"] .form-label {
  color: #1e293b !important;
  font-weight: 800 !important;
}
</style>