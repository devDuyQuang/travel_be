<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::query()
            ->where('is_active', 1)
            ->withCount([
                'posts as posts_count' => fn ($query) => $query->where('status', 1),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'sort_order', 'is_active']);

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $tags,
        ]);
    }
}
