<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->string('booking_code', 32)->unique();
                $table->string('idempotency_key', 100)->nullable()->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('service_product_id')->constrained('products')->restrictOnDelete();
                $table->foreignId('service_category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('service_name_snapshot');
                $table->string('service_slug_snapshot');
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('customer_phone', 30);
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->time('start_time')->nullable();
                $table->unsignedSmallInteger('adults')->default(1);
                $table->unsignedSmallInteger('children')->default(0);
                $table->unsignedSmallInteger('quantity')->default(1);
                $table->char('currency', 3)->default('VND');
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->text('customer_note')->nullable();
                $table->text('internal_note')->nullable();
                $table->string('booking_status', 30)->default('pending');
                $table->string('payment_status', 30)->default('unpaid');
                $table->string('payment_method', 30)->nullable();
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('cancel_reason')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('service_category_id');
                $table->index('customer_phone');
                $table->index('customer_email');
                $table->index('booking_status');
                $table->index('payment_status');
                $table->index('start_date');
                $table->index('created_at');
            });
        }

        if (! Schema::hasTable('booking_status_histories')) {
            Schema::create('booking_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->string('from_status', 30)->nullable();
                $table->string('to_status', 30);
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['booking_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->string('order_code', 32)->unique();
                $table->string('idempotency_key', 100)->nullable()->unique();
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('customer_phone', 30);
                $table->string('shipping_address_line');
                $table->string('shipping_ward')->nullable();
                $table->string('shipping_district')->nullable();
                $table->string('shipping_province');
                $table->string('shipping_country', 2)->default('VN');
                $table->char('currency', 3)->default('VND');
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('shipping_fee', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->text('customer_note')->nullable();
                $table->text('internal_note')->nullable();
                $table->string('order_status', 30)->default('pending');
                $table->string('payment_status', 30)->default('unpaid');
                $table->string('payment_method', 30)->nullable();
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamp('shipped_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->text('cancel_reason')->nullable();
                $table->timestamp('stock_deducted_at')->nullable();
                $table->timestamp('stock_restored_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('customer_phone');
                $table->index('customer_email');
                $table->index('order_status');
                $table->index('payment_status');
                $table->index('payment_method');
                $table->index('created_at');
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                $table->string('product_name_snapshot');
                $table->string('product_slug_snapshot');
                $table->string('sku_snapshot')->nullable();
                $table->string('image_snapshot')->nullable();
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->unsignedInteger('quantity');
                $table->decimal('line_total', 15, 2)->default(0);
                $table->timestamps();

                $table->index('order_id');
                $table->index('product_id');
            });
        }

        if (! Schema::hasTable('order_status_histories')) {
            Schema::create('order_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('from_status', 30)->nullable();
                $table->string('to_status', 30);
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->nullableMorphs('payable');
                $table->string('provider', 50)->default('manual');
                $table->string('transaction_code')->nullable()->index();
                $table->decimal('amount', 15, 2)->default(0);
                $table->char('currency', 3)->default('VND');
                $table->string('status', 30)->default('unpaid');
                $table->string('method', 30)->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->json('provider_payload')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('method');
                $table->index('created_at');
            });
        }

        if (! Schema::hasTable('payment_status_histories')) {
            Schema::create('payment_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
                $table->string('from_status', 30)->nullable();
                $table->string('to_status', 30);
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['payment_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_status_histories');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('booking_status_histories');
        Schema::dropIfExists('bookings');
    }
};
