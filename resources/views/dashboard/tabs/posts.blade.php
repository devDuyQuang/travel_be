{{-- Stats Cards --}}
<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Bài viết</span>
                        <h4 class="mb-0 my-1">{{ $stats['posts']['total'] }}</h4>
                        <p class="mb-0 small text-primary">+{{ $stats['posts']['this_week'] }} tuần này</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti tabler-news fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Đang Hoạt Động</span>
                        <h4 class="mb-0 my-1">{{ $stats['posts']['active'] }}</h4>
                        <p class="mb-0 small text-success">Public trên Web</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti tabler-circle-check fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Lượt Xem</span>
                        <h4 class="mb-0 my-1">{{ $stats['posts']['views'] }}</h4>
                        <p class="mb-0 small text-info">Toàn thời gian</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti tabler-eye fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Lượt Yêu Thích</span>
                        <h4 class="mb-0 my-1">{{ $stats['posts']['favs'] }}</h4>
                        <p class="mb-0 small text-warning">Tương tác user</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti tabler-heart fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Biểu đồ --}}
    <div class="col-xl-8 col-12 mb-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Biểu Đồ Thống Kê Bài Viết</h5>
            </div>
            <div class="card-body">
                <div id="postGrowthChart"></div>
            </div>
        </div>
    </div>

    {{-- Bài viết mới nhất --}}
    <div class="col-xl-4 col-12 mb-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Bài Viết Mới Cập Nhật</h5>
                <a href="{{ panel_route('post.index') }}" class="small">Tất cả</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($latestPosts as $post)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                        <div class="d-flex flex-column overflow-hidden">
                            <span class="fw-bold text-heading text-truncate" style="max-width: 220px;" title="{{ $post->name }}">
                                {{ $post->name }}
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted">Bởi: {{ $post->creator->name ?? 'System' }}</small>
                                <small class="text-muted">•</small>
                                <small class="text-muted">{{ $post->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="ms-2">
                            @if($post->status === 1)
                                <span class="badge bg-label-success">Active</span>
                            @else
                                <span class="badge bg-label-secondary">Draft</span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted border-0">Chưa có bài viết mới.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
