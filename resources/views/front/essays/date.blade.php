@extends('front.layouts.skeleton')

@section('content')
<h2>Essays</h2>

@foreach ($essays as $essay)
    <?php 
        $essay_pc = [
            'picked' => [],
            'completed' => []
        ]; 
        foreach ($essay->essay_response as $response) {
            if(empty($response->completed_on)){
                $essay_pc['picked'][] = [
                    'account_id' => $response->account_id,
                    'name' => $response->name,
                ];
            } else{
                $essay_pc['completed'][] = [
                    'account_id' => $response->account_id,
                    'name' => $response->name,
                ];
            }
        }
    ?>
<div class="row pb-4 border-bottom essay-box" data-id="{{ $essay->id }}">
    <div class="col">
        <p class="lead"><i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i> {{ $essay->title }}</p>
        
        <div class="essay-stats">
            <div class="pt-2">
                <label>Picked By:</label>
                <ul id="essay-pickers">
                @foreach($essay_pc['picked'] as $picked)
                    <li>{{ $picked['name'] }}</li>
                @endforeach
                </ul>
            </div>
            <div class="pt-2">
                <label>Completed By:</label>
                <ul id="essay-finishers">
                @foreach($essay_pc['completed'] as $completed)
                    <li><a href="{{ route('front::essays', ['action' => 'show', 'essay' => $essay->id, 'user' => $completed['account_id']]) }}">{{ $completed['name'] }}</a></li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @if(Gate::allows('full-user'))
    <div class="w-100"></div>
    <div class="col">
        <div class="row essay-action">
            <div class="col-6">
                <button type="button" class="btn btn-primary btn-sm btn-pick">Pick</button>
                <a href="{{ route('user::essays', ['action' => 'write', 'id' => $essay->id]) }}" class="btn btn-primary btn-sm">Write</a>
                <a href="{{ route('user::essays', ['action' => 'show', 'id' => $essay->id]) }}" class="btn btn-secondary btn-sm">See</a>
            </div>
            <div class="col-6 text-center essay-timer">23:59:59</div>
        </div>
    </div>
    @endif
</div>
@endforeach
@stop
