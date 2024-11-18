@extends('admin.layouts.master')


@section('page-title') ساخت کاربر جدید @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">کاربران</a></li>
        <li class="breadcrumb-item active"> ساخت کاربر جدید </li>
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="p-3 d-flex justify-content-end">
                <a href="{{ route('admin.users') }}" class="btn btn-warning"> بازگشت به کاربران <i class="bi bi-arrow-left me-2"></i></a>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body py-5">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="p-0 m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="firstname"> نام <span class="text-danger">*</span></label>
                                    <input value="{{ old('firstname') }}" type="text" class="form-control mt-1" id="firstname" name="firstname"  required  />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="lastname"> نام خانوادگی </label>
                                    <input value="{{ old('lastname') }}" type="text" class="form-control mt-1" id="lastname" name="lastname" />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="username"> نام کاربری <span class="text-danger">*</span></label>
                                    <input value="{{ old('username') }}" type="text" class="form-control mt-1 avash-direction-ltr" id="username" name="username"  required />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="groups"> گروه کاربری <span class="text-danger">*</span></label>
                                    <select class="form-control mt-1" id="groups" name="groups" required autocomplete="off">
                                        <option value=""> انتخاب کنید ... </option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group['id'] }}"> {{ $group['name'] }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="mobile"> شماره موبایل </label>
                                    <input value="{{ old('mobile') }}" type="text" class="form-control mt-1 avash-direction-ltr" id="mobile" name="mobile" />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="email"> آدرس ایمیل </label>
                                    <input value="{{ old('email') }}" type="text" class="form-control mt-1 avash-direction-ltr" id="email" name="email" />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="nationalcode"> کدملی </label>
                                    <input value="{{ old('nationalcode') }}" type="text" class="form-control mt-1 avash-direction-ltr" id="nationalcode" name="nationalcode" />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="status"> وضعیت <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control mt-1" id="status">
                                        <option value="1">فعال</option>
                                        <option value="0">غیرفعال</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <hr/>
                            </div>

                            <div class="col-md-12">
                                <div style="display: inline-block; width:30%;">
                                    <label>همه دسترسی‌ها:</label>
                                    <ul id="all_permissions" style="width: 100%; height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 5px;">
                                        @foreach($permissions as $permission)
                                                <li onclick="moveToSelected(this)" data-id="{{ $permission->id }}" style="cursor:pointer; border-bottom: 1px solid #000; padding: 3px 0">
                                                    {{ $permission->persian_title }}
                                                </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div style="display: inline-block; margin: 0 10px; width: 30%;">
                                    <label>دسترسی‌های انتخاب‌شده:</label>
                                    <ul id="selected_permissions" style="width: 100%; height: 150px; overflow-y: auto; border: 1px solid #ccc; padding: 5px;">
                                       
                                    </ul>
                                </div>

                                <input type="hidden" name="permissions" id="permissions_input">
                            </div>

                            <div class="col-md-12 my-2">
                                <hr/>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="password"> کلمه عبور <span class="text-danger">*</span></label>
                                    <input value="{{ old('password') }}" type="password" class="form-control mt-1 avash-direction-ltr" id="password" name="password"  required />
                                </div>
                            </div>
                            <div class="col-md-4 my-2">
                                <div class="form-group">
                                    <label for="password_confirmation"> تکرار کلمه عبور <span class="text-danger">*</span></label>
                                    <input value="{{ old('password_confirmation') }}" type="password" class="form-control mt-1 avash-direction-ltr" id="" name="password_confirmation"  required />
                                </div>
                            </div>

                            
                            <div class="col-md-12 my-3 ">
                                <div class="form-group ">
                                    <button class="btn btn-success px-4 py-2"> <i class="bi bi-save me-2"></i> ذخیره </button>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('script-tag')
<script>
   function moveToSelected(item) {
    const selectedPermissions = document.getElementById('selected_permissions');
    const permissionsInput = document.getElementById('permissions_input');

    // اضافه کردن به لیست انتخاب‌شده
    selectedPermissions.appendChild(item);

    // بروزرسانی hidden input
    updatePermissionsInput();
}

function moveToAll(item) {
    const allPermissions = document.getElementById('all_permissions');

    // انتقال به لیست اصلی
    allPermissions.appendChild(item);

    // بروزرسانی hidden input
    updatePermissionsInput();
}

// اینجا از delegation استفاده می‌کنیم
document.getElementById('all_permissions').addEventListener('click', function(event) {
    // بررسی اینکه آیا آیتم لیست کلیک شده است
    if (event.target && event.target.nodeName === "LI") {
        moveToSelected(event.target);
    }
});

document.getElementById('selected_permissions').addEventListener('click', function(event) {
    // بررسی اینکه آیا آیتم لیست کلیک شده است
    if (event.target && event.target.nodeName === "LI") {
        moveToAll(event.target);
    }
});

document.getElementById('userForm').addEventListener('submit', function(event) {
    
    const selectedPermissions = document.getElementById('selected_permissions');
    const permissionsInput = document.getElementById('permissions_input');

    const selectedPermissionIds = [];
    const items = selectedPermissions.getElementsByTagName('li');

    // گرفتن idهای دسترسی‌ها از لیست انتخاب‌شده
    for (let item of items) {
        selectedPermissionIds.push(item.getAttribute('data-id'));
    }

    // به‌روز کردن hidden field
    permissionsInput.value = selectedPermissionIds.join(',');

    event.currentTarget.submit();
});

</script>
@endsection