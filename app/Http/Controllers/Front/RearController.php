<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class RearController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'rear_side');
        view()->share('pagetitle', 'Rear Side');
        $this->request = $request;
    }

    public function user_register()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            switch ($type) {
                case 'name':
                    $val = $this->request->input('val', '');
                    $code = User::where('name', $val)->count() ? 0 : 1;
                    
                    $msg = $code ? 'Name available' : 'Name not available';
                    return response()->json(['code' => $code, 'message' => $msg]);
                    break;
                case 'email':
                    $val = $this->request->input('val', '');
                    $code = User::where('email', $val)->count() ? 0 : 1;
                    $msg = $code ? 'Email available' : 'Email not available';
                    return response()->json(['code' => $code, 'message' => $msg]);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }
}
