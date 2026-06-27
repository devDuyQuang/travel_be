<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\SettingController as ApiSettingController;
use App\Http\Controllers\SettingController;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContactSettingPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_hero_text_is_persisted_with_the_banner_value(): void
    {
        Setting::create([
            'key' => 'contact_page_hero_travel',
            'value' => ['banner_hero' => 'uploads/settings/contact-banner.jpg'],
        ]);

        $response = app(SettingController::class)->updateContactHero(
            Request::create('/setting/contact-hero', 'POST', [
                'type' => 'travel',
                'title' => 'Liên hệ Golfnity',
                'sub_title' => 'Liên hệ',
                'remove_banner_hero_file' => '0',
            ])
        );

        $saved = Setting::where('key', 'contact_page_hero_travel')->firstOrFail()->value;

        $this->assertSame('Liên hệ Golfnity', $saved['title']);
        $this->assertSame('Liên hệ', $saved['sub_title']);
        $this->assertSame('uploads/settings/contact-banner.jpg', $saved['banner_hero']);
        $this->assertSame($saved, $response->getData(true)['value']);
    }

    public function test_contact_locations_are_returned_by_the_public_setting_api(): void
    {
        $locations = [
            'section_title' => 'Vị trí Golfnity',
            'items' => [
                [
                    'name' => 'Sân Golf Long Thành',
                    'address' => 'Đồng Nai, Việt Nam',
                    'service_time' => 'Thứ 2 - Thứ 6',
                    'google_link' => 'https://maps.google.com/?q=Long+Thanh',
                    'map_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=test"></iframe>',
                ],
            ],
        ];

        Setting::create([
            'key' => 'contact_page_locations_travel',
            'value' => $locations,
        ]);

        $response = app(ApiSettingController::class)->index(
            Request::create('/setting', 'GET', [
                'keys' => 'contact_page_locations',
            ])
        );

        $this->assertSame(
            $locations,
            $response->getData(true)['data']['contact_page_locations']
        );
    }
}
