<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('front.layouts.head')
    </head>
    <script type="text/javascript">
        var pub_globes = {};
    </script>
    <body class="{{ empty($page) ? 'orphan' : $page }}">
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-12 logo">
                        <p class="home"><img src="{{ asset('assets/imgs/front/logo/logo7.png') }}" /></p>
                    </div>
                    <div class="col-lg-10 banner-top-container d-none d-lg-block d-xl-block">
                        <div class="banner-top"></div>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button type="button" class="btn sidebar-toggler d-lg-none d-xl-none left-bar-toggle" data-sidebar="left"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                <button class="navbar-toggler btn" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                      <li class="nav-item">
                        <a class="nav-link active" href="#">Essays</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">News Concise</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Prelims 2021</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Syllabus Foci</a>
                      </li>
                    </ul>
                </div>
                <div class="btn-group search-toggler">
                  <button type="button" class="btn dropdown-toggle d-lg-none d-xl-none" id="dropdownSearchButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-search" aria-hidden="true"></i>
                  </button>
                  <div class="search-box dropdown-menu" aria-labelledby="dropdownSearchButton">
                    <div class="input-group md-form form-sm form-2">
                        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <span class="input-group-text lighten-3" id="basic-text1"><i class="fa fa-search text-grey" aria-hidden="true"></i></span>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="profile btn-group">
                  <button class="btn dropdown-toggle" type="button" id="dropdownUserButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user" aria-hidden="true"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownUserButton">
                    @guest
                      <a href="{{ route('login') }}" class="dropdown-item">Login</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item disabled" href="javascript:void(0);">My Account</a>
                      <a class="dropdown-item disabled" href="javascript:void(0);">My Notes</a>                      
                    @endguest
                    @auth
                      <a class="dropdown-item" href="{{ route('user::profile', ['action' => 'show']) }}">My Account</a>
                      <a class="dropdown-item" href="#">My Notes</a>
                      <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    @endauth
                  </div>
                </div>
                <button type="button" class="btn sidebar-toggler d-lg-none d-xl-none right-bar-toggle" data-sidebar="right"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
            </nav>
        </header>

        <main role="main" class="flex-grow-1 d-flex">
            <div class="main-container container-fluid flex-grow-1 d-flex">
                <div class="row position-relative flex-fill">
                    <div class="left-side siderbars col-lg-2 col-xl-2 d-none d-lg-block d-xl-block col-8">
                        @stack('col_left_top')
                        <div class="sidebar-item">
                          <div class="sidebar-item-radial">
                            <div class="side-item-container">
                                <h2 class="title">Schdeule</h2>
                                <p>New batches start from June</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="main-wrapper col-lg-8 col-xl-8 col-12">
                      <div class="main-wrapper-radial">
                        <div class="main-content">
                          @include('front.partials.messages')
                          @yield('content')
                        </div>
                      </div>
                    </div>
                    <div class="right-side siderbars col-lg-2 col-xl-2 d-none d-lg-block d-xl-block col-8">
                        <div class="sidebar-item">
                          <div class="sidebar-item-radial">
                            <div class="side-item-container">
                                <h2 class="title">Prelims</h2>
                                <p>12 months left</p>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="copyright-block col-lg-4 col-xl-4 col-12 text-center text-lg-left text-xl-left">
                        <span class="text-muted">&copy; 2020. IASpirant</span>
                    </div>
                    <div class="footer-links col-lg-8 col-xl-8 col-12 text-center text-lg-right text-xl-right">
                        <ul class="list-inline d-inline-block">
                          <li class="list-inline-item">
                            <a class="nav-link" href="#">About</a>
                          </li>
                          <li class="list-inline-item">
                            <a class="nav-link" href="#">Contact</a>
                          </li>
                        </ul>
                        <div class="btn-group dropup">
                          <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><a href="#">FAQs</a></li>
                                <li class="list-group-item"><a href="#">Terms and Conditions</a></li>
                            </ul>
                          </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </footer>
        <!-- Footer -->
        <script type="text/javascript" src="{!! asset('assets/js/front_libs.js') !!}"></script>
        @stack('scripts_foot')
        <script type="text/javascript" src="{!! asset('assets/js/front_main.js') !!}"></script>
    </body>
</html>
