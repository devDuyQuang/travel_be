<style>
.setting-page .setting-layout-row {
  align-items: flex-start;
}

.setting-page .setting-sidebar-col {
  width: 250px;
  flex: 0 0 250px;
}

.setting-page .setting-content-col {
  flex: 1 1 0;
  min-width: 0;
}

.setting-page .setting-content-box {
  min-height: 520px;
}

.setting-page #v-pills-tab.nav-pills,
.setting-page #v-pills-tab.nav-pills .nav-link {
  text-align: left !important;
}

.setting-page #v-pills-tab.nav-pills .nav-link {
  display: block !important;
  justify-content: flex-start !important;
  font-size: 0.9rem;
  white-space: normal;
}

.setting-logo-upload {
  width: 100%;
}

.setting-logo-preview-box {
  width: 100%;
  min-height: 170px;
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 6px 6px 0 0;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 18px;
  background: rgba(255, 255, 255, 0.02);
  overflow: hidden;
}

.setting-logo-preview-img {
  width: 260px;
  max-width: 100%;
  height: 140px;
  object-fit: contain;
  display: block;
}

.setting-favicon-preview-box {
  min-height: 150px;
}

.setting-favicon-preview-img {
  width: 120px;
  height: 120px;
}

.setting-logo-empty {
  width: 260px;
  height: 120px;
  border: 1px dashed rgba(255, 255, 255, 0.22);
  border-radius: 6px;
  color: rgba(255, 255, 255, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
}

.setting-logo-file-row .form-control {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

@media (max-width: 991.98px) {
  .setting-page .setting-sidebar-col {
    width: 100%;
    flex: 0 0 100%;
  }

  .setting-page .setting-content-col {
    width: 100%;
    flex: 0 0 100%;
  }
}

</style>