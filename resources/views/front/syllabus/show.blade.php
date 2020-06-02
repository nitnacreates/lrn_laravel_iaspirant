@extends('front.layouts.skeleton')

@push('col_left_top')
@include('front.partials.syllabus')
@endpush

@section('content')
<h3 class="page-title">Syllabus</h3>
<div class="row">
    <div class="col-12">
        <form>
            <div class="form-group">
                <label for="syllabusSelect">Subject</label>
                <select class="form-control" id="syllabusSelect">
                    @foreach(config('aspirant.subject_map') as $key => $val)
                        <option value="{{ $key }}" data-url="{{ route('front::syllabus', ['action' => 'show', 'sub' => $key]) }}" @if($key == $sub) selected="selected" @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@stop
