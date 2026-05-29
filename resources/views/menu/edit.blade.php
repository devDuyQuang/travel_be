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
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const topicEl = document.getElementById('menu_topic');
    const partEl = document.getElementById('part_id');

    if (!topicEl || !partEl) return;

    const oldPartId = partEl.dataset.oldPartId || '';

    const placeholderMap = {
      category: 'Chọn danh mục',
      post: 'Chọn bài viết',
    };

    const getPlaceholder = (topic) => placeholderMap[topic] || 'Chọn đường dẫn';

    async function loadTargets(topic, selectedId = '') {
      partEl.disabled = !topic;
      partEl.innerHTML = `<option value="">${getPlaceholder(topic)}</option>`;

      if (!topic) return;

      try {
        const url = "{{ panel_route(module().'.targets') }}" + '?topic=' + encodeURIComponent(topic);

        const response = await fetch(url, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();

        if (!result?.success || !Array.isArray(result.data)) {
          partEl.innerHTML = '<option value="">Không có dữ liệu</option>';
          return;
        }

        result.data.forEach(function (target) {
          const option = document.createElement('option');
          option.value = target.id;
          option.textContent = target.name;

          if (String(selectedId) === String(target.id)) {
            option.selected = true;
          }

          partEl.appendChild(option);
        });
      } catch (error) {
        console.error('Load targets error:', error);
        partEl.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
      }
    }

    topicEl.addEventListener('change', function () {
      loadTargets(this.value);
    });

    loadTargets(topicEl.value, oldPartId);
  });
</script>
@endpush