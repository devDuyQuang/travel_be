@extends('index')
@section('title', 'Cấu hình trực tuyến - Giới Thiệu')

@section('content')
@php
  $settingType  = $settingType  ?? 'clinic';
  $settingTypes = $settingTypes ?? ['clinic' => 'clinic', 'rac' => 'RAC'];
  $baseAboutUrl = panel_route('setting.aboutPage');
@endphp

<div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
  <h5 class="mb-0 d-flex align-items-center gap-2 flex-wrap"> <i class="icon-base ti tabler-user-screen"></i>Cấu hình trang Giới Thiệu</h5>
  <div class="d-flex align-items-center gap-2">
    <span class="small text-muted">Loại domain:</span>
    <div class="btn-group" role="group">
      @foreach($settingTypes as $typeKey => $typeLabel)
        <a href="{{ $baseAboutUrl . '?type=' . $typeKey . '&section=' . $currentSection }}"
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
    {{-- Left: vertical tabs --}}
    <div class="col-12 col-sm-4 col-md-3 col-lg-3 text-start">
      <div class="nav flex-column nav-pills border rounded p-2 text-start" id="about-pills-tab" role="tablist" aria-orientation="vertical">
        @foreach($sectionsList as $item)
          @php $key = $item['key']; @endphp
          <a href="#about-section-{{ $key }}"
             class="nav-link text-start {{ ($currentSection === $key) ? 'active' : '' }} rounded mb-1 small"
             id="about-tab-{{ $key }}"
             data-bs-toggle="pill"
             data-section="{{ $key }}"
             role="tab"
             aria-controls="about-section-{{ $key }}"
             aria-selected="{{ $currentSection === $key ? 'true' : 'false' }}"
             style="text-align: left !important; justify-content: flex-start !important;">
            <span class="me-1 text-muted">{{ $loop->iteration }}.</span>{{ $item['label'] }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- Right: content --}}
    <div class="col-12 col-sm-8 col-md-9 col-lg-9 border rounded p-3">
      <div class="tab-content" id="about-sections-tabContent">
        @foreach($sectionsList as $item)
          @php
            $key  = $item['key'];
            $data = $sectionsData[$key] ?? [];
          @endphp
          <div class="tab-pane fade {{ $currentSection === $key ? 'show active' : '' }}"
               id="about-section-{{ $key }}"
               role="tabpanel"
               aria-labelledby="about-tab-{{ $key }}">
            <h5 class="fw-bold text-uppercase mb-3 pb-2 border-bottom" style="letter-spacing:.05em">{{ $loop->iteration }}. {{ $item['label'] }}</h5>
            @includeIf('setting.about.sections.' . $key, $data)
          </div>
        @endforeach
      </div>

@php
  $sectionsJsonMap = [];
  foreach ($sectionsData as $k => $d) {
    $sectionsJsonMap[$k] = $d['v'] ?? ($d['item']->value ?? []);
  }
@endphp
      <div id="about-json-wrapper" class="border rounded mt-3 p-2 bg-dark-subtle"
           data-section="{{ $currentSection }}"
           data-type="{{ $settingType }}"
           data-db-json='{{ json_encode($sectionsJsonMap) }}'>
        <div class="d-flex justify-content-between align-items-center mb-1">
          <h6 class="mb-0 small text-muted">JSON response</h6>
          <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted" id="btn-clear-about-json">Xoá</button>
        </div>
        <pre id="about-json-preview" class="bg-black text-white small rounded p-2 mb-0" style="max-height: 260px; overflow: auto; white-space: pre-wrap; word-break: break-all;"></pre>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  #about-pills-tab.nav-pills,
  #about-pills-tab.nav-pills .nav-link {
    text-align: left !important;
  }
  #about-pills-tab.nav-pills .nav-link {
    display: block !important;
    justify-content: flex-start !important;
    font-size: 0.9rem;
  }
  div.tab-content#about-sections-tabContent,
  #about-sections-tabContent.tab-content,
  #about-sections-tabContent {
    padding: 0 !important;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pre     = document.getElementById('about-json-preview');
    const btn     = document.getElementById('btn-clear-about-json');
    const wrapper = document.getElementById('about-json-wrapper');
    const section = wrapper ? wrapper.getAttribute('data-section') : null;
    const type    = wrapper ? (wrapper.getAttribute('data-type') || 'clinic') : 'clinic';
    let storageKey = section ? `about_json_${type}_${section}` : `about_json_${type}_preview`;
    const forms    = document.querySelectorAll('#about-sections-tabContent form.ajax-form');
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
        link.setAttribute('href', `{{ $baseAboutUrl }}?type=${linkType}&section=${sectionKey || 'hero'}`);
      });
    }
    updateTypeLinks(section || 'hero');

    try {
      const dbJsonRaw = wrapper ? wrapper.getAttribute('data-db-json') : null;
      const dbJsonMap = dbJsonRaw ? JSON.parse(dbJsonRaw) : {};
      const saved = window.localStorage ? window.localStorage.getItem(storageKey) : null;
      const displayData = saved || (dbJsonMap[section] ? JSON.stringify(dbJsonMap[section], null, 2) : null);
      if (displayData && pre) { pre.textContent = displayData; if (wrapper) wrapper.style.display = 'block'; }
      else if (wrapper) wrapper.style.display = 'none';
    } catch (e) { if (wrapper) wrapper.style.display = pre && pre.textContent.trim() ? 'block' : 'none'; }

    if (btn && pre) {
      btn.addEventListener('click', function () {
        pre.textContent = '';
        try { window.localStorage && window.localStorage.removeItem(storageKey); } catch (e) {}
        if (wrapper) wrapper.style.display = 'none';
      });
    }

    const navLinks = document.querySelectorAll('#about-pills-tab .nav-link[data-section]');
    navLinks.forEach(link => {
      link.addEventListener('shown.bs.tab', function () {
        const sec = this.getAttribute('data-section');
        if (!pre || !wrapper) return;
        const key = sec ? `about_json_${type}_${sec}` : `about_json_${type}_preview`;
        storageKey = key;
        try {
          const savedJson = window.localStorage ? window.localStorage.getItem(key) : null;
          const dbJsonRaw2 = wrapper.getAttribute('data-db-json');
          const dbJsonMap2 = dbJsonRaw2 ? JSON.parse(dbJsonRaw2) : {};
          const displayData = savedJson || (dbJsonMap2[sec] ? JSON.stringify(dbJsonMap2[sec], null, 2) : null);
          if (displayData) { pre.textContent = displayData; wrapper.style.display = 'block'; }
          else { pre.textContent = ''; wrapper.style.display = 'none'; }
          wrapper.setAttribute('data-section', sec || '');
          updateTypeLinks(sec || 'hero');
        } catch (e) { console.warn('Không thể load JSON', sec, e); }
      });
    });
  });
</script>
@endpush
@endsection
