@extends('super.layouts.skeleton')

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-newspaper-o"></i> Stash News</h3>
            </div>
            <form role="form" id="stash_form"  method="post" action="{{ route('super::news', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{$mode == 'edit'? $news->id : 0 }}" />
            <div class="card-body">
                <div class="form-group">
                    <label>News Title</label>
                    <input type="text" name="news_title" @if($mode == 'edit') value="{{ $news->news_title }}" @endif class="form-control" placeholder="Title ...">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-on-danger custom-switch-off-success">
                        <input type="hidden" name="locked" value="0" />
                        <input type="checkbox" name="locked" value="1" @if($mode != 'edit' || $news->locked) checked="checked" @endif class="custom-control-input" id="newslocked">
                        <label class="custom-control-label" for="newslocked">Locked ?</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>News Link</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="check_link">Check Link</button>
                        </div>
                        <input type="text" name="news_link" @if($mode == 'edit') value="{{ $news->news_link }}" @endif class="form-control" placeholder="Link ...">
                    </div>
                </div>
                <div class="form-group">
                    <label>Meta Keys</label>
                    <input type="text" name="meta_key" @if($mode == 'edit') value="{{ $news->meta_key }}" @endif class="form-control" placeholder="Meta Keys ...">
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea class="form-control" name="meta_descr" rows="3" placeholder="Meta Description ...">@if($mode == 'edit'){{ $news->meta_descr }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $news->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Select Topic</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="topic_id" value="{{ $mode == 'edit' ? $news->topic_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" class="form-control sublabel" autocomplete="off" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="category">
                      <option value="b" @if(($mode == 'edit') && ($news->category == 'b')) selected="selected" @endif>Prelims & Mains</option>
                      <option value="p" @if(($mode == 'edit') && ($news->category == 'p')) selected="selected" @endif>Prelims</option>
                      <option value="m" @if(($mode == 'edit') && ($news->category == 'm')) selected="selected" @endif>Mains</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>News Type</label>
                    <select class="form-control" name="type" autocomplete="off">
                        @foreach(config('aspirant.news.type') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $news->type)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        @for ($i = 1; $i < 6; $i++)
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage{{$i}}" name="weightage" value="{{$i}}" @if(($mode == 'edit') && ($news->weightage == $i)) checked="checcked" @endif />
                                <label for="weightage{{$i}}" class="custom-control-label">{{$i}}</label>
                            </div>   
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group">
                    <label>Show On</label>
                    <input type="text" name="show_on" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'edit') value="{{ $news->show_on }}" @endif />
                </div>
                <div class="form-group">
                    <label>News Content</label>
                    <textarea class="form-control wyswyg_block" name="news_content" rows="3" placeholder="News Content ...">@if($mode == 'edit'){{ $news->news_content }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Self Note</label>
                    <textarea class="form-control" name="self_note" rows="3" placeholder="Self Note ...">@if($mode == 'edit'){{ $news->self_note }}@endif</textarea>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="{{$mode}}" class="btn btn-block btn-success">Save</button>
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

@push('scripts_foot')
    <script type="text/javascript" src="{!! asset('assets/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpush
