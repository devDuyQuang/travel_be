@props(['nodes' => [], 'depth' => 0, 'prefix' => ''])

@foreach($nodes as $index => $node)
  @php
    $number = $prefix ? $prefix . '.' . ($index+1) : (string) ($index+1);
    $status = $node['status'] ?? 1;
  @endphp

  <li class="list-group-item p-5" data-id="{{ $node['id'] }}" data-sort="{{ $node['sort'] ?? 0 }}">
    <div class="d-flex justify-content-between align-items-center w-100">
      <span class="d-flex align-items-center gap-2 flex-grow-1" style="margin-left: {{ $depth * 20 }}px">
        <i class="drag-handle cursor-move icon-base ti tabler-menu-2 align-text-bottom"></i>
        <span>
          <strong class="text-primary">{{ $number }}</strong>
          {{ $node['name'] }}
        </span>
      </span>

      <span class="me-3">
        @if((int)$status === 1)
          <span class="badge bg-success">ON</span>
        @else
          <span class="badge bg-secondary">OFF</span>
        @endif
      </span>

      <x-action-dropdown
        :edit-route="panel_route(module().'.edit', $node['id'])"
        :id="$node['id']"
        edit-text="Edit"
        delete-text="Delete" />
    </div>
  </li>

  @if(!empty($node['children']))
    @include('partials.tree-items', [
      'nodes' => $node['children'],
      'depth' => $depth + 1,
      'prefix' => $number
    ])
  @endif
@endforeach
