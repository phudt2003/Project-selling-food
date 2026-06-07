<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('tbl_admin')->updateOrInsert(
            ['admin_email' => 'admin@gmail.com'],
            [
                'admin_password' => '123456',
                'admin_name' => 'Admin',
                'admin_phone' => '0900000000',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('tbl_customers')->updateOrInsert(
            ['customer_email' => 'customer@gmail.com'],
            [
                'customer_name' => 'Customer Demo',
                'customer_password' => md5('123456'),
                'customer_phone' => '0900000001',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $categories = [
            ['key' => 'rau_cu', 'old_name' => 'Rau cu', 'category_name' => 'Rau củ', 'category_desc' => 'Rau củ tươi mới mỗi ngày'],
            ['key' => 'thit_heo', 'old_name' => 'Thit heo', 'category_name' => 'Thịt heo', 'category_desc' => 'Thịt heo tươi sạch'],
            ['key' => 'thit_ga', 'old_name' => 'Thit ga', 'category_name' => 'Thịt gà', 'category_desc' => 'Thịt gà tươi ngon'],
            ['key' => 'hai_san', 'old_name' => 'Hai san', 'category_name' => 'Hải sản', 'category_desc' => 'Hải sản tươi sống'],
            ['key' => 'trai_cay', 'old_name' => 'Trai cay', 'category_name' => 'Trái cây', 'category_desc' => 'Trái cây chọn lọc'],
        ];

        $categoryIds = [];

        foreach ($categories as $category) {
            $categoryId = DB::table('tbl_category_product')
                ->whereIn('category_name', [$category['old_name'], $category['category_name']])
                ->value('category_id');

            $values = [
                'category_name' => $category['category_name'],
                'category_desc' => $category['category_desc'],
                'category_status' => 1,
                'parent_id' => 0,
                'updated_at' => $now,
            ];

            if ($categoryId) {
                DB::table('tbl_category_product')->where('category_id', $categoryId)->update($values);
            } else {
                $categoryId = DB::table('tbl_category_product')->insertGetId($values + ['created_at' => $now], 'category_id');
            }

            $categoryIds[$category['key']] = $categoryId;
        }

        $products = [
            [
                'product_name' => 'Bắp cải',
                'category' => 'rau_cu',
                'product_desc' => 'Bắp cải tươi, giòn ngọt.',
                'product_content' => 'Phù hợp nấu canh, xào hoặc làm salad.',
                'product_price' => '25000',
                'product_image' => 'bapcai50.jpg',
                'product_company' => 'Fresh Farm',
                'product_unit' => 'kg',
                'discount_percentage' => 10,
            ],
            [
                'product_name' => 'Cải thìa',
                'category' => 'rau_cu',
                'product_desc' => 'Cải thìa xanh tươi.',
                'product_content' => 'Rau sạch cho bữa ăn gia đình.',
                'product_price' => '18000',
                'product_image' => 'caithia39.jpg',
                'product_company' => 'Fresh Farm',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Sườn heo',
                'category' => 'thit_heo',
                'product_desc' => 'Sườn heo tươi ngon.',
                'product_content' => 'Phù hợp kho, ram, nấu canh.',
                'product_price' => '120000',
                'product_image' => 'suonheo34.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 5,
            ],
            [
                'product_name' => 'Cốt lết heo',
                'category' => 'thit_heo',
                'product_desc' => 'Cốt lết heo cắt miếng.',
                'product_content' => 'Thích hợp chiên, nướng hoặc áp chảo.',
                'product_price' => '95000',
                'product_image' => 'cotlech7.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Đùi gà',
                'category' => 'thit_ga',
                'product_desc' => 'Đùi gà tươi.',
                'product_content' => 'Nguồn hàng được chọn lọc mỗi ngày.',
                'product_price' => '78000',
                'product_image' => 'duiga39.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 15,
            ],
            [
                'product_name' => 'Cá nục',
                'category' => 'hai_san',
                'product_desc' => 'Cá nục tươi.',
                'product_content' => 'Hải sản tươi, bảo quản lạnh.',
                'product_price' => '65000',
                'product_image' => 'canuc57.jpg',
                'product_company' => 'Fresh Seafood',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Táo đỏ',
                'category' => 'trai_cay',
                'product_desc' => 'Táo đỏ giòn ngọt.',
                'product_content' => 'Trái cây tươi phù hợp ăn trực tiếp.',
                'product_price' => '55000',
                'product_image' => 'tao43.jpg',
                'product_company' => 'Fresh Fruit',
                'product_unit' => 'kg',
                'discount_percentage' => 8,
            ],
        ];

        foreach ($products as $product) {
            DB::table('tbl_product')->updateOrInsert(
                ['product_image' => $product['product_image']],
                [
                    'category_id' => $categoryIds[$product['category']],
                    'product_name' => $product['product_name'],
                    'product_desc' => $product['product_desc'],
                    'product_content' => $product['product_content'],
                    'product_price' => $product['product_price'],
                    'product_image' => $product['product_image'],
                    'product_status' => 1,
                    'product_company' => $product['product_company'],
                    'product_date' => $now->toDateString(),
                    'expiration_date' => $now->copy()->addDays(7)->toDateString(),
                    'product_unit' => $product['product_unit'],
                    'discount_percentage' => $product['discount_percentage'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
