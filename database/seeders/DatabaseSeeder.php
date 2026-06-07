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

        $categories = [
            ['category_name' => 'Rau cu', 'category_desc' => 'Rau cu tuoi moi moi ngay'],
            ['category_name' => 'Thit heo', 'category_desc' => 'Thit heo tuoi sach'],
            ['category_name' => 'Thit ga', 'category_desc' => 'Thit ga tuoi ngon'],
            ['category_name' => 'Hai san', 'category_desc' => 'Hai san tuoi song'],
            ['category_name' => 'Trai cay', 'category_desc' => 'Trai cay chon loc'],
        ];

        foreach ($categories as $category) {
            DB::table('tbl_category_product')->updateOrInsert(
                ['category_name' => $category['category_name']],
                [
                    'category_desc' => $category['category_desc'],
                    'category_status' => 1,
                    'parent_id' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $categoryIds = DB::table('tbl_category_product')
            ->whereIn('category_name', array_column($categories, 'category_name'))
            ->pluck('category_id', 'category_name');

        $products = [
            [
                'product_name' => 'Bap cai',
                'category' => 'Rau cu',
                'product_desc' => 'Bap cai tuoi, gion ngot.',
                'product_content' => 'Phu hop nau canh, xao hoac lam salad.',
                'product_price' => '25000',
                'product_image' => 'bapcai50.jpg',
                'product_company' => 'Fresh Farm',
                'product_unit' => 'kg',
                'discount_percentage' => 10,
            ],
            [
                'product_name' => 'Cai thia',
                'category' => 'Rau cu',
                'product_desc' => 'Cai thia xanh tuoi.',
                'product_content' => 'Rau sach cho bua an gia dinh.',
                'product_price' => '18000',
                'product_image' => 'caithia39.jpg',
                'product_company' => 'Fresh Farm',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Suon heo',
                'category' => 'Thit heo',
                'product_desc' => 'Suon heo tuoi ngon.',
                'product_content' => 'Phu hop kho, ram, nau canh.',
                'product_price' => '120000',
                'product_image' => 'suonheo34.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 5,
            ],
            [
                'product_name' => 'Cot lech heo',
                'category' => 'Thit heo',
                'product_desc' => 'Cot lech heo cat mieng.',
                'product_content' => 'Thich hop chien, nuong hoac ap chao.',
                'product_price' => '95000',
                'product_image' => 'cotlech7.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Dui ga',
                'category' => 'Thit ga',
                'product_desc' => 'Dui ga tuoi.',
                'product_content' => 'Nguon hang duoc chon loc moi ngay.',
                'product_price' => '78000',
                'product_image' => 'duiga39.jpg',
                'product_company' => 'Fresh Meat',
                'product_unit' => 'kg',
                'discount_percentage' => 15,
            ],
            [
                'product_name' => 'Ca nuc',
                'category' => 'Hai san',
                'product_desc' => 'Ca nuc tuoi.',
                'product_content' => 'Hai san tuoi, bao quan lanh.',
                'product_price' => '65000',
                'product_image' => 'canuc57.jpg',
                'product_company' => 'Fresh Seafood',
                'product_unit' => 'kg',
                'discount_percentage' => 0,
            ],
            [
                'product_name' => 'Tao do',
                'category' => 'Trai cay',
                'product_desc' => 'Tao do gion ngot.',
                'product_content' => 'Trai cay tuoi phu hop an truc tiep.',
                'product_price' => '55000',
                'product_image' => 'tao43.jpg',
                'product_company' => 'Fresh Fruit',
                'product_unit' => 'kg',
                'discount_percentage' => 8,
            ],
        ];

        foreach ($products as $product) {
            DB::table('tbl_product')->updateOrInsert(
                ['product_name' => $product['product_name']],
                [
                    'category_id' => $categoryIds[$product['category']],
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
