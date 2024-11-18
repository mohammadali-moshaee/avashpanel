<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['file_name', 'file_path', 'file_type','pin'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
