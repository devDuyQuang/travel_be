<script>
(function () {
    'use strict';

    window.__POST_FILTER_JS_LOADED__ = true;

    var bootAttempt = 0;

    function bootPostPage() {
        if (!window.jQuery) {
            bootAttempt++;

            if (bootAttempt < 50) {
                setTimeout(bootPostPage, 100);
            } else {
                console.error('Không tìm thấy jQuery.');
            }

            return;
        }

        var $ = window.jQuery;
        var suppressFilterReload = false;

        /* =====================================================
           CUSTOM FILTER DROPDOWN
        ===================================================== */

        function closeAllFilterDropdowns(exceptControl) {
            $('[data-custom-select].is-open').each(function () {
                if (
                    exceptControl &&
                    this === exceptControl
                ) {
                    return;
                }

                $(this)
                    .removeClass('is-open')
                    .find('.post-filter-trigger')
                    .attr('aria-expanded', 'false');
            });
        }

        function syncCustomSelect($control) {
            var $select = $control.find(
                '.post-filter-native'
            );

            var $selectedOption = $select
                .find('option:selected')
                .first();

            var selectedValue = String(
                $selectedOption.val() || ''
            );

            var selectedText = $.trim(
                $selectedOption.text() || ''
            );

            $control
                .find('.post-filter-trigger-text')
                .text(selectedText);

            $control
                .find('.post-filter-option')
                .removeClass('is-selected')
                .attr('aria-selected', 'false');

            $control
                .find('.post-filter-option')
                .filter(function () {
                    return String(
                        $(this).attr('data-value') || ''
                    ) === selectedValue;
                })
                .addClass('is-selected')
                .attr('aria-selected', 'true');
        }

        function initCustomFilterSelects() {
            $('[data-custom-select]').each(function () {
                var $control = $(this);

                var $select = $control.find(
                    '.post-filter-native'
                );

                if (
                    !$select.length ||
                    $control.hasClass('is-enhanced')
                ) {
                    return;
                }

                var selectId =
                    $select.attr('id') ||
                    'post-filter-' +
                    Math.random()
                        .toString(36)
                        .slice(2);

                var menuId = selectId + '-menu';

                var $trigger = $(
                    '<button ' +
                        'type="button" ' +
                        'class="post-filter-trigger" ' +
                        'aria-haspopup="listbox" ' +
                        'aria-expanded="false" ' +
                        'aria-controls="' + menuId + '">' +

                        '<span class="post-filter-trigger-text"></span>' +

                        '<span class="material-icons-outlined post-filter-arrow">' +
                            'expand_more' +
                        '</span>' +

                    '</button>'
                );

                var $menu = $(
                    '<div ' +
                        'id="' + menuId + '" ' +
                        'class="post-filter-menu" ' +
                        'role="listbox">' +
                    '</div>'
                );

                $select.find('option').each(function () {
                    var optionValue = String(
                        $(this).val() || ''
                    );

                    var optionText = $.trim(
                        $(this).text() || ''
                    );

                    var $optionButton = $(
                        '<button ' +
                            'type="button" ' +
                            'class="post-filter-option" ' +
                            'role="option">' +

                            '<span class="post-filter-option-check material-icons-outlined">' +
                                'check' +
                            '</span>' +

                            '<span class="post-filter-option-text"></span>' +

                        '</button>'
                    );

                    $optionButton.attr(
                        'data-value',
                        optionValue
                    );

                    $optionButton
                        .find('.post-filter-option-text')
                        .text(optionText);

                    $menu.append($optionButton);
                });

                $control
                    .append($trigger)
                    .append($menu)
                    .addClass('is-enhanced');

                syncCustomSelect($control);

                $trigger.on(
                    'click.customFilter',
                    function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        var willOpen =
                            !$control.hasClass('is-open');

                        closeAllFilterDropdowns(
                            $control.get(0)
                        );

                        $control.toggleClass(
                            'is-open',
                            willOpen
                        );

                        $trigger.attr(
                            'aria-expanded',
                            willOpen
                                ? 'true'
                                : 'false'
                        );
                    }
                );

                $menu.on(
                    'click.customFilter',
                    '.post-filter-option',
                    function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        var value = String(
                            $(this).attr('data-value') || ''
                        );

                        $select
                            .val(value)
                            .trigger('change');

                        syncCustomSelect($control);

                        $control.removeClass('is-open');

                        $trigger.attr(
                            'aria-expanded',
                            'false'
                        );

                        $trigger.trigger('focus');
                    }
                );

                $select
                    .off('change.customFilterUi')
                    .on(
                        'change.customFilterUi',
                        function () {
                            syncCustomSelect($control);
                        }
                    );
            });

            $(document)
                .off('click.customFilter')
                .on(
                    'click.customFilter',
                    function () {
                        closeAllFilterDropdowns();
                    }
                );

            $(document)
                .off('keydown.customFilter')
                .on(
                    'keydown.customFilter',
                    function (event) {
                        if (event.key === 'Escape') {
                            closeAllFilterDropdowns();
                        }
                    }
                );
        }

        /* =====================================================
           DATATABLE FILTER
        ===================================================== */

        function waitForPostTable(attempt) {
            attempt = attempt || 0;

            if (
                !$.fn.DataTable ||
                !$.fn.DataTable.isDataTable(
                    '#reload-table'
                )
            ) {
                if (attempt < 50) {
                    setTimeout(function () {
                        waitForPostTable(
                            attempt + 1
                        );
                    }, 200);
                } else {
                    console.error(
                        'Không tìm thấy DataTable #reload-table.'
                    );
                }

                return;
            }

            var table = $('#reload-table').DataTable();

            var initialAjaxUrl =
                table.ajax.url() || '';

            var baseAjaxUrl = initialAjaxUrl
                ? initialAjaxUrl.split('?')[0]
                : '';

            function buildFilteredUrl() {
                var categoryId = String(
                    $('#post-filter-category').val() || ''
                );

                var creatorId = String(
                    $('#post-filter-creator').val() || ''
                );

                var params = new URLSearchParams();

                if (categoryId) {
                    params.set(
                        'category_id',
                        categoryId
                    );
                }

                if (creatorId) {
                    params.set(
                        'created_by',
                        creatorId
                    );
                }

                var queryString = params.toString();

                return queryString
                    ? baseAjaxUrl + '?' + queryString
                    : baseAjaxUrl;
            }

            function reloadWithFilters() {
                if (suppressFilterReload) {
                    return;
                }

                var filteredUrl =
                    buildFilteredUrl();

                if (!filteredUrl) {
                    return;
                }

                table
                    .ajax
                    .url(filteredUrl)
                    .load(null, true);
            }

            $('#post-filter-category, #post-filter-creator')
                .off('change.postFilter')
                .on(
                    'change.postFilter',
                    reloadWithFilters
                );

            $('#post-filter-reset')
                .off('click.postFilter')
                .on(
                    'click.postFilter',
                    function () {
                        suppressFilterReload = true;

                        $('#post-filter-category')
                            .val('')
                            .trigger('change');

                        $('#post-filter-creator')
                            .val('')
                            .trigger('change');

                        suppressFilterReload = false;

                        table.search('');

                        $('#reload-table')
                            .closest(
                                '.dataTables_wrapper, .dt-container'
                            )
                            .find('input[type="search"]')
                            .val('');

                        closeAllFilterDropdowns();

                        if (baseAjaxUrl) {
                            table
                                .ajax
                                .url(baseAjaxUrl)
                                .load(null, true);
                        }
                    }
                );
        }

        /* =====================================================
           STATUS TOGGLE
        ===================================================== */

        $(document)
            .off(
                'change.postStatus',
                '.post-status-toggle'
            )
            .on(
                'change.postStatus',
                '.post-status-toggle',
                function () {
                    var checkbox = this;

                    var url = checkbox.getAttribute(
                        'data-url'
                    );

                    if (!url) {
                        return;
                    }

                    if (
                        !$.fn.DataTable ||
                        !$.fn.DataTable.isDataTable(
                            '#reload-table'
                        )
                    ) {
                        checkbox.checked =
                            !checkbox.checked;

                        return;
                    }

                    var table =
                        $('#reload-table').DataTable();

                    var isEnabled =
                        checkbox.checked;

                    var csrfMeta =
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        );

                    var csrfToken = csrfMeta
                        ? csrfMeta.getAttribute(
                            'content'
                        )
                        : '';

                    $.ajax({
                        url: url,
                        method: 'PATCH',

                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },

                        success: function (response) {
                            var rowData = table
                                .row(
                                    $(checkbox).closest('tr')
                                )
                                .data();

                            if (rowData) {
                                rowData.status =
                                    response.status;
                            }

                            if (
                                typeof window.toastSuccess ===
                                'function'
                            ) {
                                window.toastSuccess(
                                    isEnabled
                                        ? 'Đã bật hiển thị.'
                                        : 'Đã tắt hiển thị.'
                                );
                            }
                        },

                        error: function () {
                            checkbox.checked =
                                !checkbox.checked;

                            if (
                                typeof window.toastError ===
                                'function'
                            ) {
                                window.toastError(
                                    'Không thể cập nhật trạng thái.'
                                );
                            } else {
                                alert(
                                    'Không thể cập nhật trạng thái.'
                                );
                            }
                        }
                    });
                }
            );

        /* =====================================================
           TOOLTIP / POPOVER
        ===================================================== */

        function disposeBootstrapInstance(
            element,
            type
        ) {
            if (
                typeof window.bootstrap ===
                'undefined'
            ) {
                return;
            }

            var instance = null;

            if (
                type === 'tooltip' &&
                window.bootstrap.Tooltip
            ) {
                instance =
                    window.bootstrap.Tooltip
                        .getInstance(element);
            }

            if (
                type === 'popover' &&
                window.bootstrap.Popover
            ) {
                instance =
                    window.bootstrap.Popover
                        .getInstance(element);
            }

            if (instance) {
                instance.dispose();
            }
        }

        function initPostPopovers() {
            if (
                typeof window.bootstrap ===
                'undefined' ||
                !window.bootstrap.Popover
            ) {
                return;
            }

            document
                .querySelectorAll(
                    '.post-meta-trigger'
                )
                .forEach(function (element) {
                    disposeBootstrapInstance(
                        element,
                        'popover'
                    );

                    new window.bootstrap.Popover(
                        element,
                        {
                            html: true,
                            trigger: 'hover focus',

                            placement:
                                element.getAttribute(
                                    'data-bs-placement'
                                ) || 'left',

                            container: 'body',
                            sanitize: false
                        }
                    );
                });
        }

        function initPostTooltips() {
            if (
                typeof window.bootstrap ===
                'undefined' ||
                !window.bootstrap.Tooltip
            ) {
                return;
            }

            document
                .querySelectorAll(
                    '.js-post-tooltip'
                )
                .forEach(function (element) {
                    disposeBootstrapInstance(
                        element,
                        'tooltip'
                    );

                    new window.bootstrap.Tooltip(
                        element,
                        {
                            trigger: 'hover focus',

                            placement:
                                element.getAttribute(
                                    'data-bs-placement'
                                ) || 'top',

                            container: 'body'
                        }
                    );
                });
        }

        function initPostUiHelpers() {
            initPostPopovers();
            initPostTooltips();
        }

        initCustomFilterSelects();
        waitForPostTable();
        initPostUiHelpers();

        $(document)
            .off('draw.dt.postUi')
            .on(
                'draw.dt.postUi',
                function () {
                    initPostUiHelpers();
                }
            );
    }

    if (document.readyState === 'complete') {
        bootPostPage();
    } else {
        window.addEventListener(
            'load',
            bootPostPage,
            {
                once: true
            }
        );
    }
})();
</script>