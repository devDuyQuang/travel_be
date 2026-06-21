<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Support\ServiceLayout;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'layout_key')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('layout_key', 50)->nullable()->index()->after('type');
            });
        }

        foreach (ServiceLayout::categoryDefaults() as $slug => $layoutKey) {
            DB::table('categories')
                ->where('slug', $slug)
                ->whereRaw('LOWER(type) = ?', ['service'])
                ->update(['layout_key' => $layoutKey]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('categories', 'layout_key')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex(['layout_key']);
                $table->dropColumn('layout_key');
            });
        }
    }
};
