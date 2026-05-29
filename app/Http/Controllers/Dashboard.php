<?php

namespace App\Http\Controllers;

use App\Models\{Doctor, Post, Comment, User, Role, Permission};
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Logic phân quyền (dùng 'code' như mình đã tìm ra lúc nãy)
        $roleCode = $user->role['code'] ?? $user->role ?? '';
        $canViewStats = (strtolower(trim($roleCode)) === 'admin');

        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();
        $startOfWeek = $now->copy()->startOfWeek();

        // --- 1. KHỞI TẠO DỮ LIỆU BIỂU ĐỒ 7 NGÀY ---
        $last7DaysLabels = [];
        // $growthMapping = [];//post
        // $doctorGrowth = [];
        // $commentGrowth = [];
        // $userGrowth = [];
        // $roleGrowth = [];
        $growth = [
            'post' => [], 'doctor' => [], 'comment' => [], 'user' => [], 'role' => []
        ];


        // for ($i = 6; $i >= 0; $i--) {
        //     $date = now()->subDays($i);
        //     $dateKey = $date->format('Y-m-d');
        //     $last7DaysLabels[] = $date->format('d/m');
        //     $growthMapping[$dateKey] = 0; //post
        //     $doctorGrowth[$dateKey] = 0;
        //     $commentGrowth[$dateKey] = 0;
        //     $userGrowth[$dateKey] = 0;
        //     $roleGrowth[$dateKey] = 0;
        // }
        for ($i = 6; $i >= 0; $i--) {
            $dateKey = $now->copy()->subDays($i)->format('Y-m-d');
            $last7DaysLabels[] = $now->copy()->subDays($i)->format('d/m');
            foreach ($growth as $key => $val) { $growth[$key][$dateKey] = 0; }
        }

        // --- 2. TRUY VẤN DỮ LIỆU ---
        // $postQuery = Post::query();

        // // Thống kê tăng trưởng bài viết
        // $postStats = Post::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        //     ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        //     ->groupBy('date')
        //     ->pluck('count', 'date');

        // foreach ($postStats as $date => $count) {
        //     if (isset($growthMapping[$date])) {
        //         $growthMapping[$date] = (int)$count;
        //     }
        // }

        // // --- 2. THỐNG KÊ DOCTOR ---
        // // Tăng trưởng bác sĩ
        // $doctorDaily = Doctor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        //     ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        //     ->groupBy('date')
        //     ->pluck('count', 'date');

        // foreach ($doctorDaily as $date => $count) {
        //     if (isset($doctorGrowth[$date])) $doctorGrowth[$date] = (int)$count;
        // }

        // // Tăng trưởng bình luận
        // $commentDaily = Comment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        // ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        // ->groupBy('date')
        // ->pluck('count', 'date');

        // foreach ($commentDaily as $date => $count) {
        //     if (isset($commentGrowth[$date])) $commentGrowth[$date] = (int)$count;
        // }
        // // Tăng trưởng người dùng
        // $userDaily = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        //     ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        //     ->groupBy('date')
        //     ->pluck('count', 'date');

        // foreach ($userDaily as $date => $count) {
        //     if (isset($userGrowth[$date])) $userGrowth[$date] = (int)$count;
        // }

        // // Tăng trưởng vai trò
        // $roleDaily = Role::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        //     ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        //     ->groupBy('date')
        //     ->pluck('count', 'date');

        // foreach ($roleDaily as $date => $count) {
        //     if (isset($roleGrowth[$date])) $roleGrowth[$date] = (int)$count;
        // }


        // --- 2. TRUY VẤN TĂNG TRƯỞNG (Gộp Query để tối ưu RAM) ---
        $this->fillGrowthData(Post::query(), $sevenDaysAgo, $growth['post']);
        $this->fillGrowthData(Doctor::query(), $sevenDaysAgo, $growth['doctor']);
        $this->fillGrowthData(Comment::query(), $sevenDaysAgo, $growth['comment']);
        $this->fillGrowthData(User::query(), $sevenDaysAgo, $growth['user']);
        $this->fillGrowthData(Role::query(), $sevenDaysAgo, $growth['role']);


        // --- 3. TỔNG HỢP STATS (Khớp với cột trong Model Post) ---
        // $stats = [
        //     'posts' => [
        //         'total'     => number_format((clone $postQuery)->count(), 0, ',', '.'),
        //         'active'    => (clone $postQuery)->active()->count(), // Dùng scopeActive trong Model
        //         'inactive'  => (clone $postQuery)->where('status', '!=', 1)->count(),
        //         'this_week' => (clone $postQuery)->where('created_at', '>=', $startOfWeek)->count(),
        //         'views'     => number_format((clone $postQuery)->sum('views'), 0, ',', '.'),
        //         'favs'      => number_format((clone $postQuery)->sum('favorites'), 0, ',', '.'),
        //     ],
        //         'doctors' => [
        //         'total'      => Doctor::count(),
        //         'this_week'  => Doctor::where('created_at', '>=', $startOfWeek)->count(),
        //         'specialties' => Doctor::distinct('specialty')->count('specialty'),
        //     ],
        //     'comments' => [
        //         'total'    => Comment::count(),
        //         'pending'  => Comment::where('status', 'pending')->count(),
        //         'root'     => Comment::whereNull('parent_id')->count(),
        //         'replies'  => Comment::whereNotNull('parent_id')->count(),
        //     ],
        //     'users' => [
        //         'total'    => User::count(),
        //         'this_week'=> User::where('created_at', '>=', now()->startOfWeek())->count(),
        //         'admins'   => User::whereHas('role', fn($q) => $q->where('code', 'admin'))->count(),
        //         'members'  => User::whereHas('role', fn($q) => $q->where('code', '!=', 'admin'))->count(),
        //     ],
        //     'roles' => [
        //         'total'       => Role::count(),
        //         'active'      => Role::where('status', 1)->count(),
        //         'permissions' => Permission::where('status', 1)->count(),
        //         'this_week'   => Role::where('created_at', '>=', now()->startOfWeek())->count(),
        //     ],
        // ];

        // $chartData = [
        //     'post_growth' => array_values($growthMapping),
        //     'doctor_growth' => array_values($doctorGrowth),
        //     'comment_growth' => array_values($commentGrowth),
        //     'user_growth' => array_values($userGrowth),
        //     'role_growth' => array_values($roleGrowth),
        // ];


        $stats = [
            'posts' => [
                'total'     => number_format(Post::count(), 0, ',', '.'),
                'active'    => Post::where('status', 1)->count(),
                'inactive'  => Post::where('status', '!=', 1)->count(),
                'this_week' => Post::where('created_at', '>=', $startOfWeek)->count(),
                'views'     => number_format(Post::sum('views'), 0, ',', '.'),
                'favs'      => number_format(Post::sum('favorites'), 0, ',', '.'),
            ],
            'doctors' => [
                'total'      => Doctor::count(),
                'this_week'  => Doctor::where('created_at', '>=', $startOfWeek)->count(),
                'specialties' => Doctor::distinct('specialty')->count('specialty'),
            ],
            'comments' => [
                'total'    => Comment::count(),
                'pending'  => Comment::where('status', 'pending')->count(),
                'root'     => Comment::whereNull('parent_id')->count(),
                'replies'  => Comment::whereNotNull('parent_id')->count(),
            ],
            'users' => [
                'total'    => User::count(),
                'this_week'=> User::where('created_at', '>=', $startOfWeek)->count(),
                'admins'   => User::whereHas('role', fn($q) => $q->where('code', 'admin'))->count(),
                'members'  => User::whereHas('role', fn($q) => $q->where('code', '!=', 'admin'))->count(),
            ],
            'roles' => [
                'total'       => Role::count(),
                'active'      => Role::where('status', 1)->count(),
                'permissions' => Permission::where('status', 1)->count(),
                'this_week'   => Role::where('created_at', '>=', $startOfWeek)->count(),
            ],
        ];


        // --- 4. DANH SÁCH BÀI VIẾT ---
        // Lấy 5 bài mới cập nhật nhất, kèm thông tin người tạo
        // $latestPosts = Post::with('creator')->orderByDesc('updated_at')->take(5)->get();
        // $latestDoctors = Doctor::with('user')->orderByDesc('created_at')->take(5)->get();
        // $latestComments = Comment::with('commentable')
        // ->orderByDesc('created_at')
        // ->take(6)
        // ->get();
        // $latestUsers = \App\Models\User::with('role')
        // ->orderByDesc('created_at')
        // ->take(6)
        // ->get();
        // $latestRoles = \App\Models\Role::withCount(['users', 'permissions'])
        // ->orderByDesc('created_at')
        // ->take(6)
        // ->get();

        // // Top bài viết theo Scope trong Model
        // $topViewedPosts = Post::mostViewed()->take(5)->get();
        // $topFavoritedPosts = Post::mostFavorited()->take(5)->get();

        // --- 4. DANH SÁCH RÚT GỌN (Tối ưu: Chỉ select các cột cần thiết) ---
        $latestPosts = Post::with('creator:id,name')
        ->select('id', 'name', 'created_by', 'updated_at', 'status')
        ->latest('updated_at')
        ->take(5)
        ->get();

        $latestDoctors = Doctor::select('id','name','specialty','image','created_at')
            ->latest()->take(5)->get();

        $latestComments = Comment::select('id','content','name','commentable_type','commentable_id','status','created_at')
            ->latest()->take(6)->get();

        $latestUsers = User::with('role:id,name')
            ->select('id','name','email','role_id','created_at')
            ->latest()->take(6)->get();

        $latestRoles = Role::withCount(['users', 'permissions'])
            ->latest()->take(6)->get();

        $topViewedPosts = Post::select('id', 'name', 'views')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        $topFavoritedPosts = Post::select('id', 'name', 'favorites')
            ->orderByDesc('favorites')
            ->take(5)
            ->get();

        // return view('dashboard.main', compact(
        //     'stats',
        //     'chartData',
        //     'latestPosts',
        //     'latestDoctors',
        //     'latestComments',
        //     'latestUsers',
        //     'latestRoles',
        //     'topViewedPosts',
        //     'topFavoritedPosts',
        //     'last7DaysLabels',
        //     'canViewStats'
        // ));


        return view('dashboard.main', [
            'stats' => $stats,
            'chartData' => [
                'post_growth'    => array_values($growth['post']),
                'doctor_growth'  => array_values($growth['doctor']),
                'comment_growth' => array_values($growth['comment']),
                'user_growth'    => array_values($growth['user']),
                'role_growth'    => array_values($growth['role']),
            ],
            'latestPosts' => $latestPosts,
            'latestDoctors' => $latestDoctors,
            'latestComments' => $latestComments,
            'latestUsers' => $latestUsers,
            'latestRoles' => $latestRoles,
            'topViewedPosts' => $topViewedPosts,
            'topFavoritedPosts' => $topFavoritedPosts,
            'last7DaysLabels' => $last7DaysLabels,
            'canViewStats' => $canViewStats
        ]);

    }

    /**
     * Hàm helper để điền dữ liệu tăng trưởng tránh lặp code và tiết kiệm RAM
     */
    private function fillGrowthData($query, $startDate, &$targetArray)
    {
        $results = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        foreach ($results as $date => $count) {
            if (isset($targetArray[$date])) {
                $targetArray[$date] = (int)$count;
            }
        }
    }


    /**
     * Xuất báo cáo bài viết dạng CSV.
     */
    public function exportPosts(): StreamedResponse
    {
        $fileName = 'posts-report-' . Carbon::now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // BOM cho Excel đọc unicode tốt hơn
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['ID', 'Tiêu đề', 'Trạng thái', 'Lượt xem', 'Lượt yêu thích', 'Tác giả', 'Ngày tạo']);

            Post::with('creator')
                ->orderByDesc('created_at')
                ->chunk(200, function ($posts) use ($handle) {
                    foreach ($posts as $post) {
                        fputcsv($handle, [
                            $post->id,
                            $post->name,
                            $post->status ? 'Hiển thị' : 'Ẩn',
                            $post->views,
                            $post->favorites,
                            optional($post->creator)->name ?? 'Hệ thống',
                            optional($post->created_at)?->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
