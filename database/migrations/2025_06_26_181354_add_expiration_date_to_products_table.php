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
        Schema::table('tbl_product', function (Blueprint $table) {
            // Thêm cột 'expiration_date' với kiểu dữ liệu DATE
            // nullable() để cho phép giá trị NULL nếu không nhập
            $table->date('expiration_date')->nullable()->after('product_date'); // Đặt sau product_date cho hợp lý
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            // Xóa cột 'expiration_date' khi rollback
            $table->dropColumn('expiration_date');
        });
    }
};