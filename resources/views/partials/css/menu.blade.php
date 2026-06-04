<style>
  #reload-table td {
    vertical-align: middle;
  }

  .seo-tree-parent,
  .seo-tree-child {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 200px;
    width: 100%;
  }

  .seo-tree-parent {
    font-size: 0.9rem;
  }

  .seo-tree-child {
    font-size: 0.85rem;
    opacity: 0.9;
  }

  #reload-table tbody tr.seo-row-parent:hover {
    background-color: rgba(113, 221, 55, 0.04) !important;
  }

  #reload-table tbody tr.seo-row-child {
    background-color: rgba(47, 54, 74, 0.25) !important;
  }

  #reload-table tbody tr.seo-row-child:hover {
    background-color: rgba(47, 54, 74, 0.4) !important;
  }

  .menu-name-wrap {
    width: 100%;
  }

  .menu-name-line {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .menu-name-label {
    min-width: 0;
    flex: 1 1 auto;
    display: inline-flex;
    align-items: center;
    overflow: hidden;
    white-space: nowrap;
  }

  .menu-name-label strong,
  .menu-name-label span:last-child,
  .menu-item-title {
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .menu-name-actions {
    flex: 0 0 auto;
    min-width: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
  }

  .menu-name-actions.has-link {
    min-width: 48px;
  }

  .menu-inline-icon {
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    vertical-align: middle;
    line-height: 1;
  }

  .menu-inline-icon i {
    font-size: 0.95rem;
    line-height: 1;
  }

  .menu-inline-icon-link {
    color: #00cfe8 !important;
  }

  .menu-inline-icon-info {
    color: rgba(255,255,255,0.65) !important;
  }

  .menu-inline-icon:hover {
    opacity: 0.9;
  }

  .menu-meta-trigger {
    box-shadow: none !important;
  }

  .menu-meta-popover {
    font-size: 0.8125rem;
    line-height: 1.5;
    min-width: 180px;
  }

  .menu-meta-row + .menu-meta-row {
    margin-top: 4px;
  }

  .seo-tree-connector {
    color: rgba(255,255,255,0.3);
    font-family: monospace;
    font-size: 0.75rem;
    margin-right: 4px;
    user-select: none;
    flex: 0 0 auto;
  }

  .drag-handle {
    cursor: grab !important;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .drag-handle i {
    font-size: 1.1rem;
    opacity: 0.5;
  }

  .drag-handle:active {
    cursor: grabbing !important;
  }

  .drag-handle:hover i {
    opacity: 1 !important;
  }

  .sortable-ghost {
    opacity: 0.4;
    background-color: rgba(105, 108, 255, 0.1) !important;
  }

  .sortable-chosen {
    background-color: rgba(105, 108, 255, 0.05) !important;
  }

  .status-toggle-wide {
    width: 2.5em !important;
    min-width: 2.5em;
  }

  .menu-status-toggle:disabled {
    cursor: not-allowed;
    opacity: 0.35;
  }

  .menu-action-icons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
  }

  .menu-action-icon {
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    line-height: 1;
  }

  .menu-action-icon i {
    font-size: 1rem;
    line-height: 1;
  }

  .menu-action-edit {
    color: #00cfe8 !important;
  }

  .menu-action-delete {
    color: #ea5455 !important;
  }

  .menu-action-icon:hover {
    opacity: 0.9;
  }
  #reload-table th.menu-action-col,
#reload-table td.menu-action-col {
  text-align: center !important;
  vertical-align: middle !important;
}

.menu-action-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 14px;
  width: auto;
  min-width: 52px;
  margin: 0 auto;
}

.menu-action-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  text-decoration: none !important;
  line-height: 1;
}

.menu-action-icon i {
  font-size: 1.1rem;
  line-height: 1;
}

.menu-action-edit {
  color: #00cfe8 !important;
}

.menu-action-delete {
  color: #ea5455 !important;
}

  #reload-table_wrapper .dt-paging,
  #reload-table_wrapper .dt-length,
  #reload-table_wrapper .dt-info,
  #reload-table_wrapper .dt-search,
  #reload-table_wrapper .dt-layout-start .dt-length,
  #reload-table_wrapper .dt-layout-end .dt-paging,
  #reload-table_wrapper .dt-layout-end .dt-search {
    display: none !important;
  }
</style>
