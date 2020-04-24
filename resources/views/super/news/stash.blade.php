@extends('super.layouts.skeleton')
@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/js/tinymce/tinymce.min.js') !!}"></script>
@endpush

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-newspaper-o"></i> Stash News</h3>
            </div>
            <form role="form" id="stash_form"  method="post" action="{{ route('super::news', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$mode == 'display'? $news->id : 0 }}" />
            <div class="card-body">
                <div class="form-group">
                    <label>News Title</label>
                    <input type="text" name="news_title" @if($mode == 'display') value="{{ $news->news_content->news_title }}" @endif class="form-control" placeholder="New ...">
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'display') && ($key == $news->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Subject Heading</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="syllabi_id" value="{{ $mode == 'display' ? $news->syllabi_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Heading</button>
                        </div>
                        <input type="text" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="category">
                      <option value="b" @if(($mode == 'display') && ($news->category == 'b')) selected="selected" @endif>Prelims & Mains</option>
                      <option value="p" @if(($mode == 'display') && ($news->category == 'p')) selected="selected" @endif>Prelims</option>
                      <option value="m" @if(($mode == 'display') && ($news->category == 'm')) selected="selected" @endif>Mains</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage" name="weightage" value="1" @if(($mode == 'display') && ($news->weightage == '1')) checked="checcked" @endif />
                                <label for="weightage" class="custom-control-label">1</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage2" name="weightage" value="2" @if(($mode == 'display') && ($news->weightage == '2')) checked="checcked" @endif />
                                <label for="weightage2" class="custom-control-label">2</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage3" name="weightage" value="3" @if(($mode == 'display') && ($news->weightage == '3')) checked="checcked" @endif />
                                <label for="weightage3" class="custom-control-label">3</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage4" name="weightage" value="4" @if(($mode == 'display') && ($news->weightage == '4')) checked="checcked" @endif />
                                <label for="weightage4" class="custom-control-label">4</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage5" name="weightage" value="5" @if(($mode == 'display') && ($news->weightage == '5')) checked="checcked" @endif />
                                <label for="weightage5" class="custom-control-label">5</label>
                            </div>   
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Date Created</label>
                    <input type="text" name="created_on" id="date_created" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'display') value="{{ $news->created_on }}" @endif />
                </div>
                <div class="form-group">
                    <label>Visible From</label>
                    <input type="text" name="visible_from" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'display') value="{{ $news->visible_from }}" @endif />
                    <div class="form-group">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="hidden" name="locked" value="0" />
                            <input type="checkbox" name="locked" value="1" @if(($mode == 'display') && ($news->locked == '1')) checked="checked" @endif class="custom-control-input" id="newslocked">
                            <label class="custom-control-label" for="newslocked">Locked</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>News Content</label>
                    <textarea class="form-control wyswyg_block" name="news_content" rows="3" placeholder="About ...">@if($mode == 'display'){{ $news->news_content->news_content }}@endif</textarea>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="{{$mode == 'display' ? 'edit' : $mode}}" class="btn btn-block btn-success">Save</button>
                </div>
            </div>
            </form>
            <input type="hidden" id="opener_media_select" value="">
        </div>
    </section>
    <script type="text/javascript">
        myglobes.picker_url = "{{ $picker_url }}";
    </script>
@stop
