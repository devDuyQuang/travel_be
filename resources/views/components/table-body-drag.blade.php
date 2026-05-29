<div class="cpx-3 px-3">
  <ul class="list-group list-group-flush" id="{{ $listId }}">
    @foreach($items as $item)
      <li class="list-group-item d-flex justify-content-between align-items-center py-4"
          data-id="{{ $item->id }}">
        <span class="d-flex justify-content-between align-items-md-center flex-wrap gap-2">
          <i class="drag-handle cursor-move icon-base ti tabler-menu-2 align-text-bottom"></i>
          <span>{{ $item->name ?? $item->title ?? 'Item '.$item->id }}</span>
        </span>

        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="icon-base ti tabler-dots-vertical"></i>
          </button>
          <div class="dropdown-menu">
            @php
              $editUrl = '#';
              if ($editRoute) {
                  try {
                      $editUrl = route($editRoute, $item->id);
                  } catch (\Throwable $e) {
                      // nếu editRoute chứa {id} placeholder, thay thế
                      $editUrl = str_replace('{id}', $item->id, $editRoute);
                  }
              } else {
                  try {
                      $editUrl = panel_route(module().'.edit', $item->id);
                  } catch (\Throwable $e) {
                      $editUrl = '#';
                  }
              }
            @endphp

            <a class="dropdown-item" href="{{ $editUrl }}">
              <i class="icon-base ti tabler-pencil me-2"></i> Chỉnh Sửa
            </a>

            <a class="dropdown-item btn-delete" href="javascript:void(0)"
               data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
              <i class="icon-base ti tabler-trash me-2"></i> Xóa
            </a>
          </div>
        </div>
      </li>
    @endforeach
  </ul>
</div>