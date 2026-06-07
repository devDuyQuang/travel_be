<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'location')) {
                $table->string('location')->nullable()->after('description');
            }

            if (!Schema::hasColumn('products', 'duration')) {
                $table->string('duration')->nullable()->after('location');
            }

            if (!Schema::hasColumn('products', 'review_rating')) {
                $table->decimal('review_rating', 2, 1)->nullable()->after('duration');
            }

            if (!Schema::hasColumn('products', 'review_count')) {
                $table->string('review_count')->nullable()->after('review_rating');
            }

            if (!Schema::hasColumn('products', 'established_year')) {
                $table->unsignedSmallInteger('established_year')->nullable()->after('review_count');
            }

            if (!Schema::hasColumn('products', 'highlight')) {
                $table->text('highlight')->nullable()->after('established_year');
            }

            if (!Schema::hasColumn('products', 'facility')) {
                $table->text('facility')->nullable()->after('highlight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'review_rating',
                'review_count',
                'established_year',
                'highlight',
                'facility',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Không drop location và duration vì project của bạn đã có sẵn/đang dùng.
        });
    }
};