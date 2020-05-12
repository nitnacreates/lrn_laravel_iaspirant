<section class="comments">
    <div class="row">
        <div class="col-12"><h3>Comments</h3></div>
    </div>
    @if(Gate::allows('account-holder'))
    <div class="form-group row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-left">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="float-right">
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-danger" href="javascript:void(0)">Cancel</a>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-sm btn-outline-primary" href="javascript:void(0)">Comment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <ul>
            @foreach($page_comments as $c_id => $c_arr)
                <li data-id="{{ $c_id }}" @if($c_arr['user_type'] == 's') class="highlighted" @endif>
                    <div class="row">
                        <div class="col-6">
                            <span class="user">{{ $c_arr['user_name'] }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group">
                                <label class="btn btn-light">
                                    <i class="fa fa-reply" aria-hidden="true"></i>
                                </label>
                                <label class="btn btn-light">
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i> 13
                                </label>
                                <label class="btn btn-light">
                                    <i class="fa fa-thumbs-down" aria-hidden="true"></i> 10
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            {{ $c_arr['comment'] }}
                        </div>
                    </div>
                    @if(array_key_exists('child', $c_arr))
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-11">
                            <ul>
                                @foreach($c_arr['child'] as $ch_id => $ch_arr)
                                    <li data-id="{{ $ch_id }}" @if($ch_arr['user_type'] == 's') class="highlighted" @endif>
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="user">{{ $ch_arr['user_name'] }}</span>
                                            </div>
                                            <div class="col-6 text-right">
                                                <div class="btn-group">
                                                    <label class="btn btn-light">
                                                        <i class="fa fa-reply" aria-hidden="true"></i>
                                                    </label>
                                                    <label class="btn btn-light">
                                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i> 13
                                                    </label>
                                                    <label class="btn btn-light">
                                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i> 10
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                {{ $ch_arr['comment'] }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div>
</section>
