<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\Subscriber;
use App\Models\DemonstrationContact;

class DemonstrationRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function new_contact(Request $request)
    {
        if( $request->name == null ||  $request->name == "") return response()->json(['message' => "Nome deve ser preenchido"], 500);

        if( $request->email == null ||  $request->email == "") return response()->json(['message' => "E-mail deve ser preenchido"], 500);
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)) return response()->json(['message' => "E-mail não é válido"], 500);

        if( $request->phone == null ||  $request->phone == "") return response()->json(['message' => "Telefone deve ser preenchido"], 500);

        if( $request->employeesNumber == null ||  $request->employeesNumber == "") return response()->json(['message' => "Número de colaboradores deve ser preenchido"], 500);

        if( $request->mainDifficulty == null ||  $request->mainDifficulty == "") return response()->json(['message' => "Principal dificuldade em gerir deve ser preenchido"], 500);

        $demonstrationContact = new DemonstrationContact();
        $demonstrationContact->name = $request->name;
        $demonstrationContact->email = $request->email;
        $demonstrationContact->phone = $request->phone;
        $demonstrationContact->employeesNumber = $request->employeesNumber;
        $demonstrationContact->otherSoftware = $request->otherSoftware;
        $demonstrationContact->mainDifficulty = $request->mainDifficulty;
        $demonstrationContact->save();

        Mail::send(new \App\Mail\NewDemonstrationContact($request));

        return "ok";
    }
    
    public function admin_index(Request $request)
    {
        $this->checkIsAdmin();
        
        $demonstrationRequests = DemonstrationContact::orderBy('id', 'DESC')->get();
        return view('admin.demonstrationRequests.index')->with('demonstrationRequests', $demonstrationRequests);
    }
}
