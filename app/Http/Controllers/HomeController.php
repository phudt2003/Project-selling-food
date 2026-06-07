<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        $data = $this->getHomeData();

        if ($data['all_product']->isEmpty() || $data['parent_categories']->isEmpty()) {
            $this->setupDatabase();
            $data = $this->getHomeData();
        }

        if ($data['all_product']->isEmpty() || $data['parent_categories']->isEmpty()) {
            $data = $this->fallbackHomeData();
        }

        return view('pages.home')
            ->with('parent_categories', $data['parent_categories'])
            ->with('category_children', $data['category_children'])
            ->with('all_product', $data['all_product'])
            ->with('shock_sale_products', $data['shock_sale_products']);
    }

    public function show_shock_sale_products()
    {
        $data = $this->getHomeData(false);

        if ($data['shock_sale_products']->isEmpty() || $data['parent_categories']->isEmpty()) {
            $this->setupDatabase();
            $data = $this->getHomeData(false);
        }

        if ($data['shock_sale_products']->isEmpty() || $data['parent_categories']->isEmpty()) {
            $data = $this->fallbackHomeData(false);
        }

        return view('pages.show_shock_sale_products')
            ->with('shock_sale_products', $data['shock_sale_products'])
            ->with('parent_categories', $data['parent_categories'])
            ->with('category_children', $data['category_children']);
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

    private function getHomeData(bool $limitProducts = true): array
    {
        try {
            $categories = DB::table('tbl_category_product')
                ->where('category_status', '1')
                ->orderBy('category_id', 'asc')
                ->get();

            $productsQuery = DB::table('tbl_product')
                ->where('product_status', '1')
                ->orderBy('product_id', 'desc');

            $all_product = $limitProducts
                ? $productsQuery->limit(10)->get()
                : $productsQuery->get();

            $shock_sale_products = DB::table('tbl_product')
                ->where('product_status', '1')
                ->where('discount_percentage', '>', 0)
                ->orderBy('product_id', 'desc')
                ->limit($limitProducts ? 4 : 100)
                ->get();

            return [
                'parent_categories' => $categories->where('parent_id', 0)->values(),
                'category_children' => $categories->where('parent_id', '!=', 0)->groupBy('parent_id'),
                'all_product' => $all_product,
                'shock_sale_products' => $shock_sale_products,
            ];
        } catch (Throwable $exception) {
            report($exception);

            return [
                'parent_categories' => collect(),
                'category_children' => collect(),
                'all_product' => collect(),
                'shock_sale_products' => collect(),
            ];
        }
    }

    private function setupDatabase(): void
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function fallbackHomeData(bool $limitProducts = true): array
    {
        $categories = collect([
            (object) ['category_id' => 1, 'category_name' => 'Rau cu', 'parent_id' => 0],
            (object) ['category_id' => 2, 'category_name' => 'Thit heo', 'parent_id' => 0],
            (object) ['category_id' => 3, 'category_name' => 'Thit ga', 'parent_id' => 0],
            (object) ['category_id' => 4, 'category_name' => 'Hai san', 'parent_id' => 0],
            (object) ['category_id' => 5, 'category_name' => 'Trai cay', 'parent_id' => 0],
        ]);

        $products = collect([
            $this->fallbackProduct(1, 'Bap cai', 1, '25000', 'bapcai50.jpg', 'kg', 10),
            $this->fallbackProduct(2, 'Cai thia', 1, '18000', 'caithia39.jpg', 'kg', 0),
            $this->fallbackProduct(3, 'Suon heo', 2, '120000', 'suonheo34.jpg', 'kg', 5),
            $this->fallbackProduct(4, 'Cot lech heo', 2, '95000', 'cotlech7.jpg', 'kg', 0),
            $this->fallbackProduct(5, 'Dui ga', 3, '78000', 'duiga39.jpg', 'kg', 15),
            $this->fallbackProduct(6, 'Ca nuc', 4, '65000', 'canuc57.jpg', 'kg', 0),
            $this->fallbackProduct(7, 'Tao do', 5, '55000', 'tao43.jpg', 'kg', 8),
        ]);

        return [
            'parent_categories' => $categories,
            'category_children' => collect(),
            'all_product' => $limitProducts ? $products->take(10)->values() : $products,
            'shock_sale_products' => $products->where('discount_percentage', '>', 0)->values(),
        ];
    }

    private function fallbackProduct(
        int $id,
        string $name,
        int $categoryId,
        string $price,
        string $image,
        string $unit,
        int $discount
    ): object {
        return (object) [
            'product_id' => $id,
            'product_name' => $name,
            'category_id' => $categoryId,
            'product_desc' => $name,
            'product_content' => $name,
            'product_price' => $price,
            'product_image' => $image,
            'product_status' => 1,
            'product_company' => 'Fresh',
            'product_date' => now()->toDateString(),
            'expiration_date' => now()->addDays(7)->toDateString(),
            'product_unit' => $unit,
            'discount_percentage' => $discount,
            'is_fallback' => true,
        ];
    }
}
