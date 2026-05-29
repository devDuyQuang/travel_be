<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Support\Facades\Storage;

class DegreeController extends Controller
{
    public function index()
    {
        $items = Degree::query()
            ->select(['id', 'name', 'image', 'description', 'link_text', 'year', 'created_at'])
            ->orderByDesc('id')
            ->get()
            ->map(function (Degree $degree) {
                return [
                    'id' => $degree->id,
                    'name' => $degree->name,
                    'image' => $degree->image ? Storage::url($degree->image) : null,
                    'description' => $degree->description,
                    'link_text' => $degree->link_text,
                    'year' => $degree->year,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => $items,
        ]);
    }
}
