<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CopyServicePostsToProducts extends Command
{
    protected $signature = 'products:copy-service-posts
        {--category=* : Chỉ copy từ các category service có slug này}';

    protected $description = 'Copy nội dung dịch vụ từ Post sang Product mà không xóa Post nguồn';

    public function handle(): int
    {
        $created = 0;
        $skipped = 0;
        $failed = 0;

        $requestedSlugs = collect($this->option('category'))
            ->filter()
            ->unique()
            ->values();

        $categories = Category::query()
            ->where('status', 1)
            ->whereRaw('LOWER(type) = ?', ['service'])
            ->when(
                $requestedSlugs->isNotEmpty(),
                fn ($query) => $query->whereIn('slug', $requestedSlugs)
            )
            ->orderBy('order_position')
            ->orderBy('name')
            ->get();

        if ($requestedSlugs->isNotEmpty()) {
            $missingSlugs = $requestedSlugs->diff($categories->pluck('slug'));

            foreach ($missingSlugs as $slug) {
                $failed++;
                $this->logFailure("Không tìm thấy category service active: {$slug}");
            }
        }

        if ($categories->isEmpty()) {
            $this->warn('Không có category service active để xử lý.');

            return $failed > 0 ? self::FAILURE : self::SUCCESS;
        }

        foreach ($categories as $category) {
            $posts = Post::query()
                ->whereHas('categories', fn ($query) => $query->whereKey($category->id))
                ->orderBy('id')
                ->get();

            foreach ($posts as $post) {
                if (Product::query()->where('slug', $post->slug)->exists()) {
                    $skipped++;
                    $this->line("Bỏ qua [{$post->slug}]: Product đã tồn tại.");

                    continue;
                }

                try {
                    Product::create([
                        'category_id' => $category->id,
                        'name' => $post->name,
                        'slug' => $post->slug,
                        'description' => $post->description,
                        'content' => $post->content,
                        'image' => $post->image,
                        'title_seo' => $post->title_seo,
                        'description_seo' => $post->description_seo,
                        'canonical_url' => $post->canonical_seo,
                        'status' => $post->status,
                        'order_position' => (int) Product::max('order_position') + 1,
                        'created_by' => $post->created_by,
                        'updated_by' => $post->updated_by,
                    ]);

                    $created++;
                    $message = "Đã copy [{$post->slug}] vào category [{$category->slug}].";
                    $this->info($message);
                    Log::info('Copy service Post to Product succeeded', [
                        'post_id' => $post->id,
                        'product_slug' => $post->slug,
                        'category_slug' => $category->slug,
                    ]);
                } catch (\Throwable $exception) {
                    $failed++;
                    $this->logFailure(
                        "Copy thất bại [{$post->slug}]: {$exception->getMessage()}",
                        $post,
                        $category->slug,
                        $exception
                    );
                }
            }
        }

        $this->newLine();
        $this->info("Hoàn tất: tạo {$created}, bỏ qua {$skipped}, lỗi {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function logFailure(
        string $message,
        ?Post $post = null,
        ?string $categorySlug = null,
        ?\Throwable $exception = null
    ): void {
        $this->error($message);
        Log::error('Copy service Post to Product failed', [
            'message' => $message,
            'post_id' => $post?->id,
            'post_slug' => $post?->slug,
            'category_slug' => $categorySlug,
            'exception' => $exception,
        ]);
    }
}
