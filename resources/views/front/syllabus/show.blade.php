@extends('front.layouts.skeleton')

@push('col_left_top')
@include('front.partials.syllabus')
@endpush

@section('content')
<h1 class="page-title">Syllabus</h1>
<div class="row">
    <div class="col-12">
        <form>
            <div class="form-group">
                <label for="syllabusSelect">Subject</label>
                <select class="form-control" id="syllabusSelect">
                    @foreach(config('aspirant.subject_map') as $key => $val)
                        <option value="{{ $key }}" data-url="{{ route('front::syllabus-show', ['param' => $key]) }}" @if($key == $sub) selected="selected" @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col">
        @include('front.syllabus.sub_loop', ['topics_arr' => $topics_arr])
    </div>
</div>
@stop
