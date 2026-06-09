<style>
.contact-list-page .main-content {
  padding-bottom: 88px;
}

.contact-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 26px;
}

.contact-page-title {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin: 0;
  color: var(--cms-heading);
  font-size: 22px;
  font-weight: 700;
}

.contact-page-title .material-icons-outlined {
  font-size: 24px;
}

.contact-table-card {
  width: 100%;
  overflow: visible;
}

.contact-table-wrap {
  width: 100%;
  overflow-x: auto;
}

.contact-table {
  width: 100%;
  min-width: 980px;
  table-layout: fixed;
  border-collapse: collapse;
  margin: 0;
}

.contact-table thead th {
  padding: 15px 12px;
  color: var(--cms-heading);
  font-size: 15px;
  font-weight: 800;
  letter-spacing: .03em;
  text-transform: uppercase;
  border-top: 1px solid var(--cms-border);
  border-bottom: 1px solid var(--cms-border);
  background: transparent;
  white-space: nowrap;
}

.contact-table tbody td {
  padding: 16px 12px;
  color: var(--cms-text);
  font-size: 15px;
  font-weight: 500;
  vertical-align: middle;
  border-bottom: 1px solid var(--cms-border-soft);
  background: transparent;
}

.contact-table tbody tr:hover td {
  background-color: var(--cms-hover-bg);
}

.contact-index-col {
  width: 70px;
}

.contact-name-col {
  width: 18%;
}

.contact-email-col {
  width: 22%;
}

.contact-phone-col {
  width: 16%;
}

.contact-message-col {
  width: 14%;
}

.contact-date-col {
  width: 18%;
}

.contact-message-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  color: #22d3ee !important;
  border: 1px solid rgba(255, 255, 255, .08);
  background: rgba(255, 255, 255, .03);
  text-decoration: none !important;
}

.contact-message-btn:hover {
  background: rgba(255, 255, 255, .075);
}

.contact-message-btn .material-icons-outlined {
  font-size: 20px;
}

.contact-date {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.contact-date strong {
  color: var(--cms-heading);
  font-weight: 800;
}

.contact-date small {
  color: var(--cms-muted);
}

.contact-empty-row {
  text-align: center;
  color: rgba(255,255,255,.62) !important;
  padding: 26px 12px !important;
}

.contact-pagination {
  margin-top: 22px;
  display: flex;
  justify-content: flex-end;
}

.contact-pagination nav {
  margin: 0;
}

.contact-pagination .pagination {
  margin: 0;
}

.contact-modal-message {
  white-space: pre-wrap;
  line-height: 1.7;
  color: var(--cms-text);
}

.contact-modal-meta {
  color: var(--cms-text);
  line-height: 1.8;
}

@media (max-width: 992px) {
  .contact-table {
    min-width: 850px;
  }
}
</style>