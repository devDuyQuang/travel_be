<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(100, max(1, (int) $request->input('limit', 12)));
        $categorySlug = $request->string('category_slug')->trim()->value();

        $query = Product::query()
            ->select($this->productFields())
            ->with([
                'category:id,name,slug,type,layout_key,status',
                'images',
            ])
            ->where('status', 1)
            ->whereHas('category', function ($categoryQuery) {
                $categoryQuery
                    ->where('status', 1)
                    ->whereRaw('LOWER(type) = ?', ['service']);
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery
                        ->where('slug', $categorySlug)
                        ->where('status', 1)
                        ->whereRaw('LOWER(type) = ?', ['service']);
                });
            });

        $products = $query
            ->orderBy('order_position')
            ->orderByDesc('id')
            ->paginate($limit);

        return ProductResource::collection($products)->additional([
            'success' => true,
            'message' => 'OK',
        ]);
    }

    public function show(Request $request, $domain, string $slug)
    {
        $product = Product::query()
            ->select($this->productFields())
            ->with([
                'category:id,name,slug,type,layout_key,status',
                'images',
            ])
            ->where('status', 1)
            ->where('slug', $slug)
            ->whereHas('category', function ($categoryQuery) {
                $categoryQuery
                    ->where('status', 1)
                    ->whereRaw('LOWER(type) = ?', ['service']);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => ProductResource::make($product)->resolve($request),
        ]);
    }

    private function productFields(): array
    {
        return [
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
            'description',
            'location',
            'duration',
            'review_rating',
            'review_count',
            'established_year',
            'established_text',
            'highlight',
            'facility',
            'attributes',
            'content',
            'price',
            'price_discount',
            'badge_text',
            'title_seo',
            'canonical_url',
            'description_seo',
            'status',
            'is_featured',
            'order_position',
            'created_at',
            'updated_at',
        ];
    }

}
