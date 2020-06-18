@extends('front.layouts.skeleton')

@section('content')
<h2>News</h2>

@foreach ($news_show->news as $cn)
<div class="row mt-4 border-bottom {{ $cn->type == 'dn' ? 'news-brick' : 'news-editorial' }}">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h3 class="text-primary"><a href="{{ route('front::news-show', ['param' => $cn->news_link]) }}" >{{ $cn->news_title }}</a></h3>
                <div class="news-area">
                    {!! $cn->news_content !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <div class="text-muted">
                    <p class="text-sm"><b>Sub: </b>{{ config('aspirant.subject_map')[$cn->sub] }}</p>
                    <p class="text-sm"><b>Topic: </b>@include('front.partials.topic_hier', ['topic_arr' => $topic_arr, 'topic_id' => $cn->topic_id])</p>
                    <p class="text-sm"><b>Weightage: </b>@include('front.partials.weightage_star', ['weight' => $cn->weightage, 'total' => 5]) </p>
                    <p class="text-sm"><b>Category: </b>@switch($cn->category)
                        @case('b') Prelims+Mains @break
                        @case('p') Prelims @break
                        @case('m') Mains @break
                        @endswitch
                    </p>
                </div>
            </div>
            <div class="col-2">
                @if(Gate::allows('full-user'))
                    <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}" role="button">Create Note</a>
                @else
                    <button type="button" class="btn btn-default">Create Note</button>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endforeach

<h2>Questions</h2>
<div class="row" id="daily-questions">
    <?php $counter = 1; ?>
    @foreach($questions as $ques)
        <div class="col ques-wrapper border-top" id="question-block-{{ $ques->id }}" data-ques="{{ $ques->id }}">
            <p><strong>Question {{$counter++}}:</strong></p>
            <div class="question d-block">{!! $ques->question !!}</div>
            <p class="lead">Options:</p>
            <ol type="A" class="question-options pl-3">
                <li>
                    <div class="form-check option-container">
                        <input class="form-check-input" autocomplete="off" type="radio" name="q_option_{{ $ques->id }}" id="option-a-{{ $ques->id }}" value="a" />
                        <label class="form-check-label" for="option-a-{{ $ques->id }}">
                            <span class="option-content">{!! $ques->option_a !!}</span>
                        </label>
                    </div>
                </li>
                <li>
                    <div class="form-check option-container">
                        <input class="form-check-input" autocomplete="off" type="radio" name="q_option_{{ $ques->id }}" id="option-b-{{ $ques->id }}" value="b" />
                        <label class="form-check-label" for="option-b-{{ $ques->id }}">
                            <span class="option-content">{!! $ques->option_b !!}</span>
                        </label>
                    </div>
                </li>
                <li>
                    <div class="form-check option-container">
                        <input class="form-check-input" autocomplete="off" type="radio" name="q_option_{{ $ques->id }}" id="option-c-{{ $ques->id }}" value="c" />
                        <label class="form-check-label" for="option-c-{{ $ques->id }}">
                            <span class="option-content">{!! $ques->option_c !!}</span>
                        </label>
                    </div>
                </li>
                <li>
                    <div class="form-check option-container">
                        <input class="form-check-input" autocomplete="off" type="radio" name="q_option_{{ $ques->id }}" id="option-d-{{ $ques->id }}" value="d" />
                        <label class="form-check-label" for="option-d-{{ $ques->id }}">
                            <span class="option-content">{!! $ques->option_d !!}</span>
                        </label>
                    </div>
                </li>
            </ol>
            <div class="explanation-block d-none">
                <p class="lead">Explanation:</p>
                <div class="question-explanation"></div>
            </div>
        </div>
        <div class="w-100"></div>
    @endforeach
</div>
<div class="row border-top border-bottom py-3">
    <div class="col-6">
        <p><strong>Avg: </strong>{{$avg}}/10 based on {{$total_users}} users &nbsp;&nbsp;&nbsp;&nbsp;<strong>Your score: </strong><span id="your-score">{{ $user_score }}</span></p>
    </div>
    <div class="col-6 text-right">
        <input type="hidden" id="news_date" value="{{ $date }}" />
        <button type="button" id="today-answers" class="btn btn-sm btn-success">Submit</button>
    </div>
</div>
@include('front.partials.comments', $page_comments)
@stop
