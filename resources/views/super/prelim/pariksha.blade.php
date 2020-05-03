@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Test Number: {{ $pid }}</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a class="btn btn-block btn-primary" href="{{ route('super::prelim', ['action' => 'pariksha-stash', 'pid' => $pid])  }}">Add question</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5>Total Questions: {{ $questions->count() }}</h5>
        </div>
    </div>
    @foreach ($questions as $question)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Question Id: {{ $question->display_id }}</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a class="btn btn-block btn-primary" href="{{ route('super::prelim', ['action' => 'pariksha-stash', 'pid' => $pid, 'qid' => $question->id])  }}">Edit Question</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h4>{{ $question->question }}</h4>
            </div>
        </div>
    @endforeach
</section>
@stop
