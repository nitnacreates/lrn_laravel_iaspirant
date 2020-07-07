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
                    <input type="text" name="news_title" @if($mode == 'edit') value="{{ old('news_title', ($mode == 'edit' ? $news->news_title : '')) }}" @endif class="form-control" placeholder="Title ..." required>
                </div>
                <div class="form-group">
                    <label>News Link</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary" id="check_link">Check Link</button>
                        </div>
                        <input type="text" name="news_link" @if($mode == 'edit') value="{{ old('news_link', ($mode == 'edit' ? $news->news_link : '')) }}" @endif class="form-control @if($mode == 'edit') is-valid @endif" placeholder="Link ..." required>
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
                    <label>News Content</label>
                    <textarea class="form-control wyswyg_block" name="news_content" rows="3" placeholder="News Content ...">{{ old('news_content', ($mode == 'edit' ? $news->news_content : '')) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Self Note</label>
                    <textarea class="form-control" name="self_note" rows="3" placeholder="Self Note ...">@if($mode == 'edit'){{ $news->self_note }}@endif</textarea>
                </div>
                <hr/>
                <div class="card card-info" id="show_dates">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-calendar" aria-hidden="true"></i> Show On Dates</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody id="essay_dates_show">
                                <?php $counter = 1; ?>
                                @if($mode == 'edit')
                                @foreach($news->news_shows as $date)
                                <tr>
                                    <td>
                                        <input type="text" name="show_on[{{$counter++}}]" class="form-control pick-date" placeholder="yyyy-mm-dd" @if($mode == 'edit') value="{{ $date->show_on->format('Y-m-d') }}" @endif />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-date">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                              </tbody>
                          </table>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-warning btn-sm" id="add_new_show" value="{{$counter}}">Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:void(0)" class="btn btn-block btn-danger">Please copy news content before saving</a>
                </div>
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
    <script type="text/unders" id="render_show_dates">
        <tr>
            <td>
                <input type="text" name="show_on[<%= cntr %>]" class="form-control pick-date" placeholder="yyyy-mm-dd" />
            </td>
            <td>
                <button type="button" value="<%= cntr %>" class="btn btn-danger btn-sm delete-date">Delete</button>
            </td>
        </tr>
    </script>
    <script type="text/javascript" src="{!! asset('assets/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpush
