<?php

namespace Tests\Feature;

use App\Http\Controllers\HomepageSettingController;
use App\Http\Controllers\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;
use Tests\TestCase;

class SettingImageRemovalTest extends TestCase
{
    public function test_public_setting_image_is_deleted_when_remove_flag_is_sent(): void
    {
        $relativePath = 'uploads/settings/removal-test.jpg';
        $absolutePath = public_path($relativePath);

        if (!is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0755, true);
        }

        file_put_contents($absolutePath, 'test-image');

        $request = Request::create('/', 'PUT', [
            'remove_banner_hero_file' => '1',
        ]);

        $method = new ReflectionMethod(SettingController::class, 'updatePublicSettingImage');
        $result = $method->invoke(
            app(SettingController::class),
            $request,
            'banner_hero_file',
            'remove_banner_hero_file',
            $relativePath,
            'test'
        );

        $this->assertSame('', $result);
        $this->assertFileDoesNotExist($absolutePath);
    }

    public function test_homepage_image_is_deleted_from_public_disk_when_remove_flag_is_sent(): void
    {
        Storage::fake('public');
        $path = 'uploads/default/setting/homepage/about/removal-test.jpg';
        Storage::disk('public')->put($path, 'test-image');

        $request = Request::create('/', 'PUT', [
            'remove_image' => '1',
        ]);

        $method = new ReflectionMethod(HomepageSettingController::class, 'singleImage');
        $result = $method->invoke(
            app(HomepageSettingController::class),
            $request,
            'image_file',
            'remove_image',
            $path,
            'about'
        );

        $this->assertSame('', $result);
        Storage::disk('public')->assertMissing($path);
    }
}
