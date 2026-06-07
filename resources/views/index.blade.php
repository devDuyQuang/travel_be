<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
@include('partials.head')
@include('partials.css.admin-layout')

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


