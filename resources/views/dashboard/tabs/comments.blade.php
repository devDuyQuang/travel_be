{{-- Stats Cards Comments --}}
<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Bình luận</span>
                        <h4 class="mb-0 my-1">{{ $stats['comments']['total'] }}</h4>
                        <p class="mb-0 small text-muted">Toàn hệ thống</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="ti tabler-messages fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-danger h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Chờ Phê Duyệt</span>
                        <h4 class="mb-0 my-1">{{ $stats['comments']['pending'] }}</h4>
                        <p class="mb-0 small text-danger">Cần xử lý ngay</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ti tabler-clock-pause fs-3"></i>
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
                        <span class="text-heading fw-medium">Bình Luận Gốc</span>
                        <h4 class="mb-0 my-1">{{ $stats['comments']['root'] }}</h4>
                        <p class="mb-0 small text-info">Câu hỏi từ khách</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti tabler-message-dots fs-3"></i>
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
                        <span class="text-heading fw-medium">Phản Hồi</span>
                        <h4 class="mb-0 my-1">{{ $stats['comments']['replies'] }}</h4>
                        <p class="mb-0 small text-success">Đã trả lời khách</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti tabler-message-forward fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    <div class="col-xl-8 col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-4">Biểu Đồ Thống Kê Bình Luận</h5>
                <div id="commentGrowthChart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center pb-2">
                <h5 class="card-title mb-0">Bình luận mới nhất</h5>
                @if($stats['comments']['pending'] > 0)
                    <span class="badge bg-danger">!</span>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-flush">
                        <tbody>
                            @foreach($latestComments as $cmt)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-start py-1">
                                        <div class="avatar avatar-sm me-3 mt-1">
                                            <span class="avatar-initial rounded-circle bg-label-dark small">
                                                {{ strtoupper(substr($cmt->display_name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold text-heading small me-2">{{ $cmt->display_name }}</span>
                                                @if($cmt->status == 'pending')
                                                    <span class="badge badge-dot bg-danger"></span>
                                                @endif
                                            </div>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 220px; font-size: 11px;">
                                                "{{ $cmt->short_content }}"
                                            </small>
                                            <small class="text-primary mt-1" style="font-size: 9px">
                                                <i class="ti tabler-link fs-tiny"></i> Tại: {{ class_basename($cmt->commentable_type) }}
                                            </small>
                                        </div>
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
