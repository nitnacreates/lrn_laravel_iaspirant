<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMsg;

class ManifestController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'manifest');
        view()->share('pagetitle', 'About');
        $this->request = $request;
    }

    public function subscription()
    {
        if (Auth::check()) {
            return redirect()->route('user::subscription', ['action' => 'show']);
        }
        
        return view('user.subscription.show');
    }

    public function about_us()
    {
        $view_arr['pagetitle'] = 'About';   
        return view('front.manifest.about_us')->with($view_arr);
    }

    public function contact()
    {   

        if ($this->request->isMethod('post')){
            $params = $this->request->all();

            $this->validate($this->request, [
                'g-recaptcha-response' => 'required|captcha',
                'first_name' => 'required|alpha|min:2',
                'last_name' => 'required|alpha|min:2',
                'email' => ['required', 'email', 'regex:/^[A-Za-z0-9-\._]+@(gmail.com|yahoo.co.in|yahoo.in|yahoo.com|rediff.com|rediff.in|rediffmail.com|zoho.com|zoho.in|outlook.com|live.com)$/'],
                'mobile' => 'required|integer|min:1111111111',
                'message' => 'required',
            ],[
                'email.regex' => 'Invalid email domain',
                'mobile.min' => 'Enter 10 digit phone',
                'g-recaptcha-response.required' => 'Captcha entered was wrong'
            ]);
            $message = json_encode($params);
        
            $objContact = new \stdClass();
            $objContact->first_name = $params['first_name'];
            $objContact->last_name = $params['last_name'];
            $objContact->email = $params['email'];
            $objContact->mobile = $params['mobile'];
            $objContact->message = $params['message'];

            try{
                Mail::to(config('mail.from.address'))->send(new ContactMsg($objContact));
            } 
            catch(\Exception $e){
                
            }
            
            $this->request->session()->flash('msgs', ["Thanks, we will get back to you in 1-3 business days"]);
            return redirect()->route('front::manifest', ['action' => 'contact']);
        }
        $view_arr['pagetitle'] = 'Contact Us';
        return view('front.manifest.contact')->with($view_arr);
    }
}
