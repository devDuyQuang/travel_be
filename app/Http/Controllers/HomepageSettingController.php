<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomepageSettingController extends Controller
{
    private const SECTIONS = [
        'hero' => ['label' => 'Banner chính', 'key' => 'hero_home'],
        'search' => ['label' => 'Tìm kiếm dịch vụ', 'key' => 'search_home'],
        'about' => ['label' => 'Giới thiệu WAYLUNE', 'key' => 'about_home'],
        'featured' => ['label' => 'Sản phẩm nổi bật', 'key' => 'featured_products_home'],
        'why_choose_us' => ['label' => 'Vì sao chọn WAYLUNE', 'key' => 'why_choose_us_home'],
        'promo' => ['label' => 'Video & ưu đãi', 'key' => 'promo_home'],
        'destinations' => ['label' => 'Điểm đến nổi bật', 'key' => 'destinations_home'],
        'cta' => ['label' => 'Banner kêu gọi hành động', 'key' => 'cta_home'],
        'testimonials' => ['label' => 'Ý kiến khách hàng', 'key' => 'testimonials_home'],
        'blogs' => ['label' => 'Tin tức mới', 'key' => 'blogs_home'],
        'app_cta' => ['label' => 'CTA cuối trang', 'key' => 'app_cta_home'],
    ];

    private const VALID_LAYOUT_KEYS = [
        'tee_time',
        'tour',
        'accommodation',
        'transport',
        'attraction',
    ];

    public function index(Request $request)
    {
        $section = $request->string('section')->value() ?: 'hero';
        if (!isset(self::SECTIONS[$section])) {
            $section = 'hero';
        }

        $sectionsData = [];
        foreach (self::SECTIONS as $name => $config) {
            $sectionsData[$name] = $this->setting($config['key'])->value ?? [];
        }

        $serviceCategories = Category::query()
            ->where('status', 1)
            ->whereRaw('LOWER(type) = ?', ['service'])
            ->whereIn('layout_key', self::VALID_LAYOUT_KEYS)
            ->orderBy('sort')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'layout_key']);

        return view('setting.home', [
            'currentSection' => $section,
            'sectionsList' => collect(self::SECTIONS)
                ->map(fn(array $config, string $key) => ['key' => $key, 'label' => $config['label']])
                ->values()
                ->all(),
            'sectionsData' => $sectionsData,
            'serviceCategories' => $serviceCategories,
        ]);
    }

    public function update(Request $request, string $domain, string $section)
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);

        $payload = match ($section) {
            'hero' => $this->heroPayload($request),
            'search' => $this->searchPayload($request),
            'about' => $this->aboutPayload($request),
            'featured' => $this->featuredPayload($request),
            'why_choose_us' => $this->whyChooseUsPayload($request),
            'promo' => $this->promoPayload($request),
            'destinations' => $this->headingPayload($request),
            'cta' => $this->ctaPayload($request),
            'testimonials' => $this->testimonialsPayload($request),
            'blogs' => $this->blogsPayload($request),
            'app_cta' => $this->appCtaPayload($request),
        };

        $settingKey = self::SECTIONS[$section]['key'];
        $setting = $this->setting($settingKey);

        DB::transaction(function () use ($setting, $settingKey, $payload) {
            $backupSetting = Setting::firstOrNew(['key' => $settingKey . '_backup']);
            $backupValue = is_array($backupSetting->value) ? $backupSetting->value : [];
            $history = is_array($backupValue['history'] ?? null)
                ? $backupValue['history']
                : [];

            if (array_key_exists('value', $backupValue)) {
                $history[] = [
                    'saved_at' => $backupValue['saved_at'] ?? null,
                    'value' => $backupValue['value'],
                ];
            }

            $history[] = [
                'saved_at' => now()->toIso8601String(),
                'value' => $setting->value ?? [],
            ];

            $backupSetting->value = [
                'history' => array_slice($history, -20),
            ];
            $backupSetting->save();

            $setting->value = $payload;
            $setting->save();
        });

        $setting->refresh();

        if ($this->normalizeForComparison($setting->value) !== $this->normalizeForComparison($payload)) {
            abort(500, 'Dữ liệu lưu xuống database không khớp với dữ liệu gửi lên.');
        }

        $response = [
            'message' => 'Đã lưu ' . mb_strtolower(self::SECTIONS[$section]['label']) . '.',
            'key' => $setting->key,
            'value' => $setting->value,
        ];

        return $request->ajax() || $request->wantsJson()
            ? response()->json($response)
            : redirect()->to(panel_route('setting.home') . '?section=' . $section)
                ->with('success', $response['message']);
    }

    private function setting(string $key): Setting
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            return $setting;
        }

        $legacy = Setting::whereIn('key', [$key . '_travel', $key . '_golfnity', $key . '_clinic'])
            ->orderByRaw("CASE WHEN `key` = ? THEN 0 ELSE 1 END", [$key . '_golfnity'])
            ->first();

        return Setting::create(['key' => $key, 'value' => $legacy->value ?? []]);
    }

    private function heroPayload(Request $request): array
    {
        $data = $request->validate([
            'slides' => ['nullable', 'array'],
            'slides.*.id' => ['nullable', 'string', 'max:100'],
            'slides.*.enabled' => ['nullable', 'boolean'],
            'slides.*.sort' => ['nullable', 'integer', 'min:0'],
            'slides.*.subtitle' => ['nullable', 'string', 'max:255'],
            'slides.*.title' => ['nullable', 'string', 'max:255'],
            'slides.*.description' => ['nullable', 'string'],
            'slides.*.price_prefix' => ['nullable', 'string', 'max:100'],
            'slides.*.price_currency' => ['nullable', 'string', 'max:20'],
            'slides.*.price' => ['nullable', 'string', 'max:50'],
            'slides.*.price_suffix' => ['nullable', 'string', 'max:50'],
            'slides.*.button_text' => ['nullable', 'string', 'max:100'],
            'slides.*.button_link' => ['nullable', 'string', 'max:500'],
            'slides.*.image' => ['nullable', 'string'],
            'slides.*.image_file' => ['nullable', 'image', 'max:10240'],
            'slides.*.remove_image' => ['nullable', 'boolean'],
        ]);

        $slides = [];
        foreach ($data['slides'] ?? [] as $index => $slide) {
            $image = $slide['image'] ?? '';
            if ((bool) ($slide['remove_image'] ?? false)) {
                $image = '';
            } elseif ($request->hasFile("slides.$index.image_file")) {
                $image = $this->storeImage($request->file("slides.$index.image_file"), 'hero');
            }

            $slides[] = [
                'id' => trim((string) ($slide['id'] ?? '')) ?: (string) Str::uuid(),
                'enabled' => (bool) ($slide['enabled'] ?? false),
                'sort' => (int) ($slide['sort'] ?? $index),
                'subtitle' => trim((string) ($slide['subtitle'] ?? '')),
                'title' => trim((string) ($slide['title'] ?? '')),
                'description' => trim((string) ($slide['description'] ?? '')),
                'price_prefix' => trim((string) ($slide['price_prefix'] ?? '')),
                'price_currency' => trim((string) ($slide['price_currency'] ?? '')),
                'price' => trim((string) ($slide['price'] ?? '')),
                'price_suffix' => trim((string) ($slide['price_suffix'] ?? '')),
                'button_text' => trim((string) ($slide['button_text'] ?? '')),
                'button_link' => trim((string) ($slide['button_link'] ?? '')),
                'image' => $image,
            ];
        }

        usort($slides, fn(array $a, array $b) => $a['sort'] <=> $b['sort']);

        return ['slides' => array_values($slides)];
    }

    private function searchPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'button_label' => ['nullable', 'string', 'max:100'],
            'tabs' => ['nullable', 'array'],
            'tabs.*.category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn($query) => $query
                    ->where('status', 1)
                    ->whereRaw('LOWER(type) = ?', ['service'])
                    ->whereIn('layout_key', self::VALID_LAYOUT_KEYS)),
            ],
            'tabs.*.enabled' => ['nullable', 'boolean'],
            'tabs.*.sort' => ['nullable', 'integer', 'min:0'],
            'tabs.*.display_name' => ['nullable', 'string', 'max:100'],
            'tabs.*.placeholder' => ['nullable', 'string', 'max:150'],
        ]);

        return [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'button_label' => trim((string) ($data['button_label'] ?? '')),
            'tabs' => collect($data['tabs'] ?? [])
                ->map(fn(array $tab, int $index) => [
                    'category_id' => (int) $tab['category_id'],
                    'enabled' => (bool) ($tab['enabled'] ?? false),
                    'sort' => (int) ($tab['sort'] ?? $index),
                    'display_name' => trim((string) ($tab['display_name'] ?? '')),
                    'placeholder' => trim((string) ($tab['placeholder'] ?? '')),
                ])
                ->sortBy('sort')
                ->values()
                ->all(),
        ];
    }

    private function aboutPayload(Request $request): array
    {
        $data = $request->validate([
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'string'],
            'logo_file' => ['nullable', 'image', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'string'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['nullable', 'boolean'],
            'image_files' => ['nullable', 'array'],
            'image_files.*' => ['nullable', 'image', 'max:10240'],
        ]);

        return array_merge($this->contentPayload($data), [
            'logo' => $this->singleImage($request, 'logo_file', 'remove_logo', $data['logo'] ?? '', 'about'),
            'images' => $this->imageGroup($request, $data['images'] ?? [], 'image_files', 'remove_images', 'about', 4),
        ]);
    }

    private function featuredPayload(Request $request): array
    {
        $data = $request->validate([
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:24'],
            'featured_first' => ['nullable', 'boolean'],
            'tabs' => ['nullable', 'array'],
            'tabs.*.category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'tabs.*.enabled' => ['nullable', 'boolean'],
            'tabs.*.sort' => ['nullable', 'integer', 'min:0'],
            'tabs.*.display_name' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'limit' => (int) ($data['limit'] ?? 8),
            'featured_first' => (bool) ($data['featured_first'] ?? false),
            'tabs' => collect($data['tabs'] ?? [])
                ->map(fn(array $tab, int $index) => [
                    'category_id' => (int) $tab['category_id'],
                    'enabled' => (bool) ($tab['enabled'] ?? false),
                    'sort' => (int) ($tab['sort'] ?? $index),
                    'display_name' => trim((string) ($tab['display_name'] ?? '')),
                ])
                ->sortBy('sort')
                ->values()
                ->all(),
        ];
    }

    private function whyChooseUsPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'max:10240'],
            'remove_image' => ['nullable', 'boolean'],
            'secondary_image' => ['nullable', 'string'],
            'secondary_image_file' => ['nullable', 'image', 'max:10240'],
            'remove_secondary_image' => ['nullable', 'boolean'],
            'items' => ['nullable', 'array'],
            'items.*.icon' => ['nullable', 'string', 'max:100'],
            'items.*.title' => ['nullable', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        return array_merge($this->contentPayload($data), [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'image' => $this->singleImage($request, 'image_file', 'remove_image', $data['image'] ?? '', 'why-choose-us'),
            'secondary_image' => $this->singleImage($request, 'secondary_image_file', 'remove_secondary_image', $data['secondary_image'] ?? '', 'why-choose-us'),
            'items' => array_values($data['items'] ?? []),
        ]);
    }

    private function promoPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'string'],
            'cover_image_file' => ['nullable', 'image', 'max:10240'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
        ]);

        return array_merge($this->contentPayload($data), [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'video_url' => trim((string) ($data['video_url'] ?? '')),
            'cover_image' => $this->singleImage($request, 'cover_image_file', 'remove_cover_image', $data['cover_image'] ?? '', 'promo'),
        ]);
    }

    private function headingPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        return [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'limit' => (int) ($data['limit'] ?? 4),
        ];
    }

    private function ctaPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'background' => ['nullable', 'string'],
            'background_file' => ['nullable', 'image', 'max:10240'],
            'remove_background' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_link' => ['nullable', 'string', 'max:500'],
            'decorative_text' => ['nullable', 'string', 'max:100'],
        ]);

        return array_merge($this->contentPayload($data), [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'background' => $this->singleImage($request, 'background_file', 'remove_background', $data['background'] ?? '', 'cta'),
            'decorative_text' => trim((string) ($data['decorative_text'] ?? '')),
        ]);
    }

    private function testimonialsPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.enabled' => ['nullable', 'boolean'],
            'items.*.sort' => ['nullable', 'integer', 'min:0'],
            'items.*.name' => ['nullable', 'string', 'max:255'],
            'items.*.role' => ['nullable', 'string', 'max:255'],
            'items.*.content' => ['nullable', 'string'],
            'items.*.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'items.*.image' => ['nullable', 'string'],
            'items.*.image_file' => ['nullable', 'image', 'max:5120'],
            'items.*.remove_image' => ['nullable', 'boolean'],
        ]);

        $items = [];
        foreach ($data['items'] ?? [] as $index => $item) {
            $items[] = [
                'enabled' => (bool) ($item['enabled'] ?? false),
                'sort' => (int) ($item['sort'] ?? $index),
                'name' => trim((string) ($item['name'] ?? '')),
                'role' => trim((string) ($item['role'] ?? '')),
                'content' => trim((string) ($item['content'] ?? '')),
                'rating' => (int) ($item['rating'] ?? 5),
                'image' => $this->singleImage($request, "items.$index.image_file", "items.$index.remove_image", $item['image'] ?? '', 'testimonials'),
            ];
        }
        usort($items, fn(array $a, array $b) => $a['sort'] <=> $b['sort']);

        return [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'items' => array_values($items),
        ];
    }

    private function blogsPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:12'],
            'view_all_prefix' => ['nullable', 'string', 'max:255'],
            'view_all_text' => ['nullable', 'string', 'max:150'],
            'view_all_link' => ['nullable', 'string', 'max:500'],
        ]);

        return [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'limit' => (int) ($data['limit'] ?? 3),
            'view_all' => [
                'prefix' => trim((string) ($data['view_all_prefix'] ?? '')),
                'text' => trim((string) ($data['view_all_text'] ?? '')),
                'link' => trim((string) ($data['view_all_link'] ?? '')),
            ],
        ];
    }

    private function appCtaPayload(Request $request): array
    {
        $data = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'background' => ['nullable', 'string'],
            'background_file' => ['nullable', 'image', 'max:10240'],
            'remove_background' => ['nullable', 'boolean'],
            'phone_image' => ['nullable', 'string'],
            'phone_image_file' => ['nullable', 'image', 'max:10240'],
            'remove_phone_image' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'google_play_link' => ['nullable', 'string', 'max:500'],
            'app_store_link' => ['nullable', 'string', 'max:500'],
        ]);

        return [
            'enabled' => (bool) ($data['enabled'] ?? false),
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'google_play_link' => trim((string) ($data['google_play_link'] ?? '')),
            'app_store_link' => trim((string) ($data['app_store_link'] ?? '')),
            'background' => $this->singleImage($request, 'background_file', 'remove_background', $data['background'] ?? '', 'app-cta'),
            'phone_image' => $this->singleImage($request, 'phone_image_file', 'remove_phone_image', $data['phone_image'] ?? '', 'app-cta'),
        ];
    }

    private function contentPayload(array $data): array
    {
        return [
            'subtitle' => trim((string) ($data['subtitle'] ?? '')),
            'title' => trim((string) ($data['title'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'button_text' => trim((string) ($data['button_text'] ?? '')),
            'button_link' => trim((string) ($data['button_link'] ?? '')),
        ];
    }

    private function normalizeForComparison(mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        if (!array_is_list($value)) {
            ksort($value);
        }

        foreach ($value as $key => $item) {
            $value[$key] = $this->normalizeForComparison($item);
        }

        return $value;
    }

    private function imageGroup(Request $request, array $current, string $fileGroup, string $removeGroup, string $folder, int $count): array
    {
        $images = [];
        for ($index = 0; $index < $count; $index++) {
            $images[] = $this->singleImage(
                $request,
                "$fileGroup.$index",
                "$removeGroup.$index",
                $current[$index] ?? '',
                $folder
            );
        }

        return $images;
    }

    private function singleImage(Request $request, string $fileKey, string $removeKey, string $current, string $folder): string
    {
        $shouldRemove = $request->boolean($removeKey);
        $hasNewFile = $request->hasFile($fileKey);

        if (($shouldRemove || $hasNewFile) && $current !== '' && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }

        if ($shouldRemove && !$hasNewFile) {
            return '';
        }

        return $hasNewFile
            ? $this->storeImage($request->file($fileKey), $folder)
            : $current;
    }

    private function storeImage($file, string $folder): string
    {
        $domain = preg_replace('/[^a-z0-9_-]/i', '_', strtolower((string) request()->route('domain', 'default')));
        $name = uniqid($folder . '_', true) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs("uploads/$domain/setting/homepage/$folder", $name, 'public');
    }
}
