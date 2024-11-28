@extends('admin.layouts.master')


@section('page-title') ویرایش مشخصات | {{ $attribute->name }} @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> فروشگاه </a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.shop.attribute.index') }}"> مشخصات دسته بندی </a></li>
        <li class="breadcrumb-item active"> ویرایش مشخصات | {{ $attribute->name }} </li> 
    </ol>
</nav>
@endsection

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form class="form-submit" action="{{ route('admin.shop.attribute.update',$attribute->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <label for="name"> عنوان مشخصه <span class="text-danger"> * </span></label>
                            <input type="text" name="name" id="name" class="form-control mt-2" value="{{old('name',$attribute->name)}}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="categories"> دسته بندی </label>
                            <select name="categories[]" id="categories" class="form-control mt-2" multiple="multiple" autocomplete="off">
                                @foreach($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" @if($attribute->categories->contains($productCategory->id)) selected @endif> {{ $productCategory->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <label for="title"> نوع <span class="text-danger"> * </span></label>
                            <select id="type" class="form-control mt-2 form-select" name="type" autocomplete="off">
                                <option value="single" @if($attribute->type == 'single') selected @endif> تک انتخابی </option>
                                <option value="multiple" @if($attribute->type == 'multiple') selected @endif> چند انتخابی </option>
                                <option value="text" @if($attribute->type == 'text') selected @endif> متنی </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 mt-2">
                        <div class="form-group">
                            <div class="mb-3" id="options-container" style="display: none;">
                                <label class="form-label">گزینه‌ها</label>
                                <div id="options">
                                @foreach($attribute->options as $option)
                                    <div class="input-group mb-2">
                                        <input type="hidden" name="options[{{ $loop->index }}][id]" value="{{ $option->id }}">
                                        <input type="text" name="options[{{ $loop->index }}][value]" class="form-control" value="{{ $option->value }}" required>
                                        <button type="button" class="btn btn-danger remove-option">حذف</button>
                                    </div>
                                @endforeach
                                
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
            optionsDiv.innerHTML = ''; // در صورت تغییر به نوع دیگر، گزینه‌ها حذف می‌شوند
        }
    }

    // رویداد تغییر نوع ویژگی
    typeSelect.addEventListener('change', toggleOptionsVisibility);

    // افزودن گزینه جدید
    addOptionBtn.addEventListener('click', function () {
        const existingOptions = optionsDiv.querySelectorAll('.input-group');
        const index = existingOptions.length; // تعداد گزینه‌های فعلی برای تعیین ایندکس جدید
        const optionGroup = document.createElement('div');
        optionGroup.classList.add('input-group', 'mb-2');
        optionGroup.innerHTML = `
            <input type="hidden" name="options[${index}][id]" value="">
            <input type="text" name="options[${index}][value]" class="form-control" placeholder="گزینه جدید" required>
            <button type="button" class="btn btn-danger remove-option">حذف</button>
        `;
        optionsDiv.appendChild(optionGroup);
    });

    // حذف گزینه
    optionsDiv.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.input-group').remove();

            // به‌روزرسانی ایندکس‌های گزینه‌ها
            const remainingOptions = optionsDiv.querySelectorAll('.input-group');
            remainingOptions.forEach((optionGroup, newIndex) => {
                const idInput = optionGroup.querySelector('input[name^="options["][name$="[id]"]');
                const valueInput = optionGroup.querySelector('input[name^="options["][name$="[value]"]');
                idInput.setAttribute('name', `options[${newIndex}][id]`);
                valueInput.setAttribute('name', `options[${newIndex}][value]`);
            });
        }
    });

    // نمایش اولیه گزینه‌ها در صورت نیاز
    toggleOptionsVisibility();
});

</script>
@endsection

