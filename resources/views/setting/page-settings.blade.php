@extends('index')

@section('title', 'Cấu hình ' . $config['title'])

@section('content')
<main class="main-wrapper"><div class="main-content">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-1">Cấu hình trang {{ $config['title'] }}</h5>
      <p class="mb-0 text-muted">Thiết lập banner, sidebar và SEO cho frontend.</p>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" action="{{ panel_route('setting.page.update', ['page' => $page]) }}">
        @csrf @method('PUT')
        <div class="row g-4">
          <div class="col-lg-6"><x-input-field name="title" label="Tiêu đề trang" :value="old('title', $value['title'] ?? '')" /></div>
          <div class="col-lg-6"><x-input-field name="breadcrumb" label="Nhãn breadcrumb" :value="old('breadcrumb', $value['breadcrumb'] ?? '')" /></div>
          <div class="col-lg-6">
            <label class="form-label">Banner header</label>
            @if($bannerUrl)<div class="mb-2"><img src="{{ $bannerUrl }}" style="max-width:220px;border-radius:8px" alt=""><label class="ms-2"><input type="checkbox" name="remove_banner" value="1"> Xóa</label></div>@endif
            <input class="form-control" type="file" name="banner_file" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-lg-6">
            <label class="form-label">Open Graph image</label>
            @if($ogImageUrl)<div class="mb-2"><img src="{{ $ogImageUrl }}" style="max-width:220px;border-radius:8px" alt=""><label class="ms-2"><input type="checkbox" name="remove_og_image" value="1"> Xóa</label></div>@endif
            <input class="form-control" type="file" name="og_image_file" accept=".jpg,.jpeg,.png,.webp">
          </div>
          <div class="col-lg-4"><x-input-field name="per_page" label="Số item mỗi trang" type="number" :value="old('per_page', $value['per_page'] ?? 8)" /></div>
          <div class="col-lg-8 d-flex flex-wrap gap-4 align-items-end pb-2">
            @foreach(['show_search'=>'Hiển thị tìm kiếm','show_category'=>'Hiển thị danh mục','show_recent_posts'=>'Hiển thị bài viết mới','show_tags'=>'Hiển thị tags'] as $key => $label)
              <label><input type="checkbox" name="{{ $key }}" value="1" @checked(old($key, $value[$key] ?? true))> {{ $label }}</label>
            @endforeach
          </div>
          <div class="col-md-3"><x-input-field name="search_title" label="Tiêu đề tìm kiếm" :value="old('search_title', $value['search_title'] ?? 'Tìm kiếm')" /></div>
          <div class="col-md-3"><x-input-field name="category_title" label="Tiêu đề danh mục" :value="old('category_title', $value['category_title'] ?? 'Danh mục')" /></div>
          <div class="col-md-3"><x-input-field name="recent_posts_title" label="Tiêu đề bài viết mới" :value="old('recent_posts_title', $value['recent_posts_title'] ?? 'Bài viết mới')" /></div>
          <div class="col-md-3"><x-input-field name="tags_title" label="Tiêu đề tags" :value="old('tags_title', $value['tags_title'] ?? 'Thẻ')" /></div>
          <div class="col-md-6"><x-input-field name="seo_title" label="SEO title" :value="old('seo_title', $value['seo_title'] ?? '')" /></div>
          <div class="col-md-6"><x-input-field name="canonical" label="Canonical" :value="old('canonical', $value['canonical'] ?? '')" /></div>
          <div class="col-12"><x-textarea-field name="seo_description" label="SEO description" rows="3" :value="old('seo_description', $value['seo_description'] ?? '')" /></div>
        </div>
        <div class="mt-4"><button class="btn btn-primary">Lưu cấu hình</button></div>
      </form>
    </div>
  </div>
</div></main>
@endsection
