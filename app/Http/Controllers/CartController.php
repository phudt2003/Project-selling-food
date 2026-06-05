<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;

class CartController extends Controller
{
    public function save_cart(Request $request){
        $productid = $request->productid_hidden;
        $quantity = $request->qty;
        $product_info = DB::table('tbl_product')->where('product_id', $productid)->first();

        // Tính giá sau khi giảm (nếu có)
        $discount = $product_info->discount_percentage;
        $original_price = $product_info->product_price;
        $final_price = $original_price;

    if ($discount > 0) {
        $final_price = $original_price - ($original_price * $discount / 100);
    }

        $data['id'] = $product_info->product_id;
        $data['qty'] = $quantity;
        $data['name'] = $product_info->product_name;
        $data['price'] = round($final_price); // 👈 Giá sau khi giảm
        $data['weight'] = '123';
        $data['options']['image'] = $product_info->product_image;

        Cart::add($data);
        return Redirect::to('/show-cart');
    }


    public function show_cart(){
        $cate_product = DB::table('tbl_category_product')
                          ->where('category_status','0')
                          ->orderby('category_id','desc')
                          ->get();

        // 🔁 Set lại tất cả sản phẩm trong cart có thuế = 5%
        foreach (Cart::content() as $item) {
            Cart::setTax($item->rowId, 0);
        }

        return view('pages.cart.show_cart')->with('category', $cate_product);
    }
    public function delete_to_cart($rowId){
        Cart::update($rowId, 0);
        return Redirect::to('/show-cart');
    }
    public function update_cart_quantity(Request $request){
        $rowId = $request->rowId_cart;
        $qty = $request->cart_quantity;
        Cart::update($rowId, $qty);
        return Redirect::to('/show-cart');
    }
    public function save_cart_by_weight(Request $request)
{
    $productId = $request->productid_hidden;
    // Lấy số gram người dùng muốn mua, nếu không có thì mặc định là 0
    $weightInGram = $request->weight ?? 0;

    $product = DB::table('tbl_product')->where('product_id', $productId)->first();

    if (!$product) {
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
    }

    // --- PHẦN LOGIC SỬA LỖI ---
    $base_weight_in_grams = 1000; // Mặc định là 1000g (1kg)
    $product_unit = strtolower($product->product_unit); // Lấy đơn vị và chuyển thành chữ thường

    // Tự động nhận diện khối lượng cơ bản từ chuỗi đơn vị (vd: '500g', '1 kg')
    // preg_match tìm các chữ số trong chuỗi đơn vị
    if (preg_match('/(\d+)/', $product_unit, $matches)) {
        $base_weight_in_grams = (int)$matches[1];
        // Nếu đơn vị là 'kg', thì nhân số vừa tìm được với 1000
        if (strpos($product_unit, 'kg') !== false) {
            $base_weight_in_grams *= 1000;
        }
    }

    // Nếu khối lượng cơ bản là 0 (tránh lỗi chia cho 0) thì đặt lại là 1
    if ($base_weight_in_grams == 0) {
        $base_weight_in_grams = 1;
    }

    // Tính giá mỗi gram một cách CHÍNH XÁC
    $pricePerGram = $product->product_price / $base_weight_in_grams;
    // --- KẾT THÚC PHẦN SỬA LỖI ---

    // Tính giá cuối cùng theo số gram người dùng nhập
    $final_price = $pricePerGram * $weightInGram;

    // Áp dụng giảm giá (nếu có)
    if ($product->discount_percentage > 0) {
        $final_price = $final_price - ($final_price * $product->discount_percentage / 100);
    }

    // Nếu người dùng không nhập gram (thêm từ trang sản phẩm), sử dụng tên sản phẩm gốc
    if ($weightInGram > 0) {
        $productName = $product->product_name . " ({$weightInGram}g)";
        $quantity = 1; // Mỗi lần thêm theo gram là 1 đơn vị riêng
    } else {
        // Xử lý cho trường hợp thêm sản phẩm có đơn vị cố định như 'Đùi gà 500g'
        $productName = $product->product_name . " (" . $product->product_unit . ")";
        $quantity = $request->qty ?? 1; // Lấy số lượng từ form
        // Giá cuối cùng chính là giá sau khi giảm giá của đơn vị đó
        $final_price = $product->product_price - ($product->product_price * $product->discount_percentage / 100);
    }

    $data['id'] = $product->product_id . '-' . ($weightInGram > 0 ? $weightInGram : $base_weight_in_grams); // Tạo ID duy nhất cho mỗi khối lượng
    $data['qty'] = $quantity;
    $data['name'] = $productName;
    $data['price'] = round($final_price, 0);
    $data['weight'] = $weightInGram > 0 ? $weightInGram : $base_weight_in_grams;
    $data['options']['image'] = $product->product_image;
    $data['options']['unit'] = $product->product_unit;


    Cart::add($data);

    return Redirect::to('/show-cart')->with('message', 'Đã thêm sản phẩm vào giỏ hàng');
}




}
