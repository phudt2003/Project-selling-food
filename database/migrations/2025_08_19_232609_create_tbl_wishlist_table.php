<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_wishlist', function (Blueprint $table) {
            $table->id('wishlist_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('product_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_wishlist');
    }
};
