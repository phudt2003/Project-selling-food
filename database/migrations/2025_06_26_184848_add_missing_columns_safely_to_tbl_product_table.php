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
            // Thêm cột 'expiration_date' chỉ khi nó CHƯA TỒN TẠI
            if (!Schema::hasColumn('tbl_product', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('product_date');
            }
            // Thêm cột 'product_unit' chỉ khi nó CHƯA TỒN TẠI
            if (!Schema::hasColumn('tbl_product', 'product_unit')) {
                $table->string('product_unit')->nullable()->after('product_price');
            }
            // Thêm cột 'discount_percentage' chỉ khi nó CHƯA TỒN TẠI
            if (!Schema::hasColumn('tbl_product', 'discount_percentage')) {
                $table->integer('discount_percentage')->default(0)->nullable()->after('product_unit');
            }
            // Nếu bạn cũng muốn sửa đổi product_date thành nullable, và nó chưa phải là nullable
            // bạn cần một lệnh riêng biệt vì alter column không đơn giản như add column
            // Example (for MySQL): DB::statement('ALTER TABLE tbl_product MODIFY COLUMN product_date DATE NULL');
            // Hoặc bạn phải chạy migrate:fresh như Phương án 1.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_product', function (Blueprint $table) {
            // Xóa cột chỉ khi nó tồn tại
            if (Schema::hasColumn('tbl_product', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
            if (Schema::hasColumn('tbl_product', 'product_unit')) {
                $table->dropColumn('product_unit');
            }
            if (Schema::hasColumn('tbl_product', 'discount_percentage')) {
                $table->dropColumn('discount_percentage');
            }
        });
    }
};