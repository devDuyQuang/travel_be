<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CatalogExport extends Command
{
    protected $signature = 'golfnity:catalog-export
        {path=storage/app/catalog/golfnity-catalog.json : Output JSON path}';

    protected $description = 'Export production-safe product catalog data to a versioned JSON file.';

    private const CATEGORY_FIELDS = [
        'name',
        'slug',
        'status',
        'home',
        'description',
        'content',
        'image',
        'icon',
        'type',
        'layout_key',
        'sort',
        'order_position',
        'title_seo',
        'description_seo',
        'canonical_seo',
    ];

    private const PRODUCT_FIELDS = [
        'name',
        'slug',
        'badge_text',
        'product_type',
        'sku',
        'image',
        'image_original_name',
        'gallery_image_1',
        'gallery_image_1_original_name',
        'gallery_image_2',
        'gallery_image_2_original_name',
        'gallery_image_3',
        'video_url',
        'description',
        'location',
        'duration',
        'review_rating',
        'review_count',
        'star_rating',
        'established_year',
        'highlight',
        'facility',
        'attributes',
        'content',
        'price',
        'price_discount',
        'regular_price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'status',
        'is_featured',
        'golf_information',
        'title_seo',
        'canonical_url',
        'description_seo',
        'established_text',
        'sort',
        'order_position',
    ];

    public function handle(): int
    {
        $products = Product::query()
            ->with(['category.parent', 'images', 'serviceOptions'])
            ->orderBy('category_id')
            ->orderBy('order_position')
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        $categoryIds = [];
        foreach ($products as $product) {
            $category = $product->category;
            while ($category) {
                $categoryIds[$category->id] = true;
                $category = $category->parent;
            }
        }

        $categories = Category::query()
            ->with('parent')
            ->whereIn('id', array_keys($categoryIds))
            ->orderBy('parent_id')
            ->orderBy('sort')
            ->orderBy('order_position')
            ->orderBy('id')
            ->get();

        $media = [];
        $categoryPayload = $categories->map(function (Category $category) use (&$media): array {
            $fields = $this->onlyFields($category, self::CATEGORY_FIELDS);
            $this->rememberMedia($media, 'categories', $category->slug, $fields['image'] ?? null);
            $this->rememberMedia($media, 'categories', $category->slug, $fields['icon'] ?? null);

            return [
                'key' => $category->slug,
                'parent_slug' => $category->parent?->slug,
                'fields' => $fields,
            ];
        })->values();

        $productPayload = $products->map(function (Product $product) use (&$media): array {
            $fields = $this->onlyFields($product, self::PRODUCT_FIELDS);
            foreach (['image', 'gallery_image_1', 'gallery_image_2', 'gallery_image_3'] as $field) {
                $this->rememberMedia($media, 'products', $product->slug, $fields[$field] ?? null);
            }

            $images = $product->images->map(function ($image) use (&$media, $product): array {
                $this->rememberMedia($media, 'product_images', $product->slug, $image->image);

                return [
                    'key' => $image->image,
                    'fields' => [
                        'image' => $image->image,
                        'original_name' => $image->original_name,
                        'sort_order' => $image->sort_order,
                    ],
                ];
            })->values();

            $serviceOptions = $product->serviceOptions->map(fn ($option): array => [
                'key' => $this->serviceOptionKey($option->type, $option->name),
                'fields' => [
                    'type' => $option->type,
                    'name' => $option->name,
                    'description' => $option->description,
                    'price' => $option->price,
                    'currency' => $option->currency,
                    'unit' => $option->unit,
                    'capacity' => $option->capacity,
                    'sort_order' => $option->sort_order,
                    'is_active' => $option->is_active,
                    'metadata' => $option->metadata,
                ],
            ])->values();

            return [
                'key' => $product->slug,
                'category_slug' => $product->category?->slug,
                'fields' => $fields,
                'images' => $images,
                'service_options' => $serviceOptions,
            ];
        })->values();

        $payload = [
            'version' => 1,
            'exported_at' => now()->toIso8601String(),
            'source' => [
                'app' => config('app.name'),
                'environment' => app()->environment(),
            ],
            'classification' => [
                'catalog' => ['categories', 'products', 'product_images', 'service_product_options'],
                'runtime_excluded' => [
                    'bookings',
                    'booking_price_histories',
                    'booking_status_histories',
                    'orders',
                    'order_items',
                    'order_status_histories',
                    'payments',
                    'payment_status_histories',
                    'users',
                    'password_reset_tokens',
                    'sessions',
                    'personal_access_tokens',
                    'contacts',
                    'service_registrations',
                    'comments',
                    'jobs',
                    'cache',
                ],
                'unchanged_unsure' => ['menu', 'settings', 'posts', 'tags', 'category_post', 'post_tag', 'faqs', 'team_members', 'roles', 'permissions', 'domains'],
            ],
            'entities' => [
                'categories' => $categoryPayload,
                'products' => $productPayload,
            ],
            'media_references' => array_values($media),
        ];

        $path = $this->resolvePath($this->argument('path'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $missing = collect($media)->where('exists_locally', false)->count();
        $this->info("Catalog exported: {$path}");
        $this->line('Categories: '.$categoryPayload->count());
        $this->line('Products: '.$productPayload->count());
        $this->line('Product images: '.$productPayload->sum(fn ($product) => count($product['images'])));
        $this->line('Service options: '.$productPayload->sum(fn ($product) => count($product['service_options'])));
        $this->line('Media references: '.count($media).' (missing locally: '.$missing.')');

        return self::SUCCESS;
    }

    private function onlyFields($model, array $fields): array
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $model->{$field};
        }

        return $data;
    }

    private function rememberMedia(array &$media, string $entity, string $owner, ?string $path): void
    {
        $normalized = $this->normalizeMediaPath($path);
        if ($normalized === null) {
            return;
        }

        $media[$normalized] ??= [
            'path' => $path,
            'storage_path' => $normalized,
            'entity' => $entity,
            'owner' => $owner,
            'exists_locally' => Storage::disk('public')->exists($normalized),
        ];
    }

    private function normalizeMediaPath(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '' || preg_match('#^https?://#i', $path)) {
            return null;
        }

        $path = ltrim($path, '/');
        foreach (['storage/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
            }
        }

        return $path;
    }

    private function serviceOptionKey(?string $type, ?string $name): string
    {
        return trim((string) $type).'::'.trim((string) $name);
    }

    private function resolvePath(string $path): string
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR) ? $path : base_path($path);
    }
}
