<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\User\User;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'short_description',
        'created_by',
        'updated_by',
        'published',
        'price',
        'seo_title',
        'seo_description'
    ];

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category','product_id','category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_id');
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function keywords()
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }

}
