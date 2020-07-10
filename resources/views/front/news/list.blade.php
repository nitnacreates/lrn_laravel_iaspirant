@extends('front.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/clndr/clndr.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush

@push('col_left_top')
@include('front.partials.news_read')
@endpush

@section('content')
<h1 class="page-title">News</h1>

<div class="row">
    <div class="col">
        <h3>Latest News : <a href="{{ route('front::news-date', ['param' => $latest_dt->format('Y-m-d')]) }}">{{ $latest_dt->format('F d, Y') }}</a></h3>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div id="full-clndr" class="clearfix">
        <script type="text/javascript">
            pub_globes.clndr_summon = "{{ route('front::news', ['action' => 'summon']) }}";
        </script>
        <script type="text/template" id="full-clndr-template">
          <div class="clndr-controls">
            <div class="clndr-previous-button"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></div>
            <div class="clndr-next-button"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></div>
            <div class="current-month"><%= month %> <%= year %></div>
          </div>
          <div class="clndr-grid">
            <div class="days-of-the-week clearfix">
              <% _.each(daysOfTheWeek, function(day) { %>
                <div class="header-day"><%= day %></div>
              <% }); %>
            </div>
            <div class="days">
              <% _.each(days, function(day) { %>
                <div class="<%= day.classes %>" id="<%= day.id %>">
                <% if (day.classes.indexOf("event") >= 0) { %>
                    <a href="{{ url('/') }}/news/date/<% print(moment(day.date).format("YYYY-MM-DD")) %>">
                        <span class="day-number"><%= day.day %></span>
                    </a>
                <% } else{ %>
                    <span class="day-number"><%= day.day %></span>
                <% } %>
                </div>
              <% }); %>
            </div>
          </div>
        </script>
        </div> 
    </div>
</div>

<?php $current_month = 0; ?>
<div class="row mt-5">
    @foreach($month_dates as $dt)
        @if($dt->format('F') !== $current_month)
            @if($current_month)
                </ul></div></div>
            @endif
            <?php $current_month = $dt->format('F'); ?>
            <div class="col-3"><div class="month"><h3>{{ $dt->format('F, Y') }}</h3></div><div class="month-dates"><ul>
        @endif
        <li><a href="{{ route('front::news-date', ['param' => $dt->format('Y-m-d')]) }}">{{ $dt->format('d, Y') }}</a></li>
    @endforeach
    </ul></div></div>
</div>
@stop

@push('scripts_foot')
    <script type="text/javascript" src="{!! asset('assets/plugins/clndr/clndr.min.js') !!}"></script>
@endpush
