@extends('index')

@section('title', 'Chỉnh Sửa ' . page_title())

@section('content')
<h5 class="card-header">Chỉnh Sửa {{ page_title() }}</h5>

<div class="card-body">
  <div id="ajax-alert" class="alert" role="alert" style="display:none"></div>

  <form action="{{ panel_route(module().'.update', $item->id) }}" method="POST" id="menu-form">
    @csrf
    @method('PUT')

    <x-input-field
      name="name"
      label="Tên menu"
      :value="old('name', $item->name)"
      placeholder="Nhập tên menu" />

    <div class="mb-3">
      <label for="menu_topic" class="form-label">Chọn chủ đề</label>
      <select name="topic" id="menu_topic" class="form-select">
        <option value="">Chọn chủ đề</option>
        @foreach($types as $value => $label)
          <option value="{{ $value }}" {{ old('topic', $item->topic) == $value ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="part_id" class="form-label">Chọn đường dẫn</label>
      <select
        name="part_id"
        id="part_id"
        class="form-select"
        data-old-part-id="{{ old('part_id', $item->part_id ?? '') }}">
        <option value="">Chọn đường dẫn</option>
      </select>
    </div>

    <x-select-field
      name="parent_id"
      label="Menu Cha"
      :options="$parents"
      :value="old('parent_id', $item->parent_id)"
      id="parent_id"
      placeholder="Chọn {{ page_title() }} Cha" />

    <x-select-field
      name="location"
      label="Vị trí (location)"
      :options="$locations"
      :value="old('location', $item->location)"
      placeholder="Chọn vị trí" />

    <x-submit-buttons
      :cancel-route="panel_route(module().'.index')"
      submit-text="Cập nhật"
      cancel-text="Thoát" />
  </form>
</div>
@endsection

@push('scripts')
@include('partials.js.menu-edit')
@endpush