<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Support\ServiceLayout;
use Illuminate\Database\Seeder;

class ServiceCategoryLayoutSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ServiceLayout::categoryDefaults() as $slug => $layoutKey) {
            Category::query()
                ->where('slug', $slug)
                ->whereRaw('LOWER(type) = ?', ['service'])
                ->update(['layout_key' => $layoutKey]);
        }
    }
}
