<?php

namespace App\Http\Controllers\Admin\Article;
use App\Models\Admin\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Admin\Article;
use App\Models\Admin\ArticleCategory;
use App\Models\Admin\File;
use Yajra\DataTables\Facades\DataTables;
use Morilog\Jalali\Jalalian;
use App\Http\Requests\Admin\ArticleRequest;
use Illuminate\Support\Facades\DB;
use App\LogService;


class ArticleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        LogService::createLog(
            'بازدید لیست مطالب',
            'کاربر ' . Auth()->user()->username . ' صفحه لیست مطالب را بازدید کرد',
            Article::class,
        );
        
        return view('admin.articles.index');
    }

    public function getArticlesDataTable(){

        $articles = Article::with('categories')->select(['id', 'title','published','created_at'])->orderBy('id','desc');
        return DataTables::of($articles)
        ->addColumn('categories', function($article) {
            return $article->categories->map(function($category) {
                return '<span class="badge bg-info">' . $category->name . '</span>';
            })->implode(' ');
        })
        ->addColumn('action', function($row) {
            $csrf = csrf_field(); 
            $method = method_field('DELETE');
            return '
            <div class="d-flex">
                <a href="'.route('admin.articles.edit',$row->id).'" class="btn btn-sm btn-warning">ویرایش</a>
                <div class="dropdown ms-1">
                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            حذف
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="'.route('admin.articles.delete',$row->id).'" method="POST">
                                '.$csrf.'
                                '.$method.'
                                    <button type="submit" class="btn text-danger">بله، حذف شود</button>
                                </form>
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0)">خیر منصرف شدم </a></li>
                        </ul>
                    </div>
            </div>';
        })->addColumn('published', function($article){
            if($article->published == 1){
                return '<span class="badge bg-success"> منتشر شده </span>';

            }else if($article->published == 0){
                return '<span class="badge bg-danger"> عدم انتشار </span>';
            }
        })->addColumn('created_at', function($article){
            return Jalalian::fromDateTime($article->created_at)->format('Y/m/d');
        })
        ->rawColumns(['categories', 'action','published']) 
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $articleCategories = ArticleCategory::get();

        LogService::createLog(
            'بازدید صفحه درج مطلب جدید',
            'کاربر ' . Auth()->user()->username . ' صفحه درج مطلب جدید را بازدید کرد',
            Article::class,
        );

        return view('admin.articles.create',compact('articleCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        dd('masha');

        $inputs = $request->all();
        $inputs['created_by'] = Auth()->user()->id;
        $article = Article::create($inputs);


        //categories
        if ($request->has('categories')) {
            foreach ($request->categories as $categoryId) {
                DB::table('article_category_article')->insert([
                    'article_id' => $article->id,
                    'article_category_id' => $categoryId, // ID دسته‌بندی
                ]);
            }
        }

        // keywords
        if ($request->has('keywords')) {
            foreach ($request->keywords as $keywordId) {
                DB::table('keywordables')->insert([
                    'keyword_id' => $keywordId,
                    'keywordable_id' => $article->id, 
                    'keywordable_type' => Article::class, 
                ]);
            }
        }

        //pictures
        if($request->hasFile('pictures')){
            foreach($request->file('pictures') as $file){
                $date = now();
                $year = $date->format('Y');
                $month = $date->format('m');
                $day = $date->format('d');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = public_path("uploads/{$year}/{$month}/{$day}");
                if (!file_exists($filePath)) {
                    mkdir($filePath, 0755, true);
                }
        
                $file->move($filePath, $filename);

                $fileRecord = new File();
                $fileRecord->file_name = $filename;
                $fileRecord->file_path = "uploads/{$year}/{$month}/{$day}/{$filename}";
                $fileRecord->file_type = $file->getClientMimeType();
                $fileRecord->fileable_type = Article::class;
                $fileRecord->fileable_id = $article->id; 
                $fileRecord->save();
            }
        }


        // pinned picture
        if($request->hasFile('pinnedPic')){
            $file = $request->file('pinnedPic');

            $date = now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path("uploads/{$year}/{$month}/{$day}");
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
    
            $file->move($filePath, $filename);

            $fileRecord = new File();
            $fileRecord->file_name = $filename;
            $fileRecord->file_path = "uploads/{$year}/{$month}/{$day}/{$filename}";
            $fileRecord->file_type = $file->getClientMimeType();
            $fileRecord->fileable_type = Article::class;
            $fileRecord->pin = 1;
            $fileRecord->fileable_id = $article->id; 
            $fileRecord->save();

        }

        LogService::createLog(
            'درج مطلب',
            'کاربر ' . Auth()->user()->username . ' مطلب '. $article->title.' را ایجاد کرد',
            $article
        );

        return redirect()->back()->with('success', 'مقاله شما با موفقیت ثبت شد.');
    }


    public function edit(Article $id)
    {
        $article = $id;
        $articleCategories = ArticleCategory::get();

        LogService::createLog(
            'بازدید صفحه ویرایش مطلب',
            'کاربر ' . Auth()->user()->username . ' صفحه ویرایش مطلب '.$article->title.' را بازدید کرد.',
            Article::class,
        );

        return view('admin.articles.edit',compact('articleCategories','article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $id)
{
    $inputs = $request->all();
    $id->update($inputs);
    
    // categories
    if ($request->has('categories')) {
        $id->categories()->sync($request->categories);
    }

    // keywords
    if ($request->has('keywords')) {
        $id->keywords()->sync($request->keywords);
    }

    // removed images
    if ($request->has('removed_images')) {
        $removedImageIds = json_decode($request->input('removed_images'), true);
    
        foreach ($removedImageIds as $imageId) {
            $file = File::find($imageId);
            if ($file) {
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $file->delete();
            }
        }
    }

    // pictures
    if ($request->hasFile('pictures')) {
        foreach ($request->file('pictures') as $file) {
            $date = now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path("uploads/{$year}/{$month}/{$day}");
            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
    
            $file->move($filePath, $filename);

            $fileRecord = new File();
            $fileRecord->file_name = $filename;
            $fileRecord->file_path = "uploads/{$year}/{$month}/{$day}/{$filename}";
            $fileRecord->file_type = $file->getClientMimeType();
            $fileRecord->fileable_type = Article::class;
            $fileRecord->fileable_id = $id->id; 
            $fileRecord->save();
        }
    }
    
    // pinned picture
    if ($request->hasFile('pinnedPic')) {
        $file = $request->file('pinnedPic');
        $date = now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = public_path("uploads/{$year}/{$month}/{$day}");
        if (!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        $file->move($filePath, $filename);

        $fileRecord = new File();
        $fileRecord->file_name = $filename;
        $fileRecord->file_path = "uploads/{$year}/{$month}/{$day}/{$filename}";
        $fileRecord->file_type = $file->getClientMimeType();
        $fileRecord->fileable_type = Article::class;
        $fileRecord->pin = 1;
        $fileRecord->fileable_id = $id->id; 
        $fileRecord->save();
    }

    LogService::createLog(
        'ویرایش مطلب',
        'کاربر ' . Auth()->user()->username . ' مطلب '. $id->title.' را ویرایش کرد',
        $id
    );

        return redirect()->back()->with('success', 'مقاله با موفقیت ویرایش شد');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        foreach ($article->files as $file) {
            $filePath = public_path($file->file_path);
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $file->delete();
        }

        $article->keywords()->detach();
        $article->categories()->detach();

        LogService::createLog(
            'حذف مطلب',
            'کاربر ' . Auth()->user()->username . ' مطلب '. $article->title.' را حذف کرد',
            $article
        );

        $article->delete();

        return redirect()->back()->with('success', 'مقاله و تمام وابستگی‌های مرتبط با موفقیت حذف شدند.');
    }

}
