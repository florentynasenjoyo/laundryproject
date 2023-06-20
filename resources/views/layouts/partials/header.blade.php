<header class="site-navbar" role="banner">
    <div class="site-navbar-top">
      <div class="container">
        <div class="row align-items-center">

          <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
            <form action="{{ route('service.search') }}" method="post" class="site-block-top-search">
                @csrf
              <span class="icon icon-search2"></span>
              <input type="text" name="search" class="form-control border-0" placeholder="Search">
            </form>
          </div>

          <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
            <div class="site-logo">
              <a href="{{ route('home') }}" class="js-logo-clone">Laundry-In</a>
            </div>
          </div>

          <div class="col-6 col-md-4 order-3 order-md-3 text-right">
            <div class="site-top-icons">
              <ul>
                @if (Str::length(Auth::guard('web')->user()) > 0)
                    <li><a href="{{ route('profile.index') }}"><span class="icon icon-person"></span></a></li>
                @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                    <li><a href="{{ route('shopprofile.index') }}"><span class="icon icon-person"></span></a></li>
                @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                    <li><a href="{{ route('driverprofile.index') }}"><span class="icon icon-person"></span></a></li>
                @endif
                @if (Session::get('login')==TRUE || Session::get('login_shop')==TRUE || Session::get('login_driver'))
                    {{-- <li><a href="#"><span class="icon icon-heart-o"></span></a></li> --}}
                    @if (Str::length(Auth::guard('web')->user()) > 0)
                        <li>
                            <a href="{{ route('order.index') }}" class="site-cart">
                                <span class="icon icon-shopping-basket"></span>
                                <span class="count">{{ $orderCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"><span class="icon icon-sign-out"></span></a>
                        </li>
                    @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                        <li>
                            <a href="{{ route('shop.order') }}" class="site-cart">
                                <span class="icon icon-shopping-basket"></span>
                                <span class="count">{{ $orderCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop.logout') }}"><span class="icon icon-sign-out"></span></a>
                        </li>
                    @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                        <li>
                            <a href="{{ route('driver.order') }}" class="site-cart">
                                <span class="icon icon-dropbox"></span>
                                <span class="count">{{ $orderCount }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('driver.logout') }}"><span class="icon icon-sign-out"></span></a>
                        </li>
                    @endif
                @else
                    <li>
                        <a href="{{ route('login') }}"><span class="icon icon-sign-in" title="Member Login/Register"></span></a>
                        <a href="{{ route('shop.login') }}"><span class="icon icon-shopping-cart" title="Shop Login/Register"></span></a>
                        <a href="{{ route('driver.login') }}"><span class="icon icon-drivers-license" title="Driver Login/Register"></span></a>
                    </li>
                @endif
                <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
    <nav class="site-navigation text-right text-md-center" role="navigation">
      <div class="container">
        <ul class="site-menu js-clone-nav d-none d-md-block">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('about') }}">About</a></li>
          <li><a href="{{ route('maps.index') }}">Maps</a></li>
          <li><a href="{{ route('shop.index') }}">Shops</a></li>
          <li><a href="{{ route('services.index') }}">Services</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </div>
    </nav>
  </header>
