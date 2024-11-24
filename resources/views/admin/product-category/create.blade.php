@extends('admin.layouts.master')


@section('page-title') درج دسته بندی محصول  @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">فروشگاه</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shop.category.index') }}"> دسته بندی محصولات </a></li>
        <li class="breadcrumb-item active"> درج دسته بندی محصول  </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.shop.category.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <label for="name"> عنوان دسته بندی <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2" value="{{old('name')}}" required>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <label for="parent_id"> دسته بندی والد<span class="text-danger"> * </span></label>
                            <select name="parent_id" class="form-control" id="parent_id">
                                <option value="">بدون والد</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" >{{ $cat->name }}</option>
                                @endforeach
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
