<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'category_id',
                'golf_information',
                'image',
                'image_original_name',
                'gallery_image_1',
                'gallery_image_1_original_name',
                'gallery_image_2',
                'gallery_image_2_original_name',
                'video_url',
                'star_rating',
                'description',
                'location',
                'duration',
                'review_rating',
                'review_count',
                'established_year',
                'highlight',
                'facility',
                'content',
                'price',
                'price_discount',
                'status',
                'sort',
                'order_position',
                'created_at',
                'badge_text',
            ])
            ->with([
                'category:id,name,slug,type',
                'images',
            ])
            ->where('status', 1);


        if ($request->filled('category')) {
            $category = $request->get('category');

            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)
                    ->orWhere('id', $category);
            });
        }

        $products = $query
            ->orderBy('order_position')
            ->orderByDesc('id')
            ->get()
            ->map(function ($item) {
                return $this->formatProduct($item);
            });

        return response()->json([
            'data' => $products,
        ]);
    }

    public function show($domain, string $slug)
    {
        $product = Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'category_id',
                'star_rating',
                'golf_information',
                'image',
                'image_original_name',
                'gallery_image_1',
                'gallery_image_1_original_name',
                'gallery_image_2',
                'gallery_image_2_original_name',
                'video_url',
                'badge_text',
                'description',
                'location',
                'duration',
                'review_rating',
                'review_count',
                'established_year',
                'highlight',
                'facility',
                'content',
                'price',
                'price_discount',
                'status',
                'sort',
                'order_position',
                'created_at',
            ])
            ->with([
                'category:id,name,slug,type',
                'images',
            ])
            ->where('status', 1)
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug);

                if (is_numeric($slug)) {
                    $query->orWhere('id', (int) $slug);
                }
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatProduct($product),
        ]);
    }

    private function formatProduct(Product $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,

            'description' => $item->description,
            'location' => $item->location,
            'duration' => $item->duration,
            'review_rating' => $item->review_rating,
            'review_count' => $item->review_count,
            'established_year' => $item->established_year,
            'highlight' => $item->highlight,
            'facility' => $item->facility,
            'content' => $item->content,
            'star_rating' => $item->star_rating,
            'image' => $item->image ? Storage::url($item->image) : null,
            'image_original_name' => $item->image_original_name,
            'badge_text' => $item->badge_text,
            'gallery_image_1' => $item->gallery_image_1 ? Storage::url($item->gallery_image_1) : null,
            'gallery_image_1_original_name' => $item->gallery_image_1_original_name,
            'golf_information' => $item->golf_information,
            'gallery_image_2' => $item->gallery_image_2 ? Storage::url($item->gallery_image_2) : null,
            'gallery_image_2_original_name' => $item->gallery_image_2_original_name,
            'gallery_images' => $item->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image' => Storage::url($image->image),
                    'original_name' => $image->original_name,
                ];
            })->values(),

            'video_url' => $item->video_url,

            'price' => $item->price,
            'price_discount' => $item->price_discount,

            'category' => $item->category ? [
                'id' => $item->category->id,
                'name' => $item->category->name,
                'slug' => $item->category->slug,
                'type' => $item->category->type,
            ] : null,

            'created_at' => $item->created_at,
        ];
    }
}
