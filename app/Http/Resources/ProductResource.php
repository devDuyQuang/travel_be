<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storedAttributes = $this->resource->getAttribute('attributes');
        $attributes = is_array($storedAttributes) ? $storedAttributes : [];

        foreach ([
            'number_of_holes' => 'holes',
            'number_of_days' => 'days',
            'number_of_nights' => 'nights',
            'group_size' => 'max_guests',
            'language' => 'languages',
            'hotel_star' => 'classification_rating',
            'room_type' => 'property_type',
        ] as $legacyKey => $canonicalKey) {
            if (
                ! array_key_exists($canonicalKey, $attributes)
                && array_key_exists($legacyKey, $attributes)
            ) {
                $attributes[$canonicalKey] = $attributes[$legacyKey];
            }
        }

        $gallery = collect([
            $this->mediaUrl($this->gallery_image_1),
            $this->mediaUrl($this->gallery_image_2),
            $this->mediaUrl($this->gallery_image_3),
        ])->merge(
            $this->images->map(fn ($image) => $this->mediaUrl($image->image))
        )->filter()->unique()->values();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'type' => $this->category->type,
                'layout_key' => $this->category->layout_key,
            ]),
            'badge' => $this->badge_text,
            'short_description' => $this->description,
            'content' => $this->content,
            'image_url' => $this->mediaUrl($this->image),
            'gallery' => $gallery,
            'video_url' => $this->video_url,
            'location' => $this->location,
            'duration' => $this->duration,
            'price' => $this->price,
            'price_discount' => $this->price_discount,
            'product_type' => $this->product_type,
            'sku' => $this->sku,
            'regular_price' => $this->regular_price,
            'sale_price' => $this->sale_price,
            'display_price' => $this->sale_price ?? $this->regular_price ?? $this->price_discount ?? $this->price,
            'stock_quantity' => (int) ($this->stock_quantity ?? 0),
            'manage_stock' => (bool) $this->manage_stock,
            'stock_status' => $this->stock_status,
            'rating' => $this->review_rating,
            'review_count' => $this->normalizeReviewCount($this->review_count),
            'is_featured' => (bool) $this->is_featured,
            'sort_order' => (int) $this->order_position,
            'highlights' => $this->highlight,
            'facilities' => $this->facility,
            'attributes' => (object) $attributes,
            'service_options' => $this->whenLoaded('activeServiceOptions', fn () => $this->activeServiceOptions
                ->sortBy([
                    ['sort_order', 'asc'],
                    ['id', 'asc'],
                ])
                ->values()
                ->map(function ($option) {
                    $metadata = is_array($option->metadata) ? $option->metadata : [];

                    return [
                        'id' => $option->id,
                        'type' => $option->type,
                        'name' => $option->name,
                        'label' => $option->name,
                        'description' => $option->description,
                        'price' => $option->price,
                        'currency' => $option->currency,
                        'unit' => $option->unit,
                        'capacity' => $option->capacity,
                        'sort_order' => $option->sort_order,
                        'is_active' => (bool) $option->is_active,
                        'metadata' => (object) $metadata,
                        'min_quantity' => $metadata['min_quantity']
                            ?? $metadata['min_golfers']
                            ?? $metadata['min_people']
                            ?? null,
                        'max_quantity' => $metadata['max_quantity']
                            ?? $metadata['max_golfers']
                            ?? $metadata['max_people']
                            ?? $option->capacity,
                        'inclusions' => $metadata['inclusions'] ?? null,
                        'exclusions' => $metadata['exclusions'] ?? null,
                    ];
                })),
            'seo' => [
                'title' => $this->title_seo,
                'description' => $this->description_seo,
                'canonical_url' => $this->canonical_url,
            ],
            'status' => (int) $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        if (str_starts_with($path, '/storage/')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return '/'.$path;
        }

        return Storage::url($path);
    }

    private function normalizeReviewCount(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return max(0, (int) $value);
        }

        if (preg_match('/[\d,.]+/', (string) $value, $matches)) {
            $digits = preg_replace('/\D/', '', $matches[0]);
            return $digits === '' ? null : (int) $digits;
        }

        return null;
    }
}
