@extends('super.layouts.skeleton')

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Stash essays</h3>
            </div>
            <form role="form" id="stash_form" method="post" action="{{ route('super::essays', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $mode == 'edit' ?  $essay->id : 0}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Essay Title</label>
                    <input type="text" name="title" class="form-control" placeholder="New ..." @if($mode == 'edit') value="{{ $essay->title }}" @endif />
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $essay->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Select Topic</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="topic_id" value="{{ $mode == 'edit' ? $essay->topic_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" autocomplete="off" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Word Limit</label>
                    <select class="form-control" name="word_limit" autocomplete="off">
                        @foreach(config('aspirant.essay_word_limits') as $lmt)
                            <option value="{{ $lmt }}" @if(($mode == 'edit') && ($lmt == $essay->word_limit)) selected="selected" @endif>{{$lmt}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        @for ($i = 1; $i < 6; $i++)
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage{{$i}}" name="weightage" value="{{$i}}" @if(($mode == 'edit') && ($essay->weightage == $i)) checked="checcked" @endif />
                                <label for="weightage{{$i}}" class="custom-control-label">{{$i}}</label>
                            </div>   
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group">
                    <label>Date Created</label>
                    <input type="text" name="created_on" id="date_created" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'edit') value="{{ $essay->created_on }}" @endif />
                </div>
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-on-danger custom-switch-off-success">
                        <input type="hidden" name="locked" value="0" />
                        <input type="checkbox" name="locked" value="1" @if($mode != 'edit' || $essay->locked) checked="checked" @endif class="custom-control-input" id="essaylocked">
                        <label class="custom-control-label" for="essaylocked">Locked ?</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Essay Framework</label>
                    <textarea class="form-control wyswyg_block" name="framework" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $essay->framework }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Essay References</label>
                    <textarea class="form-control wyswyg_block" name="references" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $essay->references }}@endif</textarea>
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
