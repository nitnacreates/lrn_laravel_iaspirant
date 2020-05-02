@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card">
        <form role="form" method="get" action="{{ route('super::question', ['action' => 'show']) }}" autocomplete="off">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-university"></i> Search</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="submit" class="btn btn-block btn-success">Search</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" name="type">
                            <option value="">Pick</option>
                            @foreach(config('aspirant.question.types.front') as $key => $val)
                                <option value="{{ $key }}" @if(isset($current['type']) && ($key == $current['type'])) selected="selected" @endif>{{$val}}</option>
                            @endforeach
                            @foreach(config('aspirant.question.types.super') as $key => $val)
                                <option value="{{ $key }}" @if(isset($current['type']) && ($key == $current['type'])) selected="selected" @endif>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="form-control" name="sub" autocomplete="off">
                            <option value="">Pick</option>
                            @foreach(config('aspirant.subject_map') as $key => $val)
                                <option value="{{ $key }}" @if(isset($current['sub']) && ($key == $current['sub'])) selected="selected" @endif>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>User Type</label>
                        <select class="form-control" name="user_type" autocomplete="off">
                            <option value="">Pick</option>
                            @foreach(config('aspirant.user_types') as $key => $val)
                                <option value="{{ $key }}" @if(isset($current['user_type']) && ($key == $current['user_type'])) selected="selected" @endif>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="question" class="form-control" placeholder="Like ..." @if(isset($current['question'])) value="{{ $current['question'] }}" @endif />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Explanation</label>
                        <input type="text" name="explanation" class="form-control" placeholder="Like ..." @if(isset($current['explanation'])) value="{{ $current['explanation'] }}" @endif />
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-university"></i> Questions</h3>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10px">id</th>
                        <th>Type</th>
                        <th>Locked</th>
                        <th>Sub</th>
                        <th>Topic</th>
                        <th>Account</th>
                        <th>User Type</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody id="news_display">
                    @foreach ($questions as $question)
                    <tr>
                        <td rowspan="2" class="align-middle">{{ $question->id }}</td>
                        <td>{{ config('aspirant.question.types.front')[$question->type] || config('aspirant.question.types.super')[$question->type]}}</td>
                        <td>{{ $question->locked ? 'Yes' : 'No'}}</td>
                        <td>{{ config('aspirant.subject_map')[$question->sub] }}</td>
                        <td>{{ $question->topic_id}}</td>
                        <td>{{ $question->account_id}}</td>
                        <td>{{ config('aspirant.user_types')[$question->user_type]}}</td>
                        <td>{{ $question->created_at}}</td>
                        <td>{{ $question->updated_at}}</td>
                    </tr>
                    <tr>
                        <td colspan="8"><a href="{{ route('super::question', ['action' => 'stash', 'id' => $question->id])  }}">{{ $question->question }}</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="float-left">
                <div class="btn-group">
                    <a class="btn btn-block btn-primary" href="{{ route('super::question', ['action' => 'stash'])  }}">Add question</a>
                </div>
            </div>
            <div class="float-right">
                {{ $questions->appends($current)->links() }}
            </div>
        </div>
    </div>
</section>
@stop
