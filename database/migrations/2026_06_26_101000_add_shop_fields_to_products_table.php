<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type')->default('service')->after('category_id');
            }
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('product_type');
            }
            if (! Schema::hasColumn('products', 'regular_price')) {
                $table->decimal('regular_price', 15, 2)->nullable()->after('price_discount');
            }
            if (! Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 15, 2)->nullable()->after('regular_price');
            }
            if (! Schema::hasColumn('products', 'stock_quantity')) {
                $table->unsignedInteger('stock_quantity')->default(0)->after('sale_price');
            }
            if (! Schema::hasColumn('products', 'manage_stock')) {
                $table->boolean('manage_stock')->default(false)->after('stock_quantity');
            }
            if (! Schema::hasColumn('products', 'stock_status')) {
                $table->string('stock_status')->default('in_stock')->after('manage_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach ([
                'stock_status',
                'manage_stock',
                'stock_quantity',
                'sale_price',
                'regular_price',
                'sku',
                'product_type',
            ] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
