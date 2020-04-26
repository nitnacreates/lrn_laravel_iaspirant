@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Select date</h3>
        </div>
        <form role="form">
            <div class="card-body text-center">
                <div class="d-inline-block" id="essays_calendar_show"></div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a class="btn btn-block btn-primary" href="{{ route('super::essays', ['action' => 'stash'])  }}">Add essay</a>
                </div>
            </div>
        </form>
    </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-pencil-square-o"></i> Essays</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                  <thead>
                    <tr>
                      <th style="width: 10px">Id</th>
                      <th>Sub</th>
                      <th>Syllabus Id</th>
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
</section>
@stop
