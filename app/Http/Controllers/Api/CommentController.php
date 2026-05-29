<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Lấy danh sách bình luận đã duyệt của bài viết
     */
    public function index(Request $request)
    {
        $postId = $request->query('post_id');

        if (!$postId) {
            return response()->json(['message' => 'Thiếu post_id'], 400);
        }

        // Đếm tổng số bình luận đã duyệt (cả cha lẫn con)
        $totalCount = Comment::where('commentable_id', $postId)
            ->where('commentable_type', Post::class)
            ->approved()
            ->count();

        $comments = Comment::where('commentable_id', $postId)
            ->where('commentable_type', Post::class)
            ->root()
            ->approved()
            ->with(['replies' => function($query) {
                $query->approved(); // Chỉ lấy các reply đã duyệt
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Dùng paginate để load mượt hơn

        return response()->json([
            'data' => $comments->items(), // Lấy mảng dữ liệu
            'total' => $totalCount,       // Tổng số để hiện lên tiêu đề
            'current_page' => $comments->currentPage(),
            'last_page' => $comments->lastPage(),
        ]);
    }

    /**
     * Lưu bình luận mới
     */
    public function store(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'post_id'   => 'required|exists:posts,id',
            'author'    => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'content'   => 'required|string|min:5',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Tạo bình luận mới dựa trên cấu trúc bảng thực tế của anh
        $comment = Comment::create([
            'content'          => $request->content,
            'name'             => $request->author, // Map 'author' từ NextJS sang 'name' trong DB
            'email'            => $request->email,
            'status'           => Comment::STATUS_PENDING, // Mặc định chờ duyệt
            'commentable_id'   => $request->post_id,
            'commentable_type' => Post::class, // Định danh model Post
            'parent_id'        => $request->parent_id,
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Cảm ơn anh! Bình luận đã được gửi và đang chờ quản trị viên duyệt.',
            'data'    => $comment
        ], 201);

        } catch (\Exception $e) {
            // Ghi lỗi ra log để anh check trong storage/logs/laravel.log
            Log::error('Comment Store Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ], 500);
        }
    }
}
