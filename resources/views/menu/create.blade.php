@extends('index')

@section('title', 'Thêm ' . page_title())

@section('content')
@include('partials.css.menu')

<main class="main-wrapper menu-form-page">
  <div class="main-content">
    <h5 class="card-header menu-form-title">
      Thêm {{ page_title() }}
    </h5>

    <div class="card-body menu-form-card">
      <div id="ajax-alert" class="alert" role="alert" style="display:none"></div>

      <form
        action="{{ panel_route(module().'.store') }}"
        method="POST"
        id="menu-form"
        class="ajax-form"
        data-index-url="{{ panel_route(module().'.index') }}">
        @csrf

        <x-input-field
          name="name"
          label="Tên menu"
          :value="old('name')"
          placeholder="Ví dụ: Home, About, Service" />

        <div class="mb-6">
          <label for="menu_topic" class="form-label">Chọn chủ đề</label>
          <select name="topic" id="menu_topic" class="form-select no-select2">
            <option value="">Chọn chủ đề</option>
            @foreach($types as $value => $label)
              <option value="{{ $value }}" @selected(old('topic') == $value)>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-6" id="menu-target-wrap">
          <label for="part_id" class="form-label">Chọn đường dẫn</label>
          <select name="part_id" id="part_id" class="form-select no-select2">
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
            value="{{ old('custom_path') }}"
            placeholder="Ví dụ: /, /about, /tour-grid-1, /blog-grid, /contact">
        </div>

        <x-select-field
          name="parent_id"
          label="Menu Cha"
          :options="$parents"
          :value="old('parent_id')"
          id="parent_id"
          placeholder="Chọn Menu Cha" />

        <x-select-field
          name="location"
          label="Vị trí"
          :options="$locations"
          :value="old('location')"
          placeholder="Chọn vị trí" />

        <x-submit-buttons
          :cancel-route="panel_route(module().'.index')"
          submit-text="Lưu"
          cancel-text="Thoát" />
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
@include('partials.js.menu-create')
@endpush