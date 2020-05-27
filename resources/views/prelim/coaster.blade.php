@extends('front.layouts.skeleton')

@section('content')
<h1 class="page-title">Prelim Coaster</h1>
<div class="row">
    @foreach($questions as $ques)
        <div class="col ques-wrapper border-top">
            <p><strong>Question {{$ques->display_id}}:</strong></p>
            <div class="question d-block">{!! $ques->question !!}</div>
            <p class="lead">Options:</p>
            <ol type="A" class="question-options pl-3">
                <li><label class="form-check-label option-label-a" for="option-a"><span class="option-content">{!! $ques->option_a !!}</span></label>@if($ques->answer == 'a') <i class="fa fa-check text-success" aria-hidden="true"></i>@endif</li>
                <li><label class="form-check-label option-label-b" for="option-b"><span class="option-content">{!! $ques->option_b !!}</span></label>@if($ques->answer == 'b') <i class="fa fa-check text-success" aria-hidden="true"></i>@endif</li>
                <li><label class="form-check-label option-label-c" for="option-c"><span class="option-content">{!! $ques->option_c !!}</span></label>@if($ques->answer == 'c') <i class="fa fa-check text-success" aria-hidden="true"></i>@endif</li>
                <li><label class="form-check-label option-label-d" for="option-d"><span class="option-content">{!! $ques->option_d !!}</span></label>@if($ques->answer == 'd') <i class="fa fa-check text-success" aria-hidden="true"></i>@endif</li>
            </ol>
            <p><strong>Your Answer:</strong> {{ array_key_exists($ques->display_id, $user_answers) ? $user_answers[$ques->display_id] : '-' }}</p>
            <p class="text-success"><strong>Right Answer: </strong>{{ $ques->answer }}</p>
            <p class="lead">Explanation:</p>
            <div class="explanation d-block">{!! $ques->explanation !!}</div>
        </div>
        <div class="w-100"></div>
    @endforeach
</div>
<div class="row border-top border-bottom ">
    <div class="col mt-2">
        {{ $questions->appends(['mode' => 'view', 'roll' => $roll_id])->links() }}
    </div>
    <div class="col mt-2 text-right">
        <p><strong>Total Questions: </strong>{{ $questions->total() }}</p>
    </div>
</div>
@stop
