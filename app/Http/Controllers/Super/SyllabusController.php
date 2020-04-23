<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Topic;

class SyllabusController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'syllabus');
        view()->share('pagetitle', 'Syllabus');
        $this->request = $request;
    }

    protected function getSub($sub)
    {
        $sub_arr = config('aspirant.subject_map');
        return array_key_exists($sub, $sub_arr) ? $sub_arr[$sub] : 0;
    }

    public function show()
    {
        $sub = $this->request->input('sub');

        if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
            return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => 'history']);
        }
        
        $topics = new Topic;
        $topics_arr = $topics->structuredSubject($sub);

        $view_arr = array(
            'sub' => $sub,
            'sub_title' => $sub_title,
            'topics_arr' => $topics_arr
        );

        return view('super.syllabus.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $sub = $params['sub'];
        if(!($sub_title = $this->getSub($sub))){
            $this->request->session()->flash('msgs', array('Subject does not exist'));
            return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => 'history']);
        }

        // CRUD operations
        $subid = isset($params['id']) ? intval(trim($params['id'])) : 0;
        if ($this->request->isMethod('post')) {

            $this->validate($this->request, [
                'title' => 'required',
                'about' => 'max:500',
                'id' => 'required|integer',
                'mode' => 'required|alpha',
                'category' => 'in:p,m,b'
            ]);
            $params['about'] = is_null($params['about']) ? "" : $params['about'];

            $msg = 'There was an error';
            switch ($params['mode']) {
                case 'add':

                    $parent_level = 0;
                    if($subid){
                        $parent_syll = Topic::find($subid);
                        if($parent_syll !== null){
                            $parent_level = intval($parent_syll->level) + 1;
                        }
                    }

                    $topic_obj = new Topic;
                    $topic_obj->sub = $sub;
                    $topic_obj->title = $params['title'];
                    $topic_obj->parent = $subid;
                    $topic_obj->level = $parent_level;
                    $topic_obj->about = $params['about'];
                    $topic_obj->category = $params['category'];

                    $topic_obj->save(); 
                    $msg = $params['title'] . ' added to ' . $sub;
                    break;
                case 'edit':

                    $topic = Topic::find($subid);
                    $topic->update(array(
                        'title' => $params['title'],
                        'about' => $params['about'],
                        'category' => $params['category']
                    ));
                    $msg = $params['title'] . ' updated';
                    break;
                case 'delete':
                    $exist_count = Topic::where('parent', '=', $subid)->count();
                    if($exist_count){
                        return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => $sub])
                                        ->withErrors(array('message' => 'Delete all children first'));
                    }
                    Topic::destroy($subid);
                    $msg = 'Topic deleted successfully';
                    break;
                default:
                    break;
            }

            $flash_msgs[] = $msg;
            $this->request->session()->flash('msgs', $flash_msgs);
            return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => $sub]);
        }

        // view
        $mode = 'add';
        if((isset($params['mode']) && $params['mode'] == 'display')){
            
            if(!$subid){
                return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => $sub]);
            }
            $mode = 'display';
            $syllabus = Syllabus::find($subid);

            if(is_null($syllabus)){
                return redirect()->route('super::syllabus', ['action' => 'show', 'sub' => $sub]);   
            }
            $view_arr['syllabus'] = $syllabus;
        }
        
        $view_arr['sub'] = $sub;
        $view_arr['subid'] = $subid;
        $view_arr['sub_title'] = $sub_title;
        $view_arr['mode'] = $mode;
        $view_arr['pagetitle'] = ucfirst($mode) . ' syllabus';
        
        return view('super.syllabus.stash')->with($view_arr);
    }

    public function picker()
    {
        $sub = $this->request->input('sub');
        if(!($sub_title = $this->getSub($sub))){
            exit('No syllabus selected');
        }
        $syllabus = new Syllabus();
        $syllabus_arr = $syllabus->structuredSyllabus($sub);

        $view_arr = array(
            'sub' => $sub,
            'sub_title' => $sub_title,
            'syllabus_arr' => $syllabus_arr
        );

        return view('super.syllabus.picker')->with($view_arr);
    }
}
