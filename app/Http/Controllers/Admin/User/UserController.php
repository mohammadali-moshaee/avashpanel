<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User\User;
use App\Models\Admin\Group;
use App\Models\Admin\Permission;
use App\Http\Requests\Admin\User\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('groups')->get();

        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::get();
        $permissions = Permission::get();
        return view('admin.users.create',compact('groups','permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $inputs = $request->all();
        $user = User::create($inputs);
        
        if ($request->has('permissions')) {
            $permissions = is_array($request->input('permissions')) 
                ? $request->input('permissions') 
                : explode(',', $request->input('permissions'));

            $permissionsWithModelType = [];
            foreach ($permissions as $permissionId) {
                if (!empty($permissionId) && is_numeric($permissionId)) {
                    $permissionsWithModelType[$permissionId] = ['model_type' => User::class];
                }
            }
        
            $user->permissions()->sync($permissionsWithModelType);
        }

        $user->groups()->attach($request->groups);

        return redirect()->back()->with('success', 'کاربر شما با موفقیت درج شد');
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
    public function edit(User $user)
    {
        $groups = Group::get();
        $userPermissions = $user->permissions()->pluck('permission_id')->toArray();
        $permissions = Permission::get();

        return view('admin.users.edit',compact('user','groups','permissions','userPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $inputs = $request->all();
        
        if ($request->filled('password')) {
            $inputs['password'] = bcrypt($request->password);
        } else {
            unset($inputs['password']);
        }

        if ($request->has('permissions')) {
            $permissions = is_array($request->input('permissions')) 
                ? $request->input('permissions') 
                : explode(',', $request->input('permissions'));

            $permissionsWithModelType = [];
            foreach ($permissions as $permissionId) {
                if (!empty($permissionId) && is_numeric($permissionId)) {
                    $permissionsWithModelType[$permissionId] = ['model_type' => User::class];
                }
            }
        
            $user->permissions()->sync($permissionsWithModelType);
        }
        
        $user->update($inputs);
        $user->groups()->sync($inputs['groups']);

        return redirect()->back()->with('success', 'کاربر شما با موفقیت ویرایش شد.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        
        $user->groups()->detach();
        $user->delete();

        return redirect()->back()->with('success', 'کاربر شما با موفقیت حذف شد.');
    }
}
