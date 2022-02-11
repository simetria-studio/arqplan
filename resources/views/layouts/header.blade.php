<header class="topbar" data-navbarbg="skin6">

    <nav class="navbar top-navbar navbar-expand-md">
        <div class="navbar-header" data-logobg="skin6">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                <i data-feather="menu" class="feather-icon"></i></a>

            <div class="navbar-brand">
                <!-- Logo icon -->
                <a href="{{ route('home') }}">
                    @if ( Auth::user()->isAdmin() )
                    <img src="{{ asset("images/logo.png") }}" alt="ArqPlann" class="logo dark-logo" />
                    @else
                    <img src="{{route('company.logo')}}" alt="{{ Auth::user()->company->name ?? "ArqPlann" }}" class="logo dark-logo" />
                    @endif
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent2"
                aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
                <i data-feather="user" class="feather-icon"></i>
            </a>
        </div>

        <div class="collapse dropdown-menu dropdown-menu-right user-dd animated flipInY" id="navbarSupportedContent2">
            <a class="dropdown-item" href="{{route('profile')}}"><i data-feather="user" class="svg-icon mr-2 ml-1"></i> Meus dados</a>
            <a class="dropdown-item" href="{{route('logout')}}"><i data-feather="power" class="svg-icon mr-2 ml-1"></i> Logout</a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left ml-3 pl-1">
                <li class="page-breadcrumb">
                    @yield('breadcrumb')
                </li>
            </ul>
            <ul class="navbar-nav m-auto float-center arqplann-logo">
                <a href="http://www.arqplann.com.br" class="logo-text">
                    <img src="{{asset("images/logo.png")}}" alt="{{ Auth::user()->company->name ?? "ArqPlann" }}" class="logo dark-logo" />
                </a>
            </ul>
            <ul class="navbar-nav float-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span><v-gravatar email="{{ Auth::user()->email }}" alt="user" class="rounded-circle" width="40"/></span>
                        <span class="ml-2 d-none d-lg-inline-block">
                            <span class="text-dark">{{ Auth::user()->fullname() }}</span>
                            <i data-feather="chevron-down" class="svg-icon"></i>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <a class="dropdown-item" href="{{route('profile')}}"><i data-feather="user" class="svg-icon mr-2 ml-1"></i> Meus dados</a>
                        <a class="dropdown-item" href="{{route('logout')}}"><i data-feather="power" class="svg-icon mr-2 ml-1"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
