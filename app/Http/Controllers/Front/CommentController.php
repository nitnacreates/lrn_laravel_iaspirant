<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Comment\Comment;
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
                    if(\Gate::allows('prelim-user')){
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
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
