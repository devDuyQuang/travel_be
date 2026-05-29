<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('path')->nullable();
            $table->string('icon')->nullable();
            $table->string('color', 32)->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('type', 50)->nullable();
            $table->string('location', 50)->nullable();

            $table->boolean('status')->default(1);
            $table->integer('sort')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index(['parent_id', 'status']);
            $table->index('sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
