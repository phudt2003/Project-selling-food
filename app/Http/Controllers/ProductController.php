<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function add_product(){
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        return view('admin.add_product')->with('cate_product', $cate_product);
    }

    public function all_product(){
        $this->AuthLogin();
        $all_product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'tbl_product.category_id')
            ->orderBy('tbl_product.product_id', 'asc')
            ->paginate(10);

        return view('admin.all_product', compact('all_product'));
    }

    public function save_product(Request $request)
{
    $this->AuthLogin();

    // Validate dữ liệu
    $request->validate([
        'product_name' => 'required',
        'product_price' => 'required|numeric|min:0',
        'product_desc' => 'required',
        'product_content' => 'required',
        'product_cate' => 'required',
        'product_status' => 'required',
        'product_company' => 'required',
        'product_unit' => 'required',
        'product_date' => 'required|date',
        'expiration_date' => 'required|date|after_or_equal:product_date',
        'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ], [
        'product_name.required' => 'Vui lòng nhập tên sản phẩm',
        'product_price.required' => 'Vui lòng nhập giá sản phẩm',
        'product_price.numeric' => 'Giá sản phẩm phải là số',
        'product_desc.required' => 'Vui lòng nhập mô tả sản phẩm',
        'product_content.required' => 'Vui lòng nhập nội dung sản phẩm',
        'product_cate.required' => 'Vui lòng chọn danh mục sản phẩm',
        'product_status.required' => 'Vui lòng chọn trạng thái sản phẩm',
        'product_company.required' => 'Vui lòng nhập tên công ty sản xuất',
        'product_unit.required' => 'Vui lòng nhập đơn vị sản phẩm',
        'product_date.required' => 'Vui lòng nhập ngày sản xuất',
        'expiration_date.required' => 'Vui lòng nhập ngày hết hạn',
        'expiration_date.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày sản xuất',
        'product_image.required' => 'Vui lòng chọn ảnh sản phẩm',
        'product_image.image' => 'Tệp tải lên phải là hình ảnh',
    ]);

    $data = [];
    $data['product_name'] = $request->product_name;
    $data['product_price'] = $request->product_price;
    $data['product_desc'] = $request->product_desc;
    $data['product_content'] = $request->product_content;
    $data['category_id'] = $request->product_cate;
    $data['product_status'] = $request->product_status;
    $data['product_company'] = $request->product_company;
    $data['expiration_date'] = Carbon::parse($request->expiration_date)->format('Y-m-d');
    $data['product_date'] = Carbon::parse($request->product_date)->format('Y-m-d');
    $data['product_unit'] = $request->product_unit;
    $data['discount_percentage'] = $request->discount_percentage ?? 0;

    // Xử lý upload ảnh
    $get_image = $request->file('product_image');
    $get_name_image = $get_image->getClientOriginalName();
    $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
    $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
    $get_image->move('public/uploads/product', $new_image);
    $data['product_image'] = $new_image;

    DB::table('tbl_product')->insert($data);

    Session::put('message', 'Thêm sản phẩm thành công');
    return Redirect::to('add-product');
}


    public function active_product($product_id){
        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 0]);
        Session::put('message', 'Đã ẩn sản phẩm thành công!');
        return Redirect::to('all-product');
    }

    public function unactive_product($product_id){
        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 1]);
        Session::put('message', 'Hiển thị sản phẩm thành công!');
        return Redirect::to('all-product');
    }

    public function edit_product($product_id){
        $this->AuthLogin();
        $cate_product = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        $edit_product = DB::table('tbl_product')->where('product_id', $product_id)->get();

        return view('admin.edit_product')
            ->with('edit_product', $edit_product)
            ->with('cate_product', $cate_product);
    }

    public function update_product(Request $request, $product_id){
        $this->AuthLogin();

        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_price'] = $request->product_price;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['category_id'] = $request->product_cate;
        $data['product_status'] = $request->product_status;
        $data['product_company'] = $request->product_company;
        $data['expiration_date'] = $request->expiration_date ? Carbon::parse($request->expiration_date)->format('Y-m-d') : null;
        $data['product_date'] = $request->product_date ? Carbon::parse($request->product_date)->format('Y-m-d') : null;
        $data['product_unit'] = $request->input('product_unit');
        $data['discount_percentage'] = $request->discount_percentage ?? 0;

        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . rand(0, 99) . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/product', $new_image);
            $data['product_image'] = $new_image;
        }

        DB::table('tbl_product')->where('product_id', $product_id)->update($data);
        Session::put('message', 'Cập nhật sản phẩm thành công');
        return Redirect::to('all-product');
    }

    public function delete_product($product_id){
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id', $product_id)->delete();
        Session::put('message', 'Xóa sản phẩm thành công!');
        return Redirect::to('all-product');
    }

    // ✅ Chi tiết sản phẩm và sản phẩm liên quan
    public function details_product($product_id){
        $product = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->where('tbl_product.product_id', $product_id)
            ->select('tbl_product.*', 'tbl_category_product.category_name')
            ->first();

        if (!$product) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        $related = DB::table('tbl_product')
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product_id)
            ->limit(6)
            ->get();

        return view('pages.sanpham.show_details')
            ->with('product_details', [$product])
            ->with('relate', $related);
    }
}
