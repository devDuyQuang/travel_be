<script>
    (function() {
        window.CMS_DEBUG_JSON = "{{ config('app.debug') ? '1' : '0' }}" === "1";

        console.log('cms ajax json response loaded');

        function findResponseBox(form) {
            return form.closest('.main-content')?.querySelector('.cms-json-response-card') ||
                form.closest('.card-body')?.querySelector('.cms-json-response-card') ||
                form.parentElement?.querySelector('.cms-json-response-card') ||
                document.querySelector('.cms-json-response-card');
        }

        function showJsonResponse(form, payload) {
            if (!window.CMS_DEBUG_JSON) return;

            const box = findResponseBox(form);

            if (!box) {
                console.warn('Không tìm thấy .cms-json-response-card');
                return;
            }

            const content = box.querySelector('.cms-json-response-content');

            if (!content) {
                console.warn('Không tìm thấy .cms-json-response-content');
                return;
            }

            box.style.display = 'block';
            content.textContent = JSON.stringify(payload, null, 2);

            box.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function clearFieldErrors(form) {
            form.querySelectorAll('.is-invalid').forEach(function(el) {
                el.classList.remove('is-invalid');
            });

            form.querySelectorAll('[id^="error-"]').forEach(function(el) {
                el.textContent = '';
            });
        }

        function showFieldErrors(form, errors) {
            if (!errors || typeof errors !== 'object') return;

            Object.keys(errors).forEach(function(field) {
                const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];

                const input =
                    form.querySelector('[name="' + field + '"]') ||
                    form.querySelector('[name="' + field + '[]"]');

                if (input) {
                    input.classList.add('is-invalid');
                }

                const errorId = '#error-' + field.replace(/\./g, '_');
                const errorEl = form.querySelector(errorId);

                if (errorEl) {
                    errorEl.textContent = message;
                }
            });
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.cms-json-response-clear');

            if (!btn) return;

            const box = btn.closest('.cms-json-response-card');
            if (!box) return;

            const content = box.querySelector('.cms-json-response-content');

            box.style.display = 'none';

            if (content) {
                content.textContent = '';
            }
        });

        document.addEventListener('submit', async function(e) {
            const form = e.target;

            if (!(form instanceof HTMLFormElement)) return;

            const isAjaxForm =
                form.classList.contains('ajax-form') ||
                form.dataset.ajax === 'true' ||
                form.closest('.product-form-page, .post-form-page, .category-form-page, .menu-form-page, .setting-page');

            if (!isAjaxForm) return;

            e.preventDefault();
            e.stopImmediatePropagation();

            console.log('cms ajax form submit captured', form);

            clearFieldErrors(form);

            const submitBtn = form.querySelector('[type="submit"]');
            const formData = new FormData(form);

            if (submitBtn) {
                submitBtn.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json().catch(function() {
                    return {
                        message: 'Response không phải JSON hợp lệ.'
                    };
                });

                showJsonResponse(form, {
                    status: response.status,
                    ok: response.ok,
                    method: 'POST',
                    url: form.action,
                    response: data
                });

                if (!response.ok) {
                    showFieldErrors(form, data.errors);

                    if (window.toastError) {
                        window.toastError(data.message || 'Dữ liệu không hợp lệ.');
                    }

                    return;
                }

                if (window.toastSuccess) {
                    window.toastSuccess(data.message || 'Lưu thành công.');
                }

                const stayOnPage = form.dataset.stay === 'true';
                const redirectUrl = data.redirect_url || data.redirect;

                if (!stayOnPage && redirectUrl) {
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1500);
                }
            } catch (error) {
                showJsonResponse(form, {
                    ok: false,
                    message: error.message || 'Có lỗi xảy ra khi gửi form.'
                });

                if (window.toastError) {
                    window.toastError('Có lỗi xảy ra khi gửi form.');
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }
        }, true);
    })();
</script>