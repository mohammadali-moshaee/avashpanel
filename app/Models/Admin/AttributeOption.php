<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeOption extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['attribute_id', 'value','user_id'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
