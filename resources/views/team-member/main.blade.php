@extends('index')

@section('title', 'Nhân viên')

@section('content')
<main class="main-wrapper"><div class="main-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><h5 class="mb-1">Nhân viên</h5><p class="mb-0 text-muted">Quản lý đội ngũ tư vấn WAYLUNE.</p></div>
      <a class="btn btn-primary" href="{{ panel_route('team-member.create') }}">Thêm nhân viên</a>
    </div>
    <div class="card-body table-responsive">
      <table class="table align-middle">
        <thead><tr><th>Ảnh</th><th>Họ tên</th><th>Chức vụ</th><th>Bộ phận</th><th>Team</th><th>Panel</th><th>Trạng thái</th><th class="text-end">Hành động</th></tr></thead>
        <tbody>
          @forelse($members as $member)
            <tr>
              <td>@if($member->avatar)<img src="{{ normalize_image_url($member->avatar, 'team-member') }}" width="56" height="70" style="object-fit:cover;border-radius:8px" alt="">@endif</td>
              <td>{{ $member->name }}</td><td>{{ $member->job_title ?: '—' }}</td><td>{{ $member->department ?: '—' }}</td>
              <td>{{ $member->show_on_team ? 'Có' : 'Không' }}</td><td>{{ $member->show_in_quick_panel ? 'Có' : 'Không' }}</td>
              <td><span class="badge {{ $member->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $member->is_active ? 'Đang bật' : 'Đã tắt' }}</span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ panel_route('team-member.edit', $member->id) }}">Sửa</a>
                <form class="d-inline" method="POST" action="{{ panel_route('team-member.destroy', $member->id) }}" onsubmit="return confirm('Xóa nhân viên này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center text-muted">Chưa có nhân viên.</td></tr>
          @endforelse
        </tbody>
      </table>
      {{ $members->links() }}
    </div>
  </div>
</div></main>
@endsection
