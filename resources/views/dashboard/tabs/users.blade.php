{{-- Stats Cards Users --}}
<div class="row g-6 mb-6">
    {{-- Tổng Thành Viên --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-secondary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Thành Viên</span>
                        <h4 class="mb-0 my-1">{{ $stats['users']['total'] }}</h4>
                        <p class="mb-0 small text-muted">User đăng ký</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="ti tabler-user-circle fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Đăng ký mới trong tuần --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Mới Tuần Này</span>
                        <h4 class="mb-0 my-1">{{ $stats['users']['this_week'] }}</h4>
                        <p class="mb-0 small text-success">+{{ $stats['users']['this_week'] }} đăng ký</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti tabler-user-plus fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quản trị viên --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-danger h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Quản Trị Viên</span>
                        <h4 class="mb-0 my-1">{{ $stats['users']['admins'] }}</h4>
                        <p class="mb-0 small text-danger">Quyền Admin/Editor</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ti tabler-shield-check fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Khách hàng/Member --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Thành Viên Thường</span>
                        <h4 class="mb-0 my-1">{{ $stats['users']['members'] }}</h4>
                        <p class="mb-0 small text-primary">Người dùng web</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti tabler-user fs-3"></i>
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
                <h5 class="card-title mb-4">Biểu Đồ Thống Kê Người Dùng</h5>
                <div id="userGrowthChart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Thành viên mới nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-flush">
                        <tbody>
                            @foreach($latestUsers as $user)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center py-1">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-heading small">{{ $user->name }}</span>
                                            <small class="text-muted" style="font-size: 11px;">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0 text-end">
                                    <span class="badge bg-label-secondary small" style="font-size: 10px">
                                        {{ $user->role?->name ?? 'N/A' }}
                                    </span>
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
