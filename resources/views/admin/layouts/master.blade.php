<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> پنل مدیریت فروشگاه | @yield('page-title') </title>

  <meta content="" name="description">
  <meta content="" name="keywords">

  
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    @include('admin.layouts.head-tag')
    @yield('head-tag')

</head>

<body>

  <!-- ======= Header ======= -->
  @include('admin.layouts.header')
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  @include('admin.layouts.sidebar')
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1> @yield('page-title') </h1>
      @yield('breadcrumb')
    </div><!-- End Page Title -->

    @yield('content')
    

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  @include('admin.layouts.footer')
  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  
   
  @include('admin.layouts.script')
  @yield('script-tag')

</body>

</html>