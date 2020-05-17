<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('front.layouts.head')
        <script type="text/javascript">
                    @auth
                      var pub_globes = {
                        'user' : '{{ auth()->user()->name }}',
                        'account' : '{{  Session::get('account_id') }}',
                      };
                    @else
                      var pub_globes = {};
                    @endauth
        </script>
    </head>
    <body class="{{ empty($page) ? 'orphan' : $page }}">
        <main role="main" class="flex-grow-1 d-flex">
            <div class="main-container container-fluid flex-grow-1 d-flex">
                <div class="row position-relative flex-fill">
                    <div class="main-wrapper col-12">
                      <div class="main-wrapper-radial">
                        <div class="main-content">
                          @yield('content')
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </main>
        <script type="text/javascript" src="{!! asset('assets/js/front_libs.js') !!}"></script>
        @stack('scripts_foot')
        @if(Gate::allows('full-user'))
          <script type="text/javascript" src="{!! asset('assets/js/front_user.js') !!}"></script>
        @elseif(Gate::allows('prelim-user'))
          <script type="text/javascript" src="{!! asset('assets/js/front_median.js') !!}"></script>
        @else
          <script type="text/javascript" src="{!! asset('assets/js/front_main.js') !!}"></script>
        @endif
    </body>
</html>
