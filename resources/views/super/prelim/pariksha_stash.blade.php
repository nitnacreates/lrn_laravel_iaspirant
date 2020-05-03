@extends('super.layouts.skeleton')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-question-circle-o"></i> Prelim Stash</h3>
            </div>

            <form role="form" id="stash_form" method="post" action="{{ route('super::prelim', ['action' => 'pariksha-stash', 'pid' => $pid]) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="qid" value="{{ $mode == 'edit' ?  $pre_ques->id : 0}}">

            <div class="card-body">
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $pre_ques->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Select Topic</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="topic_id" value="{{ $mode == 'edit' ? $pre_ques->topic_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" autocomplete="off" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Question Reference (to avoid duplicates)</label>
                    @if($mode == 'edit')
                        <div class="row">
                            <div class="col-10">
                                <input type="text" name="ques_ref_id" class="form-control" placeholder="Number ..." value="{{ $pre_ques->ques_ref_id }}" />
                            </div>
                            <div class="col-2">
                                <p>Question: <a href="{{ route('super::question', ['action' => 'stash', 'id' => $pre_ques->ques_ref_id]) }}">{{ $pre_ques->ques_ref_id }}</a></p>
                            </div>
                        </div>
                    @else
                        <input type="text" name="ques_ref_id" class="form-control" placeholder="Number ..." />
                    @endif
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        @for ($i = 1; $i < 6; $i++)
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage{{$i}}" name="weightage" value="{{$i}}" @if(($mode == 'edit') && ($pre_ques->weightage == $i)) checked="checcked" @endif />
                                <label for="weightage{{$i}}" class="custom-control-label">{{$i}}</label>
                            </div>   
                        </div>
                        @endfor
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label>Question</label>
                    <textarea class="form-control wyswyg_block" name="question" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->question }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option A</label>
                    <textarea class="form-control wyswyg_block" name="option_a" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->option_a }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option B</label>
                    <textarea class="form-control wyswyg_block" name="option_b" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->option_b }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option C</label>
                    <textarea class="form-control wyswyg_block" name="option_c" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->option_c }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Option D</label>
                    <textarea class="form-control wyswyg_block" name="option_d" rows="2" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->option_d }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Answer</label>
                    <select class="form-control" name="answer" autocomplete="off">
                        <option>Select</option>
                        <option value="a" @if(($mode == 'edit') && ('a' == $pre_ques->answer)) selected="selected" @endif>A</option>
                        <option value="b" @if(($mode == 'edit') && ('b' == $pre_ques->answer)) selected="selected" @endif>B</option>
                        <option value="c" @if(($mode == 'edit') && ('c' == $pre_ques->answer)) selected="selected" @endif>C</option>
                        <option value="d" @if(($mode == 'edit') && ('d' == $pre_ques->answer)) selected="selected" @endif>D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Explanation</label>
                    <textarea class="form-control wyswyg_block" name="explanation" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->explanation }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Secret Text</label>
                    <textarea class="form-control wyswyg_block" name="secret_text" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $pre_ques->secret_text }}@endif</textarea>
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
