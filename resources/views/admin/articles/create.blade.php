@extends('admin.layouts.master')


@section('page-title') درج مقاله جدید @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> مقالات </a></li>
        <li class="breadcrumb-item active"> درج مقاله جدید </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="title"> عنوان مقاله <span class="text-danger"> * </span></label>
                            <input type="text" name="title" id="title" class="form-control mt-2" value="{{old('title')}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="categories" class="mb-2"> دسته بندی والد <span class="text-danger"> * </span></label>
                            <select name="categories[]" id="categories" class="form-control" autocomplete="off" multiple="multiple">
                                @foreach($articleCategories as $articleCategory)
                                <option value="{{ $articleCategory->id }}"> {{ $articleCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label for="lead"> خلاصه مقاله </label>
                            <textarea class="form-control mt-2" name="lead" id="lead" rows="5">{{old('lead')}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <label for="content"> متن کامل مقاله </label>
                            <textarea class="form-control mt-2 tinymce-editor" name="content" id="tinymce-editor" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="pinnedPic"> تصویر شاخص مقاله </label>
                            <input type="file" name="pinnedPic" id="pinnedPic" class="form-control mt-2" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="pictures"> گالری تصاویر مقاله (میتوانید چندین عکس انتخاب کنید)</label>
                            <input type="file" name="pictures[]" id="pictures" class="form-control mt-2" multiple accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <hr/>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="seo_title"> عنوان سئو مقاله </label>
                            <input type="text" name="seo_title" id="seo_title" class="form-control mt-2" value="{{old('seo_title')}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="seo_description"> توضیحات سئو مقاله</label>
                            <input type="text" name="seo_description" id="seo_description" class="form-control mt-2" value="{{old('seo_description')}}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="keywords"> کلمات کلیدی </label>
                            <select name="keywords[]" id="keywords" class="form-control mt-2" multiple="multiple"></select>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
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
<script src="{{ asset('admin-assets/assets/js/select2.min.js') }}"></script>
<script>
    
$(document).ready(function() {
    
        $('#categories').select2({
            placeholder: 'انتخاب دسته‌بندی‌ها',
            allowClear: true
        });


        $('#keywords').select2({
            placeholder: 'کلمات کلیدی را انتخاب کنید',
            tags: true,
            ajax: {
                url: '{{ route("admin.keywords.get") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            };
                        })
                    };
                },
                cache: true
            },
            // استفاده از tokenSeparators برای تایید تگ با کلید enter و comma
            tokenSeparators: [',', ' ']
        });
        $('#keywords').on('select2:close', function() {
            var newTags = $('#keywords').find("option[data-select2-tag='true']");

            newTags.each(function() {
                var tagText = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.keywords.store") }}',
                    data: {
                        name: tagText,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // به‌روزرسانی گزینه با ID واقعی و حذف گزینه موقت
                        $(this).replaceWith(new Option(response.name, response.id, false, true));
                        $('#keywords').trigger('change');
                    }.bind(this)
                });
            });
        });

});

</script>

@endsection
