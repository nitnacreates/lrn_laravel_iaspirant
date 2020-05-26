@extends('front.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/chart/Chart.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush


@section('content')
<h1 class="page-title">Prelim Test Report</h1>

<div class="row border-bottom py-3">
    <div class="col">
        <h3>Test Toppers</h3>
    <table class="table">
      <thead>
        <tr>
            <th scope="col">Rank</th>
            <th scope="col">Name</th>
            <th scope="col">Marks</th>
        </tr>
      </thead>
      <tbody>
        <?php $counter = 1;?>
        @foreach($toppers as $tpr)
            <tr>
                <th scope="row">{{ $counter++ }}</th>
                <td>{{ $tpr->user_grab->name }}</td>
                <td>{{ $tpr->marks }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
    </div>
</div>

<div class="row border-bottom py-3" id="attempt-area">
    @foreach($responses as $rsp)
        
    @endforeach
    <canvas id="bar-chart" width="10%" height="10%"></canvas>
    <canvas id="pie-chart" width="10%" height="10%"></canvas>
</div>
@stop

@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/plugins/chart/Chart.min.js') !!}"></script>
@endpush
