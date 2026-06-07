<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $categories = DB::table('tbl_category_product')
                ->where('category_status', '1')
                ->orderBy('category_id', 'asc')
                ->get();
            $parent_categories = $categories->where('parent_id', 0)->values();
            $category_children = $categories->where('parent_id', '!=', 0)->groupBy('parent_id');

            $all_product = DB::table('tbl_product')
                ->where('product_status', '1')
                ->orderBy('product_id', 'desc')
                ->limit(10)
                ->get();

            $shock_sale_products = DB::table('tbl_product')
                ->where('product_status', '1')
                ->where('discount_percentage', '>', 0)
                ->orderBy('product_id', 'desc')
                ->limit(4)
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            $parent_categories = collect();
            $category_children = collect();
            $all_product = collect();
            $shock_sale_products = collect();
        }

        return view('pages.home')
            ->with('parent_categories', $parent_categories)
            ->with('category_children', $category_children)
            ->with('all_product', $all_product)
            ->with('shock_sale_products', $shock_sale_products);
    }

    public function show_shock_sale_products()
    {
        try {
            $shock_sale_products = DB::table('tbl_product')
                ->where('product_status', '1')
                ->where('discount_percentage', '>', 0)
                ->orderBy('product_id', 'desc')
                ->get();

            $categories = DB::table('tbl_category_product')
                ->where('category_status', '1')
                ->orderBy('category_id', 'asc')
                ->get();
            $parent_categories = $categories->where('parent_id', 0)->values();
            $category_children = $categories->where('parent_id', '!=', 0)->groupBy('parent_id');
        } catch (Throwable $exception) {
            report($exception);

            $shock_sale_products = collect();
            $parent_categories = collect();
            $category_children = collect();
        }

        return view('pages.show_shock_sale_products')
            ->with('shock_sale_products', $shock_sale_products)
            ->with('parent_categories', $parent_categories)
            ->with('category_children', $category_children);
    }

    public function search(Request $request)
    {
        $keywords = $request->keywords_submit;

        if (empty($keywords)) {
            return redirect()->back()->with('message', 'Ban chua nhap tu khoa tim kiem');
        }

        try {
            $cate_product = DB::table('tbl_category_product')
                ->where('category_status', '0')
                ->orderBy('category_id', 'desc')
                ->get();

            $search_product = DB::table('tbl_product')
                ->where('product_status', '1')
                ->where('product_name', 'like', '%' . $keywords . '%')
                ->get();
        } catch (Throwable $exception) {
            report($exception);

            $cate_product = collect();
            $search_product = collect();
        }

        return view('pages.sanpham.search')
            ->with('category', $cate_product)
            ->with('search_product', $search_product)
            ->with('message', $search_product->isEmpty() ? 'Khong tim thay san pham' : null);
    }
}
