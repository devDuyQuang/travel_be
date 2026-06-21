<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::updateOrCreate(
            [
                'slug' => 'dat-tee-time',
            ],
            [
                'name' => 'Đặt tee time',
                'type' => 'service',
                'status' => 1,
                'sort' => 0,
                'order_position' => 1,
            ]
        );

        Product::updateOrCreate(
            [
                'slug' => 'ba-na-hills-golf-club',
            ],
            [
                'name' => 'Ba Na Hills Golf Club',
                'category_id' => $category->id,

                'price' => 154.90,
                'price_discount' => 117.65,

                'location' => 'Danang (6 km from Danang center)',
                'duration' => '25 minutes from Danang center',

                'review_rating' => 4.4,
                'review_count' => '1,267 Google reviews',
                'established_year' => 2016,

                'description' => 'Bà Nà Hills Golf Club là sân golf đẳng cấp tại miền Trung Việt Nam, được thiết kế bởi Luke Donald. Sân có địa hình tự nhiên, cảnh quan núi rừng và hệ thống tiện ích phù hợp cho golfer ở nhiều trình độ.',

                'highlight' => "Nằm cạnh khu du lịch sinh thái Suối Mơ nổi tiếng.\nCách thành phố Đà Nẵng khoảng 25 phút lái xe.\nLà điểm đến phù hợp cho golfer khi đến Đà Nẵng.",

                'facility' => 'Green fee 18 holes, Caddy fee, Shared golf cart.',

                'content' => '<p>Một sân golf đẳng cấp thế giới khác tại miền Trung Việt Nam, Đà Nẵng là Câu lạc bộ Golf Bà Nà Hills. Là sân golf đầu tiên được thiết kế bởi tay golf hàng đầu Luke Donald, sân Golf Bà Nà Hills được đầu tư và quản lý bởi Tập đoàn IMG và Tập đoàn SunGroup.</p>',

                'video_url' => 'https://www.youtube.com/watch?v=qemC_eYFzIA',

                'image' => null,
                'image_original_name' => null,
                'gallery_image_1' => null,
                'gallery_image_1_original_name' => null,
                'gallery_image_2' => null,
                'gallery_image_2_original_name' => null,

                'status' => 1,
                'sort' => 0,
                'order_position' => 1,
            ]
        );
    }
}
