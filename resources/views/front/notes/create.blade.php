@extends('front.layouts.skeleton')

@section('content')
<h3>Create Note</h3>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">{{ $note->id ? 'Edit Note' : 'Add Note' }}</div>
            <form method="post" id="create-note-form" class="wyswyg-form sub-picker" action="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}">
            <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{ $note->id ?? 0 }}">
                <div class="form-group row">
                    <label for="sub" class="col-md-2 col-form-label">{{ __('Subject') }}</label>
                    <div class="col-md-10">
                        <select name="sub" id="sub" class="form-control  @error('sub') is-invalid @enderror" autocomplete="sub">
                            @foreach(config('aspirant.subject_map') as $key => $val)
                                <option value="{{ $key }}" @if(old('sub', $note->sub) == $key) selected="selected" @endif>{{$val}}</option>
                            @endforeach
                        </select>
                        @error('sub')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row" id="subject-heading">
                    <label for="topic" class="col-md-2 col-form-label">{{ __('Topic') }}</label>
                    <div class="col-md-10 input-group">
                        <input type="hidden" name="topic_id" value="{{ old('topic_id',$note->topic_id ?: 0) }}"/>
                        <div class="input-group-prepend">
                          <button type="button" class="btn btn-primary" id="select_heading">Select Topic</button>
                        </div>
                        <input type="text" class="form-control sublabel" autocomplete="topic" disabled placeholder="Pick..." value="{{ $sel_topic }}" />
                        @error('topic')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="locked" class="col-md-2 col-form-label">{{ __('Locked') }}</label>
                    <div class="col-md-10">
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="hidden" name="locked" value="0">
                            <input type="checkbox" id="locked" class="custom-control-input" name="locked" value="1" @if(old('locked',$note->locked)) checked="checked" @endif autocomplete="locked">
                            <label class="custom-control-label" for="locked">Locked</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="note_title" class="col-md-2 col-form-label">{{ __('Note Title') }}</label>
                    <div class="col-md-10">
                        <input id="note_title" type="text" class="form-control @error('note_title') is-invalid @enderror" name="note_title" value="{{ old('note_title',$note->note_title) }}" required autocomplete="note_title" autofocus>
                        @error('note_title')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="note_content" class="col-md-2 col-form-label">{{ __('Note Content') }}</label>
                    <div class="col-md-12">
                        <textarea name="note_content" class="form-control wyswyg-bg @error('note_content') is-invalid @enderror" rows="3" autocomplete="note_content">{{ old('note_content', $note->note_content) }}</textarea>
                        @error('note_content')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="self_note" class="col-md-2 col-form-label">{{ __('Self Note') }}</label>
                    <div class="col-md-12">
                        <textarea name="self_note" aria-describedby="self-note-help" class="form-control wyswyg-sm" rows="3" autocomplete="self_note">{{ old('self_note', $note->self_note) }}</textarea>
                        <small id="self-note-help" class="form-text text-muted">Only you can see this.</small>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-block btn-success">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

@stop

@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpush
