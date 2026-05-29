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
        Schema::create('service_registrations', function (Blueprint $table) {
           $table->id();
            // Thông tin gói dịch vụ
            $table->string('package_name')->comment('Tên gói điều trị');
            $table->string('package_price')->nullable()->comment('Giá gói tại thời điểm đăng ký');
            
            // Thông tin khách hàng
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('message')->nullable()->comment('Lời nhắn của khách hàng');
            
            // Trạng thái xử lý (để anh quản lý trong admin)
            $table->enum('status', ['pending', 'contacted', 'canceled'])
                  ->default('pending')
                  ->comment('Trạng thái: chờ xử lý, đã liên hệ, đã hủy');
            
            $table->text('admin_note')->nullable()->comment('Ghi chú nội bộ của admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_registrations');
    }
};
