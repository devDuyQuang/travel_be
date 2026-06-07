@extends('index')
@section('title', page_title())

@section('content')
@include('partials.css.setting')

<main class="main-wrapper setting-page">
  <div class="main-content">
<div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
  <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap"> <i class="icon-base ti tabler-settings"></i>{{ page_title() }}</h5>


  <div class="d-flex align-items-center gap-2">
    <span class="small text-muted">Loại domain:</span>
    <div class="btn-group" role="group" aria-label="Chọn loại domain">
      @foreach(['clinic' => 'clinic', 'rac' => 'RAC'] as $typeKey => $typeLabel)
        <a href="{{ panel_route(module().'.index') . '?type=' . $typeKey . '&tab=' . request('tab', 'config') }}"
           class="btn btn-sm setting-type-switch {{ (isset($settingType) ? $settingType : 'clinic') === $typeKey ? 'btn-primary' : 'btn-outline-primary' }}"
           data-type="{{ $typeKey }}">
          {{ $typeLabel }}
        </a>
      @endforeach
    </div>
  </div>
</div>

@php
    $tabs = [
        ['id' => 'home', 'title' => 'THÔNG TIN CHUNG', 'active' => true],
    ];
@endphp

<!-- ['id' => 'facilities_home',     'title' => 'TIỆN ÍCH TRANG CHỦ'],
['id' => 'statistics_home',     'title' => 'THỐNG KÊ TRANG CHỦ'],
['id' => 'service_home',        'title' => 'DỊCH VỤ TRANG CHỦ'],
['id' => 'appoinment_home',     'title' => 'ĐẶT HẸN TRANG CHỦ'],
['id' => 'whyus_home',          'title' => 'TẠI SAO CHỌN TRANG CHỦ'],
['id' => 'team_home',           'title' => 'TEAM TRANG CHỦ'],
['id' => 'patient_home',        'title' => 'BỆNH NHÂN TRANG CHỦ'],
['id' => 'workflow_home',       'title' => 'QUY TRÌNH TRANG CHỦ'],
['id' => 'doctor_home',         'title' => 'BÁC SĨ TRANG CHỦ'],
['id' => 'faq_home',            'title' => 'FAQ TRANG CHỦ'],
['id' => 'awards_home',         'title' => 'BẰNG CẤP TRANG CHỦ'],
['id' => 'blog_home',           'title' => 'TIN TỨC TRANG CHỦ'],
['id' => 'contact_home',        'title' => 'LIÊN HỆ TRANG CHỦ'],
['id' => 'touch_home',          'title' => 'THÊM LIÊN HỆ TRANG CHỦ'],
['id' => 'footer_home',         'title' => 'FOOTER TRANG CHỦ'],
['id' => 'copyright_home',      'title' => 'COPYRIGHT TRANG CHỦ'], -->

<div class="card-body text-start">
  <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

 <div class="row g-4 justify-content-start setting-layout-row">
    {{-- Cột trái: navbar dọc --}}
   <div class="col-12 col-lg-auto text-start setting-sidebar-col">
      <div class="nav flex-column nav-pills border rounded p-2 text-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a href="#main-section-config"
           class="nav-link text-start {{ request('tab', 'config') === 'config' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-config"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-config"
           aria-selected="{{ request('tab', 'config') === 'config' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">1.</span>Thông tin chung
        </a>
        <a href="#main-section-logo"
           class="nav-link text-start {{ request('tab') === 'logo' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-logo"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-logo"
           aria-selected="{{ request('tab') === 'logo' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">2.</span>Logo &amp; Favicon
        </a>
        <a href="#main-section-topbar"
           class="nav-link text-start {{ request('tab') === 'topbar' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-topbar"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-topbar"
           aria-selected="{{ request('tab') === 'topbar' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">3.</span>Thông tin Topbar
        </a>
        <a href="#main-section-floating"
           class="nav-link text-start {{ request('tab') === 'floating' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-floating"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-floating"
           aria-selected="{{ request('tab') === 'floating' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">4.</span>Mạng xã hội
        </a>
        <a href="#main-section-services"
           class="nav-link text-start {{ request('tab') === 'services' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-services"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-services"
           aria-selected="{{ request('tab') === 'services' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">5.</span>Dịch vụ
        </a>
        <a href="#main-section-why_choose_us"
           class="nav-link text-start {{ request('tab') === 'why_choose_us' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-why_choose_us"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-why_choose_us"
           aria-selected="{{ request('tab') === 'why_choose_us' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">6.</span>Tại sao chọn chúng tôi
        </a>
        <a href="#main-section-utilities"
           class="nav-link text-start {{ request('tab') === 'utilities' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-utilities"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-utilities"
           aria-selected="{{ request('tab') === 'utilities' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">7.</span>Tiện ích
        </a>
        <a href="#main-section-testimonials"
           class="nav-link text-start {{ request('tab') === 'testimonials' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-testimonials"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-testimonials"
           aria-selected="{{ request('tab') === 'testimonials' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">8.</span>Ý kiến khách hàng
        </a>
        <a href="#main-section-doctor"
           class="nav-link text-start {{ request('tab') === 'doctor' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-doctor"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-doctor"
           aria-selected="{{ request('tab') === 'doctor' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">9.</span>Bác sĩ
        </a>
        <a href="#main-section-specialists"
           class="nav-link text-start {{ request('tab') === 'specialists' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-specialists"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-specialists"
           aria-selected="{{ request('tab') === 'specialists' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">10.</span>Đội ngũ bác sĩ
        </a>
        <a href="#main-section-appointment"
           class="nav-link text-start {{ request('tab') === 'appointment' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-appointment"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-appointment"
           aria-selected="{{ request('tab') === 'appointment' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">11.</span>Form đặt lịch hẹn
        </a>
        <a href="#main-section-faq"
           class="nav-link text-start {{ request('tab') === 'faq' ? 'active' : '' }} rounded mb-1 small"
           id="main-tab-faq"
           data-bs-toggle="pill"
           role="tab"
           aria-controls="main-section-faq"
           aria-selected="{{ request('tab') === 'faq' ? 'true' : 'false' }}"
           style="text-align: left !important; justify-content: flex-start !important;">
          <span class="me-1 text-muted">12.</span>Hỏi đáp
        </a>
      </div>
    </div>

    {{-- Cột phải: nội dung tab ngang --}}
  <div class="col-12 setting-content-col border rounded p-3 setting-content-box">
      <div class="tab-content" id="main-sections-tabContent" style="padding: 0 !important;">
        <div class="tab-pane fade {{ request('tab', 'config') === 'config' ? 'show active' : '' }}" id="main-section-config" role="tabpanel" aria-labelledby="main-tab-config">
          {{-- Ẩn tab header dư thừa của x-tab-form (chỉ có 1 tab nên không cần hiện) --}}
          <style>
            #main-section-config > .ajax-form .nav-align-top > ul.nav.nav-tabs { display: none !important; }
            #main-section-config > .ajax-form .nav-align-top .tab-content { padding: 0 !important; }
          </style>
          <x-tab-form
            :action="panel_route(module().'.update', $item->id ?? 1)"
            :index-url="panel_route(module().'.index')"
            method="PUT"
            :tabs="$tabs">

    <x-slot name="home">
      {{-- Thông tin chung - không chia tab, gộp thành từng cụm --}}
      <div class="p-2">
        <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">1. Thông Tin Chung</h5>
        {{-- Cụm: Công ty --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-building me-1"></i>Công ty</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field name="company" label="Tên công ty" :value="old('value.company', data_get($item->value, 'company'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="copyright" label="Bản quyền (Copyright)" :value="old('value.copyright', data_get($item->value, 'copyright'))" />
            </div>
            <div class="col-12">
              <x-textarea-field name="description" label="Mô tả chung" :value="old('value.description', data_get($item->value, 'description'))" rows="3" />
            </div>
          </div>
        </div>

        {{-- Cụm: Email --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-mail me-1"></i>Email</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field name="email_icon" label="Icon email" :value="old('value.email_icon', data_get($item->value, 'email_icon'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="email_title" label="Tiêu đề email" :value="old('value.email_title', data_get($item->value, 'email_title'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="email_description" label="Mô tả email" :value="old('value.email_description', data_get($item->value, 'email_description'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="email_placeholder" label="Placeholder email" :value="old('value.email_placeholder', data_get($item->value, 'email_placeholder'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Điện thoại --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-phone me-1"></i>Điện thoại</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <x-input-field name="phone_icon" label="Icon điện thoại" :value="old('value.phone_icon', data_get($item->value, 'phone_icon'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="phone_title" label="Tiêu đề điện thoại" :value="old('value.phone_title', data_get($item->value, 'phone_title'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="phone_description" label="Số điện thoại" :value="old('value.phone_description', data_get($item->value, 'phone_description'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Địa chỉ --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-map-pin me-1"></i>Địa chỉ</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <x-input-field name="address_icon" label="Icon địa chỉ" :value="old('value.address_icon', data_get($item->value, 'address_icon'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="address_title" label="Tiêu đề địa chỉ" :value="old('value.address_title', data_get($item->value, 'address_title'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="address_description" label="Nội dung địa chỉ" :value="old('value.address_description', data_get($item->value, 'address_description'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Thời gian --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-clock me-1"></i>Thời gian</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <x-input-field name="time_icon" label="Icon thời gian" :value="old('value.time_icon', data_get($item->value, 'time_icon'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="time_title" label="Tiêu đề thời gian" :value="old('value.time_title', data_get($item->value, 'time_title'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="time_description" label="Giờ hoạt động" :value="old('value.time_description', data_get($item->value, 'time_description'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Nút đặt lịch & Đăng ký --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-calendar me-1"></i>Nút hành động</h6>
          <div class="row g-3">
            <div class="col-md-3">
              <x-input-field name="btn_appointment_title" label="Tiêu đề nút đặt lịch" :value="old('value.btn_appointment_title', data_get($item->value, 'btn_appointment_title'))" />
            </div>
            <div class="col-md-3">
              <x-input-field name="btn_appointment_link" label="Link nút đặt lịch" :value="old('value.btn_appointment_link', data_get($item->value, 'btn_appointment_link'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="btn_register_title" label="Tiêu đề nút đăng ký" :value="old('value.btn_register_title', data_get($item->value, 'btn_register_title'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Liên hệ & Đăng ký --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-address-book me-1"></i>Liên hệ & Đăng ký</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field name="contact_title" label="Tiêu đề liên hệ" :value="old('value.contact_title', data_get($item->value, 'contact_title'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="contact_description" label="Mô tả liên hệ" :value="old('value.contact_description', data_get($item->value, 'contact_description'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="register_title" label="Tiêu đề đăng ký" :value="old('value.register_title', data_get($item->value, 'register_title'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="register_description" label="Mô tả đăng ký" :value="old('value.register_description', data_get($item->value, 'register_description'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Bản đồ --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-map me-1"></i>Bản đồ (Embed)</h6>
          <div class="row g-3">
            <div class="col-12">
              <x-textarea-field name="map" label="Mã nhúng bản đồ" :value="old('value.map', data_get($item->value, 'map'))" rows="5" />
            </div>
          </div>
        </div>

        {{-- Cụm: Sidebar --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-layout-sidebar me-1"></i>Sidebar</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <x-input-field name="sidebar_title_one" label="Tiêu đề sidebar 1" :value="old('value.sidebar_title_one', data_get($item->value, 'sidebar_title_one'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="sidebar_title_two" label="Tiêu đề sidebar 2" :value="old('value.sidebar_title_two', data_get($item->value, 'sidebar_title_two'))" />
            </div>
            <div class="col-md-4">
              <x-input-field name="sidebar_title_three" label="Tiêu đề sidebar 3" :value="old('value.sidebar_title_three', data_get($item->value, 'sidebar_title_three'))" />
            </div>
          </div>
        </div>

        {{-- Cụm: Nhãn Sidebar Footer --}}
        <div class="border rounded mb-3 p-3">
          <h6 class="mb-3 fw-bold text-primary small"><i class="ti tabler-tags me-1"></i>Nhãn Sidebar</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <x-input-field name="label_contact" label="Tiêu đề liên hệ (sidebar)" :value="old('label_contact', data_get($item->value, 'label_contact'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="label_email" label="Tiêu đề Email (sidebar)" :value="old('label_email', data_get($item->value, 'label_email'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="placeholder_email" label="Placeholder Email (sidebar)" :value="old('placeholder_email', data_get($item->value, 'placeholder_email'))" />
            </div>
            <div class="col-md-6">
              <x-input-field name="label_follow" label="Tiêu đề Follow (sidebar)" :value="old('label_follow', data_get($item->value, 'label_follow'))" />
            </div>
          </div>
        </div>


      </div>{{-- /p-2 --}}
    </x-slot>


    <x-slot name="logo_and_favicon">
      <div class="row g-3">
        <div class="col-md-12">
          <x-file-input
            label="Logo White (PNG/SVG/JPG)"
            name="logo_file"
            :multiple="false"
            :current-url="$currentLogoUrl ?? null"
          />
        </div>
        <div class="col-md-12">
          <x-file-input
            label="Logo Black (PNG/SVG/JPG)"
            name="logo_black_file"
            :multiple="false"
            :current-url="$currentLogoBlackUrl ?? null"
          />
        </div>
        <div class="col-md-12">
          <x-file-input
            label="Favicon (ICO/PNG)"
            name="favicon_file"
            :multiple="false"
            :current-url="$currentFaviconUrl ?? null"
          />
        </div>
      </div>
    </x-slot>

    @include('setting.home.slide')
    <!-- @include('setting.home.facilities')
    @include('setting.home.statistics')
    @include('setting.home.service')
    @include('setting.home.appoinment')
    @include('setting.home.whyus')
    @include('setting.home.team')
    @include('setting.home.patient')
    @include('setting.home.workflow')
    @include('setting.home.doctor')
    @include('setting.home.faq')
    @include('setting.home.awards')
    @include('setting.home.blog')
    @include('setting.home.contact')
    @include('setting.home.touch')
    @include('setting.home.footer')
    @include('setting.home.copyright') -->

    <x-slot name="banners">
      <div class="row g-3">
          @php
              $banners = [
                  ['key' => 'banner_slide',       'label' => 'Banner Slide (JPG/PNG)',       'url' => $currentBannerSlideUrl],
                  ['key' => 'banner_schedule',    'label' => 'Banner Schedule (JPG/PNG)',    'url' => $currentBannerScheduleUrl],
                  ['key' => 'banner_appointment', 'label' => 'Banner Appointment (JPG/PNG)', 'url' => $currentBannerAppointmentUrl],
                  ['key' => 'banner_why_us',      'label' => 'Banner Why Us (JPG/PNG)',      'url' => $currentBannerWhyUsUrl],
                  ['key' => 'banner_patients',    'label' => 'Banner Patients (JPG/PNG)',    'url' => $currentBannerPatientsUrl],
                  ['key' => 'banner_work',        'label' => 'Banner Work (JPG/PNG)',        'url' => $currentBannerWorkUrl],
                  ['key' => 'banner_doctor',      'label' => 'Banner Doctor (JPG/PNG)',      'url' => $currentBannerDoctorUrl],
                  ['key' => 'banner_faq',         'label' => 'Banner FAQ (JPG/PNG)',         'url' => $currentBannerFaqUrl],
              ];
          @endphp

          @foreach($banners as $b)
              <div class="col-md-12">
                  <x-file-input
                      :label="$b['label']"
                      name="{{ $b['key'] }}_file"
                      :multiple="false"
                      :current-url="$b['url']"
                      accept="image/jpeg,image/png"
                  />
              </div>
          @endforeach
      </div>
    </x-slot>
    <x-submit-buttons
      :cancel-route="null"
      submit-text="Lưu thay đổi"
      cancel-text="Hủy"
    />
  </x-tab-form>
        </div>


       {{-- Tab: Logo & Favicon --}}
<div
  class="tab-pane fade {{ request('tab') === 'logo' ? 'show active' : '' }}"
  id="main-section-logo"
  role="tabpanel"
  aria-labelledby="main-tab-logo">

  <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">
    2. Logo &amp; Favicon
  </h5>

  <form
    action="{{ panel_route('setting.updateLogoFavicon') }}"
    method="POST"
    enctype="multipart/form-data"
    class="ajax-form setting-logo-form">

    @csrf
    @method('PUT')

    <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
    <input type="hidden" name="tab" value="logo">

    {{-- Logo White --}}
    <div class="setting-logo-upload mb-4">
      <label class="form-label fw-bold">Logo White (PNG/SVG/JPG)</label>

      <div class="setting-logo-preview-box">
        @if(!empty($currentLogoUrl))
          <img src="{{ $currentLogoUrl }}" class="setting-logo-preview-img" alt="Logo White">
        @else
          <div class="setting-logo-empty">Chưa có ảnh</div>
        @endif
      </div>

      <div class="setting-logo-file-row">
        <input
          type="file"
          name="logo_file"
          class="form-control"
          accept="image/png,image/jpeg,image/jpg,image/svg+xml">
      </div>
    </div>

    {{-- Logo Black --}}
    <div class="setting-logo-upload mb-4">
      <label class="form-label fw-bold">Logo Black (PNG/SVG/JPG)</label>

      <div class="setting-logo-preview-box">
        @if(!empty($currentLogoBlackUrl))
          <img src="{{ $currentLogoBlackUrl }}" class="setting-logo-preview-img" alt="Logo Black">
        @else
          <div class="setting-logo-empty">Chưa có ảnh</div>
        @endif
      </div>

      <div class="setting-logo-file-row">
        <input
          type="file"
          name="logo_black_file"
          class="form-control"
          accept="image/png,image/jpeg,image/jpg,image/svg+xml">
      </div>
    </div>

    {{-- Favicon --}}
    <div class="setting-logo-upload mb-4">
      <label class="form-label fw-bold">Favicon (ICO/PNG)</label>

      <div class="setting-logo-preview-box setting-favicon-preview-box">
        @if(!empty($currentFaviconUrl))
          <img src="{{ $currentFaviconUrl }}" class="setting-logo-preview-img setting-favicon-preview-img" alt="Favicon">
        @else
          <div class="setting-logo-empty">Chưa có ảnh</div>
        @endif
      </div>

      <div class="setting-logo-file-row">
        <input
          type="file"
          name="favicon_file"
          class="form-control"
          accept="image/x-icon,image/vnd.microsoft.icon,image/png">
      </div>
    </div>

    <div class="text-end mt-4">
      <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
      <button type="reset" class="btn btn-label-secondary">Hủy</button>
    </div>
  </form>
</div>

        <div class="tab-pane fade {{ request('tab') === 'topbar' ? 'show active' : '' }}" id="main-section-topbar" role="tabpanel" aria-labelledby="main-tab-topbar">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">3. Thông Tin Topbar</h5>
            <form action="{{ panel_route('setting.updateTopbar') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="topbar">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Cấu hình Topbar Info</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="open-topbar-modal">
                        + Thêm mục
                    </button>
                </div>

                <div class="table-responsive border rounded mb-3">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th width="70">HÌNH</th>
                                <th>TIÊU ĐỀ</th>
                                <th>PHỤ ĐỀ</th>
                                <th>LINK</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="topbar-items-container">
                            @php
                                $topbarItemsList = $topbarV['items'] ?? [];
                            @endphp
                            @foreach($topbarItemsList as $index => $ti)
                            <tr class="item-row">
                                <td class="text-center align-middle"><span class="item-index">{{ $index + 1 }}</span></td>
                                <td class="align-middle">
                                    @if(!empty($ti['image']))
                                        <img src="{{ asset('storage/' . $ti['image']) }}" class="cell-img-thumb rounded" style="height:40px;width:40px;object-fit:cover;" alt="">
                                    @else
                                        <span class="cell-img-thumb-placeholder text-muted small"><i class="ti tabler-photo"></i></span>
                                    @endif
                                    <input type="hidden" name="items[{{ $index }}][image]" class="hidden-image" value="{{ $ti['image'] ?? '' }}">
                                    <input type="file" name="items[{{ $index }}][image_file]" class="d-none topbar-file-input" accept="image/*">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-title">{{ $ti['title'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][title]" class="hidden-title" value="{{ $ti['title'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-subtitle">{{ $ti['description'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][description]" class="hidden-subtitle" value="{{ $ti['description'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-link">{{ $ti['link'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][link]" class="hidden-link" value="{{ $ti['link'] ?? '' }}">
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-primary edit-topbar-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-remove-item" title="Xóa"><i class="ti tabler-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade {{ request('tab') === 'floating' ? 'show active' : '' }}" id="main-section-floating" role="tabpanel" aria-labelledby="main-tab-floating">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">4. Mạng Xã Hội</h5>
            <form action="{{ panel_route('setting.updateFloating') }}" method="POST" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="floating">

                <div class="mb-3">
                    <h6 class="mb-3">Cấu hình Tiện ích nổi bật (Dọc)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Dòng chữ thẳng</label>
                            <input type="text" name="emergency_text" class="form-control" value="{{ $floatingV['emergency_text'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link dòng chữ thẳng</label>
                            <input type="text" name="emergency_link" class="form-control" value="{{ $floatingV['emergency_link'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ $floatingV['contact_phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link gọi điện</label>
                            <input type="text" name="contact_link" class="form-control" value="{{ $floatingV['contact_link'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Mạng Xã Hội</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="open-social-modal">
                        + Thêm mục
                    </button>
                </div>

                <div class="table-responsive border rounded mb-3">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>TÊN</th>
                                <th>LINK</th>
                                <th>ICON</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="social-items-container">
                            @php
                                $socialItemsList = $floatingV['socials'] ?? [];
                            @endphp
                            @foreach($socialItemsList as $index => $soc)
                            <tr class="social-item-row">
                                <td class="text-center align-middle"><span class="social-index">{{ $index + 1 }}</span></td>
                                <td class="align-middle">
                                    <span class="cell-name">{{ $soc['name'] ?? '' }}</span>
                                    <input type="hidden" name="socials[{{ $index }}][name]" class="hidden-name" value="{{ $soc['name'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-link">{{ $soc['link'] ?? '' }}</span>
                                    <input type="hidden" name="socials[{{ $index }}][link]" class="hidden-link" value="{{ $soc['link'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-icon">{{ $soc['icon'] ?? '' }}</span>
                                    <input type="hidden" name="socials[{{ $index }}][icon]" class="hidden-icon" value="{{ $soc['icon'] ?? '' }}">
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-primary edit-social-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-remove-social" title="Xóa"><i class="ti tabler-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade {{ request('tab') === 'services' ? 'show active' : '' }}" id="main-section-services" role="tabpanel" aria-labelledby="main-tab-services">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">5. Dịch Vụ</h5>
            <form action="{{ panel_route(module().'.updateServices') }}" method="POST" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="services">

                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiêu đề chính</label>
                            <input type="text" name="title" class="form-control" value="{{ $servicesV['title'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tiêu đề phụ</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ $servicesV['subtitle'] ?? '' }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Link Xem tất cả</label>
                            <input type="text" name="view_all_link" class="form-control" value="{{ $servicesV['view_all_link'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-between align-items-center mt-4">
                    <h6 class="mb-0">Danh sách dịch vụ</h6>
                    <button type="button" class="btn btn-sm btn-primary" id="add-service-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Thêm mục
                    </button>
                </div>

                <div class="table-responsive border rounded">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>TÊN DỊCH VỤ</th>
                                <th>MÔ TẢ</th>
                                <th>TEXT PHỤ</th>
                                <th>LINK</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="services-items-container">
                            @php $serviceList = $servicesV['items'] ?? []; @endphp
                            @foreach($serviceList as $index => $sv)
                            <tr class="service-item-row">
                                <td class="text-center align-middle"><span class="service-index">{{ $index + 1 }}</span></td>
                                <td class="align-middle">
                                    <span class="cell-title">{{ $sv['title'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][title]" class="hidden-title" value="{{ $sv['title'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-description">{{ Str::limit($sv['description'] ?? '', 40) }}</span>
                                    <input type="hidden" name="items[{{ $index }}][description]" class="hidden-description" value="{{ $sv['description'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-doctor-text">{{ $sv['doctor_text'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][doctor_text]" class="hidden-doctor-text" value="{{ $sv['doctor_text'] ?? '' }}">
                                </td>
                                <td class="align-middle">
                                    <span class="cell-link">{{ $sv['link'] ?? '' }}</span>
                                    <input type="hidden" name="items[{{ $index }}][link]" class="hidden-link" value="{{ $sv['link'] ?? '' }}">
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-primary edit-service-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                        <button type="button" class="btn btn-xs btn-outline-danger btn-remove-service" title="Xóa"><i class="ti tabler-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade {{ request('tab') === 'why_choose_us' ? 'show active' : '' }}" id="main-section-why_choose_us" role="tabpanel" aria-labelledby="main-tab-why_choose_us">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">6. Tại Sao Chọn Chúng Tôi</h5>
            <form action="{{ panel_route('setting.updateWhyChooseUs') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="why_choose_us">

                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card shadow-none border bg-transparent">
                            <div class="card-header border-bottom p-0">
                                <ul class="nav nav-tabs card-header-tabs m-0 border-0" id="whyTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active py-2" id="why-image-tab" data-bs-toggle="tab" data-bs-target="#why-image" type="button" role="tab">Ảnh & Kinh nghiệm</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link py-2" id="why-features-tab" data-bs-toggle="tab" data-bs-target="#why-features" type="button" role="tab">Tiêu đề & Đặc điểm</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content p-0 shadow-none border-0">
                                    <div class="tab-pane fade show active" id="why-image" role="tabpanel">
                                        <div class="row g-4 align-items-center">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                                <input type="file" name="image_file" id="whyus-image-input" class="form-control" accept="image/*">
                                                <div id="whyus-image-preview-container" class="mt-3 border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                                                    @if(!empty($whyChooseUsV['currentImageUrl']))
                                                        <img src="{{ $whyChooseUsV['currentImageUrl'] }}" id="whyus-image-preview" class="h-100 object-fit-contain rounded">
                                                    @else
                                                        <div id="whyus-image-placeholder" class="text-muted small">Chưa có ảnh</div>
                                                        <img src="" id="whyus-image-preview" class="h-100 object-fit-contain rounded d-none">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Con số kinh nghiệm (vd: 20+)</label>
                                                    <input type="text" name="experience_number" class="form-control" value="{{ $whyChooseUsV['experience_number'] ?? '' }}">
                                                </div>
                                                <div>
                                                    <label class="form-label fw-bold">Nhãn kinh nghiệm</label>
                                                    <input type="text" name="experience_label" class="form-control" value="{{ $whyChooseUsV['experience_label'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="why-features" role="tabpanel">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tiêu đề chính Section</label>
                                            <input type="text" name="title" class="form-control" value="{{ $whyChooseUsV['title'] ?? '' }}">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                                            <h6 class="mb-0 small">Danh sách đặc điểm</h6>
                                            <button type="button" class="btn btn-sm btn-primary" id="add-feature-item">Thêm đặc điểm</button>
                                        </div>
                                        <div class="table-responsive border rounded">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="50" class="text-center">#</th>
                                                        <th>TIÊU ĐỀ</th>
                                                        <th>MÔ TẢ</th>
                                                        <th width="80" class="text-center">THAO TÁC</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="features-items-container">
                                                    @foreach($whyChooseUsV['items'] ?? [] as $idx => $f)
                                                    <tr class="feature-item-row">
                                                        <td class="text-center align-middle"><span class="feature-index">{{ $idx + 1 }}</span></td>
                                                        <td class="align-middle">
                                                            <span class="cell-title">{{ $f['title'] ?? '' }}</span>
                                                            <input type="hidden" name="items[{{ $idx }}][title]" class="hidden-title" value="{{ $f['title'] ?? '' }}">
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="cell-description">{{ Str::limit($f['description'] ?? '', 40) }}</span>
                                                            <input type="hidden" name="items[{{ $idx }}][description]" class="hidden-description" value="{{ $f['description'] ?? '' }}">
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                                <button type="button" class="btn btn-xs btn-outline-primary edit-feature-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                                                <button type="button" class="btn btn-xs btn-outline-danger btn-remove-feature" title="Xóa"><i class="ti tabler-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade {{ request('tab') === 'testimonials' ? 'show active' : '' }}" id="main-section-testimonials" role="tabpanel" aria-labelledby="main-tab-testimonials">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">8. Ý Kiến Khách Hàng</h5>
            <form action="{{ panel_route('setting.updateTestimonials') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="testimonials">

                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card shadow-none border bg-transparent">
                            <div class="card-header border-bottom p-0">
                                <ul class="nav nav-tabs card-header-tabs m-0 border-0" id="testimonialsTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active py-2" id="t-title-tab" data-bs-toggle="tab" data-bs-target="#t-title" type="button" role="tab">Tiêu đề</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-2" id="t-main-tab" data-bs-toggle="tab" data-bs-target="#t-main" type="button" role="tab">Ảnh & Floating</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-2" id="t-achieve-tab" data-bs-toggle="tab" data-bs-target="#t-achieve" type="button" role="tab">Thành tựu</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-2" id="t-list-tab" data-bs-toggle="tab" data-bs-target="#t-list" type="button" role="tab">Danh sách</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content p-0 shadow-none border-0">
                                    <div class="tab-pane fade show active" id="t-title" role="tabpanel">
                                        <label class="form-label fw-bold small">Tiêu đề chính Section</label>
                                        <input type="text" name="main_title" class="form-control" value="{{ $testimonialsV['main_title'] ?? '' }}">
                                    </div>
                                    <div class="tab-pane fade" id="t-main" role="tabpanel">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Ảnh nền lớn</label>
                                                <input type="file" name="main_image_file" class="form-control form-control-sm img-input-preview" data-preview="#preview-tmain">
                                                <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 150px; overflow: hidden;">
                                                    <img src="{{ !empty($testimonialsV['main_image']) ? asset($testimonialsV['main_image']) : '' }}" id="preview-tmain" class="h-100 object-fit-contain {{ empty($testimonialsV['main_image']) ? 'd-none' : '' }}">
                                                    @if(empty($testimonialsV['main_image'])) <span class="text-muted small">Chưa có ảnh</span> @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-2 border rounded">
                                                    <h6 class="small fw-bold text-primary mb-2">Floating Card</h6>
                                                    <div class="mb-2">
                                                        <label class="form-label x-small mb-0">Avatar</label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ !empty($testimonialsV['floating_review']['avatar']) ? asset($testimonialsV['floating_review']['avatar']) : '' }}" id="preview-tfloat" class="rounded-circle border {{ empty($testimonialsV['floating_review']['avatar']) ? 'd-none' : '' }}" width="40" height="40" style="object-fit: cover;">
                                                            <input type="file" name="floating_review_avatar" class="form-control form-control-sm img-input-preview" data-preview="#preview-tfloat">
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="text" name="floating_review[name]" class="form-control form-control-sm" value="{{ $testimonialsV['floating_review']['name'] ?? '' }}" placeholder="Tên">
                                                    </div>
                                                    <div class="mb-2">
                                                        <input type="number" name="floating_review[rating]" class="form-control form-control-sm" value="{{ $testimonialsV['floating_review']['rating'] ?? 5 }}" min="1" max="5" placeholder="Rating">
                                                    </div>
                                                    <textarea name="floating_review[text]" class="form-control form-control-sm" rows="2" placeholder="Nội dung...">{{ $testimonialsV['floating_review']['text'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="t-achieve" role="tabpanel">
                                        <div class="row g-2">
                                            <div class="col-6"><input type="text" name="achievement[number]" class="form-control form-control-sm" value="{{ $testimonialsV['achievement']['number'] ?? '' }}" placeholder="Số"></div>
                                            <div class="col-6"><input type="text" name="achievement[text]" class="form-control form-control-sm" value="{{ $testimonialsV['achievement']['text'] ?? '' }}" placeholder="Text"></div>
                                            <div class="col-12 mt-2">
                                                <label class="form-label x-small fw-bold mb-1">4 Avatars</label>
                                                <div class="row g-1">
                                                    @for($i=0;$i<4;$i++)
                                                    <div class="col-3 text-center">
                                                        <input type="file" name="achievement_avatars[{{ $i }}]" class="form-control form-control-sm img-input-preview" data-preview="#preview-tach-{{ $i }}">
                                                        <img src="{{ !empty($testimonialsV['achievement']['avatars'][$i]) ? asset($testimonialsV['achievement']['avatars'][$i]) : '' }}" id="preview-tach-{{ $i }}" class="mt-1 rounded-circle border {{ empty($testimonialsV['achievement']['avatars'][$i]) ? 'd-none' : '' }}" width="30" height="30">
                                                    </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="t-list" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 small">Danh sách phản hồi</h6>
                                            <button type="button" class="btn btn-sm btn-primary" id="add-testimonial-item">Thêm phản hồi</button>
                                        </div>
                                        <div class="table-responsive border rounded">
                                            <table class="table table-hover align-middle mb-0 table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="30" class="text-center">#</th>
                                                        <th width="40">ẢNH</th>
                                                        <th>TÊN/CHỨC DANH</th>
                                                        <th>NỘI DUNG</th>
                                                        <th width="70" class="text-center">THAO TÁC</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="testimonials-items-container">
                                                    @foreach($testimonialsV['items'] ?? [] as $idx => $t)
                                                    <tr class="testimonial-item-row">
                                                        <td class="text-center align-middle small"><span class="testimonial-index">{{ $idx + 1 }}</span></td>
                                                        <td class="align-middle">
                                                            <div class="testimonial-avatar-wrap">
                                                                @if(!empty($t['image']))
                                                                    <img src="{{ asset($t['image']) }}" class="rounded-circle object-fit-cover testimonial-thumb" width="30" height="30">
                                                                @else
                                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:30px;height:30px;font-size:10px">NA</div>
                                                                @endif
                                                                <input type="file" name="items[{{ $idx }}][image_file]" class="d-none testimonial-file-input">
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="small fw-bold cell-name">{{ $t['name'] ?? '' }}</div>
                                                            <div class="x-small text-muted cell-role">{{ $t['role'] ?? '' }}</div>
                                                            <input type="hidden" name="items[{{ $idx }}][name]" class="hidden-name" value="{{ $t['name'] ?? '' }}">
                                                            <input type="hidden" name="items[{{ $idx }}][role]" class="hidden-role" value="{{ $t['role'] ?? '' }}">
                                                            <input type="hidden" name="items[{{ $idx }}][title]" class="hidden-title" value="{{ $t['title'] ?? '' }}">
                                                            <input type="hidden" name="items[{{ $idx }}][video_link]" class="hidden-video" value="{{ $t['video_link'] ?? '' }}">
                                                        </td>
                                                        <td class="align-middle small">
                                                            <span class="cell-review">{{ Str::limit($t['review'] ?? '', 40) }}</span>
                                                            <input type="hidden" name="items[{{ $idx }}][review]" class="hidden-review" value="{{ $t['review'] ?? '' }}">
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                                <button type="button" class="btn btn-xs btn-outline-primary edit-testimonial-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                                                <button type="button" class="btn btn-xs btn-outline-danger btn-remove-testimonial" title="Xóa"><i class="ti tabler-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade {{ request('tab') === 'faq' ? 'show active' : '' }}" id="main-section-faq" role="tabpanel" aria-labelledby="main-tab-faq">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">12. Hỏi Đáp</h5>
            <form action="{{ panel_route('setting.updateFaq') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $settingType ?? 'clinic' }}">
                <input type="hidden" name="tab" value="faq">

                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card shadow-none border bg-transparent">
                            <div class="card-header border-bottom p-0">
                                <ul class="nav nav-tabs card-header-tabs m-0 border-0" id="faqTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active py-2" id="f-main-tab" data-bs-toggle="tab" data-bs-target="#f-main" type="button" role="tab">Nội dung</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-2" id="f-contact-tab" data-bs-toggle="tab" data-bs-target="#f-contact" type="button" role="tab">Liên hệ</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link py-2" id="f-list-tab" data-bs-toggle="tab" data-bs-target="#f-list" type="button" role="tab">Danh sách câu hỏi</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-3">
                                <div class="tab-content p-0 shadow-none border-0">
                                    <div class="tab-pane fade show active" id="f-main" role="tabpanel">
                                        <label class="form-label fw-bold small">Tiêu đề chính Section</label>
                                        <input type="text" name="title" class="form-control mb-2" value="{{ $faqV['title'] ?? '' }}">
                                        <label class="form-label fw-bold small">Mô tả</label>
                                        <textarea name="description" class="form-control mb-2" rows="2">{{ $faqV['description'] ?? '' }}</textarea>
                                        <label class="form-label fw-bold small">Ảnh minh họa</label>
                                        <input type="file" name="image_file" class="form-control form-control-sm img-input-preview" data-preview="#preview-faq">
                                        <div class="mt-2 border rounded bg-light d-flex align-items-center justify-content-center" style="height: 120px; overflow: hidden;">
                                            <img src="{{ !empty($faqV['image']) ? asset($faqV['image']) : '' }}" id="preview-faq" class="h-100 object-fit-contain {{ empty($faqV['image']) ? 'd-none' : '' }}">
                                            @if(empty($faqV['image'])) <span class="text-muted small">Chưa có ảnh</span> @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="f-contact" role="tabpanel">
                                        <div class="row g-2">
                                            <div class="col-6"><label class="form-label small mb-0">Text liên hệ</label><input type="text" name="contact[text]" class="form-control form-control-sm" value="{{ $faqV['contact']['text'] ?? '' }}"></div>
                                            <div class="col-6"><label class="form-label small mb-0">Số điện thoại</label><input type="text" name="contact[phone]" class="form-control form-control-sm" value="{{ $faqV['contact']['phone'] ?? '' }}"></div>
                                            <div class="col-6"><label class="form-label small mb-0">Text nút Hẹn</label><input type="text" name="appointment_btn_text" class="form-control form-control-sm" value="{{ $faqV['appointment_btn']['text'] ?? '' }}"></div>
                                            <div class="col-6"><label class="form-label small mb-0">Link nút</label><input type="text" name="appointment_btn_link" class="form-control form-control-sm" value="{{ $faqV['appointment_btn']['link'] ?? '' }}"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="f-list" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 small">Danh sách FAQ</h6>
                                            <button type="button" class="btn btn-sm btn-primary" id="add-faq-item">Thêm FAQ</button>
                                        </div>
                                        <div class="table-responsive border rounded">
                                            <table class="table table-hover align-middle mb-0 table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="30" class="text-center">#</th>
                                                        <th>CÂU HỎI</th>
                                                        <th>CÂU TRẢ LỜI</th>
                                                        <th width="70" class="text-center">THAO TÁC</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="faq-items-container">
                                                    @foreach($faqV['items'] ?? [] as $idx => $f)
                                                    <tr class="faq-item-row">
                                                        <td class="text-center align-middle small"><span class="faq-index">{{ $idx + 1 }}</span></td>
                                                        <td class="align-middle small">
                                                            <span class="cell-question">{{ $f['question'] ?? '' }}</span>
                                                            <input type="hidden" name="items[{{ $idx }}][question]" class="hidden-question" value="{{ $f['question'] ?? '' }}">
                                                        </td>
                                                        <td class="align-middle small">
                                                            <span class="cell-answer">{{ Str::limit($f['answer'] ?? '', 50) }}</span>
                                                            <input type="hidden" name="items[{{ $idx }}][answer]" class="hidden-answer" value="{{ $f['answer'] ?? '' }}">
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                                <button type="button" class="btn btn-xs btn-outline-primary edit-faq-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                                                                <button type="button" class="btn btn-xs btn-outline-danger btn-remove-faq" title="Xóa"><i class="ti tabler-trash"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
                        <button type="reset" class="btn btn-label-secondary">Hủy</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Ti\u1ec7n \u00edch --}}
        <div class="tab-pane fade {{ request('tab') === 'utilities' ? 'show active' : '' }}" id="main-section-utilities" role="tabpanel" aria-labelledby="main-tab-utilities">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">7. Tiện Ích</h5>
            @php
                $utilitiesTabData = $utilitiesTabData ?? [];
            @endphp
            @includeIf('setting.home.sections.utilities', $utilitiesTabData)
        </div>

        {{-- Tab: B\u00e1c s\u0129 --}}
        <div class="tab-pane fade {{ request('tab') === 'doctor' ? 'show active' : '' }}" id="main-section-doctor" role="tabpanel" aria-labelledby="main-tab-doctor">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">9. Bác Sĩ</h5>
            @php
                $doctorTabData = $doctorTabData ?? [];
            @endphp
            @includeIf('setting.home.sections.doctor', $doctorTabData)
        </div>

        {{-- Tab: \u0110\u1ed9i ng\u0169 b\u00e1c s\u0129 --}}
        <div class="tab-pane fade {{ request('tab') === 'specialists' ? 'show active' : '' }}" id="main-section-specialists" role="tabpanel" aria-labelledby="main-tab-specialists">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">10. Đội Ngũ Bác Sĩ</h5>
            @php
                $specialistsTabData = $specialistsTabData ?? [];
            @endphp
            @includeIf('setting.home.sections.specialists', $specialistsTabData)
        </div>

        {{-- Tab: Form đặt lịch hẹn --}}
        <div class="tab-pane fade {{ request('tab') === 'appointment' ? 'show active' : '' }}" id="main-section-appointment" role="tabpanel" aria-labelledby="main-tab-appointment">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">11. Form Đặt Lịch Hẹn</h5>
            @php
                $appointmentTabData = array_merge($appointmentTabData ?? [], ['settingType' => $settingType ?? 'clinic']);
            @endphp
            @includeIf('setting.home.sections.appointment', $appointmentTabData)
        </div>
      </div>

      <div id="home-json-wrapper" class="border rounded mt-3 p-2 bg-dark-subtle" data-section="{{ request('tab', 'config') }}" data-type="{{ $settingType ?? 'clinic' }}" style="display: none;">
        <h6 class="mb-1 small text-muted">JSON response</h6>
        <pre id="home-json-preview" class="bg-black text-white small rounded p-2 mb-0" style="max-height: 260px; overflow: auto; white-space: pre-wrap; word-break: break-all;"></pre>
      </div>

    </div>
  </div>
</div>

<!-- Modal thêm/sửa Topbar -->
<div class="modal fade" id="topbarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="topbarModalTitle">Thêm mục Topbar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Tiêu đề</label><input type="text" class="form-control" id="modal-topbar-title" placeholder="Contact Us"></div>
        <div class="mb-3"><label class="form-label fw-bold">Phụ đề</label><input type="text" class="form-control" id="modal-topbar-subtitle" placeholder="+1 123 456 7890"></div>
        <div class="mb-3"><label class="form-label fw-bold">Link (Tùy chọn)</label><input type="text" class="form-control" id="modal-topbar-link" placeholder="/"></div>
        <div class="mb-0">
          <label class="form-label fw-bold">Hình ảnh (Tùy chọn)</label>
          <input type="file" class="form-control" id="modal-topbar-image" accept="image/*">
          <div class="mt-2">
            <img src="" id="modal-topbar-image-preview" class="rounded d-none" style="height:60px;object-fit:contain;" alt="">
          </div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-topbar-modal">Lưu</button></div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa Social -->
<div class="modal fade" id="socialModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="socialModalTitle">Thêm Mạng Xã Hội</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Tên mạng xã hội</label><input type="text" class="form-control" id="modal-social-name" placeholder="Facebook"></div>
        <div class="mb-3"><label class="form-label fw-bold">Link / Tên hiển thị</label><input type="text" class="form-control" id="modal-social-link" placeholder="https://fb.com/..."></div>
        <div class="mb-0"><label class="form-label fw-bold">Icon (tùy chọn)</label><input type="text" class="form-control" id="modal-social-icon" placeholder="ti tabler-brand-facebook"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-social-modal">Lưu</button></div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa Service -->
<div class="modal fade" id="serviceModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalTitle">Thêm Dịch Vụ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Tên dịch vụ</label><input type="text" class="form-control" id="modal-service-title"></div>
        <div class="mb-3"><label class="form-label fw-bold">Mô tả</label><textarea class="form-control" id="modal-service-description" rows="3"></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-bold">Text phụ</label><input type="text" class="form-control" id="modal-service-doctor-text"></div>
          <div class="col-md-6"><label class="form-label fw-bold">Link</label><input type="text" class="form-control" id="modal-service-link"></div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-service-modal">Lưu</button></div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa Feature (Why Choose Us) -->
<div class="modal fade" id="featureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="featureModalTitle">Thêm Đặc Điểm</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold">Tiêu đề</label><input type="text" class="form-control" id="modal-feature-title"></div>
        <div class="mb-0"><label class="form-label fw-bold">Mô tả</label><textarea class="form-control" id="modal-feature-description" rows="3"></textarea></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-feature-modal">Lưu</button></div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa Testimonial -->
<div class="modal fade" id="testimonialModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testimonialModalTitle">Thêm Phản Hồi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label fw-bold small">Ảnh đại diện</label>
            <div class="d-flex align-items-center gap-3">
                <img src="" id="modal-t-preview" class="rounded-circle border d-none" width="60" height="60" style="object-fit:cover">
                <div id="modal-t-no-photo" class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width:60px;height:60px">NA</div>
                <input type="file" class="form-control form-control-sm" id="modal-t-image">
            </div>
          </div>
          <div class="col-md-6"><label class="form-label small fw-bold">Tên</label><input type="text" class="form-control form-control-sm" id="modal-t-name"></div>
          <div class="col-md-6"><label class="form-label small fw-bold">Chức danh</label><input type="text" class="form-control form-control-sm" id="modal-t-role"></div>
          <div class="col-md-6"><label class="form-label small fw-bold">Tiêu đề (nếu có)</label><input type="text" class="form-control form-control-sm" id="modal-t-title"></div>
          <div class="col-md-6"><label class="form-label small fw-bold">Link video (nếu có)</label><input type="text" class="form-control form-control-sm" id="modal-t-video"></div>
          <div class="col-md-12"><label class="form-label small fw-bold">Nội dung</label><textarea class="form-control form-control-sm" id="modal-t-review" rows="3"></textarea></div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-testimonial-modal">Lưu</button></div>
    </div>
  </div>
</div>

<!-- Modal thêm/sửa FAQ -->
<div class="modal fade" id="faqModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="faqModalTitle">Thêm FAQ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold small">Câu hỏi</label><input type="text" class="form-control form-control-sm" id="modal-f-question"></div>
        <div><label class="form-label fw-bold small">Câu trả lời</label><textarea class="form-control form-control-sm" id="modal-f-answer" rows="4"></textarea></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Huỷ</button><button type="button" class="btn btn-primary btn-sm" id="save-faq-modal">Lưu</button></div>
    </div>
  </div>
</div>
  </div>
</main>
@push('scripts')
<style>
  #v-pills-tab.nav-pills,
  #v-pills-tab.nav-pills .nav-link {
    text-align: left !important;
  }
  #v-pills-tab.nav-pills .nav-link {
    display: block !important;
    justify-content: flex-start !important;
    font-size: 0.9rem;
  }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tham chiếu các thành phần JSON Viewer
        const pre = document.getElementById('home-json-preview');
        const btn = document.getElementById('btn-clear-json');
        const wrapper = document.getElementById('home-json-wrapper');
        const type = wrapper ? (wrapper.getAttribute('data-type') || 'clinic') : 'clinic';

        function loadJsonData(sec) {
            if (!pre || !wrapper) return;
            const key = sec ? `home_json_${type}_${sec}` : `home_json_${type}_preview`;
            try {
                const savedJson = window.localStorage ? window.localStorage.getItem(key) : null;
                if (savedJson) {
                    pre.textContent = savedJson;
                    wrapper.style.display = 'block';
                } else {
                    pre.textContent = '';
                    wrapper.style.display = 'none';
                }
                wrapper.setAttribute('data-section', sec || '');
            } catch (e) {
                console.warn('Không thể load JSON cho section', sec, e);
            }
        }

        // Khởi tạo ban đầu
        const initialTab = new URL(window.location.href).searchParams.get('tab') || 'config';
        loadJsonData(initialTab);

        if (btn && pre) {
            btn.addEventListener('click', function () {
                pre.textContent = '';
                try {
                    const sec = wrapper.getAttribute('data-section') || 'config';
                    const key = `home_json_${type}_${sec}`;
                    window.localStorage && window.localStorage.removeItem(key);
                } catch (e) {}
                if (wrapper) wrapper.style.display = 'none';
            });
        }

        // Tab click
        const tabLinks = document.querySelectorAll('#v-pills-tab .nav-link');
        tabLinks.forEach(link => {
            link.addEventListener('click', function() {
                const url = new URL(window.location.href);
                const targetPaneId = this.getAttribute('href').replace('#main-section-', '');
                url.searchParams.set('tab', targetPaneId);
                window.history.replaceState({}, '', url);

                document.querySelectorAll('.setting-type-switch').forEach(typeLink => {
                    const linkUrl = new URL(typeLink.href);
                    linkUrl.searchParams.set('tab', targetPaneId);
                    typeLink.href = linkUrl.toString();
                });

                // Tải JSON content ứng với tab
                loadJsonData(targetPaneId);
            });
        });

        var topbarModalEl = document.getElementById('topbarModal');
        var topbarModal = topbarModalEl ? new bootstrap.Modal(topbarModalEl) : null;
        var editingTopbarRow = null;
        var topbarImageDataUrl = null;
        function esc(s) { return (s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

        // Image preview in modal
        document.getElementById('modal-topbar-image')?.addEventListener('change', function() {
            topbarImageDataUrl = null;
            var preview = document.getElementById('modal-topbar-image-preview');
            if (this.files && this.files[0]) {
                var r = new FileReader();
                r.onload = function(e) {
                    topbarImageDataUrl = e.target.result;
                    preview.src = topbarImageDataUrl;
                    preview.classList.remove('d-none');
                };
                r.readAsDataURL(this.files[0]);
            } else {
                preview.classList.add('d-none');
                preview.src = '';
            }
        });

        const tbContainer = document.getElementById('topbar-items-container');
        if (tbContainer) {
            document.getElementById('open-topbar-modal').addEventListener('click', function() {
                editingTopbarRow = null;
                topbarImageDataUrl = null;
                document.getElementById('topbarModalTitle').innerText = 'Thêm mục Topbar';
                document.getElementById('modal-topbar-title').value = '';
                document.getElementById('modal-topbar-subtitle').value = '';
                document.getElementById('modal-topbar-link').value = '';
                document.getElementById('modal-topbar-image').value = '';
                var prev = document.getElementById('modal-topbar-image-preview');
                prev.src = ''; prev.classList.add('d-none');
                if (topbarModal) topbarModal.show();
            });

            tbContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-topbar-item');
                if (editBtn) {
                    editingTopbarRow = editBtn.closest('.item-row');
                    topbarImageDataUrl = null;
                    document.getElementById('topbarModalTitle').innerText = 'Sửa mục Topbar';
                    document.getElementById('modal-topbar-title').value = editingTopbarRow.querySelector('.hidden-title').value;
                    document.getElementById('modal-topbar-subtitle').value = editingTopbarRow.querySelector('.hidden-subtitle').value;
                    document.getElementById('modal-topbar-link').value = editingTopbarRow.querySelector('.hidden-link').value;
                    document.getElementById('modal-topbar-image').value = '';
                    var prev = document.getElementById('modal-topbar-image-preview');
                    var existImg = editingTopbarRow.querySelector('img.cell-img-thumb');
                    if (existImg) { prev.src = existImg.src; prev.classList.remove('d-none'); }
                    else { prev.src = ''; prev.classList.add('d-none'); }
                    if (topbarModal) topbarModal.show();
                    return;
                }

                if (e.target.closest('.btn-remove-item')) {
                    e.target.closest('.item-row').remove();
                    tbContainer.querySelectorAll('.item-row').forEach((row, i) => {
                        row.querySelector('.item-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => {
                            input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                        });
                    });
                }
            });

            document.getElementById('save-topbar-modal').addEventListener('click', function() {
                var title = document.getElementById('modal-topbar-title').value;
                var subtitle = document.getElementById('modal-topbar-subtitle').value;
                var link = document.getElementById('modal-topbar-link').value;
                var modalImgInput = document.getElementById('modal-topbar-image');

                if (editingTopbarRow) {
                    editingTopbarRow.querySelector('.cell-title').innerText = title;
                    editingTopbarRow.querySelector('.hidden-title').value = title;
                    editingTopbarRow.querySelector('.cell-subtitle').innerText = subtitle;
                    editingTopbarRow.querySelector('.hidden-subtitle').value = subtitle;
                    editingTopbarRow.querySelector('.cell-link').innerText = link;
                    editingTopbarRow.querySelector('.hidden-link').value = link;
                    if (topbarImageDataUrl) {
                        var imgEl = editingTopbarRow.querySelector('img.cell-img-thumb');
                        var placeEl = editingTopbarRow.querySelector('.cell-img-thumb-placeholder');
                        if (!imgEl) {
                            var td = editingTopbarRow.querySelector('.hidden-image').closest('td');
                            var newImg = document.createElement('img');
                            newImg.className = 'cell-img-thumb rounded';
                            newImg.style.cssText = 'height:40px;width:40px;object-fit:cover;';
                            td.insertBefore(newImg, td.firstChild);
                            imgEl = newImg;
                        }
                        imgEl.src = topbarImageDataUrl;
                        if (placeEl) placeEl.classList.add('d-none');
                        // Transfer file to row's file input
                        var rowFileInput = editingTopbarRow.querySelector('.topbar-file-input');
                        if (rowFileInput && modalImgInput.files.length) {
                            var dt = new DataTransfer(); dt.items.add(modalImgInput.files[0]); rowFileInput.files = dt.files;
                        }
                    }
                    editingTopbarRow = null;
                    if (topbarModal) topbarModal.hide();
                    return;
                }

                const count = tbContainer.querySelectorAll('.item-row').length;
                var imgHtml = topbarImageDataUrl
                    ? `<img src="${topbarImageDataUrl}" class="cell-img-thumb rounded" style="height:40px;width:40px;object-fit:cover;" alt="">`
                    : `<span class="cell-img-thumb-placeholder text-muted small"><i class="ti tabler-photo"></i></span>`;
                const newRow = `
<tr class="item-row">
    <td class="text-center align-middle"><span class="item-index">${count + 1}</span></td>
    <td class="align-middle">
        ${imgHtml}
        <input type="hidden" name="items[${count}][image]" class="hidden-image" value="">
        <input type="file" name="items[${count}][image_file]" class="d-none topbar-file-input" accept="image/*">
    </td>
    <td class="align-middle">
        <span class="cell-title">${esc(title)}</span>
        <input type="hidden" name="items[${count}][title]" class="hidden-title" value="${esc(title)}">
    </td>
    <td class="align-middle">
        <span class="cell-subtitle">${esc(subtitle)}</span>
        <input type="hidden" name="items[${count}][description]" class="hidden-subtitle" value="${esc(subtitle)}">
    </td>
    <td class="align-middle">
        <span class="cell-link">${esc(link)}</span>
        <input type="hidden" name="items[${count}][link]" class="hidden-link" value="${esc(link)}">
    </td>
    <td class="text-center align-middle">
        <div class="d-flex align-items-center justify-content-center gap-1">
            <button type="button" class="btn btn-xs btn-outline-primary edit-topbar-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-item" title="Xóa"><i class="ti tabler-trash"></i></button>
        </div>
    </td>
</tr>`;
                tbContainer.insertAdjacentHTML('beforeend', newRow);
                // Transfer file to new row
                if (modalImgInput.files.length) {
                    var newRowEl = tbContainer.lastElementChild;
                    var rowFileInput = newRowEl.querySelector('.topbar-file-input');
                    if (rowFileInput) { var dt = new DataTransfer(); dt.items.add(modalImgInput.files[0]); rowFileInput.files = dt.files; }
                }
                if (topbarModal) topbarModal.hide();
            });
        }

        var socialModalEl = document.getElementById('socialModal');
        var socialModal = socialModalEl ? new bootstrap.Modal(socialModalEl) : null;
        var editingSocialRow = null;

        const socContainer = document.getElementById('social-items-container');
        if (socContainer) {
            document.getElementById('open-social-modal').addEventListener('click', function() {
                editingSocialRow = null;
                document.getElementById('socialModalTitle').innerText = 'Thêm Mạng Xã Hội';
                document.getElementById('modal-social-name').value = '';
                document.getElementById('modal-social-link').value = '';
                document.getElementById('modal-social-icon').value = '';
                if (socialModal) socialModal.show();
            });

            socContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-social-item');
                if (editBtn) {
                    editingSocialRow = editBtn.closest('.social-item-row');
                    document.getElementById('socialModalTitle').innerText = 'Sửa Mạng Xã Hội';
                    document.getElementById('modal-social-name').value = editingSocialRow.querySelector('.hidden-name').value;
                    document.getElementById('modal-social-link').value = editingSocialRow.querySelector('.hidden-link').value;
                    document.getElementById('modal-social-icon').value = editingSocialRow.querySelector('.hidden-icon').value;
                    if (socialModal) socialModal.show();
                    return;
                }

                if (e.target.closest('.btn-remove-social')) {
                    e.target.closest('.social-item-row').remove();
                    socContainer.querySelectorAll('.social-item-row').forEach((row, i) => {
                        row.querySelector('.social-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => {
                            input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                        });
                    });
                }
            });

            document.getElementById('save-social-modal').addEventListener('click', function() {
                var name = document.getElementById('modal-social-name').value;
                var link = document.getElementById('modal-social-link').value;
                var icon = document.getElementById('modal-social-icon').value;

                if (editingSocialRow) {
                    editingSocialRow.querySelector('.cell-name').innerText = name;
                    editingSocialRow.querySelector('.hidden-name').value = name;
                    editingSocialRow.querySelector('.cell-link').innerText = link;
                    editingSocialRow.querySelector('.hidden-link').value = link;
                    editingSocialRow.querySelector('.cell-icon').innerText = icon;
                    editingSocialRow.querySelector('.hidden-icon').value = icon;
                    editingSocialRow = null;
                    if (socialModal) socialModal.hide();
                    return;
                }

                const count = socContainer.querySelectorAll('.social-item-row').length;
                const newRow = `
<tr class="social-item-row">
    <td class="text-center align-middle"><span class="social-index">${count + 1}</span></td>
    <td class="align-middle">
        <span class="cell-name">${esc(name)}</span>
        <input type="hidden" name="socials[${count}][name]" class="hidden-name" value="${esc(name)}">
    </td>
    <td class="align-middle">
        <span class="cell-link">${esc(link)}</span>
        <input type="hidden" name="socials[${count}][link]" class="hidden-link" value="${esc(link)}">
    </td>
    <td class="align-middle">
        <span class="cell-icon">${esc(icon)}</span>
        <input type="hidden" name="socials[${count}][icon]" class="hidden-icon" value="${esc(icon)}">
    </td>
    <td class="text-center align-middle">
        <div class="d-flex align-items-center justify-content-center gap-1">
            <button type="button" class="btn btn-xs btn-outline-primary edit-social-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-social" title="Xóa"><i class="ti tabler-trash"></i></button>
        </div>
    </td>
</tr>`;
                socContainer.insertAdjacentHTML('beforeend', newRow);
                if (socialModal) socialModal.hide();
            });
        }

        var serviceModalEl = document.getElementById('serviceModal');
        var serviceModal = serviceModalEl ? new bootstrap.Modal(serviceModalEl) : null;
        var editingServiceRow = null;

        const svContainer = document.getElementById('services-items-container');
        if (svContainer) {
            document.getElementById('add-service-item').addEventListener('click', function() {
                editingServiceRow = null;
                document.getElementById('serviceModalTitle').innerText = 'Thêm Dịch Vụ';
                document.getElementById('modal-service-title').value = '';
                document.getElementById('modal-service-description').value = '';
                document.getElementById('modal-service-doctor-text').value = '';
                document.getElementById('modal-service-link').value = '';
                if (serviceModal) serviceModal.show();
            });

            svContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-service-item');
                if (editBtn) {
                    editingServiceRow = editBtn.closest('.service-item-row');
                    document.getElementById('serviceModalTitle').innerText = 'Sửa Dịch Vụ';
                    document.getElementById('modal-service-title').value = editingServiceRow.querySelector('.hidden-title').value;
                    document.getElementById('modal-service-description').value = editingServiceRow.querySelector('.hidden-description').value;
                    document.getElementById('modal-service-doctor-text').value = editingServiceRow.querySelector('.hidden-doctor-text').value;
                    document.getElementById('modal-service-link').value = editingServiceRow.querySelector('.hidden-link').value;
                    if (serviceModal) serviceModal.show();
                    return;
                }

                if (e.target.closest('.btn-remove-service')) {
                    e.target.closest('.service-item-row').remove();
                    svContainer.querySelectorAll('.service-item-row').forEach((row, i) => {
                        row.querySelector('.service-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => {
                            input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                        });
                    });
                }
            });

            document.getElementById('save-service-modal').addEventListener('click', function() {
                var title = document.getElementById('modal-service-title').value;
                var description = document.getElementById('modal-service-description').value;
                var doctorText = document.getElementById('modal-service-doctor-text').value;
                var link = document.getElementById('modal-service-link').value;

                if (editingServiceRow) {
                    editingServiceRow.querySelector('.cell-title').innerText = title;
                    editingServiceRow.querySelector('.hidden-title').value = title;
                    editingServiceRow.querySelector('.cell-description').innerText = description.substring(0, 40) + (description.length > 40 ? '...' : '');
                    editingServiceRow.querySelector('.hidden-description').value = description;
                    editingServiceRow.querySelector('.cell-doctor-text').innerText = doctorText;
                    editingServiceRow.querySelector('.hidden-doctor-text').value = doctorText;
                    editingServiceRow.querySelector('.cell-link').innerText = link;
                    editingServiceRow.querySelector('.hidden-link').value = link;
                    editingServiceRow = null;
                    if (serviceModal) serviceModal.hide();
                    return;
                }

                const count = svContainer.querySelectorAll('.service-item-row').length;
                const newRow = `
<tr class="service-item-row">
    <td class="text-center align-middle"><span class="service-index">${count + 1}</span></td>
    <td class="align-middle">
        <span class="cell-title">${esc(title)}</span>
        <input type="hidden" name="items[${count}][title]" class="hidden-title" value="${esc(title)}">
    </td>
    <td class="align-middle">
        <span class="cell-description">${esc(description).substring(0, 40) + (description.length > 40 ? '...' : '')}</span>
        <input type="hidden" name="items[${count}][description]" class="hidden-description" value="${esc(description)}">
    </td>
    <td class="align-middle">
        <span class="cell-doctor-text">${esc(doctorText)}</span>
        <input type="hidden" name="items[${count}][doctor_text]" class="hidden-doctor-text" value="${esc(doctorText)}">
    </td>
    <td class="align-middle">
        <span class="cell-link">${esc(link)}</span>
        <input type="hidden" name="items[${count}][link]" class="hidden-link" value="${esc(link)}">
    </td>
    <td class="text-center align-middle">
        <div class="d-flex align-items-center justify-content-center gap-1">
            <button type="button" class="btn btn-xs btn-outline-primary edit-service-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-service" title="Xóa"><i class="ti tabler-trash"></i></button>
        </div>
    </td>
</tr>`;
                svContainer.insertAdjacentHTML('beforeend', newRow);
                if (serviceModal) serviceModal.hide();
            });
        }

        // --- Why Choose Us Logic ---
        $('#whyus-image-input').on('change', function() {
            var f = this.files[0];
            if (f) { var r = new FileReader(); r.onload = function(e) { $('#whyus-image-preview').attr('src', e.target.result).removeClass('d-none'); $('#whyus-image-placeholder').addClass('d-none'); }; r.readAsDataURL(f); }
        });

        var featureModalEl = document.getElementById('featureModal');
        var featureModal = featureModalEl ? new bootstrap.Modal(featureModalEl) : null;
        var editingFeatureRow = null;

        const ftContainer = document.getElementById('features-items-container');
        if (ftContainer) {
            document.getElementById('add-feature-item').addEventListener('click', function() {
                editingFeatureRow = null;
                document.getElementById('featureModalTitle').innerText = 'Thêm Đặc Điểm';
                document.getElementById('modal-feature-title').value = '';
                document.getElementById('modal-feature-description').value = '';
                if (featureModal) featureModal.show();
            });

            ftContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-feature-item');
                if (editBtn) {
                    editingFeatureRow = editBtn.closest('.feature-item-row');
                    document.getElementById('featureModalTitle').innerText = 'Sửa Đặc Điểm';
                    document.getElementById('modal-feature-title').value = editingFeatureRow.querySelector('.hidden-title').value;
                    document.getElementById('modal-feature-description').value = editingFeatureRow.querySelector('.hidden-description').value;
                    if (featureModal) featureModal.show();
                    return;
                }

                if (e.target.closest('.btn-remove-feature')) {
                    e.target.closest('.feature-item-row').remove();
                    ftContainer.querySelectorAll('.feature-item-row').forEach((row, i) => {
                        row.querySelector('.feature-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => {
                            input.name = input.name.replace(/\[\d+\]/, `[${i}]`);
                        });
                    });
                }
            });

            document.getElementById('save-feature-modal').addEventListener('click', function() {
                var title = document.getElementById('modal-feature-title').value;
                var description = document.getElementById('modal-feature-description').value;

                if (editingFeatureRow) {
                    editingFeatureRow.querySelector('.cell-title').innerText = title;
                    editingFeatureRow.querySelector('.hidden-title').value = title;
                    editingFeatureRow.querySelector('.cell-description').innerText = description.substring(0, 40) + (description.length > 40 ? '...' : '');
                    editingFeatureRow.querySelector('.hidden-description').value = description;
                    editingFeatureRow = null;
                    if (featureModal) featureModal.hide();
                    return;
                }

                const count = ftContainer.querySelectorAll('.feature-item-row').length;
                const newRow = `
<tr class="feature-item-row">
    <td class="text-center align-middle"><span class="feature-index">${count + 1}</span></td>
    <td class="align-middle">
        <span class="cell-title">${esc(title)}</span>
        <input type="hidden" name="items[${count}][title]" class="hidden-title" value="${esc(title)}">
    </td>
    <td class="align-middle">
        <span class="cell-description">${esc(description).substring(0, 40) + (description.length > 40 ? '...' : '')}</span>
        <input type="hidden" name="items[${count}][description]" class="hidden-description" value="${esc(description)}">
    </td>
    <td class="text-center align-middle">
        <div class="d-flex align-items-center justify-content-center gap-1">
            <button type="button" class="btn btn-xs btn-outline-primary edit-feature-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-feature" title="Xóa"><i class="ti tabler-trash"></i></button>
        </div>
    </td>
</tr>`;
                ftContainer.insertAdjacentHTML('beforeend', newRow);
                if (featureModal) featureModal.hide();
            });
        }

        // --- COMMON IMAGE PREVIEW ---
        $('.img-input-preview').on('change', function() {
            var input = this;
            var target = $(this).data('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(target).attr('src', e.target.result).removeClass('d-none');
                    $(target).siblings('.text-muted').addClass('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        // --- TESTIMONIALS LOGIC ---
        var tModalEl = document.getElementById('testimonialModal');
        var tModal = tModalEl ? new bootstrap.Modal(tModalEl) : null;
        var editingTRow = null;
        var modalTDataUrl = null;

        $('#modal-t-image').on('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    modalTDataUrl = e.target.result;
                    $('#modal-t-preview').attr('src', e.target.result).removeClass('d-none');
                    $('#modal-t-no-photo').addClass('d-none');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        const tContainer = document.getElementById('testimonials-items-container');
        if (tContainer) {
            document.getElementById('add-testimonial-item').addEventListener('click', function() {
                editingTRow = null;
                $('#testimonialModalTitle').text('Thêm Phản Hồi');
                $('#modal-t-name, #modal-t-role, #modal-t-title, #modal-t-video, #modal-t-review, #modal-t-image').val('');
                modalTDataUrl = null;
                $('#modal-t-preview').addClass('d-none');
                $('#modal-t-no-photo').removeClass('d-none');
                if (tModal) tModal.show();
            });

            tContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-testimonial-item');
                if (editBtn) {
                    editingTRow = editBtn.closest('.testimonial-item-row');
                    $('#testimonialModalTitle').text('Sửa Phản Hồi');
                    $('#modal-t-name').val(editingTRow.querySelector('.hidden-name').value);
                    $('#modal-t-role').val(editingTRow.querySelector('.hidden-role').value);
                    $('#modal-t-title').val(editingTRow.querySelector('.hidden-title').value);
                    $('#modal-t-video').val(editingTRow.querySelector('.hidden-video').value);
                    $('#modal-t-review').val(editingTRow.querySelector('.hidden-review').value);
                    $('#modal-t-image').val('');
                    modalTDataUrl = null;
                    const src = editingTRow.querySelector('img')?.src;
                    if (src) { $('#modal-t-preview').attr('src', src).removeClass('d-none'); $('#modal-t-no-photo').addClass('d-none'); }
                    else { $('#modal-t-preview').addClass('d-none'); $('#modal-t-no-photo').removeClass('d-none'); }
                    if (tModal) tModal.show();
                    return;
                }
                if (e.target.closest('.btn-remove-testimonial')) {
                    e.target.closest('.testimonial-item-row').remove();
                    tContainer.querySelectorAll('.testimonial-item-row').forEach((row, i) => {
                        row.querySelector('.testimonial-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => { input.name = input.name.replace(/\[\d+\]/, `[${i}]`); });
                    });
                }
            });

            document.getElementById('save-testimonial-modal').addEventListener('click', function() {
                const data = {
                    name: $('#modal-t-name').val(),
                    role: $('#modal-t-role').val(),
                    title: $('#modal-t-title').val(),
                    video: $('#modal-t-video').val(),
                    review: $('#modal-t-review').val()
                };

                if (editingTRow) {
                    editingTRow.querySelector('.cell-name').innerText = data.name;
                    editingTRow.querySelector('.cell-role').innerText = data.role;
                    editingTRow.querySelector('.cell-review').innerText = data.review.substring(0, 40) + (data.review.length > 40 ? '...' : '');
                    editingTRow.querySelector('.hidden-name').value = data.name;
                    editingTRow.querySelector('.hidden-role').value = data.role;
                    editingTRow.querySelector('.hidden-title').value = data.title;
                    editingTRow.querySelector('.hidden-video').value = data.video;
                    editingTRow.querySelector('.hidden-review').value = data.review;
                    if (modalTDataUrl) {
                        let img = editingTRow.querySelector('img');
                        if (!img) {
                             editingTRow.querySelector('.testimonial-avatar-wrap').innerHTML = `<img src="${modalTDataUrl}" class="rounded-circle object-fit-cover testimonial-thumb" width="30" height="30"><input type="file" name="items[0][image_file]" class="d-none testimonial-file-input">`;
                        } else {
                            img.src = modalTDataUrl;
                            img.classList.remove('d-none');
                            $(img).siblings('.bg-secondary').addClass('d-none');
                        }
                    }
                    const modalInput = document.getElementById('modal-t-image');
                    const rowInput = editingTRow.querySelector('.testimonial-file-input');
                    if (modalInput.files.length && rowInput) {
                        const dt = new DataTransfer();
                        dt.items.add(modalInput.files[0]);
                        rowInput.files = dt.files;
                    }
                    editingTRow = null;
                    if (tModal) tModal.hide();
                    return;
                }

                const i = tContainer.querySelectorAll('.testimonial-item-row').length;
                const thumbHtml = modalTDataUrl
                    ? `<img src="${modalTDataUrl}" class="rounded-circle object-fit-cover testimonial-thumb" width="30" height="30">`
                    : `<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center text-none" style="width:30px;height:30px;font-size:10px">NA</div>`;

                const html = `<tr class="testimonial-item-row">
                    <td class="text-center align-middle small"><span class="testimonial-index">${i + 1}</span></td>
                    <td class="align-middle"><div class="testimonial-avatar-wrap">${thumbHtml}<input type="file" name="items[${i}][image_file]" class="d-none testimonial-file-input"></div></td>
                    <td class="align-middle">
                        <div class="small fw-bold cell-name">${esc(data.name)}</div>
                        <div class="x-small text-muted cell-role">${esc(data.role)}</div>
                        <input type="hidden" name="items[${i}][name]" class="hidden-name" value="${esc(data.name)}">
                        <input type="hidden" name="items[${i}][role]" class="hidden-role" value="${esc(data.role)}">
                        <input type="hidden" name="items[${i}][title]" class="hidden-title" value="${esc(data.title)}">
                        <input type="hidden" name="items[${i}][video_link]" class="hidden-video" value="${esc(data.video)}">
                    </td>
                    <td class="align-middle small">
                        <span class="cell-review">${esc(data.review).substring(0, 40) + (data.review.length > 40 ? '...' : '')}</span>
                        <input type="hidden" name="items[${i}][review]" class="hidden-review" value="${esc(data.review)}">
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button type="button" class="btn btn-xs btn-outline-primary edit-testimonial-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-testimonial" title="Xóa"><i class="ti tabler-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
                tContainer.insertAdjacentHTML('beforeend', html);
                const modalInput = document.getElementById('modal-t-image');
                const lastRow = tContainer.lastElementChild;
                const rowInput = lastRow.querySelector('.testimonial-file-input');
                if (modalInput.files.length && rowInput) {
                    const dt = new DataTransfer();
                    dt.items.add(modalInput.files[0]);
                    rowInput.files = dt.files;
                }
                if (tModal) tModal.hide();
            });
        }

        // --- FAQ LOGIC ---
        var fModalEl = document.getElementById('faqModal');
        var fModal = fModalEl ? new bootstrap.Modal(fModalEl) : null;
        var editingFRow = null;

        const fContainer = document.getElementById('faq-items-container');
        if (fContainer) {
            document.getElementById('add-faq-item').addEventListener('click', function() {
                editingFRow = null;
                $('#faqModalTitle').text('Thêm FAQ');
                $('#modal-f-question, #modal-f-answer').val('');
                if (fModal) fModal.show();
            });

            fContainer.addEventListener('click', function(e) {
                const editBtn = e.target.closest('.edit-faq-item');
                if (editBtn) {
                    editingFRow = editBtn.closest('.faq-item-row');
                    $('#faqModalTitle').text('Sửa FAQ');
                    $('#modal-f-question').val(editingFRow.querySelector('.hidden-question').value);
                    $('#modal-f-answer').val(editingFRow.querySelector('.hidden-answer').value);
                    if (fModal) fModal.show();
                    return;
                }
                if (e.target.closest('.btn-remove-faq')) {
                    e.target.closest('.faq-item-row').remove();
                    fContainer.querySelectorAll('.faq-item-row').forEach((row, i) => {
                        row.querySelector('.faq-index').innerText = i + 1;
                        row.querySelectorAll('input').forEach(input => { input.name = input.name.replace(/\[\d+\]/, `[${i}]`); });
                    });
                }
            });

            document.getElementById('save-faq-modal').addEventListener('click', function() {
                const question = $('#modal-f-question').val();
                const answer = $('#modal-f-answer').val();

                if (editingFRow) {
                    editingFRow.querySelector('.cell-question').innerText = question;
                    editingFRow.querySelector('.cell-answer').innerText = answer.substring(0, 50) + (answer.length > 50 ? '...' : '');
                    editingFRow.querySelector('.hidden-question').value = question;
                    editingFRow.querySelector('.hidden-answer').value = answer;
                    editingFRow = null;
                    if (fModal) fModal.hide();
                    return;
                }

                const i = fContainer.querySelectorAll('.faq-item-row').length;
                const html = `<tr class="faq-item-row">
                    <td class="text-center align-middle small"><span class="faq-index">${i + 1}</span></td>
                    <td class="align-middle small">
                        <span class="cell-question">${esc(question)}</span>
                        <input type="hidden" name="items[${i}][question]" class="hidden-question" value="${esc(question)}">
                    </td>
                    <td class="align-middle small">
                        <span class="cell-answer">${esc(answer).substring(0, 50) + (answer.length > 50 ? '...' : '')}</span>
                        <input type="hidden" name="items[${i}][answer]" class="hidden-answer" value="${esc(answer)}">
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button type="button" class="btn btn-xs btn-outline-primary edit-faq-item" title="Sửa"><i class="ti tabler-pencil"></i></button>
                            <button type="button" class="btn btn-xs btn-outline-danger btn-remove-faq" title="Xóa"><i class="ti tabler-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
                fContainer.insertAdjacentHTML('beforeend', html);
                if (fModal) fModal.hide();
            });
        }
    });
</script>
@endpush
@endsection
