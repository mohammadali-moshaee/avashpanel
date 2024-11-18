<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name'];

    public function keywordable()
    {
        return $this->morphedByMany(Keywordable::class, 'keywordable');
    }
}
