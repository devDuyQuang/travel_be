@extends('index')

@section('title', 'Chỉnh Sửa ' . page_title())

@section('content')
@include('partials.css.menu')

<main class="main-wrapper menu-form-page">
  <div class="main-content">
    <h5 class="card-header menu-form-title">
      Chỉnh Sửa {{ page_title() }}: {{ $item->name }}
    </h5>

    <div class="card-body menu-form-card">
      <div id="ajax-alert" class="alert" role="alert" style="display:none"></div>

      <form
      action="{{ panel_route(module().'.update', $item->id) }}"
  method="POST"
  id="menu-form"
  class="ajax-form"
  data-stay="true"
  data-index-url="{{ panel_route(module().'.index') }}">
        @csrf
        @method('PUT')

        <x-input-field
          name="name"
          label="Tên menu"
          :value="old('name', $item->name)"
          placeholder="Ví dụ: Home, About, Service" />

        <div class="mb-6">
          <label for="menu_topic" class="form-label">Chọn chủ đề</label>
          <select name="topic" id="menu_topic" class="form-select no-select2">
            <option value="">Chọn chủ đề</option>
            @foreach($types as $value => $label)
              <option value="{{ $value }}" @selected(old('topic', $item->topic) == $value)>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-6" id="menu-target-wrap">
          <label for="part_id" class="form-label">Chọn đường dẫn</label>
          <select
            name="part_id"
            id="part_id"
            class="form-select no-select2"
            data-old-part-id="{{ old('part_id', $item->part_id ?? '') }}">
            <option value="">Chọn đường dẫn</option>
          </select>
        </div>

        <div class="mb-6" id="menu-custom-path-wrap" style="display:none">
          <label for="custom_path" class="form-label">Liên kết tùy chỉnh</label>
          <input
            type="text"
            name="custom_path"
            id="custom_path"
            class="form-control"
            value="{{ old('custom_path', $item->topic === 'custom' ? $item->path : '') }}"
            placeholder="Ví dụ: /, /about, /tour-grid-1, /blog-grid, /contact">
        </div>

        <x-select-field
          name="parent_id"
          label="Menu Cha"
          :options="$parents"
          :value="old('parent_id', $item->parent_id)"
          id="parent_id"
          placeholder="Chọn Menu Cha" />

        <x-select-field
          name="location"
          label="Vị trí"
          :options="$locations"
          :value="old('location', $item->location)"
          placeholder="Chọn vị trí" />

        @include('partials.debug.json-response')
        <x-submit-buttons
          :cancel-route="panel_route(module().'.index')"
          submit-text="Cập nhật"
          cancel-text="Thoát" />
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
@include('partials.js.menu-edit')
@endpush