@extends('front.layouts.skeleton')

@push('col_left_top')
@include('user.partials.left_col')
@endpush

@section('content')
<h1 class="page-title">User subscription</h1>
<div class="row mb-5">
    <div class="col">
        <h4>Your current Subscription: {{ config('aspirant.user_subscription.plans')[$user->subscription] }}</h4>
    </div>
</div>
@include('front.partials.plan_blocks')
@stop
