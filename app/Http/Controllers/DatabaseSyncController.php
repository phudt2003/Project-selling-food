<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class DatabaseSyncController extends Controller
{
    public function __invoke()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $migrateOutput = Artisan::output();

            Artisan::call('db:seed', ['--force' => true]);
            $seedOutput = Artisan::output();

            $productCount = DB::table('tbl_product')->count();
            $categoryCount = DB::table('tbl_category_product')->count();

            return response()->json([
                'ok' => true,
                'products' => $productCount,
                'categories' => $categoryCount,
                'migrate' => trim($migrateOutput),
                'seed' => trim($seedOutput),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'ok' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
