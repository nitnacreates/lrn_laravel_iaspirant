<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\User;
use App\Model\User\UserKeep;

class UsersController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'users');
        view()->share('pagetitle', 'Users');
        $this->request = $request;
    }

    public function show()
    {
        $view_arr = array();
        
        $view_arr['users'] = User::select('id', 'name', 'email', 'email_verified_at', 'subscription', 'created_at')->orderBy('created_at', 'DESC')->paginate(10);
        return view('super.users.show')->with($view_arr);
    }

    public function stash()
    {
        $params = $this->request->all();
        $view_arr = array();
        $user_id = isset($params['id']) ? intval(trim($params['id'])) : 0;

        if ($this->request->isMethod('post')) {
            $this->validate($this->request, [
                'id' => 'required|integer|min:1',
                'name' => ['required', 'max:255', 'regex:/^[a-z\._]+$/', Rule::unique('users')->ignore($user_id)],
                'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore($user_id)],
                'subscription' => 'required|in:1,2,3',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|integer|min:111111111',
                'gender' => 'required|in:m,f',
                'dob' => 'required|date_format:Y-m-d',
                'qualification_type' => 'required|integer|min:1',
                'qualification' => 'required|string',
                'total_attempts' => 'nullable|integer|max:8',
                'max_stage' => 'nullable|integer|max:3',
            ],[
                'phone.min' => 'Only numerals allowed in phone'
            ]);

            $user = User::findOrFail($user_id);
            $user->name = $params['name'];
            $user->email = $params['email'];
            $user->subscription = $params['subscription'];
            $user->save();

            $user_keep = UserKeep::findOrFail($user_id);
            $user_keep->first_name = $params['first_name'];
            $user_keep->last_name = $params['last_name'];
            $user_keep->phone = $params['phone'];
            $user_keep->gender = $params['gender'];
            $user_keep->dob = $params['dob'];
            $user_keep->qualification_type = $params['qualification_type'];
            $user_keep->qualification = $params['qualification'];
            $user_keep->total_attempts = $params['total_attempts'];
            $user_keep->max_stage = $params['max_stage'];
            $user_keep->save();

            $this->request->session()->flash('msgs', array('User successfully saved'));
            return redirect()->route('super::users', ['action' => 'show']); 
        }

        // view
        if($user_id){
            $user = User::with('user_keep')->find($user_id);
            if(empty($user)){
                $this->request->session()->flash('msgs', array('User does not exist'));
                return redirect()->route('super::users', ['action' => 'show']);  
            }
            
            $view_arr['user'] = $user;
        } else{
            $this->request->session()->flash('msgs', array('User does not exist'));
            return redirect()->route('super::users', ['action' => 'show']); 
        }

        $view_arr['mode'] = $user_id ? 'edit':'add';
        return view('super.users.stash')->with($view_arr);
    }

    public function summon()
    {

    }
}
