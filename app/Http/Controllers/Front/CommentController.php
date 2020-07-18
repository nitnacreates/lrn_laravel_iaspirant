<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Comment\Comment;
use App\Model\Comment\CommentReaction;
use App\Model\Message\Message;

class CommentController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'comment');
        view()->share('pagetitle', 'Comments');
        $this->request = $request;
    }

    public function populate()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'user_comment':
                    /*
                    * TODO: If too frequest, create a check based on created at
                    */
                    if(\Gate::any(['free-user'])){
                        $user_comment = $this->request->input('cmtx');
                        $page_link = $this->request->input('cmpt');
                        $resp = \FrontHelper::checkRoute($page_link);
                        $thread = intval($this->request->input('thread'), 0);
                        $to_acc = intval($this->request->input('toacc'), 0);

                        if(!$resp){
                            return response()->json(['message' => 'An error occurred'], 404);
                        }
                        if($thread){
                            $parent_comment = Comment::where('page_link', $page_link)->where('id', $thread)->first();
                            if(empty($parent_comment)){
                                return response()->json(['message' => 'An error occurred'], 404);
                            }
                        }

                        $comment = new Comment;
                        $comment->page_link = $page_link;
                        $comment->account_id = \UserHelper::getAccountId();
                        $comment->comment = $user_comment;
                        $comment->moderated = 1;    // no check currently
                        $comment->thread_id = $thread;
                        $comment->save();

                        if($to_acc){
                            $message = new Message;
                            $msg_data = [
                                'from_account_id' => \UserHelper::getAccountId(), 
                                'to_account_id' => $to_acc,
                                'type' => config('aspirant.notification.type')['comment'],
                                'message' => 'Your comment received a new reply',
                                'link' => $page_link
                            ];
                            $message->createNotification($msg_data);
                        }
                        return response()->json(['message' => 1]);
                    } else{
                        return response()->json(['message' => 'Forbidden'], 403);
                    }
                    break;
                case 'user_reaction':
                    if(\Gate::any(['free-user'])){
                        $comment_id = intval($this->request->input('cid'), 0);
                        $reaction_type = $this->request->input('rt');
                        
                        $page_link = $this->request->input('cmpt');
                        $resp = \FrontHelper::checkRoute($page_link);
                        if(!$resp){
                            return response()->json(['message' => 'An error occurred'], 404);
                        }

                        if($comment_id && ($reaction_type == 1) || ($reaction_type == 2)){
                            $comment = Comment::find($comment_id);
                            if(!empty($comment)){
                                $create_notification = 1;
                                $reaction_obj = CommentReaction::firstOrNew(['comment_id' => $comment_id, 'account_id' => \UserHelper::getAccountId()]);
                                if($reaction_obj->exists) {
                                    $create_notification = 0;
                                }
                                $reaction_obj->comment_id = $comment_id;
                                $reaction_obj->account_id = \UserHelper::getAccountId();
                                $reaction_obj->reaction = $reaction_type;
                                $reaction_obj->save();

                                if($create_notification && ($reaction_type == 1)){
                                    $message = new Message;
                                    $msg_data = [
                                        'from_account_id' => \UserHelper::getAccountId(), 
                                        'to_account_id' => $comment->account_id,
                                        'type' => config('aspirant.notification.type')['like-reaction'],
                                        'message' => 'Your comment got a new like',
                                        'link' => $page_link
                                    ];
                                    $message->createNotification($msg_data);
                                }

                                $resp = [
                                    'likes' => 0,
                                    'dislikes' => 0
                                ];
                                $reaction_count = CommentReaction::select('reaction', \DB::raw("count(reaction) as total_reaction"))
                                                    ->where('comment_id', $comment_id)
                                                    ->groupBy('reaction')
                                                    ->get();

                                foreach ($reaction_count as $rct) {
                                    switch ($rct->reaction) {
                                        case '1': $resp['likes'] = $rct->total_reaction; break;
                                        case '2': $resp['dislikes'] = $rct->total_reaction; break;
                                        default: break;
                                    }
                                }
                                return response()->json($resp);
                            }
                        }
                    }
                    return response()->json(['message' => 'Forbidden'], 403);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
