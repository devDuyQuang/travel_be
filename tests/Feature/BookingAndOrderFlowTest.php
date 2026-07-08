<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\OrderStatus;
use App\Enums\PricingMode;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Services\BookingService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BookingAndOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking_successfully_and_backend_recalculates_price(): void
    {
        $product = $this->serviceProduct(price: '1000000.00');

        $response = $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'adults' => 2,
            'children' => 1,
            'quantity' => 1,
            'total_amount' => '1.00',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'idempotency_key' => 'booking-key-1',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.amounts.total_amount', '3000000.00')
            ->assertJsonPath('data.booking_status.value', BookingStatus::Pending->value);

        $this->assertDatabaseHas('bookings', [
            'service_product_id' => $product->id,
            'service_name_snapshot' => $product->name,
            'booking_status' => BookingStatus::Pending->value,
            'payment_status' => 'pending',
            'total_amount' => '3000000.00',
        ]);
        $this->assertDatabaseCount('booking_status_histories', 1);
    }

    public function test_booking_rejects_past_date(): void
    {
        $product = $this->serviceProduct();

        $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->subDay()->toDateString(),
        ])->assertUnprocessable();
    }

    public function test_booking_tour_calculates_adults_children_and_extras(): void
    {
        $product = $this->serviceProduct(
            price: '0.00',
            layoutKey: 'tour',
            attributes: [
                'adult_price' => '620.00',
                'child_price' => '300.00',
            ],
        );

        $response = $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Tour->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '+84901234567',
            'start_date' => now()->addDay()->toDateString(),
            'adults' => 2,
            'children' => 1,
            'extras' => [
                ['name' => 'Xe đưa đón', 'pricing_type' => 'per_booking', 'price' => '1000'],
                ['name' => 'Bảo hiểm', 'pricing_type' => 'per_person', 'price' => '200'],
            ],
            'payment_method' => 'cash',
            'idempotency_key' => 'booking-price-key',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.amounts.pricing_mode.value', PricingMode::PerPerson->value)
            ->assertJsonPath('data.amounts.unit_price', '620.00')
            ->assertJsonPath('data.amounts.total_amount', '3140.00')
            ->assertJsonPath('data.pricing_snapshot.extras_total', '1600.00');

        $this->assertDatabaseHas('bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Tour->value,
            'pricing_mode' => PricingMode::PerPerson->value,
            'total_amount' => '3140.00',
        ]);
    }

    public function test_transport_without_price_is_marked_as_quote_not_free(): void
    {
        $product = $this->serviceProduct(price: '0.00', layoutKey: 'transport');

        $response = $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Transport->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'booking_details' => [
                'pickup_location' => 'Sân bay Tân Sơn Nhất',
                'dropoff_location' => 'Quận 1',
                'vehicle_type' => 'SUV',
                'passengers' => 3,
            ],
            'payment_method' => 'bank_transfer',
            'idempotency_key' => 'transport-quote-key',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.booking_type.value', BookingType::Transport->value)
            ->assertJsonPath('data.amounts.pricing_mode.value', PricingMode::Quote->value)
            ->assertJsonPath('data.booking_details.passengers', 3);

        $this->assertDatabaseHas('bookings', [
            'service_product_id' => $product->id,
            'pricing_mode' => PricingMode::Quote->value,
            'total_amount' => '0.00',
        ]);
    }

    public function test_hotel_booking_requires_checkout_and_room_type(): void
    {
        $product = $this->serviceProduct(price: '0.00', layoutKey: 'accommodation');

        $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Hotel->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'booking_details' => [
                'rooms' => 1,
            ],
            'idempotency_key' => 'hotel-missing-fields',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors([
                'end_date',
                'booking_details.room_type',
            ]);
    }

    public function test_hotel_booking_rejects_checkout_not_after_checkin(): void
    {
        $product = $this->serviceProduct(price: '0.00', layoutKey: 'accommodation');
        $date = now()->addDay()->toDateString();

        $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Hotel->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => $date,
            'end_date' => $date,
            'booking_details' => [
                'room_type' => 'Deluxe Ocean View',
                'rooms' => 1,
            ],
            'idempotency_key' => 'hotel-bad-checkout',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['end_date']);
    }

    public function test_hotel_booking_calculates_room_price_by_nights(): void
    {
        $product = $this->serviceProduct(
            price: '0.00',
            layoutKey: 'accommodation',
            attributes: ['room_price' => '1500000.00'],
        );
        $checkIn = now()->addDay();

        $response = $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Hotel->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => $checkIn->toDateString(),
            'end_date' => $checkIn->copy()->addDays(2)->toDateString(),
            'start_time' => '14:00',
            'adults' => 2,
            'children' => 1,
            'quantity' => 1,
            'booking_details' => [
                'room_type' => 'Deluxe Ocean View',
                'rooms' => 1,
            ],
            'idempotency_key' => 'hotel-priced-booking',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.amounts.pricing_mode.value', PricingMode::PerRoom->value)
            ->assertJsonPath('data.amounts.total_amount', '3000000.00')
            ->assertJsonPath('data.booking_details.nights', 2)
            ->assertJsonPath('data.booking_details.room_type', 'Deluxe Ocean View');
    }

    public function test_product_options_can_be_saved_from_cms(): void
    {
        $admin = $this->adminUser();
        $category = Category::create([
            'name' => 'Khách sạn',
            'slug' => 'khach-san-'.uniqid(),
            'type' => 'service',
            'layout_key' => 'accommodation',
            'status' => 1,
        ]);

        $this->actingAs($admin)
            ->postJson('http://cms.example.test/product', [
                'name' => 'Fusion Resort',
                'slug' => 'fusion-resort',
                'category_id' => $category->id,
                'product_type' => 'service',
                'status' => 1,
                'service_options' => [
                    [
                        'type' => 'room_type',
                        'name' => 'Deluxe Ocean View',
                        'price' => '1500000',
                        'unit' => 'đêm',
                        'capacity' => 3,
                        'sort_order' => 1,
                        'is_active' => 1,
                    ],
                ],
            ])
            ->assertOk();

        $this->assertDatabaseHas('service_product_options', [
            'type' => 'room_type',
            'name' => 'Deluxe Ocean View',
            'price' => '1500000.00',
            'unit' => 'đêm',
            'capacity' => 3,
            'is_active' => 1,
        ]);
    }

    public function test_product_api_returns_only_active_service_options(): void
    {
        $product = $this->serviceProduct(layoutKey: 'accommodation');
        $active = $product->serviceOptions()->create([
            'type' => 'room_type',
            'name' => 'Deluxe Ocean View',
            'price' => '1500000.00',
            'currency' => 'VND',
            'unit' => 'đêm',
            'is_active' => true,
        ]);
        $product->serviceOptions()->create([
            'type' => 'room_type',
            'name' => 'Inactive Room',
            'price' => '900000.00',
            'currency' => 'VND',
            'unit' => 'đêm',
            'is_active' => false,
        ]);

        $response = $this->getJson('http://api.example.test/product/'.$product->slug);

        $response->assertOk()
            ->assertJsonPath('data.service_options.0.id', $active->id)
            ->assertJsonPath('data.service_options.0.name', 'Deluxe Ocean View');

        $this->assertCount(1, $response->json('data.service_options'));
    }

    public function test_hotel_booking_with_room_option_calculates_price_by_nights_and_rooms(): void
    {
        $product = $this->serviceProduct(price: '0.00', layoutKey: 'accommodation');
        $option = $product->serviceOptions()->create([
            'type' => 'room_type',
            'name' => 'Suite Ocean View',
            'price' => '2000000.00',
            'currency' => 'VND',
            'unit' => 'đêm',
            'is_active' => true,
        ]);
        $checkIn = now()->addDay();

        $response = $this->postJson('http://api.example.test/bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Hotel->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => $checkIn->toDateString(),
            'end_date' => $checkIn->copy()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'quantity' => 2,
            'booking_details' => [
                'service_option_id' => $option->id,
                'room_type' => 'Client sent fake name',
                'rooms' => 2,
            ],
            'idempotency_key' => 'hotel-option-priced-booking',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.amounts.total_amount', '12000000.00')
            ->assertJsonPath('data.booking_details.option_id', $option->id)
            ->assertJsonPath('data.booking_details.option_name', 'Suite Ocean View')
            ->assertJsonPath('data.booking_details.quantity_basis', 6);
    }

    public function test_booking_rejects_option_from_another_product_or_inactive_option(): void
    {
        $product = $this->serviceProduct(price: '0.00', layoutKey: 'accommodation');
        $otherProduct = $this->serviceProduct(price: '0.00', layoutKey: 'accommodation');
        $otherOption = $otherProduct->serviceOptions()->create([
            'type' => 'room_type',
            'name' => 'Other Room',
            'price' => '1000000.00',
            'currency' => 'VND',
            'unit' => 'đêm',
            'is_active' => true,
        ]);
        $inactiveOption = $product->serviceOptions()->create([
            'type' => 'room_type',
            'name' => 'Inactive Room',
            'price' => '1000000.00',
            'currency' => 'VND',
            'unit' => 'đêm',
            'is_active' => false,
        ]);
        $checkIn = now()->addDay();
        $payload = [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Hotel->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => $checkIn->toDateString(),
            'end_date' => $checkIn->copy()->addDay()->toDateString(),
            'booking_details' => [
                'service_option_id' => $otherOption->id,
                'room_type' => 'Other Room',
                'rooms' => 1,
            ],
            'idempotency_key' => 'hotel-wrong-option',
        ];

        $this->postJson('http://api.example.test/bookings', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['booking_details.service_option_id']);

        $payload['booking_details']['service_option_id'] = $inactiveOption->id;
        $payload['idempotency_key'] = 'hotel-inactive-option';

        $this->postJson('http://api.example.test/bookings', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['booking_details.service_option_id']);
    }

    public function test_booking_idempotency_returns_existing_and_rejects_different_payload(): void
    {
        $product = $this->serviceProduct();
        $payload = [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Tour->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'adults' => 1,
            'children' => 0,
            'idempotency_key' => 'booking-idempotent',
        ];

        $first = $this->postJson('http://api.example.test/bookings', $payload)->assertCreated();
        $second = $this->postJson('http://api.example.test/bookings', $payload)->assertCreated();

        $this->assertSame($first->json('data.booking_code'), $second->json('data.booking_code'));
        $this->assertDatabaseCount('bookings', 1);

        $payload['adults'] = 2;
        $this->postJson('http://api.example.test/bookings', $payload)
            ->assertUnprocessable()
            ->assertJsonPath('success', false);
        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_booking_code_is_unique_and_transition_rules_are_enforced(): void
    {
        $booking = app(BookingService::class)->create([
            'service_product_id' => $this->serviceProduct()->id,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
        ]);

        $updated = app(BookingService::class)->transition($booking, BookingStatus::Confirmed->value);
        $this->assertSame(BookingStatus::Confirmed, $updated->booking_status);

        $this->expectException(ValidationException::class);
        app(BookingService::class)->transition($updated, BookingStatus::Completed->value);
    }

    public function test_cancel_booking_requires_reason_and_writes_history(): void
    {
        $booking = app(BookingService::class)->create([
            'service_product_id' => $this->serviceProduct()->id,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'a@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
        ]);

        $admin = $this->adminUser();
        $this->actingAs($admin)
            ->patch('http://cms.example.test/booking/'.$booking->id.'/status', [
                'status' => BookingStatus::Cancelled->value,
                'cancel_reason' => 'Khách đổi lịch.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'booking_status' => BookingStatus::Cancelled->value,
            'cancel_reason' => 'Khách đổi lịch.',
        ]);
        $this->assertDatabaseHas('booking_status_histories', [
            'booking_id' => $booking->id,
            'to_status' => BookingStatus::Cancelled->value,
            'note' => 'Khách đổi lịch.',
        ]);
    }

    public function test_create_order_successfully_deducts_stock_and_recalculates_total(): void
    {
        $product = $this->physicalProduct(price: '500000.00', stock: 5);

        $response = $this->postJson('http://api.example.test/orders', [
            'customer_name' => 'Tran Van B',
            'customer_email' => 'b@example.test',
            'customer_phone' => '0912345678',
            'shipping_address_line' => '123 Nguyen Hue',
            'shipping_province' => 'TP HCM',
            'payment_method' => 'cash',
            'idempotency_key' => 'order-key-1',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'line_total' => '1.00'],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.amounts.total_amount', '1000000.00');

        $this->assertSame(3, $product->fresh()->stock_quantity);
        $this->assertDatabaseHas('orders', [
            'order_status' => OrderStatus::Pending->value,
            'total_amount' => '1000000.00',
            'payment_status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'product_name_snapshot' => $product->name,
            'quantity' => 2,
            'line_total' => '1000000.00',
        ]);
    }

    public function test_order_rejects_service_product_and_overselling(): void
    {
        $service = $this->serviceProduct();
        $physical = $this->physicalProduct(stock: 1);

        $payload = [
            'customer_name' => 'Tran Van B',
            'customer_email' => 'b@example.test',
            'customer_phone' => '0912345678',
            'shipping_address_line' => '123 Nguyen Hue',
            'shipping_province' => 'TP HCM',
            'items' => [['product_id' => $service->id, 'quantity' => 1]],
        ];

        $this->postJson('http://api.example.test/orders', $payload)->assertUnprocessable();

        $payload['items'] = [['product_id' => $physical->id, 'quantity' => 2]];
        $this->postJson('http://api.example.test/orders', $payload)->assertUnprocessable();
    }

    public function test_order_idempotency_key_does_not_create_duplicate_order(): void
    {
        $product = $this->physicalProduct(stock: 5);
        $payload = [
            'customer_name' => 'Tran Van B',
            'customer_email' => 'b@example.test',
            'customer_phone' => '0912345678',
            'shipping_address_line' => '123 Nguyen Hue',
            'shipping_province' => 'TP HCM',
            'idempotency_key' => 'order-idempotent',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ];

        $first = $this->postJson('http://api.example.test/orders', $payload)->assertCreated();
        $second = $this->postJson('http://api.example.test/orders', $payload)->assertCreated();

        $this->assertSame(
            $first->json('data.order_code'),
            $second->json('data.order_code')
        );
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(4, $product->fresh()->stock_quantity);

        $payload['items'] = [['product_id' => $product->id, 'quantity' => 2]];
        $this->postJson('http://api.example.test/orders', $payload)
            ->assertUnprocessable()
            ->assertJsonPath('success', false);
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(4, $product->fresh()->stock_quantity);
    }

    public function test_order_item_snapshot_does_not_change_when_product_changes(): void
    {
        $product = $this->physicalProduct(price: '500000.00', stock: 5);

        $order = app(OrderService::class)->create([
            'customer_name' => 'Tran Van B',
            'customer_email' => 'b@example.test',
            'customer_phone' => '0912345678',
            'shipping_address_line' => '123 Nguyen Hue',
            'shipping_province' => 'TP HCM',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $product->update([
            'name' => 'Gậy golf mới',
            'price' => '900000.00',
        ]);

        $item = $order->items()->firstOrFail()->fresh();

        $this->assertSame('Gậy golf', $item->product_name_snapshot);
        $this->assertSame('500000.00', $item->unit_price);
    }

    public function test_cancel_order_restores_stock_only_once_and_transition_rules_are_enforced(): void
    {
        $product = $this->physicalProduct(stock: 5);
        $order = app(OrderService::class)->create([
            'customer_name' => 'Tran Van B',
            'customer_email' => 'b@example.test',
            'customer_phone' => '0912345678',
            'shipping_address_line' => '123 Nguyen Hue',
            'shipping_province' => 'TP HCM',
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $this->assertSame(3, $product->fresh()->stock_quantity);

        $cancelled = app(OrderService::class)->transition($order, OrderStatus::Cancelled->value, cancelReason: 'Hết hàng.');
        $this->assertSame(OrderStatus::Cancelled, $cancelled->order_status);
        $this->assertSame(5, $product->fresh()->stock_quantity);

        $this->expectException(ValidationException::class);
        app(OrderService::class)->transition($cancelled, OrderStatus::Confirmed->value);
    }

    public function test_admin_booking_route_requires_auth_and_pagination_works(): void
    {
        $this->get('http://cms.example.test/booking')->assertRedirect();

        $product = $this->serviceProduct();

        for ($i = 0; $i < 25; $i++) {
            app(BookingService::class)->create([
                'service_product_id' => $product->id,
                'customer_name' => 'Khach '.$i,
                'customer_email' => "khach{$i}@example.test",
                'customer_phone' => '09012345'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'start_date' => now()->addDays($i + 1)->toDateString(),
            ]);
        }

        $admin = $this->adminUser();

        $this->actingAs($admin)
            ->get('http://cms.example.test/booking')
            ->assertOk()
            ->assertSee('Quản lý Booking')
            ->assertSee('Khach 24')
            ->assertDontSee('Khach 0');

        $this->actingAs($admin)
            ->get('http://cms.example.test/booking?page=2')
            ->assertOk()
            ->assertSee('Khach 0');
    }

    private function serviceProduct(string $price = '100000.00', string $layoutKey = 'tour', array $attributes = []): Product
    {
        $category = Category::create([
            'name' => 'Dịch vụ',
            'slug' => 'dich-vu-'.uniqid(),
            'type' => 'service',
            'layout_key' => $layoutKey,
            'status' => 1,
        ]);

        return Product::create([
            'name' => 'Tour golf',
            'slug' => 'tour-golf-'.uniqid(),
            'category_id' => $category->id,
            'product_type' => 'service',
            'price' => $price,
            'attributes' => $attributes,
            'status' => 1,
        ]);
    }

    private function physicalProduct(string $price = '100000.00', int $stock = 10): Product
    {
        $category = Category::create([
            'name' => 'Sản phẩm',
            'slug' => 'san-pham-'.uniqid(),
            'type' => 'product',
            'status' => 1,
        ]);

        return Product::create([
            'name' => 'Gậy golf',
            'slug' => 'gay-golf-'.uniqid(),
            'category_id' => $category->id,
            'product_type' => 'physical',
            'sku' => 'SKU-'.uniqid(),
            'price' => $price,
            'manage_stock' => true,
            'stock_quantity' => $stock,
            'stock_status' => 'in_stock',
            'status' => 1,
        ]);
    }

    private function adminUser(): User
    {
        $role = Role::query()->firstOrCreate(
            ['code' => 'admin'],
            ['name' => 'Admin', 'status' => 1]
        );

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }
}
