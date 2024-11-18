<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','parent_id','published','created_by'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category_article');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent()
    {
        return $this->belongsTo(ArticleCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ArticleCategory::class, 'parent_id');
    }
}
