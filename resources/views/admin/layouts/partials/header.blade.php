<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin6">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <b class="logo-icon">
                    <!-- Dark Logo icon -->
                    <h4 class="text-header">Administrator</h4>
                </b>
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">

            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav ms-auto d-flex align-items-center">

                <li>
                    <a class="profile-pic" href="#">
                        <img src="{{ url('/images/no-image.jpg') }}" alt="user-img" width="36" class="img-circle">
                        <span class="text-white font-medium">{{ Auth::user()->name }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</header>
