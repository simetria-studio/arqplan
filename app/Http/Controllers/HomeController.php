<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Event;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $greetings = "";

        $time = date("H");

        if ($time < "12") {
            $greetings = "Bom dia";
        }else if ($time >= "12" && $time < "18") {
            $greetings = "Boa tarde";
        } else {
            $greetings = "Boa noite";
        }

        return view('home', compact('greetings'));
    }
    
    public function profile()
    {
        return view('profile');
    }
    
    public function profile_post(Request $request)
    {        
        Auth::user()->name = $request->name;
        Auth::user()->lastname = $request->lastname ?? "";

        Auth::user()->email = $request->email ?? "";        
        Auth::user()->cpf = $request->cpf ?? "";
        Auth::user()->mobile = $request->mobile ?? "";

        $errorResult = "";

        if(isset($request->old_password) || isset($request->new_password) || isset($request->new_password_confirmation)){
            $validator = $this->password_rules($request->All());
            
            if($validator->fails())
            {
                $aRes = $validator->getMessageBag()->toArray();
                $errorResult = reset($aRes)[0];
            }
            else
            {    
                if(Hash::check($request->old_password, Auth::User()->password))
                {
                    Auth::user()->password = Hash::make($request->new_password);
                }
                else
                {           
                    $errorResult = 'Por favor, insira sua senha atual corretamente';
                }
            }
        }

        Auth::user()->save();

        if($errorResult != ''){
            $request->session()->flash('error', $errorResult);
        }else{
            $request->session()->flash('message', 'Seus dados foram atualizados com sucesso!');
        }
        

        return redirect()->route('profile');
    }

    public function password_rules(array $data)
    {
        $messages = [
            'old_password.required' => 'Por favor, insira sua senha atual',
            'new_password.required' => 'Por favor, insira sua nova senha',
            'new_password.min'=> 'A sua nova senha deve ter pelo menos 6 caracteres.',
            'new_password_confirmation.same' => 'Por favor, confirme a senha digitada',
        ];

        $validator = Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',//|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/
            'new_password_confirmation' => 'required|min:6|same:new_password',
        ], $messages);

        return $validator;
    }  
    
    public function template()
    {
        return view('template');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
