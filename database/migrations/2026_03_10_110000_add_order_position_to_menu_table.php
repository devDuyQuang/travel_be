<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->unsignedInteger('order_position')->default(0)->after('sort');
        });

        $all = DB::table('menu')->orderByRaw("
            CASE
            WHEN location = 'header' THEN 0
            WHEN location = 'footer' THEN 1
            ELSE 2
            END
        ")->orderBy('parent_id')->orderBy('sort')->orderBy('id')->get();
        $byParent = $all->groupBy(function ($row) { return $row->parent_id; });
        $sorted = collect();
        $build = function ($parentId) use (&$build, $byParent, &$sorted) {
            $children = $byParent->get($parentId, collect());
            foreach ($children as $child) {
                $sorted->push((object)['id' => $child->id]);
                $build($child->id);
            }
        };
        $build(null);
        foreach ($sorted->values()->all() as $index => $entry) {
            DB::table('menu')->where('id', $entry->id)->update(['order_position' => $index + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropColumn('order_position');
        });
    }
};
