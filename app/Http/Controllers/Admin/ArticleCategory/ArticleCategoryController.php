<?php

namespace App\Http\Controllers\Admin\ArticleCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User\User;
use App\Models\Admin\Article;
use App\Models\Admin\ArticleCategory;
use Yajra\DataTables\Facades\DataTables;
use Morilog\Jalali\Jalalian;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\Admin\ArticleCategoryRequest;

class ArticleCategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.article-categories.index');
    }

    public function getArticleCategoriesDataTable(){

        $articles = ArticleCategory::with(['parent','children'])->select(['id', 'name','published','parent_id','created_at']);
        return DataTables::of($articles)
        ->addColumn('name', function($article) {
            return '<a href="'.route('admin.article.categories.edit',$article->id).'">'.$article->name.'</a>';
        })
        ->addColumn('parent', function($articleCategory){
            if($articleCategory->parent == null){
                return '<span class="badge bg-info"> بدون والد </span>';
            }else{
                return '<span class="badge bg-info">'.$articleCategory->parent->name.'</span>';
            }
        })
        ->addColumn('action', function($row) {
            $childrenCount = $row->children->count();
            if($childrenCount == 0){
                $csrf = csrf_field(); 
                $method = method_field('DELETE');
                return '
                <div class="d-flex">
                    <a href="'.route('admin.article.categories.edit',$row->id).'" class="btn btn-sm btn-warning">ویرایش</a>
                    <div class="dropdown ms-1">
                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            حذف
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="'.route('admin.article.categories.delete',$row->id).'" method="POST">
                                '.$csrf.'
                                '.$method.'
                                    <button type="submit" class="btn text-danger">بله، حذف شود</button>
                                </form>
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0)">خیر منصرف شدم </a></li>
                        </ul>
                    </div>
                </div>';

            }else{
                return '
                <div class="d-flex">
                    <a href="'.route('admin.article.categories.edit',$row->id).'" class="btn btn-sm btn-warning">ویرایش</a>
                </div>';

            }
        })->addColumn('published', function($article){
            if($article->published == 1){
                return '<span class="badge bg-success"> منتشر شده </span>';

            }else if($article->published == 0){
                return '<span class="badge bg-danger"> عدم انتشار </span>';
            }
        })->addColumn('created_at', function($article){
            return Jalalian::fromDateTime($article->created_at)->format('Y/m/d');
        })
        ->rawColumns(['parent', 'action','published','name']) 
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $articleCategories = ArticleCategory::get();
        
        return view('admin.article-categories.create',compact('articleCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCategoryRequest $request)
    {
        $inputs = $request->all();
        $inputs['created_by'] = auth()->user()->id;
        if ($inputs['parent_id'] == 0) {
            $inputs['parent_id'] = null;
        }
        
        ArticleCategory::create($inputs);
        return back()->with('success', 'درخواست شما با موفقیت ثبت شد');
    }

    public function edit(string $id)
    {
        $articleCategory = ArticleCategory::where('id',$id)->first();
        $articleCategories = ArticleCategory::where('id','!=',$articleCategory->id)->pluck('id','name');
        return view('admin.article-categories.edit',compact('articleCategory','articleCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleCategoryRequest $request, string $id)
    {
        $inputs = $request->all();
        if ($inputs['parent_id'] == 0) {
            $inputs['parent_id'] = null;
        }
        $articleCategory = ArticleCategory::findOrFail($id);
        $articleCategory->update($inputs);
        
        return back()->with('success', 'درخواست شما با موفقیت ثبت شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $articleCategory = ArticleCategory::findOrFail($id);
        if($articleCategory->children->count() > 0){
            return back()->with('error','نمیتوانید دسته بندی فرزند دارد را حذف کنید');
        }

        $articleCategory->delete();
        
        return back()->with('success','درخواست حذف شما با موفقیت انجام شد.');
    }
}
