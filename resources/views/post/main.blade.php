@extends('index')

@section('title', 'Bài Viết')

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

    $categoriesRender = <<<'JS'
        if (type !== 'display') {
            return data;
        }

        const list = Array.isArray(row?.categories)
            ? row.categories
            : [];

        if (!list.length) {
            return '<span class="text-muted">—</span>';
        }

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        return list.map(function (name) {
            return (
                '<span class="post-category-badge">' +
                    escapeHtml(name) +
                '</span>'
            );
        }).join('');
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

        const name = escapeHtml(row?.name || '—');
        const href = buildPublicUrl(row?.public_url || '');

        const createdAt = escapeHtml(
            row?.created_at || '—'
        );

        const creator = escapeHtml(
            row?.creator_name ||
            row?.creator ||
            '—'
        );

        const updatedAt = escapeHtml(
            row?.updated_at || '—'
        );

        const updater = escapeHtml(
            row?.updater_name ||
            row?.updater ||
            '—'
        );

        const popoverContent =
            '<div class="post-meta-popover">' +

                '<div class="post-meta-row">' +
                    '<strong>Ngày tạo:</strong>' +
                    '<span>' + createdAt + '</span>' +
                '</div>' +

                '<div class="post-meta-row">' +
                    '<strong>Người tạo:</strong>' +
                    '<span>' + creator + '</span>' +
                '</div>' +

                '<hr class="my-2">' +

                '<div class="post-meta-row">' +
                    '<strong>Cập nhật cuối:</strong>' +
                    '<span>' + updatedAt + '</span>' +
                '</div>' +

                '<div class="post-meta-row">' +
                    '<strong>Người cập nhật:</strong>' +
                    '<span>' + updater + '</span>' +
                '</div>' +

            '</div>';

        let iconsHtml = '';

        if (href) {
            iconsHtml +=
                '<a ' +
                    'href="' + href.replace(/"/g, '&quot;') + '" ' +
                    'target="_blank" ' +
                    'rel="noopener noreferrer" ' +
                    'class="post-name-icon post-name-icon-link js-post-tooltip" ' +
                    'data-bs-toggle="tooltip" ' +
                    'data-bs-placement="top" ' +
                    'title="Mở đường dẫn">' +

                    '<span class="material-icons-outlined">' +
                        'open_in_new' +
                    '</span>' +

                '</a>';
        }

        iconsHtml +=
            '<button ' +
                'type="button" ' +
                'class="btn p-0 border-0 bg-transparent post-name-icon post-name-icon-info post-meta-trigger" ' +
                'data-bs-toggle="popover" ' +
                'data-bs-placement="left" ' +
                'data-bs-html="true" ' +
                'data-bs-trigger="hover focus" ' +
                'data-bs-content="' +
                    popoverContent.replace(/"/g, '&quot;') +
                '">' +

                '<span class="material-icons-outlined">' +
                    'info' +
                '</span>' +

            '</button>';

        return (
            '<div class="post-name-with-icons">' +

                '<span class="post-name-text">' +
                    name +
                '</span>' +

                '<span class="post-name-icons">' +
                    iconsHtml +
                '</span>' +

            '</div>'
        );
    JS;

    $statusRender = <<<JS
        if (type !== 'display') {
            return data;
        }

        const id = row.id;
        const isEnabled = parseInt(row.status, 10) === 1;

        const toggleUrl = id
            ? {$toggleStatusTplJson}.replace('__ID__', id)
            : '';

        return (
            '<div class="form-check form-switch form-check-inline mb-0">' +

                '<input ' +
                    'type="checkbox" ' +
                    'class="form-check-input post-status-toggle status-toggle-wide" ' +
                    'data-id="' + id + '" ' +
                    'data-url="' +
                        toggleUrl.replace(/"/g, '&quot;') +
                    '" ' +
                    (isEnabled ? 'checked' : '') +
                '>' +

            '</div>'
        );
    JS;

    $actionsRenderByKey = <<<JS
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

        const escapeAction = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        return (
            '<div class="post-action-icons">' +

                '<a ' +
                    'href="' + escapeAction(editUrl) + '" ' +
                    'class="post-action-icon post-action-edit js-post-tooltip" ' +
                    'data-bs-toggle="tooltip" ' +
                    'data-bs-placement="top" ' +
                    'title="Chỉnh sửa">' +

                    '<span class="material-icons-outlined">' +
                        'edit' +
                    '</span>' +

                '</a>' +

                '<a ' +
                    'href="javascript:void(0)" ' +
                    'class="post-action-icon post-action-delete btn-delete js-post-tooltip" ' +
                    'data-id="' + escapeAction(id) + '" ' +
                    'data-name="' + escapeAction(name) + '" ' +
                    'data-url="' + escapeAction(deleteUrl) + '" ' +
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
            'lengthMenu' => 'Hiển thị _MENU_ dòng',
            'search' => 'Tìm kiếm:',
            'info' => 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
            'infoEmpty' => 'Hiển thị 0 đến 0 của 0 dòng',
            'infoFiltered' => '(lọc từ _MAX_ dòng)',
            'zeroRecords' => 'Không tìm thấy dữ liệu phù hợp',
            'emptyTable' => 'Không có dữ liệu',

            'paginate' => [
                'first' => '«',
                'previous' => '‹',
                'next' => '›',
                'last' => '»',
            ],
        ],

        'rendersByKey' => [
            'index' => $indexRender,
            'name' => $nameRender,
            'categories' => $categoriesRender,
            'status' => $statusRender,
            'actions' => $actionsRenderByKey,
        ],

        'columnDefs' => [
            [
                'targets' => 0,
                'orderable' => false,
                'searchable' => false,
                'className' => 'text-center text-nowrap post-index-col',
            ],
            [
                'targets' => 1,
                'className' => 'text-start post-name-col',
            ],
            [
                'targets' => 2,
                'className' => 'text-start post-category-col',
            ],
            [
                'targets' => 3,
                'orderable' => false,
                'searchable' => false,
                'className' => 'text-center text-nowrap post-status-col',
            ],
            [
                'targets' => 4,
                'orderable' => false,
                'searchable' => false,
                'className' => 'text-center text-nowrap post-action-col',
            ],
        ],
    ];
@endphp

@include('partials.css.post')

<main class="main-wrapper post-list-page">
    <div class="main-content">
        <div class="post-page-shell">

            <div class="post-page-header">
                <div class="post-page-heading">
                    <div class="post-page-icon">
                        <span class="material-icons-outlined">
                            article
                        </span>
                    </div>

                    <div>
                        <h4 class="post-page-title">
                            Quản lý bài viết
                        </h4>

                        <p class="post-page-subtitle">
                            Quản lý tin tức, bài chia sẻ và nội dung truyền thông trên website.
                            và các bài viết trên website.
                        </p>
                    </div>
                </div>

                <a
                    href="{{ panel_route(module().'.create') }}"
                    class="btn btn-primary post-create-btn"
                >
                    <span class="material-icons-outlined">
                        add
                    </span>

                    <span>Thêm bài viết</span>
                </a>
            </div>

            <div class="post-filter-card">
                <div class="post-filter-header">
                    <div>
                        <h6 class="post-filter-title">
                            <span class="material-icons-outlined">
                                filter_alt
                            </span>

                            Bộ lọc dữ liệu
                        </h6>

                        <p class="post-filter-description">
                            Lọc nhanh nội dung theo danh mục hoặc người tạo.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn post-reset-filter-btn"
                        id="post-filter-reset"
                    >
                        <span class="material-icons-outlined">
                            restart_alt
                        </span>

                        Đặt lại
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label
                            for="post-filter-category"
                            class="post-filter-label"
                        >
                            Danh mục
                        </label>

                        <div
                            class="post-filter-control"
                            data-custom-select
                        >
                            <select
                                id="post-filter-category"
                                class="form-select post-filter-native"
                            >
                                <option value="">
                                    Tất cả danh mục
                                </option>

                                @foreach($filterCategories ?? [] as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                        ({{ $category->posts_count }} mục)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label
                            for="post-filter-creator"
                            class="post-filter-label"
                        >
                            Người tạo
                        </label>

                        <div
                            class="post-filter-control"
                            data-custom-select
                        >
                            <select
                                id="post-filter-creator"
                                class="form-select post-filter-native"
                            >
                                <option value="">
                                    Tất cả người tạo
                                </option>

                                @foreach($filterCreators ?? [] as $creator)
                                    <option value="{{ $creator->id }}">
                                        {{ $creator->name }}
                                        ({{ $creator->posts_count }} mục)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-table-card">
                <div class="post-table-card-header">
                    <div>
                        <h6 class="post-table-title">
                            Danh sách nội dung
                        </h6>

                        <p class="post-table-description">
                            Theo dõi trạng thái và cập nhật nội dung
                            đang hiển thị trên website.
                        </p>
                    </div>

                    <div class="post-table-status">
                        <span class="post-table-status-dot"></span>
                        Dữ liệu đang hoạt động
                    </div>
                </div>

                <div class="post-table-body">
                    <x-data-table
                        id="reload-table"
                        :columns="[
                            ['key' => 'index', 'title' => 'STT'],
                            ['key' => 'name', 'title' => 'TÊN NỘI DUNG'],
                            ['key' => 'categories', 'title' => 'DANH MỤC'],
                            ['key' => 'status', 'title' => 'TRẠNG THÁI'],
                            ['key' => 'actions', 'title' => 'HÀNH ĐỘNG']
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

{{-- Đặt JS bên trong section để chắc chắn được render --}}
@include('partials.js.post')
