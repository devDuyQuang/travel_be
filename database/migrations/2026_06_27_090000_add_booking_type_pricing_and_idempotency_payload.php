<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'booking_type')) {
                $table->string('booking_type', 30)->default('tour')->after('service_category_id')->index();
            }
            if (! Schema::hasColumn('bookings', 'pricing_mode')) {
                $table->string('pricing_mode', 30)->default('quote')->after('currency')->index();
            }
            if (! Schema::hasColumn('bookings', 'pricing_snapshot')) {
                $table->json('pricing_snapshot')->nullable()->after('total_amount');
            }
            if (! Schema::hasColumn('bookings', 'booking_details')) {
                $table->json('booking_details')->nullable()->after('pricing_snapshot');
            }
            if (! Schema::hasColumn('bookings', 'idempotency_payload_hash')) {
                $table->string('idempotency_payload_hash', 64)->nullable()->after('idempotency_key');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'idempotency_payload_hash')) {
                $table->string('idempotency_payload_hash', 64)->nullable()->after('idempotency_key');
            }
        });

        if (! Schema::hasTable('booking_price_histories')) {
            Schema::create('booking_price_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->decimal('from_total_amount', 15, 2)->nullable();
                $table->decimal('to_total_amount', 15, 2);
                $table->string('from_pricing_mode', 30)->nullable();
                $table->string('to_pricing_mode', 30);
                $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('note')->nullable();
                $table->timestamps();

                $table->index(['booking_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_price_histories');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'idempotency_payload_hash')) {
                $table->dropColumn('idempotency_payload_hash');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            foreach ([
                'idempotency_payload_hash',
                'booking_details',
                'pricing_snapshot',
                'pricing_mode',
                'booking_type',
            ] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
