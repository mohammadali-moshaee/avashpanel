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

        $logs = Log::select(['id', 'action','description','created_at'])->orderBy('id','desc');
        return DataTables::of($logs)
        ->addColumn('created_at', function($log){
            return Jalalian::fromDateTime($log->created_at)->format('Y/m/d');
        })
        ->rawColumns(['categories', 'action','published']) 
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
