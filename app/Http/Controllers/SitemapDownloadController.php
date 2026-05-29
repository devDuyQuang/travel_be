<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SitemapDownloadController extends Controller
{
    /**
     * Download all 4 sitemap files (home, genre, movie, index) as a single zip.
     */
    public function downloadAll(Request $request)
    {
        $host = $request->getHost(); // e.g. admin.sacduc18.com
        $dir = public_path('sitemaps/' . str_replace([':', '/', '\\'], '-', $host));
        $files = [
            'home-sitemap.xml',
            'genre-sitemap.xml',
            'movie-sitemap.xml',
            'sitemap.xml',
        ];

        // Ensure output dir exists
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // 1) Try to generate sitemaps in-process using Artisan::call
        try {
            Log::info('SitemapDownload: running Artisan::call sitemap:generate', ['host' => $host]);
            $exitCode = Artisan::call('sitemap:generate', ['--host' => $host]);
            $artOutput = Artisan::output();
            Log::info('SitemapDownload: Artisan output', ['exit' => $exitCode, 'output' => $artOutput]);
        } catch (\Throwable $e) {
            Log::error('SitemapDownload: Artisan::call threw exception', ['err' => $e->getMessage()]);
        }

        // 2) If some files still missing, try running CLI php artisan (fallback)
        $missing = $this->missingFiles($dir, $files);
        if (!empty($missing)) {
            Log::warning('SitemapDownload: files missing after Artisan::call, attempting CLI fallback', ['missing' => $missing]);

            // Try Symfony Process if available
            if (class_exists(Process::class)) {
                try {
                    $php = PHP_BINARY; // path to current php binary
                    $cmd = [$php, base_path('artisan'), 'sitemap:generate', '--host=' . $host];

                    $process = new Process($cmd);
                    // increase timeout for large sites:
                    $process->setTimeout(300); // 5 minutes, adjust if needed
                    $process->run();

                    if (!$process->isSuccessful()) {
                        Log::error('SitemapDownload: CLI process failed', ['exit' => $process->getExitCode(), 'stderr' => $process->getErrorOutput()]);
                    } else {
                        Log::info('SitemapDownload: CLI process finished', ['output' => $process->getOutput()]);
                    }
                } catch (ProcessFailedException $pf) {
                    Log::error('SitemapDownload: Symfony Process failed', ['err' => $pf->getMessage()]);
                } catch (\Throwable $e) {
                    Log::error('SitemapDownload: Exception while running CLI fallback', ['err' => $e->getMessage()]);
                }
            } else {
                Log::warning('SitemapDownload: Symfony Process class not available; cannot run CLI fallback.');
            }
        }

        // Final check for missing files
        $notFound = $this->missingFiles($dir, $files);
        if (!empty($notFound)) {
            Log::error('SitemapDownload: final files missing, aborting download', ['missing' => $notFound]);
            return abort(404, 'Some sitemap files are missing after generation attempts: ' . implode(', ', $notFound));
        }

        // Create zip in temp
        $zipName = 'sitemaps-' . str_replace([':', '/', '\\'], '-', $host) . '-' . date('YmdHis') . '.zip';
        $tmpFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error('SitemapDownload: could not create zip file', ['tmp' => $tmpFile]);
            return abort(500, 'Could not create zip file.');
        }

        foreach ($files as $f) {
            $full = $dir . DIRECTORY_SEPARATOR . $f;
            // Add file with top-level filename (no folder inside zip)
            $zip->addFile($full, $f);
        }

        $zip->close();

        // Stream the zip and delete after send
        return response()->download($tmpFile, $zipName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Return list of filenames that are missing in directory.
     */
    protected function missingFiles(string $dir, array $files): array
    {
        $missing = [];
        foreach ($files as $f) {
            if (!File::exists($dir . DIRECTORY_SEPARATOR . $f)) {
                $missing[] = $f;
            }
        }
        return $missing;
    }
}
