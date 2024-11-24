<?php

namespace App\Http\Controllers\Admin\ContactInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ContactInfo;
use App\LogService;

class ContactInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        LogService::createLog(
            'بازدید صفحه اطلاعات تماس باما',
            'کاربر ' . Auth()->user()->username . ' صفحه اطلاعات تماس با ما را بازدید کرد',
            ContactInfo::class,
        );

        $contactInfo = ContactInfo::findOrFail(1);
        
        return view('admin.contact-info.index',compact('contactInfo'));
    }

    public function update(Request $request, ContactInfo $contactInfo)
    {
        
        $inputs = $request->all();
        
        $contactInfo->update($inputs);
        return redirect()->back()->with('success', 'اطلاعات تماس با ما شما با موفقیت ویرایش شد');
    }

}
