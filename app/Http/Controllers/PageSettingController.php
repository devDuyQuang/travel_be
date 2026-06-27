<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageSettingController extends Controller
{
    private array $pages = [
        'blog' => ['key' => 'blog_page_travel', 'title' => 'Tin tức'],
        'shop' => ['key' => 'shop_page_travel', 'title' => 'Cửa hàng'],
        'faq' => ['key' => 'faq_page_travel', 'title' => 'Câu hỏi thường gặp'],
    ];

    public function edit(string $page)
    {
        abort_unless(isset($this->pages[$page]), 404);
        $config = $this->pages[$page];
        $setting = Setting::firstOrCreate(['key' => $config['key']], ['value' => []]);
        $value = $setting->value ?? [];
        $bannerUrl = !empty($value['banner'])
            ? (function_exists('normalize_image_url') ? normalize_image_url($value['banner'], 'setting') : $value['banner'])
            : null;
        $ogImageUrl = !empty($value['og_image'])
            ? (function_exists('normalize_image_url') ? normalize_image_url($value['og_image'], 'setting') : $value['og_image'])
            : null;

        return view('setting.page-settings', compact('page', 'config', 'setting', 'value', 'bannerUrl', 'ogImageUrl'));
    }

    public function update(Request $request, string $page)
    {
        abort_unless(isset($this->pages[$page]), 404);
        $config = $this->pages[$page];
        $setting = Setting::firstOrCreate(['key' => $config['key']], ['value' => []]);
        $old = $setting->value ?? [];

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'breadcrumb' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'show_search' => ['nullable', 'boolean'],
            'show_category' => ['nullable', 'boolean'],
            'show_recent_posts' => ['nullable', 'boolean'],
            'show_tags' => ['nullable', 'boolean'],
            'search_title' => ['nullable', 'string', 'max:255'],
            'category_title' => ['nullable', 'string', 'max:255'],
            'recent_posts_title' => ['nullable', 'string', 'max:255'],
            'tags_title' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'canonical' => ['nullable', 'string', 'max:500'],
            'banner_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'og_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_banner' => ['nullable', 'boolean'],
            'remove_og_image' => ['nullable', 'boolean'],
        ]);

        $payload = array_merge($old, $data);
        foreach (['show_search', 'show_category', 'show_recent_posts', 'show_tags'] as $flag) {
            $payload[$flag] = $request->boolean($flag, true);
        }

        foreach (['banner' => 'banner_file', 'og_image' => 'og_image_file'] as $field => $fileKey) {
            if ($request->hasFile($fileKey)) {
                $payload[$field] = $this->storeImage($request, $page, $fileKey);
                if (!empty($old[$field]) && Storage::disk('public')->exists($old[$field])) {
                    Storage::disk('public')->delete($old[$field]);
                }
            } elseif ($request->boolean('remove_' . $field)) {
                if (!empty($old[$field]) && Storage::disk('public')->exists($old[$field])) {
                    Storage::disk('public')->delete($old[$field]);
                }
                $payload[$field] = null;
            }
        }

        unset($payload['banner_file'], $payload['og_image_file'], $payload['remove_banner'], $payload['remove_og_image']);

        $setting->update(['value' => $payload]);

        return back()->with('success', 'Đã lưu cấu hình trang ' . $config['title'] . '.');
    }

    private function storeImage(Request $request, string $page, string $key): string
    {
        $file = $request->file($key);
        $domain = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($request->route('domain') ?? 'default'));
        $filename = $page . '_' . Str::before($key, '_file') . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs("uploads/{$domain}/settings", $filename, 'public');
    }
}
