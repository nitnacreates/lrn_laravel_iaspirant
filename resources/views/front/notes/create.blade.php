@extends('front.layouts.skeleton')

@section('content')
<h3>Create Note</h3>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">{{ __('New Note') }}</div>
            <form method="POST" action="{{ route('front::notes', ['user_name' => auth()->user()->name, 'controller' => 'notes', 'action' => 'create']) }}">
            <div class="card-body">
                @csrf
                <div class="form-group row">
                    <label for="sub" class="col-md-2 col-form-label text-md-right">{{ __('Subject') }}</label>
                    <div class="col-md-10">
                        <input id="sub" type="text" class="form-control @error('sub') is-invalid @enderror" name="sub" value="{{ old('sub') }}" required autocomplete="sub" autofocus>
                        @error('sub')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="topic_id" class="col-md-2 col-form-label text-md-right">{{ __('Topic') }}</label>
                    <div class="col-md-10">
                        <input id="topic_id" type="text" class="form-control @error('topic_id') is-invalid @enderror" name="topic_id" value="{{ old('topic_id') }}" required autocomplete="topic_id" autofocus>
                        @error('topic_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="locked" class="col-md-2 col-form-label text-md-right">{{ __('Locked') }}</label>
                    <div class="col-md-10">
                        <input id="locked" type="text" class="form-control @error('locked') is-invalid @enderror" name="locked" value="{{ old('locked') }}" required autocomplete="locked" autofocus>
                        @error('locked')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="note_title" class="col-md-2 col-form-label text-md-right">{{ __('Note Title') }}</label>
                    <div class="col-md-10">
                        <input id="note_title" type="text" class="form-control @error('note_title') is-invalid @enderror" name="note_title" value="{{ old('note_title') }}" required autocomplete="note_title" autofocus>
                        @error('note_title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
