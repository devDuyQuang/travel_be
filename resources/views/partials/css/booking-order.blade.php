<style>
    /*
    |--------------------------------------------------------------------------
    | Booking & Order administration
    |--------------------------------------------------------------------------
    */

    .commerce-page {
        padding-bottom: 32px;
    }

    /*
    |--------------------------------------------------------------------------
    | Page header
    |--------------------------------------------------------------------------
    */

    .commerce-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .commerce-page-title {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .commerce-page-title-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        margin-top: 1px;
        border-radius: 12px;
        background: rgba(25, 135, 120, 0.1);
        color: #198778;
    }

    .commerce-page-title-icon .material-icons-outlined {
        font-size: 23px;
    }

    .commerce-page-title h4 {
        margin: 0 0 4px;
        color: #182033;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.3;
    }

    .commerce-page-title p {
        margin: 0;
        color: #748095;
        font-size: 14px;
        line-height: 1.5;
    }

    .commerce-page-summary {
        flex: 0 0 auto;
        padding: 9px 14px;
        border: 1px solid #e4e9f1;
        border-radius: 10px;
        background: #ffffff;
        color: #697386;
        font-size: 13px;
        white-space: nowrap;
    }

    .commerce-page-summary strong {
        color: #182033;
    }

    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */

    .commerce-card {
        overflow: hidden;
        border: 1px solid #e6eaf0;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(24, 32, 51, 0.04);
    }

    .commerce-filter-card {
        margin-bottom: 24px;
    }

    .commerce-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        min-height: 68px;
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f5;
        background: #ffffff;
    }

    .commerce-card-header h6 {
        margin: 0 0 3px;
        color: #182033;
        font-size: 15px;
        font-weight: 700;
    }

    .commerce-card-header small {
        display: block;
        color: #8490a3;
        font-size: 12px;
        line-height: 1.5;
    }

    .commerce-card-header-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 35px;
        border-radius: 8px;
        font-weight: 600;
        white-space: nowrap;
    }

    .commerce-card-header-action .material-icons-outlined {
        font-size: 17px;
    }

    /*
    |--------------------------------------------------------------------------
    | Filter form
    |--------------------------------------------------------------------------
    */

    .commerce-filter-card .card-body {
        padding: 20px;
    }

    .commerce-filter-card .form-label {
        margin-bottom: 7px;
        color: #354052;
        font-size: 13px;
        font-weight: 600;
    }

    .commerce-filter-card .form-control,
    .commerce-filter-card .form-select,
    .commerce-filter-card .input-group-text {
        min-height: 42px;
        border-color: #dce2eb;
    }

    .commerce-filter-card .form-control,
    .commerce-filter-card .form-select {
        color: #263147;
        font-size: 14px;
    }

    .commerce-filter-card .form-control::placeholder {
        color: #a1a9b7;
    }

    .commerce-filter-card .form-control:focus,
    .commerce-filter-card .form-select:focus {
        border-color: #198778;
        box-shadow: 0 0 0 3px rgba(25, 135, 120, 0.12);
    }

    .commerce-filter-card .input-group-text {
        background: #f7f9fc;
        color: #778196;
    }

    .commerce-filter-card .input-group-text .material-icons-outlined {
        font-size: 19px;
    }

    .commerce-date-range {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 30px minmax(0, 1fr);
        align-items: end;
        gap: 8px;
    }

    .commerce-date-field {
        min-width: 0;
    }

    .commerce-date-field-label {
        display: block;
        margin-bottom: 6px;
        color: #8a94a6;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .commerce-date-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 42px;
        color: #99a3b5;
    }

    .commerce-date-separator .material-icons-outlined {
        font-size: 18px;
    }

    .commerce-filter-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 4px;
    }

    .commerce-filter-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 40px;
        border-radius: 9px;
        font-weight: 600;
    }

    .commerce-filter-actions .material-icons-outlined {
        font-size: 18px;
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .commerce-table {
        width: 100%;
        min-width: 1080px;
        margin: 0;
    }

    .commerce-table thead th {
        padding: 13px 16px;
        border-bottom: 1px solid #dde3ec;
        background: #f8fafc;
        color: #667085;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.3;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }

    .commerce-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #edf0f4;
        color: #263147;
        font-size: 13px;
        vertical-align: middle;
    }

    .commerce-table tbody tr {
        transition: background-color 0.18s ease;
    }

    .commerce-table tbody tr:hover {
        background: #fbfcfe;
    }

    .commerce-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .commerce-code-column {
        min-width: 150px;
    }

    .commerce-customer-column {
        min-width: 235px;
    }

    .commerce-content-column {
        min-width: 180px;
    }

    .commerce-date-column {
        min-width: 125px;
    }

    .commerce-amount-column {
        min-width: 125px;
    }

    .commerce-status-column {
        min-width: 140px;
    }

    .commerce-created-column {
        min-width: 115px;
    }

    .commerce-action-column {
        width: 105px;
        min-width: 105px;
    }

    /*
    |--------------------------------------------------------------------------
    | Code
    |--------------------------------------------------------------------------
    */

    .commerce-code {
        color: #147a6d;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .commerce-code:hover {
        color: #0f655b;
        text-decoration: underline;
    }

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    .commerce-customer {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .commerce-customer-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #eef6f4;
        color: #198778;
        font-size: 14px;
        font-weight: 700;
    }

    .commerce-customer-info {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 2px;
    }

    .commerce-customer-info strong {
        overflow: hidden;
        max-width: 185px;
        color: #263147;
        font-size: 13px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .commerce-customer-info a,
    .commerce-customer-info span {
        display: flex;
        align-items: center;
        gap: 4px;
        overflow: hidden;
        max-width: 195px;
        color: #7b8799;
        font-size: 12px;
        font-weight: 400;
        text-decoration: none;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .commerce-customer-info a:hover {
        color: #198778;
        text-decoration: underline;
    }

    .commerce-customer-info .material-icons-outlined {
        flex: 0 0 auto;
        font-size: 13px;
    }

    /*
    |--------------------------------------------------------------------------
    | Main content cells
    |--------------------------------------------------------------------------
    */

    .commerce-primary-info {
        display: flex;
        max-width: 210px;
        flex-direction: column;
        gap: 3px;
    }

    .commerce-primary-info strong {
        display: -webkit-box;
        overflow: hidden;
        color: #263147;
        font-size: 13px;
        font-weight: 650;
        line-height: 1.4;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .commerce-primary-info span {
        color: #8490a3;
        font-size: 12px;
        line-height: 1.4;
    }

    .commerce-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .commerce-meta > .material-icons-outlined {
        flex: 0 0 auto;
        color: #8c97a9;
        font-size: 18px;
    }

    .commerce-meta-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .commerce-meta-content strong {
        color: #344054;
        font-size: 13px;
        font-weight: 650;
    }

    .commerce-meta-content small,
    .commerce-meta-content span {
        color: #8a94a6;
        font-size: 11px;
    }

    .commerce-amount {
        color: #182033;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    /*
    |--------------------------------------------------------------------------
    | Status badges
    |--------------------------------------------------------------------------
    */

    .commerce-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 155px;
        padding: 6px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 650;
        line-height: 1.2;
        white-space: nowrap;
    }

    .commerce-status-dot {
        flex: 0 0 6px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.8;
    }

    .commerce-status-badge.bg-label-primary {
        background: #e8f1ff !important;
        color: #2868c7 !important;
    }

    .commerce-status-badge.bg-label-info {
        background: #e7f5fb !important;
        color: #187a9c !important;
    }

    .commerce-status-badge.bg-label-warning {
        background: #fff4dc !important;
        color: #9c6500 !important;
    }

    .commerce-status-badge.bg-label-success {
        background: #e8f7ef !important;
        color: #198754 !important;
    }

    .commerce-status-badge.bg-label-danger {
        background: #fdebec !important;
        color: #c33c47 !important;
    }

    .commerce-status-badge.bg-label-secondary {
        background: #edf0f4 !important;
        color: #667085 !important;
    }

    .commerce-status-badge.bg-label-dark {
        background: #e9ebef !important;
        color: #3d4655 !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Action button
    |--------------------------------------------------------------------------
    */

    .commerce-detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 34px;
        border-radius: 8px;
        font-weight: 600;
        white-space: nowrap;
    }

    .commerce-detail-button .material-icons-outlined {
        font-size: 17px;
    }

    /*
    |--------------------------------------------------------------------------
    | Empty state
    |--------------------------------------------------------------------------
    */

    .commerce-empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 260px;
        padding: 32px;
        flex-direction: column;
        text-align: center;
    }

    .commerce-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin-bottom: 14px;
        border-radius: 16px;
        background: #f1f5f9;
        color: #8792a5;
    }

    .commerce-empty-icon .material-icons-outlined {
        font-size: 29px;
    }

    .commerce-empty-state h6 {
        margin-bottom: 5px;
        color: #263147;
        font-weight: 700;
    }

    .commerce-empty-state p {
        max-width: 420px;
        margin-bottom: 16px;
        color: #8792a5;
        font-size: 13px;
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    .commerce-pagination {
        padding: 14px 18px;
        border-top: 1px solid #edf0f4;
        background: #ffffff;
    }

    .commerce-pagination nav {
        width: 100%;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199.98px) {
        .commerce-page-summary {
            display: none;
        }
    }

    @media (max-width: 767.98px) {
        .commerce-page {
            padding-bottom: 20px;
        }

        .commerce-page-header {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 18px;
        }

        .commerce-page-title h4 {
            font-size: 19px;
        }

        .commerce-page-title p {
            font-size: 13px;
        }

        .commerce-card-header {
            align-items: flex-start;
            min-height: auto;
            padding: 15px 16px;
            flex-direction: column;
        }

        .commerce-filter-card .card-body {
            padding: 16px;
        }

        .commerce-date-range {
            grid-template-columns: 1fr;
        }

        .commerce-date-separator {
            display: none;
        }

        .commerce-filter-actions {
            align-items: stretch;
            justify-content: stretch;
            flex-direction: column;
        }

        .commerce-filter-actions .btn {
            width: 100%;
        }

        .commerce-detail-text {
            display: none;
        }

        .commerce-action-column {
            width: 58px;
            min-width: 58px;
        }
    }
</style>