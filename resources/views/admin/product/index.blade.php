@extends('admin.layouts.master')


@section('page-title') مدیریت محصولات @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">فروشگاه</a></li>
        <li class="breadcrumb-item active"> مشخصات دسته بندی </li> 
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        @can('article.create')
                        <a href="{{ route('admin.shop.product.create') }}" class="btn btn-success btn-sm">
                        درج محصول جدید 
                        <i class="bi bi-plus"></i>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
              <table class="table datatable-ajax">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th> دسته بندی </th>
                    <th> گزینه ها </th>
                    <th> تاریخ ایجاد </th>
                    <th> عملیات </th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>



@endsection

@section('script-tag')

<script>
$(document).ready(function() {
    $('.datatable-ajax').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.shop.product.dataTable') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'categories', name: 'categories' },
            { data: 'id', name: 'id' },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            }
        ],
        language: {
            url: "/admin-assets/assets/js/DataTablePersian.json"
        }
    });
});

</script>

@endsection
