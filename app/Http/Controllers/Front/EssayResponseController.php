<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Model\Essay\Essay;
use App\Model\Essay\EssayResponse;
use App\Model\Essay\EssayReaction;

class EssayResponseController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'essay-response');
        view()->share('pagetitle', 'Essay Response');
        $this->request = $request;
    }

    public function show($user_name)
    {
        $params = $this->request->all();
        $essay_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        $view_arr = array();

        if(\Gate::denies('full-user')){
            $this->request->session()->flash('msgs', array('Unauthorized access'));
            return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show']);              
        }

        $essay = Essay::where('id', $essay_id)->where('locked', 0)->first();
        if(empty($essay)){
            $this->request->session()->flash('msgs', 'Essay does not exist');
            return redirect()->route('front::essays', ['action' => 'list']);
        }

        $view_arr['essay'] = $essay;
        $account_id = \UserHelper::getUserAccount($user_name);

        $essay_response = EssayResponse::with('essay_reactions')
                            ->with(['grouped_essay_reactions' => function($query) {
                                $query->select('essay_response_id', 'type', \DB::raw("count(type) as type_counter"))
                                                    ->groupBy('essay_response_id')
                                                    ->groupBy('type');
                            }])
                            ->where('essay_id', $essay_id)
                            ->where('account_id', $account_id)
                            ->first();
        if(!empty($essay_response)){
            if($essay_response->locked && ($user_name != \UserHelper::getUserName())){
                $this->request->session()->flash('msgs', array('Unauthorized access'));
                return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show']);              
            }

            if(!empty($essay_response->completed_on)){
                $response_created = Carbon::createFromFormat('Y-m-d H:i:s', $essay_response->created_at);
                $completed_on = Carbon::createFromFormat('Y-m-d H:i:s', $essay_response->completed_on);
                if(!$response_created->diffInDays($completed_on->copy())){
                    $view_arr['completed_in'] = $completed_on->diff($response_created)->format('%H:%I:%S');    
                }
            }
        }

        $view_arr['is_user_same'] = $user_name == \UserHelper::getUserName() ? 1 : 0;
        $view_arr['essay_id'] = $essay_id;
        $view_arr['response'] = $essay_response;
        
        return view('front.essay_response.show')->with($view_arr);
    }

    public function create($user_name)
    {
        $params = $this->request->all();
        $essay_id = isset($params['id']) ? intval(trim($params['id'])) : 0;
        $view_arr = array();

        if(\Gate::denies('full-user') || ($user_name != \UserHelper::getUserName())){
            $this->request->session()->flash('msgs', array('Unauthorized access'));
            return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show']);              
        }

        if($essay_id){
            $essay = Essay::where('id', $essay_id)->where('locked', 0)->first();
            if(empty($essay)){
                $this->request->session()->flash('msgs', 'Essay does not exist');
                return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show']);
            }

            $view_arr['essay'] = $essay;
            $account_id = \UserHelper::getAccountId();
            $essay_response = EssayResponse::where('essay_id', $essay_id)->where('account_id', $account_id)->first();
            if(empty($essay_response)){
                $essay_response = new EssayResponse;
                $essay_response->essay_id = $essay_id;
                $essay_response->account_id = $account_id;
                $essay_response->locked = 1;
                $essay_response->save();
            }

            if($this->request->isMethod('post')){
                $params = $this->request->all();
                $this->validate($this->request, [
                    'response_content' => 'required',
                    'locked' => 'required|boolean',
                ]);

                $now = Carbon::now();
                $essay_response->response_content = $params['response_content'];
                $essay_response->locked = $params['locked'];
                if(is_null($essay_response->completed_on)){
                    $essay_response->completed_on = $now->format('Y-m-d H:i:s');    
                }
                $essay_response->save();

                $this->request->session()->flash('msgs', array('Essay successfully saved'));
                return redirect()->route('front::three-essay-response', ['user_name' => \UserHelper::getUserName(), 'action' => 'show', 'id' => $essay_id]);

            }

            $view_arr['response'] = $essay_response;
        }
        return view('front.essay_response.create')->with($view_arr);
    }

    /*
    * TODO - Insert notifications for user
    */
    public function summon()
    {
        if($this->request->ajax()){
            if(\Gate::denies('full-user')){
                return response()->json(['message' => 'Unauthorised access'], 404);
            }
            $response_id = intval($this->request->input('response',0));
            $essay_response = EssayResponse::find($response_id);
            if(empty($essay_response)){
                return response()->json(['message' => 'Incorrect response'], 404);   
            }

            $account_id = \UserHelper::getAccountId();
            $essay_reaction = EssayReaction::firstOrNew([
                'essay_response_id' => $response_id,
                'account_id' => $account_id
            ]);

            $type = $this->request->input('type');
            switch ($type) {
                case 'user_emotion':
                    $motion = $this->request->input('motion');
                    if(in_array($motion, [1,2,3])){
                        $essay_reaction->type = $motion;
                        $essay_reaction->save();

                        $response_reaction = EssayReaction::select('essay_response_id', 'type', \DB::raw("count(type) as type_counter"))
                                                ->where('essay_response_id', $response_id)
                                                ->groupBy('essay_response_id')
                                                ->groupBy('type')
                                                ->get();
                        $reaction_type = [
                            '1' => 0,
                            '2' => 0,
                            '3' => 0
                        ];
                        foreach ($response_reaction as $grouped_reaction) {
                            $reaction_type[$grouped_reaction->type] = $grouped_reaction->type_counter;
                        }
                        return response()->json([
                            'reaction' => $reaction_type
                        ]);
                    } else{
                        return response()->json(['message' => 'Incorrect response provided'], 404); 
                    }
                    break;
                case 'user_score':
                    $user_score = intval($this->request->input('user_score'));
                    if(($user_score > 0) && ($user_score < 11)){
                        $essay_reaction->score = $user_score;
                        $essay_reaction->save();
                        return response()->json(['message' => 'Thank you for reviewing']);
                    } else{
                        return response()->json(['message' => 'Incorrect rating provided'], 404); 
                    }
                    break;
                case 'user_comment':
                    $user_comment = $this->request->input('user_comment');
                    $user_comment = preg_replace('/[^a-zA-Z0-9 \.\-_]/i', "", $user_comment);
                    $essay_reaction->comment = $user_comment;
                    $essay_reaction->save();
                    return response()->json(['message' => 'Thank you for commenting']);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }

}
