<!doctype html>
<html lang="vi" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Đăng nhập - Shenlong</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('vertical-menu/assets/images/favicon-32x32.png') }}" type="image/png">
  <!-- loader-->
  <link href="{{ asset('vertical-menu/assets/css/pace.min.css') }}" rel="stylesheet">
  <script src="{{ asset('vertical-menu/assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{ asset('vertical-menu/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('vertical-menu/assets/plugins/metismenu/metisMenu.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('vertical-menu/assets/plugins/metismenu/mm-vertical.css') }}">
  <!--bootstrap css-->
  <link href="{{ asset('vertical-menu/assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!--main css-->
  <link href="{{ asset('vertical-menu/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('vertical-menu/sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('vertical-menu/sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('vertical-menu/sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('vertical-menu/sass/responsive.css') }}" rel="stylesheet">
</head>

<body>

  <!--authentication-->
  <div class="mx-3 mx-lg-0">
    <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
      <div class="row g-4">
        <div class="col-lg-6 d-flex">
          <div class="card-body">
            <img src="{{ asset('vertical-menu/assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
            <h4 class="fw-bold">Bắt đầu ngay</h4>
            <p class="mb-0">Nhập thông tin đăng nhập để truy cập tài khoản</p>

            <div class="form-body mt-4">
              <form id="formAuthentication" class="row g-3" action="{{ panel_route('auth.login') }}" method="POST">
                @csrf
                <div class="col-12">
                  <div id="loginAlert" class="alert d-none" role="alert"></div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Nhập email"
                    value="admin@example.com">
                </div>
                <div class="col-12">
                  <label for="password" class="form-label">Mật khẩu</label>
                  <div class="input-group" id="show_hide_password">
                    <input
                      type="password"
                      class="form-control border-end-0"
                      id="password"
                      name="password"
                      value="secret12345"
                      placeholder="Nhập mật khẩu">
                    <a href="javascript:;" class="input-group-text bg-transparent" id="togglePassword"><i class="bi bi-eye-slash-fill"></i></a>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" checked>
                    <label class="form-check-label" for="remember-me">Ghi nhớ đăng nhập</label>
                  </div>
                </div>
                <div class="col-md-6 text-end"> <a href="javascript:;">Quên mật khẩu?</a>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-grd-primary">Đăng nhập</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-primary">
            <img src="{{ asset('vertical-menu/assets/images/auth/login1.png') }}" class="img-fluid" alt="">
          </div>
        </div>
      </div><!--end row-->
    </div>
  </div>

  <!--plugins-->
  <script src="{{ asset('vertical-menu/assets/js/jquery.min.js') }}"></script>

  <script>
    $(function() {
      // Toggle password
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        var $passInput = $('#show_hide_password input');
        var $icon = $('#show_hide_password i');
        if ($passInput.attr("type") == "text") {
          $passInput.attr('type', 'password');
          $icon.addClass("bi-eye-slash-fill").removeClass("bi-eye-fill");
        } else if ($passInput.attr("type") == "password") {
          $passInput.attr('type', 'text');
          $icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
      });

      // Handle Ajax Login
      var $form = $('#formAuthentication');
      if (!$form.length) return;

      var $alertBox = $('#loginAlert');
      var $submitBtn = $form.find('button[type="submit"]');
      var $emailEl = $form.find('#email');
      var $passEl = $form.find('#password');
      var $remember = $form.find('#remember-me');

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