@extends('front.layouts.skeleton')

@push('col_left_top')
@include('user.partials.left_col')
@endpush

@section('content')
<h1 class="page-title">Subscription Verification</h1>
<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <div class="card-body">
               @if($success)
                    <div class="alert alert-success" role="alert">
                      {{ $msg }}
                    </div>
               @else
                    <div class="alert alert-danger" role="alert">
                      {{ $msg }}
                    </div>
                    <div class="d-inline float right">
                      <a class="btn btn-primary" href="{{ route('user::subscription', ['action' => 'show']) }}" role="button">Retry</a> &nbsp; <a class="btn btn-primary" href="{{ route('front::manifest', ['action' => 'contact']) }}" role="button">Drop a message</a>
                    </div>
               @endif
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>
@stop
