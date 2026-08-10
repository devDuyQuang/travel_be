<?php

namespace Tests\Feature;

use App\Enums\BookingType;
use App\Enums\PricingMode;
use App\Mail\BookingReceivedMail;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerBookingEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_booking_with_new_email_creates_customer_and_sends_setup_link(): void
    {
        Mail::fake();
        config(['services.frontend.url' => 'https://fe.example.test']);
        $product = $this->serviceProduct();

        $response = $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'customer_email' => 'new@example.test',
            'idempotency_key' => 'new-customer-booking',
        ]));

        $response->assertCreated()
            ->assertJsonPath('meta.account_created', true)
            ->assertJsonPath('meta.mail_dispatched', true);

        $user = User::where('email', 'new@example.test')->firstOrFail();
        $this->assertSame('customer', $user->role?->code);
        $this->assertNull($user->email_verified_at);
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $user->id,
            'customer_email' => 'new@example.test',
        ]);

        Mail::assertSent(BookingReceivedMail::class, function (BookingReceivedMail $mail) {
            return $mail->hasTo('new@example.test')
                && str_contains((string) $mail->accountSetupUrl, '/reset-password?')
                && str_contains((string) $mail->accountSetupUrl, 'email=new%40example.test');
        });
        Mail::assertSent(BookingReceivedMail::class, 1);
    }

    public function test_guest_booking_with_existing_active_email_reuses_user_without_setup_link(): void
    {
        Mail::fake();
        $product = $this->serviceProduct();
        $role = Role::query()->firstOrCreate(['code' => 'customer'], ['name' => 'Customer', 'status' => 1]);
        $existing = User::factory()->create([
            'email' => 'active@example.test',
            'role_id' => $role->id,
            'email_verified_at' => now(),
            'password' => Hash::make('existing-password'),
        ]);

        $response = $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'customer_email' => 'active@example.test',
            'idempotency_key' => 'existing-customer-booking',
        ]));

        $response->assertCreated()
            ->assertJsonPath('meta.account_created', false)
            ->assertJsonPath('meta.mail_dispatched', true);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $existing->id,
            'customer_email' => 'active@example.test',
        ]);

        Mail::assertSent(BookingReceivedMail::class, function (BookingReceivedMail $mail) {
            return $mail->hasTo('active@example.test')
                && $mail->accountSetupUrl === null
                && ! str_contains($mail->render(), 'existing-password');
        });
        Mail::assertSent(BookingReceivedMail::class, 1);
    }

    public function test_guest_booking_with_legacy_user_role_promotes_customer_and_sends_setup_link(): void
    {
        Mail::fake();
        config(['services.frontend.url' => 'https://fe.example.test']);
        $product = $this->serviceProduct();
        $legacyRole = Role::query()->firstOrCreate(['code' => 'user'], ['name' => 'User', 'status' => 1]);
        $existing = User::factory()->create([
            'email' => 'legacy@example.test',
            'role_id' => $legacyRole->id,
            'email_verified_at' => null,
            'password' => Hash::make('existing-password'),
        ]);

        $response = $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'customer_email' => 'legacy@example.test',
            'idempotency_key' => 'legacy-customer-booking',
        ]));

        $response->assertCreated()
            ->assertJsonPath('meta.account_created', false)
            ->assertJsonPath('meta.mail_dispatched', true);

        $existing->refresh()->load('role');
        $this->assertSame('customer', $existing->role?->code);

        Mail::assertSent(BookingReceivedMail::class, function (BookingReceivedMail $mail) {
            return $mail->hasTo('legacy@example.test')
                && str_contains((string) $mail->accountSetupUrl, '/reset-password?')
                && str_contains((string) $mail->accountSetupUrl, 'email=legacy%40example.test');
        });
    }

    public function test_idempotent_replay_does_not_create_second_booking_or_send_second_mail(): void
    {
        Mail::fake();
        $product = $this->serviceProduct();
        $payload = $this->bookingPayload($product, [
            'customer_email' => 'replay@example.test',
            'idempotency_key' => 'booking-replay-mail',
        ]);

        $first = $this->postJson('http://api.example.test/bookings', $payload)->assertCreated();
        $second = $this->postJson('http://api.example.test/bookings', $payload)->assertCreated();

        $this->assertSame($first->json('data.booking_code'), $second->json('data.booking_code'));
        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseCount('users', 1);
        Mail::assertSent(BookingReceivedMail::class, 1);
    }

    public function test_spa_csrf_guest_booking_flow_remains_public_and_idempotent(): void
    {
        Mail::fake();
        $product = $this->serviceProduct();
        $payload = $this->bookingPayload($product, [
            'customer_email' => 'csrf-booking@example.test',
            'idempotency_key' => 'csrf-booking-replay',
        ]);
        $csrfToken = 'test-csrf-token';
        $headers = [
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000/dat-tee-time/'.$product->slug,
            'X-CSRF-TOKEN' => $csrfToken,
        ];

        $first = $this->withSession(['_token' => $csrfToken])
            ->withHeaders($headers)
            ->postJson('http://api.example.test/bookings', $payload)
            ->assertCreated();

        $second = $this->withSession(['_token' => $csrfToken])
            ->withHeaders($headers)
            ->postJson('http://api.example.test/bookings', $payload)
            ->assertCreated();

        $this->assertSame($first->json('data.booking_code'), $second->json('data.booking_code'));
        $this->assertDatabaseCount('bookings', 1);
        Mail::assertSent(BookingReceivedMail::class, 1);
    }

    public function test_invalid_booking_does_not_create_customer_booking_or_mail(): void
    {
        Mail::fake();
        $product = $this->serviceProduct();

        $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'customer_email' => 'invalid@example.test',
            'start_date' => now()->subDay()->toDateString(),
        ]))->assertUnprocessable();

        $this->assertDatabaseMissing('users', ['email' => 'invalid@example.test']);
        $this->assertDatabaseCount('bookings', 0);
        Mail::assertNothingSent();
    }

    public function test_tee_time_request_without_start_time_creates_booking_and_sends_mail(): void
    {
        Mail::fake();
        $category = Category::create([
            'name' => 'Tee time',
            'slug' => 'tee-time-'.uniqid(),
            'type' => 'service',
            'layout_key' => 'tee_time',
            'status' => 1,
        ]);
        $product = Product::create([
            'name' => 'Tan Son Nhat Golf Course',
            'slug' => 'tan-son-nhat-'.uniqid(),
            'category_id' => $category->id,
            'product_type' => 'service',
            'price' => '2500000.00',
            'status' => 1,
        ]);

        $response = $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'booking_type' => BookingType::TeeTime->value,
            'customer_email' => 'tee-time@example.test',
            'quantity' => 3,
            'booking_details' => [
                'option_name' => '(Golf + Di chuyển) Nhóm 3 - 4 người',
                'golfers' => 3,
            ],
            'idempotency_key' => 'tee-time-no-start-time-mail',
        ]));

        $response->assertCreated()
            ->assertJsonPath('meta.mail_dispatched', true)
            ->assertJsonPath('data.booking_type.value', BookingType::TeeTime->value)
            ->assertJsonPath('data.schedule.start_time', null);

        $this->assertDatabaseHas('bookings', [
            'service_product_id' => $product->id,
            'booking_type' => BookingType::TeeTime->value,
            'customer_email' => 'tee-time@example.test',
            'quantity' => 3,
            'total_amount' => '7500000.00',
        ]);
        Mail::assertSent(BookingReceivedMail::class, function (BookingReceivedMail $mail) {
            return $mail->hasTo('tee-time@example.test');
        });
        Mail::assertSent(BookingReceivedMail::class, 1);
    }

    public function test_customer_cannot_access_admin_panel_but_admin_can(): void
    {
        $customerRole = Role::query()->firstOrCreate(['code' => 'customer'], ['name' => 'Customer', 'status' => 1]);
        $adminRole = Role::query()->firstOrCreate(['code' => 'admin'], ['name' => 'Admin', 'status' => 1]);
        $customer = User::factory()->create(['role_id' => $customerRole->id]);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $this->actingAs($customer)
            ->get('http://cms.example.test/dashboard')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('http://cms.example.test/dashboard')
            ->assertOk();
    }

    public function test_mail_template_renders_price_quote_and_optional_end_date(): void
    {
        $pricedBooking = $this->bookingForMail($this->serviceProduct(price: '100000.00'), [
            'total_amount' => '200000.00',
            'pricing_mode' => PricingMode::Fixed->value,
            'end_date' => now()->addDays(3)->toDateString(),
            'booking_details' => [
                'option_name' => 'Deluxe Ocean View',
                'unit_price' => '100000.00',
                'unit' => 'đêm',
                'quantity_basis' => 2,
                'calculated_total' => '200000.00',
            ],
        ]);
        $quoteBooking = $this->bookingForMail($this->serviceProduct(price: '0.00'), [
            'total_amount' => '0.00',
            'pricing_mode' => PricingMode::Quote->value,
            'end_date' => null,
        ]);

        $pricedHtml = (new BookingReceivedMail($pricedBooking, 'https://fe.example.test/reset-password?token=abc&email=a%40example.test'))->render();
        $quoteHtml = (new BookingReceivedMail($quoteBooking))->render();

        $this->assertStringContainsString('200.000 VND', $pricedHtml);
        $this->assertStringContainsString('Deluxe Ocean View', $pricedHtml);
        $this->assertStringContainsString('100.000 VND / đêm', $pricedHtml);
        $this->assertStringContainsString('Ngày kết thúc', $pricedHtml);
        $this->assertStringContainsString('Thiết lập mật khẩu', $pricedHtml);
        $this->assertStringContainsString('Sẽ được tư vấn', $quoteHtml);
        $this->assertStringNotContainsString('Ngày kết thúc', $quoteHtml);
    }

    public function test_mail_failure_after_commit_does_not_rollback_booking(): void
    {
        Log::spy();
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('SMTP unavailable'));
        $product = $this->serviceProduct();

        $response = $this->postJson('http://api.example.test/bookings', $this->bookingPayload($product, [
            'customer_email' => 'mailfail@example.test',
            'idempotency_key' => 'mail-failure-booking',
        ]));

        $response->assertCreated()
            ->assertJsonPath('meta.mail_dispatched', false);

        $this->assertDatabaseHas('users', ['email' => 'mailfail@example.test']);
        $this->assertDatabaseHas('bookings', ['customer_email' => 'mailfail@example.test']);
        Log::shouldHaveReceived('error')->once();
    }

    private function bookingPayload(Product $product, array $overrides = []): array
    {
        return array_replace([
            'service_product_id' => $product->id,
            'booking_type' => BookingType::Tour->value,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'customer@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'adults' => 2,
            'children' => 0,
        ], $overrides);
    }

    private function serviceProduct(string $price = '100000.00'): Product
    {
        $category = Category::create([
            'name' => 'Dịch vụ',
            'slug' => 'dich-vu-'.uniqid(),
            'type' => 'service',
            'layout_key' => 'tour',
            'status' => 1,
        ]);

        return Product::create([
            'name' => 'Tour golf',
            'slug' => 'tour-golf-'.uniqid(),
            'category_id' => $category->id,
            'product_type' => 'service',
            'price' => $price,
            'status' => 1,
        ]);
    }

    private function bookingForMail(Product $product, array $overrides = []): Booking
    {
        return Booking::create(array_replace([
            'public_id' => (string) \Illuminate\Support\Str::ulid(),
            'booking_code' => 'BK'.strtoupper(substr(uniqid(), -8)),
            'service_product_id' => $product->id,
            'service_category_id' => $product->category_id,
            'booking_type' => BookingType::Tour->value,
            'service_name_snapshot' => $product->name,
            'service_slug_snapshot' => $product->slug,
            'customer_name' => 'Nguyen Van A',
            'customer_email' => 'mail@example.test',
            'customer_phone' => '0901234567',
            'start_date' => now()->addDay()->toDateString(),
            'adults' => 1,
            'children' => 0,
            'quantity' => 1,
            'currency' => 'VND',
            'pricing_mode' => PricingMode::Fixed->value,
            'unit_price' => '100000.00',
            'subtotal' => '100000.00',
            'discount_amount' => '0.00',
            'total_amount' => '100000.00',
            'booking_status' => 'pending',
            'payment_status' => 'unpaid',
        ], $overrides));
    }
}
