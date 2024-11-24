@extends('admin.layouts.master')


@section('page-title') صفحات اجتماعی @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item active"> صفحات اجتماعی </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.social-pages.update',$socialPage->id) }}" method="POST" enctype="multipart/form-data">
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
                            <label for="instagram"> اینستاگرام (Instagram) </label>
                            <input type="text" name="instagram" id="instagram" class="form-control mt-2 avash-direction-ltr" value="{{old('instagram',$socialPage->instagram)}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="telegram"> تلگرام (Telegram)</label>
                            <input type="text" name="telegram" id="telegram" class="form-control mt-2 avash-direction-ltr" value="{{old('telegram',$socialPage->telegram)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="whatsapp"> واتس اَپ (Whatsapp) </label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control mt-2 avash-direction-ltr" value="{{old('whatsapp',$socialPage->whatsapp)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="bale"> بله</label>
                            <input type="text" name="bale" id="bale" class="form-control mt-2 avash-direction-ltr" value="{{old('bale',$socialPage->bale)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="soroush"> سروش </label>
                            <input type="text" name="soroush" id="soroush" class="form-control mt-2 avash-direction-ltr" value="{{old('soroush',$socialPage->soroush)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="eitaa"> ایتا </label>
                            <input type="text" name="eitaa" id="eitaa" class="form-control mt-2 avash-direction-ltr" value="{{old('eitaa',$socialPage->eitaa)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="igap"> آی گپ </label>
                            <input type="text" name="igap" id="igap" class="form-control mt-2 avash-direction-ltr" value="{{old('igap',$socialPage->igap)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="rubika"> روبیکا </label>
                            <input type="text" name="rubika" id="rubika" class="form-control mt-2 avash-direction-ltr" value="{{old('rubika',$socialPage->rubika)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="linkedin"> لینکدین (Linkedin) </label>
                            <input type="text" name="linkedin" id="linkedin" class="form-control mt-2 avash-direction-ltr" value="{{old('linkedin',$socialPage->linkedin)}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="facebook"> فیس بوک (Facebook) </label>
                            <input type="text" name="facebook" id="facebook" class="form-control mt-2 avash-direction-ltr" value="{{old('facebook',$socialPage->facebook)}}">
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
