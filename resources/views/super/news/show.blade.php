@extends('super.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/clndr/clndr.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<section class="content">
    <!-- Default box -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Select date</h3>
        </div>
        <form role="form">
            <div class="card-body text-center cal3">
              <div id="full-clndr" class="clearfix">
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
                        <div class="<%= day.classes %>" id="<%= day.id %>"><span class="day-number"><%= day.day %></span></div>
                      <% }); %>
                    </div>
                  </div>
                </script>
              </div>    
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a class="btn btn-block btn-primary" href="{{ route('super::news', ['action' => 'stash'])  }}">Add news</a>
                </div>
            </div>
        </form>
    </div>
        <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-newspaper"></i> News</h3>
            <div class="card-tools">
              <p>Date: <span id="select_date">-</span></p>
            </div>
        </div>
        <div class="card-body" id="news_display">

        </div>
    </div>
</section>
@stop

@push('scripts_foot')
    <script type="text/javascript" src="{!! asset('assets/plugins/clndr/clndr.min.js') !!}"></script>
@endpush
