@extends('front.layouts.skeleton')

@section('content')
<h3 class="page-title">@ {{ $user_name }}'s Note</h3>
<div class="row">
    <div class="col-12">
        <p class="text-right">Total Notes: {{ $notes->count() }}</p>
        
        <?php $current_head = ''; ?>
        @foreach ($notes as $current_note)
            @if($current_note->sub != $current_head)
                @if(!empty($current_head)) </ul> @endif
                <?php $current_head = $current_note->sub; ?>

                <h5>{{ config('aspirant.subject_map')[$current_head] }}</h5>
                <ul>
            @endif
            <li>
                <a href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'show', 'id' => $current_note->id]) }}">{{ $current_note->note_title }}</a>
                @if(Gate::allows('pro-user') && $is_user_same)
                    @if($current_note->locked == 1) <i class="fa fa-user-secret" aria-hidden="true"></i> @endif
                    <a href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'controller' => 'notes', 'action' => 'create', 'id' => $current_note->id]) }}"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                @endif
            </li>
        @endforeach
    </div>
</div>
@if(Gate::allows('pro-user') && $is_user_same)
<div class="row">
    <div class="col-12 text-right">
        <a class="btn btn-primary" href="{{ route('front::three-notes', ['user_name' => auth()->user()->name, 'action' => 'create']) }}" role="button">New Note</a>
    </div>
</div>
@endif

@stop
