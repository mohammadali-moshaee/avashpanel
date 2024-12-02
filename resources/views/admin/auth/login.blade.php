<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> ورود به پنل مدیریت | {{ env('APP_NAME') }} </title>

  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('admin-assets/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('admin-assets/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('admin-assets/assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="{{ asset('admin-assets/assets/img/logo.png') }}" alt="">
                  <span class="d-none d-lg-block">MoshaCMS</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4"> ورود به پنل مدیریت </h5>
                    <p class="text-center small">نام کاربری و کلمه عبور خود را جهت وارد به پنل مدیریت وارد کنید</p>
                  </div>
                    <!-- نمایش خطاهای عمومی -->
                    @if ($errors->any())
                        <div class="error">
                            <ul class="p-2 m-0">
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                  <form class="row g-3" method="POST" action="{{ route('login.submit') }}" >
                    @csrf
                    <div class="col-12">
                      <label for="username" class="form-label">نام کاربری</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control avash-direction-ltr" id="username" required>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="password" class="form-label">کلمه عبور</label>
                      <input type="password" name="password" class="form-control avash-direction-ltr" id="password" required>
                    </div>
                    
                    <div class="col-12">
                      <label class="mb-3"> متن امنیتی </label>
                      <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>    
                      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">ورود</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->



</body>

</html>