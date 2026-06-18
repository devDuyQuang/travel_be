<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private Product $model;

    public function __construct()
    {
        $this->model = new Product();
    }

    public function index()
    {
        return view('product.main');
    }

    public function datatable(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $searchValue = $request->input('search.value', '');

        $query = Product::query()
            ->select([
                 'established_text',
                'id',
                'name',
                'slug',
                'category_id',
                'image',
                'description',
                'location',
                'star_rating',
                'review_rating',
                'review_count',
                'established_year',
                'highlight',
                'facility',
                'price',
                'price_discount',
                'status',
                'created_at',
                'created_by',
                'golf_information',
                'badge_text',
            ])
            ->with([
                'category:id,name',
                'creator:id,name',
            ]);

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('price', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Product::count();
        $recordsFiltered = $searchValue !== '' ? (clone $query)->count() : $recordsTotal;

        $items = (clone $query)
            ->orderByDesc('id')
            ->offset($start)
            ->limit($length)
            ->get();

        $rows = $items->map(function (Product $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'category' => optional($item->category)->name ?? '—',
                'image' => $item->image ? Storage::url($item->image) : null,
                'description' => Str::limit((string) $item->description, 60),
                'location' => $item->location,
                'review_rating' => $item->review_rating,
                'star_rating' => $item->star_rating,
                'review_count' => $item->review_count,
                'established_year' => $item->established_year,
                'highlight' => Str::limit((string) $item->highlight, 80),
                'facility' => Str::limit((string) $item->facility, 80),
                'price' => $item->price,
                'price_discount' => $item->price_discount,
                'status' => (int) $item->status,
                'creator' => optional($item->creator)->name ?? '—',
                'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                '__details' => '',
                'badge_text' => $item->badge_text,
               'established_text' => $item->established_text,
            ];
        })->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function create()
    {
        $categories = $this->getCategoryOptions();

        return view('product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProductData($request);

        $uploadedPaths = [];

        try {
            $this->handleProductFixedImages(
                request: $request,
                product: null,
                data: $data,
                uploadedPaths: $uploadedPaths
            );

            $data['created_by'] = Auth::id();
            $data['order_position'] = (int) Product::max('order_position') + 1;

            $product = Product::create($data);

            $this->uploadProductGalleryImages($request, $product);

            $msg = __('messages.data_saved') ?: 'Thêm mới thành công';

            return $request->wantsJson()
                ? response()->json([
                    'message' => $msg,
                    'redirect_url' => panel_route(module() . '.index'),
                ])
                : redirect()->to(panel_route(module() . '.index'))->with('success', $msg);
        } catch (\Throwable $e) {
            $this->deleteFiles($uploadedPaths);

            Log::error('Create product error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = Product::with('images')->findOrFail($id);
        $categories = $this->getCategoryOptions();

        $currentImageUrl = $item->image ? Storage::url($item->image) : null;
        $currentGalleryImage1Url = $item->gallery_image_1 ? Storage::url($item->gallery_image_1) : null;
        $currentGalleryImage2Url = $item->gallery_image_2 ? Storage::url($item->gallery_image_2) : null;

        return view('product.edit', compact(
            'item',
            'categories',
            'currentImageUrl',
            'currentGalleryImage1Url',
            'currentGalleryImage2Url',
        ));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = Product::findOrFail($id);

        $data = $this->validateProductData($request, $item->id);

        $uploadedPaths = [];
        $oldPathsToDelete = [];

        try {
            $this->handleProductFixedImages(
                request: $request,
                product: $item,
                data: $data,
                uploadedPaths: $uploadedPaths,
                oldPathsToDelete: $oldPathsToDelete
            );

            $data['updated_by'] = Auth::id();

            $item->update($data);

            $this->uploadProductGalleryImages($request, $item->fresh());

            $this->deleteFiles($oldPathsToDelete);

            $msg = __('messages.data_saved') ?: 'Cập nhật thành công';

            return $request->wantsJson()
                ? response()->json([
                    'message' => $msg,
                    'redirect_url' => null,
                ])
                : redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            $this->deleteFiles($uploadedPaths);

            Log::error('Update product error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $domain, $id)
    {
        $item = Product::findOrFail($id);

        $item->update([
            'status' => (int) $item->status === 1 ? 0 : 1,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'status' => (int) $item->status,
        ]);
    }

    public function destroy(Request $request, $domain, $id)
    {
        $item = Product::with('images')->findOrFail($id);

        $paths = [];

        foreach (['image', 'gallery_image_1', 'gallery_image_2'] as $field) {
            if (!empty($item->{$field})) {
                $paths[] = $item->{$field};
            }
        }

        foreach ($item->images as $galleryImage) {
            if (!empty($galleryImage->image)) {
                $paths[] = $galleryImage->image;
            }
        }

        $item->delete();

        $this->deleteFiles($paths);

        return $request->wantsJson()
            ? response()->json(['message' => 'Xóa thành công'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xóa thành công');
    }

    public function destroyGalleryImage(Request $request, $domain, ProductImage $image)
    {
        $path = $image->image;

        $image->delete();

        $this->deleteFiles([$path]);

        return back()->with('success', 'Xóa ảnh thành công.');
    }

    private function validateProductData(Request $request, ?int $id = null): array
    {
        foreach (['price', 'price_discount', 'review_rating'] as $numberField) {
            if ($request->filled($numberField)) {
                $request->merge([
                    $numberField => str_replace(',', '.', $request->input($numberField)),
                ]);
            }
        }

        $request->merge([
            'slug' => filled($request->slug)
                ? Str::slug($request->slug)
                : Str::slug($request->name),

            'category_id' => filled($request->category_id) && is_numeric($request->category_id)
                ? (int) $request->category_id
                : null,
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereRaw('LOWER(type) = ?', ['product']);
                }),
            ],

            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'gallery_image_1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'gallery_image_2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],

            'image_original_name' => ['nullable', 'string', 'max:255'],
            'gallery_image_1_original_name' => ['nullable', 'string', 'max:255'],
            'gallery_image_2_original_name' => ['nullable', 'string', 'max:255'],

            'remove_image' => ['nullable', 'boolean'],
            'remove_gallery_image_1' => ['nullable', 'boolean'],
            'remove_gallery_image_2' => ['nullable', 'boolean'],

            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],

            'video_url' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'review_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'star_rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'string', 'max:255'],
            'established_text' => ['nullable', 'string', 'max:255'],
            'established_year' => ['nullable', 'integer', 'min:1800', 'max:' . ((int) date('Y') + 1)],
            'highlight' => ['nullable', 'string'],
            'facility' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'golf_information' => ['nullable', 'string'],
            'title_seo' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'description_seo' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'price_discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'badge_text' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.string' => 'Tên sản phẩm không hợp lệ.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'slug.required' => 'Vui lòng nhập slug.',
            'slug.unique' => 'Slug này đã tồn tại.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',

            'category_id.integer' => 'Danh mục không hợp lệ.',
            'category_id.exists' => 'Danh mục không tồn tại.',

            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải thuộc định dạng jpeg, png, jpg, gif, svg hoặc webp.',
            'image.max' => 'Dung lượng ảnh không được vượt quá 5MB.',

            'gallery_image_1.image' => 'Ảnh phụ 1 phải là hình ảnh.',
            'gallery_image_1.mimes' => 'Ảnh phụ 1 phải thuộc định dạng jpeg, png, jpg, gif, svg hoặc webp.',
            'gallery_image_1.max' => 'Dung lượng ảnh phụ 1 không được vượt quá 5MB.',

            'gallery_image_2.image' => 'Ảnh phụ 2 phải là hình ảnh.',
            'gallery_image_2.mimes' => 'Ảnh phụ 2 phải thuộc định dạng jpeg, png, jpg, gif, svg hoặc webp.',
            'gallery_image_2.max' => 'Dung lượng ảnh phụ 2 không được vượt quá 5MB.',

            'description.string' => 'Mô tả không hợp lệ.',
            'content.string' => 'Nội dung không hợp lệ.',

            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được nhỏ hơn 0.',
            'price_discount.numeric' => 'Giá khuyến mãi phải là số.',
            'price_discount.min' => 'Giá khuyến mãi không được nhỏ hơn 0.',

            'status.integer' => 'Trạng thái không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',

            'sort.integer' => 'Thứ tự phải là số.',
            'sort.min' => 'Thứ tự không được nhỏ hơn 0.',

            'location.string' => 'Vị trí không hợp lệ.',
            'location.max' => 'Vị trí không được vượt quá 255 ký tự.',

            'review_rating.numeric' => 'Điểm đánh giá phải là số.',
            'review_rating.min' => 'Điểm đánh giá không được nhỏ hơn 0.',
            'review_rating.max' => 'Điểm đánh giá không được lớn hơn 5.',

            'review_count.string' => 'Số lượng review không hợp lệ.',
            'review_count.max' => 'Số lượng review không được vượt quá 255 ký tự.',

            'established_year.integer' => 'Năm thành lập phải là số.',
            'established_year.min' => 'Năm thành lập không hợp lệ.',
            'established_year.max' => 'Năm thành lập không hợp lệ.',

            'highlight.string' => 'Điểm nổi bật không hợp lệ.',
            'facility.string' => 'Dịch vụ tiện ích không hợp lệ.',

            'star_rating.numeric' => 'Số sao được phép là số bất kỳ.',
            'star_rating.min' => 'Số sao không được nhỏ hơn 0.',
            'star_rating.max' => 'Số sao không được lớn hơn 5.',

            'badge_text.string' => 'Nhãn hiển thị không hợp lệ.',
            'badge_text.max' => 'Nhãn hiển thị không được vượt quá 50 ký tự.',
        ]);
    }

    private function handleProductFixedImages(
        Request $request,
        ?Product $product,
        array &$data,
        array &$uploadedPaths,
        array &$oldPathsToDelete = []
    ): void {
        $slug = $data['slug'] ?? Str::slug($data['name']);

        foreach (['image', 'gallery_image_1', 'gallery_image_2'] as $field) {
            $uploaded = $this->uploadProductImage($request, $field, $slug);

            if ($uploaded !== null) {
                $data[$field] = $uploaded['path'];
                $data[$field . '_original_name'] = $uploaded['original_name'];
                $uploadedPaths[] = $uploaded['path'];

                if ($product && !empty($product->{$field})) {
                    $oldPathsToDelete[] = $product->{$field};
                }
            }

            $removeField = 'remove_' . $field;

            if ($product && $request->boolean($removeField)) {
                if (!empty($product->{$field})) {
                    $oldPathsToDelete[] = $product->{$field};
                }

                $data[$field] = null;
                $data[$field . '_original_name'] = null;
            }
        }
    }

    private function uploadProductImage(Request $request, string $field, string $slug): ?array
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);

        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = $slug
            . '_' . $field
            . '_' . now()->timestamp
            . '_' . Str::random(12)
            . '.'
            . strtolower($file->getClientOriginalExtension());

        $path = $file->storeAs('products', $filename, 'public');

        return [
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    private function uploadProductGalleryImages(Request $request, Product $product): void
    {
        if (!$request->hasFile('gallery_images')) {
            return;
        }

        $files = $request->file('gallery_images');

        if (!is_array($files)) {
            return;
        }

        $currentCount = $product->images()->count();

        foreach ($files as $index => $file) {
            if ($currentCount + $index >= 10) {
                break;
            }

            if (!$file || !$file->isValid()) {
                continue;
            }

            $filename = Str::slug($product->name)
                . '_gallery_'
                . now()->timestamp
                . '_'
                . Str::random(12)
                . '.'
                . strtolower($file->getClientOriginalExtension());

            $path = $file->storeAs('products', $filename, 'public');

            $product->images()->create([
                'image' => $path,
                'original_name' => $file->getClientOriginalName(),
                'sort_order' => $currentCount + $index + 1,
            ]);
        }
    }

    private function deleteFiles(array $paths): void
    {
        foreach (array_unique(array_filter($paths)) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function getCategoryOptions(): array
    {
        return Category::query()
            ->where('status', 1)
            ->whereRaw('LOWER(type) = ?', ['product'])
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}