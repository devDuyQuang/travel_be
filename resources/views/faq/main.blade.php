@extends('index')

@section('title', 'FAQ')

@section('content')
<main class="main-wrapper"><div class="main-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div><h5 class="mb-1">Câu hỏi thường gặp</h5><p class="mb-0 text-muted">Quản lý FAQ hiển thị ngoài website.</p></div>
      <a class="btn btn-primary" href="{{ panel_route('faq.create') }}">Thêm FAQ</a>
    </div>
    <div class="card-body table-responsive">
      <table class="table align-middle">
        <thead><tr><th>Câu hỏi</th><th>Nhóm</th><th>Thứ tự</th><th>Trạng thái</th><th class="text-end">Hành động</th></tr></thead>
        <tbody>
          @forelse($faqs as $faq)
            <tr>
              <td>{{ $faq->question }}</td><td>{{ $faq->category ?: '—' }}</td><td>{{ $faq->sort_order }}</td>
              <td><span class="badge {{ $faq->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $faq->is_active ? 'Đang bật' : 'Đã tắt' }}</span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ panel_route('faq.edit', $faq->id) }}">Sửa</a>
                <form class="d-inline" method="POST" action="{{ panel_route('faq.destroy', $faq->id) }}" onsubmit="return confirm('Xóa FAQ này?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa</button></form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Chưa có FAQ.</td></tr>
          @endforelse
        </tbody>
      </table>
      {{ $faqs->links() }}
    </div>
  </div>
</div></main>
@endsection
