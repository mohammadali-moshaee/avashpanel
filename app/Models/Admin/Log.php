<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'action',
        'description',
        'user_id',
        'ip_address',
        'model_type',
        'model_id',
    ];
}
