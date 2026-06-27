<style>
:root,
html[data-bs-theme="semi-dark"] {
  --cms-primary: #0f766e;
  --cms-primary-dark: #115e59;
  --cms-primary-soft: #ccfbf1;
  --cms-accent: #f59e0b;
  --cms-danger: #dc2626;
  --cms-page: #f6f8fb;
  --cms-surface: #ffffff;
  --cms-surface-muted: #f8fafc;
  --cms-text: #172033;
  --cms-heading: #0f172a;
  --cms-muted: #64748b;
  --cms-border: #dbe3ed;
  --cms-border-strong: #cbd5e1;
  --cms-shadow: 0 12px 32px rgba(15, 23, 42, .07);
  --cms-radius: 16px;
}

html[data-bs-theme="dark"],
html[data-bs-theme="blue-theme"] {
  --cms-page: #111827;
  --cms-surface: #18212f;
  --cms-surface-muted: #202b3a;
  --cms-text: #e5e7eb;
  --cms-heading: #f8fafc;
  --cms-muted: #a8b3c4;
  --cms-border: #334155;
  --cms-border-strong: #475569;
  --cms-shadow: 0 12px 32px rgba(0, 0, 0, .2);
}

body,
.main-wrapper,
.main-content {
  color: var(--cms-text);
}

html[data-bs-theme="semi-dark"] body,
html[data-bs-theme="semi-dark"] .main-wrapper,
html[data-bs-theme="semi-dark"] .main-content {
  background: var(--cms-page) !important;
}

.main-content {
  padding: 28px;
}

.main-content h1,
.main-content h2,
.main-content h3,
.main-content h4,
.main-content h5,
.main-content h6 {
  color: var(--cms-heading);
  font-weight: 750;
  letter-spacing: -.015em;
}

.main-content .text-muted,
.main-content .form-text {
  color: var(--cms-muted) !important;
}

.card,
[class$="-form-card"],
[class$="-table-card"] {
  background: var(--cms-surface) !important;
  border: 1px solid var(--cms-border) !important;
  border-radius: var(--cms-radius) !important;
  box-shadow: var(--cms-shadow) !important;
}

.card-header {
  padding: 20px 24px;
  background: transparent !important;
  border-bottom: 1px solid var(--cms-border) !important;
}

.card-body {
  padding: 24px;
}

.form-label {
  margin-bottom: 8px;
  color: var(--cms-heading) !important;
  font-size: .875rem;
  font-weight: 650 !important;
}

.form-control,
.form-select,
.select2-container--default .select2-selection {
  min-height: 44px;
  border: 1px solid var(--cms-border-strong) !important;
  border-radius: 10px !important;
  background-color: var(--cms-surface) !important;
  color: var(--cms-text) !important;
  font-weight: 500;
  box-shadow: none !important;
}

textarea.form-control {
  min-height: 110px;
}

.form-control:focus,
.form-select:focus,
.select2-container--focus .select2-selection {
  border-color: var(--cms-primary) !important;
  box-shadow: 0 0 0 3px rgba(15, 118, 110, .13) !important;
}

.btn {
  min-height: 40px;
  border-radius: 10px;
  font-weight: 650;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.btn-primary {
  border-color: var(--cms-primary) !important;
  background: var(--cms-primary) !important;
}

.btn-primary:hover,
.btn-primary:focus {
  border-color: var(--cms-primary-dark) !important;
  background: var(--cms-primary-dark) !important;
}

.btn-outline-primary {
  border-color: var(--cms-primary) !important;
  color: var(--cms-primary) !important;
}

.btn-outline-primary:hover {
  background: var(--cms-primary) !important;
  color: #fff !important;
}

.nav-pills .nav-link {
  border-radius: 10px;
  color: var(--cms-muted);
  font-weight: 600;
}

.nav-pills .nav-link.active {
  background: var(--cms-primary) !important;
  color: #fff !important;
}

.table {
  --bs-table-bg: transparent;
  --bs-table-color: var(--cms-text);
  margin-bottom: 0;
}

.table thead th,
#reload-table thead th {
  padding: 14px 16px;
  border-bottom: 1px solid var(--cms-border-strong) !important;
  background: var(--cms-surface-muted) !important;
  color: var(--cms-heading) !important;
  font-size: .75rem;
  font-weight: 750 !important;
  letter-spacing: .045em;
  text-transform: uppercase;
}

.table tbody td,
#reload-table tbody td {
  padding: 14px 16px;
  border-color: var(--cms-border) !important;
  color: var(--cms-text) !important;
  font-weight: 500 !important;
  vertical-align: middle;
}

.table tbody tr:hover td,
#reload-table tbody tr:hover td {
  background: rgba(15, 118, 110, .035) !important;
}

[class$="-action-icon"] {
  width: 38px !important;
  height: 38px !important;
  min-height: 38px;
  padding: 0 !important;
  border: 1px solid var(--cms-border) !important;
  border-radius: 10px !important;
  background: var(--cms-surface) !important;
  box-shadow: none !important;
  transition: border-color .18s ease, background .18s ease, transform .18s ease;
}

[class$="-action-icon"]:hover {
  transform: translateY(-1px);
  border-color: var(--cms-primary) !important;
  background: var(--cms-primary-soft) !important;
}

[class*="-action-edit"] {
  color: var(--cms-primary) !important;
}

[class*="-action-delete"] {
  color: var(--cms-danger) !important;
}

.badge,
[class*="bg-label-"] {
  border-radius: 999px;
  font-weight: 650 !important;
}

.cms-media-field {
  padding: 16px;
  border: 1px solid var(--cms-border);
  border-radius: 14px;
  background: var(--cms-surface-muted);
}

.cms-media-preview {
  min-height: 150px;
  margin-bottom: 12px;
  display: grid;
  place-items: center;
  overflow: hidden;
  border: 1px dashed var(--cms-border-strong);
  border-radius: 12px;
  background: var(--cms-surface);
}

.cms-media-preview img {
  display: block;
  width: 100%;
  max-height: 220px;
  object-fit: contain;
}

.cms-media-placeholder {
  display: grid;
  place-items: center;
  gap: 4px;
  padding: 24px;
  color: var(--cms-muted);
}

.cms-media-placeholder .material-icons-outlined {
  font-size: 34px;
}

.cms-media-field .btn .material-icons-outlined {
  font-size: 18px;
}

@media (max-width: 767.98px) {
  .main-content {
    padding: 18px 14px;
  }

  .card-header,
  .card-body {
    padding: 18px;
  }
}
</style>
