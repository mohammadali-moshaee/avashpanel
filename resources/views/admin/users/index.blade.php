@extends('admin.layouts.master')


@section('page-title') کاربران @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item active"> کاربران </li>
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="p-3 d-flex justify-content-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-2"></i>  ساخت کاربر جدید </a>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام و نام خانوادگی </th>
                                <th> نام کاربری </th>
                                <th> گروه کاربری </th>
                                <th> تاریخ ایجاد </th>
                                <th>فعالیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td> {{ $index + 1 }}  </td>
                                <td> <a href="{{ route('admin.users.edit',$user->id) }}">{{ $user->firstname  .' '. $user->lastname }}</a> </td>
                                <td> {{ $user->username }} </td>
                                <td> 
                                    @foreach($user->groups as $groups)
                                    <span class="badge text-bg-success"> {{ $groups->name }} </span>
                                    @endforeach
                                </td>
                                <td> {{ jdate($user->created_at)->format('Y/m/d') }} </td>
                                <td>
                                    <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> ویرایش</a>
                                    @if($user->username != 'root')
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-trash"></i>
                                            حذف
                                        </button>
                                        <ul class="dropdown-menu">
                                            <form action="{{route('admin.users.delete',$user->id)}}" method="POST">
                                            @csrf
                                            @method('delete')
                                                <button type="submit" class="btn text-danger">بله، حذف شود</button>
                                            </form>
                                            <li><a class="dropdown-item" href="#">خیر، منصرف شدم</a></li>
                                        </ul>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

