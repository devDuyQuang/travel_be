<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'title_seo')) {
                $table->string('title_seo')->nullable()->after('content');
            }

            if (!Schema::hasColumn('products', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('title_seo');
            }

            if (!Schema::hasColumn('products', 'description_seo')) {
                $table->text('description_seo')->nullable()->after('canonical_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'description_seo')) {
                $table->dropColumn('description_seo');
            }

            if (Schema::hasColumn('products', 'canonical_url')) {
                $table->dropColumn('canonical_url');
            }

            if (Schema::hasColumn('products', 'title_seo')) {
                $table->dropColumn('title_seo');
            }
        });
    }
};