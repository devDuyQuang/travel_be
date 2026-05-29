<!doctype html>
<html lang="vi" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Đăng nhập - Shenlong</title>
  <meta name="description" content="Trang đăng nhập Shenlong" />
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/vendor/fonts/iconify-icons.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/pickr/pickr-themes.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/core.css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../../assets/vendor/libs/@form-validation/form-validation.css" />
  <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
  <script src="../../assets/vendor/js/helpers.js"></script>
  <script src="../../assets/vendor/js/template-customizer.js"></script>
  <script src="../../assets/js/config.js"></script>
</head>

<body>
  <div class="authentication-wrapper authentication-cover">
    <a href="/" class="app-brand auth-cover-brand">
      <span class="app-brand-logo demo">
        <span class="text-primary">
          <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0v6.854S-.135 9.012 1.98 10.84L13.69 22h6.09L18.8 9.882 16.495 7.173 9.238 0H0Z" fill="currentColor" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.773 16.357 23.656 0H32v6.884s-.174 2.294-1.341 3.522L19.782 22h-6.09L7.773 16.357Z" fill="currentColor" />
          </svg>
        </span>
      </span>
      <span class="app-brand-text demo text-heading fw-bold">Shenlong</span>
    </a>

    <div class="authentication-inner row m-0">
      <div class="d-none d-xl-flex col-xl-8 p-0">
        <div class="auth-cover-bg d-flex justify-content-center align-items-center">
          <img src="../../assets/img/illustrations/auth-login-illustration-light.png" alt="Đăng nhập minh hoạ" class="my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png" />
          <img src="../../assets/img/illustrations/bg-shape-image-light.png" alt="Nền đăng nhập" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png" />
        </div>
      </div>

      <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
        <div class="w-px-400 mx-auto mt-12 pt-5">
          <h4 class="mb-1">Chào mừng đến với Shenlong! 👋</h4>
          <p class="mb-6">Vui lòng đăng nhập để bắt đầu trải nghiệm</p>

          <form id="formAuthentication" class="mb-6" action="{{ panel_route('auth.login') }}" method="POST">
            @csrf

            <div id="loginAlert" class="alert d-none" role="alert"></div>

            <div class="mb-6 form-control-validation">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Nhập email" autofocus value="admin@example.com" />
            </div>

            <div class="mb-6 form-password-toggle form-control-validation">
              <label class="form-label" for="password">Mật khẩu</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••••" aria-describedby="password" value="secret12345" />
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
              </div>
            </div>

            <!-- <div class="my-8">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                    <label class="form-check-label" for="remember-me"> Ghi nhớ đăng nhập </label>
                  </div>
                </div>
              </div> -->

            <button class="btn btn-primary d-grid w-100" type="submit">Đăng nhập</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../../assets/vendor/libs/popper/popper.js"></script>
  <script src="../../assets/vendor/js/bootstrap.js"></script>
  <script src="../../assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="../../assets/vendor/libs/pickr/pickr.js"></script>
  <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
  <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
  <script src="../../assets/vendor/js/menu.js"></script>
  <script src="../../assets/vendor/libs/@form-validation/popular.js"></script>
  <script src="../../assets/vendor/libs/@form-validation/bootstrap5.js"></script>
  <script src="../../assets/vendor/libs/@form-validation/auto-focus.js"></script>
  <script src="../../assets/js/main.js"></script>
  <script src="../../assets/js/pages-auth.js"></script>

  <script>
    $(function() {
      var $form = $('#formAuthentication');
      if (!$form.length) return;

      var $alertBox = $('#loginAlert');
      var $submitBtn = $form.find('button[type="submit"]');
      var $emailEl = $form.find('#email');
      var $passEl = $form.find('#password');
      var $remember = $form.find('#remember-me');

      $(document).on('click', '#togglePassword', function() {
        var type = $passEl.attr('type') === 'password' ? 'text' : 'password';
        $passEl.attr('type', type);
        $(this).toggleClass('tabler-eye-off tabler-eye');
      });

      function getCsrf() {
        var $meta = $('meta[name="csrf-token"]');
        if ($meta.length && $meta.attr('content')) return $meta.attr('content');
        var $hidden = $form.find('input[name="_token"]');
        return $hidden.length ? $hidden.val() : null;
      }

      function setLoading(loading) {
        if (!$submitBtn.length) return;
        if (!$submitBtn.data('original-text')) {
          $submitBtn.data('original-text', $submitBtn.text());
        }
        $submitBtn.prop('disabled', loading)
          .text(loading ? 'Đang đăng nhập…' : $submitBtn.data('original-text'));
      }

      function showAlert(type, message) {
        if (!$alertBox.length) return;
        $alertBox.removeClass('d-none')
          .attr('class', 'alert alert-' + type)
          .text(message);
      }

      function clearAlert() {
        if (!$alertBox.length) return;
        $alertBox.addClass('d-none').text('');
      }

      $emailEl.add($passEl).on('input', function() {
        $(this).removeClass('is-invalid');
      });

      $(document).on('submit', '#formAuthentication', function(e) {
        e.preventDefault();
        e.stopPropagation();

        clearAlert();

        var email = $.trim($emailEl.val() || '');
        var password = $.trim($passEl.val() || '');
        var remember = $remember.is(':checked') ? '1' : '';

        if (!email || !password) {
          showAlert('warning', 'Vui lòng nhập đầy đủ Email và Mật khẩu.');
          if (!email) $emailEl.addClass('is-invalid');
          if (!password) $passEl.addClass('is-invalid');
          return false;
        }

        setLoading(true);

        var payload = {
          email: email,
          password: password
        };
        if (remember) payload.remember = remember;

        $.ajax({
            url: $form.attr('action') || '/login',
            method: 'POST',
            data: $.param(payload),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            dataType: 'json',
            headers: {
              'X-CSRF-TOKEN': getCsrf(),
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          })
          .done(function(data) {
            if (data && data.success === false) {
              var msg = data.message || data.error || 'Đăng nhập thất bại. Vui lòng kiểm tra thông tin.';
              showAlert('danger', msg);
              $emailEl.addClass('is-invalid');
              $passEl.addClass('is-invalid');
              return;
            }
            var msg = (data && data.message) || 'Đăng nhập thành công. Đang chuyển hướng…';
            var redirectUrl = (data && typeof data.redirect === 'string') ? data.redirect : '/';
            showAlert('success', msg);
            window.location.assign(redirectUrl);
          })
          .fail(function(jqXHR) {
            if (jqXHR.status === 419) {
              showAlert('danger', 'Phiên đã hết hạn hoặc thiếu CSRF token (419). Hãy refresh trang và thử lại.');
            } else {
              var resp = jqXHR.responseJSON || {};
              var msg = resp.message || resp.error || 'Có lỗi kết nối. Vui lòng thử lại.';
              showAlert('danger', msg);
            }
            $emailEl.addClass('is-invalid');
            $passEl.addClass('is-invalid');
          })
          .always(function() {
            setLoading(false);
          });

        return false;
      });
    });
  </script>
</body>

</html>