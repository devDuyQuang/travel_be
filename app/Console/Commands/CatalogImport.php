<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ServiceProductOption;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CatalogImport extends Command
{
    protected $signature = 'golfnity:catalog-import
        {path : Catalog JSON path}
        {--dry-run : Preview changes without mutating the database}
        {--apply : Persist the import inside one database transaction}';

    protected $description = 'Import product catalog data from a versioned JSON file without touching runtime records.';

    private array $summary = [];

    private array $missingMedia = [];

    public function handle(): int
    {
        if ($this->option('dry-run') === $this->option('apply')) {
            $this->error('Choose exactly one mode: --dry-run or --apply.');

            return self::FAILURE;
        }

        $payload = $this->readPayload($this->argument('path'));
        if ($payload === null) {
            return self::FAILURE;
        }

        $this->resetSummary();
        $this->auditMedia($payload['media_references'] ?? []);

        $mode = $this->option('apply') ? 'apply' : 'dry-run';
        $this->warn('Runtime/customer tables are excluded: bookings, orders, payments, users, sessions, tokens, contacts, comments, logs/jobs/cache.');

        if ($mode === 'apply') {
            $this->warn('Backup first, for example: mysqldump --single-transaction --no-tablespaces "$DB_DATABASE" > backup-before-catalog-import.sql');
            DB::transaction(fn () => $this->syncPayload($payload, true));
        } else {
            $this->syncPayload($payload, false);
        }

        $this->renderSummary($mode);

        return self::SUCCESS;
    }

    private function readPayload(string $path): ?array
    {
        $path = $this->resolvePath($path);
        if (! File::exists($path)) {
            $this->error("Catalog JSON not found: {$path}");

            return null;
        }

        $payload = json_decode(File::get($path), true);
        if (! is_array($payload) || ($payload['version'] ?? null) !== 1) {
            $this->error('Unsupported or invalid catalog JSON. Expected version 1.');

            return null;
        }

        return $payload;
    }

    private function syncPayload(array $payload, bool $apply): void
    {
        $categories = $payload['entities']['categories'] ?? [];
        $products = $payload['entities']['products'] ?? [];

        foreach ($categories as $entry) {
            $this->syncCategory($entry, $apply, false);
        }

        foreach ($categories as $entry) {
            $this->syncCategory($entry, $apply, true);
        }

        foreach ($products as $entry) {
            $this->syncProduct($entry, $apply);
        }
    }

    private function syncCategory(array $entry, bool $apply, bool $syncParent): void
    {
        $slug = $entry['key'] ?? $entry['fields']['slug'] ?? null;
        if (! $slug) {
            $this->bump('categories', 'skipped');

            return;
        }

        $fields = $entry['fields'] ?? [];
        $parentSlug = $entry['parent_slug'] ?? null;
        $category = Category::query()->where('slug', $slug)->first();

        if ($syncParent) {
            if (! $category) {
                return;
            }

            $parentId = $parentSlug ? Category::query()->where('slug', $parentSlug)->value('id') : null;
            if (($category->parent_id ?? null) !== $parentId) {
                $this->bump('relations', 'would_sync');
                if ($apply) {
                    $category->parent_id = $parentId;
                    $category->save();
                }
            } else {
                $this->bump('relations', 'unchanged');
            }

            return;
        }

        unset($fields['parent_id'], $fields['created_by'], $fields['updated_by']);
        $fields['slug'] = $slug;

        if (! $category) {
            $this->bump('categories', 'create');
            if ($apply) {
                Category::query()->create($fields);
            }

            return;
        }

        $changes = $this->changesFor($category, $fields);
        if ($changes === []) {
            $this->bump('categories', 'unchanged');

            return;
        }

        $this->bump('categories', 'update');
        if ($apply) {
            $category->fill($changes)->save();
        }
    }

    private function syncProduct(array $entry, bool $apply): void
    {
        $slug = $entry['key'] ?? $entry['fields']['slug'] ?? null;
        if (! $slug) {
            $this->bump('products', 'skipped');

            return;
        }

        $categorySlug = $entry['category_slug'] ?? null;
        $categoryId = $categorySlug ? Category::query()->where('slug', $categorySlug)->value('id') : null;

        $fields = $entry['fields'] ?? [];
        unset($fields['id'], $fields['category_id'], $fields['created_by'], $fields['updated_by']);
        $fields['slug'] = $slug;
        $fields['category_id'] = $categoryId;

        $product = Product::query()->where('slug', $slug)->first();
        if (! $product) {
            $this->bump('products', 'create');
            if ($apply) {
                $product = Product::query()->create($fields);
            } else {
                foreach ($entry['images'] ?? [] as $imageEntry) {
                    if (($imageEntry['fields']['image'] ?? null) !== null) {
                        $this->bump('product_images', 'create');
                    }
                }

                foreach ($entry['service_options'] ?? [] as $optionEntry) {
                    if (($optionEntry['fields']['type'] ?? null) && ($optionEntry['fields']['name'] ?? null)) {
                        $this->bump('service_options', 'create');
                    }
                }

                return;
            }
        } else {
            $changes = $this->changesFor($product, $fields);
            if ($changes === []) {
                $this->bump('products', 'unchanged');
            } else {
                $this->bump('products', 'update');
                if ($apply) {
                    $product->fill($changes)->save();
                }
            }
        }

        $productId = $product?->id ?? Product::query()->where('slug', $slug)->value('id');
        if (! $productId) {
            return;
        }

        foreach ($entry['images'] ?? [] as $imageEntry) {
            $this->syncProductImage((int) $productId, $imageEntry, $apply);
        }

        foreach ($entry['service_options'] ?? [] as $optionEntry) {
            $this->syncServiceOption((int) $productId, $optionEntry, $apply);
        }
    }

    private function syncProductImage(int $productId, array $entry, bool $apply): void
    {
        $fields = $entry['fields'] ?? [];
        $path = $fields['image'] ?? null;
        if (! $path) {
            $this->bump('product_images', 'skipped');

            return;
        }

        $fields['product_id'] = $productId;
        $image = ProductImage::query()
            ->where('product_id', $productId)
            ->where('image', $path)
            ->first();

        if (! $image) {
            $this->bump('product_images', 'create');
            if ($apply) {
                ProductImage::query()->create($fields);
            }

            return;
        }

        $changes = $this->changesFor($image, $fields);
        if ($changes === []) {
            $this->bump('product_images', 'unchanged');

            return;
        }

        $this->bump('product_images', 'update');
        if ($apply) {
            $image->fill($changes)->save();
        }
    }

    private function syncServiceOption(int $productId, array $entry, bool $apply): void
    {
        $fields = $entry['fields'] ?? [];
        $type = $fields['type'] ?? null;
        $name = $fields['name'] ?? null;
        if (! $type || ! $name) {
            $this->bump('service_options', 'skipped');

            return;
        }

        $fields['service_product_id'] = $productId;
        $option = ServiceProductOption::query()
            ->where('service_product_id', $productId)
            ->where('type', $type)
            ->where('name', $name)
            ->first();

        if (! $option) {
            $this->bump('service_options', 'create');
            if ($apply) {
                ServiceProductOption::query()->create($fields);
            }

            return;
        }

        $changes = $this->changesFor($option, $fields);
        if ($changes === []) {
            $this->bump('service_options', 'unchanged');

            return;
        }

        $this->bump('service_options', 'update');
        if ($apply) {
            $option->fill($changes)->save();
        }
    }

    private function changesFor($model, array $fields): array
    {
        $changes = [];
        foreach ($fields as $field => $value) {
            if (! $this->sameValue($model->{$field}, $value)) {
                $changes[$field] = $value;
            }
        }

        return $changes;
    }

    private function sameValue($current, $incoming): bool
    {
        if (is_array($current) || is_array($incoming)) {
            return json_encode($current ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                === json_encode($incoming ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_bool($current) || is_bool($incoming)) {
            return (bool) $current === (bool) $incoming;
        }

        if (is_numeric($current) && is_numeric($incoming)) {
            return (string) $current === (string) $incoming
                || number_format((float) $current, 2, '.', '') === number_format((float) $incoming, 2, '.', '');
        }

        return (string) $current === (string) $incoming;
    }

    private function auditMedia(array $references): void
    {
        foreach ($references as $reference) {
            $path = $reference['storage_path'] ?? $this->normalizeMediaPath($reference['path'] ?? null);
            if ($path && ! Storage::disk('public')->exists($path)) {
                $this->missingMedia[$path] = $reference + ['storage_path' => $path];
            }
        }
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

    private function resetSummary(): void
    {
        $this->summary = [];
        foreach (['categories', 'products', 'product_images', 'service_options', 'relations'] as $entity) {
            $this->summary[$entity] = [
                'create' => 0,
                'update' => 0,
                'unchanged' => 0,
                'would_sync' => 0,
                'skipped' => 0,
            ];
        }
    }

    private function bump(string $entity, string $status): void
    {
        $this->summary[$entity][$status] ??= 0;
        $this->summary[$entity][$status]++;
    }

    private function renderSummary(string $mode): void
    {
        $this->info(strtoupper($mode).' catalog import summary');
        $this->table(
            ['Entity', 'Create', 'Update', 'Unchanged', 'Relation sync', 'Skipped'],
            collect($this->summary)->map(fn ($row, $entity) => [
                $entity,
                $row['create'],
                $row['update'],
                $row['unchanged'],
                $row['would_sync'],
                $row['skipped'],
            ])->values()->all()
        );

        if ($this->missingMedia !== []) {
            $this->warn('Missing public storage media files on this environment: '.count($this->missingMedia));
            foreach (array_slice(array_keys($this->missingMedia), 0, 30) as $path) {
                $this->line(' - storage/app/public/'.$path);
            }
            if (count($this->missingMedia) > 30) {
                $this->line(' - ...');
            }
            $this->comment('DB paths can be synced by this import, but image files must be copied separately, e.g. rsync -av storage/app/public/ user@production:/path/to/app/storage/app/public/');
        } else {
            $this->info('All referenced public storage media files exist on this environment.');
        }
    }

    private function resolvePath(string $path): string
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR) ? $path : base_path($path);
    }
}
