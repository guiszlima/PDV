<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{


    public $fillable=[
        'name',
        
      
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'brand_product', 'brand_id', 'product_id');
    }
}