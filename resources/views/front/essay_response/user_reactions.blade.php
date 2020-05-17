<?php
    $score_counter = $total_score = 0;

    $user_reaction = null;

    $reaction_type = [
        '1' => 0,
        '2' => 0,
        '3' => 0
    ];
    foreach ($grouped_essay_reactions as $grouped_reaction) {
        $reaction_type[$grouped_reaction->type] = $grouped_reaction->type_counter;
    }
?>
<div class="row border mt-3 reaction-section" data-response="{{ $response_id }}">
    <div class="col-6 border-right">
        <legend>Comments</legend>
        <ul class="list-group list-group-flush user-comments">
            @foreach($essay_reactions as $curr_reaction)
            <?php 
                if(!empty($curr_reaction->score)){
                    $score_counter++;
                    $total_score += intval($curr_reaction->score);

                    if($curr_reaction->account_id == Session::get('account_id')){
                        $user_reaction = $curr_reaction;
                    }
                }

                if(empty($curr_reaction->comment)) continue;

            ?>
            <li class="list-group-item bg-transparent @if($curr_reaction->account_id == Session::get('account_id')) active @endif">{{ $curr_reaction->comment }}</li>
            @endforeach
        </ul>
    </div>
    <div class="col-6">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Reactions</label>
            <div class="col-sm-8 col-form-label reaction-motion">
                <button value="1" class="px-4 btn btn-sm {{ (!empty($user_reaction) && $user_reaction->type == '1') ? 'btn-outline-success' : 'btn-outline-secondary'  }}" type="button"><i class="fa fa-thumbs-up" aria-hidden="true" title="like"></i> <span class="total-likes reaction-counter">{{ $reaction_type['1'] }}</span></button>
                <button value="2" class="px-4 btn btn-sm {{ (!empty($user_reaction) && $user_reaction->type == '2') ? 'btn-outline-success' : 'btn-outline-secondary'  }}" type="button"><i class="fa fa-thumbs-down" aria-hidden="true" title="dislike"></i> <span class="total-dislikes reaction-counter">{{ $reaction_type['2'] }}</span></button>
                <button value="3" class="px-4 btn btn-sm {{ (!empty($user_reaction) && $user_reaction->type == '3') ? 'btn-outline-success' : 'btn-outline-secondary'  }}" type="button"><i class="fa fa-heart" aria-hidden="true" title="save"></i> <span class="total-saved reaction-counter">{{ $reaction_type['3'] }}</span></button>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Mean Score</label>
            <div class="col-sm-8 col-form-label mean-score">
                @if($score_counter)
                    {{ ceil($total_score / $score_counter) }} / 10
                @else
                    - / 10
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Your Score</label>
            <div class="col-sm-8 your-score">
                <select class="form-control your-score" autocomplete="off">
                    @for ($i = 1; $i < 11; $i++)
                        <option value="{{$i}}" @if(!empty($user_reaction) && $user_reaction->score == $i) selected="selected" @endif>{{$i}}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group row your-comment">
            <div class="col">
                <label>Comment</label>
                <textarea autocomplete="off" class="form-control" maxlength="150" rows="2">@if(!empty($user_reaction->comment)) {{ $user_reaction->comment }} @endif</textarea>
            </div>
            <div class="w-100"></div>
            <div class="col text-right pt-3">
                <button type="button" class="btn btn-primary btn-sm">Comment</button>
            </div>
        </div>
    </div>
</div>
