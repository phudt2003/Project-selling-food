<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; // Đảm bảo dòng này có nếu bạn sử dụng DB facade

class HomeController extends Controller
{
    // Giữ nguyên phương thức index của bạn
    public function index(){
        $parent_categories = DB::table('tbl_category_product')
                                ->where('category_status', '1')
                                ->where('parent_id', 0)
                                ->orderBy('category_id', 'asc')
                                ->get();

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

        return view('pages.home')->with('parent_categories', $parent_categories)
                                ->with('all_product', $all_product)
                                ->with('shock_sale_products', $shock_sale_products);
    }

    // Phương thức mới để hiển thị tất cả sản phẩm khuyến mãi
    public function show_shock_sale_products(){
        $shock_sale_products = DB::table('tbl_product')
                                ->where('product_status', '1')
                                ->where('discount_percentage', '>', 0)
                                ->orderBy('product_id', 'desc')
                                ->get(); // Lấy TẤT CẢ các sản phẩm khuyến mãi

        // Cần lấy parent_categories để hiển thị sidebar trên trang này
        $parent_categories = DB::table('tbl_category_product')
                                ->where('category_status', '1')
                                ->where('parent_id', 0)
                                ->orderBy('category_id', 'asc')
                                ->get();

        return view('pages.show_shock_sale_products')
                    ->with('shock_sale_products', $shock_sale_products)
                    ->with('parent_categories', $parent_categories);
    }
    public function search(Request $request){
    $keywords = $request->keywords_submit;

    if(empty($keywords)) {
        return redirect()->back()->with('message', 'Bạn chưa nhập từ khóa tìm kiếm');
    }

    $cate_product = DB::table('tbl_category_product')
        ->where('category_status','0')->orderBy('category_id','desc')->get();

    $search_product = DB::table('tbl_product')
        ->where('product_status', '1')
        ->where('product_name', 'like', '%'.$keywords.'%')
        ->get();

    // Luôn truyền biến $search_product, kể cả khi rỗng
    return view('pages.sanpham.search')
        ->with('category', $cate_product)
        ->with('search_product', $search_product)
        ->with('message', $search_product->isEmpty() ? 'Không tìm thấy sản phẩm' : null);
}


}