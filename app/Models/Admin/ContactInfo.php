<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactInfo extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'contact_info';

    protected $fillable = [
        'phone1',
        'phone2',
        'phone3',
        'mobile1',
        'mobile2',
        'mobile3',
        'fax',
        'address',
        'location',
        'email'
    ];
}
