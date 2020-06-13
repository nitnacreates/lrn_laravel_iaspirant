<section id="comments">
    <div class="row">
        <div class="col-12"><h3>Comments</h3></div>
    </div>
    @if(Gate::allows('prelim-user'))
    <div class="form-group row comment-box border-right">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="replying-to d-none">
                        <p><strong>Replying To Message: </strong><span id="reply-to"></span></p>
                        <div class="reply-for-message"></div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="comment_path" value="{{ Request::path() }}">
                        <input type="hidden" name="comment_thread" value="0">
                        <input type="hidden" name="to_account" value="0">
                        <textarea class="form-control user-comment" rows="2"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-left">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="float-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-danger clear-content">Cancel</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-primary comment-submit">Comment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col">
            <p>{{ config('aspirant.view_msg.comment_box') }}</p>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col">
            <ul class="list-unstyled">
            @foreach($page_comments as $c_id => $c_arr)
                <li data-id="{{ $c_id }}" class="parent-comment @if($c_arr['user_type'] == 's') highlighted @endif">
                    <div class="row">
                        <div class="col-12 user-to-comment">
                            {{ $c_arr['comment'] }}
                        </div>
                        <div class="w-100"></div>
                        <div class="col-6">
                            <span class="user-to-name border-bottom" data-user="{{ $c_arr['account_id'] }}">{{ $c_arr['user_name'] }}</span>
                        </div>
                        <div class="col-6 text-right">
                            <div class="btn-group user-response">
                                <label class="btn btn-light send-reply">
                                    <i class="fa fa-reply" aria-hidden="true"></i>
                                </label>
                                <label data-resp="1" class="btn btn-light user-reaction">
                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="total-likes reaction-counter">{{ $c_arr['likes'] ?: "" }}</span>
                                </label>
                                <label data-resp="2" class="btn btn-light user-reaction">
                                    <i class="fa fa-thumbs-down" aria-hidden="true"></i> <span class="total-dislikes reaction-counter">{{ $c_arr['dislikes'] ?: "" }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if(array_key_exists('child', $c_arr))
                        <ul class="list-unstyled pl-3">
                            @foreach($c_arr['child'] as $ch_id => $ch_arr)
                                <li data-id="{{ $ch_id }}" class="child-comment @if($ch_arr['user_type'] == 's') highlighted @endif">
                                    <div class="row">
                                        <div class="col-12 user-to-comment">
                                            {{ $ch_arr['comment'] }}
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="col-6">
                                            <span class="user-to-name border-bottom" data-user="{{ $ch_arr['account_id'] }}">{{ $ch_arr['user_name'] }}</span>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="btn-group user-response">
                                                <label class="btn btn-light send-reply">
                                                    <i class="fa fa-reply" aria-hidden="true"></i>
                                                </label>
                                                <label data-resp="1" class="btn btn-light user-reaction">
                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i> <span class="total-likes reaction-counter">{{ $ch_arr['likes'] ?: "" }}</span>
                                                </label>
                                                <label data-resp="2" class="btn btn-light user-reaction">
                                                    <i class="fa fa-thumbs-down" aria-hidden="true"></i> <span class="total-dislikes reaction-counter">{{ $ch_arr['dislikes'] ?: "" }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div>
</section>
