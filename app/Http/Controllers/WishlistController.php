<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class WishlistController extends Controller
{
    // Hiển thị danh sách wishlist
    public function index()
    {
        $wishlist = Session::get('wishlist', []);
        return view('pages.wishlist', compact('wishlist'));
    }

    // Thêm vào wishlist
    public function add_wishlist(Request $request)
    {
        // ✅ Bắt buộc đăng nhập
        if (!Session::get('customer_id')) {
            // Lưu URL trước khi login để quay lại
            Session::put('url.intended', url()->previous());
            return redirect('/login-checkout')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào yêu thích.');
        }

        $product_id = $request->product_id;
        $product = Product::find($product_id);

        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
        }

        $wishlist = Session::get('wishlist', []);

        // Kiểm tra trùng sản phẩm
        if (!isset($wishlist[$product_id])) {
            $wishlist[$product_id] = [
                'id'    => $product->product_id,
                'name'  => $product->product_name,
                'image' => $product->product_image,
                'price' => $product->product_price,
                'unit'  => $product->product_unit,
            ];
        }

        Session::put('wishlist', $wishlist);

        return redirect()->route('wishlist.index')->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    // Xóa sản phẩm khỏi wishlist
    public function remove($id)
    {
        $wishlist = Session::get('wishlist', []);

        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            Session::put('wishlist', $wishlist);
        }

        return redirect()->route('wishlist.index')->with('success', 'Đã xóa sản phẩm khỏi yêu thích!');
    }
}
