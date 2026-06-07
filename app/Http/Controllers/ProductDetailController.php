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

            if ($product) {
                $related = DB::table('tbl_product')
                    ->where('category_id', $product->category_id)
                    ->where('product_id', '!=', $product_id)
                    ->limit(6)
                    ->get();

                return view('pages.sanpham.show_details')
                    ->with('product_details', [$product])
                    ->with('relate', $related);
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        $fallbackProducts = $this->fallbackProducts();
        $product = $fallbackProducts->firstWhere('product_id', (int) $product_id);

        if (! $product) {
            abort(404, 'San pham khong ton tai');
        }

        $related = $fallbackProducts
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->values();

        return view('pages.sanpham.show_details')
            ->with('product_details', [$product])
            ->with('relate', $related);
    }

    private function fallbackProducts()
    {
        return collect([
            $this->fallbackProduct(1, 'Bap cai', 'Rau cu', 1, '25000', 'bapcai50.jpg', 'kg', 10),
            $this->fallbackProduct(2, 'Cai thia', 'Rau cu', 1, '18000', 'caithia39.jpg', 'kg', 0),
            $this->fallbackProduct(3, 'Suon heo', 'Thit heo', 2, '120000', 'suonheo34.jpg', 'kg', 5),
            $this->fallbackProduct(4, 'Cot lech heo', 'Thit heo', 2, '95000', 'cotlech7.jpg', 'kg', 0),
            $this->fallbackProduct(5, 'Dui ga', 'Thit ga', 3, '78000', 'duiga39.jpg', 'kg', 15),
            $this->fallbackProduct(6, 'Ca nuc', 'Hai san', 4, '65000', 'canuc57.jpg', 'kg', 0),
            $this->fallbackProduct(7, 'Tao do', 'Trai cay', 5, '55000', 'tao43.jpg', 'kg', 8),
        ]);
    }

    private function fallbackProduct(
        int $id,
        string $name,
        string $categoryName,
        int $categoryId,
        string $price,
        string $image,
        string $unit,
        int $discount
    ): object {
        return (object) [
            'product_id' => $id,
            'product_name' => $name,
            'category_name' => $categoryName,
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
