<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'instagram',
        'telegram',
        'whatsapp',
        'bale',
        'soroush',
        'eitaa',
        'rubika',
        'igap',
        'facebook',
        'linkedin'
    ];
}
