<aside id="layout-menu" class="layout-menu menu-vertical menu">
   <div class="app-brand demo">
      <a href="/dashboard" class="app-brand-link">
         <span class="app-brand-logo demo">
            <span class="text-primary">
               {{-- Logo --}}
            </span>
         </span>
         <span class="app-brand-text demo menu-text fw-bold ms-3">Shenlong</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
         <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
         <i class="icon-base ti tabler-x d-block d-xl-none"></i>
      </a>
   </div>

   <div class="menu-inner-shadow"></div>

   @php
   $user = auth()->user();
   $roleCode = $user?->role?->code;
   @endphp

   <ul class="menu-inner py-1">
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
      $isParentActive = request()->url() === panel_route($menu['route']);
      }

      if (!$isParentActive && !empty($menu['url'])) {
      $menuSegment = trim($menu['url'], '/');
      $isParentActive = request()->is($menuSegment);
      }

      foreach ($children as $child) {
      if (!empty($child['route']) && request()->url() === panel_route($child['route'])) {
      $isChildActive = true;
      break;
      }

      if (!empty($child['url'])) {
      $childSegment = trim($child['url'], '/');

      if (request()->is($childSegment)) {
      $isChildActive = true;
      break;
      }
      }
      }

      $isOpen = $hasChildren && ($isParentActive || $isChildActive);
      @endphp

      <li class="menu-item {{ $isParentActive || $isChildActive ? 'active' : '' }} {{ $isOpen ? 'open' : '' }}">
         <a
            href="{{ $hasChildren ? 'javascript:void(0);' : panel_route($menu['route']) }}"
            class="menu-link {{ $hasChildren ? 'menu-toggle' : '' }}">
            <i class="menu-icon icon-base ti {{ $menu['icon'] ?? 'tabler-circle' }}"></i>
            <div>{{ $menu['label'] ?? '' }}</div>
         </a>

         @if($hasChildren)
         <ul class="menu-sub">
            @foreach($children as $child)
            @php
            $childActive = false;

            if (!empty($child['route'])) {
            $childActive = request()->url() === panel_route($child['route']);
            }

            if (!$childActive && !empty($child['url'])) {
            $childSegment = trim($child['url'], '/');
            $childActive = request()->is($childSegment);
            }
            @endphp

            <li class="menu-item {{ $childActive ? 'active' : '' }}">
               <a href="{{ panel_route($child['route']) }}" class="menu-link menu-link-none-list">
                @if(!empty($child['icon']))
                        <i class="menu-icon icon-base ti {{ is_array($child['icon']) ? ($child['icon'][0] ?? 'tabler-circle-dot') : $child['icon'] }}"></i>
                        @endif

                  <div>{{ $child['label'] ?? '' }}</div>
               </a>
            </li>
            @endforeach
         </ul>
         @endif
      </li>
      @endforeach
   </ul>
</aside>
<style>
    a.menu-link-none-list::before {
        display: none;
    }

    .menu-vertical .menu-sub .menu-link-none-list {
        padding-inline-start: 2.4rem;
    }
    </style>
