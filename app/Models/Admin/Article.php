<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = ['title', 'content','lead','seo_title','seo_description','created_by','published']; 

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function categories()
    {
        return $this->belongsToMany(ArticleCategory::class, 'article_category_article');
    }
    
    public function keywords()
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }
}
