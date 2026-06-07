<style>
html,
body {
  min-height: 100%;
}

body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Nav + sidebar của template thường fixed/absolute nên không cần đụng */
.main-wrapper {
  flex: 1 0 auto;
  display: flex;
  flex-direction: column;
}

.main-wrapper .main-content {
  flex: 1 0 auto;
}

/* Footer sẽ bị đẩy xuống đáy khi content ngắn */
.page-footer,
.footer,
footer {
  flex-shrink: 0;
}

/* Tránh footer dính sát bảng khi trang có dữ liệu */
.main-content {
  padding-bottom: 48px;
}
</style>