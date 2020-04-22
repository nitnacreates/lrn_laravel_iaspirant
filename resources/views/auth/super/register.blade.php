@extends('super.layouts.stripped')
@section('content')
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Sign Up</p>
            <form method="POST" action="{{ route('super::register') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="name" placeholder="User name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fa fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fa fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fa fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
@endsection
