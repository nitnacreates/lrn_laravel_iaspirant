@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Select date</h3>
        </div>
        <form role="form">
            <div class="card-body text-center">
                <div class="d-inline-block" id="news_calendar_show"></div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a class="btn btn-block btn-primary" href="{{ route('super::news', ['action' => 'stash'])  }}">Add news</a>
                </div>
            </div>
        </form>
    </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-newspaper"></i> News</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Sub</th>
                      <th>Syllabus Id</th>
                      <th>Category</th>
                      <th>Weightage</th>
                      <th>Created On</th>
                      <th>Visible From</th>
                      <th>Locked</th>
                      <th>Title</th>
                      <th>Raw Content</th>
                    </tr>
                  </thead>
                  <tbody id="news_display">

                  </tbody>
              </table>
        </div>
    </div>
</section>
@stop
