{{-- Stats Cards Roles --}}
<div class="row g-6 mb-6">
    {{-- Tổng Vai Trò --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-dark h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Vai Trò</span>
                        <h4 class="mb-0 my-1">{{ $stats['roles']['total'] }}</h4>
                        <p class="mb-0 small text-muted">Hệ thống phân quyền</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-dark">
                            <i class="ti tabler-settings-check fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Role Mới Tuần Này --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Mới Tuần Này</span>
                        <h4 class="mb-0 my-1">{{ $stats['roles']['this_week'] }}</h4>
                        <p class="mb-0 small text-success">+{{ $stats['roles']['this_week'] }} vai trò mới</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti tabler-plus fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tổng Quyền Hạn --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Quyền Hạn</span>
                        <h4 class="mb-0 my-1">{{ $stats['roles']['permissions'] }}</h4>
                        <p class="mb-0 small text-info">Permissions active</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti tabler-lock-access fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Role Đang Hoạt Động --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Đang Hoạt Động</span>
                        <h4 class="mb-0 my-1">{{ $stats['roles']['active'] }}</h4>
                        <p class="mb-0 small text-primary">Vai trò khả dụng</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti tabler-shield-checkered fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-6">
    {{-- Area Chart đồng bộ với các tab trước --}}
    <div class="col-xl-8 col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-4">Biểu Đồ Thống Kê Vai Trò</h5>
                <div id="roleGrowthChart"></div>
            </div>
        </div>
    </div>

    {{-- Danh sách Role mới tạo --}}
    <div class="col-xl-4 col-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Vai trò mới nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-flush">
                        <tbody>
                            @foreach($latestRoles as $role)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center py-1">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-dark">
                                                {{ strtoupper(substr($role->code, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-heading small">{{ $role->name }}</span>
                                            <small class="text-muted" style="font-size: 11px;">
                                                {{ $role->users_count }} người dùng • {{ $role->permissions_count }} quyền
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
