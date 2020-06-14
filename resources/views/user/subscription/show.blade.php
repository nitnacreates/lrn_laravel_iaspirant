@extends('front.layouts.skeleton')
@auth
    @push('col_left_top')
    @include('user.partials.left_col')
    @endpush
@endauth
@section('content')
<h3>User subscription</h3>
@auth
<div class="row">
    <div class="col">
        <legend>Current Subscription</legend>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Your Subscription</label>
            <div class="col-sm-8">
                <span>{{ config('aspirant.user_subscription')[$user->subscription] }}</span>
            </div>
        </div>
    </div>
</div>
@endauth
<div class="row">
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title">Free</h5>
            <ul class="card-text">
                <li>View daily News</li>
                <li>View daily essay quesstions</li>
                <li>Important Notifications</li>
            </ul>
            <div class="border text-center p-1 mb-2">
                Rs 0
            </div>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary float-right">Pick</a>
            @endguest
            @auth
                <a href="{{ route('user::subscription', ['action' => 'show']) }}" class="btn btn-primary float-right">Pick</a>
            @endauth
          </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title">Premium</h5>
            <ul class="card-text">
                <li><strong>Everything in Free</strong></li>
                <li>Prelim mock test series</li>
                <li>Comments Section</li>
            </ul>
            <div class="border text-center p-1 mb-2">
                Rs 4999<sup class="text-danger">*</sup>
            </div>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary float-right">Pick</a>
            @endguest
            @auth
                <a href="{{ route('user::subscription', ['action' => 'show']) }}" class="btn btn-primary float-right">Pick</a>
            @endauth
          </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card" style="width: 18rem;">
          <div class="card-body">
            <h5 class="card-title">Pro</h5>
            <ul class="card-text">
                <li><strong>Everything in Free and Premium</strong></li>
                <li>Notes section</li>
                <li>Media section</li>
                <li>Subject wise News and Questions</li>
                <li>Upload and Save Essays</li>
                <li>View and save other user essays</li>
                <li>Article Access</li>
            </ul>
            <div class="border text-center p-1 mb-2">
                Rs 9999<sup class="text-danger">*</sup>
            </div>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary float-right">Pick</a>
            @endguest
            @auth
                <a href="{{ route('user::subscription', ['action' => 'show']) }}" class="btn btn-primary float-right">Pick</a>
            @endauth
          </div>
        </div>
    </div>
    <div class="w-100"></div>
    <div class="col mt-4"><span class="text-danger">*</span>  18% GST (SGST,CGST) to Government not included</div>
</div>
@stop
