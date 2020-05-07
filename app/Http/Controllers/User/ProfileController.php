<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\User\UserKeep;

class ProfileController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'profile');
        view()->share('pagetitle', 'User Profile');
        $this->request = $request;
    }

    public function show()
    {
        $user_id = \UserHelper::getUserId();
        $view_arr['user'] = \Auth::user();
        $view_arr['user_keep'] = UserKeep::find($user_id);

        return view('user.profile.show')->with($view_arr);
    }

    public function mutate_profile()
    {
        if ($this->request->isMethod('post')){
            $params = $this->request->all();

            $this->validate($this->request, [
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

            $user_id = \UserHelper::getUserId();
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
            $msg = array('Details updated successfully');
        } else{
            $msg = array('There was an error');
        }

        $this->request->session()->flash('msgs', $msg);
        return redirect()->route('user::profile', ['action' => 'show']);
    }

    public function mutate_password()
    {
        if ($this->request->isMethod('post')){
            $params = $this->request->all();

            $this->validate($this->request, [
                'old_password' => 'required|string',
                'password' => 'required|string|confirmed|min:6',
            ]);

            $user_id = \UserHelper::getUserId();
            $user = User::findOrFail($user_id);

            if (Hash::check($params['old_password'], $user->password)) { 
                $user->fill([
                    'password' => Hash::make($params['password'])
                ])->save();

               $msg = array('Password updated successfully');
            } else {
                $msg = array('Current password is wrong');
            }
        } else{
            $msg = array('There was an error');
        }

        $this->request->session()->flash('msgs', $msg);
        return redirect()->route('user::profile', ['action' => 'show']);
    }
}
