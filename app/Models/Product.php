<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'id_product_category',
    ];

    public function productCategory()
    {
        return $this->belongsTo('App\Models\ProductCategory', 'id_product_category');
    }
}
