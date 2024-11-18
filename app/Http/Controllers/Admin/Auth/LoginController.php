<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Models\Admin\User\User;
use Illuminate\Support\Facades\Hash;
use App\LogService;

class LoginController extends Controller
{
    public function showLoginForm()
    {
  
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login')->with('success', 'Logged out successfully!');
    }

    public function login(LoginRequest $request)
    {
        $inputs = $request->all();

        $user = User::where('username', $inputs['username'])
                    ->where('status', 1)
                    ->whereHas('groups', function($query) {
                        $query->where('name', 'ادمین'); 
                    })
                    ->first();

        if ($user && Hash::check($inputs['password'], $user->password)) {
            Auth::login($user);
            LogService::createLog(
                'ورود',
                'کاربر ' . $user->username . ' وارد پنل شد.',
                $user
            );
            return redirect()->intended('admin/dashboard')->with('success', 'Login successful!');
        }
        LogService::createLog(
            'ورود ناموفق',
            'کاربر ' . $request->username . ' تلاش جهت ورود به پنل !',
            $user
        );
        return back()->withErrors(['email' => 'نام کاربری یا کلمه عبور اشتباه می باشد !']);
    }

}
