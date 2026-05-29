<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->renameColumn('type', 'topic');
            $table->renameColumn('target_id', 'part_id');
        });
    }

    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->renameColumn('topic', 'type');
            $table->renameColumn('part_id', 'target_id');
        });
    }
};
