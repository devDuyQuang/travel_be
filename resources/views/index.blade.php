<!DOCTYPE html>
<html
    lang="en"
    class="layout-navbar-fixed layout-menu-fixed layout-compact customizer-hide"
    dir="ltr"
    data-skin="default"
    data-bs-theme="dark"
    data-assets-path="../../assets/"
    data-template="vertical-menu-template">

@include('partials.head')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('partials.aside')
            @include('partials.menu-mobile')

            <div class="layout-page">
                @include('partials.nav')

                <div class="content-wrapper">
                    <div class="{{ request()->routeIs('setting.home') ? 'container-fluid' : 'container-xxl' }} flex-grow-1 container-p-y {{ request()->routeIs('setting.home') ? 'text-start' : '' }}">
                        @if(request()->routeIs('setting.home'))
                        @yield('content')
                        @else
                        <div class="card">
                            @yield('content')
                        </div>
                        @endif
                    </div>

                    @include('partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    @include('partials.script')
    @stack('scripts')
</body>

</html>