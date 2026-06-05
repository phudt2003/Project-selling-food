<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CategoryProduct extends Controller 
{
    public function AuthLogin(){
    $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function add_category_product()
    {
        // Lấy tất cả danh mục để tạo lựa chọn danh mục cha
        $category = DB::table('tbl_category_product')->orderBy('category_id', 'desc')->get();
        return view('admin.add_category_product')->with('category', $category);
    }

    public function all_category_product(){
        $this->AuthLogin();
        $all_category_product = DB::table('tbl_category_product')
            ->orderBy('category_id', 'ASC')
            ->paginate(10); // hiển thị 10 danh mục mỗi trang
        $manager_category_product = view('admin.all_category_product')->with('all_category_product',$all_category_product);
        return view('admin_layout')->with('admin.all_category_product',$manager_category_product);
    }
    public function save_category_product(Request $request)
    {
    // Validate dữ liệu
    $request->validate([
        'category_product_name' => 'required',
    ], [
        'category_product_name.required' => 'Vui lòng nhập tên danh mục',
    ]);

    // Nếu validate thành công thì tiếp tục lưu
    $data = array();
    $data['category_name'] = $request->category_product_name;
    $data['category_desc'] = $request->category_product_desc;
    $data['category_status'] = $request->category_product_status;
    $data['parent_id'] = $request->parent_id;

    DB::table('tbl_category_product')->insert($data);

    return redirect('/add-category-product')->with('message', 'Thêm danh mục sản phẩm thành công');
    }

    public function unactive_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>1]);
        Session::put('message','Kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }
    public function active_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>0]);
        Session::put('message','Không kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');   
    }
    public function edit_category_product($category_product_id){
        $this->AuthLogin();
        $edit_category_product = DB::table('tbl_category_product')->where('category_id',$category_product_id)->get();
        $manager_category_product = view('admin.edit_category_product')->with('edit_category_product',$edit_category_product);
        return view('admin_layout')->with('admin.edit_category_product',$manager_category_product);
    }
    public function update_category_product(Request $request,$category_product_id){
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;

        DB::table('tbl_category_product')->where('category_id',$category_product_id)->update($data);
        Session::put('message','Cập nhật danh mục sản phẩm thành công');
        return Redirect::to('all-category-product'); 
    }
    public function delete_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id',$category_product_id)->delete();
        Session::put('message','Xóa danh mục sản phẩm thành công');
        return Redirect::to('all-category-product'); 
    }
    //End Function Admin Page

    public function show_category_home($category_id){
        // Lấy tất cả các danh mục có status = 1 (active)
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderBy('category_id', 'desc')->get();

        // --- BẮT ĐẦU ĐIỀU CHỈNH ĐỂ HIỂN THỊ SẢN PHẨM CỦA DANH MỤC CHA VÀ CON ---

        // Bước 1: Lấy tất cả ID của danh mục con (bao gồm cả chính nó)
        // Chúng ta cần một hàm đệ quy để làm điều này.
        // Tôi sẽ định nghĩa một hàm trợ giúp riêng biệt.
        $allCategoryIds = $this->getAllChildCategoryIds($category_id);

        // Bước 2: Truy vấn sản phẩm dựa trên tất cả các ID danh mục đã tìm được
        $category_by_id = DB::table('tbl_product')
            ->join('tbl_category_product', 'tbl_product.category_id', '=', 'tbl_category_product.category_id')
            ->whereIn('tbl_product.category_id', $allCategoryIds) // <- Sửa đổi quan trọng ở đây
            ->get();
        
        // Lấy tên của danh mục hiện tại để hiển thị trên tiêu đề trang
        $category_name = DB::table('tbl_category_product')
            ->where('tbl_category_product.category_id', $category_id)
            ->limit(1)
            ->get(); // Bạn sẽ nhận được một Collection, có thể dùng first() để lấy một object

        // --- KẾT THÚC ĐIỀU CHỈNH ---

        return view('pages.category.show_category')
            ->with('category', $cate_product)
            ->with('category_by_id', $category_by_id)
            ->with('category_name', $category_name);
    }

    /**
     * Hàm trợ giúp: Lấy tất cả ID của danh mục con (bao gồm cả danh mục cha)
     * Đây là một hàm đệ quy để duyệt qua cây danh mục.
     * Có thể đặt nó ở một nơi chung hơn (ví dụ: Trait, Service) nếu muốn tái sử dụng.
     */
    private function getAllChildCategoryIds($parentCategoryId)
    {
        $ids = [$parentCategoryId]; // Bắt đầu với ID của chính danh mục cha

        // Lấy tất cả danh mục con trực tiếp của parentCategoryId
        $children = DB::table('tbl_category_product')
                    ->where('parent_id', $parentCategoryId)
                    ->get();

        foreach ($children as $child) {
            // Đệ quy để lấy ID của các danh mục con của danh mục con này
            $ids = array_merge($ids, $this->getAllChildCategoryIds($child->category_id));
        }

        return array_unique($ids); // Trả về mảng các ID danh mục duy nhất
    }

}