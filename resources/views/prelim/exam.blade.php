@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Prelim Exam</h1>
<div class="row">
    <div class="col-6">
        <p><strong>Time Left: </strong><span class="countdown">{{ $time_remaining }}</span></p>
    </div>
    <div class="col-6 text-right">
        <p>Question: <span id="question-number" data-qid="1" data-total_ques={{ $questions_count }}> 1 / {{ $questions_count }}</span></p>
    </div>
</div>
<div class="row" id="current_question">
    <div></div>
</div>
<div class="row py-3 border-top border-bottom" id="question_action">
    <div class="col-4">
        <button type="button" value="0" id="prev_question" class="btn btn-secondary disabled">Previous</button>
        <button type="button" value="2" id="next_question" class="btn btn-secondary">Skip</button>
    </div>
    <div class="col-4 text-center">
        <button type="button" value="end_test" id="end_test" class="btn btn-danger">End Test</button>
    </div>
    <div class="col-4 text-right">
        <button type="button" value="2" id="review_question" class="btn btn-warning">Review and Continue</button>
        <button type="button" value="2" id="save_question" class="btn btn-success">Save and Continue</button>
    </div>
</div>
<div class="row text-center pt-3" id="question_track">
    <div class="col">
        <h3>Progress</h3>
        @for($i = 1; $i <= $questions_count; $i++)
            <button type="button" class="btn btn-outline-secondary" value="{{ $i }}">{{ $i }}</button>
            @if($i%10 == 0) </div><div class="w-100"></div><div class="col"> @endif
        @endfor
    </div>    
</div>
<div class="row d-none" id="question_block">
    @foreach($questions as $ques)
    <div class="col" id="pre-ques-{{$ques->display_id}}" data-ques={{$ques->display_id}}>
        <p><strong>Question {{$ques->display_id}}:</strong></p>
        <div class="question d-block">{!! $ques->question !!}</div>
        <p class="lead">Options:</p>
        <ul class="pl-3">
            <li>{!! $ques->option_a !!}</li>
            <li>{!! $ques->option_b !!}</li>
            <li>{!! $ques->option_c !!}</li>
            <li>{!! $ques->option_d !!}</li>
        </ul>
    </div>
    <div class="w-100"></div>
    @endforeach
</div>
@stop
