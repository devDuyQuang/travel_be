<?php

namespace Tests\Feature;

use App\Http\Controllers\SettingController;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AboutSettingPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_hero_text_is_persisted_and_returned_after_save(): void
    {
        $request = Request::create('/setting/about-hero', 'POST', [
            'type' => 'travel',
            'title' => 'Về Golfnity',
            'sub_title' => 'Câu chuyện của chúng tôi',
            'remove_banner_hero_file' => '0',
        ]);

        $response = app(SettingController::class)->updateAboutHero($request);
        $saved = Setting::where('key', 'about_page_hero_travel')->firstOrFail();

        $this->assertSame('Về Golfnity', $saved->value['title']);
        $this->assertSame('Câu chuyện của chúng tôi', $saved->value['sub_title']);
        $this->assertSame($saved->value, $response->getData(true)['value']);
    }

    public function test_about_intro_and_values_are_persisted_without_losing_nested_items(): void
    {
        $controller = app(SettingController::class);

        $controller->updateAboutIntro(Request::create('/setting/about-intro', 'POST', [
            'type' => 'travel',
            'subtitle' => 'Khám phá thế giới cùng chúng tôi',
            'title' => 'Kỳ nghỉ hoàn hảo',
            'description' => 'Nội dung giới thiệu',
            'button_text' => 'Đặt ngay',
            'button_link' => '/tour-details',
        ]));

        $controller->updateAboutValues(Request::create('/setting/about-values', 'POST', [
            'type' => 'travel',
            'choose_subtitle' => 'Điều chúng tôi làm',
            'choose_title' => 'Giá trị nổi bật',
            'choose_description' => 'Mô tả nhóm giá trị',
            'items' => [
                [
                    'icon' => 'ti tabler-star',
                    'title' => 'Trải nghiệm đáng nhớ',
                    'description' => 'Dịch vụ được thiết kế riêng.',
                ],
            ],
        ]));

        $intro = Setting::where('key', 'about_page_intro_travel')->firstOrFail()->value;
        $values = Setting::where('key', 'about_page_values_travel')->firstOrFail()->value;

        $this->assertSame('Kỳ nghỉ hoàn hảo', $intro['title']);
        $this->assertSame('/tour-details', $intro['button_link']);
        $this->assertCount(1, $values['items']);
        $this->assertSame('Trải nghiệm đáng nhớ', $values['items'][0]['title']);
    }
}
