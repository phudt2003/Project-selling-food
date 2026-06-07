<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Throwable;

class ProductDetailController extends Controller
{
    public function __invoke($product_id)
    {
        try {
            $product = DB::table('tbl_product')
                ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
                ->where('tbl_product.product_id', $product_id)
                ->select('tbl_product.*', 'tbl_category_product.category_name')
                ->first();

            if (! $product) {
                abort(404, 'San pham khong ton tai');
            }

            $related = DB::table('tbl_product')
                ->where('category_id', $product->category_id)
                ->where('product_id', '!=', $product_id)
                ->limit(6)
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            return response()
                ->view('errors.db_connection', ['error' => $exception->getMessage()], 500);
        }

        return view('pages.sanpham.show_details')
            ->with('product_details', [$product])
            ->with('relate', $related);
    }
}
