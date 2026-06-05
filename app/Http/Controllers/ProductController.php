<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
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
                'id',
                'name',
                'slug',
                'category_id',
                'image',
                'description',
                'price',
                'price_discount',
                'status',
                'created_at',
                'created_by',

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
                'price' => $item->price,
                'price_discount' => $item->price_discount,
                'status' => (int) $item->status,
                'creator' => optional($item->creator)->name ?? '—',
                'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                '__details' => '',
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

        $newPath = null;

        try {
            if ($request->hasFile('image')) {
                $uploadDir = 'products';
                $filename = Str::slug($data['name']) . '_' . time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                $newPath = $request->file('image')->storeAs($uploadDir, $filename, 'public');
                $data['image'] = $newPath;
            }

            $data['created_by'] = Auth::id();
            $data['order_position'] = (int) Product::max('order_position') + 1;

            $this->model->create($data);

            $msg = __('messages.data_saved') ?: 'Thêm mới thành công';

            return $request->wantsJson()
                ? response()->json([
                    'message' => $msg,
                    'redirect_url' => panel_route(module() . '.index'),
                ])
                : redirect()->to(panel_route(module() . '.index'))->with('success', $msg);
        } catch (\Throwable $e) {
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('Create product error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function edit(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);
        $categories = $this->getCategoryOptions();

        $currentImageUrl = $item->image ? Storage::url($item->image) : null;

        return view('product.edit', compact('item', 'categories', 'currentImageUrl'));
    }

    public function update(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

        $data = $this->validateProductData($request, $item->id);

        $oldPath = $item->image;
        $newPath = null;
        $wantRemove = $request->boolean('remove_image');

        try {
            if ($request->hasFile('image')) {
                $uploadDir = 'products';
                $filename = Str::slug($data['name']) . '_' . time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();

                $newPath = $request->file('image')->storeAs($uploadDir, $filename, 'public');
                $data['image'] = $newPath;
            } elseif ($wantRemove) {
                $data['image'] = null;
            }

            $data['updated_by'] = Auth::id();

            $item->update($data);

            if ($oldPath && ($newPath || $wantRemove) && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $msg = __('messages.data_saved') ?: 'Cập nhật thành công';

            return $request->wantsJson()
                ? response()->json([
                    'message' => $msg,
                    'redirect_url' => null,
                ])
                : redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            if ($newPath && Storage::disk('public')->exists($newPath)) {
                Storage::disk('public')->delete($newPath);
            }

            Log::error('Update product error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => __('messages.unexpected_error') ?: 'Có lỗi xảy ra.',
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $domain, $id)
    {
        $item = $this->model->findOrFail($id);

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
        $item = $this->model->findOrFail($id);

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Xóa thành công'])
            : redirect()->to(panel_route(module() . '.index'))->with('success', 'Xóa thành công');
    }

    private function validateProductData(Request $request, ?int $id = null): array
    {
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
            'remove_image' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'price_discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'sort' => ['nullable', 'integer', 'min:0'],
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
        ]);
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
