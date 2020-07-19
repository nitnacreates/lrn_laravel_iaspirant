@extends('front.layouts.skeleton')
@push('col_left_top')
@include('user.partials.left_col')
@endpush
@section('content')
<h1 class="page-title">User profile</h1>
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <form role="form" id="stash_form" method="post" action="{{ route('user::profile', ['action' => 'mutate-profile']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <legend>Profile</legend>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Display Name</label>
                <div class="col-sm-8">
                    <span>{{ $user->name }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <span>{{ $user->email }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">First Name<sup class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" name="first_name" class="form-control" value="{{$user_keep->first_name}}" required pattern="[a-zA-z]{2,20}" autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Last Name<sup class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" name="last_name" class="form-control" value="{{$user_keep->last_name}}" required pattern="[a-zA-z]{2,20}" autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Mobile<sup class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" name="phone" class="form-control" value="{{$user_keep->phone}}" minlength="10" required>
                    <small id="phone" class="form-text text-muted">10 digits</small>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Gender<sup class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="m" @if($user_keep->gender == 'm') checked @endif>
                        <label class="form-check-label" for="inlineRadio1">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="f" @if($user_keep->gender == 'f') checked @endif>
                        <label class="form-check-label" for="inlineRadio2">Female</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Date of Birth<sup class="text-danger">*</sup></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="dob" placeholder="YYYY-MM-DD" value="{{$user_keep->dob}}" required>
                    <small id="phone" class="form-text text-muted">YYYY-MM-DD</small>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Highest Qualification<sup class="text-danger">*</sup></label>
                <div class="col-sm-4">
                    <select class="form-control" name="qualification_type" autocomplete="off">
                        @foreach(config('aspirant.user.qualification_type') as $key => $val)
                            <option value="{{ $key }}" @if($key == $user_keep->qualification_type)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="qualification" placeholder="Qualification name" value="{{$user_keep->qualification}}">
                </div>
            </div>
            <hr />
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Previous Attempts</label>
                <div class="col-sm-8">
                   <select class="form-control" name="total_attempts" autocomplete="off">
                        @for($i = 0; $i<=7; $i++)
                            <option value="{{ $i }}" @if($i == $user_keep->total_attempts)) selected="selected" @endif>{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Highest stage reached</label>
                <div class="col-sm-8">
                   <select class="form-control" name="max_stage" autocomplete="off">
                        @foreach(config('aspirant.user.upsc_stage') as $key => $val)
                            <option value="{{ $key }}" @if($key == $user_keep->max_stage)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Profile</button>
            <hr />
        </form>
        <form role="form" id="stash_form" method="post" action="{{ route('user::profile', ['action' => 'mutate-password']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <legend>Update Password</legend>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Current Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="old_password" placeholder="Current Password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">New Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="password" placeholder="New Password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Retype New Password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
    <div class="col-2"></div>
</div>
@stop
