<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'tbl_product';    // tên bảng
    protected $primaryKey = 'product_id'; // khóa chính
    public $timestamps = false;           // bảng không có created_at, updated_at

    protected $fillable = [
        'product_name',
        'product_desc',
        'product_content',
        'product_price',
        'product_unit',
        'discount_percentage',
        'product_image',
        'product_status',
        'category_id'
    ];
}
