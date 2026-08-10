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
                ->whereIn('name', ['Home', 'Trang chủ', 'About', 'Service', 'Blogs', 'Contact', 'Tin tức', 'Về Golfnity', 'Giải pháp golf'])
                ->update(['status' => 0]);

            $tree = [
                [
                    'name' => 'Dịch vụ',
                    'path' => '/dich-vu/golf',
                    'children' => [
                        ['name' => 'Đặt sân golf / Tee time', 'path' => '/dich-vu/golf'],
                        ['name' => 'Tour golf Việt Nam', 'path' => '/dich-vu/golf'],
                        ['name' => 'Combo golf & nghỉ dưỡng', 'path' => '/dich-vu/golf'],
                        ['name' => 'Tổ chức giải golf', 'path' => '/dich-vu/to-chuc-giai-golf'],
                        ['name' => 'Sự kiện golf doanh nghiệp', 'path' => '/dich-vu/to-chuc-su-kien-golf'],
                        ['name' => 'Phòng golf 3D', 'path' => '/dich-vu/thi-cong-phong-golf-3d'],
                        ['name' => 'Sân tập / putting / mini golf', 'path' => '/dich-vu/thi-cong-san-tap-golf'],
                    ],
                ],
                ['name' => 'Điểm đến', 'path' => '/dich-vu/tour-trai-nghiem', 'children' => []],
                ['name' => 'Ưu đãi', 'path' => '/dich-vu/golf?tag=featured', 'children' => []],
                ['name' => 'Tin tức & Cẩm nang', 'path' => '/tin-tuc', 'children' => []],
                ['name' => 'Về GOLFNITY', 'path' => '/ve-golfnity', 'children' => []],
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
