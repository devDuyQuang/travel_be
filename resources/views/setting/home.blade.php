@extends('index')
@section('title', 'Cấu hình trang chủ')

@section('content')
@php
  $settingType = $settingType ?? 'clinic';
  $settingTypes = $settingTypes ?? ['clinic' => 'clinic', 'rac' => 'RAC'];
  $baseHomeUrl = panel_route('setting.home');
@endphp

<div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
   <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap"> <i class="icon-base ti tabler-home"></i>Cấu hình trang chủ</h5>
  <div class="d-flex align-items-center gap-2">
    <span class="small text-muted">Loại domain:</span>
    <div class="btn-group" role="group" aria-label="Chọn loại domain">
      @foreach($settingTypes as $typeKey => $typeLabel)
        <a href="{{ $baseHomeUrl . '?type=' . $typeKey . '&section=' . $currentSection }}"
           class="btn btn-sm setting-type-switch {{ $settingType === $typeKey ? 'btn-primary' : 'btn-outline-primary' }}"
           data-type="{{ $typeKey }}">
          {{ $typeLabel }}
        </a>
      @endforeach
    </div>
  </div>
</div>

<div class="card-body text-start">
  <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>

  <div class="row g-4 justify-content-start">
    {{-- Cột trái: navbar (vertical tab, không reload) --}}
    <div class="col-12 col-sm-4 col-md-3 col-lg-3 text-start">
      <div class="nav flex-column nav-pills border rounded p-2 text-start" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        @foreach($sectionsList as $item)
          @php $key = $item['key']; @endphp
          <a href="#home-section-{{ $key }}"
             class="nav-link text-start {{ ($currentSection === $key) ? 'active' : '' }} rounded mb-1 small"
             id="home-tab-{{ $key }}"
             data-bs-toggle="pill"
             data-section="{{ $key }}"
             role="tab"
             aria-controls="home-section-{{ $key }}"
             aria-selected="{{ $currentSection === $key ? 'true' : 'false' }}"
             style="text-align: left !important; justify-content: flex-start !important;">
            <span class="me-1 text-muted">{{ $loop->iteration }}.</span>{{ $item['label'] }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- Cột phải: nội dung (tab-pane cho từng section) --}}
    <div class="col-12 col-sm-8 col-md-9 col-lg-9 border rounded p-3">
      <div class="tab-content" id="home-sections-tabContent">
        @foreach($sectionsList as $item)
          @php
            $key  = $item['key'];
            $data = $sectionsData[$key] ?? [];
          @endphp
          <div class="tab-pane fade {{ $currentSection === $key ? 'show active' : '' }}"
               id="home-section-{{ $key }}"
               role="tabpanel"
               aria-labelledby="home-tab-{{ $key }}">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">{{ $loop->iteration }}. {{ $item['label'] }}</h5>
            @includeIf('setting.home.sections.' . $key, $data)
          </div>
        @endforeach
      </div>

      <div id="home-json-wrapper" class="border rounded mt-3 p-2 bg-dark-subtle" data-section="{{ $currentSection }}" data-type="{{ $settingType }}">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <h6 class="mb-0 small text-muted">JSON response</h6>
          <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted" id="btn-clear-json">Xoá</button>
        </div>
        <pre id="home-json-preview" class="bg-black text-white small rounded p-2 mb-0" style="max-height: 260px; overflow: auto; white-space: pre-wrap; word-break: break-all;"></pre>
      </div>
    </div>
  </div>
</div>

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

  /* Nav-tabs con: text có thể xuống hàng, nhưng dãy tab vẫn một hàng, cuộn ngang nếu thiếu chỗ */
  .card-header .nav-tabs.card-header-tabs {
    flex-wrap: nowrap;
    overflow-x: auto;
  }
  .card-header .nav-tabs.card-header-tabs .nav-link {
    white-space: normal;
    text-align: center;
  }

  /* Hero tabs: 4 mục chia 2 cột x 2 dòng, không cần thanh kéo ngang */
  #heroTabs {
    flex-wrap: wrap;
    overflow-x: visible;
  }
  #heroTabs .nav-item {
    flex: 0 0 50%;
    max-width: 50%;
    text-align: center;
  }
  #heroTabs .nav-link {
    width: 100%;
  }

  /* Tiện ích: 4 tab chia 2 cột x 2 hàng */
  #utilitiesTabs {
    flex-wrap: wrap;
    overflow-x: visible;
  }
  #utilitiesTabs .nav-item {
    flex: 0 0 50%;
    max-width: 50%;
    text-align: center;
  }
  #utilitiesTabs .nav-link {
    width: 100%;
  }

  /* Ý kiến khách hàng: 4 tab chia 2 cột x 2 hàng */
  #testimonialsTabs {
    flex-wrap: wrap;
    overflow-x: visible;
  }
  #testimonialsTabs .nav-item {
    flex: 0 0 50%;
    max-width: 50%;
    text-align: center;
  }
  #testimonialsTabs .nav-link {
    width: 100%;
  }

  /* Doctor section: chỉ giữ border, bỏ hết màu nền bên trong */
  .doctor-card,
  .doctor-card .card-header,
  .doctor-card .card-body,
  .doctor-card .tab-pane {
    background-color: transparent !important;
    box-shadow: none !important;
  }

  div.tab-content#home-sections-tabContent,
  #home-sections-tabContent.tab-content,
  #home-sections-tabContent {
    padding: 0 !important;
  }

  /* Bỏ padding 1.5rem ở tab-content trong tất cả tab con (card-body .tab-content) */
  #home-sections-tabContent .card-body .tab-content,
  #home-sections-tabContent .card .tab-content {
    padding: 0 !important;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pre = document.getElementById('home-json-preview');
    const btn = document.getElementById('btn-clear-json');
    const wrapper = document.getElementById('home-json-wrapper');
    const section = wrapper ? wrapper.getAttribute('data-section') : null;
    const type = wrapper ? (wrapper.getAttribute('data-type') || 'clinic') : 'clinic';
    let storageKey = section ? `home_json_${type}_${section}` : `home_json_${type}_preview`;
    const forms = document.querySelectorAll('#home-sections-tabContent form.ajax-form');
    const typeLinks = document.querySelectorAll('.setting-type-switch[data-type]');

    forms.forEach(form => {
      let typeInput = form.querySelector('input[name="type"]');
      if (!typeInput) {
        typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        form.appendChild(typeInput);
      }
      typeInput.value = type;
    });

    function updateTypeLinks(sectionKey) {
      typeLinks.forEach(link => {
        const linkType = link.getAttribute('data-type') || 'clinic';
        link.setAttribute('href', `{{ $baseHomeUrl }}?type=${linkType}&section=${sectionKey || 'hero'}`);
      });
    }

    updateTypeLinks(section || 'hero');

    // Khởi tạo: nếu có JSON đã lưu trong localStorage thì hiển thị luôn
    try {
      const saved = window.localStorage ? window.localStorage.getItem(storageKey) : null;
      if (saved && pre) {
        pre.textContent = saved;
        if (wrapper) wrapper.style.display = 'block';
      } else if (wrapper) {
        wrapper.style.display = 'none';
      }
    } catch (e) {
      if (wrapper) wrapper.style.display = pre && pre.textContent.trim() ? 'block' : 'none';
    }

    if (btn && pre) {
      btn.addEventListener('click', function () {
        pre.textContent = '';
        try {
          window.localStorage && window.localStorage.removeItem(storageKey);
        } catch (e) {}
        if (wrapper) wrapper.style.display = 'none';
      });
    }

    // Khi đổi tab vertical, load JSON tương ứng trong localStorage
    const navLinks = document.querySelectorAll('#v-pills-tab .nav-link[data-section]');
    navLinks.forEach(link => {
      link.addEventListener('shown.bs.tab', function () {
        const sec = this.getAttribute('data-section');
        if (!pre || !wrapper) return;
        const key = sec ? `home_json_${type}_${sec}` : `home_json_${type}_preview`;
        storageKey = key;
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
          updateTypeLinks(sec || 'hero');
        } catch (e) {
          console.warn('Không thể load JSON cho section', sec, e);
        }
      });
    });
  });
</script>
@endpush
@endsection
