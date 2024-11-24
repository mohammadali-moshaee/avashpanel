<?php

namespace App\Http\Controllers\Admin\SocialPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\SocialPage;
use App\LogService;

class SocialPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        LogService::createLog(
            'بازدید صفحه صفحات اجتماعی',
            'کاربر ' . Auth()->user()->username . ' صفحه صفحات اجتماعی را بازدید کرد',
            SocialPage::class,
        );

        $socialPage = SocialPage::findOrFail(1);
        
        return view('admin.social-pages.index',compact('socialPage'));
    }


    public function update(Request $request, SocialPage $socialPage)
    {
        $inputs = $request->all();
        
        $socialPage->update($inputs);
        return redirect()->back()->with('success', 'صفحات اجتماعی شما با موفقیت ویرایش شد');

    }

}
