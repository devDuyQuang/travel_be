<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\SettingController;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class HomepageSettingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_api_does_not_allow_empty_typed_key_to_shadow_canonical_data(): void
    {
        $hero = [
            'slides' => [
                [
                    'title' => 'Banner từ CMS',
                    'image' => 'uploads/localhost/setting/homepage/hero/banner.jpg',
                    'enabled' => true,
                ],
            ],
        ];

        Setting::create(['key' => 'hero_home', 'value' => $hero]);
        Setting::create(['key' => 'hero_home_travel', 'value' => []]);

        $request = Request::create('/setting?keys=hero_home', 'GET', [
            'keys' => 'hero_home',
        ]);
        $response = app(SettingController::class)->index($request);
        $payload = $response->getData(true);

        $this->assertSame($hero, $payload['data']['hero_home']);
    }
}
