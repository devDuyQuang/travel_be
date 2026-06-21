@extends('index')

@section('title', 'Quản Lý Sản Phẩm')

@section('content')
@php
    $module = module();

    $editRouteTpl = panel_route(
        $module . '.edit',
        ['id' => '__ID__']
    );

    $deleteRouteTpl = panel_route(
        $module . '.destroy',
        ['id' => '__ID__']
    );

    $toggleStatusTpl = panel_route(
        $module . '.toggle-status',
        ['id' => '__ID__']
    );

    $editRouteTplJson = json_encode(
        $editRouteTpl,
        JSON_UNESCAPED_SLASHES
    );

    $deleteRouteTplJson = json_encode(
        $deleteRouteTpl,
        JSON_UNESCAPED_SLASHES
    );

    $toggleStatusTplJson = json_encode(
        $toggleStatusTpl,
        JSON_UNESCAPED_SLASHES
    );

    $indexRender = <<<'JS'
        return meta.row + meta.settings._iDisplayStart + 1;
    JS;

    $imageRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        if (!data) {
            return (
                '<span class="product-image-placeholder">' +
                    '<span class="material-icons-outlined">image</span>' +
                '</span>'
            );
        }

        const imageUrl = String(data)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;');

        return (
            '<img ' +
                'src="' + imageUrl + '" ' +
                'class="product-thumb" ' +
                'alt="Ảnh sản phẩm" ' +
                'loading="lazy">'
        );
    JS;

    $nameRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        const name = escapeHtml(row?.name || '—');
        const slug = escapeHtml(row?.slug || '—');
        const creator = escapeHtml(
            row?.creator_name ||
            row?.creator ||
            '—'
        );

        const createdAt = escapeHtml(
            row?.created_at || '—'
        );

        const updatedAt = escapeHtml(
            row?.updated_at || '—'
        );

        const popoverContent =
            '<div class="product-meta-popover">' +

                '<div class="product-meta-row">' +
                    '<strong>Slug:</strong>' +
                    '<span>' + slug + '</span>' +
                '</div>' +

                '<div class="product-meta-row">' +
                    '<strong>Ngày tạo:</strong>' +
                    '<span>' + createdAt + '</span>' +
                '</div>' +

                '<div class="product-meta-row">' +
                    '<strong>Người tạo:</strong>' +
                    '<span>' + creator + '</span>' +
                '</div>' +

                '<div class="product-meta-row">' +
                    '<strong>Cập nhật:</strong>' +
                    '<span>' + updatedAt + '</span>' +
                '</div>' +

            '</div>';

        return (
            '<div class="product-name-with-icons">' +

                '<span class="product-name-text">' +
                    name +
                '</span>' +

                '<button ' +
                    'type="button" ' +
                    'class="btn p-0 border-0 bg-transparent product-name-icon product-meta-trigger" ' +
                    'data-bs-toggle="popover" ' +
                    'data-bs-placement="left" ' +
                    'data-bs-trigger="hover focus" ' +
                    'data-bs-html="true" ' +
                    'data-bs-content="' +
                        popoverContent.replace(/"/g, '&quot;') +
                    '">' +

                    '<span class="material-icons-outlined">' +
                        'info' +
                    '</span>' +

                '</button>' +

            '</div>'
        );
    JS;

    $categoryRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        if (!data || data === '—') {
            return '<span class="text-muted">—</span>';
        }

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        return (
            '<span class="product-category-badge">' +
                escapeHtml(data) +
            '</span>'
        );
    JS;

    $priceRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        if (
            data === null ||
            data === undefined ||
            data === ''
        ) {
            return '<span class="text-muted">—</span>';
        }

        const value = Number(data);

        if (Number.isNaN(value)) {
            return '<span class="text-muted">—</span>';
        }

        return (
            '<span class="product-price">' +
                value.toLocaleString('vi-VN') +
                ' ₫' +
            '</span>'
        );
    JS;

    $priceDiscountRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        if (
            data === null ||
            data === undefined ||
            data === ''
        ) {
            return '<span class="text-muted">—</span>';
        }

        const value = Number(data);

        if (Number.isNaN(value)) {
            return '<span class="text-muted">—</span>';
        }

        return (
            '<span class="product-price-discount">' +
                value.toLocaleString('vi-VN') +
                ' ₫' +
            '</span>'
        );
    JS;

    $statusRender = <<<JS
        if (type !== 'display') {
            return data;
        }

        const id = row.id;
        const isEnabled =
            parseInt(row.status, 10) === 1;

        const toggleUrl = id
            ? {$toggleStatusTplJson}.replace('__ID__', id)
            : '';

        return (
            '<div class="form-check form-switch form-check-inline mb-0">' +

                '<input ' +
                    'type="checkbox" ' +
                    'class="form-check-input product-status-toggle status-toggle-wide" ' +
                    'data-id="' + id + '" ' +
                    'data-url="' +
                        toggleUrl.replace(/"/g, '&quot;') +
                    '" ' +
                    (isEnabled ? 'checked' : '') +
                '>' +

            '</div>'
        );
    JS;

    $actionsRender = <<<JS
        if (type !== 'display') {
            return '';
        }

        const id = row.id ?? '';
        const name = row.name ?? '';

        const editUrl = id
            ? {$editRouteTplJson}.replace('__ID__', id)
            : 'javascript:void(0)';

        const deleteUrl = id
            ? {$deleteRouteTplJson}.replace('__ID__', id)
            : 'javascript:void(0)';

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        return (
            '<div class="product-action-icons">' +

                '<a ' +
                    'href="' + escapeHtml(editUrl) + '" ' +
                    'class="product-action-icon product-action-edit js-product-tooltip" ' +
                    'data-bs-toggle="tooltip" ' +
                    'data-bs-placement="top" ' +
                    'title="Chỉnh sửa">' +

                    '<span class="material-icons-outlined">' +
                        'edit' +
                    '</span>' +

                '</a>' +

                '<a ' +
                    'href="javascript:void(0)" ' +
                    'class="product-action-icon product-action-delete btn-delete js-product-tooltip" ' +
                    'data-id="' + escapeHtml(id) + '" ' +
                    'data-name="' + escapeHtml(name) + '" ' +
                    'data-url="' + escapeHtml(deleteUrl) + '" ' +
                    'data-bs-toggle="modal" ' +
                    'data-bs-target="#deleteModal" ' +
                    'data-bs-placement="top" ' +
                    'title="Xóa">' +

                    '<span class="material-icons-outlined">' +
                        'delete' +
                    '</span>' +

                '</a>' +

            '</div>'
        );
    JS;

    $options = [
        'control' => false,
        'ordering' => false,
        'order' => [],
        'responsive' => false,
        'responsiveModal' => false,
        'autoWidth' => false,
        'scrollX' => false,
        'pageLength' => 10,
        'pagingType' => 'full_numbers',
        'searchPlaceholder' => 'Nhập từ khóa...',

        'language' => [
            'lengthMenu' =>
                'Hiển thị _MENU_ dòng',

            'search' =>
                'Tìm kiếm:',

            'info' =>
                'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',

            'infoEmpty' =>
                'Hiển thị 0 đến 0 của 0 dòng',

            'infoFiltered' =>
                '(lọc từ _MAX_ dòng)',

            'zeroRecords' =>
                'Không tìm thấy dữ liệu phù hợp',

            'emptyTable' =>
                'Không có dữ liệu',

            'paginate' => [
                'first' => '«',
                'previous' => '‹',
                'next' => '›',
                'last' => '»',
            ],
        ],

        'rendersByKey' => [
            'index' => $indexRender,
            'image' => $imageRender,
            'name' => $nameRender,
            'category' => $categoryRender,
            'price' => $priceRender,
            'price_discount' => $priceDiscountRender,
            'status' => $statusRender,
            'actions' => $actionsRender,
        ],

      'columnDefs' => [
    [
        'targets' => 0,
        'width' => '58px',
        'orderable' => false,
        'searchable' => false,
        'className' => 'text-center text-nowrap product-index-col',
    ],
    [
        'targets' => 1,
        'width' => '78px',
        'orderable' => false,
        'searchable' => false,
        'className' => 'text-center text-nowrap product-image-col',
    ],
    [
        'targets' => 2,
        'className' => 'text-start product-name-col',
    ],
    [
        'targets' => 3,
        'width' => '210px',
        'className' => 'text-start product-category-col',
    ],
    [
        'targets' => 4,
        'width' => '126px',
        'className' => 'text-center text-nowrap product-price-col',
    ],
    [
        'targets' => 5,
        'width' => '126px',
        'className' => 'text-center text-nowrap product-discount-col',
    ],
    [
        'targets' => 6,
        'width' => '112px',
        'orderable' => false,
        'searchable' => false,
        'className' => 'text-center text-nowrap product-status-col',
    ],
    [
        'targets' => 7,
        'width' => '126px',
        'orderable' => false,
        'searchable' => false,
        'className' => 'text-center text-nowrap product-action-col',
    ],
],
    ];
@endphp

@include('partials.css.product')

<main class="main-wrapper product-list-page">
    <div class="main-content">
        <div class="product-page-shell">

            <div class="product-page-header">
                <div class="product-page-heading">
                    <div class="product-page-icon">
                        <span class="material-icons-outlined">
                            inventory_2
                        </span>
                    </div>

                    <div>
                        <h4 class="product-page-title">
                            Quản lý sản phẩm
                        </h4>

                        <p class="product-page-subtitle">
                            Quản lý dịch vụ, tour, khách sạn và sản phẩm
                            đang hiển thị trên website.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.create') }}"
                    class="btn btn-primary product-create-btn"
                >
                    <span class="material-icons-outlined">
                        add
                    </span>

                    <span>Thêm sản phẩm</span>
                </a>
            </div>

            <div class="product-table-card">
                <div class="product-table-card-header">
                    <div>
                        <h6 class="product-table-title">
                            Danh sách sản phẩm
                        </h6>

                        <p class="product-table-description">
                            Theo dõi giá, danh mục và trạng thái
                            hiển thị của sản phẩm.
                        </p>
                    </div>

                    <div class="product-table-status">
                        <span class="product-table-status-dot"></span>
                        Dữ liệu đang hoạt động
                    </div>
                </div>

                <div class="product-table-body">
                    <x-data-table
                        id="reload-table"
                        :columns="[
                            [
                                'key' => 'index',
                                'title' => 'STT'
                            ],
                            [
                                'key' => 'image',
                                'title' => 'ẢNH'
                            ],
                            [
                                'key' => 'name',
                                'title' => 'TÊN SẢN PHẨM'
                            ],
                            [
                                'key' => 'category',
                                'title' => 'DANH MỤC'
                            ],
                            [
                                'key' => 'price',
                                'title' => 'GIÁ'
                            ],
                            [
                                'key' => 'price_discount',
                                'title' => 'GIÁ KM'
                            ],
                            [
                                'key' => 'status',
                                'title' => 'TRẠNG THÁI'
                            ],
                            [
                                'key' => 'actions',
                                'title' => 'HÀNH ĐỘNG'
                            ]
                        ]"
                        ajax-url="{{ panel_route(module().'.datatable') }}"
                        :options="$options"
                    />
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@include('partials.js.product')
