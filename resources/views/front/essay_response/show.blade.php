@extends('front.layouts.skeleton')

@section('content')
<h3>Show Essay</h3>
<div class="row">
    <div class="col">
        <strong>Essay:</strong> {{ $essay->title }}
        <div class="essay_content">
            {!! $response->response_content !!}
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-6">
        Date: {{ $essay->created_on->format('M d,Y') }} <br />
        Word Limit: {{ $essay->word_limit }} <br />
        Weightage: {{ $essay->weightage }}
    </div>
    <div class="col-6">
        Subject: {{  config('aspirant.subject_map')[$essay->sub] }} <br />
        Topic: {{ $essay->topic_id }}
    </div>
</div>

@if(!empty($response))
<div class="row mt-5">
    <div class="col">
        Access: {{ $response->locked ? 'Private' : 'Public' }} <br />
        @if(isset($completed_in))
        Completed in : {{ $completed_in }}
        @endif
    </div>
    <div class="col-6 text-right">
        @if($is_user_same)
        <a class="btn btn-primary" href="{{ route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'create', 'id' => $essay_id]) }}" role="button">Edit</a>
        @endif
    </div>
</div>
@endif
@stop


