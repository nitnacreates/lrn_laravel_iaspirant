@extends('front.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/clndr/clndr.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<h2>Essays</h2>

<div class="row">
    <div class="col-6">
        <h3>Latest Essay</h3>
    </div>
    <div class="col-6">
        @if($latest_dt)<h4><a href="{{ route('front::essays-date', ['param' => $latest_dt->format('Y-m-d')]) }}">{{ $latest_dt->format('M d,Y') }}</a></h4>@endif
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h3>Select Day</h3>
        <div id="full-clndr" class="clearfix">
        <script type="text/javascript">
            pub_globes.clndr_summon = "{{ route('front::essays', ['action' => 'summon']) }}";
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
                        <a href="{{ url('/') }}/essays/date/<% print(moment(day.date).format("YYYY-MM-DD")) %>"><span class="day-number"><%= day.day %></span></a>
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

<div class="row mt-5">
    <div class="col-12">
        <h3>{{ date('M') }} Essays</h3>
        <ul>
            @foreach($month_dates as $dt)
                <li><a href="{{ route('front::essays-date', ['param' => $dt->format('Y-m-d')]) }}">{{ $dt->format('M d, Y') }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@stop

@push('scripts_foot')
    <script type="text/javascript" src="{!! asset('assets/plugins/clndr/clndr.min.js') !!}"></script>
@endpush
