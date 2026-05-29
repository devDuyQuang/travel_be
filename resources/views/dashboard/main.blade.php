@extends('index')
@section('title', 'Bảng Điều Khiển')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="card-title mb-3 text-md-start text-center pb-md-0 pb-6 d-flex align-items-center gap-2 flex-wrap">
        <i class="icon-base ti tabler-smart-home"></i>
    Bảng Điều Khiển

    </h5>

@php
    $user = auth()->user();
    // Chuyển toàn bộ thông tin role thành chuỗi để tìm chữ 'admin'
    $roleData = json_encode($user->role ?? '');


    $canPost = str_contains(strtolower($roleData), 'admin');
    $activeTab = $canPost ? 'posts' : '';
    $canDoctor = str_contains(strtolower($roleData), 'admin');
    $canComment = str_contains(strtolower($roleData), 'admin');
    $canUsers = str_contains(strtolower($roleData), 'admin');
    $canRoles = str_contains(strtolower($roleData), 'admin');
@endphp


   <div class="nav-align-top mb-6">
        <div class="nav-tabs-wrapper mb-8 pt-7">
            <ul class="nav nav-tabs shadow-none border-bottom flex-nowrap" role="tablist" style="overflow-x: auto; overflow-y: hidden;">
                @if($canPost)
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'posts' ? 'active' : '' }} text-nowrap d-flex align-items-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-posts">
                        <i class="ti tabler-news me-1"></i> Quản Lý Bài Viết
                        <span class="badge rounded-pill bg-label-dark ms-2">{{ $stats['posts']['total'] }}</span>
                    </button>
                </li>
                @endif

                @if($canDoctor)
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'doctors' ? 'active' : '' }} text-nowrap d-flex align-items-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-doctors">
                        <i class="ti tabler-stethoscope me-1"></i> Thông Tin Bác Sĩ
                        <span class="badge rounded-pill bg-label-dark ms-2">{{ $stats['doctors']['total'] }}</span>
                    </button>
                </li>
                @endif

                @if($canComment)
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'comments' ? 'active' : '' }} text-nowrap d-flex align-items-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-comments">
                        <i class="ti tabler-messages me-1"></i> Quản Lý Bình Luận
                        <span class="badge rounded-pill bg-label-dark ms-2">{{ $stats['comments']['total'] }}</span>
                    </button>
                </li>
                @endif

                @if($canUsers)
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'users' ? 'active' : '' }} text-nowrap d-flex align-items-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-users">
                        <i class="ti tabler-users me-1"></i> Quản Lý Người Dùng
                        <span class="badge rounded-pill bg-label-dark ms-2">{{ $stats['users']['total'] }}</span>
                    </button>
                </li>
                @endif

                @if($canRoles)
                <li class="nav-item">
                    <button type="button" class="nav-link {{ $activeTab === 'roles' ? 'active' : '' }} text-nowrap d-flex align-items-center" role="tab" data-bs-toggle="tab" data-bs-target="#tab-roles">
                        <i class="ti tabler-shield me-1"></i> Quản Lý Vai Trò
                        <span class="badge rounded-pill bg-label-dark ms-2">{{ $stats['roles']['total'] }}</span>
                    </button>
                </li>
                @endif

            </ul>
        </div>

      <div class="tab-content bg-transparent border-0 shadow-none px-0 py-0">

        @if($canPost)
            <div class="tab-pane show {{ $activeTab === 'posts' ? 'active' : '' }}" id="tab-posts" role="tabpanel">
                @include('dashboard.tabs.posts')
            </div>
        @endif

        @if($canDoctor)
            <div class="tab-pane show {{ $activeTab === 'doctors' ? 'active' : '' }}" id="tab-doctors" role="tabpanel">
                @include('dashboard.tabs.doctors')
            </div>
        @endif

        @if($canComment)
            <div class="tab-pane show {{ $activeTab === 'comments' ? 'active' : '' }}" id="tab-comments" role="tabpanel">
                @include('dashboard.tabs.comments')
            </div>
        @endif

        @if($canUsers)
            <div class="tab-pane show {{ $activeTab === 'users' ? 'active' : '' }}" id="tab-users" role="tabpanel">
                @include('dashboard.tabs.users')
            </div>
        @endif

        @if($canRoles)
            <div class="tab-pane show {{ $activeTab === 'roles' ? 'active' : '' }}" id="tab-roles" role="tabpanel">
                @include('dashboard.tabs.roles')
            </div>
        @endif
      </div>
   </div>
</div>

<style>
   .bg-label-primary {
      background-color: rgba(115, 103, 240, 0.08) !important;
      color: #7367f0 !important;
   }

   .bg-label-success {
      background-color: rgba(40, 199, 111, 0.08) !important;
      color: #28c76f !important;
   }

   .bg-label-info {
      background-color: rgba(0, 207, 221, 0.08) !important;
      color: #00cfdd !important;
   }

   .bg-label-warning {
      background-color: rgba(255, 159, 67, 0.08) !important;
      color: #ff9f43 !important;
   }

   .bg-label-danger {
      background-color: rgba(234, 84, 85, 0.08) !important;
      color: #ea5455 !important;
   }

   .btn-white {
      background: white;
      border: none;
      transition: 0.3s;
   }

   .btn-white:hover {
      background: #f8f9fa;
      transform: translateY(-5px);
   }

   .hover-up:hover {
      transform: translateY(-5px);
      transition: 0.3s;
   }



   /* 1. Đảm bảo các tab không xuống hàng và có thể kéo ngang */
    .nav-tabs {
        display: flex;
        flex-wrap: nowrap !important;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch; /* Hỗ trợ vuốt mượt trên iPhone/Android */
    }

    /* 2. Ép các button tab không bị co lại quá hẹp */
    .nav-tabs .nav-link {
        flex-shrink: 0;
    }

    /* 3. Tùy chọn: Ẩn thanh cuộn xấu xí nhưng vẫn kéo được (nhìn giống mobile app) */
    .nav-tabs::-webkit-scrollbar {
        height: 3px; /* Độ cao thanh cuộn nhỏ thôi */
    }
    .nav-tabs::-webkit-scrollbar-thumb {
        background: #5a5a5a; /* Màu thanh cuộn khớp với theme */
        border-radius: 10px;
    }
    .nav-tabs::-webkit-scrollbar-track {
        background: transparent;
    }

    .nav-tabs .nav-link .badge {
    font-size: 0.65rem; /* Nhỏ hơn chữ tiêu đề một chút */
    padding: 0.25em 0.5em;
    font-weight: 500;
    }
    /* Làm mờ nhẹ badge khi tab không được chọn */
    .nav-tabs .nav-link:not(.active) .badge {
        opacity: 0.8;
    }
</style>
@endsection


@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cấu hình màu sắc chung
    const labelColor = '#a1acb8';
    const borderColor = '#f1f1f1';

    // 1. Lấy dữ liệu từ Controller
    const dailyLabels = {!! json_encode($last7DaysLabels) !!};
    const postData = {!! json_encode($chartData['post_growth'] ?? []) !!};
    const doctorData = {!! json_encode($chartData['doctor_growth'] ?? []) !!};

    /**
     * Hàm tạo cấu hình biểu đồ Area chuẩn
     */
    function getAreaConfig(name, data, color, categories) {
        return {
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false },
                parentHeightOffset: 0
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            series: [{ name: name, data: data }],
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: labelColor, fontSize: '13px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: labelColor, fontSize: '13px' },
                    formatter: (val) => Math.floor(val)
                },
                tickAmount: 4
            },
            colors: [color],
            grid: {
                borderColor: borderColor,
                strokeDashArray: 7,
                padding: { top: -20, bottom: -10 }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1, opacityFrom: 0.6, opacityTo: 0.1, stops: [0, 90, 100]
                }
            }
        };
    }

    // 2. Khởi tạo Chart Bài Viết
    const postChartEl = document.querySelector('#postGrowthChart');
    if (postChartEl) {
        new ApexCharts(postChartEl, getAreaConfig('Bài viết mới', postData, '#7367f0', dailyLabels)).render();
    }
    // 3. Khởi tạo Biểu đồ Bác Sĩ (Màu Cyan - Info)
    const doctorChartEl = document.querySelector('#doctorChart');
    if (doctorChartEl) {
        const doctorChart = new ApexCharts(doctorChartEl, getAreaConfig('Bác sĩ mới', doctorData, '#7367f0', dailyLabels));
        doctorChart.render();
    }

    // 4. Khởi tạo Chart Bình luận (Màu Đỏ Cam - Danger/Warning)
    const commentChartEl = document.querySelector('#commentGrowthChart');
    if (commentChartEl) {
        const commentChart = new ApexCharts(commentChartEl, getAreaConfig('Bình luận mới', {!! json_encode($chartData['comment_growth'] ?? []) !!}, '#7367f0', dailyLabels));
        commentChart.render();
    }

    // Khởi tạo Chart User (Màu Indigo/Tím đậm)
    const userChartEl = document.querySelector('#userGrowthChart');
    if (userChartEl) {
        const userChart = new ApexCharts(userChartEl, getAreaConfig('User mới', {!! json_encode($chartData['user_growth'] ?? []) !!}, '#7367f0', dailyLabels));
        userChart.render();
    }

    // Khởi tạo Chart Role (Màu Dark/Grey - thể hiện sự vững chãi)
    const roleChartEl = document.querySelector('#roleGrowthChart');
    if (roleChartEl) {
        const roleChart = new ApexCharts(roleChartEl, getAreaConfig('Vai trò mới', {!! json_encode($chartData['role_growth'] ?? []) !!}, '#7367f0', dailyLabels));
        roleChart.render();
    }


    // Fix lỗi hiển thị khi chuyển Tab
    const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            window.dispatchEvent(new Event('resize'));
        });
    });
});
</script>
@endpush
