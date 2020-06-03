@extends('super.layouts.skeleton')
@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-book"></i> {{ $sub_title }}</h3>
            </div>
            <form role="form" method="post" action="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub]) }}">
            <div class="card-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{ $subid }}" />
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" @if($mode == 'display') value="{{ $syllabus->title }}" @endif class="form-control" placeholder="New ...">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category">
                          <option value="b" selected="selected">Prelims & Mains</option>
                          <option value="p" @if(($mode == 'display') && ($syllabus->category == 'p')) selected="selected" @endif>Prelims</option>
                          <option value="m" @if(($mode == 'display') && ($syllabus->category == 'm')) selected="selected" @endif>Mains</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>About</label>
                        <textarea class="form-control" name="about" rows="3" placeholder="About ...">@if($mode == "display"){{ $syllabus->about }}@endif</textarea>
                    </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <button type="submit" name="mode" value="{{$mode == 'display' ? 'edit':'add'}}"class="btn btn-block btn-success">Save</button>
                </div>
                <div class="btn-group">
                    <button type="button" name="mode" value="delete" class="btn btn-block btn-danger">Delete Button</button>
                </div>
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
            </div>
            </form>
        </div>
    </section>
@stop
