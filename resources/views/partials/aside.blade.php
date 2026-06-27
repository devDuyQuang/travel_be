<!--start sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
  <div class="sidebar-header">
    <div class="logo-icon">
      <img src="{{ asset('vertical-menu/assets/images/logo-icon.png') }}" class="logo-img" alt="">
    </div>
    <div class="logo-name flex-grow-1">
      <h5 class="mb-0"> CMS</h5>
    </div>
    <div class="sidebar-close">
      <span class="material-icons-outlined">close</span>
    </div>
  </div>
  <div class="sidebar-nav">
      <!--navigation-->
      <ul class="metismenu" id="sidenav">
        @php
        $user = auth()->user();
        $roleCode = $user?->role?->code;
        
        $iconMap = [
            'tabler-smart-home' => 'home',
            'tabler-news' => 'article',
            'tabler-table' => 'table_view',
            'tabler-category' => 'category',
            'tabler-award' => 'workspace_premium',
            'tabler-brand-hipchat' => 'forum',
            'tabler-message' => 'chat',
            'tabler-package' => 'inventory_2',
            'tabler-calendar-time' => 'calendar_month',
            'tabler-mail' => 'mail',
            'tabler-id' => 'badge',
            'tabler-users' => 'group',
            'tabler-user-check' => 'how_to_reg',
            'tabler-assembly' => 'settings_suggest',
            'tabler-settings' => 'settings',
            'tabler-menu-2' => 'menu',
            'tabler-file-description' => 'description',
            'tabler-home' => 'home',
            'tabler-server-spark' => 'miscellaneous_services',
            'tabler-user-screen' => 'contact_page',
        ];
        @endphp

        @foreach($menus as $menu)
        @php
        $children = $menu['childrens'] ?? [];

        if (isset($menu['roles']) && !in_array($roleCode, $menu['roles'])) {
            continue;
        }

        $children = collect($children)
        ->filter(function ($child) use ($roleCode) {
            return !isset($child['roles']) || in_array($roleCode, $child['roles']);
        })
        ->values()
        ->all();

        $hasChildren = count($children) > 0;

        $isParentActive = false;
        $isChildActive = false;

        if (!$hasChildren && !empty($menu['route'])) {
            try {
                $isParentActive = request()->url() === panel_route($menu['route']);
            } catch (\Exception $e) {}
        }

        if (!$isParentActive && !empty($menu['url'])) {
            $menuSegment = trim($menu['url'], '/');
            $isParentActive = request()->is($menuSegment) || request()->is($menuSegment . '/*');
        }

        foreach ($children as $child) {
            if (!empty($child['route'])) {
                try {
                    if (request()->url() === panel_route($child['route'])) {
                        $isChildActive = true;
                        break;
                    }
                } catch (\Exception $e) {}
            }

            if (!empty($child['url'])) {
                $childSegment = trim($child['url'], '/');
                if (request()->is($childSegment) || request()->is($childSegment . '/*')) {
                    $isChildActive = true;
                    break;
                }
            }
        }

        $isOpen = $hasChildren && ($isParentActive || $isChildActive);
        $matIcon = $iconMap[$menu['icon'] ?? ''] ?? 'radio_button_unchecked';
        @endphp

        <li class="{{ $isOpen || $isParentActive || $isChildActive ? 'mm-active' : '' }}">
          <a href="{{ $hasChildren ? 'javascript:;' : (!empty($menu['route']) ? panel_route($menu['route']) : (!empty($menu['url']) ? url($menu['url']) : 'javascript:;')) }}" class="{{ $hasChildren ? 'has-arrow' : '' }}">
            <div class="parent-icon"><i class="material-icons-outlined">{{ $matIcon }}</i>
            </div>
            <div class="menu-title">{{ $menu['label'] ?? '' }}</div>
          </a>
          
          @if($hasChildren)
          <ul class="{{ $isOpen ? 'mm-collapse mm-show' : 'mm-collapse' }}">
            @foreach($children as $child)
            @php
            $childActive = false;

            if (!empty($child['route'])) {
                try {
                    $childActive = request()->url() === panel_route($child['route']);
                } catch (\Exception $e) {}
            }

            if (!$childActive && !empty($child['url'])) {
                $childSegment = trim($child['url'], '/');
                $childActive = request()->is($childSegment) || request()->is($childSegment . '/*');
            }
            @endphp
            <li class="{{ $childActive ? 'mm-active' : '' }}">
                <a href="{{ !empty($child['route']) ? panel_route($child['route']) : (!empty($child['url']) ? url($child['url']) : 'javascript:;') }}" class="{{ $childActive ? 'active' : '' }}">
                    <i class="material-icons-outlined">arrow_right</i>{{ $child['label'] ?? '' }}
                </a>
            </li>
            @endforeach
          </ul>
          @endif
        </li>
        @endforeach

      </ul>
      <!--end navigation-->
  </div>
</aside>
<!--end sidebar-->
