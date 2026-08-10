<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TeeTimeDemoSeeder extends Seeder
{
    private const FALLBACK_GOLF_IMAGE = 'products/ba-na-hills-golf-club-ok_image_1781066720_aZv2Lg4tDgBK.jpg';
    private const BA_NA_EDITORIAL_GALLERY = [
        'products/ba-na-hills-golf-club-ok_gallery_image_1_1781064269_6a28e24da09b5.jpg',
        'products/ba-na-hills-golf-club_gallery_image_2_1780836366_6a25680e91c3f.jpg',
        'products/ba-na-hills-golf-club-ok_gallery_1781065759_6a28e81f5272c.jpg',
    ];
    private const BA_NA_EDITORIAL_CAPTIONS = [
        'Câu lạc bộ golf này vừa được bình chọn là "Sân Golf Tốt Nhất Châu Á", mang đến các tiện nghi và dịch vụ đẳng cấp thế giới.',
        'Thử thách bản thân với nhiều cấp độ chơi khác nhau trên sân golf rộng lớn',
        'Đội ngũ nhân viên thân thiện và chuyên nghiệp sẽ chào đón và đồng hành cùng bạn trên sân cỏ',
    ];
    private const BA_NA_EDITORIAL_DRESS_CODE = [
        'Nam giới nên mặc áo sơ mi có cổ tay và quần tây hoặc quần short chơi golf',
        'Nữ giới nên mặc áo sơ mi có cổ tay và quần tây, quần short hoặc váy dài vừa phải',
        'Chỉ được phép mang giày chơi golf có đinh mềm hoặc không đinh bên trong cơ sở',
    ];
    private const BA_NA_EDITORIAL_CONTENT = '<p>Bạn đang muốn ra sân golf khi đến Đà Nẵng? Vậy thì bạn thật may mắn! Ba Na Hills Golf Club là một cơ sở đẳng cấp thế giới nằm dưới chân núi Bà Nà nổi tiếng trên bờ biển miền Trung của Việt Nam. Chỉ cách đó 25km, trải nghiệm chơi golf của bạn chỉ cách khách sạn ở trung tâm Thành phố Đà Nẵng 25 phút. Hơn nữa, câu lạc bộ golf đặc biệt này ở Bà Nà đã giành được giải thưởng Sân Golf Tốt Nhất Châu Á vào năm 2018 vì nó tự hào có các cơ sở vật chất đẳng cấp thế giới. Câu lạc bộ cung cấp một loạt các độ dài chơi khác nhau, làm cho mỗi lỗ golf trở nên độc đáo. Thêm vào đó, đây là sân golf có đèn chiếu sáng duy nhất ở Đà Nẵng để bạn có thể tận hưởng chơi golf ngay cả khi mặt trời lặn. Gói dịch vụ bao gồm phí sân cỏ, phí người phục vụ và tủ khóa dành cho những người sẽ tham gia khóa học đầy thử thách. Những người mới bắt đầu luôn được chào đón đến thăm cơ sở này vì có những người hướng dẫn chuyên nghiệp và thân thiện tại chỗ để dạy bạn những điều cơ bản về golf. Vì vậy, đối với bất kỳ người chơi golf đầy triển vọng nào, điểm đến chơi golf này nên được đưa vào hành trình của bạn.</p>';
    private const LAGUNA_EDITORIAL_GALLERY = [
        'products/ba-na-hills-golf-club-ok_gallery_image_1_1781064843_6a28e48bda656.jpg',
        'products/ba-na-hills-golf-club-ok_gallery_image_1_1781065999_xcq3ZImzIc7U.jpg',
        'products/ba-na-hills-golf-club-ok_gallery_1781065759_6a28e81f5272c.jpg',
    ];
    private const LAGUNA_EDITORIAL_CAPTIONS = [
        'Laguna Golf Lăng Cô mang đến không gian chơi golf xanh mát giữa cảnh quan núi, biển và khu nghỉ dưỡng miền Trung.',
        'Các hố golf được bố trí theo địa hình tự nhiên, tạo nhịp chơi đa dạng cho cả golfer mới và người chơi nhiều kinh nghiệm.',
        'Dịch vụ sân golf và khu nghỉ dưỡng lân cận giúp hành trình tee time tại Lăng Cô trở nên thư thái và thuận tiện hơn.',
    ];
    private const LAGUNA_EDITORIAL_DRESS_CODE = [
        'Nam giới nên mặc áo sơ mi có cổ tay và quần tây hoặc quần short chơi golf',
        'Nữ giới nên mặc áo sơ mi có cổ tay và quần tây, quần short hoặc váy dài vừa phải',
        'Chỉ được phép mang giày chơi golf có đinh mềm hoặc không đinh bên trong cơ sở',
    ];
    private const LAGUNA_EDITORIAL_CONTENT = '<p>Laguna Golf Lăng Cô nằm trong quần thể nghỉ dưỡng ven biển giữa Huế và Đà Nẵng, phù hợp cho golfer muốn kết hợp một vòng golf thư giãn với kỳ nghỉ miền Trung. Sân được thiết kế hài hòa với địa hình tự nhiên của Lăng Cô, đi qua những khu vực cây xanh, đầm nước và tầm nhìn hướng núi, tạo cảm giác chơi đa dạng nhưng vẫn dễ chịu cho nhiều trình độ. Với không gian yên tĩnh, dịch vụ sân golf đầy đủ và vị trí thuận tiện để kết hợp nghỉ dưỡng, đây là lựa chọn phù hợp cho nhóm bạn, gia đình hoặc khách công tác muốn sắp xếp tee time linh hoạt trong chuyến đi.</p>';

    public function run(): void
    {
        DB::transaction(function () {
            $category = Category::query()->updateOrCreate(
                [
                    'slug' => 'dat-tee-time',
                    'type' => 'service',
                ],
                [
                    'name' => 'Đặt tee time',
                    'status' => 1,
                    'home' => true,
                    'layout_key' => 'tee_time',
                    'sort' => 1,
                    'order_position' => 1,
                ]
            );

            foreach ($this->products() as $data) {
                $existing = Product::query()
                    ->where('slug', $data['slug'])
                    ->first();

                $price = $data['price'] === '__KEEP_OR_NULL__'
                    ? $existing?->price
                    : $data['price'];

                $product = Product::query()->updateOrCreate(
                    ['slug' => $data['slug']],
                    [
                        'name' => $data['name'],
                        'category_id' => $category->id,
                        'product_type' => 'service',
                        'location' => $data['location'],
                        'duration' => $data['duration'],
                        'description' => $data['description'],
                        'highlight' => $data['highlight'],
                        'facility' => $data['facility'],
                        'content' => $data['content'],
                        'price' => $price,
                        'price_discount' => null,
                        'regular_price' => null,
                        'sale_price' => null,
                        'badge_text' => $data['badge_text'],
                        'is_featured' => $data['is_featured'],
                        'status' => 1,
                        'sort' => $data['order_position'],
                        'order_position' => $data['order_position'],
                        'attributes' => $data['attributes'],
                        'image' => $this->resolveImage($existing?->image),
                        'image_original_name' => $existing?->image_original_name,
                        'gallery_image_1' => $this->resolveGalleryImage($data['gallery_images'][0] ?? null, $existing?->gallery_image_1),
                        'gallery_image_2' => $this->resolveGalleryImage($data['gallery_images'][1] ?? null, $existing?->gallery_image_2),
                        'gallery_image_3' => $this->resolveGalleryImage($data['gallery_images'][2] ?? null, $existing?->gallery_image_3),
                    ]
                );

                $this->syncDemoOptions($product, $price);
            }

            Product::query()
                ->where('category_id', $category->id)
                ->with('serviceOptions')
                ->get()
                ->each(fn (Product $product) => $this->syncExistingOptionPackageDetails($product));
        });
    }

    private function syncDemoOptions(Product $product, mixed $basePrice): void
    {
        $basePackagePrice = $this->validVndPrice($basePrice);
        $packageDetails = $this->packageDetailsForProduct($product);

        $options = [
            [
                'name' => '(Golf + Di chuyển) Nhóm 2 người',
                'price' => $basePackagePrice,
                'sort_order' => 1,
                'capacity' => 2,
                'metadata' => [
                    'discount_label' => 'Giảm 10%',
                    'min_quantity' => 2,
                    'max_quantity' => 2,
                    'group_size_min' => 2,
                    'group_size_max' => 2,
                    'package_kind' => 'golf_transfer',
                    'daily_prices' => $this->demoDailyPricesForOption(1),
                ],
            ],
            [
                'name' => '(Golf + Di chuyển) Nhóm 3 - 4 người',
                'price' => $basePackagePrice,
                'sort_order' => 2,
                'capacity' => 4,
                'metadata' => [
                    'discount_label' => 'Giảm 10%',
                    'min_quantity' => 3,
                    'max_quantity' => 4,
                    'group_size_min' => 3,
                    'group_size_max' => 4,
                    'package_kind' => 'golf_transfer',
                    'daily_prices' => $this->demoDailyPricesForOption(2),
                ],
            ],
            [
                'name' => '(Golf + Di chuyển) Nhóm 5+ người',
                'price' => $basePackagePrice,
                'sort_order' => 3,
                'capacity' => 12,
                'metadata' => [
                    'discount_label' => 'Giảm 10%',
                    'min_quantity' => 5,
                    'max_quantity' => 12,
                    'group_size_min' => 5,
                    'group_size_max' => 12,
                    'package_kind' => 'golf_transfer',
                    'daily_prices' => $this->demoDailyPricesForOption(3),
                ],
            ],
            [
                'name' => 'Gói Golf Chỉ Bao Gồm',
                'price' => $basePackagePrice !== null ? max(0, $basePackagePrice - 200000) : null,
                'sort_order' => 4,
                'capacity' => 12,
                'metadata' => [
                    'discount_label' => 'Giảm 200,000đ',
                    'min_quantity' => 1,
                    'max_quantity' => 12,
                    'group_size_min' => 1,
                    'group_size_max' => 12,
                    'package_kind' => 'golf_only',
                    'daily_prices' => $this->demoDailyPricesForOption(4),
                ],
            ],
        ];
        $activeOptionNames = array_column($options, 'name');

        foreach ($options as $option) {
            $existingOption = $product->serviceOptions()
                ->where('type', 'golf_package')
                ->where('name', $option['name'])
                ->first();
            $metadata = is_array($existingOption?->metadata) ? $existingOption->metadata : [];
            $metadata = array_merge($metadata, $option['metadata']);
            $metadata['package_details'] = $packageDetails;

            $product->serviceOptions()->updateOrCreate(
                [
                    'type' => 'golf_package',
                    'name' => $option['name'],
                ],
                [
                    'description' => $option['name'].' dành cho dữ liệu demo Tee Time trong CMS.',
                    'price' => $option['price'],
                    'currency' => 'VND',
                    'unit' => 'golfer',
                    'capacity' => $option['capacity'],
                    'sort_order' => $option['sort_order'],
                    'is_active' => true,
                    'metadata' => $metadata,
                ]
            );
        }

        $product->serviceOptions()
            ->whereIn('type', ['golf_package', 'time_slot'])
            ->whereNotIn('name', $activeOptionNames)
            ->update(['is_active' => false]);

        $product->serviceOptions()
            ->whereIn('type', ['golf_package', 'time_slot'])
            ->whereIn('name', $activeOptionNames)
            ->get()
            ->each(function ($option) use ($packageDetails) {
                $metadata = is_array($option->metadata) ? $option->metadata : [];
                $metadata['package_details'] = $packageDetails;

                $option->forceFill(['metadata' => $metadata])->save();
            });
    }

    private function syncExistingOptionPackageDetails(Product $product): void
    {
        $packageDetails = $this->packageDetailsForProduct($product);

        $product->serviceOptions
            ->whereIn('type', ['golf_package', 'time_slot'])
            ->each(function ($option) use ($packageDetails) {
                $metadata = is_array($option->metadata) ? $option->metadata : [];
                $metadata['package_details'] = $packageDetails;

                $option->forceFill(['metadata' => $metadata])->save();
            });
    }

    private function resolveImage(?string $currentImage): string
    {
        if ($currentImage && File::exists(storage_path('app/public/'.$currentImage))) {
            return $currentImage;
        }

        return self::FALLBACK_GOLF_IMAGE;
    }

    private function resolveGalleryImage(?string $preferredImage, ?string $currentImage): ?string
    {
        if ($preferredImage && File::exists(storage_path('app/public/'.$preferredImage))) {
            return $preferredImage;
        }

        if ($currentImage && File::exists(storage_path('app/public/'.$currentImage))) {
            return $currentImage;
        }

        return null;
    }

    private function validVndPrice(mixed $price): ?int
    {
        if ($price === null || $price === '' || ! is_numeric($price)) {
            return null;
        }

        $value = (int) $price;

        return $value >= 100000 ? $value : null;
    }

    private function demoDailyPricesForOption(int $sortOrder): array
    {
        $weekdayPrice = match ($sortOrder) {
            1 => 4000000,
            2 => 4000000,
            3 => 4000000,
            default => 4000000,
        };
        $weekendPrice = 5000000;
        $prices = [];

        for ($day = 9; $day <= 31; $day++) {
            $date = sprintf('2026-08-%02d', $day);
            $dayOfWeek = (int) date('w', strtotime($date));
            $isWeekend = in_array($dayOfWeek, [0, 6], true);

            $prices[$date] = [
                'price' => $isWeekend ? $weekendPrice : $weekdayPrice,
                'available' => true,
            ];
        }

        return $prices;
    }

    private function packageDetailsForProduct(Product $product): array
    {
        $baseOverrides = [
            'itinerary' => [
                'venueName' => $product->name,
                'note' => 'Lịch trình có thể thay đổi tùy vào điều kiện sân, giao thông và thời tiết.',
            ],
            'meeting_pickup' => [
                'meetingPointNote' => 'Nhà điều hành sẽ xác nhận lại thời gian đón hoặc điểm tập trung trước ngày chơi.',
            ],
            'pickup_information' => [
                'Nhà điều hành xác nhận trước thời gian đón hoặc điểm tập trung.',
                'Có mặt tại sảnh khách sạn hoặc điểm hẹn 10 phút trước giờ đã xác nhận.',
                'Nhập tên và địa chỉ khách sạn tại trang thanh toán nếu chọn dịch vụ đưa đón.',
                'Khu vực áp dụng tùy theo vị trí sân golf và phạm vi phục vụ của nhà điều hành.',
                'Có thể phát sinh phụ phí nếu địa chỉ nằm ngoài khu vực phục vụ tiêu chuẩn.',
            ],
        ];

        $productOverrides = match ($product->slug) {
            'ba-na-hills-golf-club' => [
                'itinerary' => [
                    'venueName' => 'Ba Na Hills Golf Club',
                    'returnTime' => '08:00',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Nhà điều hành sẽ xác nhận lại thời gian đón trước đó.',
                    'returnTime' => '08:00',
                ],
                'pickup_information' => [
                    'Nhà điều hành xác nhận trước thời gian đón.',
                    'Có mặt tại sảnh khách sạn 10 phút trước giờ đón.',
                    'Nhập tên và địa chỉ khách sạn tại trang thanh toán.',
                    'Khu vực áp dụng: khách sạn trong trung tâm Thành phố Đà Nẵng.',
                    'Có thể phát sinh phụ phí ở Intercontinental Đà Nẵng, Hội An hoặc ngoài trung tâm.',
                ],
            ],
            'laguna-golf-lang-co' => [
                'itinerary' => [
                    'venueName' => 'Laguna Golf Lăng Cô',
                    'returnTime' => '07:30',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Thông tin đón khách tại Đà Nẵng, Huế hoặc Lăng Cô sẽ được xác nhận theo địa chỉ khách sạn.',
                    'returnTime' => '07:30',
                ],
                'pickup_information' => [
                    'Nhà điều hành xác nhận trước thời gian đón.',
                    'Có mặt tại sảnh khách sạn 10 phút trước giờ đón.',
                    'Nhập tên và địa chỉ khách sạn tại trang thanh toán.',
                    'Khu vực áp dụng: khách sạn tại Đà Nẵng, Huế hoặc Lăng Cô theo phạm vi phục vụ.',
                    'Có thể phát sinh phụ phí nếu điểm đón nằm ngoài khu vực phục vụ tiêu chuẩn.',
                ],
            ],
            'hoiana-shores-golf-club' => [
                'itinerary' => [
                    'venueName' => 'Hoiana Shores Golf Club',
                    'returnTime' => '07:00',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Thông tin đón khách tại Hội An hoặc Đà Nẵng sẽ được xác nhận theo địa chỉ khách sạn.',
                    'returnTime' => '07:00',
                ],
                'pickup_information' => [
                    'Nhà điều hành xác nhận trước thời gian đón.',
                    'Có mặt tại sảnh khách sạn 10 phút trước giờ đón.',
                    'Nhập tên và địa chỉ khách sạn tại trang thanh toán.',
                    'Khu vực áp dụng: khách sạn tại Hội An hoặc Đà Nẵng theo phạm vi phục vụ.',
                    'Có thể phát sinh phụ phí nếu điểm đón nằm ngoài khu vực phục vụ tiêu chuẩn.',
                ],
            ],
            'tan-son-nhat-golf-course' => [
                'itinerary' => [
                    'venueName' => 'Tân Sơn Nhất Golf Course',
                    'returnTime' => '06:30',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Thông tin đón khách tại TP. Hồ Chí Minh sẽ được xác nhận theo địa chỉ khách sạn.',
                    'returnTime' => '06:30',
                ],
            ],
            'long-thanh-golf-club' => [
                'itinerary' => [
                    'venueName' => 'Long Thành Golf Club',
                    'returnTime' => '06:30',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Thông tin đón khách tại TP. Hồ Chí Minh hoặc Đồng Nai sẽ được xác nhận theo địa chỉ khách sạn.',
                    'returnTime' => '06:30',
                ],
            ],
            'montgomerie-links-vietnam' => [
                'itinerary' => [
                    'venueName' => 'Montgomerie Links Vietnam',
                    'returnTime' => '07:00',
                ],
                'meeting_pickup' => [
                    'meetingPointNote' => 'Thông tin đón khách tại Đà Nẵng hoặc Hội An sẽ được xác nhận theo địa chỉ khách sạn.',
                    'returnTime' => '07:00',
                ],
            ],
            default => [],
        };

        return $this->commonTeeTimePackageDetails(
            $this->mergePackageDetails($baseOverrides, $productOverrides)
        );
    }

    private function commonTeeTimePackageDetails(array $overrides = []): array
    {
        $details = $this->mergePackageDetails([
            'booking_badges' => [
                'Đặt trước cho ngày mai',
                'Hoàn huỷ có điều kiện*',
                'Xác nhận trong 48 giờ',
                'Hiệu lực vào ngày đã chọn',
            ],
            'itinerary' => [
                'departureLabel' => 'Khởi hành',
                'venueName' => 'Sân golf đã chọn',
                'returnTime' => '08:00',
                'returnLabel' => 'Quay về',
                'note' => 'Lịch trình có thể thay đổi tùy vào điều kiện giao thông và thời tiết.',
            ],
            'inclusions' => [
                'Nước uống đóng chai',
                'Đưa đón khứ hồi từ và đến khách sạn của bạn',
                'Phí green cho sân golf 18 lỗ',
                'Phí caddie',
                'Tủ khóa',
                'Xe điện golf dùng chung',
            ],
            'exclusions' => [
                'Bữa ăn và đồ uống',
                'Chi phí cá nhân khác',
                'Tiền típ',
            ],
            'meeting_pickup' => [
                'title' => 'Thông tin tập trung / đón khách',
                'departureTitle' => 'Khởi hành',
                'searchableLocation' => true,
                'confirmationLabel' => 'Thời gian được xác nhận sau khi đặt',
                'meetingPointLabel' => 'Khu đón khách được chỉ định',
                'meetingPointNote' => 'Nhà điều hành sẽ xác nhận lại thời gian đón trước ngày chơi.',
                'returnTime' => '08:00',
                'returnLabel' => 'Về khách sạn/địa chỉ riêng',
            ],
            'booking_notes' => [
                'conditions' => [
                    'Trẻ em phải luôn đi cùng với người lớn đã thanh toán mọi lúc',
                ],
                'additionalInformation' => [
                    'Vui lòng cho biết giờ phát bóng ưa thích của bạn tại trang thanh toán',
                    'Phụ phí đưa đón có thể được áp dụng tùy theo khu vực khách sạn',
                    'Phụ phí đón khách xa sẽ được điều hành viên địa phương thông báo nếu khách sạn nằm ngoài phạm vi',
                    'Có thể chọn điểm đón được nhà điều hành xác nhận trước ngày chơi',
                    'Phụ phí được thanh toán bằng tiền mặt trực tiếp cho tài xế',
                ],
                'restrictions' => [
                    'Không khuyến khích khách bị suy giảm khả năng vận động thể chất hoặc người sử dụng xe lăn tham gia',
                    'Hoạt động không phù hợp với khách có vấn đề sức khỏe như huyết áp cao, động kinh, v.v.',
                    'Hoạt động có thể bị hủy do thời tiết xấu hoặc trường hợp không lường trước',
                    'Nếu bị hủy, khách được đổi ngày hoặc yêu cầu hoàn tiền',
                ],
                'dressCode' => [
                    'Nam giới nên mặc áo sơ mi có cổ tay và quần tây hoặc quần short chơi golf',
                    'Phụ nữ nên mặc áo sơ mi có cổ tay và quần dài, quần short hoặc váy dài đến giữa bắp chân',
                    'Chỉ được mang giày golf đinh mềm hoặc không đinh trong cơ sở',
                ],
            ],
            'conditions' => [
                'Trẻ em phải luôn đi cùng với người lớn đã thanh toán mọi lúc',
            ],
            'additional_information' => [
                'Vui lòng cho biết giờ phát bóng ưa thích của bạn tại trang thanh toán',
                'Phụ phí đưa đón có thể được áp dụng tùy theo khu vực khách sạn',
                'Phụ phí đón khách xa sẽ được điều hành viên địa phương thông báo nếu khách sạn nằm ngoài phạm vi',
                'Có thể chọn điểm đón được nhà điều hành xác nhận trước ngày chơi',
                'Phụ phí được thanh toán bằng tiền mặt trực tiếp cho tài xế',
            ],
            'restrictions' => [
                'Không khuyến khích khách bị suy giảm khả năng vận động thể chất hoặc người sử dụng xe lăn tham gia',
                'Hoạt động không phù hợp với khách có vấn đề sức khỏe như huyết áp cao, động kinh, v.v.',
                'Hoạt động có thể bị hủy do thời tiết xấu hoặc trường hợp không lường trước',
                'Nếu bị hủy, khách được đổi ngày hoặc yêu cầu hoàn tiền',
            ],
            'dress_code' => [
                'Nam giới nên mặc áo sơ mi có cổ tay và quần tây hoặc quần short chơi golf',
                'Phụ nữ nên mặc áo sơ mi có cổ tay và quần dài, quần short hoặc váy dài đến giữa bắp chân',
                'Chỉ được mang giày golf đinh mềm hoặc không đinh trong cơ sở',
            ],
            'confirmation_policy' => [
                'Khách nhận email xác nhận trong vòng 48 giờ',
                'Nếu không nhận được email, liên hệ chăm sóc khách hàng',
            ],
            'cancellation_policy' => [
                'Nhà điều hành có quyền hủy trong trường hợp thời tiết khắc nghiệt hoặc sự việc không lường trước',
                'Khách có thể đổi lịch hoặc yêu cầu hoàn tiền',
                'Nếu hủy trong vòng 6 ngày trước hoạt động, phí hủy có thể là 100% giá trị đơn hàng',
            ],
            'voucher_information' => [
                'Voucher chỉ có hiệu lực vào ngày và giờ đã chọn',
                'Xuất trình voucher trên điện thoại.',
            ],
            'pickup_information' => [
                'Nhà điều hành xác nhận trước thời gian đón.',
                'Có mặt tại sảnh khách sạn 10 phút trước giờ đón.',
                'Nhập tên và địa chỉ khách sạn tại trang thanh toán.',
                'Khu vực áp dụng tùy theo vị trí sân golf và phạm vi phục vụ của nhà điều hành.',
                'Có thể phát sinh phụ phí nếu địa chỉ nằm ngoài khu vực phục vụ tiêu chuẩn.',
            ],
        ], $overrides);

        $details['badges'] = $details['booking_badges'];
        $details['pickup'] = $details['meeting_pickup'];
        $details['bookingNotes'] = [
            'conditions' => $details['conditions'],
            'additionalInformation' => $details['additional_information'],
            'restrictions' => $details['restrictions'],
            'dressCode' => $details['dress_code'],
        ];
        $details['generalTerms'] = [
            'confirmation' => $details['confirmation_policy'],
            'cancellation' => $details['cancellation_policy'],
        ];
        $details['usage'] = [
            'validity' => [
                'Voucher chỉ có hiệu lực vào ngày và giờ đã chọn',
            ],
            'voucher_type' => $details['voucher_information'],
            'pickup_information' => $details['pickup_information'],
        ];

        return $details;
    }

    private function mergePackageDetails(array $details, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            if (
                isset($details[$key]) &&
                is_array($details[$key]) &&
                is_array($value) &&
                $this->isAssoc($details[$key]) &&
                $this->isAssoc($value)
            ) {
                $details[$key] = $this->mergePackageDetails($details[$key], $value);
                continue;
            }

            $details[$key] = $value;
        }

        return $details;
    }

    private function isAssoc(array $value): bool
    {
        return $value !== [] && array_keys($value) !== range(0, count($value) - 1);
    }

    private function baNaPackageDetails(): array
    {
        return $this->commonTeeTimePackageDetails([
            'itinerary' => [
                'venueName' => 'Ba Na Hills Golf Club',
                'returnTime' => '08:00',
                'note' => 'Lịch trình có thể thay đổi tùy vào điều kiện giao thông và thời tiết',
            ],
            'meeting_pickup' => [
                'meetingPointNote' => 'Nhà điều hành sẽ xác nhận lại thời gian đón trước đó',
                'returnTime' => '08:00',
            ],
            'additional_information' => [
                'Vui lòng cho biết giờ phát bóng ưa thích của bạn tại trang thanh toán',
                'Phụ phí sẽ được tính thêm cho dịch vụ đưa đón trong khu vực Intercontinental Đà Nẵng hoặc Hội An',
                'Phụ phí đón khách xa sẽ được điều hành viên địa phương thông báo nếu khách sạn nằm ngoài phạm vi',
                'Nếu khách sạn không nằm ở trung tâm Thành phố Đà Nẵng, khách có thể phải trả thêm phụ phí',
                'Có thể chọn điểm đón tại A La Carte Danang Beach - 200 Võ Nguyên Giáp, Quận Sơn Trà, Đà Nẵng',
                'Phụ phí được thanh toán bằng tiền mặt trực tiếp cho tài xế',
            ],
            'pickup_information' => [
                'Nhà điều hành xác nhận trước thời gian đón.',
                'Có mặt tại sảnh khách sạn 10 phút trước giờ đón.',
                'Nhập tên và địa chỉ khách sạn tại trang thanh toán.',
                'Khu vực áp dụng: khách sạn trong trung tâm Thành phố Đà Nẵng.',
                'Có thể phát sinh phụ phí ở Intercontinental Đà Nẵng, Hội An hoặc ngoài trung tâm',
            ],
        ]);
    }

    private function products(): array
    {
        return [
            [
                'name' => 'Laguna Golf Lăng Cô',
                'slug' => 'laguna-golf-lang-co',
                'location' => 'Lăng Cô, Huế',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => 2900000,
                'badge_text' => 'GOLFNITY Đề xuất',
                'is_featured' => true,
                'order_position' => 1,
                'attributes' => [
                    'course_type' => 'Sân golf nghỉ dưỡng ven biển',
                    'is_weekend_recommended' => true,
                    'is_near_center' => false,
                    'distance_to_center' => 55,
                    'travel_time_to_center' => 60,
                    'editorial_gallery_captions' => self::LAGUNA_EDITORIAL_CAPTIONS,
                    'editorial_dress_code' => self::LAGUNA_EDITORIAL_DRESS_CODE,
                ],
                'description' => 'Sân golf nghỉ dưỡng ven biển tại Lăng Cô, phù hợp cho hành trình golf kết hợp nghỉ dưỡng miền Trung.',
                'highlight' => "Gần khu nghỉ dưỡng ven biển Lăng Cô.\nPhù hợp nhóm golfer muốn kết hợp nghỉ dưỡng.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => self::LAGUNA_EDITORIAL_CONTENT,
                'gallery_images' => self::LAGUNA_EDITORIAL_GALLERY,
            ],
            [
                'name' => 'Hoiana Shores Golf Club',
                'slug' => 'hoiana-shores-golf-club',
                'location' => 'Hội An, Quảng Nam',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => 3200000,
                'badge_text' => 'Được quan tâm',
                'is_featured' => true,
                'order_position' => 2,
                'attributes' => [
                    'course_type' => 'Sân golf ven biển',
                    'is_weekend_recommended' => true,
                    'is_near_center' => true,
                    'distance_to_center' => 15,
                    'travel_time_to_center' => 25,
                ],
                'description' => 'Sân golf ven biển gần Hội An, phù hợp cho tee time cuối tuần và lịch trình nghỉ dưỡng ngắn ngày.',
                'highlight' => "Vị trí thuận tiện từ Hội An.\nPhù hợp lịch chơi cuối tuần.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => '<p>Dữ liệu demo cho luồng đặt tee time Hoiana Shores Golf Club trên GOLFNITY.</p>',
            ],
            [
                'name' => 'Ba Na Hills Golf Club',
                'slug' => 'ba-na-hills-golf-club',
                'location' => 'Đà Nẵng',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => '__KEEP_OR_NULL__',
                'badge_text' => 'Mới',
                'is_featured' => true,
                'order_position' => 3,
                'attributes' => [
                    'course_type' => 'Sân golf đồi núi',
                    'is_weekend_recommended' => true,
                    'is_near_center' => true,
                    'distance_to_center' => 25,
                    'travel_time_to_center' => 35,
                    'editorial_gallery_captions' => self::BA_NA_EDITORIAL_CAPTIONS,
                    'editorial_dress_code' => self::BA_NA_EDITORIAL_DRESS_CODE,
                ],
                'description' => 'Sân golf đồi núi tại Đà Nẵng, phù hợp golfer muốn trải nghiệm địa hình khác biệt.',
                'highlight' => "Không gian đồi núi đặc trưng.\nPhù hợp hành trình golf Đà Nẵng.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => self::BA_NA_EDITORIAL_CONTENT,
                'gallery_images' => self::BA_NA_EDITORIAL_GALLERY,
            ],
            [
                'name' => 'Tân Sơn Nhất Golf Course',
                'slug' => 'tan-son-nhat-golf-course',
                'location' => 'TP. Hồ Chí Minh',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => 2500000,
                'badge_text' => null,
                'is_featured' => false,
                'order_position' => 4,
                'attributes' => [
                    'course_type' => 'Sân golf đô thị',
                    'is_weekend_recommended' => true,
                    'is_near_center' => true,
                    'distance_to_center' => 8,
                    'travel_time_to_center' => 20,
                ],
                'description' => 'Sân golf đô thị tại TP. Hồ Chí Minh, phù hợp lịch chơi linh hoạt và nhóm golfer cần di chuyển nhanh.',
                'highlight' => "Vị trí đô thị thuận tiện.\nPhù hợp lịch tee time sau giờ làm hoặc cuối tuần.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => '<p>Dữ liệu demo cho luồng đặt tee time Tân Sơn Nhất Golf Course trên GOLFNITY.</p>',
            ],
            [
                'name' => 'Long Thành Golf Club',
                'slug' => 'long-thanh-golf-club',
                'location' => 'Đồng Nai',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => '__KEEP_OR_NULL__',
                'badge_text' => null,
                'is_featured' => false,
                'order_position' => 5,
                'attributes' => [
                    'course_type' => 'Sân golf ngoại ô',
                    'is_weekend_recommended' => false,
                    'is_near_center' => true,
                    'distance_to_center' => 35,
                    'travel_time_to_center' => 50,
                ],
                'description' => 'Sân golf ngoại ô phù hợp nhóm golfer muốn kết hợp di chuyển ngắn và không gian rộng rãi.',
                'highlight' => "Không gian ngoại ô thoáng.\nPhù hợp nhóm golfer đi trong ngày.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => '<p>Dữ liệu demo cho luồng đặt tee time Long Thành Golf Club trên GOLFNITY.</p>',
            ],
            [
                'name' => 'Montgomerie Links Vietnam',
                'slug' => 'montgomerie-links-vietnam',
                'location' => 'Điện Bàn, Quảng Nam',
                'duration' => 'Kiểm tra lịch theo yêu cầu',
                'price' => 2800000,
                'badge_text' => null,
                'is_featured' => false,
                'order_position' => 6,
                'attributes' => [
                    'course_type' => 'Sân golf links',
                    'is_weekend_recommended' => true,
                    'is_near_center' => true,
                    'distance_to_center' => 12,
                    'travel_time_to_center' => 20,
                ],
                'description' => 'Sân golf links giữa Đà Nẵng và Hội An, phù hợp lịch tee time cuối tuần và golf nghỉ dưỡng.',
                'highlight' => "Vị trí kết nối Đà Nẵng - Hội An.\nPhù hợp lịch golf nghỉ dưỡng ngắn ngày.",
                'facility' => 'Green fee, caddie, xe điện theo chính sách sân.',
                'content' => '<p>Dữ liệu demo cho luồng đặt tee time Montgomerie Links Vietnam trên GOLFNITY.</p>',
            ],
        ];
    }
}
