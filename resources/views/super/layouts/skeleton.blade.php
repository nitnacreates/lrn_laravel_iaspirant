<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('super.layouts.head')
    </head>
    <script type="text/javascript">
        var myglobes = {};
    </script>
    <body class="hold-transition sidebar-mini {{ $page }}">
        <!-- Site wrapper -->
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
                    </li>
                    @if(Auth::guard('superg')->check())
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="{{ route('super::logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('super::logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                    @endif
                </ul>
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                            <i class="fa fa-th-large"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="brand-link">
                    <img src="{{ asset('assets/imgs/front/logo/logo7.png') }}" style="width: 100%; background-color: #888787;"/>
                </a>
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                            <li class="nav-header">Content</li>
                            <li class="nav-item">
                                <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                    <i class="nav-icon fa fa-tachometer"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super::essays', ['action' => 'show']) }}" class="nav-link">
                                    <i class="nav-icon fa fa-pencil-square-o"></i>
                                    <p>
                                        Essays
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super::news', ['action' => 'show']) }}" class="nav-link">
                                    <i class="nav-icon fa fa-newspaper-o"></i>
                                    <p>
                                        News
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                    <i class="nav-icon fa fa-sticky-note"></i>
                                    <p>
                                        Notes
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a class="nav-link" href="javascript:void(0);">
                                    <i class="nav-icon fa fa-book"></i>
                                    <p>
                                        Syllabus
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php $subject_map = config('aspirant.subject_map'); ?>
                                    @foreach($subject_map as $loop_slug => $sub)
                                    <li class="nav-item">
                                        <a href="{{ route('super::syllabus', ['action' => 'show', 'sub' => $loop_slug]) }}" class="nav-link">
                                            <i class="fa fa-arrow-circle-right nav-icon"></i>
                                            <p>{{ $sub }}</p>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-graduation-cap"></i>
                                    <p>
                                        Theories
                                        <i class="fa fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('super::theory', ['action' => 'show']) }}" class="nav-link">
                                            <i class="fa fa-circle nav-icon"></i>
                                            <p>Content</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('super::theory-summaries', ['action' => 'show']) }}" class="nav-link">
                                            <i class="fa fa-circle nav-icon"></i>
                                            <p>Summaries</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-header">Manage</li>
                            <li class="nav-item has-treeview">
                                <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                    <i class="nav-icon fa fa-envelope"></i>
                                    <p>
                                        Mailbox
                                        <i class="fa fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                            <i class="fa fa-circle nav-icon"></i>
                                            <p>Inbox</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                            <i class="fa fa-circle nav-icon"></i>
                                            <p>Compose</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('super::dashboard', ['action' => 'show']) }}" class="nav-link">
                                            <i class="fa fa-circle nav-icon"></i>
                                            <p>Read</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super::media', ['action' => 'show']) }}" class="nav-link">
                                    <i class="fa fa-picture-o nav-icon"></i>
                                    <p>Media</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @include('super.partials.breadcrumbs')
                @include('super.partials.messages')
                @yield('content')
            </div>
            <!-- /.content-wrapper -->
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <div class="clock">
                    <p>10:20:44 am</p>
                </div>
            </aside>
            <!-- /.control-sidebar -->
        </div>
        {{-- modals --}}
        <div id="frame_modal" class="modal fade"><div class="modal-dialog"></div></div>
        <script type="text/javascript" src="{!! asset('assets/js/super_core_libs.js') !!}"></script>
        @stack('scripts_foot')
        <script type="text/javascript" src="{!! asset('assets/js/super_main.js') !!}"></script>
    </body>
</html>
