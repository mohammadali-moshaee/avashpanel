@extends('admin.layouts.master')


@section('page-title') اطلاعات تماس باما @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item active"> اطلاعات تماس باما </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.contact-info.update',$contactInfo->id) }}" method="POST" enctype="multipart/form-data">
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

                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="phone1"> شماره تلفن اول  </label>
                            <input type="text" name="phone1" id="phone1" class="form-control mt-2 avash-direction-ltr" value="{{old('phone1',$contactInfo->phone1)}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="phone2"> شماره تلفن دوم  </label>
                            <input type="text" name="phone2" id="phone2" class="form-control mt-2 avash-direction-ltr" value="{{old('phone2',$contactInfo->phone2)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="phone3"> شماره تلفن سوم </label>
                            <input type="text" name="phone3" id="phone3" class="form-control mt-2 avash-direction-ltr" value="{{old('phone3',$contactInfo->phone3)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="mobile1"> شمار موبایل اول </label>
                            <input type="text" name="mobile1" id="mobile1" class="form-control mt-2 avash-direction-ltr" value="{{old('mobile1',$contactInfo->mobile1)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="mobile2"> شماره موبایل دوم </label>
                            <input type="text" name="mobile2" id="mobile2" class="form-control mt-2 avash-direction-ltr" value="{{old('mobile2',$contactInfo->mobile2)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="mobile3"> شماره موبایل سوم </label>
                            <input type="text" name="mobile3" id="mobile3" class="form-control mt-2 avash-direction-ltr" value="{{old('mobile3',$contactInfo->mobile3)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="fax"> فکس </label>
                            <input type="text" name="fax" id="fax" class="form-control mt-2 avash-direction-ltr" value="{{old('fax',$contactInfo->fax)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="address"> آدرس </label>
                            <input type="text" name="address" id="address" class="form-control mt-2 avash-direction-ltr" value="{{old('address',$contactInfo->address)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="location"> لوکیشن (گوگل) </label>
                            <input type="text" name="location" id="location" class="form-control mt-2 avash-direction-ltr" value="{{old('location',$contactInfo->location)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="email"> آدرس ایمیل </label>
                            <input type="text" name="email" id="email" class="form-control mt-2 avash-direction-ltr" value="{{old('email',$contactInfo->email)}}">
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
