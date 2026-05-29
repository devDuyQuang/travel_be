{{-- Stats Cards Doctors --}}
<div class="row g-6 mb-6">
    {{-- 1. Tổng Bác Sĩ --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-info h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Tổng Bác Sĩ</span>
                        <h4 class="mb-0 my-1">{{ $stats['doctors']['total'] }}</h4>
                        <p class="mb-0 small text-info">+{{ $stats['doctors']['this_week'] }} tuần này</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti tabler-users fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Chuyên Khoa --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Chuyên Khoa</span>
                        <h4 class="mb-0 my-1">{{ $stats['doctors']['specialties'] }}</h4>
                        <p class="mb-0 small text-primary">Đa dạng ngành</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti tabler-stethoscope fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Mạng Xã Hội --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Kết Nối MXH</span>
                        <h4 class="mb-0 my-1">{{ $stats['doctors']['has_social'] ?? 0 }}</h4>
                        <p class="mb-0 small text-warning">Đã cập nhật Profile</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti tabler-share fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Tương Tác (Hoặc chỉ số khác tùy anh) --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-none border-0 bg-label-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading fw-medium">Hoạt Động</span>
                        <h4 class="mb-0 my-1">Active</h4>
                        <p class="mb-0 small text-success">Đội ngũ sẵn sàng</p>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti tabler-user-check fs-3"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Biểu đồ và danh sách (Dòng dưới) --}}
<div class="row g-6">
    <div class="col-xl-8 col-12 mt-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Biểu Đồ Thống Kê Bác sĩ</h5>
                    <small class="text-muted">7 ngày gần nhất</small>
                </div>
                <div id="doctorChart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-12 mt-2">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title mb-0">Bác sĩ mới gia nhập</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-flush mt-2">
                        <tbody>
                            @forelse($latestDoctors as $doc)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="{{ $doc->image ? \Storage::url($doc->image) : asset('assets/img/avatars/1.png') }}" class="rounded-circle border">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-heading small">{{ $doc->name }}</span>
                                            <small class="text-muted" style="font-size: 11px">{{ $doc->specialty }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0 text-end">
                                    <span class="badge bg-label-secondary small" style="font-size: 10px">{{ $doc->created_at->format('d/m') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td class="text-center p-4">Chưa có dữ liệu</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
