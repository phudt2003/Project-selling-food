<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['product_name', 'price', 'description'];
}
