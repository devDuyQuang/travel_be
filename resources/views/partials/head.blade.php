<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>@yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- loader-->
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}">
  <!--bootstrap css-->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/semi-dark.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/bordered-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/responsive.css') }}" rel="stylesheet">

  <!-- <style>
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

    thead.table-light>tr>th,
    thead.table-light>tr>td {
      background-color: #4a4f5e !important;
      color: #c8ccd8 !important;
      border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .btn-xs .ti,
    .btn-xs i.ti {
      font-size: 0.85rem !important;
      line-height: 1 !important;
    }
  </style> -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}">
</head>