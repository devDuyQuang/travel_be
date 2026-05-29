@props([
  'editRoute'     => null,
  'destroyRoute'  => null,    // ← mới: URL DELETE
  'id'            => null,    // dùng để build destroyRoute fallback nếu muốn
  'name'          => 'bản ghi',
  'editText'      => 'Chỉnh Sửa',
  'deleteText'    => 'Xóa',
])

<div class="dropdown">
  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
    <i class="icon-base ti tabler-dots-vertical"></i>
  </button>

  <div class="dropdown-menu">
    @if($editRoute)
      <a class="dropdown-item" href="{{ $editRoute }}">
        <i class="icon-base ti tabler-pencil me-2"></i> {{ $editText }}
      </a>
    @endif

    <a class="dropdown-item btn-delete"
       href="javascript:void(0)"
       data-id="{{ $id }}"
       data-name="{{ $name }}"
       data-url="{{ $destroyRoute ?? (function_exists('module') ? panel_route(module().'.destroy', $id) : '') }}"
       data-bs-toggle="modal"
       data-bs-target="#deleteModal">
      <i class="icon-base ti tabler-trash me-2"></i> {{ $deleteText }}
    </a>
  </div>
</div>
