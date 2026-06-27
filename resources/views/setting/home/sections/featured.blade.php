@php $configured = collect($tabs ?? [])->keyBy('category_id'); @endphp
<form action="{{ panel_route('setting.home.update', ['section' => 'featured']) }}" method="POST" class="ajax-form">
  @csrf @method('PUT')
  <div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" rows="2" name="description">{{ $description ?? '' }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Số sản phẩm</label><input class="form-control" type="number" min="1" max="24" name="limit" value="{{ $limit ?? 8 }}"></div>
    <div class="col-md-8"><input type="hidden" name="featured_first" value="0"><label class="form-check form-switch mt-4"><input class="form-check-input" type="checkbox" name="featured_first" value="1" @checked($featured_first ?? true)><span class="form-check-label">Ưu tiên sản phẩm nổi bật</span></label></div>
  </div>
  <div class="table-responsive border rounded">
    <table class="table align-middle mb-0">
      <thead><tr><th>Hiển thị tab</th><th>Thứ tự</th><th>Category</th><th>Tên tùy chỉnh</th></tr></thead>
      <tbody>
      @foreach($serviceCategories as $index => $category)
        @php $tab = $configured->get($category->id, []); @endphp
        <tr>
          <input type="hidden" name="tabs[{{ $index }}][category_id]" value="{{ $category->id }}">
          <td><input type="hidden" name="tabs[{{ $index }}][enabled]" value="0"><input class="form-check-input" type="checkbox" name="tabs[{{ $index }}][enabled]" value="1" @checked($tab['enabled'] ?? true)></td>
          <td style="width:100px"><input class="form-control" type="number" min="0" name="tabs[{{ $index }}][sort]" value="{{ $tab['sort'] ?? $index }}"></td>
          <td>{{ $category->name }}</td>
          <td><input class="form-control" name="tabs[{{ $index }}][display_name]" value="{{ $tab['display_name'] ?? '' }}" placeholder="{{ $category->name }}"></td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu Sản phẩm nổi bật</button></div>
</form>
