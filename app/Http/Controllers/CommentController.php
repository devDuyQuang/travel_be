<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new Comment();
    }

    /**
     * Trang danh sách bình luận trong admin
     */
    public function index()
    {
        return view(module() . '.main'); // Ví dụ: resources/views/admin/comment/main.blade.php
    }

    /**
     * Datatable Ajax cho danh sách bình luận
     */
    public function datatable(Request $request)
    {
        $perPage = $request->get('length', 25);
        $page    = $request->get('start', 0) / $perPage + 1;

        $query = Comment::query()
            ->with(['commentable', 'parent', 'approver', 'replies'])
            ->whereNull('parent_id')
            ->select('comments.*')
            ->orderByDesc('created_at');

        // Tìm kiếm
        if ($search = $request->get('search')['value'] ?? '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Lọc theo loại nội dung (nếu cần)
        if ($type = $request->get('type')) {
            $query->where('commentable_type', $type);
        }

        $total   = $query->count();
        $items   = $query->forPage($page, $perPage)->get();

        $rows = $items->map(function ($c) {
            // $parentName = $c->parent?->name ?? '';
            // $objectTitle = $c->commentable?->name ?? $c->commentable?->title ?? '(Đã xóa)';
            // 2. Chuẩn bị dữ liệu replies cho cột __details
            // Chúng ta sẽ trả về mảng để JS Render xử lý giao diện
            $repliesData = $c->replies->map(function($reply) {
            return [
                    'name'       => $reply->name,
                    'content'    => $reply->content,
                    'created_at' => optional($reply->created_at)->format('d/m/Y H:i'),
                ];
            });

            return [
                'id'           => $c->id,
                'name'         => $c->name,
                'email'        => $c->email ?? '-',
                //'content'      => $c->short_content,
                'content' => $c->content, // Thay vì short_content để Modal hiện đủ ý
                'short_content' => $c->short_content, // Nếu anh vẫn muốn dùng short_content cho bảng ngoài
                'object'      => ($c->commentable?->name ?? $c->commentable?->title ?? 'Bài viết đã xóa') . ($c->isReply() ? " (Trả lời: {$c->parent?->name})" : ''),
                'status'       => $c->status,
                'status_text' => [
                    'pending'  => 'Chờ duyệt',
                    'approved' => 'Đã duyệt',
                    'hidden'   => 'Đã ẩn',
                ][$c->status] ?? $c->status,
                'created_at'   => optional($c->created_at)->format('d/m/Y H:i'),
                'approved_at'  => optional($c->approved_at)->format('d/m/Y H:i') ?? '-',
                'creator'      => $c->display_name ?? $c->name ?? '—',
                // 3. Đưa mảng replies vào __details
                'replies'     => $repliesData,
                '__details'   => '', // Để trống vì JS render sẽ dùng mảng 'replies' ở trên
                'actions'     => '',
            ];
        });

        return response()->json([
            'draw'            => (int)$request->get('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows,
        ]);
    }

    /**
     * Duyệt bình luận (đổi status thành approved)
     */
public function approve($domain,$id) {
    $comment = Comment::find($id);

    if (!$comment) {
        return response()->json([
            'success' => false,
            'message' => "Không tìm thấy bình luận có ID: " . $id
        ], 404);
    }

    $comment->update(['status' => 'approved']);
    return response()->json(['success' => true, 'message' => 'Duyệt thành công']);
}

    /**
     * Ẩn bình luận (spam hoặc không phù hợp)
     */
    public function hide(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        DB::beginTransaction();
        try {
            $comment->markAsHidden();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã ẩn bình luận.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Hide comment failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi ẩn.'], 500);
        }
    }

    /**
     * Trả lời bình luận từ admin
     */
    public function reply(Request $request, $domain, $id)
    {
        $parent = Comment::findOrFail($id);

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        DB::beginTransaction();
        try {
            $reply = new Comment();
            $reply->content           = $request->content;
            $reply->name              = auth()->user()->name ?? 'Admin';
            $reply->email             = auth()->user()->email ?? null;
            $reply->status            = Comment::STATUS_APPROVED;
            $reply->commentable_id    = $parent->commentable_id;
            $reply->commentable_type  = $parent->commentable_type;
            $reply->parent_id         = $parent->id;
            $reply->approved_by       = auth()->id();
            $reply->approved_at       = now();
            $reply->ip_address        = $request->ip();
            $reply->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã trả lời bình luận.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Reply comment failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi trả lời.'], 500);
        }
    }

    /**
     * Xóa bình luận (soft delete)
     */
    public function destroy($domain, $id)
    {
         $comment = Comment::findOrFail($id);

        if (!$comment) {
            return response()->json([
                'status' => 'error',
                'message' => "Không tìm thấy Comment với ID: $id"
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa thành công!'
        ]);
    }

    /**
     * Xử lý hành động hàng loạt (bulk actions)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer|exists:comments,id',
            'action' => 'required|in:approve,hide,delete',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        DB::beginTransaction();
        try {
            $comments = Comment::whereIn('id', $ids)->get();

            foreach ($comments as $comment) {
                switch ($action) {
                    case 'approve':
                        $comment->markAsApproved(auth()->id());
                        break;
                    case 'hide':
                        $comment->markAsHidden();
                        break;
                    case 'delete':
                        $comment->delete();
                        break;
                }
            }

            DB::commit();

            $messages = [
                'approve' => 'Đã duyệt các bình luận được chọn.',
                'hide'    => 'Đã ẩn các bình luận được chọn.',
                'delete'  => 'Đã xóa các bình luận được chọn.',
            ];

            return response()->json(['success' => true, 'message' => $messages[$action]]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Bulk comment action failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi thực hiện.'], 500);
        }
    }
}