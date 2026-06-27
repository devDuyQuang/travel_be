<form action="{{ panel_route('setting.updateAboutHero') }}" method="POST" enctype="multipart/form-data" class="ajax-form" data-require-persist="true">
  @csrf
  @method('PUT')
  <div class="row g-3 p-2">
    <div class="col-md-12">
      <div class="card shadow-none border bg-transparent">
        <div class="card-header border-bottom">
          <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="about-hero-main-tab" data-bs-toggle="tab"
                data-bs-target="#about-hero-main" type="button" role="tab">
                Nội dung chính &amp; Banner
              </button>
            </li>
          </ul>
        </div>
        <div class="card-body p-3">
          <div class="tab-content">
            <div class="tab-pane fade show active" id="about-hero-main" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-6">
                  <x-input-field label="Tiêu đề trang" name="title" :value="$v['title'] ?? ''" placeholder="About Us" />
                </div>
                <div class="col-md-6">
                  <x-input-field label="Nhãn breadcrumb" name="sub_title" :value="$v['sub_title'] ?? ''" placeholder="About Us" />
                </div>
                <div class="col-md-12">
                  <x-file-input label="Banner Header" name="banner_hero_file" :multiple="false" :current-url="$currentBannerHeroUrl ?? null" remove-name="remove_banner_hero_file" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 mt-3 text-end">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </div>
</form>
