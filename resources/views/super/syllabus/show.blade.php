@extends('super.layouts.skeleton')

@section('content')
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="fa fa-book"></i> {{ $sub_title }}</h3>
            <div class="card-tools">
              <div class="row">
                <div class="col-sm-6">
                  <!-- select -->
                  <div class="form-group">
                    <select class="form-control" name="subject_switch" autocomplete="off">
                      <option>Optional Subjects</option>
                      @foreach(config('aspirant.optional_map') as $key => $val)
                          <option value="{{$key}}" data-url="{{ route('super::syllabus', ['action' => 'show', 'sub' => $key])  }}"  @if($key == $sub) selected="selected" @endif>{{$val}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <select class="form-control" name="subject_switch" autocomplete="off">
                      <option>Subjects</option>
                      @foreach(config('aspirant.subject_map') as $key => $val)
                          <option value="{{$key}}" data-url="{{ route('super::syllabus', ['action' => 'show', 'sub' => $key])  }}"  @if($key == $sub) selected="selected" @endif>{{$val}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>


            </div>
        </div>
        <div class="card-body">
            @include('super.partials.syllabus', array('topics_arr' => $topics_arr))
        </div>
        <div class="card-footer text-right">
            <div class="btn-group">
                <a class="btn btn-block btn-outline-primary" href="{{ route('super::syllabus', ['action' => 'stash', 'sub' => $sub])  }}">Add base syllabus</a>
            </div>
        </div>
      </div>
    </section>
@stop
