<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>@yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="../../assets/vendor/fonts/iconify-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/pickr/pickr-themes.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/swiper/swiper.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/pages/cards-advance.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/dropzone/dropzone.css" />
  <link rel="stylesheet" href="../../assets/css/fileinput.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/select2/select2.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/tagify/tagify.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />

  <!-- Helpers -->
  <script src="../../assets/vendor/js/helpers.js"></script>
  <!-- <script src="../../assets/vendor/js/template-customizer.js"></script> -->
  <script src="../../assets/js/config.js"></script>

  <style>
    .cke_contents {
      min-height: 350px;
    }

    .cke_contents .cke_source{
      color: black;
    }

    ul.nested-list:empty {
      min-height: 14px;
    }

    ul.nested-list {
      padding-left: 12px;
    }

    .sortable-ghost {
      opacity: .6;
    }

    .sortable-drag {
      opacity: .8;
    }

    .modal .modal-header {
      display: flex;
      align-items: center;
      flex-wrap: nowrap;
    }

    .modal .modal-header .modal-title {
      flex: 1 1 auto;
      min-width: 0;
    }

    .modal .modal-header .btn-close {
      flex-shrink: 0;
      order: 2;
      margin-left: auto;
      margin-right: 0;
      margin-bottom: 0;
      padding: 0.5rem;
      background-color: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 0.375rem;
      opacity: 1;
      filter: invert(1) grayscale(100%);
    }

    .modal .modal-header .btn-close:hover {
      background-color: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.4);
      opacity: 1;
    }

    [data-bs-theme="dark"] .modal .modal-header .btn-close {
      background-color: rgba(255, 255, 255, 0.08);
      border-color: rgba(255, 255, 255, 0.2);
    }

    [data-bs-theme="dark"] .modal .modal-header .btn-close:hover {
      background-color: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.35);
    }

    /* ── Override thead.table-light: bỏ màu tím, dùng xám trung tính ── */
    thead.table-light>tr>th,
    thead.table-light>tr>td {
      background-color: #4a4f5e !important;
      color: #c8ccd8 !important;
      border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* ── Đồng nhất kích thước icon trong btn-xs ── */
    .btn-xs .ti,
    .btn-xs i.ti {
      font-size: 0.85rem !important;
      line-height: 1 !important;
    }
  </style>
</head>