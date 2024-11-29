@extends('admin.layouts.master')


@section('page-title') درج محصول جدید @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> فروشگاه </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shop.product.index') }}"> مدیریت محصولات </a></li>
        <li class="breadcrumb-item active"> درج محصول جدید </li> 
    </ol>
</nav>
@endsection

@section('content')
<style>
#attributes-container > div{
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.shop.product.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-5 mt-2">
                        <div class="form-group">
                            <label for="name"> عنوان محصول <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2" value="{{old('name')}}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="sku"> شماره محصول </label>
                            <input type="text" name="sku" id="sku" class="form-control mt-2 avash-direction-ltr" value="{{old('sku')}}">
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="form-group">
                            <label for="price"> قیمت اصلی  <span class="text-danger"> * </span></label>
                            <input type="text" name="price" id="price" class="form-control mt-2 avash-direction-ltr" value="{{old('price')}}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="categories"> دسته بندی محصول </label>
                            <select name="category_ids[]" id="categories" class="form-control mt-2" multiple="multiple" autocomplete="off">
                                @foreach($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}"> {{ $productCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <label for="short_description" class="mb-3"> توضیحات کوتاه محصول </label>
                            <textarea class="form-control mt-2 tinymce-editor" name="short_description" id="tinymce-editor" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <label for="description" class="mb-3"> توضیحات تکمیلی محصول </label>
                            <textarea class="form-control mt-2 tinymce-editor" name="description" id="tinymce-editor" rows="5"></textarea>
                        </div>
                    </div>
                    <div id="attributes-container" class="mt-4">
                        <!-- ویژگی‌های مربوط به دسته‌بندی‌ها در اینجا نمایش داده می‌شوند -->
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="published"> وضعیت نمایش <span class="text-danger"> * </span></label>
                            <select id="published" class="form-control mt-2 form-select" name="published" autocomplete="off">
                                <option value="1"> منتشر شده </option>
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
    $(document).ready(function () {
    $('#categories').select2({
        placeholder: 'انتخاب دسته‌بندی‌ها',
        allowClear: true,
        multiple: true
    });

    $('#categories').change(function () {
        const categoryIds = $(this).val();

        if (categoryIds && categoryIds.length > 0) {
            $.ajax({
                url: '{{ route('admin.shop.product.get-attributes') }}',
                method: 'POST',
                data: {
                    category_ids: categoryIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#attributes-container').empty();

                    response.forEach(attribute => {
                        let inputField = '';

                        if (attribute.type === 'text') {
                            // فیلد متنی
                            inputField = `
                                <input type="text" name="attributes[${attribute.id}]" class="form-control mt-3">
                            `;
                        } else if (attribute.type === 'single') {
                            // تک انتخابی (select)
                            inputField = `
                                <select name="attributes[${attribute.id}]" class="form-control single-select mt-3">
                                    <option value="" disabled selected>انتخاب کنید</option>
                                    ${attribute.options.map(option => `
                                        <option value="${option.id}">${option.value}</option> <!-- استفاده از option.id -->
                                    `).join('')}
                                </select>
                            `;
                        } else if (attribute.type === 'multiple') {
                            // چند انتخابی (select با multiple)
                            inputField = `
                                <select name="attributes[${attribute.id}][]" class="form-control multiple-select mt-3" multiple>
                                    ${attribute.options.map(option => `
                                        <option value="${option.id}">${option.value}</option> <!-- استفاده از option.id -->
                                    `).join('')}
                                </select>
                            `;
                        }

                        // افزودن به کانتینر
                        $('#attributes-container').append(`
                            <div class="form-group">
                                <label>${attribute.name}</label>
                                ${inputField}
                            </div>
                        `);
                    });

                    // تبدیل فیلدهای select به Select2
                    $('.single-select').select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true
                    });

                    $('.multiple-select').select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true
                    });
                },
                error: function () {
                    alert('خطا در دریافت ویژگی‌ها');
                }
            });
        } else {
            $('#attributes-container').empty();
        }
    });
});

    
    
</script>
@endsection

