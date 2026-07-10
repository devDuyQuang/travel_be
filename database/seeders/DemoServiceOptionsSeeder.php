<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DemoServiceOptionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->syncProductOptions('fusion-resort', [
            [
                'type' => 'room_type',
                'name' => 'Tầm nhìn vườn hạng sang',
                'description' => 'Phòng hướng vườn, phù hợp 2 người lớn',
                'price' => 2000000,
                'unit' => 'đêm',
                'capacity' => 2,
            ],
            [
                'type' => 'room_type',
                'name' => 'Tầm nhìn ra biển',
                'description' => 'Phòng hướng biển, phù hợp 2 người lớn',
                'price' => 3200000,
                'unit' => 'đêm',
                'capacity' => 2,
            ],
            [
                'type' => 'room_type',
                'name' => 'Phòng cao cấp',
                'description' => 'Phòng suite rộng, phù hợp gia đình',
                'price' => 5500000,
                'unit' => 'đêm',
                'capacity' => 4,
            ],
        ], [
            'location' => 'Đà Nẵng, Việt Nam',
            'duration' => '25 phút từ trung tâm Đà Nẵng',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('nha-trang-golf-tour', [
            [
                'type' => 'tour_package',
                'name' => 'Gói tiêu chuẩn',
                'description' => 'Lịch trình tour golf cơ bản, phù hợp nhóm nhỏ',
                'price' => 3500000,
                'unit' => 'khách',
            ],
            [
                'type' => 'tour_package',
                'name' => 'Gói cao cấp',
                'description' => 'Dịch vụ nâng cấp với lịch trình linh hoạt hơn',
                'price' => 5500000,
                'unit' => 'khách',
            ],
            [
                'type' => 'tour_package',
                'name' => 'Tour riêng tư',
                'description' => 'Tour riêng theo nhóm, tư vấn lịch trình chi tiết',
                'price' => 8000000,
                'unit' => 'khách',
            ],
        ], [
            'location' => 'Nha Trang, Việt Nam',
            'duration' => '3 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('thue-xe-du-lich-ok', [
            [
                'type' => 'vehicle_type',
                'name' => 'Xe 4 chỗ',
                'description' => 'Phù hợp 1-3 khách và hành lý gọn',
                'price' => 700000,
                'unit' => 'chuyến',
                'capacity' => 4,
            ],
            [
                'type' => 'vehicle_type',
                'name' => 'Xe 7 chỗ',
                'description' => 'Phù hợp gia đình hoặc nhóm nhỏ',
                'price' => 1200000,
                'unit' => 'chuyến',
                'capacity' => 7,
            ],
            [
                'type' => 'vehicle_type',
                'name' => 'Xe 16 chỗ',
                'description' => 'Phù hợp đoàn khách vừa',
                'price' => 2200000,
                'unit' => 'chuyến',
                'capacity' => 16,
            ],
            [
                'type' => 'vehicle_type',
                'name' => 'Limousine',
                'description' => 'Dòng xe cao cấp, không gian rộng và tiện nghi',
                'price' => 3500000,
                'unit' => 'chuyến',
                'capacity' => 9,
            ],
        ], [
            'name' => 'Thuê Xe Du Lịch OK',
            'location' => 'Đà Nẵng, Việt Nam',
            'duration' => '25 phút từ trung tâm Đà Nẵng',
            'review_rating' => 5,
            'review_count' => 5,
            'badge_text' => 'Giảm 40%',
        ]);

        $this->syncProductOptions('pho-co-hoi-an', [
            [
                'type' => 'ticket_type',
                'name' => 'Vé người lớn',
                'description' => 'Vé tham quan dành cho người lớn',
                'price' => 300000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Vé trẻ em',
                'description' => 'Vé tham quan dành cho trẻ em',
                'price' => 150000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Combo gia đình',
                'description' => 'Gói vé gia đình, tư vấn theo số lượng khách',
                'price' => 900000,
                'unit' => 'gói',
                'capacity' => 4,
            ],
        ], [
            'name' => 'Phố Cổ Hội An',
            'location' => 'Hội An, Việt Nam',
            'duration' => '7 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('tan-son-nhat-golf-course', [
            [
                'type' => 'golf_package',
                'name' => 'Ngày thường',
                'description' => 'Gói chơi golf ngày thường',
                'price' => 2500000,
                'unit' => 'golfer',
            ],
            [
                'type' => 'golf_package',
                'name' => 'Cuối tuần',
                'description' => 'Gói chơi golf cuối tuần',
                'price' => 3200000,
                'unit' => 'golfer',
            ],
            [
                'type' => 'time_slot',
                'name' => 'Khung giờ sáng',
                'description' => 'Khung giờ tee time buổi sáng',
                'price' => 2800000,
                'unit' => 'golfer',
            ],
        ], [
            'location' => 'TP. Hồ Chí Minh, Việt Nam',
            'duration' => '18 hố',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('ngu-hanh-son', [
            [
                'type' => 'ticket_type',
                'name' => 'Vé người lớn',
                'description' => 'Vé tham quan Ngũ Hành Sơn dành cho người lớn',
                'price' => 250000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Vé trẻ em',
                'description' => 'Vé tham quan Ngũ Hành Sơn dành cho trẻ em',
                'price' => 120000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Combo gia đình',
                'description' => 'Gói tham quan gia đình, phù hợp nhóm nhỏ',
                'price' => 650000,
                'unit' => 'gói',
                'capacity' => 4,
            ],
        ], [
            'name' => 'Ngũ Hành Sơn',
            'location' => 'Đà Nẵng, Việt Nam',
            'duration' => '2 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('ba-na-hills', [
            [
                'type' => 'ticket_type',
                'name' => 'Vé người lớn',
                'description' => 'Vé tham quan Bà Nà Hills dành cho người lớn',
                'price' => 950000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Vé trẻ em',
                'description' => 'Vé tham quan Bà Nà Hills dành cho trẻ em',
                'price' => 750000,
                'unit' => 'vé',
            ],
            [
                'type' => 'ticket_type',
                'name' => 'Combo gia đình',
                'description' => 'Combo tham quan cho gia đình, tư vấn theo số khách',
                'price' => 2900000,
                'unit' => 'gói',
                'capacity' => 4,
            ],
        ], [
            'name' => 'Bà Nà Hills',
            'location' => 'Đà Nẵng, Việt Nam',
            'duration' => '5 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('thue-limousine', [
            [
                'type' => 'vehicle_type',
                'name' => 'Limousine',
                'description' => 'Xe limousine cao cấp, phù hợp nhóm nhỏ cần không gian riêng',
                'price' => 3500000,
                'unit' => 'chuyến',
                'capacity' => 9,
            ],
        ], [
            'location' => 'TP. Hồ Chí Minh, Việt Nam',
            'duration' => '3 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('thue-carnival', [
            [
                'type' => 'vehicle_type',
                'name' => 'Kia Carnival',
                'description' => 'Xe gia đình rộng rãi, phù hợp di chuyển nội thành và liên tỉnh',
                'price' => 1800000,
                'unit' => 'chuyến',
                'capacity' => 7,
            ],
        ], [
            'location' => 'Đà Nẵng, Việt Nam',
            'duration' => '5 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);

        $this->syncProductOptions('thue-fortuner', [
            [
                'type' => 'vehicle_type',
                'name' => 'Toyota Fortuner',
                'description' => 'Xe SUV phù hợp gia đình hoặc nhóm nhỏ',
                'price' => 1200000,
                'unit' => 'chuyến',
                'capacity' => 7,
            ],
        ], [
            'location' => 'Hà Nội, Việt Nam',
            'duration' => '7 ngày',
            'review_rating' => 5,
            'review_count' => 5,
        ]);
    }

    /**
     * @param array<int, array<string, mixed>> $options
     */
    private function syncProductOptions(string $slug, array $options, array $productUpdates = []): void
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->first();

        if (! $product) {
            $this->command?->warn("Product not found: {$slug}");

            return;
        }

        $types = collect($options)->pluck('type')->unique()->values()->all();
        $names = collect($options)->pluck('name')->all();

        $product->serviceOptions()
            ->whereIn('type', $types)
            ->whereNotIn('name', $names)
            ->update(['is_active' => false]);

        foreach ($options as $index => $option) {
            $product->serviceOptions()->updateOrCreate(
                [
                    'type' => $option['type'],
                    'name' => $option['name'],
                ],
                [
                    'description' => $option['description'] ?? null,
                    'price' => $option['price'],
                    'currency' => 'VND',
                    'unit' => $option['unit'],
                    'capacity' => $option['capacity'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                    'metadata' => $option['metadata'] ?? null,
                ],
            );
        }

        $lowestPrice = collect($options)->min('price');

        $product->forceFill([
            'price' => $lowestPrice,
            'price_discount' => null,
            'regular_price' => null,
            'sale_price' => null,
            ...$productUpdates,
        ])->save();
    }
}
