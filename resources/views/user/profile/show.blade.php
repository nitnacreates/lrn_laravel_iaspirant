@extends('front.layouts.skeleton')

@push('col_left_top')
    @include('user.partials.left_col')
@endpush

@section('content')
    <h3>User profile</h3>
    <form>
      <legend>Profile</legend>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Display Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="staticEmail" value="">
        </div>
      </div>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">First Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="staticEmail" value="">
        </div>
      </div>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Last Name</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="staticEmail" value="">
        </div>
      </div>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Mobile</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="staticEmail" value="">
        </div>
      </div>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Gender</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
              <label class="form-check-label" for="inlineRadio1">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
              <label class="form-check-label" for="inlineRadio2">Female</label>
            </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="staticEmail" value="">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Save Profile</button>
      <hr />
    </form>
    <form>
      <legend>Update Password</legend>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Current Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Retype New Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Update Password</button>
      <hr />
    </form>
    <legend>Subscriptions</legend>
        <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Current Subscription</label>
        <div class="col-sm-10">
          <span>Prelim Plus</span>
        </div>
    </div>
@stop
