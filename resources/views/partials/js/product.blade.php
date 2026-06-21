@push('scripts')
<script>
(function ($) {
    'use strict';

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function getCsrfToken() {
        var meta = document.querySelector(
            'meta[name="csrf-token"]'
        );

        return meta
            ? meta.getAttribute('content') || ''
            : '';
    }

    function getProductTable() {
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
        }
    }

    function showError(message) {
        if (typeof window.toastError === 'function') {
            window.toastError(message);
            return;
        }

        alert(message);
    }

    /*
    |--------------------------------------------------------------------------
    | Tooltip / Popover
    |--------------------------------------------------------------------------
    */

    function disposeBootstrapInstance(
        element,
        type
    ) {
        if (typeof bootstrap === 'undefined') {
            return;
        }

        var instance = null;

        if (
            type === 'tooltip' &&
            bootstrap.Tooltip
        ) {
            instance =
                bootstrap.Tooltip.getInstance(element);
        }

        if (
            type === 'popover' &&
            bootstrap.Popover
        ) {
            instance =
                bootstrap.Popover.getInstance(element);
        }

        if (instance) {
            instance.dispose();
        }
    }

    function initProductPopovers() {
        if (
            typeof bootstrap === 'undefined' ||
            !bootstrap.Popover
        ) {
            return;
        }

        document
            .querySelectorAll('.product-meta-trigger')
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

    function initProductTooltips() {
        if (
            typeof bootstrap === 'undefined' ||
            !bootstrap.Tooltip
        ) {
            return;
        }

        document
            .querySelectorAll('.js-product-tooltip')
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

    function initProductTableUi() {
        initProductPopovers();
        initProductTooltips();
    }

    /*
    |--------------------------------------------------------------------------
    | Status toggle
    |--------------------------------------------------------------------------
    */

    $(document)
        .off(
            'change.productStatus',
            '.product-status-toggle'
        )
        .on(
            'change.productStatus',
            '.product-status-toggle',
            function () {
                var checkbox = this;
                var url = checkbox.getAttribute(
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

                var table = getProductTable();
                var isEnabled = checkbox.checked;

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
                        if (table) {
                            var rowData = table
                                .row(
                                    $(checkbox)
                                        .closest('tr')
                                )
                                .data();

                            if (rowData) {
                                rowData.status =
                                    response.status ??
                                    (
                                        isEnabled
                                            ? 1
                                            : 0
                                    );
                            }
                        }

                        showSuccess(
                            isEnabled
                                ? 'Đã bật hiển thị sản phẩm.'
                                : 'Đã tắt hiển thị sản phẩm.'
                        );
                    },

                    error: function (xhr) {
                        checkbox.checked =
                            !checkbox.checked;

                        var response =
                            xhr.responseJSON || {};

                        showError(
                            response.message ||
                            'Không thể cập nhật trạng thái.'
                        );
                    },

                    complete: function () {
                        checkbox.disabled = false;
                    }
                });
            }
        );

    /*
    |--------------------------------------------------------------------------
    | Category attributes
    |--------------------------------------------------------------------------
    */

    function updateProductAttributeGroup() {
        var categorySelect =
            document.getElementById('category_id');

        var emptyMessage =
            document.getElementById(
                'product-attributes-empty'
            );

        var groups =
            document.querySelectorAll(
                '.product-attribute-group[data-layout-key]'
            );

        var legacyGroups =
            document.querySelectorAll(
                '[data-legacy-category]'
            );

        if (!categorySelect || !groups.length) {
            return;
        }

        var selectedOption =
            categorySelect.options[
                categorySelect.selectedIndex
            ];

        var layoutKey =
            selectedOption
                ? selectedOption.getAttribute(
                    'data-layout-key'
                ) || ''
                : '';

        var hasVisibleGroup = false;

        groups.forEach(function (group) {
            var visible =
                group.getAttribute(
                    'data-layout-key'
                ) === layoutKey;

            group.classList.toggle(
                'd-none',
                !visible
            );

            group
                .querySelectorAll(
                    'input, textarea, select'
                )
                .forEach(function (input) {
                    input.disabled = !visible;
                });

            if (visible) {
                hasVisibleGroup = true;
            }
        });

        legacyGroups.forEach(function (group) {
            group.classList.toggle(
                'd-none',
                layoutKey !== 'tee_time'
            );
        });

        if (emptyMessage) {
            emptyMessage.classList.toggle(
                'd-none',
                hasVisibleGroup
            );
        }
    }

    function initProductAttributes() {
        var categorySelect =
            document.getElementById('category_id');

        if (!categorySelect) {
            return;
        }

        categorySelect.removeEventListener(
            'change',
            updateProductAttributeGroup
        );

        categorySelect.addEventListener(
            'change',
            updateProductAttributeGroup
        );

        updateProductAttributeGroup();
    }

    /*
    |--------------------------------------------------------------------------
    | File preview
    |--------------------------------------------------------------------------
    */

    function getFileNameElement(wrapper) {
        var fileNameElement =
            wrapper.querySelector(
                '.custom-file-name'
            );

        if (!fileNameElement) {
            fileNameElement =
                document.createElement('span');

            fileNameElement.className =
                'custom-file-name';

            wrapper.appendChild(fileNameElement);
        }

        return fileNameElement;
    }

    function createSingleImagePreview(
        mediaCard,
        file
    ) {
        var preview =
            mediaCard.querySelector(
                '.product-media-preview'
            );

        if (!preview) {
            var previewWrapper =
                document.createElement('div');

            previewWrapper.className =
                'product-media-preview-wrapper mb-3';

            preview =
                document.createElement('img');

            preview.className =
                'product-media-preview';

            previewWrapper.appendChild(preview);

            var fileRow =
                mediaCard.querySelector(
                    '.custom-file-row'
                );

            mediaCard.insertBefore(
                previewWrapper,
                fileRow
            );
        }

        preview.src =
            URL.createObjectURL(file);
    }

    function createGalleryPreviews(
        mediaCard,
        files
    ) {
        var previewContainer =
            mediaCard.querySelector(
                '.product-new-gallery-preview'
            );

        if (!previewContainer) {
            previewContainer =
                document.createElement('div');

            previewContainer.className =
                'product-gallery-preview product-new-gallery-preview mb-3';

            var fileRow =
                mediaCard.querySelector(
                    '.custom-file-row'
                );

            mediaCard.insertBefore(
                previewContainer,
                fileRow
            );
        }

        previewContainer.innerHTML = '';

        Array.from(files)
            .slice(0, 10)
            .forEach(function (file) {
                if (
                    !file.type ||
                    !file.type.startsWith('image/')
                ) {
                    return;
                }

                var item =
                    document.createElement('div');

                item.className =
                    'product-gallery-preview-item';

                var image =
                    document.createElement('img');

                image.src =
                    URL.createObjectURL(file);

                image.alt = file.name;

                item.appendChild(image);
                previewContainer.appendChild(item);
            });
    }

    function initProductFileInputs() {
        document
            .querySelectorAll(
                '.custom-file-input'
            )
            .forEach(function (input) {
                if (
                    input.dataset.previewReady === '1'
                ) {
                    return;
                }

                input.dataset.previewReady = '1';

                input.addEventListener(
                    'change',
                    function () {
                        var wrapper =
                            input.closest(
                                '.custom-file-row'
                            );

                        var mediaCard =
                            input.closest(
                                '.product-media-card'
                            );

                        if (!wrapper || !mediaCard) {
                            return;
                        }

                        var files =
                            input.files || [];

                        var fileNameElement =
                            getFileNameElement(
                                wrapper
                            );

                        if (!files.length) {
                            fileNameElement.textContent =
                                'Không có tệp nào được chọn';

                            return;
                        }

                        if (input.multiple) {
                            fileNameElement.textContent =
                                files.length +
                                ' tệp đã chọn';

                            createGalleryPreviews(
                                mediaCard,
                                files
                            );

                            return;
                        }

                        var file = files[0];

                        fileNameElement.textContent =
                            file.name;

                        if (
                            file.type &&
                            file.type.startsWith(
                                'image/'
                            )
                        ) {
                            createSingleImagePreview(
                                mediaCard,
                                file
                            );
                        }
                    }
                );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | DataTable lifecycle
    |--------------------------------------------------------------------------
    */

    function waitForProductTable(attempt) {
        attempt = attempt || 0;

        var table = getProductTable();

        if (!table) {
            if (attempt < 50) {
                setTimeout(function () {
                    waitForProductTable(
                        attempt + 1
                    );
                }, 200);
            }

            return;
        }

        $('#reload-table')
            .off('draw.dt.productUi')
            .on(
                'draw.dt.productUi',
                function () {
                    setTimeout(
                        initProductTableUi,
                        30
                    );
                }
            );

        initProductTableUi();
    }

    /*
    |--------------------------------------------------------------------------
    | Initialize
    |--------------------------------------------------------------------------
    */

    $(function () {
        initProductAttributes();
        initProductFileInputs();
        waitForProductTable();
    });

})(jQuery);
</script>
@endpush