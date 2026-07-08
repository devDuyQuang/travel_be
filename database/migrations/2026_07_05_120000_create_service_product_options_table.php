<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_product_options')) {
            return;
        }

        Schema::create('service_product_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_product_id')->constrained('products')->cascadeOnDelete();
            $table->string('type', 50)->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 10)->default('VND');
            $table->string('unit', 50)->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['service_product_id', 'type', 'is_active', 'sort_order'], 'service_product_options_service_type_active_sort_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_product_options');
    }
};
