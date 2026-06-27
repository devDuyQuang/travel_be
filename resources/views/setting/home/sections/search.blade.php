@php $configured = collect($tabs ?? [])->keyBy('category_id'); @endphp
<form action="{{ panel_route('setting.home.update', ['section' => 'search']) }}" method="POST" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-check form-switch mt-4"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị bộ tìm kiếm</span></label></div>
    <div class="col-md-8"><label class="form-label">Nhãn nút tìm kiếm</label><input class="form-control" name="button_label" value="{{ $button_label ?? '' }}" placeholder="Tìm kiếm"></div>
  </div>
  <div class="table-responsive border rounded">
    <table class="table align-middle mb-0">
      <thead><tr><th>Hiển thị</th><th>Thứ tự</th><th>Dịch vụ</th><th>Tên tùy chỉnh</th><th>Placeholder địa điểm</th></tr></thead>
      <tbody>
      @foreach($serviceCategories as $index => $category)
        @php $tab = $configured->get($category->id, []); @endphp
        <tr>
          <input type="hidden" name="tabs[{{ $index }}][category_id]" value="{{ $category->id }}">
          <td><input type="hidden" name="tabs[{{ $index }}][enabled]" value="0"><input class="form-check-input" type="checkbox" name="tabs[{{ $index }}][enabled]" value="1" @checked($tab['enabled'] ?? true)></td>
          <td style="width:100px"><input class="form-control" type="number" min="0" name="tabs[{{ $index }}][sort]" value="{{ $tab['sort'] ?? $index }}"></td>
          <td><strong>{{ $category->name }}</strong><div class="small text-muted">{{ $category->layout_key }}</div></td>
          <td><input class="form-control" name="tabs[{{ $index }}][display_name]" value="{{ $tab['display_name'] ?? '' }}" placeholder="{{ $category->name }}"></td>
          <td><input class="form-control" name="tabs[{{ $index }}][placeholder]" value="{{ $tab['placeholder'] ?? '' }}"></td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Tìm kiếm dịch vụ</button></div>
</form>
