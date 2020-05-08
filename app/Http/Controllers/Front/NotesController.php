<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Model\Note\Note;

class NotesController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'news');
        view()->share('pagetitle', 'News');
        $this->request = $request;
    }

    public function list($user_name)
    {
        $note_user_id = $this->getNoteUser($user_name);
        if($note_user_id){
            $view_arr = array();

            $notes = Note::select(['id', 'sub', 'topic_id', 'locked', 'note_title'])
                ->where('user_id', $note_user_id);

            $is_user_same = \FrontHelper::checkAuthId($note_user_id);
            if(!$is_user_same){
                $notes->where('locked',0);
            }

            $view_arr['notes'] = $notes;
            $view_arr['is_user_same'] = $is_user_same;
            return view('front.notes.list')->with($view_arr);   
        }
        
        $this->request->session()->flash('msgs', array('User does not exist'));
        return redirect()->to('/');
    }

    public function show($user_name)
    {
        $note_user_id = $this->getNoteUser($user_name);
        return view('front.notes.show'); 
    }

    public function create($user_name)
    {
        $view_arr = array();
        $note_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        if ($this->request->isMethod('post')) {

        }

        // view
        if($note_id) {
            $note = Note::where('id', $note_id)->where('user_id', \UserHelper::getUserId());
            if(empty($note)){
                $this->request->session()->flash('msgs', array('Invalid note'));
                return redirect()->route('user::notes', ['action' => 'create']);  
            }
            $view_arr['note'] = $note;
        }
        return view('front.notes.create')->with($view_arr);
    }

    protected function getNoteUser($user_name)
    {
        $user_exists = User::where('name',$user_name)->first();
        return empty($user_exists) ? 0 : $user_exists->id;
    }
}
