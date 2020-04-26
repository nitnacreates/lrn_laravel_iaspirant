@extends('super.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/clndr/clndr.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<section class="content">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-pencil-square-o"></i> Essays</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                  <thead>
                    <tr>
                      <th style="width: 10px">Id</th>
                      <th>Sub</th>
                      <th>Topic</th>
                      <th>Word Limit</th>
                      <th>Weightage</th>
                      <th>Locked</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody id="essays_display">

                  </tbody>
              </table>
        </div>
    </div>
    <div class="card  card-outline">
        <div class="card-header">
            <h3 class="card-title">Select date</h3>
        </div>
        <form role="form">
            <div class="card-body text-centercal3">
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
                    <a class="btn btn-block btn-primary" href="{{ route('super::essays', ['action' => 'stash'])  }}">Add essay</a>
                </div>
            </div>
        </form>
    </div>
</section>
@stop

@push('scripts_foot')
    <script type="text/javascript" src="{!! asset('assets/plugins/clndr/clndr.min.js') !!}"></script>
@endpush
