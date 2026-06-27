<style>
.setting-page .main-content {
  width: 100%;
  max-width: 1540px;
  margin: 0 auto;
}

.setting-page .setting-layout-row {
  display: grid;
  grid-template-columns: 260px minmax(0, 1fr);
  column-gap: 24px;
  width: 100%;
  margin-right: 0;
  margin-left: 0;
  align-items: flex-start;
}

.setting-page .setting-layout-row > * {
  margin-top: 0;
}

.setting-page .setting-sidebar-col {
  width: 260px;
  max-width: 260px;
  padding-right: 0;
  padding-left: 0;
}

.setting-page .setting-content-col {
  width: 100%;
  max-width: none;
  min-width: 0;
  padding: 24px !important;
  border: 1px solid var(--cms-border) !important;
  border-radius: var(--cms-radius) !important;
  background: var(--cms-surface);
  box-shadow: var(--cms-shadow);
}

.setting-page .setting-content-box {
  min-height: 760px;
}

.setting-page .setting-content-box > .tab-content,
.setting-page .setting-content-box > .tab-content > .tab-pane {
  min-height: 690px;
}

.setting-page .setting-content-box > .tab-content > .tab-pane > form {
  min-height: 620px;
  display: flex;
  flex-direction: column;
}

.setting-page .setting-content-box > .tab-content > .tab-pane > form > .row {
  flex: 1 1 auto;
  align-content: flex-start;
}

.setting-page .setting-content-box > .tab-content > .tab-pane > form > .row > .col-12:last-child {
  margin-top: auto !important;
}

.setting-page .nav-pills {
  gap: 4px;
  padding: 8px !important;
  border: 1px solid var(--cms-border) !important;
  border-radius: 14px !important;
  background: var(--cms-surface);
  box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
}

.setting-page .nav-pills .nav-link {
  padding: 11px 12px;
  text-align: left !important;
  white-space: normal;
}

.setting-page .nav-pills .nav-link.active,
.setting-page .nav-pills .nav-link.active .text-muted,
.setting-page .nav-pills .show > .nav-link,
.setting-page .nav-pills .show > .nav-link .text-muted {
  color: #fff !important;
}

.setting-page .tab-content {
  padding: 0;
}

.setting-page .tab-pane > h5 {
  margin-bottom: 22px !important;
  padding-bottom: 14px !important;
  border-color: var(--cms-border) !important;
  color: var(--cms-heading) !important;
  font-size: 1rem;
  text-transform: none !important;
}

.setting-page .repeat-item,
.setting-page .border.rounded {
  border-color: var(--cms-border) !important;
}

.setting-page [id$="-json-wrapper"] {
  display: none !important;
}

.homepage-image-preview-wrap {
  padding: 12px;
  border: 1px solid var(--cms-border);
  border-radius: 12px;
  background: var(--cms-surface-muted);
}

@media (max-width: 991.98px) {
  .setting-page .setting-layout-row {
    display: block;
  }

  .setting-page .setting-sidebar-col {
    width: 100%;
    max-width: none;
    margin-bottom: 24px;
  }

  .setting-page .setting-content-col {
    width: 100%;
  }

  .setting-page .nav-pills {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 575.98px) {
  .setting-page .nav-pills {
    grid-template-columns: 1fr;
  }

  .setting-page .setting-content-col {
    padding: 16px !important;
  }

  .setting-page .setting-content-box,
  .setting-page .setting-content-box > .tab-content,
  .setting-page .setting-content-box > .tab-content > .tab-pane,
  .setting-page .setting-content-box > .tab-content > .tab-pane > form {
    min-height: auto;
  }
}
</style>
