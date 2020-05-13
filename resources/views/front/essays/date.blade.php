@extends('front.layouts.skeleton')

@section('content')
<h2>Essays</h2>

@foreach ($essays as $essay)
<div class="row pb-4 border-bottom essay" data-id="{{ $essay->id }}">
    <div class="col">
        <p class="lead"><i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i> {{ $essay->title }}</p>
        <div class="essay-stats">
            <div class="pt-2"><label>Picked By:</label><span id="essay-pickers"></span></div>
            <div class="pt-2"><label>Completed By:</label><span id="essay-finishers"></span></div>
        </div>
    </div>
    @if(Gate::allows('full-user'))
    <div class="w-100"></div>
    <div class="col">
        <div class="row essay-action">
            <div class="col-6">
                <button type="button" class="btn btn-primary btn-sm">Pick</button>
                <button type="button" class="btn btn-primary btn-sm">Write</button>
            </div>
            <div class="col-6 text-center essay-timer">23:59:59</div>
        </div>
    </div>
    @endif
</div>
@endforeach
@stop
