<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();

            $table->decimal('price', 15, 2)->nullable();

            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('sort')->default(0);
            $table->unsignedInteger('order_position')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};