<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ProductCategory;
use App\Models\Admin\Product;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeOption;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use App\LogService;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Admin\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        LogService::createLog(
            'بازدید لیست محصولات',
            'کاربر ' . Auth()->user()->username . ' صفحه لیست محصولات را بازدید کرد',
            Product::class,
        );    
        return view('admin.product.index');
    }

    public function getProductsDataTable(){

        $products = Product::with(['categories','user','files'])->select(['id', 'name','created_at','published','created_by'])->orderBy('id','desc');
        return DataTables::of($products)
        ->addColumn('categories', function($product){
            return $product->categories->map(function($category) {
                return '<span class="badge bg-info">' . $category->name . '</span>';
            })->implode(' ');
            
        })
        ->addColumn('name', function($product){
            foreach($product->files as $pinFile){
                if($pinFile->pin == 1){
                    return '<img src="'.asset($pinFile->file_path).'" width="60" class="me-2" /> '.' <a href="'.route('admin.shop.product.edit',$product->id).'" >'.$product->name.'</a>';
                }
            }
        })
        ->addColumn('action', function($row) {
            $csrf = csrf_field(); 
            $method = method_field('DELETE');
            return '
            <div class="d-flex">
                <a href="'.route('admin.shop.product.edit',$row->id).'" class="btn btn-sm btn-warning">ویرایش</a>
                <div class="dropdown ms-1">
                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            حذف
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="'.route('admin.shop.product.delete',$row->id).'" method="POST">
                                '.$csrf.'
                                '.$method.'
                                    <button type="submit" class="btn text-danger">بله، حذف شود</button>
                                </form>
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0)">خیر منصرف شدم </a></li>
                        </ul>
                    </div>
            </div>';
        })
        ->addColumn('created_at', function($product){
            return Jalalian::fromDateTime($product->created_at)->format('Y/m/d');
        })
        ->addColumn('created_by', function($product){
            return $product->user->firstname.' '.$product->user->lastname;
        })
        ->addColumn('published', function($product){
            if($product->published == 1){
                return '<span class="badge bg-success"> منتشر شده </span>';

            }else if($product->published == 0){
                return '<span class="badge bg-danger"> عدم انتشار </span>';
            }
        })
        ->rawColumns(['categories', 'action', 'published', 'name']) 
        ->make(true);
    }

    public function getAttributesByCategory(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);
        $attributes = Attribute::with('options') 
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->get();

        return response()->json($attributes);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::get();

        LogService::createLog(
            'بازدید صفحه درج محصول جدید',
            'کاربر ' . Auth()->user()->username . ' صفحه درج محصول جدید را بازدید کرد',
            Product::class,
        );

        return view('admin.product.create',compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
{
    DB::beginTransaction();

    try {
        // ذخیره محصول
        $product = Product::create([
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'price' => (int) str_replace(',','', $request->input('price')),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'published' => $request->input('published'),
            'sku' => $request->input('sku'),
        ]);

        // ذخیره دسته‌بندی‌ها
        if (isset($request->category_ids)) {
            $product->categories()->sync($request->category_ids);
        }

        // keywords
        if ($request->has('keywords')) {
            foreach ($request->keywords as $keywordId) {
                DB::table('keywordables')->insert([
                    'keyword_id' => $keywordId,
                    'keywordable_id' => $product->id, 
                    'keywordable_type' => Product::class, 
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
                $fileRecord->fileable_type = Product::class;
                $fileRecord->fileable_id = $product->id; 
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
            $fileRecord->fileable_type = Product::class;
            $fileRecord->pin = 1;
            $fileRecord->fileable_id = $product->id; 
            $fileRecord->save();

        }

        $attributeReq = $request->input('attributes');
        // ذخیره ویژگی‌ها در صورت وجود
        if (isset($attributeReq)) {
            foreach ($attributeReq as $attributeId => $value) {

                $attribute = Attribute::find($attributeId);

                if (!$attribute) continue;

                if ($attribute->type === 'text') {
                    // ویژگی متنی
                    $product->attributes()->create([
                        'attribute_id' => $attributeId,
                        'value' => $value
                    ]);
                } elseif ($attribute->type === 'single') {
                    // ویژگی تک انتخابی
                    $optionValue = AttributeOption::find($value);
                    if ($optionValue) {
                        // ذخیره در جدول product_attribute_values
                        $product->attributeValues()->create([
                            'attribute_id' => $attributeId,
                            'value' => $optionValue->id, // ذخیره آیدی گزینه
                        ]);
                    }
                } elseif ($attribute->type === 'multiple') {
                    // ویژگی چند انتخابی
                    // اگر ورودی آرایه نیست، آن را به آرایه تبدیل کنید
                    $values = is_array($value) ? $value : [$value];

                    foreach ($values as $item) {
                        $optionValue = AttributeOption::find($item);
                        if ($optionValue) {
                            // ذخیره در جدول product_attribute_values
                            $product->attributeValues()->create([
                                'attribute_id' => $attributeId,
                                'value' => $optionValue->id, // ذخیره آیدی گزینه
                            ]);
                        }
                    }
                }
            }
        }

        DB::commit();

        LogService::createLog(
            'درج محصول',
            'کاربر ' . Auth()->user()->username . ' محصول '. $product->name.' را ایجاد کرد',
            $product
        );
        return redirect()->route('admin.shop.product.create')->with('success', 'محصول با موفقیت ذخیره شد.');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()->withErrors('خطا در ذخیره‌سازی: ' . $e->getMessage());
    }
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
    public function edit(Product $product)
    {
        $productID = $product->id;
        $productCategories = ProductCategory::get();
        $productCategoriesSelected = $product->categories->pluck('id')->toArray();
        
        $attributes = Attribute::whereHas('productValues', function ($query) use ($productID) {
            $query->where('product_id', $productID);
        })->with(['productValues' => function ($query) use ($productID) {
            $query->where('product_id', 41)
                  ->whereNull('deleted_at')
                  ->distinct();  // جلوگیری از داده‌های تکراری
        }])->get();
        
    
        
        
        LogService::createLog(
            'بازدید صفحه ویرایش محصول',
            'کاربر ' . Auth()->user()->username . ' صفحه ویرایش محصول '.$product->name.' را بازدید کرد.',
            $product,
        );

        return view('admin.product.edit',compact('productCategories','product','productCategoriesSelected','attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        //dd($request->all());
        DB::beginTransaction();

        try {

            // به‌روزرسانی ویژگی‌های محصول
            $product->update([
                'name' => $request->input('name'),
                'short_description' => $request->input('short_description'),
                'description' => $request->input('description'),
                'price' => (int) str_replace(',','', $request->input('price')),
                'updated_by' => auth()->user()->id,
                'published' => $request->input('published'),
                'sku' => $request->input('sku'),
            ]);


            // به‌روزرسانی دسته‌بندی‌ها
            if (isset($request->category_ids)) {
                $product->categories()->sync($request->category_ids); // استفاده از sync برای به‌روزرسانی دسته‌بندی‌ها
            }

            // keywords
            if ($request->has('keywords')) {
                $product->keywords()->sync($request->keywords);
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
                    $fileRecord->fileable_type = Product::class;
                    $fileRecord->fileable_id = $product->id; 
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
                $fileRecord->fileable_type = Product::class;
                $fileRecord->pin = 1;
                $fileRecord->fileable_id = $product->id; 
                $fileRecord->save();
            }

            // به‌روزرسانی ویژگی‌ها
            $attributeReq = $request->input('attributes');
            if (isset($attributeReq)) {
                $product->attributeValues()->delete();
                foreach ($attributeReq as $attributeId => $value) {
                    $attribute = Attribute::find($attributeId);

                    if (!$attribute) continue;

                    // حذف مقادیر قبلی ویژگی‌ها
                    //$product->attributeValues()->where('attribute_id', $attributeId)->delete();
                    

                    if ($attribute->type === 'text') {
                        // ویژگی متنی
                        $product->attributes()->create([
                            'attribute_id' => $attributeId,
                            'value' => $value
                        ]);
                    } elseif ($attribute->type === 'single') {
                        // ویژگی تک انتخابی
                        $optionValue = AttributeOption::find($value);
                        if ($optionValue) {
                            // ذخیره در جدول product_attribute_values
                            $product->attributeValues()->create([
                                'attribute_id' => $attributeId,
                                'value' => $optionValue->id, // ذخیره آیدی گزینه
                            ]);
                        }
                    } elseif ($attribute->type === 'multiple') {
                        
                        // ویژگی چند انتخابی
                        // اگر ورودی آرایه نیست، آن را به آرایه تبدیل کنید
                        $values = is_array($value) ? $value : [$value];
                        
                        foreach ($values as $item) {
                            $optionValue = AttributeOption::find($item);
                            if ($optionValue) {
                                // ذخیره در جدول product_attribute_values
                                $product->attributeValues()->create([
                                    'attribute_id' => $attributeId,
                                    'value' => $optionValue->id, // ذخیره آیدی گزینه
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            LogService::createLog(
                'ویرایش محصول',
                'کاربر ' . Auth()->user()->username . ' محصول '. $product->name.' را ویرایش کرد',
                $product
            );
            
            return redirect()->route('admin.shop.product.edit', $product->id)->with('success', 'محصول با موفقیت به‌روزرسانی شد.');

        } catch (\Exception $e) {
            DB::rollBack();


            return redirect()->back()->withErrors('خطا در به‌روزرسانی: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
