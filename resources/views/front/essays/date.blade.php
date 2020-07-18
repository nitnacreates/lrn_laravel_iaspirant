@extends('front.layouts.skeleton')

@section('content')
<h2>Essays</h2>

<?php
    $essay_titles = [];
?>
@foreach ($essays as $essay)
    <?php 
        $essay_titles[$essay->id] = $essay->title;
        $response_status = 0;
        $countdown_timer = "23:59:59";
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
                if($response->account_id == $account_id){
                    $response_status = 1;
                    $countdown_timer = FrontHelper::countdownto24($response->created_at);
                    if(!$countdown_timer){
                        $countdown_timer = "00:00:00";
                    }
                }
            } else{
                $essay_pc['completed'][] = [
                    'account_id' => $response->account_id,
                    'name' => $response->name,
                ];
                if($response->account_id == $account_id){
                    $response_status = 2;
                    $countdown_timer = "00:00:00";
                }
            }
        }
    ?>
<div class="row pb-4 border-bottom essay-box" data-id="{{ $essay->id }}" data-countdown="{{ $response_status == 1 ? "1" : "0" }}">
    <div class="col">
        <p class="lead"><i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i> {{ $essay->title }}</p>
        
        <div class="essay-stats">
            <dl class="row">
                <dt class="col-sm-3">Word Limit: </dt>
                <dd class="col-sm-9">{{ $essay->word_limit }}</dd>
                <dt class="col-sm-3">Picked By: </dt>
                <dd class="col-sm-9 essay-pickers">
                    <?php $pick_counter = 0; ?>
                    @foreach($essay_pc['picked'] as $picked)
                        @if($pick_counter++), @endif
                        @if($picked['account_id'] == $account_id)
                            <span class="active">{{ $picked['name'] }}</span>
                        @else
                            {{$picked['name']}}
                        @endif
                    @endforeach
                </dd>
                <dt class="col-sm-3">Completed By: </dt>
                <dd class="col-sm-9 essay-finishers">
                    <?php $complete_counter = 0; ?>
                    @foreach($essay_pc['completed'] as $completed)
                        @if($complete_counter++), @endif
                        <a href="{{ route('front::three-essay-response', ['user_name' => $completed['name'], 'action' => 'show', 'id' => $essay->id]) }}">
                            @if($completed['account_id'] == $account_id)
                                <span class="active">{{ $completed['name'] }}</span>
                            @else
                                {{ $completed['name'] }}
                            @endif
                        </a>
                    @endforeach
                </dd>
            </dl>
        </div>
    </div>
    @if(Gate::any(['penman-user', 'pro-user']))
    <div class="w-100"></div>
    <div class="col">
        <div class="row essay-action">
            <div class="col-6">
                <button type="button" class="btn btn-primary btn-sm btn-pick">Pick</button>
                <a href="{{ route('front::three-essay-response', ['user_name' => auth()->user()->name, 'action' => 'create', 'id' => $essay->id]) }}" class="btn btn-primary btn-sm">Write</a>
                <a @if($response_status == 2) href="{{ route('front::three-essay-response', ['user_name' => auth()->user()->name, 'action' => 'show', 'id' => $essay->id]) }}" title="Essay completed" @else href="javascript:void(0)" title="Essay not completed" @endif class="btn btn-primary btn-sm">See</a>
            </div>
            <div class="col-6 text-center essay-timer">{{ $countdown_timer }}</div>
        </div>
    </div>
    @endif
</div>
@endforeach

<section>
    <h1>Completed Essays</h1>
    @if(Gate::any(['penman-user', 'pro-user']))
        @foreach($essay_responses as $response)
        <div class="row user-essays border-bottom py-4">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <strong>Essay:</strong> {{ $essay_titles[$response->essay_id] }} <br/>
                        <strong>By:</strong> <a href="{{ route('front::three-essay-response', ['user_name' => $response->name, 'action' => 'show', 'id' => $response->essay_id]) }}"> {{ $response->name }} </a><br/>
                        <div class="essay_content">
                            {!! $response->response_content !!}
                        </div> 
                    </div>
                </div>
                @include('front.essay_response.user_reactions', [
                    'response_id' => $response->id,
                    'essay_reactions' => $response->essay_reactions,
                    'grouped_essay_reactions' => $response->grouped_essay_reactions
                ])
            </div>
        </div>
        @endforeach
    @else
        <p>You should see essays here if you are subscribed to {{ config('aspirant.user_subscription')['15'] . " or ". config('aspirant.user_subscription')['20'] }} plans</p>
    @endif
</section>

@stop
