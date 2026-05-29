<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--host=}';
    protected $description = 'Generate sitemap for homepage, genres, and movies for a specific host/tenant. Use --host=tenant.domain.com';

    public function handle()
    {
        $this->info('🚀 Generating sitemaps...');

        // Determine host
        $host = $this->option('host') ?: $this->getHostFromAppUrl();
        if (!$host) {
            $this->error('No host provided and app.url not set. Use --host=yourdomain.com');
            return Command::FAILURE;
        }
        $this->info("Target host: {$host}");

        // Extract public domain (strip admin./www./api.)
        $publicDomain = $this->publicDomainFromHost($host);
        $this->info("Public domain for links: {$publicDomain}");

        // Switch DB for tenant
        if (class_exists(\App\Services\TenantSwitcher::class)) {
            try {
                $switcher = app()->make(\App\Services\TenantSwitcher::class);
                $switcher->switchByDomain($host);
                $this->info('✅ TenantSwitcher connection switched.');
            } catch (\Throwable $e) {
                $this->error('TenantSwitcher failed: ' . $e->getMessage());
                $this->attemptFallbackSwitch($host);
            }
        } else {
            $this->attemptFallbackSwitch($host);
        }

        // Prepare output dir
        $outDir = public_path('sitemaps/' . $this->sanitizeHostDir($host));
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true);
        }

        $scheme = parse_url(config('app.url') ?: 'https://' . $publicDomain, PHP_URL_SCHEME) ?: 'https';
        $publicBase = rtrim($scheme . '://' . $publicDomain, '/');

        // Helper for absolute URLs
        $urlFor = fn(string $path = '/') => rtrim($publicBase . '/' . ltrim($path, '/'), '/');

        /* ---------------------------------------------------------
         * 1. HOME SITEMAP
         * ---------------------------------------------------------
         */
        $homeSitemap = Sitemap::create()
            ->add(
                Url::create($urlFor('/'))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate(now())
            );

        $homePath = $outDir . '/home-sitemap.xml';
        $homeSitemap->writeToFile($homePath);
        $this->info("✅ home-sitemap.xml generated -> {$homePath}");

        /* ---------------------------------------------------------
         * 2. GENRE SITEMAP (link dạng /the-loai/{slug})
         * ---------------------------------------------------------
         */
        $genreSitemap = Sitemap::create();
        $genresQuery = Genre::query();

        if ($this->modelHasColumn(Genre::class, 'domain')) {
            $genresQuery->where('domain', $host);
        }

        try {
            $genresCount = $genresQuery->count();
            $this->info("Genres to process: {$genresCount}");
        } catch (\Throwable $e) {
            $this->error("DB query error when counting genres: " . $e->getMessage());
            return Command::FAILURE;
        }

        $genresQuery->chunk(1000, function ($genres) use ($genreSitemap, $urlFor) {
            foreach ($genres as $genre) {
                $slug = $genre->slug ?? ('genre-' . ($genre->id ?? Str::random(6)));
                $genreUrl = $urlFor('the-loai/' . $slug); // ✅ cập nhật slug /the-loai/
                $genreSitemap->add(
                    Url::create($genreUrl)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($genre->updated_at ?? now())
                );
            }
        });

        $genrePath = $outDir . '/genre-sitemap.xml';
        $genreSitemap->writeToFile($genrePath);
        $this->info("✅ genre-sitemap.xml generated -> {$genrePath}");

        /* ---------------------------------------------------------
         * 3. MOVIE SITEMAP (link dạng /{slug})
         * ---------------------------------------------------------
         */
        $movieSitemap = Sitemap::create();
        $moviesQuery = Movie::query();

        if ($this->modelHasColumn(Movie::class, 'domain')) {
            $moviesQuery->where('domain', $host);
        }

        try {
            $moviesCount = $moviesQuery->count();
            $this->info("Movies to process: {$moviesCount}");
        } catch (\Throwable $e) {
            $this->error("DB query error when counting movies: " . $e->getMessage());
            return Command::FAILURE;
        }

        $moviesQuery->chunk(1000, function ($movies) use ($movieSitemap, $urlFor) {
            foreach ($movies as $movie) {
                $slug = $movie->slug ?? ('movie-' . ($movie->id ?? Str::random(6)));
                $movieUrl = $urlFor('phim-sex/' .$slug);
                $movieSitemap->add(
                    Url::create($movieUrl)
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setLastModificationDate($movie->updated_at ?? now())
                );
            }
        });

        $moviePath = $outDir . '/movie-sitemap.xml';
        $movieSitemap->writeToFile($moviePath);
        $this->info("✅ movie-sitemap.xml generated -> {$moviePath}");

        /* ---------------------------------------------------------
         * 4. SITEMAP INDEX
         * ---------------------------------------------------------
         */
        $index = SitemapIndex::create()
            ->add($urlFor('home-sitemap.xml'))
            ->add($urlFor('genre-sitemap.xml'))
            ->add($urlFor('movie-sitemap.xml'));

        $indexPath = $outDir . '/sitemap.xml';
        $index->writeToFile($indexPath);
        $this->info("✅ sitemap.xml (index) generated -> {$indexPath}");

        $this->info('🎉 All sitemaps generated successfully!');
        $this->line('Sitemap index public URLs:');
        $this->line($urlFor('sitemap.xml'));
        $this->line($urlFor('genre-sitemap.xml'));
        $this->line($urlFor('movie-sitemap.xml'));

        return Command::SUCCESS;
    }

    /* ---------------------------------------------------------
     * HELPERS
     * ---------------------------------------------------------
     */

    protected function attemptFallbackSwitch(string $host): void
    {
        $dbName = $this->mapHostToDatabase($host);
        if (!$dbName) {
            $this->info("No fallback DB mapping for {$host}.");
            return;
        }

        $this->info("Fallback DB switch to {$dbName}");
        try {
            $conn = config('database.default', 'mysql');
            config(["database.connections.{$conn}.database" => $dbName]);
            DB::purge($conn);
            DB::reconnect($conn);
            DB::getPdo();
            $this->info("✅ Fallback switched to {$dbName}");
        } catch (\Throwable $e) {
            $this->error("❌ Failed fallback switch: " . $e->getMessage());
        }
    }

    protected function publicDomainFromHost(string $host): string
    {
        $h = preg_replace('/:\d+$/', '', trim($host));
        $h = Str::lower($h);
        $h = preg_replace('/^(admin|www|api|backend|panel)\./i', '', $h);
        return $h;
    }

    protected function mapHostToDatabase(string $host): ?string
    {
        $host = preg_replace('/:\d+$/', '', $host);
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            array_shift($parts);
        }
        $domainPart = implode('.', $parts);
        return preg_replace('/[^A-Za-z0-9]+/', '_', $domainPart);
    }

    protected function getHostFromAppUrl(): ?string
    {
        $appUrl = config('app.url');
        if (!$appUrl) return null;
        return parse_url($appUrl, PHP_URL_HOST);
    }

    protected function sanitizeHostDir(string $host): string
    {
        return str_replace([':', '/', '\\', '*', '?', '"', '<', '>', '|'], '-', $host);
    }

    protected function modelHasColumn(string $modelClass, string $column): bool
    {
        try {
            $model = new $modelClass;
            return Schema::hasColumn($model->getTable(), $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
