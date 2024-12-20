<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
      <i class="bi bi-grid"></i>
      <span>داشبورد</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs(['admin.contact-info','admin.social-pages','admin.articles.*', 'admin.article.categories.*','admin.article.categories']) ? '' : 'collapsed' }} " data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-menu-button-wide"></i><span> مدیریت محتوا </span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content collapse {{ request()->routeIs(['admin.contact-info','admin.social-pages','admin.articles.*','admin.articles','admin.article.categories.*','admin.article.categories']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
      <li>
        <a href="components-alerts.html">
          <i class="bi bi-circle"></i><span> صفحات </span>
        </a>
      </li>
      <li>
        <a href="components-accordion.html">
          <i class="bi bi-circle"></i><span> منوها </span>
        </a>
      </li>
      @can('article.view')
      <li >
        <a class="{{ request()->routeIs(['admin.articles.*','admin.articles','admin.article.categories.*','admin.article.categories']) ? 'active' : '' }}" href="{{ route('admin.articles') }}">
          <i class="bi bi-circle"></i><span>مطالب</span>
        </a>
      </li>
      @endcan
      <li>
        <a class="{{ request()->routeIs(['admin.social-pages']) ? 'active' : '' }}" href="{{ route('admin.social-pages') }}">
          <i class="bi bi-circle"></i><span>صفحات اجتماعی</span>
        </a>
      </li>
      <li>
        <a class="{{ request()->routeIs(['admin.contact-info']) ? 'active' : '' }}" href="{{ route('admin.contact-info') }}">
          <i class="bi bi-circle"></i><span> اطلاعات تماس با ما </span>
        </a>
      </li>
      
    </ul>
  </li><!-- End Components Nav -->

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs(['admin.shop.*','admin.shop']) ? '' : 'collapsed' }}" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-journal-text"></i><span>فروشگاه</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="forms-nav" class="nav-content collapse {{ request()->routeIs(['admin.shop.*','admin.shop']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
      <li>
        <a class="{{ request()->routeIs(['admin.shop.product.*']) ? 'active' : '' }}" href="{{ route('admin.shop.product.index') }}">
          <i class="bi bi-circle"></i><span>مدیریت محصولات</span>
        </a>
      </li>
      <li>
        <a class="{{ request()->routeIs(['admin.shop.category.*']) ? 'active' : '' }}" href="{{ route('admin.shop.category.index') }}">
          <i class="bi bi-circle"></i><span> دسته بندی محصولات </span>
        </a>
      </li>
      <li>
        <a href="forms-editors.html">
          <i class="bi bi-circle"></i><span> پیشنهاد شگفت انگیز </span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span>سفارشات</span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span> کوپن تخفیف </span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span> شیوه ارسال </span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span> گارانتی </span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span>برندها</span>
        </a>
      </li>
      <li>
        <a class="{{ request()->routeIs(['admin.shop.attribute.*']) ? 'active' : '' }}" href="{{ route('admin.shop.attribute.index') }}">
          <i class="bi bi-circle"></i><span>مشخصات دسته بندی</span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span>درگاه پرداخت</span>
        </a>
      </li>
      <li>
        <a href="forms-validation.html">
          <i class="bi bi-circle"></i><span>سرویس پیامکی</span>
        </a>
      </li>
    </ul>
  </li><!-- End Forms Nav -->


  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs(['admin.users.*','admin.users']) ? '' : 'collapsed' }}" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-gem"></i><span>کاربران</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="icons-nav" class="nav-content collapse {{ request()->routeIs(['admin.users.*','admin.users']) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
      <li>
        <a class="{{ request()->routeIs(['admin.users.*','admin.users']) ? 'active' : '' }}" href="{{ route('admin.users') }}">
          <i class="bi bi-circle"></i><span>کاربران</span>
        </a>
      </li>
      <li>
        <a href="icons-boxicons.html">
          <i class="bi bi-circle"></i><span>اعضای سرویس خبری</span>
        </a>
      </li>
    </ul>
  </li><!-- End Icons Nav -->

  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.logs') ? '' : 'collapsed' }}" href="{{ route('admin.logs') }}">
      <i class="bi bi-person"></i>
      <span>گزارشات</span>
    </a>
  </li><!-- End Profile Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-faq.html">
      <i class="bi bi-question-circle"></i>
      <span> فرم ها </span>
    </a>
  </li><!-- End F.A.Q Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-login.html">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>سطوح دسترسی</span>
    </a>
  </li><!-- End Login Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-error-404.html">
      <i class="bi bi-dash-circle"></i>
      <span> تنظیمات </span>
    </a>
  </li><!-- End Error 404 Page Nav -->

  <li class="nav-item">
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="nav-link collapsed" style="background: none; border: none; padding: 10px 15px; width: 100%;">
            <i class="bi bi-file-earmark"></i>
            <span>خروج</span>
        </button>
    </form>
</li><!-- End Blank Page Nav -->

</ul>

</aside>