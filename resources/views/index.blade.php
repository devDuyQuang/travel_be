<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
@include('partials.head')
<body>
    @include('partials.nav')
    @include('partials.aside')
    @yield('content')
    @include('partials.footer')
    @include('partials.script')
</body>
</html>