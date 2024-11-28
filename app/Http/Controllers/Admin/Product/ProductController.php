<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ProductCategory;
use App\Models\Admin\Product;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use App\LogService;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\AttributeRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('admin.product.index');
    }

    public function getProductsDataTable(){

        $products = Product::with('categories')->select(['id', 'name','created_at'])->orderBy('id','desc');
        return DataTables::of($products)
        ->addColumn('categories', function($product){
            return $product->categories->map(function($category) {
                return '<span class="badge bg-info">' . $category->name . '</span>';
            })->implode(' ');
            
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
        ->addColumn('created_at', function($article){
            return Jalalian::fromDateTime($article->created_at)->format('Y/m/d');
        })
        ->rawColumns(['categories', 'action']) 
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
