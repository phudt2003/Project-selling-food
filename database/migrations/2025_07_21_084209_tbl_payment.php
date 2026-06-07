<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payment', function (Blueprint $table) {
            $table->bigIncrements('payment_id');

            // Liên kết với đơn hàng
            $table->unsignedBigInteger('order_id');

            // Thông tin khách hàng (nếu muốn lưu ở đây để đối chiếu)
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            // Thông tin thanh toán
            $table->string('payment_method'); // COD, momo, vnpay...
            $table->tinyInteger('payment_status')->default(0); 
            // 0 = Chờ xử lý, 1 = Thành công, 2 = Thất bại

            $table->string('payment_code')->nullable();    // mã giao dịch từ cổng thanh toán
            $table->string('payment_message')->nullable(); // thông điệp trả về

            $table->decimal('amount', 15, 2)->default(0);  // số tiền thanh toán

            $table->timestamps();

            // Khóa ngoại
            // tbl_order is created by a later migration in this project.
            // Keep this as a plain column so a fresh deploy can migrate cleanly.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_payment');
    }
};
