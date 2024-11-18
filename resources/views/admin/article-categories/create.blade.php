@extends('admin.layouts.master')


@section('page-title') درج دسته بندی مقالات جدید @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> مقالات </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.article.categories') }}"> دسته بندی مقالات </a></li>
        <li class="breadcrumb-item active"> درج دسته بندی مقالات جدید </li> 
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.article.categories.store') }}" method="POST">
                @csrf
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

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name"> عنوان دسته بندی <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="parent_id"> دسته بندی والد <span class="text-danger"> * </span></label>
                            <select name="parent_id" id="parent_id" class="form-control mt-2" autocomplete="off">
                                <option value="0" selected> بدون والد </option>
                                @foreach($articleCategories as $articleCategory)
                                <option value="{{ $articleCategory->id }}"> {{ $articleCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="published"> وضعیت انتشار <span class="text-danger"> * </span></label>
                            <select name="published" id="published" class="form-control mt-2" required autocomplete="off">
                                <option value="1" selected> انتشار </option>
                                <option value="0"> عدم انتشار </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"> ذخیره </button>
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
$(document).ready(function() {
    
});

</script>

@endsection
