<?php

namespace App\Http\Controllers\Admin\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeOption;
use Illuminate\Support\Facades\DB;
use App\LogService;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\AttributeRequest;
use App\Models\Admin\ProductCategory;
use Morilog\Jalali\Jalalian;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        LogService::createLog(
            'بازدید مشخصات دسته بندی',
            'کاربر ' . Auth()->user()->username . ' صفحه لیست مشخصات دسته بندی را بازدید کرد',
            Attribute::class,
        );
        return view('admin.attribute.index');
    }

    public function getAttributesDataTable(){

        $attributes = Attribute::with('options')->select(['id', 'name','type','created_at'])->orderBy('id','desc');
        return DataTables::of($attributes)
        ->addColumn('type', function($type){
            if($type->type == 'single'){
                return 'تک انتخابی';
            }else if($type->type == 'multiple'){
                return 'چند انتخابی';
            }else if($type->type == 'text'){
                return 'متنی';
            }
            return '-';
        })
        ->addColumn('options', function($attribute){
            if($attribute->type !== 'text'){
                return $attribute->options->map(function($option) {
                    return '<span class="badge bg-info">' . $option->value . '</span>';
                })->implode(' ');
            }else{
                return '-';
            }
        })
        ->addColumn('action', function($row) {
            $csrf = csrf_field(); 
            $method = method_field('DELETE');
            return '
            <div class="d-flex">
                <a href="'.route('admin.shop.attribute.edit',$row->id).'" class="btn btn-sm btn-warning">ویرایش</a>
                <div class="dropdown ms-1">
                        <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            حذف
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="'.route('admin.shop.attribute.delete',$row->id).'" method="POST">
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
        ->addColumn('created_at', function($article){
            return Jalalian::fromDateTime($article->created_at)->format('Y/m/d');
        })
        ->rawColumns(['options', 'action']) 
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        LogService::createLog(
            'بازدید درج مشخصات دسته بندی جدید',
            'کاربر ' . Auth()->user()->username . ' صفحه درج مشخصات دسته بندی  جدید را بازدید کرد',
            Attribute::class,
        );
        $productCategories = ProductCategory::get();
        return view('admin.attribute.create',compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        $inputs = $request->all();
        $inputs['user_id'] = Auth()->user()->id;

        if (!empty($inputs['options']) && count($inputs['options']) !== count(array_unique($inputs['options']))) {
            return back()->withErrors(['options' => 'گزینه‌ها نباید مقادیر تکراری داشته باشند.'])->withInput();
        }
        
        
        $attribute = Attribute::create($inputs);

        if ($request->has('categories')) {
            $attribute->categories()->sync($request->categories);
        }

        if (in_array($inputs['type'], ['single', 'multiple'])) {
            foreach ($inputs['options'] ?? [] as $optionValue) {
                $attribute->options()->create([
                    'value' => $optionValue,
                    'user_id' => $inputs['user_id']
                ]);
            }
        }

        LogService::createLog(
            'درج مشخصات دسته بندی',
            'کاربر ' . Auth()->user()->username . ' مشخصه جدید '. $attribute->name.' را ایجاد کرد',
            $attribute
        );
        
        
        return redirect()->back()->with('success', 'مشخصات شما با موفقیت درج شد');
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
    public function edit(Attribute $attribute)
    {
        $productCategories = ProductCategory::get();
        return view('admin.attribute.edit',compact('attribute','productCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute)
{

    $optionValues = array_map(function ($option) {
        return $option['value'];
    }, $request->options ?? []);

    if (count($optionValues) !== count(array_unique($optionValues))) {
        return back()->withErrors(['options' => 'گزینه‌ها نباید مقادیر تکراری داشته باشند.'])->withInput();
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:text,single,multiple',
        'options' => 'nullable|array',
        'options.*.value' => 'required|string|max:255',
        'options.*.id' => 'nullable|integer|exists:attribute_options,id',
    ]);

    $attribute->update($request->only(['name', 'type']));

    $categories = $request->categories ?? []; 

    if (!empty($categories)) {
        
        $attribute->categories()->sync($categories);
    } else {
        
        $attribute->categories()->detach();
    }

    if (in_array($request->type, ['single', 'multiple'])) {
        $submittedOptions = $request->options ?? [];

        $submittedIds = collect($submittedOptions)->pluck('id')->filter()->all();

        $attribute->options()->whereNotIn('id', $submittedIds)->delete();

        foreach ($submittedOptions as $optionData) {
            if (!empty($optionData['id'])) {
                $attribute->options()->where('id', $optionData['id'])->update([
                    'value' => $optionData['value'],
                ]);
            } else {
                $attribute->options()->create([
                    'value' => $optionData['value'],
                    'user_id' => auth()->id(),
                ]);
            }
        }
    } else {
        $attribute->options()->delete();
    }

    LogService::createLog(
        'ویرایش مشخصات دسته بندی',
        'کاربر ' . Auth()->user()->username . ' مشخصه  '. $attribute->name.' را ویرایش کرد',
        $attribute
    );
    return redirect()->back()->with('success', 'ویژگی با موفقیت به‌روزرسانی شد.');
}

    

    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        try {
            $attribute->options()->delete();
    
            $attribute->delete();

            LogService::createLog(
                'حذف مشخصات دسته بندی',
                'کاربر ' . Auth()->user()->username . ' مشخصه  '. $attribute->name.' را حذف کرد',
                $attribute
            );

            return redirect()->back()->with('success', 'ویژگی با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'خطایی در حذف ویژگی رخ داد. لطفاً مجدداً تلاش کنید.']);
        }
    }
}
