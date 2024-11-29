<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attribute extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'type','user_id'];
    
    public function options()
    {
        return $this->hasMany(AttributeOption::class,'attribute_id');
    }

    public function categories()
    {
        
        return $this->belongsToMany(ProductCategory::class, 'category_attribute','attribute_id', 'category_id');
    }

    public function productValues()
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }
}
