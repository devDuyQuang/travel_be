<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GolfnityMenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Menu::query()
                ->where('location', 'header')
                ->whereIn('name', ['Home', 'Trang chủ', 'About', 'Service', 'Blogs', 'Contact'])
                ->update(['status' => 0]);

            $tree = [
                [
                    'name' => 'Dịch vụ',
                    'path' => '/dat-tee-time',
                    'children' => [
                        ['name' => 'Đặt tee time', 'path' => '/dat-tee-time'],
                        ['name' => 'Tour golf Việt Nam', 'path' => '/tour-golf'],
                        ['name' => 'Khách sạn & nghỉ dưỡng', 'path' => '/khach-san-nghi-duong'],
                        ['name' => 'Thuê xe & đưa đón', 'path' => '/thue-xe-dua-don'],
                        ['name' => 'Tham quan & trải nghiệm', 'path' => '/tham-quan-trai-nghiem'],
                    ],
                ],
                [
                    'name' => 'Giải pháp golf',
                    'path' => '/dich-vu/to-chuc-giai-golf',
                    'children' => [
                        ['name' => 'Tổ chức giải golf', 'path' => '/dich-vu/to-chuc-giai-golf'],
                        ['name' => 'Tổ chức sự kiện golf', 'path' => '/dich-vu/to-chuc-su-kien-golf'],
                        ['name' => 'Thi công phòng golf 3D', 'path' => '/dich-vu/thi-cong-phong-golf-3d'],
                        ['name' => 'Thi công sân tập golf', 'path' => '/dich-vu/thi-cong-san-tap-golf'],
                        ['name' => 'Thi công sân putting', 'path' => '/dich-vu/thi-cong-san-putting'],
                        ['name' => 'Cho thuê mini golf', 'path' => '/dich-vu/cho-thue-mini-golf'],
                    ],
                ],
                ['name' => 'Tin tức', 'path' => '/blog-grid', 'children' => []],
                ['name' => 'Về Golfnity', 'path' => '/about', 'children' => []],
                ['name' => 'Liên hệ', 'path' => '/contact', 'children' => []],
            ];

            foreach ($tree as $parentPosition => $parentData) {
                $parent = $this->upsertMenu(
                    $parentData['name'],
                    $parentData['path'],
                    null,
                    $parentPosition + 1
                );

                foreach ($parentData['children'] as $childPosition => $childData) {
                    $this->upsertMenu(
                        $childData['name'],
                        $childData['path'],
                        $parent->id,
                        $childPosition + 1
                    );
                }
            }
        });
    }

    private function upsertMenu(
        string $name,
        string $path,
        ?int $parentId,
        int $position
    ): Menu {
        return Menu::query()->updateOrCreate(
            [
                'name' => $name,
                'location' => 'header',
            ],
            [
                'topic' => 'custom',
                'part_id' => null,
                'path' => $path,
                'parent_id' => $parentId,
                'status' => 1,
                'sort' => $position,
                'order_position' => $position,
            ]
        );
    }
}
