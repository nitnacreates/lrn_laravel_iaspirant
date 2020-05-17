@extends('front.layouts.skeleton')

@section('content')
<h3>Essay Note</h3>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">{{ $essay->id ? 'Edit Essay' : 'Add Essay' }}</div>
            <form method="post" id="create-essay-response-form" class="wyswyg-form sub-picker" action="{{ route('front::three-essay-response', ['user_name' => auth()->user()->name, 'action' => 'create']) }}">
            <div class="card-body">
                @csrf
                <input type="hidden" name="id" value="{{ $essay->id ?? 0 }}">
                <div class="form-group row">
                    <div class="col">Date: {{ $essay->created_on->format('M d, Y') }}</div>
                    <div class="col">Subject: {{ config('aspirant.subject_map')[$essay->sub] }}</div>
                    <div class="col">Word Limit: {{ $essay->word_limit }}</div>
                </div>
                @if(empty($response->completed_on))
                <div class="form-group row">
                    <label for="essay" class="col-md-2 col-form-label">Time Left</label>
                    <div class="col-md-10 essay-timer col-form-label">{{ FrontHelper::countdownto24($response->created_at) }}</div>
                </div>
                @endif
                <hr />
                <div class="form-group row">
                    <label for="essay" class="col-md-2 col-form-label">Essay</label>
                    <div class="col-md-10 col-form-label">{{ $essay->title }}</div>
                </div>
                <div class="form-group row">
                    <label for="response_content" class="col-md-2 col-form-label">Answer</label>
                    <div class="col-md-12">
                        <textarea name="response_content" class="form-control wyswyg-bg @error('response_content') is-invalid @enderror" aria-describedby="essayHelp" rows="3" autocomplete="response_content">{{ old('response_content', $response->response_content) }}</textarea>
                        <small id="essayHelp" class="form-text text-muted">For image, upload image in 'My Media', and then insert in editor from 'Image' icon</small>
                        @error('response_content')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="locked" class="col-md-2 col-form-label">Locked</label>
                    <div class="col-md-10">
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="hidden" name="locked" value="0">
                            <input type="checkbox" id="locked" class="custom-control-input" name="locked" value="1" @if(old('locked',$response->locked)) checked="checked" @endif autocomplete="locked">
                            <label class="custom-control-label" for="locked">Locked</label>
                        </div>
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
            <input type="hidden" id="opener_media_select" value="">
        </div>
    </div>
</div>
<script type="text/javascript">
    var myglobes = {"picker_url" : "{{ route('user::media', ['action' => 'picker']) }}"};
</script>
@stop

@push('scripts_foot')
<script type="text/javascript" src="{!! asset('assets/plugins/tinymce/tinymce.min.js') !!}"></script>
@endpush
