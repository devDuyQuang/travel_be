@extends('index')

@section('title', ($tag->exists ? 'Sửa' : 'Thêm') . ' thẻ bài viết')

@section('content')
<main class="main-wrapper">
  <div class="main-content">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">{{ $tag->exists ? 'Sửa thẻ bài viết' : 'Thêm thẻ bài viết' }}</h5>
        <a class="btn btn-outline-secondary" href="{{ panel_route('tag.index') }}">Quay lại</a>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $tag->exists ? panel_route('tag.update', $tag->id) : panel_route('tag.store') }}">
          @csrf
          @if($tag->exists) @method('PUT') @endif
          <div class="row g-3">
            <div class="col-md-6"><x-input-field name="name" label="Tên thẻ" :required="true" :value="old('name', $tag->name)" /></div>
            <div class="col-md-6"><x-input-field name="slug" label="Slug" :value="old('slug', $tag->slug)" /></div>
            <div class="col-md-6"><x-input-field name="sort_order" label="Thứ tự" type="number" :value="old('sort_order', $tag->sort_order ?? 0)" /></div>
            <div class="col-md-6 d-flex align-items-end">
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $tag->is_active ?? true))>
                <label class="form-check-label" for="is_active">Hiển thị</label>
              </div>
            </div>
            <div class="col-12"><x-textarea-field name="description" label="Mô tả" :value="old('description', $tag->description)" rows="4" /></div>
          </div>
          <div class="mt-4">
            <button class="btn btn-primary">Lưu thẻ</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection
