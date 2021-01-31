@extends('front.layouts.skeleton')

@section('content')
    <h1 class="page-title">Home</h1>
    <div class="row">
        <div class="col">
            <h3>Essays</h3>
            @if(!empty($essay))
            <h4>Date: {{ $essay->created_on->format('d-M-Y') }}</h4>
            <div class="text-left">
                {{ $essay->title }}
            </div>
            <div class="text-right">
                <a class="btn btn-primary" href="{{ route('front::essays-date', ['param' => $essay->created_on->format('Y-m-d')]) }}" role="button">View All</a>
            </div>
            @endif
        </div>
    </div>
    <div class="row border-top mt-2 pt-3">
        <div class="col">
            <h3>Questions</h3>
            @if(!empty($question))
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
            @endif
        </div>
    </div>
    <div class="row border-top mt-2 pt-3">
        <div class="col">
            <h4>CSE dates (est)</h4>
            <ul>
                <li>Sunday, June 3, 2022: Civil Services (Preliminary) Examination,</li>
                <li>Friday, September 28, 2022: Civil Services (Main) Examination</li>
                <li>Sunday, Febuary 10, 2023:  Personality Test Schedule for Civil Services (Main) Examination</li>
            </ul>
            <h4>Iaspirant dates</h4>
            <ul>
                <li>July 5, 2021: Prelims 2022 Mock Test Series</li>
            </ul>
        </div>
    </div>
@stop
