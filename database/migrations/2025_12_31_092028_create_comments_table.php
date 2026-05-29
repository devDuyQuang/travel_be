<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Nội dung bình luận
            $table->text('content');
            
            // Thông tin người bình luận
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Trạng thái: pending, approved, hidden (spam), trashed (nếu dùng soft delete)
            $table->enum('status', ['pending', 'approved', 'hidden'])
                  ->default('pending')
                  ->index();
            
            // Polymorphic: bình luận thuộc về bài viết, dịch vụ, sản phẩm, v.v.
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->index(['commentable_id', 'commentable_type']);
            
            // Hỗ trợ reply (bình luận con)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');
            $table->index('parent_id');
            
            // Người duyệt bình luận (nếu cần theo dõi)
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // IP và User Agent (chống spam)
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};