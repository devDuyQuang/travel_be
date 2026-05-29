<div class="row mt-4">
    <div class="col-12 text-end">
        <button id="btnSubmit" type="submit" class="btn btn-primary me-2">
            {{ $submitText }}
        </button>
        @if ($cancelRoute)
        <a href="{{ $cancelRoute }}" class="btn btn-label-secondary">
            {{ $cancelText }}
        </a>
        @endif
    </div>
</div>
