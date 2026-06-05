<style>
.cms-json-response-card {
  border: 1px solid rgba(255, 255, 255, .10);
  border-radius: 10px;
  background: rgba(255, 255, 255, .035);
  overflow: hidden;
}

.cms-json-response-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 11px 14px;
  color: #e5e7eb;
  font-size: 14px;
  font-weight: 700;
  border-bottom: 1px solid rgba(255, 255, 255, .08);
}

.cms-json-response-clear {
  border: 0;
  background: transparent;
  color: #c4b5fd;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.cms-json-response-content {
  margin: 0;
  padding: 14px 16px;
  max-height: 300px;
  overflow: auto;
  color: #f8fafc;
  background: rgba(15, 23, 42, .50);
  font-size: 13px;
  line-height: 1.55;
  white-space: pre-wrap;
  word-break: break-word;
}

html[data-bs-theme="light"] .cms-json-response-card,
body.light-theme .cms-json-response-card {
  border-color: rgba(17, 24, 39, .12);
  background: rgba(17, 24, 39, .035);
}

html[data-bs-theme="light"] .cms-json-response-header,
body.light-theme .cms-json-response-header {
  color: #111827;
  border-bottom-color: rgba(17, 24, 39, .10);
}

html[data-bs-theme="light"] .cms-json-response-content,
body.light-theme .cms-json-response-content {
  color: #111827;
  background: rgba(17, 24, 39, .05);
}
</style>