<?php

namespace App\Model\Comment;

use Illuminate\Database\Eloquent\Model;
use App\Model\Comment\CommentReaction;

class Comment extends Model
{
    protected $fillable = ['id'];

    protected function fetchComments($page_link)
    {
        return Comment::where('page_link', $page_link)
            ->select(['comments.id as id', 'comments.account_id', 'comments.comment', 'comments.thread_id', 'accounts.user_type', \DB::raw("IFNULL(users.name, 'moderator') AS 'user_name'") ])
            ->join('accounts', 'comments.account_id', 'accounts.id')
            ->leftJoin('users', function ($join) {
                $normal_user = 'n';
                $join->on('accounts.all_id', '=', 'users.id')
                        ->where('accounts.user_type', '=', "$normal_user");
            })
            ->where('comments.page_link', $page_link)
            ->orderBy('comments.thread_id', 'ASC')
            ->orderBy('comments.created_at', 'ASC')
            ->get(); 
    }

    protected function fetchCommentReactions($comment_ids)
    {
        $reaction_count = CommentReaction::select('comment_id', 'reaction', \DB::raw("count(reaction) as total_reaction"))
                                ->whereIn('comment_id', $comment_ids)
                                ->groupBy('comment_id')
                                ->groupBy('reaction')
                                ->get();
        $react_arr = [];
        foreach ($reaction_count as $rct) {
            $reaction_type = ($rct->reaction == 1 ? 'like' : 'dislike');
            $react_arr[$rct->comment_id][$reaction_type] = $rct->total_reaction;
        }

        return $react_arr;
    }

    public function pageComments($page_link)
    {
        $comments = $this->fetchComments($page_link);

        $comment_ids = [];
        foreach ($comments as $cmt) {
            array_push($comment_ids, $cmt->id);
        }
        $comment_reactions = $this->fetchCommentReactions($comment_ids);

        $crr = array();
        foreach ($comments as $cmt) {
            if($cmt->thread_id == 0){
                $crr[$cmt->id] = [
                    'account_id' => $cmt->account_id,
                    'comment' => $cmt->comment,
                    'user_type' => $cmt->user_type,
                    'user_name' => $cmt->user_name,
                    'likes' => $comment_reactions[$cmt->id]['like'] ?? 0,
                    'dislikes' => $comment_reactions[$cmt->id]['dislike'] ?? 0
                ];                
            } else{
                if(array_key_exists($cmt->thread_id, $crr)){
                    $crr[$cmt->thread_id]['child'][$cmt->id] = [
                        'account_id' => $cmt->account_id,
                        'comment' => $cmt->comment,
                        'user_type' => $cmt->user_type,
                        'user_name' => $cmt->user_name,
                        'likes' => $comment_reactions[$cmt->id]['like'] ?? 0,
                        'dislikes' => $comment_reactions[$cmt->id]['dislike'] ?? 0
                    ];
                }
            }
        }
        return $crr;
    }
}
