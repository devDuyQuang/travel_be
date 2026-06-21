@extends('index')

@section('title', page_title())

@section('content')
@php
    $module = module();

    $editRouteTemplate = panel_route(
        $module . '.edit',
        ['id' => '__ID__']
    );

    $deleteRouteTemplate = panel_route(
        $module . '.destroy',
        ['id' => '__ID__']
    );

    $toggleStatusTemplate = panel_route(
        $module . '.toggle-status',
        ['id' => '__ID__']
    );

    $toggleHomeTemplate = panel_route(
        $module . '.toggle-home',
        ['id' => '__ID__']
    );

    $editRouteJson = json_encode(
        $editRouteTemplate,
        JSON_UNESCAPED_SLASHES
    );

    $deleteRouteJson = json_encode(
        $deleteRouteTemplate,
        JSON_UNESCAPED_SLASHES
    );

    $toggleStatusJson = json_encode(
        $toggleStatusTemplate,
        JSON_UNESCAPED_SLASHES
    );

    $toggleHomeJson = json_encode(
        $toggleHomeTemplate,
        JSON_UNESCAPED_SLASHES
    );

    /*
    |--------------------------------------------------------------------------
    | Drag handle
    |--------------------------------------------------------------------------
    */

    $dragHandleRender = <<<'JS'
        if (type !== 'display') {
            return '';
        }

        return (
            '<button ' +
                'type="button" ' +
                'class="category-drag-handle" ' +
                'title="Kéo để sắp xếp" ' +
                'aria-label="Kéo để sắp xếp">' +

                '<span class="material-icons-outlined">' +
                    'drag_indicator' +
                '</span>' +
            '</button>'
        );
    JS;

    /*
    |--------------------------------------------------------------------------
    | Name column
    |--------------------------------------------------------------------------
    */

    $nameRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const buildPublicUrl = (rawPath) => {
            const value = String(rawPath || '').trim();

            if (!value) {
                return '';
            }

            if (/^https?:\/\//i.test(value)) {
                return value;
            }

            const protocol = window.location.protocol;
            let host = window.location.hostname;

            if (
                host === 'localhost' ||
                host === 'cms.localhost'
            ) {
                host = 'localhost:3000';
            } else {
                host = host
                    .replace(/^cms\./i, '')
                    .replace(/^admin\./i, '');
            }

            return (
                protocol +
                '//' +
                host +
                '/' +
                value.replace(/^\/+/, '')
            );
        };

        const depth = Number.parseInt(
            row?.depth ?? 0,
            10
        ) || 0;

        const name = escapeHtml(
            row?.name ||
            data ||
            '—'
        );

        const href = buildPublicUrl(
            row?.public_url || ''
        );

        const createdAt = escapeHtml(
            row?.created_at || '—'
        );

        const creatorName = escapeHtml(
            row?.creator_name ||
            row?.creator ||
            '—'
        );

        const updatedAt = escapeHtml(
            row?.updated_at || '—'
        );

        const updaterName = escapeHtml(
            row?.updater_name ||
            row?.updater ||
            '—'
        );

        const popoverContent =
            '<div class="category-meta-popover">' +

                '<div class="category-meta-row">' +
                    '<strong>Ngày tạo:</strong>' +
                    '<span>' + createdAt + '</span>' +
                '</div>' +

                '<div class="category-meta-row">' +
                    '<strong>Người tạo:</strong>' +
                    '<span>' + creatorName + '</span>' +
                '</div>' +

                '<hr class="my-2">' +

                '<div class="category-meta-row">' +
                    '<strong>Cập nhật cuối:</strong>' +
                    '<span>' + updatedAt + '</span>' +
                '</div>' +

                '<div class="category-meta-row">' +
                    '<strong>Người cập nhật:</strong>' +
                    '<span>' + updaterName + '</span>' +
                '</div>' +

            '</div>';

        const treePrefix = depth > 0
            ? (
                '<span class="category-tree-prefix">' +
                    '<span class="material-icons-outlined">' +
                        'subdirectory_arrow_right' +
                    '</span>' +
                '</span>'
            )
            : '';

        let iconsHtml = '';

        if (href) {
            iconsHtml +=
                '<a ' +
                    'href="' + href.replace(/"/g, '&quot;') + '" ' +
                    'target="_blank" ' +
                    'rel="noopener noreferrer" ' +
                    'class="category-name-icon category-name-icon-link js-category-tooltip" ' +
                    'data-bs-toggle="tooltip" ' +
                    'data-bs-placement="top" ' +
                    'title="Mở trên website">' +

                    '<span class="material-icons-outlined">' +
                        'open_in_new' +
                    '</span>' +
                '</a>';
        }

        iconsHtml +=
            '<button ' +
                'type="button" ' +
                'class="category-name-icon category-name-icon-info category-meta-trigger" ' +
                'data-bs-toggle="popover" ' +
                'data-bs-placement="left" ' +
                'data-bs-html="true" ' +
                'data-bs-trigger="hover focus" ' +
                'data-bs-content="' +
                    popoverContent.replace(/"/g, '&quot;') +
                '" ' +
                'aria-label="Xem thông tin">' +

                '<span class="material-icons-outlined">' +
                    'info' +
                '</span>' +
            '</button>';

        return (
            '<div ' +
                'class="category-name-with-icons" ' +
                'style="padding-left:' + (depth * 18) + 'px">' +

                '<div class="category-name-content">' +
                    treePrefix +

                    '<span class="category-name-text">' +
                        name +
                    '</span>' +
                '</div>' +

                '<span class="category-name-icons">' +
                    iconsHtml +
                '</span>' +

            '</div>'
        );
    JS;

    /*
    |--------------------------------------------------------------------------
    | Type badge
    |--------------------------------------------------------------------------
    */

    $typeRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        const value = String(
            row?.type ||
            data ||
            ''
        ).toLowerCase();

        if (!value) {
            return '<span class="text-muted">—</span>';
        }

        const className = value === 'service'
            ? 'is-service'
            : 'is-post';

        const label = value === 'service'
            ? 'Dịch vụ'
            : 'Bài viết';

        return (
            '<span class="category-type-badge ' +
                className +
            '">' +
                label +
            '</span>'
        );
    JS;

    $layoutRender = <<<'JS'
        if (type !== 'display') {
            return row?.layout_label || data || '';
        }

        const label = String(row?.layout_label || data || 'Mặc định');
        const hasLayout = Boolean(row?.layout_key);
        const className = hasLayout ? 'has-layout' : 'is-default';

        return (
            '<span class="category-layout-badge ' +
                className +
            '">' +
                label +
            '</span>'
        );
    JS;

    /*
    |--------------------------------------------------------------------------
    | Homepage switch
    |--------------------------------------------------------------------------
    */

    $homeRender = <<<JS
        if (type !== 'display') {
            return data;
        }

        const id = row.id;
        const isEnabled =
            Number.parseInt(row.home, 10) === 1;

        const toggleUrl = id
            ? {$toggleHomeJson}.replace('__ID__', id)
            : '';

        return (
            '<div class="category-switch-wrapper">' +

                '<input ' +
                    'type="checkbox" ' +
                    'class="form-check-input category-home-toggle category-switch" ' +
                    'data-id="' + id + '" ' +
                    'data-url="' +
                        toggleUrl.replace(/"/g, '&quot;') +
                    '" ' +
                    (isEnabled ? 'checked' : '') +
                '>' +

            '</div>'
        );
    JS;

    /*
    |--------------------------------------------------------------------------
    | Status switch
    |--------------------------------------------------------------------------
    */

    $statusRender = <<<JS
        if (type !== 'display') {
            return data;
        }

        const id = row.id;
        const isEnabled =
            Number.parseInt(row.status, 10) === 1;

        const toggleUrl = id
            ? {$toggleStatusJson}.replace('__ID__', id)
            : '';

        return (
            '<div class="category-switch-wrapper">' +

                '<input ' +
                    'type="checkbox" ' +
                    'class="form-check-input category-status-toggle category-switch" ' +
                    'data-id="' + id + '" ' +
                    'data-url="' +
                        toggleUrl.replace(/"/g, '&quot;') +
                    '" ' +
                    (isEnabled ? 'checked' : '') +
                '>' +

            '</div>'
        );
    JS;

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    $actionsRender = <<<JS
        if (type !== 'display') {
            return '';
        }

        const id = row.id ?? '';
        const name = row.name ?? '';

        const editUrl = id
            ? {$editRouteJson}.replace('__ID__', id)
            : 'javascript:void(0)';

        const deleteUrl = id
            ? {$deleteRouteJson}.replace('__ID__', id)
            : 'javascript:void(0)';

        const escapeAttribute = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        return (
            '<div class="category-action-icons">' +

                '<a ' +
                    'href="' + escapeAttribute(editUrl) + '" ' +
                    'class="category-action-icon category-action-edit js-category-tooltip" ' +
                    'data-bs-toggle="tooltip" ' +
                    'data-bs-placement="top" ' +
                    'title="Chỉnh sửa">' +

                    '<span class="material-icons-outlined">' +
                        'edit' +
                    '</span>' +
                '</a>' +

                '<button ' +
                    'type="button" ' +
                    'class="category-action-icon category-action-delete btn-delete js-category-tooltip" ' +
                    'data-id="' + escapeAttribute(id) + '" ' +
                    'data-name="' + escapeAttribute(name) + '" ' +
                    'data-url="' + escapeAttribute(deleteUrl) + '" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#deleteModal" ' +
                    'data-bs-placement="top" ' +
                    'title="Xóa">' +

                    '<span class="material-icons-outlined">' +
                        'delete' +
                    '</span>' +
                '</button>' +

            '</div>'
        );
    JS;

    $columns = [
        [
            'key' => 'drag_handle',
            'title' => '',
        ],
        [
            'key' => 'name',
            'title' => 'TÊN DANH MỤC',
        ],
        [
            'key' => 'type',
            'title' => 'LOẠI',
        ],
        [
            'key' => 'layout_label',
            'title' => 'GIAO DIỆN',
        ],
        [
            'key' => 'home',
            'title' => 'TRANG CHỦ',
        ],
        [
            'key' => 'status',
            'title' => 'TRẠNG THÁI',
        ],
        [
            'key' => 'actions',
            'title' => 'HÀNH ĐỘNG',
        ],
    ];

    $options = [
        'control' => false,
        'ordering' => false,
        'order' => [],
        'responsive' => false,
        'responsiveModal' => false,
        'autoWidth' => false,
        'scrollX' => false,

        /*
         * Không để 500 vì select số dòng không có option 500
         * nên giao diện sẽ hiện ô trống.
         */
        'pageLength' => 10,
        'lengthMenu' => [10, 25, 50, 100],

        'pagingType' => 'full_numbers',
        'searchPlaceholder' => 'Tìm danh mục...',

        'language' => [
            'lengthMenu' => 'Hiển thị _MENU_ dòng',
            'search' => 'Tìm kiếm:',
            'info' => 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
            'infoEmpty' => 'Hiển thị 0 đến 0 của 0 dòng',
            'infoFiltered' => '(lọc từ _MAX_ dòng)',
            'zeroRecords' => 'Không tìm thấy danh mục phù hợp',
            'emptyTable' => 'Chưa có dữ liệu danh mục',

            'paginate' => [
                'first' => '«',
                'previous' => '‹',
                'next' => '›',
                'last' => '»',
            ],
        ],

        'rendersByKey' => [
            'drag_handle' => $dragHandleRender,
            'name' => $nameRender,
            'type' => $typeRender,
            'layout_label' => $layoutRender,
            'home' => $homeRender,
            'status' => $statusRender,
            'actions' => $actionsRender,
        ],

        'columnDefs' => [
            [
                'targets' => 0,
                'orderable' => false,
                'searchable' => false,
                'width' => '52px',
                'className' =>
                    'text-center text-nowrap category-drag-col',
            ],
            [
                'targets' => 1,
                'className' =>
                    'text-start category-name-col',
            ],
            [
                'targets' => 2,
                'width' => '112px',
                'className' =>
                    'text-center text-nowrap category-type-col',
            ],
            [
                'targets' => 3,
                'width' => '168px',
                'className' =>
                    'text-center text-nowrap category-layout-col',
            ],
            [
                'targets' => 4,
                'orderable' => false,
                'searchable' => false,
                'width' => '108px',
                'className' =>
                    'text-center text-nowrap category-home-col',
            ],
            [
                'targets' => 5,
                'orderable' => false,
                'searchable' => false,
                'width' => '116px',
                'className' =>
                    'text-center text-nowrap category-status-col',
            ],
            [
                'targets' => 6,
                'orderable' => false,
                'searchable' => false,
                'width' => '136px',
                'className' =>
                    'text-center text-nowrap category-action-col',
            ],
        ],
    ];
@endphp

@include('partials.css.category')

<main
    class="main-wrapper category-list-page"
    data-update-order-url="{{ panel_route(module().'.update-order') }}"
>
    <div class="main-content">
        <div class="category-page-shell">

            <div class="category-page-header">
                <div class="category-page-heading">
                    <div class="category-page-icon">
                        <span class="material-icons-outlined">
                            category
                        </span>
                    </div>

                    <div>
                        <h4 class="category-page-title">
                            Quản lý danh mục
                        </h4>

                        <p class="category-page-subtitle">
                            Phân nhóm dịch vụ, bài viết và kiểm soát nội dung
                            hiển thị trên website.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.create') }}"
                    class="btn btn-primary category-create-btn"
                >
                    <span class="material-icons-outlined">
                        add
                    </span>

                    <span>Thêm danh mục</span>
                </a>
            </div>

            <div class="category-table-card">
                <div class="category-table-card-header">
                    <div>
                        <h6 class="category-table-title">
                            Danh sách danh mục
                        </h6>

                        <p class="category-table-description">
                            Kéo biểu tượng ở đầu dòng để thay đổi thứ tự.
                        </p>
                    </div>

                    <div class="category-table-status">
                        <span class="category-table-status-dot"></span>
                        Dữ liệu đang hoạt động
                    </div>
                </div>

                <div class="category-table-body">
                    <x-data-table
                        id="reload-table"
                        :columns="$columns"
                        ajax-url="{{ panel_route(module().'.datatable') }}"
                        :options="$options"
                    />
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@include('partials.js.category')
