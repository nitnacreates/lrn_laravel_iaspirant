<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;

use App\User;
use App\Model\Note\Note;
use App\Model\Topic;

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
        $params = $this->request->all();
        $note_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        $view_arr = array();

        // check if note id is accessed by authorised person
        if($note_id){
            $note_obj = Note::where('id', $note_id)->where('user_id', \UserHelper::getUserId())->first();
            if(empty($note_obj)){
                $this->request->session()->flash('msgs', array('Invalid note'));
                return redirect()->route('front::notes', ['user_name' => \UserHelper::getUserName(), 'controller' => 'notes', 'action' => 'list']);  
            } else{
                $sel_topic = Topic::find($note_obj->topic_id);
                if(!empty($sel_topic)){
                    $sel_topic = $sel_topic->title;
                }
            }
        }

        if ($this->request->isMethod('post')) {
            $subject_maps = array_keys(config('aspirant.optional_map') + config('aspirant.subject_map'));
            $sub = $params['sub'];

            // add id validation
            $this->validate($this->request, [
                'sub' => ['required',Rule::in($subject_maps)],
                'topic_id' => ['required', 'integer', 'min:1', Rule::exists('topics', 'id')->where(function($query) use($sub) {
                    $query->where('sub',$sub);
                })],
                'locked' => 'required|boolean',
                'note_title' => 'required|max:200',
                'note_content' => 'nullable|max:60000',
                'self_note' => 'nullable|max:1800'
            ],[
                'sub.in' => 'Invalid subject',
                'topic_id.min' => 'Topic is missing',
                'topic_id.exists' => 'Subject Topic mismatch',
                'note_content.max' => 'Note content limit exhausted',
                'self_note.max' => 'Self note should be less than 2000 characters'
            ]);

            $msg = 'There was an error';
            $msg_type = 'updated';

            if(!$note_obj->exists){
                $msg_type = 'added';
                $note_obj = new Note;
                $note_obj->user_id = \UserHelper::getUserId();
            }
            $note_obj->sub = $sub;
            $note_obj->topic_id = $params['topic_id'];
            $note_obj->locked = $params['locked'];
            $note_obj->note_title = $params['note_title'];
            $note_obj->note_content = $params['note_content'];
            $note_obj->self_note = $params['self_note'];
            $note_obj->save();
            
            $this->request->session()->flash('msgs', array('Note successfully ' . $msg_type));
            return redirect()->route('front::notes', ['user_name' => \UserHelper::getUserName(), 'controller' => 'notes', 'action' => 'list']); 
        }

        // view

        $view_arr['note'] = $note_obj ?? new Note;
        $view_arr['sel_topic'] = $sel_topic ?? "";
        
        return view('front.notes.create')->with($view_arr);
    }

    protected function getNoteUser($user_name)
    {
        $user_exists = User::where('name',$user_name)->first();
        return empty($user_exists) ? 0 : $user_exists->id;
    }
}
