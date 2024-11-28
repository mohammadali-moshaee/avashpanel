<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\User\User;

class ProductCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name', 'parent_id','user_id'];

    public function user() {
        return $this->belongsTo(User::class)->withDefault([
            'firstname' => 'نامشخص',
            'lastname' => ''
        ]);
    }
    
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_association');
    }

    public function attributes()
    {
        
        return $this->belongsToMany(Attribute::class, 'category_attribute','attribute_id', 'category_id');
    }

    
}
