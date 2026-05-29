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
        Schema::table('degrees', function (Blueprint $table) {
            $table->string('link_text')->nullable()->after('description');
            $table->string('year', 10)->nullable()->after('link_text');
        });
    }

    public function down(): void
    {
        Schema::table('degrees', function (Blueprint $table) {
            $table->dropColumn(['link_text', 'year']);
        });
    }
};
