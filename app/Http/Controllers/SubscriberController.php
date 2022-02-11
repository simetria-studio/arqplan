<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\Subscriber;
use App\Models\DemonstrationContact;

class SubscriberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function subscribe(Request $request)
    {
        $email = $request->email;

        if($email == null || $email == "") return response()->json(['message' => "E-mail deve ser preenchido"], 500);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return response()->json(['message' => "E-mail não é válido"], 500);

        if(Subscriber::where('email',$request->email)->exists()) return response()->json(['message' => "E-mail já inscrito"], 500);

        $subscriber = new Subscriber();
        $subscriber->email = $email;
        $subscriber->save();

        return "ok";
    }
    
    public function admin_index(Request $request)
    {
        $this->checkIsAdmin();
        
        $subscribers = Subscriber::orderBy('id', 'DESC')->get();
        return view('admin.subscribers.index')->with('subscribers', $subscribers);
    }
}
