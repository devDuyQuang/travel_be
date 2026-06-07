<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('gallery_image_1')->nullable()->after('image');
        $table->string('gallery_image_2')->nullable()->after('gallery_image_1');
        $table->string('gallery_image_3')->nullable()->after('gallery_image_2');
        $table->string('video_url')->nullable()->after('gallery_image_3');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'gallery_image_1',
            'gallery_image_2',
            'gallery_image_3',
            'video_url',
        ]);
    });
}
};
