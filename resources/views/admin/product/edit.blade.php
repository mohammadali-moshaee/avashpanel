@extends('admin.layouts.master')


@section('page-title') ویرایش محصول | {{ $product->name }} @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"> فروشگاه </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shop.product.index') }}"> مدیریت محصولات </a></li>
        <li class="breadcrumb-item active"> ویرایش محصول | {{ $product->name }} </li> 
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
              <form class="form-submit" action="{{ route('admin.shop.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-5 mt-2">
                        <div class="form-group">
                            <label for="name"> عنوان محصول <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2" value="{{old('name',$product->name)}}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="sku"> شماره محصول </label>
                            <input type="text" name="sku" id="sku" class="form-control mt-2 avash-direction-ltr" value="{{old('sku',$product->sku)}}">
                        </div>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="form-group">
                            <label for="price"> قیمت اصلی  <span class="text-danger"> * </span></label>
                            <input type="text" name="price" id="price" class="form-control mt-2 avash-direction-ltr" value="{{old('price',$product->price)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="categories"> دسته بندی محصول </label>
                            <select name="category_ids[]" id="categories" class="form-control mt-2" multiple="multiple" autocomplete="off">
                                @foreach($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" @if(in_array($productCategory->id, $productCategoriesSelected)) selected @endif> {{ $productCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <label for="short_description" class="mb-3"> توضیحات کوتاه محصول </label>
                            <textarea autocomplete="off" class="form-control mt-2 tinymce-editor" name="short_description" id="tinymce-editor" rows="5">{{ $product->short_description }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <label for="description" class="mb-3"> توضیحات تکمیلی محصول </label>
                            <textarea  autocomplete="off" class="form-control mt-2 tinymce-editor" name="description" id="tinymce-editor" rows="5">{{ $product->description }}</textarea>
                        </div>
                    </div>
                    <div id="attributes-container" class="mt-4">
                        <!-- ویژگی‌های مربوط به دسته‌بندی‌ها در اینجا نمایش داده می‌شوند -->
                        @foreach ($attributes as $attribute)
                        <div class="form-group">
                            <label>{{ $attribute->name }}</label>
                            
                            @php
                            if ($attribute->type === 'multiple') {
                                $values = $attribute->productValues->pluck('value')->toArray(); // آرایه‌ای از مقادیر
                            } else {
                                $value = $attribute->productValues->first()->value ?? null; // فقط یک مقدار
                            }
                            @endphp

                            @if($attribute->type === 'text')
                                <!-- فیلد متنی -->
                                <input type="text" name="attributes[{{ $attribute->id }}]" 
                                    value="{{ old('attributes.' . $attribute->id, $value) }}" 
                                    class="form-control mt-3">
                            @elseif($attribute->type === 'single')
                                <!-- تک انتخابی -->
                                <select name="attributes[{{ $attribute->id }}]" class="form-control mt-3" autocomplete="off">
                                    <option value="" disabled>انتخاب کنید</option>
                                    @foreach ($attribute->options as $option)
                                        <option value="{{ $option->id }}" 
                                                @if($option->id == $value) selected @endif>
                                            {{ $option->value }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($attribute->type === 'multiple')
                                <!-- چند انتخابی -->
                                <select autocomplete="off" name="attributes[{{ $attribute->id }}][]" multiple class="form-control multiple-select mt-3">
                                    @foreach ($attribute->options as $option)
                                        <option value="{{ $option->id }}" 
                                                @if(is_array($values) && in_array($option->id, $values)) selected @endif>
                                            {{ $option->value }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach

                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-group">
                            <label for="published"> وضعیت نمایش <span class="text-danger"> * </span></label>
                            <select id="published" class="form-control mt-2 form-select" name="published" autocomplete="off">
                                <option value="1" @if($product->published == 1) selected @endif> منتشر شده </option>
                                <option value="0" @if($product->published == 0) selected @endif> عدم انتشار </option>
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
    $('.multiple-select').select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true
                    });

                    $('#categories').change(function () {
    const categoryIds = $(this).val();

    if (categoryIds && categoryIds.length > 0) {
        // دریافت مقادیر فعلی ویژگی‌ها
        const currentAttributes = {};
        $('#attributes-container .form-group').each(function () {
            const attributeId = $(this).find('[name^="attributes"]').attr('name').match(/\d+/)[0];
            if ($(this).find('select[multiple]').length > 0) {
                // چند انتخابی
                currentAttributes[attributeId] = $(this).find('select').val();
            } else if ($(this).find('select').length > 0) {
                // تک انتخابی
                currentAttributes[attributeId] = $(this).find('select').val();
            } else if ($(this).find('input').length > 0) {
                // متنی
                currentAttributes[attributeId] = $(this).find('input').val();
            }
        });

        $.ajax({
            url: '{{ route('admin.shop.product.get-attributes') }}',
            method: 'POST',
            data: {
                category_ids: categoryIds,
                _token: '{{ csrf_token() }}',
                current_attributes: currentAttributes // ارسال مقادیر فعلی به سرور
            },
            success: function (response) {
                $('#attributes-container').empty();

                response.forEach(attribute => {
                    let inputField = '';
                    const currentValue = currentAttributes[attribute.id] || null;

                    if (attribute.type === 'text') {
                        inputField = `
                            <input type="text" name="attributes[${attribute.id}]" 
                                class="form-control mt-3" value="${currentValue || ''}">
                        `;
                    } else if (attribute.type === 'single') {
                        inputField = `
                            <select name="attributes[${attribute.id}]" class="form-control single-select mt-3">
                                <option value="" disabled>انتخاب کنید</option>
                                ${attribute.options.map(option => `
                                    <option value="${option.id}" ${currentValue == option.id ? 'selected' : ''}>${option.value}</option>
                                `).join('')}
                            </select>
                        `;
                    } else if (attribute.type === 'multiple') {
                        inputField = `
                            <select name="attributes[${attribute.id}][]" class="form-control multiple-select mt-3" multiple>
                                ${attribute.options.map(option => `
                                    <option value="${option.id}" ${currentValue && currentValue.includes(option.id) ? 'selected' : ''}>${option.value}</option>
                                `).join('')}
                            </select>
                        `;
                    }

                    $('#attributes-container').append(`
                        <div class="form-group">
                            <label>${attribute.name}</label>
                            ${inputField}
                        </div>
                    `);
                });

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

