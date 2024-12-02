<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> پنل مدیریت فروشگاه | <?php echo $__env->yieldContent('page-title'); ?> </title>

  <meta content="" name="description">
  <meta content="" name="keywords">

  
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <?php echo $__env->make('admin.layouts.head-tag', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('head-tag'); ?>

</head>

<body>

  <!-- ======= Header ======= -->
  <?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1> <?php echo $__env->yieldContent('page-title'); ?> </h1>
      <?php echo $__env->yieldContent('breadcrumb'); ?>
    </div><!-- End Page Title -->

    <?php echo $__env->yieldContent('content'); ?>
    

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  
   
  <?php echo $__env->make('admin.layouts.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->yieldContent('script-tag'); ?>

</body>

</html><?php /**PATH C:\xampp\htdocs\panel\cms\resources\views/admin/layouts/master.blade.php ENDPATH**/ ?>