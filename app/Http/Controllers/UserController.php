<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Company;
use App\Models\UserProfile;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->checkAccess('USER_EDIT');

        return view('user.index');
    }

    public function new()
    {
        $this->checkAccess('USER_EDIT');

        return view('user.new');
    }





    // ADMIN
    public function admin_spylogin(Request $request)
    {
        $this->checkIsAdmin();

        $user = User::where('email','=',$request->email)->first();
        Auth::loginUsingId(auth()->user()->id, TRUE);

        return redirect()->route('home');
    }
    public function admin_index()
    {
        $this->checkIsAdmin();

        $users = User::where('id', '<>', Auth::user()->id)->get();
        return view('admin.user.index')->with('users', $users);
    }

    public function admin_new()
    {
        $this->checkIsAdmin();

        $user = new User();
        $companies = Company::all();
        $profiles = UserProfile::all();
        return view('admin.user.form')->with('user', $user)->with('profiles', $profiles)->with('companies', $companies);
    }

    public function admin_edit(Request $request)
    {
        $this->checkIsAdmin();

        $user = User::find($request->id);
        $companies = Company::all();
        $profiles = UserProfile::all();

        return view('admin.user.form')->with('user', $user)->with('profiles', $profiles)->with('companies', $companies);
    }

    public function admin_newPost(Request $request)
    {
        $this->checkIsAdmin();

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:255',
            'cpf' => 'required|unique:users|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User();

        $user->company_id = $request->company > 0  ? $request->company : null;
        $user->name = $request->name;
        $user->lastname = $request->lastname ?? "";

        $user->email = $request->email ?? "";
        $user->cpf = $request->cpf ?? "";
        $user->mobile = $request->mobile ?? "";

        $user->is_super_admin = isset($request->isAdmin) && $request->isAdmin == "true";

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

        return redirect()->route('admin.user');
    }

    public function admin_editPost(Request $request)
    {
        $this->checkIsAdmin();

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$request->id.'|max:255',
            'cpf' => 'required|unique:users,cpf,'.$request->id.'|max:14',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find($request->id);

        $user->fill($request->all());
        $user->company_id = $request->company > 0  ? $request->company : null;

        $user->name = $request->name;
        $user->lastname = $request->lastname ?? "";

        $user->email = $request->email ?? "";
        $user->cpf = $request->cpf ?? "";
        $user->mobile = $request->mobile ?? "";

        $user->is_super_admin = isset($request->isAdmin) && $request->isAdmin == "true";

        if(!isset($request->profile) && $request->profile == null){
            $profiles = [];
        }else{
            $profiles = UserProfile::whereIn('code', $request->profile)->get();
        }

        $user->setProfiles($profiles);
        $user->save();

        $request->session()->flash('message', 'Usuário atualizado com sucesso!');

        return redirect()->route('admin.user');
    }

    public function admin_delete(Request $request)
    {
        $this->checkIsAdmin();

        $user = User::find($request->id);
        return view('admin.user.delete')->with('user', $user);
    }

    public function admin_deletePost(Request $request)
    {
        $this->checkIsAdmin();

        $user = User::find($request->id);
        $user->setProfiles([]);
        $user->save();

        $user->delete();

        $request->session()->flash('message', 'Usuário removido com sucesso!');

        return redirect()->route('admin.user');
    }

    public function admin_reset_password(Request $request)
    {
        $this->checkIsAdmin();

        $password = str_random(8);
        $user = User::find($request->id);
        $user->password = Hash::make($password);

        $user->save();

        $request->session()->flash('message', 'Senha resetada com sucesso!');

        Mail::to($user->email)->send(new \App\Mail\ResetUserPassword($user, $password));

        return redirect()->route('admin.user');
    }
}
