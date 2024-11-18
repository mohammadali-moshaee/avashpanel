@extends('admin.layouts.master')


@section('page-title') مقالات @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item active"> مقالات </li> 
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
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-success btn-sm">
                        درج مقاله جدید 
                        <i class="bi bi-plus"></i>
                        </a>
                        @endcan
                    </div>
                    <div>
                    @can('articleCategory.view')
                        <a href="{{ route('admin.article.categories') }}" class="btn btn-primary btn-sm"> 
                            دسته بندی مقالات 
                            <i class="bi bi-folder"></i>
                        </a>
                    @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table datatable-ajax">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th>دسته بندی</th>
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
        ajax: "{{ route('admin.articles.dataTable') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'categories', name: 'categories' },
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
