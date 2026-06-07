<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tbl_shipping')) {
            Schema::table('tbl_shipping', function (Blueprint $table) {
                if (!Schema::hasColumn('tbl_shipping', 'shipping_notes')) {
                    $table->text('shipping_notes')->nullable();
                }

                if (!Schema::hasColumn('tbl_shipping', 'is_default')) {
                    $table->boolean('is_default')->default(false);
                }
            });
        }

        if (Schema::hasTable('tbl_order') && !Schema::hasColumn('tbl_order', 'order_notes')) {
            Schema::table('tbl_order', function (Blueprint $table) {
                $table->text('order_notes')->nullable();
            });
        }

        if (Schema::hasTable('tbl_payment')) {
            $driver = Schema::getConnection()->getDriverName();

            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE tbl_payment ALTER COLUMN order_id DROP NOT NULL');
                DB::statement('ALTER TABLE tbl_payment ALTER COLUMN payment_status DROP DEFAULT');
                DB::statement('ALTER TABLE tbl_payment ALTER COLUMN payment_status TYPE VARCHAR(50) USING payment_status::text');
                DB::statement("ALTER TABLE tbl_payment ALTER COLUMN payment_status SET DEFAULT '0'");
            } elseif ($driver === 'mysql') {
                DB::statement('ALTER TABLE tbl_payment MODIFY order_id BIGINT UNSIGNED NULL');
                DB::statement("ALTER TABLE tbl_payment MODIFY payment_status VARCHAR(50) NOT NULL DEFAULT '0'");
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tbl_order') && Schema::hasColumn('tbl_order', 'order_notes')) {
            Schema::table('tbl_order', function (Blueprint $table) {
                $table->dropColumn('order_notes');
            });
        }

        if (Schema::hasTable('tbl_shipping')) {
            Schema::table('tbl_shipping', function (Blueprint $table) {
                if (Schema::hasColumn('tbl_shipping', 'is_default')) {
                    $table->dropColumn('is_default');
                }

                if (Schema::hasColumn('tbl_shipping', 'shipping_notes')) {
                    $table->dropColumn('shipping_notes');
                }
            });
        }
    }
};
