<?php

namespace App\Http\Controllers\Admin\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LogService;
use Yajra\DataTables\Facades\DataTables;
use Morilog\Jalali\Jalalian;
use App\Models\Admin\Log;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        LogService::createLog(
            'بازدید صفحه گزارشات',
            'کاربر ' . Auth()->user()->username . ' صفحه گزارشات را بازدید کرد',
            Log::class,
        );
        
        return view('admin.logs.index');
    }
    public function getLogsDataTable(){

        $logs = Log::with('user')->select(['id','ip_address','model_type','user_id','model_id','action','description','created_at'])->orderBy('id','desc');
        return DataTables::of($logs)
        ->addColumn('user_id',function($user){
            if($user->user){
                return $user->user->firstname.' '.$user->user->lastname.' ('.$user->user->username.')';
            }else{
                return '-';
            }
        })
        ->addColumn('model_type',function($model){
            $module = '';
            if($model->model_type == 'App\Models\Admin\Log'){
                $module = '<span class="badge bg-info">ماژول گزارشات</span>';
            }elseif($model->model_type == 'App\Models\Admin\User\User'){
                $module = '<span class="badge bg-info">ماژول کاربران</span>';
            }elseif($model->model_type == 'App\Models\Admin\Article'){
                $module = '<span class="badge bg-info"> ماژول مطالب </span>';
            }elseif($model->model_type == 'danger login'){
                $module = '<span class="badge bg-danger">لاگین خطرناک</span>';
            }elseif($model->model_type == 'App\Models\Admin\SocialPage'){
                $module = '<span class="badge bg-info"> ماژول صفحات اجتماعی</span>';
            }elseif($model->model_type == 'App\Models\Admin\ContactInfo'){
                $module = '<span class="badge bg-info"> ماژول تماس با ما </span>';
            }
            return $module;
        })
        ->addColumn('model_id',function($model){
            if($model->model_id == null){
                return '-';
            }else{
                return $model->model_id;
            }
        })
        ->addColumn('created_at', function($log){
            return Jalalian::fromDateTime($log->created_at)->format('Y/m/d').'<br/>'.Jalalian::fromDateTime($log->created_at)->format('H:i:s');
        })
        ->rawColumns(['created_at','model_type']) 
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
