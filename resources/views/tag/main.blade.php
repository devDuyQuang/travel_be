@extends('index')

@section('title', 'Thẻ bài viết')

@section('content')
<main class="main-wrapper">
  <div class="main-content">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <h5 class="mb-1">Thẻ bài viết</h5>
          <p class="mb-0 text-muted">Quản lý tag hiển thị ở sidebar trang Tin tức.</p>
        </div>
        <a class="btn btn-primary" href="{{ panel_route('tag.create') }}">Thêm thẻ</a>
      </div>
      <div class="card-body table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Tên</th>
              <th>Slug</th>
              <th>Số bài</th>
              <th>Thứ tự</th>
              <th>Trạng thái</th>
              <th class="text-end">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tags as $tag)
              <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td>{{ $tag->posts_count }}</td>
                <td>{{ $tag->sort_order }}</td>
                <td><span class="badge {{ $tag->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $tag->is_active ? 'Đang bật' : 'Đã tắt' }}</span></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ panel_route('tag.edit', $tag->id) }}">Sửa</a>
                  <form class="d-inline" method="POST" action="{{ panel_route('tag.destroy', $tag->id) }}" onsubmit="return confirm('Xóa thẻ này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Chưa có thẻ bài viết.</td></tr>
            @endforelse
          </tbody>
        </table>
        {{ $tags->links() }}
      </div>
    </div>
  </div>
</main>
@endsection
