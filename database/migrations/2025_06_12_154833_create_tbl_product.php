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
    public function up(): void
    {
        Schema::create('tbl_product', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name');
            $table->integer('category_id');
            $table->text('product_desc')->nullable();
            $table->text('product_content')->nullable();
            $table->string('product_price');
            $table->string('product_image');
            $table->integer('product_status');
            $table->string('product_company')->nullable();
            $table->date('product_date')->nullable(); // Đảm bảo dòng này có ->nullable()
            $table->date('expiration_date')->nullable(); // Đảm bảo dòng này có
            $table->string('product_unit')->nullable(); // Đảm bảo dòng này có
            $table->integer('discount_percentage')->default(0)->nullable(); // Đảm bảo dòng này có
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_product');
    }
};