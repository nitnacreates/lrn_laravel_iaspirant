@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Sign Up</h1>
<div class="container">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">First Name<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" pattern="[a-z]{2,20}" autofocus>
                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="last_name">Last Name<sup class="text-danger">*</sup></label>
                <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" pattern="[a-z]{2,20}" autofocus>
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="name">User Name<sup class="text-danger">*</sup></label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus pattern="[a-z0-9\.]{5,20}" minlength="6" placeholder="Name that will be visible">
            <small id="name" class="form-text text-muted">Only small alpha, period, and numerals allowed</small>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">E-Mail Address<sup class="text-danger">*</sup></label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E Mail">
            <small id="email" class="form-text text-muted">Only gmail, yahoo, rediff, zoho, outlook allowed</small>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password">Password<sup class="text-danger">*</sup></label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" minlength="6" autofocus placeholder="Password" >            
                <small id="name" class="form-text text-muted">Minimum 6 char</small>
            </div>
            <div class="form-group col-md-6">
                <label for="password_confirmation">Confirm Password<sup class="text-danger">*</sup></label>
                <input id="password_confirmation" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation"  required autocomplete="off" minlength="6" autofocus placeholder="Re-type">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form> 
</div>
@endsection
