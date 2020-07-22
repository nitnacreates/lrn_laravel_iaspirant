@extends('front.layouts.skeleton')

@push('col_left_top')
@include('user.partials.left_col')
@endpush

@section('content')
<h1 class="page-title">Subscription Review</h1>
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <h3>Personal Details</h3>
                <div class="row py-1">
                    <div class="col-6">First Name</div>
                    <div class="col-6">{{ $user->user_keep->first_name }}</div>
                </div>
                <div class="row py-1">
                    <div class="col-6">Last Name</div>
                    <div class="col-6">{{ $user->user_keep->last_name }}</div>
                </div>
                <div class="row py-1">
                    <div class="col-6">Email</div>
                    <div class="col-6">{{ $user->email }}</div>
                </div>
                <div class="row py-1">
                    <div class="col-6">Phone</div>
                    <div class="col-6">{{ $user->user_keep->phone }}</div>
                </div>
                <h3 class="mt-3">Plan Details</h3>
                <div class="row py-1">
                    <div class="col-6">Plan Name</div>
                    <div class="col-3">{{ $plan }}</div>
                    <div class="col-3"><a class="border-bottom" href="{{ route('user::subscription', ['action' => 'show']) }}">Pick another</a></div>
                </div>
                <div class="row py-1">
                    <div class="col-6">Amount</div>
                    <div class="col-6">&#8377; {{ number_format((float)$price, 2, '.', '') }}</div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-12"><p>You will be redirected to Razorpay. Razorpay is an Indian PCI-DSS compliant payment gateway system, manging lakhs of businesses, so buy without any worries </p></div>
                </div>
                <div class="row py-1">
                    <div class="col-12 text-right"><button type="button" class="btn btn-success">Buy</button></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>
@stop
