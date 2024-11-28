<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_association','product_id','category_id');
    }
}
