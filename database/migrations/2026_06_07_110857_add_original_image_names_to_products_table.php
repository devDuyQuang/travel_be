<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_original_name')->nullable()->after('image');
            $table->string('gallery_image_1_original_name')->nullable()->after('gallery_image_1');
            $table->string('gallery_image_2_original_name')->nullable()->after('gallery_image_2');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'image_original_name',
                'gallery_image_1_original_name',
                'gallery_image_2_original_name',
            ]);
        });
    }
};