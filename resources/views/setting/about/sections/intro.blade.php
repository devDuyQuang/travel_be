<form action="{{ panel_route('setting.updateAboutIntro') }}" method="POST" enctype="multipart/form-data" class="ajax-form" data-require-persist="true">
  @csrf
  @method('PUT')
  @php $intro = $v ?? []; @endphp

  <div class="card shadow-none border">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <x-input-field
            label="Tiêu đề phụ"
            name="subtitle"
            :value="$intro['subtitle'] ?? ''"
            placeholder="Explore the world with us" />
        </div>
        <div class="col-md-6">
          <x-input-field
            label="Tiêu đề chính"
            name="title"
            :value="$intro['title'] ?? ''"
            placeholder="The perfect vacation come true with our Travel Agency" />
        </div>
        <div class="col-12">
          <x-textarea-field
            label="Mô tả"
            name="description"
            :value="$intro['description'] ?? ''"
            placeholder="Nhập nội dung giới thiệu..."
            rows="4" />
        </div>
        <div class="col-md-6">
          <x-input-field
            label="Nội dung nút"
            name="button_text"
            :value="$intro['button_text'] ?? ''"
            placeholder="Book Your Room" />
        </div>
        <div class="col-md-6">
          <x-input-field
            label="Liên kết nút"
            name="button_link"
            :value="$intro['button_link'] ?? ''"
            placeholder="/tour-details" />
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3 text-end">
    <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
    <button type="reset" class="btn btn-label-secondary">Hủy</button>
  </div>
</form>
