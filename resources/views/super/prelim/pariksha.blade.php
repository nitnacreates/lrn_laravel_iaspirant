@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card card-warning">
        <div class="card-header">
            <h2 class="card-title">Test Number: {{ $pid }}</h2>
            <div class="card-tools">
                <div class="btn-group">
                    <a class="btn btn-block btn-default btn-sm" href="{{ route('super::prelim', ['action' => 'pariksha-stash', 'pid' => $pid])  }}">Add question</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5>Total Questions: {{ $questions->count() }}</h5>
        </div>
    </div>
    <hr/>
    @foreach ($questions as $question)
        <div class="card <?php $cards = ['card-primary', 'card-success', 'card-warning', 'card-danger']; echo $cards[array_rand($cards)]; ?> card-outline">
            <div class="card-header">
                <h4 class="card-title">Question Id: {{ $question->display_id }}</h4>
                <div class="card-tools">
                    <div class="btn-group">
                        <a class="btn btn-block btn-secondary btn-sm" href="{{ route('super::prelim', ['action' => 'pariksha-stash', 'pid' => $pid, 'qid' => $question->id])  }}">Edit Question</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label>Question:</label>
                        <div class="question">
                            {!! $question->question !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Options:</label>
                        <div class="options">
                            <ul>
                                <li @if('a' == $question->answer) class="text-danger" @endif>{!! $question->option_a !!}</li>
                                <li @if('b' == $question->answer) class="text-danger" @endif>{!! $question->option_b !!}</li>
                                <li @if('c' == $question->answer) class="text-danger" @endif>{!! $question->option_c !!}</li>
                                <li @if('d' == $question->answer) class="text-danger" @endif>{!! $question->option_d !!}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Answer:</label>
                        <div class="answer text-danger">
                            {!! $question->answer !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Explanation</label>
                        <div class="explanation">{!! $question->explanation !!}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Secret Text</label>
                        <div class="secret_text">{!! $question->secret_text !!}</div>              
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-6">
                        <label>Question Reference</label>
                        <div class="question_ref">{{ $question->ques_ref_id }}</div>
                    </div>
                    <div class="col-6">
                        <label>Weightage</label>
                        <div class="weightage">{{ $question->weightage }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Subject</label>
                        <div class="subject">{{ config('aspirant.subject_map')[$question->sub] }}</div>
                    </div>
                    <div class="col-6">
                        <label>Topic</label>
                        <div class="topic">{{ $question->topic_id }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</section>
@stop
