<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return view('tag.main', compact('tags'));
    }

    public function create()
    {
        return view('tag.form', ['tag' => new Tag()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        Tag::create($data);

        return redirect()->to(panel_route('tag.index'))->with('success', 'Đã tạo thẻ bài viết.');
    }

    public function edit($domain, Tag $tag)
    {
        return view('tag.form', compact('tag'));
    }

    public function update(Request $request, $domain, Tag $tag)
    {
        $data = $this->validateData($request, $tag->id);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['name'], $tag->id);
        $data['is_active'] = $request->boolean('is_active', true);
        $tag->update($data);

        return redirect()->to(panel_route('tag.index'))->with('success', 'Đã cập nhật thẻ bài viết.');
    }

    public function destroy($domain, Tag $tag)
    {
        if ($tag->posts()->exists()) {
            $tag->update(['is_active' => false]);
            return back()->with('success', 'Thẻ đang được sử dụng nên đã được tắt trạng thái.');
        }

        $tag->delete();
        return back()->with('success', 'Đã xóa thẻ bài viết.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 1;

        while (Tag::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
