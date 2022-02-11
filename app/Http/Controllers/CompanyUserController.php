<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\UserProfile;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class CompanyUserController extends Controller
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
    
    public function index()
    {
        $this->checkAccess("ADMIN");

        $users = User::where('company_id', Auth::user()->company->id)->where('id', '<>', Auth::user()->id)->where('is_super_admin', 'false')->get();
        return view('company.users.index')->with('users', $users);
    }
    
    public function new()
    {
        $this->checkAccess("ADMIN");
        
        $user = new User();
        $profiles = UserProfile::all();
        return view('company.users.form')->with('user', $user)->with('profiles', $profiles);
    }
    
    public function edit(Request $request)
    {
        $this->checkAccess("ADMIN");
        
        $user = User::find($request->id);
        $profiles = UserProfile::all();
        return view('company.users.form')->with('user', $user)->with('profiles', $profiles);
    }
    
    public function newPost(Request $request)
    {  
        $this->checkAccess("ADMIN");

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
            'cpf' => 'required|unique:users|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        };
        
        $user = new User();
        
        $user->company_id = Auth::user()->company->id;
        $user->name = $request->name;
        $user->lastname = $request->lastname ?? "";

        $user->email = $request->email ?? "";        
        $user->cpf = $request->cpf ?? "";
        $user->mobile = $request->mobile ?? "";
        
        $password = str_random(8);
        $user->password = Hash::make($password);   
        $user->save();

        if(!isset($request->profile) && $request->profile == null){
            $profiles = [];
        }else{
            $profiles = UserProfile::whereIn('code', $request->profile)->get();
        }

        $user->setProfiles($profiles);    

        $user->save();

        $request->session()->flash('message', 'Usuário criado com sucesso!');

        Mail::to($user->email)->send(new \App\Mail\NewUser($user, $password));

        return redirect()->route('company.users');
    }
    
    public function editPost(Request $request)
    {        
        $this->checkAccess("ADMIN");

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$request->id.'|max:255',
            'cpf' => 'required|unique:users,cpf,'.$request->id.'|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        };
        
        $user = User::find($request->id);
        
        $user->name = $request->name;
        $user->lastname = $request->lastname ?? "";

        $user->email = $request->email ?? "";        
        $user->cpf = $request->cpf ?? "";
        $user->mobile = $request->mobile ?? "";

        if(!isset($request->profile) && $request->profile == null){
            $profiles = [];
        }else{
            $profiles = UserProfile::whereIn('code', $request->profile)->get();
        }     

        $user->setProfiles($profiles);
        
        $user->save();

        $request->session()->flash('message', 'Usuário atualizado com sucesso!');

        return redirect()->route('company.users');
    }
    
    public function delete(Request $request)
    {
        $this->checkAccess("ADMIN");
        
        $user = User::find($request->id);
        return view('company.users.delete')->with('user', $user);
    }
    
    public function deletePost(Request $request)
    {        
        $this->checkAccess("ADMIN");
        
        $user = User::find($request->id);
        $user->setProfiles([]);
        $user->save();

        $user->delete();

        $request->session()->flash('message', 'Usuário removido com sucesso!');

        return redirect()->route('company.users');
    }
}
