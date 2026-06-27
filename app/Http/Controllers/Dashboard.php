<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Controller
{
    public function index()
    {
        $roleCode = auth()->user()?->role?->code ?? '';
        $canViewStats = strtolower(trim((string) $roleCode)) === 'admin';
        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(6)->startOfDay();
        $startOfWeek = $now->copy()->startOfWeek();

        $labels = [];
        $growth = [
            'post' => [],
            'comment' => [],
            'user' => [],
            'role' => [],
        ];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = $now->copy()->subDays($daysAgo);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');

            foreach (array_keys($growth) as $key) {
                $growth[$key][$dateKey] = 0;
            }
        }

        $this->fillGrowthData(Post::query(), $sevenDaysAgo, $growth['post']);
        $this->fillGrowthData(Comment::query(), $sevenDaysAgo, $growth['comment']);
        $this->fillGrowthData(User::query(), $sevenDaysAgo, $growth['user']);
        $this->fillGrowthData(Role::query(), $sevenDaysAgo, $growth['role']);

        return view('dashboard.main', [
            'stats' => [
                'posts' => [
                    'total' => number_format(Post::count(), 0, ',', '.'),
                    'active' => Post::where('status', 1)->count(),
                    'inactive' => Post::where('status', '!=', 1)->count(),
                    'this_week' => Post::where('created_at', '>=', $startOfWeek)->count(),
                    'views' => number_format(Post::sum('views'), 0, ',', '.'),
                    'favs' => number_format(Post::sum('favorites'), 0, ',', '.'),
                ],
                'comments' => [
                    'total' => Comment::count(),
                    'pending' => Comment::where('status', 'pending')->count(),
                    'root' => Comment::whereNull('parent_id')->count(),
                    'replies' => Comment::whereNotNull('parent_id')->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'this_week' => User::where('created_at', '>=', $startOfWeek)->count(),
                    'admins' => User::whereHas('role', fn ($query) => $query->where('code', 'admin'))->count(),
                    'members' => User::whereHas('role', fn ($query) => $query->where('code', '!=', 'admin'))->count(),
                ],
                'roles' => [
                    'total' => Role::count(),
                    'active' => Role::where('status', 1)->count(),
                    'permissions' => Permission::where('status', 1)->count(),
                    'this_week' => Role::where('created_at', '>=', $startOfWeek)->count(),
                ],
            ],
            'chartData' => [
                'post_growth' => array_values($growth['post']),
                'comment_growth' => array_values($growth['comment']),
                'user_growth' => array_values($growth['user']),
                'role_growth' => array_values($growth['role']),
            ],
            'latestPosts' => Post::with('creator:id,name')
                ->select('id', 'name', 'created_by', 'updated_at', 'status')
                ->latest('updated_at')
                ->take(5)
                ->get(),
            'latestComments' => Comment::select(
                'id',
                'content',
                'name',
                'commentable_type',
                'commentable_id',
                'status',
                'created_at'
            )->latest()->take(6)->get(),
            'latestUsers' => User::with('role:id,name')
                ->select('id', 'name', 'email', 'role_id', 'created_at')
                ->latest()
                ->take(6)
                ->get(),
            'latestRoles' => Role::withCount(['users', 'permissions'])->latest()->take(6)->get(),
            'topViewedPosts' => Post::select('id', 'name', 'views')->orderByDesc('views')->take(5)->get(),
            'topFavoritedPosts' => Post::select('id', 'name', 'favorites')->orderByDesc('favorites')->take(5)->get(),
            'last7DaysLabels' => $labels,
            'canViewStats' => $canViewStats,
        ]);
    }

    private function fillGrowthData(Builder $query, Carbon $startDate, array &$target): void
    {
        $results = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('count', 'date');

        foreach ($results as $date => $count) {
            if (array_key_exists($date, $target)) {
                $target[$date] = (int) $count;
            }
        }
    }

    public function exportPosts(): StreamedResponse
    {
        $fileName = 'posts-report-' . Carbon::now()->format('Ymd_His') . '.csv';

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
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
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
