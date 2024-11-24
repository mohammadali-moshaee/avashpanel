@extends('admin.layouts.master')


@section('page-title') دسته بندی محصولات @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">فروشگاه</a></li>
        <li class="breadcrumb-item active"> دسته بندی محصولات </li> 
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        @can('article.create')
                        <a href="{{ route('admin.shop.category.create') }}" class="btn btn-success btn-sm">
                        درج دسته بندی جدید 
                        <i class="bi bi-plus"></i>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
              <table class="table datatable-ajax">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>نام دسته بندی</th>
                    <th> والد </th>
                    <th> نویسنده </th>
                    <th> تاریخ ایجاد </th>
                    <th> عملیات </th>
                  </tr>
                </thead>
                <tbody>
                @php
    function renderCategoryTree($categories, $parent = null, $level = 0) {
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                echo '<tr>';
                echo '<td>' . $category->id . '</td>';
                echo '<td>' . str_repeat('-- ', $level) . $category->name . '</td>';
                echo '<td>' . ($category->parent ? $category->parent->name : 'بدون والد') . '</td>';
                echo '<td>' . ($category->user ? $category->user->firstname . ' ' . $category->user->lastname : 'نامشخص') . '</td>';
                echo '<td>' . \Morilog\Jalali\Jalalian::fromDateTime($category->created_at)->format('Y/m/d') . '<br/>' . \Morilog\Jalali\Jalalian::fromDateTime($category->created_at)->format('H:i:s') . '</td>';
                
                // بررسی اینکه آیا دسته‌بندی زیرمجموعه دارد یا نه
                $hasChildren = $category->children->isNotEmpty();
                $isCategoryFour = $category->id == 4; // بررسی اینکه ID دسته‌بندی برابر با 4 باشد

                echo '<td>';
                echo '<a class="btn btn-warning" href="' . route('admin.shop.category.edit', $category->id) . '">ویرایش</a>';
                
                if ($isCategoryFour) { 
                    // اگر دسته‌بندی با ID 4 باشد، دکمه حذف را نشان نده
                    echo '<button class="btn btn-sm btn-danger ms-2" disabled>امکان حذف این دسته بندی وجود ندارد</button>';
                } elseif (!$hasChildren) { // اگر دسته‌بندی زیرمجموعه ندارد، دکمه حذف را نمایش بده
                    echo '<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-trash"></i>
                                حذف
                            </button>
                            <ul class="dropdown-menu">
                                <form method="POST" action="' . route('admin.shop.category.delete', $category->id) . '" style="display:inline;">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="btn text-danger">حذف</button>
                                </form>
                                <li><a class="dropdown-item" href="#">خیر، منصرف شدم</a></li>
                            </ul>
                          </div>';
                } else {
                    // اگر دسته‌بندی زیرمجموعه دارد، دکمه حذف را غیرقابل دسترس (disabled) نمایش بده
                    echo '<button class="btn btn-sm btn-danger ms-2" disabled>دسته‌بندی دارای زیرمجموعه است</button>';
                }
                echo '</td>';
                echo '</tr>';

                // فراخوانی بازگشتی برای نمایش زیرمجموعه‌ها
                renderCategoryTree($categories, $category->id, $level + 1);
            }
        }
    }
@endphp

@php
    renderCategoryTree($categories);
@endphp


                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>



@endsection

