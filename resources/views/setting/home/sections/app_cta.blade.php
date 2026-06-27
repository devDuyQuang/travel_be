<form action="{{ panel_route('setting.home.update', ['section' => 'app_cta']) }}" method="POST" enctype="multipart/form-data" class="ajax-form">
  @csrf @method('PUT')
  <input type="hidden" name="enabled" value="0">
  <div class="row g-3">
    <div class="col-12"><label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="enabled" value="1" @checked($enabled ?? true)><span class="form-check-label">Hiển thị section</span></label></div>
    <div class="col-md-6">@include('setting.home.partials.image-field', ['label' => 'Ảnh nền', 'current' => $background ?? '', 'currentName' => 'background', 'fileName' => 'background_file', 'removeName' => 'remove_background'])</div>
    <div class="col-md-6">@include('setting.home.partials.image-field', ['label' => 'Ảnh điện thoại', 'current' => $phone_image ?? '', 'currentName' => 'phone_image', 'fileName' => 'phone_image_file', 'removeName' => 'remove_phone_image'])</div>
    <div class="col-md-4"><label class="form-label">Dòng giới thiệu</label><input class="form-control" name="subtitle" value="{{ $subtitle ?? '' }}"></div>
    <div class="col-md-8"><label class="form-label">Tiêu đề</label><input class="form-control" name="title" value="{{ $title ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Google Play URL</label><input class="form-control" name="google_play_link" value="{{ $google_play_link ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">App Store URL</label><input class="form-control" name="app_store_link" value="{{ $app_store_link ?? '' }}"></div>
  </div>
  <div class="text-end mt-3"><button class="btn btn-primary">Lưu CTA cuối trang</button></div>
</form>
