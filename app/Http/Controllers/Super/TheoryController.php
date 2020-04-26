<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Theory\Theory;
use App\Model\Theory\TheorySummary;

use App\Model\Alliance\AllianceSuper;


class TheoryController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'theory');
        view()->share('pagetitle', 'Theory');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        $sub = $view_arr['sub'] = $this->request->input('sub','');
        $view_arr['theories'] = null;
        if(!empty($sub)){
            if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
                return redirect()->route('super::theory', ['action' => 'show']);
            }

            $view_arr['theories'] = Theory::select(['id', 'theory_title', 'account_id', 'user_type', 'sub', 'topic_id', 'category', 'weightage', 'locked', \DB::raw("CAST(`created_at` AS DATE) AS created_date")])->where('sub', $sub)->get();
            /*
            $view_arr['theory_particulars'] = TheoryParticular::with(['theory_content' => function ($query) use ($sub) {
                                $query->select(['id', 'theory_title']);
                            }])
                            ->join('alliance_supers', function ($join) use ($super_id, $theory_mapper_id) {
                                $join->on('theory_particulars.theory_content_id', '=', 'alliance_supers.mapper_primary_id')
                                ->on('alliance_supers.super_user_id', '=', \DB::raw($super_id))
                                ->on('alliance_supers.alliance_mapper_id', '=', \DB::raw($theory_mapper_id));
                            })
                            ->where('theory_particulars.sub', $sub)
                            ->get();
            */

        }

        return view('super.theory.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $view_arr = array();
        $theory_id = isset($params['id']) ? intval(trim($params['id'])) : 0;

        $account_id = \SuperHelper::getAccountId();

        // CRUD operations
        if ($this->request->isMethod('post')) {
            $this->validate($this->request, [
                'theory_title' => 'required',
                'locked' => 'boolean',
                'sub' => 'required|string',
                'topic_id' => 'required|integer|min:1',
                'category' => 'required|in:p,m,b',
                'weightage' => 'required|integer',
                // 'theory_reference' => 'nullable|min:50',
                // 'theory_content' => 'required|min:500',
                // 'theory_summary' => 'nullable|min:100',
                'summary_locked' => 'boolean',
                'theory_summary_id' => 'required|integer'
            ],[
                'topic_id.min' => 'Topic is missing'
            ]);

            // TODO: check title against subject
            $sub = $params['sub'];
            if(!($sub_title = \SubjectHelper::checkSubTitle($sub))){
                $this->request->session()->flash('msgs', array('Subject does not exist'));
                return redirect()->route('super::theory', ['action' => 'show']);
            }

            $msg = 'There was an error';
            $msg_type = 'added';
            $theory_obj = Theory::firstOrNew(['id' => $theory_id]);
            $theory_obj->theory_title = $params['theory_title'];
            $theory_obj->theory_content = $params['theory_content'];
            $theory_obj->theory_reference = $params['theory_reference'];
            if(!$theory_obj->exists) {
                $msg_type = 'updated';
                $theory_obj->account_id = $account_id;
                $theory_obj->user_type = 's'; // only super can add from backend
            }
            $theory_obj->sub = $sub;
            $theory_obj->topic_id = $params['topic_id'];
            $theory_obj->category = $params['category'];
            $theory_obj->weightage = $params['weightage'];
            $theory_obj->locked = $params['locked'];
            $theory_obj->save();

            $theory_id = $theory_obj->id;

            if(!empty($params['theory_summary'])){
                $theory_summary_obj = TheorySummary::firstOrNew(['theory_id' => $theory_id]);
                $theory_summary_obj->theory_id = $theory_id;
                if(!$theory_summary_obj->exists) {
                    $theory_summary_obj->account_id = $account_id;
                    $theory_summary_obj->user_type = 's'; // only super can add from backend
                }
                $theory_summary_obj->locked = $params['summary_locked'];
                $theory_summary_obj->theory_summary = $params['theory_summary'];
                $theory_summary_obj->save();
            }

            $this->request->session()->flash('msgs', array('Theory successfully ' . $msg_type));
            return redirect()->route('super::theory', ['action' => 'show', 'sub' => $sub]);  
        }

        // view
        $mode = 'add';
        if($theory_id){
            $mode = 'edit';
            $theory = Theory::find($theory_id)->with(['theory_summaries' => function ($query) use ($account_id) {
                $query->where('account_id', $account_id)
                        ->where('user_type', 's');
            }])->first();
            if(!$theory->count()){
                $this->request->session()->flash('msgs', array('Theory does not exist'));
                return redirect()->route('super::theory', ['action' => 'show']);  
            }
            $view_arr['theory'] = $theory;
        }
        $view_arr['mode'] = $mode;
        $view_arr['picker_url'] = route('super::media', ['action' => 'picker']);

        return view('super.theory.stash')->with($view_arr);
    }
}
