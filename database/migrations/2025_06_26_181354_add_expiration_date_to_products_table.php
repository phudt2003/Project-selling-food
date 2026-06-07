<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tbl_product', 'expiration_date')) {
            return;
        }

        Schema::table('tbl_product', function (Blueprint $table) {
            $table->date('expiration_date')->nullable()->after('product_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('tbl_product', 'expiration_date')) {
            return;
        }

        Schema::table('tbl_product', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
        });
    }
};
