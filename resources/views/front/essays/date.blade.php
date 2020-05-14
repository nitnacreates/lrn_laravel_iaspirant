@extends('front.layouts.skeleton')

@section('content')
<h2>Essays</h2>

@foreach ($essays as $essay)
    <?php 
        $response_status = 0;
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
                }
            } else{
                $essay_pc['completed'][] = [
                    'account_id' => $response->account_id,
                    'name' => $response->name,
                ];
                if($response->account_id == $account_id){
                    $response_status = 2;
                }
            }
        }
    ?>
<div class="row pb-4 border-bottom essay-box" data-id="{{ $essay->id }}">
    <div class="col">
        <p class="lead"><i class="fa fa-caret-right" aria-hidden="true"></i><i class="fa fa-caret-right" aria-hidden="true"></i> {{ $essay->title }}</p>
        
        <div class="essay-stats">
            <dl class="row">
                <dt class="col-sm-3">Picked By: </dt>
                <dd class="col-sm-9" id="essay-pickers">
                    <?php $pick_counter = 0; ?>
                    @foreach($essay_pc['picked'] as $picked)
                        @if($pick_counter++), @endif
                        @if($picked['account_id'] == $account_id)
                            <span class="active">{{ $picked['name'] }}</span>
                        @else
                            {{ $picked['name'] }}
                        @endif
                    @endforeach
                </dd>
                <dt class="col-sm-3">Completed By: </dt>
                <dd class="col-sm-9" id="essay-finishers">
                    <?php $complete_counter = 0; ?>
                    @foreach($essay_pc['completed'] as $completed)
                        @if($complete_counter++), @endif
                        <a href="{{ route('front::essays', ['action' => 'show', 'essay' => $essay->id, 'user' => $completed['name']]) }}">
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
    @if(Gate::allows('full-user'))
    <div class="w-100"></div>
    <div class="col">
        <div class="row essay-action">
            <div class="col-6">
                <button type="button" class="btn btn-primary btn-sm btn-pick @if($response_status) disabled @endif">Pick</button>
                <a href="{{ route('user::essays', ['action' => 'write', 'id' => $essay->id]) }}" class="btn btn-primary btn-sm">Write</a>
                <a @if($response_status == 2) href="{{ route('user::essays', ['action' => 'show', 'id' => $essay->id]) }}" title="Essay completed" @else href="javascript:void(0)" title="Essay not completed" @endif class="btn btn-primary btn-sm @if($response_status == 1) disabled @endif ">See</a>
            </div>
            <div class="col-6 text-center essay-timer">23:59:59</div>
        </div>
    </div>
    @endif
</div>
@endforeach
@stop
