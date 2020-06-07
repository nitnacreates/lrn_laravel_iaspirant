@extends('super.layouts.skeleton')

@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-graduation-cap"></i> Update User</h3>
            </div>
            <form role="form" id="stash_form" method="post" action="{{ route('super::users', ['action' => 'stash']) }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $user->id}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Display Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" />
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" />
                </div>
                <div class="form-group">
                    <label>Subscription</label>
                    <select class="form-control" name="subscription" autocomplete="off">
                        @foreach(config('aspirant.user_subscription') as $key => $val)
                            <option value="{{ $key }}" @if($key == $user->subscription)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
                <hr />
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="{{ $user->user_keep->first_name }}" />
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ $user->user_keep->last_name }}" />
                </div>
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->user_keep->phone }}" />
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="row">
                        <div class="col-3">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="male" name="gender" value="m" @if($user->user_keep->gender == "m")) checked="checcked" @endif />
                                <label for="male" class="custom-control-label">Male</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="female" name="gender" value="f" @if($user->user_keep->gender == "f")) checked="checcked" @endif />
                                <label for="female" class="custom-control-label">Female</label>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="text" name="dob" class="form-control" value="{{ $user->user_keep->dob }}" />
                </div>
                <div class="form-group">
                    <label>Highest Qualification</label>
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control" name="qualification_type" autocomplete="off">
                                @foreach(config('aspirant.user.qualification_type') as $key => $val)
                                    <option value="{{ $key }}" @if($key == $user->user_keep->qualification_type)) selected="selected" @endif>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="qualification" placeholder="Qualification name" value="{{$user->user_keep->qualification}}">
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label>Previous Attempts</label>
                    <select class="form-control" name="total_attempts" autocomplete="off">
                        @for($i = 0; $i<=7; $i++)
                            <option value="{{ $i }}" @if($i == $user->user_keep->total_attempts)) selected="selected" @endif>{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <label>Highest stage reached</label>
                   <select class="form-control" name="max_stage" autocomplete="off">
                        @foreach(config('aspirant.user.upsc_stage') as $key => $val)
                            <option value="{{ $key }}" @if($key == $user->user_keep->max_stage)) selected="selected" @endif>{{$val}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <a href="javascript:history.go(-1)" class="btn btn-block btn-primary">Back</a>
                </div>
                <div class="btn-group">
                    <button type="submit" name="mode" value="{{$mode == 'edit' ? 'edit' : $mode}}" class="btn btn-block btn-success">Save</button>
                </div>
            </div>
            </form>
        </div>
    </section>
@stop
