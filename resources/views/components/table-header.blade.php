@props([
  'title' => '',
  'icon' => null,
  'createRoute' => null,
  'showUpdateOrder' => false,
  'modalId' => 'updateOrderModal',
  'useDefaultModal' => true,
  'modalTitle' => 'Cập nhật Thứ Tự',
  'modalBody' => 'Xác nhận lưu lại thứ tự hiện tại?',
  'confirmText' => 'Lưu thứ tự',
  'cancelText' => 'Hủy',
  'confirmEvent' => 'update-order:confirm',
])

<div class="row card-header flex-column flex-md-row border-bottom mx-0 px-3">
  <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
    <h5 class="card-title mb-0 text-md-start text-center pb-md-0 pb-6 d-flex align-items-center gap-2">
        @if($icon)
            <i class="icon-base ti {{ $icon }}"></i>
        @endif

        {{ $title }}

      @if ($showUpdateOrder)
        <button type="button"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1"
                data-bs-toggle="modal"
                data-bs-target="#{{ $modalId }}"
                data-action="update-order">
          <i class="icon-base ti tabler-refresh icon-sm"></i>
          <span class="d-none d-sm-inline-block">Cập nhật Thứ Tự</span>
        </button>
      @endif
    </h5>
  </div>

  <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      @if ($createRoute && Route::currentRouteName() !== 'admin.comment.index')
        <a href="{{ $createRoute }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-plus icon-sm"></i>
          <span class="d-none d-sm-inline-block">Thêm Mới</span>
        </a>
      @endif
    </div>
  </div>
</div>

@if ($showUpdateOrder && $useDefaultModal)
  <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" aria-labelledby="{{ $modalId }}Label">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="{{ $modalId }}Label" class="modal-title">{{ $modalTitle }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          @if(trim($slot))
            {{ $slot }}
          @else
            <p>{{ $modalBody }}</p>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ $cancelText }}</button>
          <button type="button" class="btn btn-primary" id="{{ $modalId }}-confirm">{{ $confirmText }}</button>
        </div>
      </div>
    </div>
  </div>
@endif
