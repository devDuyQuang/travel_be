<?php

namespace App\Support;

final class ServiceProductAttributes
{
    public static function groups(): array
    {
        return [
            ServiceLayout::TEE_TIME => [
                'title' => 'Thông tin hiển thị trên chi tiết sân golf',
                'fields' => [
                    'course_type' => ['label' => 'Loại sân', 'type' => 'text'],
                    'is_weekend_recommended' => [
                        'label' => 'Gợi ý tee time cuối tuần',
                        'type' => 'boolean',
                    ],
                    'is_near_center' => [
                        'label' => 'Sân gần trung tâm',
                        'type' => 'boolean',
                    ],
                    'distance_to_center' => [
                        'label' => 'Khoảng cách đến trung tâm',
                        'type' => 'number',
                        'description' => 'Đơn vị: km',
                        'min' => 0,
                    ],
                    'travel_time_to_center' => [
                        'label' => 'Thời gian di chuyển từ trung tâm',
                        'type' => 'number',
                        'description' => 'Đơn vị: phút',
                        'min' => 0,
                    ],
                ],
            ],
            ServiceLayout::TOUR => [
                'title' => 'Thông tin tour golf',
                'fields' => [
                    'departure_point' => ['label' => 'Điểm khởi hành', 'type' => 'text'],
                    'destination' => ['label' => 'Điểm đến', 'type' => 'text'],
                    'days' => ['label' => 'Số ngày', 'type' => 'number'],
                    'nights' => ['label' => 'Số đêm', 'type' => 'number'],
                    'max_guests' => ['label' => 'Số khách tối đa', 'type' => 'number'],
                    'itinerary' => ['label' => 'Lịch trình', 'type' => 'textarea'],
                    'included_services' => ['label' => 'Dịch vụ bao gồm', 'type' => 'textarea'],
                    'excluded_services' => ['label' => 'Dịch vụ không bao gồm', 'type' => 'textarea'],
                    'surcharges' => ['label' => 'Phụ thu', 'type' => 'textarea'],
                    'languages' => ['label' => 'Ngôn ngữ', 'type' => 'text'],
                ],
            ],
            ServiceLayout::ACCOMMODATION => [
                'title' => 'Thông tin lưu trú',
                'fields' => [
                    'property_type' => ['label' => 'Loại hình lưu trú', 'type' => 'text'],
                    'classification_rating' => ['label' => 'Hạng phân loại', 'type' => 'text'],
                    'full_address' => ['label' => 'Địa chỉ đầy đủ', 'type' => 'text'],
                    'check_in_time' => ['label' => 'Giờ nhận phòng', 'type' => 'text'],
                    'check_in_time_options' => [
                        'label' => 'Các giờ nhận phòng cho khách chọn',
                        'type' => 'textarea',
                        'description' => 'Mỗi dòng hoặc phân tách bằng dấu phẩy, ví dụ: 14:00, 15:00',
                    ],
                    'check_out_time' => ['label' => 'Giờ trả phòng', 'type' => 'text'],
                    'room_price' => ['label' => 'Giá/phòng/đêm', 'type' => 'number'],
                    'room_types' => [
                        'label' => 'Danh sách loại phòng',
                        'type' => 'textarea',
                        'description' => 'Mỗi dòng một loại phòng, ví dụ: Phòng thường, Phòng VIP, Suite',
                    ],
                    'room_numbers' => [
                        'label' => 'Danh sách phòng đang cho đặt',
                        'type' => 'textarea',
                        'description' => 'Mỗi dòng một phòng còn có thể chọn, ví dụ: 101 - hướng biển',
                    ],
                    'max_guests' => ['label' => 'Số khách tối đa', 'type' => 'number'],
                    'bedroom_count' => ['label' => 'Số phòng ngủ', 'type' => 'number'],
                    'bed_count' => ['label' => 'Số giường', 'type' => 'number'],
                    'bathroom_count' => ['label' => 'Số phòng tắm', 'type' => 'number'],
                    'area' => ['label' => 'Diện tích', 'type' => 'text'],
                    'bed_type' => ['label' => 'Loại giường', 'type' => 'text'],
                    'view_type' => ['label' => 'Hướng nhìn', 'type' => 'text'],
                    'amenities' => ['label' => 'Tiện ích lưu trú', 'type' => 'textarea'],
                    'cancellation_policy' => ['label' => 'Chính sách hủy', 'type' => 'textarea'],
                    'child_policy' => ['label' => 'Chính sách trẻ em', 'type' => 'textarea'],
                    'pet_policy' => ['label' => 'Chính sách thú cưng', 'type' => 'textarea'],
                    'breakfast_info' => ['label' => 'Bữa sáng', 'type' => 'textarea'],
                    'parking_info' => ['label' => 'Bãi đỗ xe', 'type' => 'textarea'],
                ],
            ],
            ServiceLayout::TRANSPORT => [
                'title' => 'Thông tin thuê xe',
                'fields' => [
                    'vehicle_type' => ['label' => 'Loại xe', 'type' => 'text'],
                    'brand' => ['label' => 'Hãng xe', 'type' => 'text'],
                    'model' => ['label' => 'Dòng xe', 'type' => 'text'],
                    'seat_count' => ['label' => 'Số chỗ', 'type' => 'number'],
                    'transmission' => ['label' => 'Hộp số', 'type' => 'text'],
                    'manufacture_year' => ['label' => 'Năm sản xuất', 'type' => 'number'],
                    'driver_included' => ['label' => 'Có tài xế', 'type' => 'boolean'],
                    'pickup_location' => ['label' => 'Điểm đón', 'type' => 'text'],
                    'dropoff_location' => ['label' => 'Điểm trả', 'type' => 'text'],
                    'pricing_unit' => ['label' => 'Đơn vị tính giá', 'type' => 'text'],
                    'luggage_capacity' => ['label' => 'Số hành lý', 'type' => 'number'],
                    'vehicle_amenities' => ['label' => 'Tiện ích xe', 'type' => 'textarea'],
                    'cancellation_policy' => ['label' => 'Chính sách hủy', 'type' => 'textarea'],
                ],
            ],
            ServiceLayout::ATTRACTION => [
                'title' => 'Thông tin tham quan và trải nghiệm',
                'fields' => [
                    'ticket_type' => ['label' => 'Loại vé', 'type' => 'text'],
                    'opening_time' => ['label' => 'Giờ mở cửa', 'type' => 'text'],
                    'closing_time' => ['label' => 'Giờ đóng cửa', 'type' => 'text'],
                    'visit_duration' => ['label' => 'Thời lượng tham quan', 'type' => 'text'],
                    'meeting_point' => ['label' => 'Điểm hẹn', 'type' => 'text'],
                    'minimum_age' => ['label' => 'Độ tuổi tối thiểu', 'type' => 'number'],
                    'adult_price' => ['label' => 'Giá người lớn', 'type' => 'number'],
                    'child_price' => ['label' => 'Giá trẻ em', 'type' => 'number'],
                    'infant_price' => ['label' => 'Giá em bé', 'type' => 'number'],
                    'included_services' => ['label' => 'Dịch vụ bao gồm', 'type' => 'textarea'],
                    'ticket_notes' => ['label' => 'Lưu ý sử dụng vé', 'type' => 'textarea'],
                    'cancellation_policy' => ['label' => 'Chính sách hủy', 'type' => 'textarea'],
                ],
            ],
        ];
    }

    public static function validationRules(): array
    {
        $rules = [];

        foreach (self::groups() as $group) {
            foreach ($group['fields'] as $key => $field) {
                $rules["attributes.$key"] = match ($field['type']) {
                    'number' => $key === 'travel_time_to_center'
                        ? ['nullable', 'integer', 'min:0']
                        : ['nullable', 'numeric', 'min:0'],
                    'boolean' => ['nullable', 'boolean'],
                    'textarea' => ['nullable', 'string'],
                    default => ['nullable', 'string', 'max:1000'],
                };
            }
        }

        return $rules;
    }
}
