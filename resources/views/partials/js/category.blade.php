@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>

<script>
(function ($) {
    'use strict';

    let sortableInstance = null;

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function getCsrfToken() {
        const meta = document.querySelector(
            'meta[name="csrf-token"]'
        );

        return meta
            ? meta.getAttribute('content') || ''
            : '';
    }

    function getUpdateOrderUrl() {
        const categoryPage = document.querySelector(
            '.category-list-page'
        );

        if (!categoryPage) {
            return '';
        }

        return categoryPage.dataset.updateOrderUrl || '';
    }

    function getDataTable() {
        if (
            typeof $.fn.DataTable !== 'function' ||
            !$.fn.DataTable.isDataTable('#reload-table')
        ) {
            return null;
        }

        return $('#reload-table').DataTable();
    }

    function showSuccess(message) {
        if (typeof window.toastSuccess === 'function') {
            window.toastSuccess(message);
            return;
        }

        console.log(message);
    }

    function showError(message) {
        if (typeof window.toastError === 'function') {
            window.toastError(message);
            return;
        }

        alert(message);
    }

    function reloadCategoryTable() {
        const table = getDataTable();

        if (!table) {
            return;
        }

        table.ajax.reload(null, false);
    }

    function disposeBootstrapInstance(element, type) {
        if (typeof bootstrap === 'undefined') {
            return;
        }

        let instance = null;

        if (
            type === 'tooltip' &&
            bootstrap.Tooltip
        ) {
            instance = bootstrap.Tooltip.getInstance(
                element
            );
        }

        if (
            type === 'popover' &&
            bootstrap.Popover
        ) {
            instance = bootstrap.Popover.getInstance(
                element
            );
        }

        if (instance) {
            instance.dispose();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Table rows
    |--------------------------------------------------------------------------
    */

    function bindCategoryRows() {
        const table = getDataTable();

        if (!table) {
            return;
        }

        table.rows().every(function () {
            const rowData = this.data();
            const rowNode = this.node();

            if (!rowData || !rowNode) {
                return;
            }

            rowNode.removeAttribute('data-id');

            if (rowData.id) {
                rowNode.setAttribute(
                    'data-id',
                    String(rowData.id)
                );
            }

            rowNode.classList.remove(
                'category-row-parent',
                'category-row-child'
            );

            const depth = Number.parseInt(
                rowData.depth || 0,
                10
            );

            rowNode.classList.add(
                depth === 0
                    ? 'category-row-parent'
                    : 'category-row-child'
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tooltip / Popover
    |--------------------------------------------------------------------------
    */

    function initCategoryPopovers() {
        if (
            typeof bootstrap === 'undefined' ||
            !bootstrap.Popover
        ) {
            return;
        }

        document
            .querySelectorAll('.category-meta-trigger')
            .forEach(function (element) {
                disposeBootstrapInstance(
                    element,
                    'popover'
                );

                new bootstrap.Popover(element, {
                    html: true,
                    trigger: 'hover focus',
                    placement:
                        element.getAttribute(
                            'data-bs-placement'
                        ) || 'left',
                    container: 'body',
                    sanitize: false
                });
            });
    }

    function initCategoryTooltips() {
        if (
            typeof bootstrap === 'undefined' ||
            !bootstrap.Tooltip
        ) {
            return;
        }

        document
            .querySelectorAll('.js-category-tooltip')
            .forEach(function (element) {
                disposeBootstrapInstance(
                    element,
                    'tooltip'
                );

                new bootstrap.Tooltip(element, {
                    trigger: 'hover focus',
                    placement:
                        element.getAttribute(
                            'data-bs-placement'
                        ) || 'top',
                    container: 'body'
                });
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Sortable
    |--------------------------------------------------------------------------
    */

    function destroySortable() {
        if (!sortableInstance) {
            return;
        }

        sortableInstance.destroy();
        sortableInstance = null;
    }

    function collectOrderItems() {
        const table = getDataTable();
        const tbody = document.querySelector(
            '#reload-table tbody'
        );

        if (!table || !tbody) {
            return [];
        }

        return Array.from(
            tbody.querySelectorAll('tr')
        )
            .map(function (row, index) {
                const rowData = table
                    .row(row)
                    .data();

                if (!rowData || !rowData.id) {
                    return null;
                }

                return {
                    id: rowData.id,
                    order_position: index + 1,
                    position: index + 1
                };
            })
            .filter(function (item) {
                return item !== null;
            });
    }

    async function saveCategoryOrder() {
        const updateOrderUrl = getUpdateOrderUrl();

        if (!updateOrderUrl) {
            console.error(
                'Không tìm thấy data-update-order-url trên .category-list-page.'
            );

            showError(
                'Không tìm thấy đường dẫn cập nhật thứ tự danh mục.'
            );

            reloadCategoryTable();
            return;
        }

        const items = collectOrderItems();

        if (!items.length) {
            reloadCategoryTable();
            return;
        }

        try {
            const response = await fetch(
                updateOrderUrl,
                {
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN':
                            getCsrfToken(),

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json',

                        'Content-Type':
                            'application/json'
                    },

                    body: JSON.stringify({
                        items: items
                    })
                }
            );

            const responseData = await response
                .json()
                .catch(function () {
                    return {};
                });

            if (!response.ok) {
                throw new Error(
                    responseData.message ||
                    'Không thể lưu thứ tự danh mục.'
                );
            }

            showSuccess(
                responseData.message ||
                'Đã cập nhật thứ tự danh mục.'
            );

            reloadCategoryTable();
        } catch (error) {
            console.error(
                'Lỗi cập nhật thứ tự danh mục:',
                error
            );

            showError(
                error.message ||
                'Không thể lưu thứ tự danh mục.'
            );

            reloadCategoryTable();
        }
    }

    function initCategorySortable() {
        destroySortable();

        if (typeof Sortable === 'undefined') {
            console.warn(
                'SortableJS chưa được tải.'
            );

            return;
        }

        const tbody = document.querySelector(
            '#reload-table tbody'
        );

        if (!tbody) {
            return;
        }

        sortableInstance = Sortable.create(
            tbody,
            {
                handle: '.category-drag-handle',
                draggable: 'tr',
                animation: 150,
                forceFallback: false,

                ghostClass:
                    'category-sortable-ghost',

                chosenClass:
                    'category-sortable-chosen',

                onEnd: function (event) {
                    if (event.oldIndex === event.newIndex) {
                        return;
                    }

                    saveCategoryOrder();
                }
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle status / home
    |--------------------------------------------------------------------------
    */

    function bindToggle(options) {
        const selector = options.selector;
        const eventNamespace = options.eventNamespace;
        const responseField = options.responseField;
        const enabledMessage = options.enabledMessage;
        const disabledMessage = options.disabledMessage;

        $(document)
            .off(
                'change.' + eventNamespace,
                selector
            )
            .on(
                'change.' + eventNamespace,
                selector,
                function () {
                    const checkbox = this;
                    const url = checkbox.getAttribute(
                        'data-url'
                    );

                    if (!url) {
                        checkbox.checked =
                            !checkbox.checked;

                        showError(
                            'Không tìm thấy đường dẫn cập nhật.'
                        );

                        return;
                    }

                    const table = getDataTable();

                    if (!table) {
                        checkbox.checked =
                            !checkbox.checked;

                        return;
                    }

                    const isEnabled =
                        checkbox.checked;

                    checkbox.disabled = true;

                    $.ajax({
                        url: url,
                        method: 'PATCH',

                        headers: {
                            'X-CSRF-TOKEN':
                                getCsrfToken(),

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'Accept':
                                'application/json'
                        },

                        success: function (response) {
                            const rowData = table
                                .row(
                                    $(checkbox)
                                        .closest('tr')
                                )
                                .data();

                            if (rowData) {
                                rowData[responseField] =
                                    response[responseField] ??
                                    (
                                        isEnabled
                                            ? 1
                                            : 0
                                    );
                            }

                            showSuccess(
                                isEnabled
                                    ? enabledMessage
                                    : disabledMessage
                            );
                        },

                        error: function (xhr) {
                            checkbox.checked =
                                !checkbox.checked;

                            const response =
                                xhr.responseJSON || {};

                            showError(
                                response.message ||
                                'Không thể cập nhật dữ liệu.'
                            );
                        },

                        complete: function () {
                            checkbox.disabled = false;
                        }
                    });
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | UI lifecycle
    |--------------------------------------------------------------------------
    */

    function initCategoryUi() {
        bindCategoryRows();
        initCategoryPopovers();
        initCategoryTooltips();
        initCategorySortable();
    }

    function waitForCategoryTable(attempt) {
        attempt = attempt || 0;

        const table = getDataTable();

        if (!table) {
            if (attempt < 50) {
                setTimeout(
                    function () {
                        waitForCategoryTable(
                            attempt + 1
                        );
                    },
                    200
                );
            } else {
                console.error(
                    'Không tìm thấy DataTable #reload-table.'
                );
            }

            return;
        }

        $('#reload-table')
            .off('draw.dt.categoryUi')
            .on(
                'draw.dt.categoryUi',
                function () {
                    setTimeout(
                        initCategoryUi,
                        30
                    );
                }
            );

        initCategoryUi();
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize
    |--------------------------------------------------------------------------
    */

    $(function () {
        bindToggle({
            selector:
                '.category-status-toggle',

            eventNamespace:
                'categoryStatusToggle',

            responseField:
                'status',

            enabledMessage:
                'Đã bật trạng thái danh mục.',

            disabledMessage:
                'Đã tắt trạng thái danh mục.'
        });

        bindToggle({
            selector:
                '.category-home-toggle',

            eventNamespace:
                'categoryHomeToggle',

            responseField:
                'home',

            enabledMessage:
                'Đã bật hiển thị trên trang chủ.',

            disabledMessage:
                'Đã tắt hiển thị trên trang chủ.'
        });

        waitForCategoryTable();
    });

})(jQuery);
</script>
@endpush