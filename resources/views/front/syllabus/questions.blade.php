@extends('front.layouts.skeleton')

@push('col_left_top')
@include('front.partials.syllabus')
@endpush

@section('content')
<h3 class="page-title">Topic Questions</h3>
<?php $ques_counter = 1; ?>
@foreach ($questions as $question)
<div class="card mb-3" data-ques="{{ $question->id }}">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <label>Question: {!! $ques_counter++ !!}</label>
            </div>
            <div class="col text-right">
                <button type="button" class="toggle-answer btn btn-sm btn-success">Toggle Answer</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <label>Question:</label>
                <div class="question">
                    {!! $question->question !!}
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col">
                <label>Options:</label>
                <div class="options">
                    <ol type="A">
                        <li @if('a' == $question->answer) data-right="1" @endif>{!! $question->option_a !!}</li>
                        <li @if('b' == $question->answer) data-right="1" @endif>{!! $question->option_b !!}</li>
                        <li @if('c' == $question->answer) data-right="1" @endif>{!! $question->option_c !!}</li>
                        <li @if('d' == $question->answer) data-right="1" @endif>{!! $question->option_d !!}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row answer-block collapse hide">
            <div class="col">
                <label>Answer: </label>&nbsp;&nbsp;<span class="answer text-success">{!! $question->answer !!}</span>
            </div>
            <div class="w-100"></div>
            <div class="col">
                <label>Explanation: </label>
                <div class="explanation">{!! $question->explanation !!}</div>
            </div>
        </div>
    </div>
</div>
@endforeach
@stop
