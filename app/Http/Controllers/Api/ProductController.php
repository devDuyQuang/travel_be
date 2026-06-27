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
        $productType = $request->string('product_type')->trim()->value();
        $categoryType = $request->string('type')->trim()->value();
        $isShop = $productType === 'physical' || $categoryType === 'product';
        $categoryType = $isShop ? 'product' : 'service';

        $query = Product::query()
            ->select($this->productFields())
            ->with([
                'category:id,name,slug,type,layout_key,status',
                'images',
            ])
            ->where('status', 1)
            ->when($productType !== '', fn ($q) => $q->where('product_type', $productType))
            ->whereHas('category', function ($categoryQuery) use ($categoryType) {
                $categoryQuery
                    ->where('status', 1)
                    ->whereRaw('LOWER(type) = ?', [$categoryType]);
            })
            ->when($categorySlug !== '', function ($query) use ($categorySlug) {
                $query->whereHas('category', function ($categoryQuery) use ($categorySlug) {
                    $categoryQuery
                        ->where('slug', $categorySlug)
                        ->where('status', 1);
                });
            })
            ->when($search = $request->string('search')->trim()->value(), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });

        match ($request->input('sort')) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, regular_price, price_discount, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, regular_price, price_discount, price) DESC'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'featured' => $query->orderByDesc('is_featured'),
            default => $query->orderByDesc('id'),
        };

        $products = $query
            ->when($request->boolean('featured_first'), fn($productQuery) => $productQuery->orderByDesc('is_featured'))
            ->orderBy('order_position')
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
                    ->where('status', 1);
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
            'product_type',
            'sku',
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
            'regular_price',
            'sale_price',
            'stock_quantity',
            'manage_stock',
            'stock_status',
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
