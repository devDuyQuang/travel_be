<!DOCTYPE html>
<html lang="vi" data-bs-theme="semi-dark">

@include('partials.head')

@include('partials.css.admin-layout')
@include('partials.css.theme-tokens')
@include('partials.css.booking-order')

<body>
    @include('partials.nav')
    @include('partials.aside')

    @yield('content')

    @include('partials.footer')
    @include('partials.script')
    @include('partials.css.json-response')

    @stack('scripts')

    @include('partials.js.ajax-json-response')
</body>

</html>