<?php

namespace App\Model\Comment;

use Illuminate\Database\Eloquent\Model;

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

    public function pageComments($page_link)
    {
        $comments = $this->fetchComments($page_link);

        $crr = array();
        foreach ($comments as $cmt) {
            if($cmt->thread_id == 0){
                $crr[$cmt->id] = [
                    'account' => $cmt->account_id,
                    'comment' => $cmt->comment,
                    'user_type' => $cmt->user_type,
                    'user_name' => $cmt->user_name,
                ];                
            } else{
                if(array_key_exists($cmt->thread_id, $crr)){
                    $crr[$cmt->thread_id]['child'][] = [
                        'account' => $cmt->account_id,
                        'comment' => $cmt->comment,
                        'user_type' => $cmt->user_type,
                        'user_name' => $cmt->user_name,
                    ];
                }
            }
        }
        return $crr;
    }
}
