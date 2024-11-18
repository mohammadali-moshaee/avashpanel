@extends('admin.layouts.master')


@section('page-title') گزارشات @endsection


@section('breadcrumb') 
<nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
        <li class="breadcrumb-item active"> گزارشات </li> 
    </ol>
</nav>
@endsection

@section('content')
<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table datatable-ajax">
                <thead>
                  <tr>
                    <th>#</th>
                    <th> آیپی </th>
                    <th> ماژول </th>
                    <th> شماره آیتم </th>
                    <th> توضیحات </th>
                    <th> تاریخ ایجاد </th>
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
        ajax: "{{ route('admin.logs.dataTable') }}",
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
