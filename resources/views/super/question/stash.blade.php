@extends('super.layouts.skeleton')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-university"></i> Question Stash</h3>
            </div>
            <form role="form" id="stash_form" method="post" action="{{ route('super::question', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $mode == 'edit' ?  $question->id : 0}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        @foreach(config('aspirant.question.types.front') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $question->type)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                        @foreach(config('aspirant.question.types.super') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $question->type)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Show On</label>
                    <input type="text" name="show_on" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'edit') value="{{ $question->show_on }}" @endif />
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-on-danger custom-switch-off-success">
                        <input type="hidden" name="locked" value="0" />
                        <input type="checkbox" name="locked" value="1" @if($mode != 'edit' || $question->locked) checked="checked" @endif class="custom-control-input" id="essaylocked">
                        <label class="custom-control-label" for="essaylocked">Locked ?</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $question->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Select Topic</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="topic_id" value="{{ $mode == 'edit' ? $question->topic_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" autocomplete="off" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label>Question</label>
                    <textarea class="form-control wyswyg_block" name="question" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $question->question }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option A</label>
                    <textarea class="form-control wyswyg_block" name="option_a" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $question->option_a }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option B</label>
                    <textarea class="form-control wyswyg_block" name="option_b" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $question->option_b }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option C</label>
                    <textarea class="form-control wyswyg_block" name="option_c" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $question->option_c }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option D</label>
                    <textarea class="form-control wyswyg_block" name="option_d" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $question->option_d }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Answer</label>
                    <select class="form-control" name="answer" autocomplete="off">
                        <option>Select</option>
                        <option value="a" @if(($mode == 'edit') && ('a' == $question->answer)) selected="selected" @endif>A</option>
                        <option value="b" @if(($mode == 'edit') && ('b' == $question->answer)) selected="selected" @endif>B</option>
                        <option value="c" @if(($mode == 'edit') && ('c' == $question->answer)) selected="selected" @endif>C</option>
                        <option value="d" @if(($mode == 'edit') && ('d' == $question->answer)) selected="selected" @endif>D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Explanation</label>
                    <textarea class="form-control wyswyg_block" name="explanation" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $question->explanation }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Secret Text</label>
                    <textarea class="form-control wyswyg_block" name="secret_text" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $question->secret_text }}@endif</textarea>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="{{$mode == 'edit' ? 'edit' : $mode}}" class="btn btn-block btn-success">Save</button>
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
