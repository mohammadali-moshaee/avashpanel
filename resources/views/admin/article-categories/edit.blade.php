@extends('admin.layouts.master')


@section('page-title') ویرایش دسته بندی - {{ $articleCategory->name }} @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> مقالات </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.article.categories') }}"> دسته بندی مقالات </a></li>
        <li class="breadcrumb-item active"> ویرایش دسته بندی - {{ $articleCategory->name }} </li> 
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.article.categories.update',$articleCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
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
                            <input value="{{ $articleCategory->name }}" type="text" name="name" id="name" class="form-control mt-2" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="parent_id"> دسته بندی والد <span class="text-danger"> * </span></label>
                            <select name="parent_id" id="parent_id" class="form-control mt-2" autocomplete="off" required>
                                <option value="0" selected> بدون والد </option>
                                @foreach($articleCategories as $name => $id)
                                <option value="{{ $id }}" @if($id == $articleCategory->parent_id) selected @endif> {{ $name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="published"> وضعیت انتشار <span class="text-danger"> * </span></label>
                            <select name="published" id="published" class="form-control mt-2" required autocomplete="off">
                                <option value="1" @if($articleCategory->published == 1 ) selected @endif> انتشار </option>
                                <option value="0" @if($articleCategory->published == 0 ) selected @endif> عدم انتشار </option>
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
