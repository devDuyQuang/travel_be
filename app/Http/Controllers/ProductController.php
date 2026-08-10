<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ServiceProductOption;
use App\Support\ServiceProductAttributes;
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
        $categoryId = $request->integer('category_id');

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
                'review_rating',
                'review_count',
                'established_year',
                'highlight',
                'facility',
                'price',
                'price_discount',
                'status',
                'created_at',
                'updated_at',
                'created_by',
                'golf_information',
                'badge_text',
            ])
            ->with([
                'category:id,name',
                'creator:id,name',
            ]);

        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('slug', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('price', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Product::count();
        $recordsFiltered = ($searchValue !== '' || $categoryId > 0)
            ? (clone $query)->count()
            : $recordsTotal;

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
                'review_count' => $item->review_count,
                'established_year' => $item->established_year,
                'highlight' => Str::limit((string) $item->highlight, 80),
                'facility' => Str::limit((string) $item->facility, 80),
                'price' => $item->price,
                'price_discount' => $item->price_discount,
                'status' => (int) $item->status,
                'creator' => optional($item->creator)->name ?? '—',
                'created_at' => $item->created_at
                    ? $item->created_at->format('d/m/Y H:i')
                    : null,
                'updated_at' => $item->updated_at
                    ? $item->updated_at->format('d/m/Y H:i')
                    : null,
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
        $categoryLayouts = $this->getCategoryLayouts();
        $attributeGroups = ServiceProductAttributes::groups();
        $serviceOptionTypes = ServiceProductOption::TYPES;
        $serviceOptionUnits = ServiceProductOption::UNITS;

        return view('product.create', compact('categories', 'categoryLayouts', 'attributeGroups', 'serviceOptionTypes', 'serviceOptionUnits'));
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
            if (! array_key_exists('order_position', $data) || $data['order_position'] === null) {
                $data['order_position'] = (int) Product::max('order_position') + 1;
            }

            $product = Product::create($data);
            $this->syncServiceOptions($product, $request->input('service_options', []));

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
        $item = Product::with(['images', 'serviceOptions'])->findOrFail($id);
        $categories = $this->getCategoryOptions();
        $categoryLayouts = $this->getCategoryLayouts();
        $attributeGroups = ServiceProductAttributes::groups();
        $serviceOptionTypes = ServiceProductOption::TYPES;
        $serviceOptionUnits = ServiceProductOption::UNITS;

        $currentImageUrl = $item->image ? Storage::url($item->image) : null;
        $currentGalleryImage1Url = $item->gallery_image_1 ? Storage::url($item->gallery_image_1) : null;
        $currentGalleryImage2Url = $item->gallery_image_2 ? Storage::url($item->gallery_image_2) : null;

        return view('product.edit', compact(
            'item',
            'categories',
            'categoryLayouts',
            'attributeGroups',
            'serviceOptionTypes',
            'serviceOptionUnits',
            'currentImageUrl',
            'currentGalleryImage1Url',
            'currentGalleryImage2Url',
        ));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = Product::findOrFail($id);

        $data = $this->validateProductData($request, $item->id);
        $data['attributes'] = array_merge(
            is_array($item->attributes) ? $item->attributes : [],
            $data['attributes'] ?? []
        );

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
            $this->syncServiceOptions($item, $request->input('service_options', []));

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
        foreach (['price', 'price_discount', 'regular_price', 'sale_price', 'review_rating'] as $numberField) {
            if ($request->filled($numberField)) {
                $request->merge([
                    $numberField => str_replace(',', '.', $request->input($numberField)),
                ]);
            }
        }

        $serviceOptions = $request->input('service_options', []);
        if (is_array($serviceOptions)) {
            foreach ($serviceOptions as $index => $option) {
                if (! is_array($option)) {
                    continue;
                }

                if (array_key_exists('price', $option) && is_string($option['price'])) {
                    $serviceOptions[$index]['price'] = str_replace(',', '.', $option['price']);
                }
            }

            $request->merge(['service_options' => $serviceOptions]);
        }

        $request->merge([
            'slug' => filled($request->slug)
                ? Str::slug($request->slug)
                : Str::slug($request->name),

            'category_id' => filled($request->category_id) && is_numeric($request->category_id)
                ? (int) $request->category_id
                : null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $request->boolean('status'),
            'product_type' => $request->input('product_type') === 'physical' ? 'physical' : 'service',
            'manage_stock' => $request->boolean('manage_stock'),
            'stock_quantity' => max(0, (int) $request->input('stock_quantity', 0)),
            'stock_status' => $request->input('stock_status') === 'out_of_stock' ? 'out_of_stock' : 'in_stock',
            'attributes' => $this->normalizeAttributes($request->input('attributes', [])),
        ]);

        $productType = $request->input('product_type', 'service');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) use ($productType) {
                    $query
                        ->where('status', 1)
                        ->whereRaw('LOWER(type) = ?', [$productType === 'physical' ? 'product' : 'service']);
                }),
            ],
            'product_type' => ['required', 'string', Rule::in(['service', 'physical'])],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($id),
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
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => [
                'nullable',
                'numeric',
                'min:0',
                function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                    if (
                        $value !== null &&
                        $value !== '' &&
                        $request->filled('regular_price') &&
                        (float) $value > (float) $request->input('regular_price')
                    ) {
                        $fail('Giá bán không được lớn hơn giá gốc.');
                    }
                },
            ],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'manage_stock' => ['nullable', 'boolean'],
            'stock_status' => ['nullable', Rule::in(['in_stock', 'out_of_stock'])],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'is_featured' => ['nullable', 'boolean'],
            'order_position' => ['nullable', 'integer', 'min:0'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'attributes' => ['nullable', 'array'],
            'service_options' => ['nullable', 'array'],
            'service_options.*.id' => ['nullable', 'integer'],
            'service_options.*._delete' => ['nullable', 'boolean'],
            'service_options.*.type' => ['nullable', Rule::in(ServiceProductOption::TYPES)],
            'service_options.*.name' => ['nullable', 'string', 'max:255'],
            'service_options.*.description' => ['nullable', 'string'],
            'service_options.*.price' => ['nullable', 'numeric', 'min:0'],
            'service_options.*.currency' => ['nullable', 'string', 'max:10'],
            'service_options.*.unit' => ['nullable', Rule::in(ServiceProductOption::UNITS)],
            'service_options.*.capacity' => ['nullable', 'integer', 'min:0'],
            'service_options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'service_options.*.is_active' => ['nullable', 'boolean'],
            ...($productType === 'service' ? ServiceProductAttributes::validationRules() : []),
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.string' => 'Tên sản phẩm không hợp lệ.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'slug.required' => 'Vui lòng nhập slug.',
            'slug.unique' => 'Slug này đã tồn tại.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.integer' => 'Danh mục không hợp lệ.',
            'category_id.exists' => 'Danh mục không tồn tại hoặc đã bị ẩn.',
            'product_type.required' => 'Vui lòng chọn loại sản phẩm.',
            'product_type.in' => 'Loại sản phẩm không hợp lệ.',
            'sku.unique' => 'SKU này đã tồn tại.',

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
            'regular_price.numeric' => 'Giá gốc phải là số.',
            'regular_price.min' => 'Giá gốc không được nhỏ hơn 0.',
            'sale_price.numeric' => 'Giá bán phải là số.',
            'sale_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'stock_quantity.integer' => 'Số lượng tồn phải là số nguyên.',
            'stock_quantity.min' => 'Số lượng tồn không được nhỏ hơn 0.',

            'status.integer' => 'Trạng thái không hợp lệ.',
            'status.in' => 'Trạng thái không hợp lệ.',

            'order_position.integer' => 'Thứ tự hiển thị phải là số.',
            'order_position.min' => 'Thứ tự hiển thị không được nhỏ hơn 0.',

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

            'badge_text.string' => 'Nhãn hiển thị không hợp lệ.',
            'badge_text.max' => 'Nhãn hiển thị không được vượt quá 50 ký tự.',
        ]);
    }

    private function syncServiceOptions(Product $product, mixed $options): void
    {
        if (! is_array($options)) {
            return;
        }

        foreach ($options as $option) {
            if (! is_array($option)) {
                continue;
            }

            $id = isset($option['id']) && is_numeric($option['id'])
                ? (int) $option['id']
                : null;
            $markedForDelete = filter_var($option['_delete'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $name = trim((string) ($option['name'] ?? ''));

            if ($id && ($markedForDelete || $name === '')) {
                $product->serviceOptions()->whereKey($id)->delete();
                continue;
            }

            if ($name === '') {
                continue;
            }

            $payload = [
                'type' => in_array(($option['type'] ?? ''), ServiceProductOption::TYPES, true)
                    ? $option['type']
                    : 'room_type',
                'name' => $name,
                'description' => filled($option['description'] ?? null)
                    ? trim((string) $option['description'])
                    : null,
                'price' => filled($option['price'] ?? null)
                    ? (string) $option['price']
                    : null,
                'currency' => filled($option['currency'] ?? null)
                    ? strtoupper(trim((string) $option['currency']))
                    : 'VND',
                'unit' => in_array(($option['unit'] ?? ''), ServiceProductOption::UNITS, true)
                    ? $option['unit']
                    : null,
                'capacity' => filled($option['capacity'] ?? null)
                    ? max(0, (int) $option['capacity'])
                    : null,
                'sort_order' => max(0, (int) ($option['sort_order'] ?? 0)),
                'is_active' => filter_var($option['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'metadata' => null,
            ];

            if ($id) {
                $existing = $product->serviceOptions()->whereKey($id)->first();

                if ($existing) {
                    $existing->update($payload);
                }

                continue;
            }

            $product->serviceOptions()->create($payload);
        }
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
            ->orderBy('order_position')
            ->orderBy('name')
            ->get(['id', 'name', 'type'])
            ->mapWithKeys(fn (Category $category) => [
                $category->id => $category->name . ' (' . (strtolower((string) $category->type) === 'product' ? 'Sản phẩm' : 'Dịch vụ') . ')',
            ])
            ->toArray();
    }

    private function getCategoryLayouts(): array
    {
        return Category::query()
            ->where('status', 1)
            ->pluck('layout_key', 'id')
            ->toArray();
    }

    private function normalizeAttributes(mixed $attributes): array
    {
        if (! is_array($attributes)) {
            return [];
        }

        $booleanKeys = [
            'driver_included',
            'is_weekend_recommended',
            'is_near_center',
        ];
        $integerKeys = ['travel_time_to_center'];
        $numericKeys = ['distance_to_center'];
        $normalized = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $booleanKeys, true)) {
                $normalized[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
            }

            if (in_array($key, $integerKeys, true)) {
                if ($value !== null && $value !== '' && is_numeric($value)) {
                    $normalized[$key] = max(0, (int) $value);
                }
                continue;
            }

            if (in_array($key, $numericKeys, true)) {
                if ($value !== null && $value !== '' && is_numeric($value)) {
                    $number = max(0, (float) $value);
                    $normalized[$key] = (int) $number == $number ? (int) $number : $number;
                }
                continue;
            }

            if ($value !== null && $value !== '') {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }
}
