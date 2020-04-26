@extends('super.layouts.skeleton')
@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/js/tinymce/tinymce.min.js') !!}"></script>
@endpush

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Stash essays</h3>
            </div>
            <form role="form" id="stash_form" method="post" action="{{ route('super::essays', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $mode == 'edit' ?  $essays->id : 0}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Essay Title</label>
                    <input type="text" name="essay_title" class="form-control" placeholder="New ..." @if($mode == 'edit') value="{{ $essays->essay_title }}" @endif />
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $essays->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Subject Heading</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="syllabi_id" value="{{ $mode == 'edit' ? $essays->syllabi_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Heading</button>
                        </div>
                        <input type="text" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Word Limit</label>
                    <select class="form-control" name="word_limit" autocomplete="off">
                        @foreach(config('aspirant.essay_word_limits') as $lmt)
                            <option value="{{ $lmt }}" @if(($mode == 'edit') && ($key == $essays->word_limit)) selected="selected" @endif>{{$lmt}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage" name="weightage" value="1" @if(($mode == 'edit') && ($essays->weightage == '1')) checked="checcked" @endif />
                                <label for="weightage" class="custom-control-label">1</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage2" name="weightage" value="2" @if(($mode == 'edit') && ($essays->weightage == '2')) checked="checcked" @endif />
                                <label for="weightage2" class="custom-control-label">2</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage3" name="weightage" value="3" @if(($mode == 'edit') && ($essays->weightage == '3')) checked="checcked" @endif />
                                <label for="weightage3" class="custom-control-label">3</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage4" name="weightage" value="4" @if(($mode == 'edit') && ($essays->weightage == '4')) checked="checcked" @endif />
                                <label for="weightage4" class="custom-control-label">4</label>
                            </div>   
                        </div>
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage5" name="weightage" value="5" @if(($mode == 'edit') && ($essays->weightage == '5')) checked="checcked" @endif />
                                <label for="weightage5" class="custom-control-label">5</label>
                            </div>   
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date Created</label>
                        <input type="text" name="created_on" id="date_created" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'edit') value="{{ $essays->created_on }}" @endif />
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                          <input type="hidden" name="locked" value="0">
                          <input class="form-check-input" name="locked" value="1" type="checkbox" @if($mode != 'edit' || ($essays->locked == 1))) checked @endif>
                          <label class="form-check-label">Is locked?</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Essay Framework</label>
                        <textarea class="form-control wyswyg_block" name="essay_framework" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $essays->essay_framework }}@endif</textarea>
                    </div>
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
@stop
