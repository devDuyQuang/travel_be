<style>
  /* ================================
   Scope:
   - .post-list-page: /post
   - .post-form-page: /post/create, /post/edit
   Không ảnh hưởng module khác.
================================ */

  /* ================================
   Post / Service List Page
================================ */

  .post-list-page .main-content {
    padding-bottom: 48px;
  }

  .post-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 26px;
  }

  .post-page-title {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: var(--cms-heading);
    font-size: 22px;
    font-weight: 700;
  }

  .post-page-title .material-icons-outlined {
    font-size: 24px;
  }

  .post-create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 44px;
    padding: 0 18px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
  }

  .post-create-btn .material-icons-outlined {
    font-size: 21px;
  }

  .post-filter-row {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .post-filter-row .form-select {
    width: 250px;
    height: 42px;
    border-radius: 9px;
    font-size: 15px;
    color: var(--cms-text);
    background-color: transparent;
    border: 1px solid var(--cms-border);
  }

  .post-table-card {
    width: 100%;
    overflow: visible;
  }

  /* ================================
   DataTables Wrapper
================================ */

  .post-list-page .dataTables_wrapper {
    width: 100%;
    max-width: 100%;
    overflow: visible;
  }

  .post-list-page .table-responsive {
    overflow-x: visible;
  }

  .post-list-page .dataTables_wrapper .row {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
  }

  /* Xóa padding dư từ wrapper/component nếu có */
  .post-list-page .px-3 {
    padding-left: 0;
    padding-right: 0;
  }

  .post-list-page .pb-3 {
    padding-bottom: 0;
  }

  /* ================================
   Top Controls: Show entries + Search
================================ */

  .post-list-page .dataTables_wrapper>.row:first-child {
    align-items: center;
    margin-bottom: 22px;
  }

  .post-list-page .dataTables_length label,
  .post-list-page .dataTables_filter label {
    color: var(--cms-text);
    font-size: 15px;
    font-weight: 600;
  }

  .post-list-page .dataTables_length select,
  .post-list-page .dataTables_filter input {
    height: 40px;
    min-height: 40px;
    border-radius: 9px;
    font-size: 15px;
    color: var(--cms-heading);
    background-color: transparent;
    border: 1px solid var(--cms-border);
    box-shadow: none;
  }

  .post-list-page .dataTables_length select {
    min-width: 70px;
    margin: 0 8px;
    text-align: center;
  }

  .post-list-page .dataTables_filter {
    text-align: right;
  }

  .post-list-page .dataTables_filter input {
    width: 190px;
    margin-left: 10px;
    padding: 6px 12px;
  }

  /* ================================
   Table
================================ */

  .post-list-page #reload-table {
    width: 100%;
    min-width: 0;
    table-layout: fixed;
    border-collapse: collapse;
    border-spacing: 0;
    margin: 0;
  }

  .post-list-page #reload-table thead th {
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

  .post-list-page #reload-table tbody td {
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

  .post-list-page #reload-table tbody tr:hover td {
    background-color: var(--cms-hover-bg);
  }

  /* Column widths */
  .post-list-page .post-index-col {
    width: 90px;
  }

  .post-list-page .post-name-col {
    width: 28%;
  }

  .post-list-page .post-category-col {
    width: 28%;
  }

  .post-list-page .post-status-col {
    width: 18%;
  }

  .post-list-page .post-action-col {
    width: 16%;
  }

  /* ================================
   Name + Meta Icons
================================ */

  .post-name-with-icons {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    width: 100%;
    min-width: 0;
  }

  .post-name-text {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .post-name-icons {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
  }

  .post-name-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    text-decoration: none;
    opacity: .9;
  }

  .post-name-icon .material-icons-outlined {
    font-size: 18px;
    line-height: 1;
  }

  .post-name-icon-link {
    color: #22d3ee;
  }

  .post-name-icon-info {
    color: var(--cms-muted);
  }

  .post-name-icon:hover {
    opacity: 1;
  }

  /* ================================
   Category Badge
================================ */

  .post-category-badge {
    display: inline-flex;
    align-items: center;
    max-width: 100%;
    padding: 6px 10px;
    margin: 2px 5px 2px 0;
    border-radius: 999px;
    color: #22d3ee;
    background: rgba(34, 211, 238, .12);
    font-size: 13.5px;
    font-weight: 700;
    line-height: 1.2;
  }

  /* ================================
   Status + Actions
================================ */

  .status-toggle-wide {
    width: 2.5em;
    min-width: 2.5em;
  }

  .post-status-toggle {
    cursor: pointer;
  }

  .post-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
  }

  .post-action-icon {
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

  .post-action-icon .material-icons-outlined {
    font-size: 20px;
    line-height: 1;
  }

  .post-action-edit {
    color: #22d3ee;
  }

  .post-action-delete {
    color: #f87171;
  }

  .post-action-icon:hover {
    opacity: .9;
    transform: translateY(-1px);
    background: rgba(255, 255, 255, .075);
  }

  /* ================================
   DataTable Bottom
   Giống style template cũ hơn:
   - info bên trái
   - pagination bên phải
   - nằm chung 1 thanh nền
================================ */

  .post-list-page .dataTables_wrapper>.row:last-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 28px;
    padding: 24px 28px;
    background: #2b3046;
    border-radius: 0 0 10px 10px;
  }

  .post-list-page .dataTables_wrapper>.row:last-child>div:first-child,
  .post-list-page .dataTables_wrapper>.row:last-child>div:last-child {
    width: auto;
    max-width: 50%;
    flex: 0 0 auto;
    padding: 0;
    margin: 0;
  }

  .post-list-page .dataTables_wrapper>.row:last-child>div:first-child {
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }

  .post-list-page .dataTables_wrapper>.row:last-child>div:last-child {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  .post-list-page .dataTables_info {
    margin: 0;
    padding: 0;
    color: var(--cms-muted);
    font-size: 22px;
    font-weight: 500;
    line-height: 54px;
    white-space: nowrap;
  }

  .post-list-page .dataTables_paginate {
    float: none;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin: 0;
    padding: 0;
  }

  .post-list-page .dataTables_paginate .paginate_button {
    min-width: 54px;
    height: 54px;
    padding: 0 18px;
    margin: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 0;
    background: rgba(255, 255, 255, .055);
    color: var(--cms-muted);
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
    box-shadow: none;
    cursor: pointer;
  }

  .post-list-page .dataTables_paginate .paginate_button.current,
  .post-list-page .dataTables_paginate .paginate_button.current:hover {
    background: #0f9f95;
    color: #ffffff;
    border: 0;
    box-shadow: 0 0 18px rgba(15, 159, 149, .28);
  }

  .post-list-page .dataTables_paginate .paginate_button:hover {
    background: rgba(255, 255, 255, .12);
    color: #ffffff;
    border: 0;
  }

  .post-list-page .dataTables_paginate .paginate_button.disabled,
  .post-list-page .dataTables_paginate .paginate_button.disabled:hover {
    opacity: .35;
    background: rgba(255, 255, 255, .045);
    color: rgba(255, 255, 255, .45);
    cursor: default;
    pointer-events: none;
    box-shadow: none;
  }

  /* ================================
   Post Create / Edit Form
================================ */

  .post-form-page .main-content {
    padding-top: 28px;
    padding-bottom: 48px;
  }

  .post-form-page .card-header {
    margin-bottom: 28px;
    padding-left: 0;
    color: var(--cms-heading);
    font-size: 22px;
    font-weight: 700;
  }

  .post-form-page .nav-tabs {
    margin-bottom: 24px;
  }

  .post-form-page .mb-6,
  .post-form-page .mb-3 {
    margin-bottom: 18px;
  }

  .post-form-page select[name="category_ids[]"],
  .post-form-page #post-category_ids {
    height: 42px;
    min-height: 42px;
    max-height: 42px;
    overflow: hidden;
  }

  .post-form-page .select2-container {
    width: 100%;
  }

  .post-form-page .select2-container .select2-selection--multiple {
    min-height: 42px;
    max-height: 42px;
    overflow: hidden;
    display: flex;
    align-items: center;
  }

  .post-form-page .select2-container .select2-selection__rendered {
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    gap: 4px;
    overflow-x: auto;
    padding: 4px 8px;
  }

  .post-form-page input[type="file"] {
    width: 100%;
    height: 42px;
    border: 1px solid var(--cms-border);
    border-radius: 6px;
    background: transparent;
    color: var(--bs-body-color);
  }

  .post-form-page input[type="file"]::file-selector-button {
    height: 42px;
    padding: 0 16px;
    margin-right: 12px;
    border: 0;
    border-right: 1px solid rgba(255, 255, 255, .16);
    background: rgba(255, 255, 255, .08);
    color: var(--bs-body-color);
  }

  /* ================================
   Responsive
================================ */

  @media (max-width: 768px) {

    .post-page-header,
    .post-filter-row,
    .post-list-page .dataTables_wrapper>.row:first-child,
    .post-list-page .dataTables_wrapper>.row:last-child {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }

    .post-filter-row .form-select,
    .post-list-page .dataTables_filter input {
      width: 100%;
    }

    .post-list-page .dataTables_wrapper>.row:last-child>div:first-child,
    .post-list-page .dataTables_wrapper>.row:last-child>div:last-child {
      max-width: 100%;
      width: 100%;
    }

    .post-list-page .dataTables_paginate {
      justify-content: flex-start;
      flex-wrap: wrap;
    }
  }

  /* =====================================================
   FIX Pagination - compact / normal style
===================================================== */

  .post-list-page .dataTables_wrapper>.row:last-child {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin: 20px 0 0 0 !important;
    padding: 0 !important;
    background: transparent !important;
    border-radius: 0 !important;
  }

  .post-list-page .dataTables_wrapper>.row:last-child>div:first-child,
  .post-list-page .dataTables_wrapper>.row:last-child>div:last-child {
    width: auto !important;
    max-width: none !important;
    flex: 0 0 auto !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .post-list-page .dataTables_info {
    margin: 0 !important;
    padding: 0 !important;
    color: rgba(255, 255, 255, 0.55) !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    line-height: 34px !important;
    white-space: nowrap !important;
  }

  .post-list-page .dataTables_paginate {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 6px !important;
    margin: 0 !important;
    padding: 0 !important;
    float: none !important;
  }

  .post-list-page .dataTables_paginate .paginate_button {
    min-width: 34px !important;
    height: 34px !important;
    padding: 0 10px !important;
    margin: 0 !important;

    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 6px !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    background: rgba(255, 255, 255, 0.04) !important;

    color: rgba(255, 255, 255, 0.68) !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    line-height: 1 !important;
    text-decoration: none !important;

    box-shadow: none !important;
    cursor: pointer !important;
  }

  .post-list-page .dataTables_paginate .paginate_button.current,
  .post-list-page .dataTables_paginate .paginate_button.current:hover {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    box-shadow: none !important;
  }

  .post-list-page .dataTables_paginate .paginate_button:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.14) !important;
    color: #ffffff !important;
  }

  .post-list-page .dataTables_paginate .paginate_button.disabled,
  .post-list-page .dataTables_paginate .paginate_button.disabled:hover {
    opacity: 0.35 !important;
    background: rgba(255, 255, 255, 0.03) !important;
    border-color: rgba(255, 255, 255, 0.05) !important;
    color: rgba(255, 255, 255, 0.45) !important;
    cursor: default !important;
    pointer-events: none !important;
  }

  .post-list-page .dataTables_paginate .paginate_button:focus,
  .post-list-page .dataTables_paginate .paginate_button:active {
    outline: none !important;
    box-shadow: none !important;
  }


  /* =====================================================
   DataTables v2 footer - match template pagination
===================================================== */

  .post-list-page .dt-layout-row:last-child,
  .post-list-page .dataTables_wrapper>.row:last-child,
  .post-list-page .row.mx-3.justify-content-between {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin: 22px 0 0 0 !important;
    padding: 0 !important;
    background: transparent !important;
    border-radius: 0 !important;
  }

  /* Left info */
  .post-list-page .dt-layout-start {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  /* Right pagination */
  .post-list-page .dt-layout-end {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  /* Text: Hiển thị 1 đến 10 của 80 dòng */
  .post-list-page .dt-info {
    color: rgba(255, 255, 255, 0.55) !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    line-height: 40px !important;
    padding: 0 !important;
    margin: 0 !important;
    white-space: nowrap !important;
  }

  /* Pagination wrapper */
  .post-list-page .dt-paging {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
  }

  /* ul.pagination */
  .post-list-page .dt-paging .pagination {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  /* li */
  .post-list-page .dt-paging .page-item {
    margin: 0 !important;
    padding: 0 !important;
  }

  /* button.page-link */
  .post-list-page .dt-paging .page-link {
    width: 44px !important;
    height: 44px !important;
    min-width: 44px !important;
    padding: 0 !important;

    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    border: 0 !important;
    border-radius: 8px !important;
    background: rgba(255, 255, 255, 0.06) !important;

    color: rgba(255, 255, 255, 0.72) !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    line-height: 1 !important;
    text-decoration: none !important;

    box-shadow: none !important;
    outline: none !important;
  }

  /* Icon inside first/prev/next/last */
  .post-list-page .dt-paging .page-link i {
    font-size: 18px !important;
    line-height: 1 !important;
  }

  /* Active page */
  .post-list-page .dt-paging .page-item.active .page-link {
    background: #0f9f95 !important;
    color: #ffffff !important;
    box-shadow: none !important;
  }

  /* Hover */
  .post-list-page .dt-paging .page-item:not(.disabled):not(.active) .page-link:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
  }

  /* Disabled */
  .post-list-page .dt-paging .page-item.disabled .page-link {
    opacity: 0.45 !important;
    background: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.45) !important;
    cursor: default !important;
    pointer-events: none !important;
  }

  /* Remove browser focus */
  .post-list-page .dt-paging .page-link:focus,
  .post-list-page .dt-paging .page-link:active {
    box-shadow: none !important;
    outline: none !important;
  }
</style>
<style>
  /* ================================
   Scope:
   - .post-list-page: /post
   - .post-form-page: /post/create, /post/edit
================================ */

  /* ================================
   Post / Service List Page
================================ */

  .post-list-page .main-content {
    padding-bottom: 48px;
  }

  .post-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 26px;
  }

  .post-page-title {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: var(--cms-heading);
    font-size: 22px;
    font-weight: 700;
  }

  .post-page-title .material-icons-outlined {
    font-size: 24px;
  }

  .post-create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 44px;
    padding: 0 18px;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
  }

  .post-create-btn .material-icons-outlined {
    font-size: 21px;
  }

  .post-filter-row {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .post-filter-row .form-select {
    width: 250px;
    height: 42px;
    border-radius: 9px;
    font-size: 15px;
    color: var(--cms-text);
    background-color: transparent;
    border: 1px solid var(--cms-border);
  }

  .post-table-card {
    width: 100%;
    overflow: visible;
  }

  /* ================================
   DataTables Wrapper
================================ */

  .post-list-page .dt-container,
  .post-list-page .dataTables_wrapper {
    width: 100%;
    max-width: 100%;
    overflow: visible;
  }

  .post-list-page .table-responsive {
    overflow-x: visible;
  }

  .post-list-page .row,
  .post-list-page .dt-layout-row {
    width: 100%;
    margin-left: 0;
    margin-right: 0;
  }

  .post-list-page .px-3,
  .post-list-page .mx-3 {
    padding-left: 0;
    padding-right: 0;
    margin-left: 0;
    margin-right: 0;
  }

  .post-list-page .pb-3 {
    padding-bottom: 0;
  }

  /* ================================
   Top Controls
================================ */

  .post-list-page .dt-layout-row:first-child,
  .post-list-page .dataTables_wrapper>.row:first-child {
    align-items: center;
    margin-bottom: 22px;
  }

  .post-list-page .dt-length label,
  .post-list-page .dt-search label,
  .post-list-page .dataTables_length label,
  .post-list-page .dataTables_filter label {
    color: var(--cms-text);
    font-size: 15px;
    font-weight: 600;
  }

  .post-list-page .dt-length select,
  .post-list-page .dt-search input,
  .post-list-page .dataTables_length select,
  .post-list-page .dataTables_filter input {
    height: 40px;
    min-height: 40px;
    border-radius: 9px;
    font-size: 15px;
    color: var(--cms-heading);
    background-color: transparent;
    border: 1px solid var(--cms-border);
    box-shadow: none;
  }

  .post-list-page .dt-length select,
  .post-list-page .dataTables_length select {
    min-width: 70px;
    margin: 0 8px;
    text-align: center;
  }

  .post-list-page .dt-search,
  .post-list-page .dataTables_filter {
    text-align: right;
  }

  .post-list-page .dt-search input,
  .post-list-page .dataTables_filter input {
    width: 190px;
    margin-left: 10px;
    padding: 6px 12px;
  }

  /* ================================
   Table
================================ */

  .post-list-page #reload-table {
    width: 100%;
    min-width: 0;
    table-layout: fixed;
    border-collapse: collapse;
    border-spacing: 0;
    margin: 0;
  }

  .post-list-page #reload-table thead th {
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

  .post-list-page #reload-table tbody td {
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

  .post-list-page #reload-table tbody tr:hover td {
    background-color: var(--cms-hover-bg);
  }

  /* Column widths */
  .post-list-page .post-index-col {
    width: 90px;
  }

  .post-list-page .post-name-col {
    width: 28%;
  }

  .post-list-page .post-category-col {
    width: 28%;
  }

  .post-list-page .post-status-col {
    width: 18%;
  }

  .post-list-page .post-action-col {
    width: 16%;
  }

  /* ================================
   Name + Meta Icons
================================ */

  .post-name-with-icons {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    width: 100%;
    min-width: 0;
  }

  .post-name-text {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .post-name-icons {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
  }

  .post-name-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    text-decoration: none;
    opacity: .9;
  }

  .post-name-icon .material-icons-outlined {
    font-size: 18px;
    line-height: 1;
  }

  .post-name-icon-link {
    color: #22d3ee;
  }

  .post-name-icon-info {
    color: var(--cms-muted);
  }

  .post-name-icon:hover {
    opacity: 1;
  }

  /* ================================
   Category Badge
================================ */

  .post-category-badge {
    display: inline-flex;
    align-items: center;
    max-width: 100%;
    padding: 6px 10px;
    margin: 2px 5px 2px 0;
    border-radius: 999px;
    color: #22d3ee;
    background: rgba(34, 211, 238, .12);
    font-size: 13.5px;
    font-weight: 700;
    line-height: 1.2;
  }

  /* ================================
   Status + Actions
================================ */

  .status-toggle-wide {
    width: 2.5em;
    min-width: 2.5em;
  }

  .post-status-toggle {
    cursor: pointer;
  }

  .post-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
  }

  .post-action-icon {
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

  .post-action-icon .material-icons-outlined {
    font-size: 20px;
    line-height: 1;
  }

  .post-action-edit {
    color: #22d3ee;
  }

  .post-action-delete {
    color: #f87171;
  }

  .post-action-icon:hover {
    opacity: .9;
    transform: translateY(-1px);
    background: rgba(255, 255, 255, .075);
  }

  /* ================================
   DataTables v2 Footer
   Pagination dạng khung liền nhau
================================ */

  .post-list-page .dt-layout-row:last-child,
  .post-list-page .row.mx-3.justify-content-between,
  .post-list-page .dataTables_wrapper>.row:last-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 22px 0 0;
    padding: 0;
    background: transparent;
    border-radius: 0;
  }

  .post-list-page .dt-layout-start,
  .post-list-page .dt-layout-end {
    display: flex;
    align-items: center;
    padding: 0;
    margin: 0;
  }

  .post-list-page .dt-layout-start {
    justify-content: flex-start;
  }

  .post-list-page .dt-layout-end {
    justify-content: flex-end;
  }

  .post-list-page .dt-info {
    color: rgba(255, 255, 255, .55);
    font-size: 15px;
    font-weight: 500;
    line-height: 38px;
    padding: 0;
    margin: 0;
    white-space: nowrap;
  }

  .post-list-page .dt-paging {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  .post-list-page .dt-paging .pagination {
    display: inline-flex;
    align-items: center;
    gap: 0;
    margin: 0;
    padding: 0;
    border: 2px solid rgba(255, 255, 255, .18);
    border-radius: 999px;
    overflow: hidden;
    background: transparent;
  }

  .post-list-page .dt-paging .page-item {
    margin: 0;
    padding: 0;
  }

  .post-list-page .dt-paging .page-link {
    width: 46px;
    height: 42px;
    min-width: 46px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-right: 2px solid rgba(255, 255, 255, .18);
    border-radius: 0;
    background: transparent;
    color: #79b3ff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
    box-shadow: none;
    outline: none;
  }

  .post-list-page .dt-paging .page-item:last-child .page-link {
    border-right: 0;
  }

  .post-list-page .dt-paging .page-item.active .page-link {
    background: #0d6efd;
    color: #ffffff;
  }

  .post-list-page .dt-paging .page-item:not(.disabled):not(.active) .page-link:hover {
    background: rgba(255, 255, 255, .08);
    color: #ffffff;
  }

  .post-list-page .dt-paging .page-item.disabled .page-link {
    opacity: .45;
    color: rgba(255, 255, 255, .45);
    cursor: default;
    pointer-events: none;
  }

  .post-list-page .dt-paging .page-link i {
    font-size: 18px;
    line-height: 1;
  }

  /* ================================
   Post Create / Edit Form
================================ */

  .post-form-page .main-content {
    padding-top: 28px;
    padding-bottom: 48px;
  }

  .post-form-page .card-header {
    margin-bottom: 28px;
    padding-left: 0;
    color: var(--cms-heading);
    font-size: 22px;
    font-weight: 700;
  }

  .post-form-page .nav-tabs {
    margin-bottom: 24px;
  }

  .post-form-page .mb-6,
  .post-form-page .mb-3 {
    margin-bottom: 18px;
  }

  .post-form-page select[name="category_ids[]"],
  .post-form-page #post-category_ids {
    height: 42px;
    min-height: 42px;
    max-height: 42px;
    overflow: hidden;
  }

  .post-form-page .select2-container {
    width: 100%;
  }

  .post-form-page .select2-container .select2-selection--multiple {
    min-height: 42px;
    max-height: 42px;
    overflow: hidden;
    display: flex;
    align-items: center;
  }

  .post-form-page .select2-container .select2-selection__rendered {
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    gap: 4px;
    overflow-x: auto;
    padding: 4px 8px;
  }

  .post-form-page input[type="file"] {
    width: 100%;
    height: 42px;
    border: 1px solid var(--cms-border);
    border-radius: 6px;
    background: transparent;
    color: var(--bs-body-color);
  }

  .post-form-page input[type="file"]::file-selector-button {
    height: 42px;
    padding: 0 16px;
    margin-right: 12px;
    border: 0;
    border-right: 1px solid rgba(255, 255, 255, .16);
    background: rgba(255, 255, 255, .08);
    color: var(--bs-body-color);
  }

  /* ================================
   Responsive
================================ */

  @media (max-width: 768px) {

    .post-page-header,
    .post-filter-row,
    .post-list-page .dt-layout-row:first-child,
    .post-list-page .dt-layout-row:last-child {
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }

    .post-filter-row .form-select,
    .post-list-page .dt-search input {
      width: 100%;
    }

    .post-list-page .dt-paging .pagination {
      flex-wrap: wrap;
      border-radius: 8px;
    }
  }

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

  .doctor-action-icon .material-icons-outlined {
    font-size: 20px;
    line-height: 1;
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

  .doctor-avatar {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 8px;
    display: inline-block;
  }
</style>

<style>
  :root {
    --post-primary: #0d6efd;
    --post-primary-dark: #0957cf;
    --post-primary-soft: #eef5ff;
    --post-success: #16a36a;
    --post-danger: #ef4d56;
    --post-text: #172033;
    --post-muted: #6b7280;
    --post-border: #e5eaf1;
    --post-bg: #f5f7fb;
    --post-card: #ffffff;
    --post-radius: 16px;
    --post-shadow: 0 10px 30px rgba(22, 34, 51, 0.06);
  }

  /* PAGE */

  .post-list-page .main-content,
  .post-form-page .main-content {
    min-height: calc(100vh - 70px);
    padding: 28px 28px 48px;
    background: var(--post-bg);
  }

  .post-page-shell {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
  }

  /* HEADER */

  .post-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 24px;
  }

  .post-page-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
  }

  .post-page-icon {
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    color: var(--post-primary);
    background: var(--post-primary-soft);
  }

  .post-page-icon .material-icons-outlined {
    font-size: 25px;
  }

  .post-page-title {
    margin: 0;
    color: var(--post-text);
    font-size: 24px;
    font-weight: 700;
    line-height: 1.3;
  }

  .post-page-subtitle {
    margin: 4px 0 0;
    color: var(--post-muted);
    font-size: 14px;
    line-height: 1.5;
  }

  .post-create-btn {
    min-height: 44px;
    padding: 10px 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border: 0;
    border-radius: 11px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 8px 18px rgba(13, 110, 253, 0.2);
  }

  .post-create-btn .material-icons-outlined {
    font-size: 20px;
  }

  .post-back-btn,
  .post-reset-filter-btn {
    min-height: 40px;
    padding: 8px 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--post-border);
    border-radius: 10px;
    color: #4b5563;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
  }

  .post-back-btn:hover,
  .post-reset-filter-btn:hover {
    color: var(--post-primary);
    border-color: #bad1f7;
    background: var(--post-primary-soft);
  }

  .post-back-btn .material-icons-outlined,
  .post-reset-filter-btn .material-icons-outlined {
    font-size: 18px;
  }

  /* FILTER CARD */

  .post-filter-card {
    position: relative;
    z-index: 2;
    overflow: visible;
    margin-bottom: 22px;
    padding: 20px 22px 22px;
    border: 1px solid var(--post-border);
    border-radius: var(--post-radius);
    background: var(--post-card);
    box-shadow: var(--post-shadow);
  }

  .post-filter-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
  }

  .post-filter-title {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--post-text);
    font-size: 15px;
    font-weight: 700;
  }

  .post-filter-title .material-icons-outlined {
    color: var(--post-primary);
    font-size: 20px;
  }

  .post-filter-description {
    margin: 5px 0 0;
    color: var(--post-muted);
    font-size: 13px;
  }

  .post-filter-label {
    display: block;
    margin-bottom: 7px;
    color: #3d4657;
    font-size: 13px;
    font-weight: 600;
  }

  /* Native fallback */

  .post-filter-native {
    width: 100%;
    min-height: 46px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    color: var(--post-text);
    background: #fff;
  }

  /* CUSTOM SELECT */

  .post-filter-control {
    position: relative;
    width: 100%;
  }

  .post-filter-control.is-enhanced .post-filter-native {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    clip-path: inset(50%) !important;
    white-space: nowrap !important;
    border: 0 !important;
    opacity: 0;
    pointer-events: none;
  }

  .post-filter-trigger {
    width: 100%;
    height: 46px;
    padding: 0 44px 0 14px;
    display: flex;
    align-items: center;
    position: relative;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    color: var(--post-text);
    background: #fff;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .post-filter-trigger:hover {
    border-color: #b9c7d9;
  }

  .post-filter-trigger:focus,
  .post-filter-control.is-open .post-filter-trigger {
    border-color: var(--post-primary);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
  }

  .post-filter-trigger-text {
    display: block;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .post-filter-arrow {
    position: absolute;
    top: 50%;
    right: 12px;
    color: #7a8495;
    font-size: 20px;
    transform: translateY(-50%);
    pointer-events: none;
    transition: transform 0.2s ease, color 0.2s ease;
  }

  .post-filter-control.is-open .post-filter-arrow {
    color: var(--post-primary);
    transform: translateY(-50%) rotate(180deg);
  }

  .post-filter-menu {
    width: 100%;
    max-height: 280px;
    display: none;
    overflow-y: auto;
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    padding: 7px;
    border: 1px solid #dce3ec;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 16px 36px rgba(22, 34, 51, 0.16);
    z-index: 9999;
  }

  .post-filter-control.is-open .post-filter-menu {
    display: block;
  }

  .post-filter-option {
    width: 100%;
    min-height: 40px;
    padding: 9px 12px 9px 38px;
    display: flex;
    align-items: center;
    position: relative;
    border: 0;
    border-radius: 8px;
    color: #3d4657;
    background: transparent;
    font-family: inherit;
    font-size: 14px;
    font-weight: 500;
    text-align: left;
    cursor: pointer;
  }

  .post-filter-option:hover,
  .post-filter-option:focus {
    color: var(--post-primary);
    background: var(--post-primary-soft);
  }

  .post-filter-option.is-selected {
    color: var(--post-primary);
    background: #e8f1ff;
    font-weight: 650;
  }

  .post-filter-option-check {
    display: none;
    position: absolute;
    left: 11px;
    top: 50%;
    color: var(--post-primary);
    font-size: 18px;
    transform: translateY(-50%);
  }

  .post-filter-option.is-selected .post-filter-option-check {
    display: inline-flex;
  }

  .post-filter-option-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* TABLE CARD */

  .post-table-card {
    position: relative;
    z-index: 1;
    overflow: hidden;
    border: 1px solid var(--post-border);
    border-radius: var(--post-radius);
    background: var(--post-card);
    box-shadow: var(--post-shadow);
  }

  .post-table-card-header {
    min-height: 76px;
    padding: 17px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    border-bottom: 1px solid var(--post-border);
  }

  .post-table-title {
    margin: 0;
    color: var(--post-text);
    font-size: 16px;
    font-weight: 700;
  }

  .post-table-description {
    margin: 4px 0 0;
    color: var(--post-muted);
    font-size: 13px;
  }

  .post-table-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    white-space: nowrap;
    color: #526071;
    font-size: 12px;
    font-weight: 600;
  }

  .post-table-status-dot {
    width: 8px;
    height: 8px;
    display: inline-block;
    border-radius: 50%;
    background: var(--post-success);
    box-shadow: 0 0 0 4px rgba(22, 163, 106, 0.12);
  }

  .post-table-card .dataTables_wrapper,
  .post-table-card .dt-container {
    padding: 0;
  }

  .post-table-card .dataTables_wrapper>.row:first-child,
  .post-table-card .dt-container>.row:first-child {
    margin: 0;
    padding: 16px 20px;
    align-items: center;
    border-bottom: 1px solid var(--post-border);
  }

  .post-table-card .dataTables_wrapper>.row:last-child,
  .post-table-card .dt-container>.row:last-child {
    margin: 0;
    padding: 15px 20px;
    align-items: center;
    border-top: 1px solid var(--post-border);
  }

  .post-table-card .dataTables_filter input,
  .post-table-card .dt-search input {
    width: 240px;
    min-height: 40px;
    margin-left: 8px;
    padding: 8px 12px;
    border: 1px solid #dce3ec;
    border-radius: 9px;
    outline: none;
  }

  .post-table-card table.dataTable {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: separate !important;
    border-spacing: 0;
  }

  .post-table-card table.dataTable thead th {
    padding: 15px 18px !important;
    border-top: 0 !important;
    border-bottom: 1px solid var(--post-border) !important;
    color: #677284;
    background: #f8fafc;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    vertical-align: middle;
  }

  .post-table-card table.dataTable tbody td {
    padding: 15px 18px !important;
    border-top: 0 !important;
    border-bottom: 1px solid #edf0f4 !important;
    color: #293244;
    background: #fff;
    font-size: 14px;
    vertical-align: middle;
  }

  .post-table-card table.dataTable tbody tr:hover td {
    background: #f8fbff;
  }

  /* BADGE */

  .post-category-badge {
    display: inline-flex;
    align-items: center;
    margin: 2px 5px 2px 0;
    padding: 6px 10px;
    border-radius: 999px;
    color: #087da4;
    background: #e8f8fc;
    font-size: 12px;
    font-weight: 600;
  }

  /* NAME */

  .post-name-with-icons {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }

  .post-name-text {
    min-width: 0;
    overflow: hidden;
    color: #20293a;
    font-size: 14px;
    font-weight: 650;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .post-name-icons {
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .post-name-icon {
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    color: #778195;
    text-decoration: none;
  }

  .post-name-icon:hover {
    color: var(--post-primary);
    background: var(--post-primary-soft);
  }

  .post-name-icon .material-icons-outlined {
    font-size: 18px;
  }

  /* ACTION */

  .post-action-icons {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
  }

  .post-action-icon {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--post-border);
    border-radius: 10px;
    background: #fff;
    text-decoration: none;
  }

  .post-action-edit {
    color: #08a8d5;
  }

  .post-action-edit:hover {
    color: #078db3;
    border-color: #b9e8f5;
    background: #effbfe;
  }

  .post-action-delete {
    color: var(--post-danger);
  }

  .post-action-delete:hover {
    color: #d83943;
    border-color: #ffc9cd;
    background: #fff3f4;
  }

  /* SWITCH */

  .post-status-toggle {
    width: 42px !important;
    height: 22px !important;
    cursor: pointer;
    border: 0;
    box-shadow: none !important;
  }

  /* FORM */

  .post-form-card {
    overflow: hidden;
    border: 1px solid var(--post-border);
    border-radius: var(--post-radius);
    background: #fff;
    box-shadow: var(--post-shadow);
  }

  .post-form-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--post-border);
  }

  .post-form-card-title {
    margin: 0;
    color: var(--post-text);
    font-size: 17px;
    font-weight: 700;
  }

  .post-form-card-description {
    margin: 5px 0 0;
    color: var(--post-muted);
    font-size: 13px;
  }

  .post-form-card-body {
    padding: 24px;
  }

  .post-form-card .nav-tabs {
    gap: 8px;
    padding: 6px;
    border: 0;
    border-radius: 11px;
    background: #f3f6fa;
  }

  .post-form-card .nav-tabs .nav-link {
    border: 0;
    border-radius: 8px;
    color: #647083;
    font-size: 14px;
    font-weight: 600;
  }

  .post-form-card .nav-tabs .nav-link.active {
    color: var(--post-primary);
    background: #fff;
    box-shadow: 0 4px 12px rgba(31, 41, 55, 0.07);
  }

  .post-form-card .tab-content {
    padding-top: 24px;
  }

  .post-form-card .form-label {
    margin-bottom: 7px;
    color: #364052;
    font-size: 13px;
    font-weight: 600;
  }

  .post-form-card .form-control,
  .post-form-card .form-select {
    min-height: 44px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
    box-shadow: none;
  }

  .post-form-card .form-control:focus,
  .post-form-card .form-select:focus {
    border-color: var(--post-primary);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
  }

  .post-form-card .select2-container {
    width: 100% !important;
  }

  .post-form-card .select2-container--default .select2-selection--multiple {
    min-height: 44px;
    padding: 4px 8px;
    border: 1px solid #dce3ec;
    border-radius: 10px;
  }

  .post-form-card .cke {
    width: 100% !important;
    overflow: hidden;
    border: 1px solid #dce3ec !important;
    border-radius: 10px;
    box-shadow: none !important;
  }

  .post-form-card .cke_contents {
    height: 360px !important;
  }

  @media (max-width: 991.98px) {

    .post-list-page .main-content,
    .post-form-page .main-content {
      padding: 22px 18px 36px;
    }

    .post-table-card {
      overflow-x: auto;
    }

    .post-table-card table.dataTable {
      min-width: 850px;
    }
  }

  @media (max-width: 767.98px) {
    .post-page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .post-create-btn,
    .post-back-btn,
    .post-reset-filter-btn {
      width: 100%;
    }

    .post-filter-header,
    .post-table-card-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .post-form-card-body {
      padding: 18px;
    }

    .post-form-card .cke_contents {
      height: 300px !important;
    }
  }

  
</style>