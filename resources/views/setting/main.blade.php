@extends('index')
@section('title', 'Cấu hình Site')

@section('content')
@include('partials.css.setting')
<main class="main-wrapper setting-page">
  <div class="main-content">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
      <h5 class="mb-0"><i class="icon-base ti tabler-settings me-1"></i>Cấu hình Site</h5>
      <span class="small text-muted">Chỉ chứa thông tin dùng chung trên toàn website.</span>
    </div>
    <div class="card-body">
      <div id="ajax-alert" style="display:none" class="alert" role="alert"></div>
      <div class="row g-4">
        <div class="col-12 col-lg-3">
          <div class="nav flex-column nav-pills border rounded p-2">
            @foreach([
              ['config', 'Thông tin công ty'],
              ['logo', 'Logo & Favicon'],
              ['topbar', 'Header & Topbar'],
              ['floating', 'Mạng xã hội & liên hệ nổi'],
            ] as $index => [$key, $label])
              <a href="#site-{{ $key }}" data-bs-toggle="pill" class="nav-link text-start {{ request('tab', 'config') === $key ? 'active' : '' }}">
                <span class="me-1 text-muted">{{ $index + 1 }}.</span>{{ $label }}
              </a>
            @endforeach
            <a href="{{ panel_route('menu.index') }}" class="nav-link text-start">
              <span class="me-1 text-muted">5.</span>Menu
            </a>
          </div>
        </div>
        <div class="col-12 col-lg-9">
          <div class="tab-content border rounded p-3">
            <div class="tab-pane fade {{ request('tab', 'config') === 'config' ? 'show active' : '' }}" id="site-config">
              <h5 class="border-bottom pb-2 mb-3">1. Thông tin công ty</h5>
              <form action="{{ panel_route('setting.update', $item->id) }}" method="POST" class="ajax-form">
                @csrf @method('PUT')
                <div class="row g-3">
                  <div class="col-md-6"><label class="form-label">Tên công ty</label><input class="form-control" name="company" value="{{ data_get($item->value, 'company') }}"></div>
                  <div class="col-md-6"><label class="form-label">Copyright</label><input class="form-control" name="copyright" value="{{ data_get($item->value, 'copyright') }}"></div>
                  <div class="col-12"><label class="form-label">Mô tả chung</label><textarea class="form-control" rows="3" name="description">{{ data_get($item->value, 'description') }}</textarea></div>
                  <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email_description" value="{{ data_get($item->value, 'email_description') }}"></div>
                  <div class="col-md-6"><label class="form-label">Hotline</label><input class="form-control" name="phone_description" value="{{ data_get($item->value, 'phone_description') }}"></div>
                  <div class="col-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="address_description" value="{{ data_get($item->value, 'address_description') }}"></div>
                  <div class="col-md-6"><label class="form-label">Website</label><input class="form-control" name="website" value="{{ data_get($item->value, 'website') }}" placeholder="https://golfnity.com"></div>
                  <div class="col-md-6"><label class="form-label">Giờ hoạt động</label><input class="form-control" name="time_description" value="{{ data_get($item->value, 'time_description') }}"></div>
                  <div class="col-12"><label class="form-label">Bản đồ nhúng</label><textarea class="form-control font-monospace" rows="3" name="map" placeholder='<iframe src="https://www.google.com/maps/embed?pb=..."></iframe>'>{{ data_get($item->value, 'map') }}</textarea><div class="form-text">Có thể dán toàn bộ mã iframe Google Maps hoặc chỉ URL trong thuộc tính src.</div></div>
                </div>
                <div class="text-end mt-3"><button class="btn btn-primary">Lưu thông tin công ty</button></div>
              </form>
            </div>

            <div class="tab-pane fade {{ request('tab') === 'logo' ? 'show active' : '' }}" id="site-logo">
              <h5 class="border-bottom pb-2 mb-3">2. Logo & Favicon</h5>
              <form action="{{ panel_route('setting.updateLogoFavicon') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf @method('PUT')
                <div class="row g-3">
                  @foreach([
                    ['logo', 'Logo chính', $currentLogoUrl],
                    ['logo_black', 'Logo nền sáng', $currentLogoBlackUrl],
                    ['favicon', 'Favicon', $currentFaviconUrl],
                  ] as [$key, $label, $url])
                    <div class="col-md-4">
                      <x-file-input
                        :label="$label"
                        :name="$key . '_file'"
                        :current-url="$url"
                        :remove-name="'remove_' . $key . '_file'"
                      />
                    </div>
                  @endforeach
                </div>
                <div class="text-end mt-3"><button class="btn btn-primary">Lưu Logo & Favicon</button></div>
              </form>
            </div>

            <div class="tab-pane fade {{ request('tab') === 'topbar' ? 'show active' : '' }}" id="site-topbar">
              <h5 class="border-bottom pb-2 mb-3">3. Header & Topbar</h5>
              <form action="{{ panel_route('setting.updateTopbar') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
                @csrf @method('PUT')
                <div id="topbar-items" class="d-grid gap-3">
                  @forelse($topbarV['items'] ?? [] as $index => $topbar)
                    <div class="border rounded p-3 row g-2 repeat-item">
                      <input type="hidden" data-field="image" name="items[{{ $index }}][image]" value="{{ $topbar['image'] ?? '' }}">
                      <div class="col-md-4"><label class="form-label">Tiêu đề</label><input class="form-control" data-field="title" name="items[{{ $index }}][title]" value="{{ $topbar['title'] ?? '' }}"></div>
                      <div class="col-md-4"><label class="form-label">Nội dung</label><input class="form-control" data-field="description" name="items[{{ $index }}][description]" value="{{ $topbar['description'] ?? '' }}"></div>
                      <div class="col-md-3"><label class="form-label">Liên kết</label><input class="form-control" data-field="link" name="items[{{ $index }}][link]" value="{{ $topbar['link'] ?? '' }}"></div>
                      <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger remove-item">Xoá</button></div>
                    </div>
                  @empty
                    <div class="border rounded p-3 row g-2 repeat-item">
                      <input type="hidden" data-field="image" name="items[0][image]">
                      <div class="col-md-4"><label class="form-label">Tiêu đề</label><input class="form-control" data-field="title" name="items[0][title]"></div>
                      <div class="col-md-4"><label class="form-label">Nội dung</label><input class="form-control" data-field="description" name="items[0][description]"></div>
                      <div class="col-md-3"><label class="form-label">Liên kết</label><input class="form-control" data-field="link" name="items[0][link]"></div>
                      <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger remove-item">Xoá</button></div>
                    </div>
                  @endforelse
                </div>
                <div class="d-flex justify-content-between mt-3"><button type="button" class="btn btn-outline-primary add-repeat" data-target="topbar-items">Thêm dòng</button><button class="btn btn-primary">Lưu Header & Topbar</button></div>
              </form>
            </div>

            <div class="tab-pane fade {{ request('tab') === 'floating' ? 'show active' : '' }}" id="site-floating">
              <h5 class="border-bottom pb-2 mb-3">4. Mạng xã hội & liên hệ nổi</h5>
              <form action="{{ panel_route('setting.updateFloating') }}" method="POST" class="ajax-form">
                @csrf @method('PUT')
                <div class="row g-3 mb-3">
                  <div class="col-md-6"><label class="form-label">Số điện thoại</label><input class="form-control" name="contact_phone" value="{{ $floatingV['contact_phone'] ?? '' }}"></div>
                  <div class="col-md-6"><label class="form-label">Liên kết liên hệ</label><input class="form-control" name="contact_link" value="{{ $floatingV['contact_link'] ?? '' }}"></div>
                </div>
                <div id="social-items" class="d-grid gap-3">
                  @forelse($floatingV['socials'] ?? [] as $index => $social)
                    <div class="border rounded p-3 row g-2 repeat-item">
                      <div class="col-md-3"><label class="form-label">Tên</label><input class="form-control" data-field="name" name="socials[{{ $index }}][name]" value="{{ $social['name'] ?? '' }}"></div>
                      <div class="col-md-4"><label class="form-label">Liên kết</label><input class="form-control" data-field="link" name="socials[{{ $index }}][link]" value="{{ $social['link'] ?? '' }}"></div>
                      <div class="col-md-4"><label class="form-label">Class icon</label><input class="form-control" data-field="icon" name="socials[{{ $index }}][icon]" value="{{ $social['icon'] ?? '' }}"></div>
                      <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger remove-item">Xoá</button></div>
                    </div>
                  @empty
                    <div class="border rounded p-3 row g-2 repeat-item">
                      <div class="col-md-3"><label class="form-label">Tên</label><input class="form-control" data-field="name" name="socials[0][name]"></div>
                      <div class="col-md-4"><label class="form-label">Liên kết</label><input class="form-control" data-field="link" name="socials[0][link]"></div>
                      <div class="col-md-4"><label class="form-label">Class icon</label><input class="form-control" data-field="icon" name="socials[0][icon]"></div>
                      <div class="col-md-1 d-flex align-items-end"><button type="button" class="btn btn-outline-danger remove-item">Xoá</button></div>
                    </div>
                  @endforelse
                </div>
                <div class="d-flex justify-content-between mt-3"><button type="button" class="btn btn-outline-primary add-repeat" data-target="social-items">Thêm mạng xã hội</button><button class="btn btn-primary">Lưu mạng xã hội</button></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  function reindex(list) {
    const prefix = list.id === 'social-items' ? 'socials' : 'items';
    list.querySelectorAll('.repeat-item').forEach((item, index) => {
      item.querySelectorAll('[data-field]').forEach(input => input.name = `${prefix}[${index}][${input.dataset.field}]`);
    });
  }
  document.addEventListener('click', function (event) {
    const remove = event.target.closest('.remove-item');
    if (remove) {
      const list = remove.closest('[id$="-items"]');
      remove.closest('.repeat-item').remove();
      reindex(list);
    }
    const add = event.target.closest('.add-repeat');
    if (add) {
      const list = document.getElementById(add.dataset.target);
      const item = list.querySelector('.repeat-item').cloneNode(true);
      item.querySelectorAll('input').forEach(input => input.value = '');
      list.appendChild(item);
      reindex(list);
    }
  });
});
</script>
@endpush
@endsection
