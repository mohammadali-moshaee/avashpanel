<?php

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ProductCategory;
use App\LogService;
use Yajra\DataTables\Facades\DataTables;
use Morilog\Jalali\Jalalian;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::with('parent', 'user')->get();

        return view('admin.product-category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::get();
        return view('admin.product-category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:product_categories,name', 
    ], [
        'name.required' => 'نام دسته‌بندی الزامی است.',
        'name.unique' => 'این نام دسته‌بندی قبلاً استفاده شده است.',
    ]);

    $requestData = $request->all();
    $requestData['user_id'] = auth()->id(); 
    
    ProductCategory::create($requestData); 
    
    return redirect()->back()->with('success', 'دسته بندی شما با موفقیت درج شد.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $category)
    {
        $childrenIds = $category->children->pluck('id')->toArray();
        $categories = ProductCategory::where('id', '!=', $category->id)  
                                ->whereNotIn('id', $childrenIds)
                                ->get();


        return view('admin.product-category.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name' => 'required|unique:product_categories,name,' . $category->id,
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.unique' => 'این نام دسته‌بندی قبلاً استفاده شده است.',
        ]);
        
        $category->update($request->all());
    
        return redirect()->back()->with('success', 'دسته بندی محصول شما با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $category)
{
    if ($category->id == 4) {
        return redirect()->back()->with('error', 'دسته‌بندی با ID 4 قابل حذف نیست.');
    }

    if ($category->children->isNotEmpty()) {
        return redirect()->back()->with('error', 'این دسته‌بندی دارای زیرمجموعه است و نمی‌توان آن را حذف کرد.');
    }

    $category->delete();

    return redirect()->route('admin.shop.category.index')->with('success', 'دسته‌بندی با موفقیت حذف شد.');
}

}
