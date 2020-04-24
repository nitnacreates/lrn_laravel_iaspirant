@extends('super.layouts.skeleton')
@section('content')
<section class="content">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Theories</h3>
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
                            <option value="{{ route('super::theory', ['action' => 'show', 'sub' => $key])  }}"  @if($key == $sub) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="card-body p-0">
          @if(!empty($theories) && $theories->count())
            <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Account</th>
                      <th>Subject</th>
                      <th>Topic</th>
                      <th>Category</th>
                      <th>Weightage</th>
                      <th>Locked</th>
                      <th>Created On</th>
                    </tr>
                  </thead>
                  <tbody id="news_display">
                    <?php $counter = 1; ?>
                    @foreach ($theories as $theory)
                      <tr>
                        <td rowspan="2">{{ $counter++ }}</td>
                        <td>{{ ($theory->user_type == 's' ? 'Super' : 'Normal') }} - {{ $theory->account_id }}</td>
                        <td>{{ config('aspirant.subject_map')[$theory->sub] }}</td>
                        <td>{{ $theory->topic_id }}</td>
                        <td>@switch($theory->category)
                            @case('b') Both @break
                            @case('p') Prelims @break
                            @case('m') Mains @break
                        @endswitch</td>
                        <td>{{ $theory->weightage }}</td>
                        <td>{{ $theory->locked ? 'Yes' : 'No' }}</td>
                        <td>{{ $theory->created_on }}</td>
                      </tr>
                      <tr>
                          <td colspan="7"><a href="{{ route('super::theory', ['action' => 'stash', 'id' => $theory->id]) }}">{{ $theory->theory_title }}</a></td>
                      </tr>
                    @endforeach
                  </tbody>
              </table>
          @endif
        </div>
        <div class="card-footer text-right">
            <div class="btn-group">
                <a class="btn btn-block btn-primary" href="{{ route('super::theory', ['action' => 'stash'])  }}">Add theory</a>
            </div>
        </div>
    </div>
</section>
@stop
