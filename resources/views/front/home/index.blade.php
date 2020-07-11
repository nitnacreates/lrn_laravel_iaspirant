@extends('front.layouts.skeleton')

@section('content')
    <h1 class="page-title">Home</h1>
    <div class="row">
        <div class="col">
            <h3>Essays</h3>
            <h4>Date: {{ $essay->created_on->format('d-M-Y') }}</h4>
            <div class="text-left">
                {{ $essay->title }}
            </div>
            <div class="text-right">
                <a class="btn btn-primary" href="{{ route('front::essays-date', ['param' => $essay->created_on->format('Y-m-d')]) }}" role="button">View All</a>
            </div>
        </div>
    </div>
    <div class="row border-top mt-2 pt-3">
        <div class="col">
            <h3>Questions</h3>
            <h4>Date: {{ $question->show_on->format('d-M-Y') }}</h4>
            <div class="question">
                {!! $question->question !!}
            </div>
            <p class="lead">Options:</p>
            <ol type="A" class="question-options pl-3">
                <li>{!! $question->option_a !!}</li>
                <li>{!! $question->option_b !!}</li>
                <li>{!! $question->option_c !!}</li>
                <li>{!! $question->option_d !!}</li>
            </ol>
            <div class="text-right">
                <a class="btn btn-primary" href="{{ route('front::news-date', ['param' => $question->show_on->format('Y-m-d')]) }}" role="button">View All</a>
            </div>
        </div>
    </div>
    <div class="row border-top mt-2 pt-3">
        <div class="col">
            <h4>CSE dates</h4>
            <ul>
                <li>Sunday, October 4, 2020: Civil Services Preliminary Exam</li>
                <li>Friday, Jaunuary 8, 2021: Civil Services Mains Exam</li>
                <li>Sunday, Febuary 28, 2021:  Indian Forest Service Mains Exam</li>
            </ul>
            <h4>Portal dates</h4>
            <ul>
                <li>July 7, 2020: Prelims 2021 Mock Test Series</li>
                <li>September 1, 2020: All India Prelim (2020 batch) Test Series</li>
                <li>October 6, 2020: Prelims 2021 Test Series</li>
            </ul>
        </div>
    </div>
@stop
