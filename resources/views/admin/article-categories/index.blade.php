@extends('admin.layouts.master')


@section('page-title') دسته بندی مقالات @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}"> مقالات </a></li>
        <li class="breadcrumb-item active"> دسته بندی مقالات </li> 
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
                        @can('articleCategory.create')
                        <a href="{{ route('admin.article.categories.create') }}" class="btn btn-success btn-sm">
                        درج دسته بندی مقاله جدید 
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
              @if(session('error'))
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
                    <th>عنوان دسته بندی</th>
                    <th>دسته بندی مادر</th>
                    <th> وضعیت انتشار </th>
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
        ajax: "{{ route('admin.article.categories.dataTable') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'parent', name: 'parent' },
            { data: 'published', name: 'published' },
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
