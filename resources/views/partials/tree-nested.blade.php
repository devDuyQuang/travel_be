@props(['nodes' => [], 'depth' => 0])

@php
  $isRoot = (($depth ?? 0) === 0);

  // Chuẩn bị bộ đếm theo location cho cấp ROOT
  if ($isRoot && !isset($GLOBALS['__locCounters'])) {
      $GLOBALS['__locCounters'] = []; // ['header' => n, 'footer' => n, 'other' => n]
  }
@endphp

<ul
  @class(['list-group','list-group-flush','nested-list', 'ms-3' => ($depth ?? 0) > 0])
  @if($isRoot)
    id="handle-list-1"
    data-update-url="{{ panel_route(module().'.update-order') }}"
    data-refresh-url="{{ panel_route(module().'.index') }}"
    data-module="{{ module() }}"
  @endif
>
  @foreach($nodes as $node)
    @php
      $id       = $node['id']        ?? null;
      $name     = $node['name']      ?? ('Item '.$id);
      $status   = (int)($node['status'] ?? 1);
      $location = $node['location']  ?? null;
      $type     = $node['type']      ?? null;
      $path     = $node['path']      ?? null;
      $children = $node['children']  ?? [];

      // Class màu cho location
      $locClass = match(strtolower((string)$location)) {
        'header' => 'bg-label-primary',
        'footer' => 'bg-label-warning',
        default  => 'bg-label-secondary',
      };

      // Tạo href hợp lệ cho path (nếu là relative thì prepend domain)
      $href = null;
      if (!empty($path)) {
          // Lấy domain hiện tại (có thể có admin.)
          $currentHost = request()->getHost(); // Ví dụ: admin.heovl.us

          // Loại bỏ 'admin.' nếu có ở đầu
          $mainHost = preg_replace('/^admin\./i', '', $currentHost);

          // Tạo base URL với https
          $baseUrl = (request()->secure() ? 'https' : 'http') . '://' . $mainHost;

          // Tạo href
          $href = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
              ? $path
              : $baseUrl . '/' . ltrim($path, '/');
      }

      // Số thứ tự hiển thị:
      // - Ở ROOT: reset theo từng location (header riêng, footer riêng, location khác riêng)
      // - Ở các cấp con: dùng $loop->iteration mặc định theo sibling
      $displayIndex = $loop->iteration;
      if ($isRoot) {
          $locKey = strtolower((string)($location ?? 'other'));
          if (!isset($GLOBALS['__locCounters'][$locKey])) {
              $GLOBALS['__locCounters'][$locKey] = 0;
          }
          $GLOBALS['__locCounters'][$locKey]++;
          $displayIndex = $GLOBALS['__locCounters'][$locKey];
      }
    @endphp

    <li class="list-group-item p-5"
        data-id="{{ $id }}"
        @if(!is_null($location)) data-location="{{ $location }}" @endif
        @if(!is_null($type)) data-type="{{ $type }}" @endif
        @if(!empty($path)) data-path="{{ $path }}" @endif
    >
      <div class="d-flex justify-content-between align-items-center w-100">
        {{-- Left: drag + name + type + path (và icon mở link) --}}
        <span class="d-flex align-items-center gap-2 flex-grow-1 flex-wrap">
          <i class="drag-handle cursor-move icon-base ti tabler-menu-2 align-text-bottom"></i>

          <span class="whitespace-nowrap">
            <strong class="text-primary">{{ $displayIndex }}</strong>
            {{ $name }}
          </span>

          {{-- Type badge (nếu có) --}}
          @if(!empty($type))
            <span class="badge bg-label-secondary text-uppercase">{{ $type }}</span>
          @endif

          {{-- Path (nếu có) + icon link mở tab mới --}}
          @if(!empty($path))
            <small class="text-muted d-inline-flex align-items-center gap-1">
              /{{ ltrim($path, '/') }}
              @if($href)
                <a href="{{ $href }}" class="text-muted" target="_blank" rel="noopener"
                   data-bs-toggle="tooltip" title="Mở đường dẫn">
                  <i class="ti tabler-external-link"></i>
                </a>
              @endif
            </small>
          @endif
        </span>

        {{-- Right: location + status + actions --}}
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex align-items-center gap-2">
            {{-- Location badge (đổi màu theo header/footer) --}}
            @if(!empty($location))
              <span class="badge {{ $locClass }} text-capitalize">{{ $location }}</span>
            @endif

            {{-- Status --}}
            @if($status)
              <span class="badge bg-success">ON</span>
            @else
              <span class="badge bg-secondary">OFF</span>
            @endif
          </span>

          <x-action-dropdown
              :edit-route="panel_route(module().'.edit', $id)"
              :destroy-route="panel_route(module().'.destroy', $id)"
              :id="$id"
              :name="$name ?? 'bản ghi'"
              edit-text="Edit"
              delete-text="Delete"
          />
        </div>
      </div>

      {{-- Children --}}
      @if(!empty($children))
        @include('partials.tree-nested', ['nodes' => $children, 'depth' => $depth + 1])
      @else
        <ul class="list-group list-group-flush nested-list ms-3"></ul>
      @endif
    </li>
  @endforeach
</ul>
