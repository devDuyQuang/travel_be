@extends('index')

@section('title', ($member->exists ? 'Sửa' : 'Thêm') . ' nhân viên')

@section('content')
<main class="main-wrapper"><div class="main-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">{{ $member->exists ? 'Sửa nhân viên' : 'Thêm nhân viên' }}</h5>
      <a class="btn btn-outline-secondary" href="{{ panel_route('team-member.index') }}">Quay lại</a>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data" action="{{ $member->exists ? panel_route('team-member.update', $member->id) : panel_route('team-member.store') }}">
        @csrf @if($member->exists) @method('PUT') @endif
        <div class="row g-3">
          <div class="col-md-6"><x-input-field name="name" label="Họ tên" :required="true" :value="old('name', $member->name)" /></div>
          <div class="col-md-6"><x-input-field name="slug" label="Slug" :value="old('slug', $member->slug)" /></div>
          <div class="col-md-6"><x-input-field name="job_title" label="Chức vụ" :value="old('job_title', $member->job_title)" /></div>
          <div class="col-md-6"><x-input-field name="department" label="Bộ phận" :value="old('department', $member->department)" /></div>
          <div class="col-md-4"><x-input-field name="phone" label="Số điện thoại" :value="old('phone', $member->phone)" /></div>
          <div class="col-md-4"><x-input-field name="email" label="Email" :value="old('email', $member->email)" /></div>
          <div class="col-md-4"><x-input-field name="zalo_url" label="Zalo URL" :value="old('zalo_url', $member->zalo_url)" /></div>
          <div class="col-md-6"><x-input-field name="facebook_url" label="Facebook" :value="old('facebook_url', $member->facebook_url)" /></div>
          <div class="col-md-6"><x-input-field name="linkedin_url" label="LinkedIn" :value="old('linkedin_url', $member->linkedin_url)" /></div>
          <div class="col-12"><x-textarea-field name="short_description" label="Mô tả ngắn" rows="3" :value="old('short_description', $member->short_description)" /></div>
          <div class="col-md-4"><x-input-field name="sort_order" label="Thứ tự" type="number" :value="old('sort_order', $member->sort_order ?? 0)" /></div>
          <div class="col-md-8">
            <label class="form-label">Ảnh đại diện</label>
            @if($currentAvatarUrl)<div class="mb-2"><img src="{{ $currentAvatarUrl }}" width="120" style="border-radius:8px" alt=""><label class="ms-3"><input type="checkbox" name="remove_avatar" value="1"> Xóa ảnh</label></div>@endif
            <input class="form-control" type="file" name="avatar_file" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">JPG, PNG, WebP tối đa 5MB. Khuyến nghị ảnh chân dung 4:5.</small>
          </div>
          <div class="col-12 d-flex gap-4 flex-wrap">
            <label><input type="checkbox" name="show_on_team" value="1" @checked(old('show_on_team', $member->show_on_team ?? true))> Hiển thị trên trang đội ngũ</label>
            <label><input type="checkbox" name="show_in_quick_panel" value="1" @checked(old('show_in_quick_panel', $member->show_in_quick_panel ?? false))> Hiển thị panel liên hệ nhanh</label>
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $member->is_active ?? true))> Đang hoạt động</label>
          </div>
        </div>
        <div class="mt-4"><button class="btn btn-primary">Lưu nhân viên</button></div>
      </form>
    </div>
  </div>
</div></main>
@endsection
