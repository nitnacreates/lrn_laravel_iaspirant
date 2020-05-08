<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Note\Note;

class NotesController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'create-note');
        view()->share('pagetitle', 'Create Note');
        $this->request = $request;
    }

    public function create()
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
        return view('user.notes.create')->with($view_arr);
    }
}
