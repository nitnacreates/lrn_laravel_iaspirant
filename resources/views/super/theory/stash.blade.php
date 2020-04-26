@extends('super.layouts.skeleton')

@push('styles_header')
    <link href="{!! asset('assets/plugins/select2/select2.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Stash theory</h3>
            </div>
            <form role="form" id="stash_form" method="post" action="{{ route('super::theory', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $mode == 'edit' ?  $theory->id : 0}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Theory Title</label>
                    <input type="text" name="theory_title" class="form-control" placeholder="New ..." @if($mode == 'edit') value="{{ $theory->theory_title }}" @endif />
                </div>
                <div class="form-group">
                    <div class="form-check">
                      <input type="hidden" name="locked" value="0">
                      <input class="form-check-input" name="locked" value="1" type="checkbox" @if($mode != 'edit' || $theory->locked) checked @endif>
                      <label class="form-check-label">Locked ?</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select class="form-control select2" name="sub" autocomplete="off">
                        @foreach(config('aspirant.subject_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $theory->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                        <option>------------Optional------------</option>
                        @foreach(config('aspirant.optional_map') as $key => $val)
                            <option value="{{ $key }}" @if(($mode == 'edit') && ($key == $theory->sub)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" id="subject-heading">
                    <label>Select Topic</label>
                    <div class="input-group mb-3">
                        <input type="hidden" name="topic_id" value="{{ $mode == 'edit' ? $theory->topic_id : 0 }}"/>
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" autocomplete="off" class="form-control sublabel" placeholder="Pick..." disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="category">
                      <option value="b" @if(($mode == 'edit') && ($theory->category == 'b')) selected="selected" @endif>Prelims & Mains</option>
                      <option value="p" @if(($mode == 'edit') && ($theory->category == 'p')) selected="selected" @endif>Prelims</option>
                      <option value="m" @if(($mode == 'edit') && ($theory->category == 'm')) selected="selected" @endif>Mains</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Weightage</label>
                    <div class="row">
                        @for ($i = 1; $i < 6; $i++)
                        <div class="col-2">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="weightage{{$i}}" name="weightage" value="{{$i}}" @if(($mode == 'edit') && ($theory->weightage == $i)) checked="checcked" @endif />
                                <label for="weightage{{$i}}" class="custom-control-label">{{$i}}</label>
                            </div>   
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group">
                    <label>Theory References</label>
                    <textarea class="form-control" name="theory_reference" rows="3" placeholder="References ...">@if($mode == 'edit'){{ $theory->theory_reference }}@endif</textarea>
                </div>
                <div class="form-group">
                    <label>Theory Content</label>
                    <textarea class="form-control wyswyg_block" name="theory_content" rows="3" placeholder="Content ...">@if($mode == 'edit'){{ $theory->theory_content }}@endif</textarea>
                </div>
                <?php
                    $summary = 0;
                    if(!empty($theory->theory_summaries) && ($theory->theory_summaries->count() == 1)){
                        $summary = $theory->theory_summaries[0];
                    }
                ?>
                <div class="form-group">
                    <label>Theory Summary</label>
                    <input type="hidden" name="theory_summary_id" value="{{ $mode == 'edit' && !empty($summary) ?  $summary->id : 0}}">
                    <textarea class="form-control" name="theory_summary" rows="3" placeholder="Summary ...">@if($mode == 'edit' && !empty($summary)){{$summary->theory_summary}} @endif</textarea>
                </div>
                <div class="form-group">
                    <div class="form-check">
                      <input type="hidden" name="summary_locked" value="0">
                      <input class="form-check-input" name="summary_locked" value="1" type="checkbox" @if($mode != 'edit' || (!empty($summary) && $summary->locked)) checked @endif>
                      <label class="form-check-label">Summary Locked ?</label>
                    </div>
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
    <script type="text/javascript" src="{!! asset('assets/plugins/select2/select2.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpush
