@csrf

@if(isset($item))
@method('PUT')
@endif

<x-input-field
    name="name"
    label="Tên vai trò"
    :required="true"
    :value="old('name', $item->name ?? '')" />

<x-input-field
    name="code"
    label="Code"
    :required="true"
    :value="old('code', $item->code ?? '')" />

<x-submit-buttons
    :cancel-route="panel_route('role.index')"
    :submit-text="isset($item) ? 'Cập nhật' : 'Lưu'"
    cancel-text="Thoát" />