@extends('admin.layouts.master')


@section('page-title') درج مشخصات جدید @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> فروشگاه </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shop.attribute.index') }}"> مشخصات دسته بندی </a></li>
        <li class="breadcrumb-item active"> درج مشخصات جدید </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.shop.attribute.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="name"> عنوان مشخصه <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2" value="{{old('name')}}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="categories"> دسته بندی </label>
                            <select name="categories[]" id="categories" class="form-control mt-2" multiple="multiple" autocomplete="off">
                                @foreach($productCategories as $productCategory)
                                    <option value="{{ $productCategory->id }}"> {{ $productCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <label for="title"> نوع <span class="text-danger"> * </span></label>
                            <select id="type" class="form-control mt-2 form-select" name="type" autocomplete="off">
                                <option value="single"> تک انتخابی </option>
                                <option value="multiple"> چند انتخابی </option>
                                <option value="text"> متنی </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <div class="mb-3" id="options-container" style="display: none;">
                                <label class="form-label">گزینه‌ها</label>
                                <div id="options">
                                    
                                </div>
                                <button type="button" id="add-option" class="btn btn-primary">افزودن گزینه</button>
                            </div>
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
    $('#categories').select2({
            placeholder: 'انتخاب دسته‌بندی‌ها',
            allowClear: true
        });
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const optionsContainer = document.getElementById('options-container');
        const addOptionBtn = document.getElementById('add-option');
        const optionsDiv = document.getElementById('options');

        // نمایش یا مخفی کردن بخش گزینه‌ها بر اساس نوع ویژگی
        function toggleOptionsVisibility() {
            if (typeSelect.value === 'single' || typeSelect.value === 'multiple') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
        }

        // رویداد تغییر نوع ویژگی
        typeSelect.addEventListener('change', toggleOptionsVisibility);

        // افزودن گزینه جدید
        addOptionBtn.addEventListener('click', function () {
            const optionGroup = document.createElement('div');
            optionGroup.classList.add('input-group', 'mb-2');
            optionGroup.innerHTML = `
                <input type="text" name="options[]" class="form-control" placeholder="گزینه جدید" required>
                <button type="button" class="btn btn-danger remove-option">حذف</button>
            `;
            optionsDiv.appendChild(optionGroup);
        });

        // حذف گزینه
        optionsDiv.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-option')) {
                e.target.parentElement.remove();
            }
        });

        // نمایش اولیه گزینه‌ها در صورت نیاز
        toggleOptionsVisibility();
    });
</script>
@endsection

