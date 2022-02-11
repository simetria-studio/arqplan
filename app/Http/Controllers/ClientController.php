<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Client;

class ClientController extends Controller
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
        $this->checkAccessArea('CLIENT');

        $clients = Client::where('company_id', Auth::user()->company->id)->get();
        return view('client.index')->with('clients', $clients);
    }
    
    public function new()
    {
        $this->checkAccess('CLIENT_EDIT');

        $client = new Client();
        return view('client.form')->with('client', $client);
    }
    
    public function show(Request $request)
    {
        $this->checkAccessArea('CLIENT');

        $client = Client::find($request->id);
        return view('client.show')->with('client', $client);
    }
    
    public function edit(Request $request)
    {
        $this->checkAccess('CLIENT_EDIT');

        $client = Client::find($request->id);
        return view('client.form')->with('client', $client);
    }
    
    public function newPost(Request $request)
    {  
        $this->checkAccess('CLIENT_EDIT');

        $client = new Client();
        
        $client->company_id = Auth::user()->company->id;
        $client->name = $request->name;
        $client->description = $request->description ?? "";
        $client->cnpjcpf = $request->cnpjcpf ?? "";
        
        $client->email = $request->email ?? "";
        $client->mobile = $request->mobile ?? "";
        $client->phone = $request->phone ?? "";

        $client->zipcode = $request->zipcode ?? "";   
        $client->address = $request->address ?? "";        
        $client->addressnumber = $request->addressnumber ?? "";
        $client->addresscomplement = $request->addresscomplement ?? "";
        $client->neighborhood = $request->neighborhood ?? "";
        $client->city = $request->city ?? "";
        $client->state = $request->state ?? "";     

        $client->save();

        $request->session()->flash('message', 'Cliente criado com sucesso!'); 

        return redirect()->route('client');
    }
    
    public function editPost(Request $request)
    {        
        $this->checkAccess('CLIENT_EDIT');

        $client = Client::find($request->id);
        
        $client->name = $request->name;
        $client->description = $request->description ?? "";
        $client->cnpjcpf = $request->cnpjcpf ?? "";

        $client->zipcode = $request->zipcode ?? "";   
        $client->address = $request->address ?? "";        
        $client->addressnumber = $request->addressnumber ?? "";
        $client->addresscomplement = $request->addresscomplement ?? "";
        $client->neighborhood = $request->neighborhood ?? "";
        $client->city = $request->city ?? "";
        $client->state = $request->state ?? "";   

        $client->save();

        $request->session()->flash('message', 'Cliente atualizado com sucesso!');

        return redirect()->route('client');
    }
    
    public function delete(Request $request)
    {
        $this->checkAccess('CLIENT_EDIT');

        $client = Client::find($request->id);
        return view('client.delete')->with('client', $client);
    }
    
    public function deletePost(Request $request)
    {  
        $this->checkAccess('CLIENT_EDIT');
        
        $client = Client::find($request->id); 

        $client->delete();

        $request->session()->flash('message', 'Cliente removido com sucesso!'); 

        return redirect()->route('client');
    }













    // API     
    public function api_get_active_clients()
    {
        $clientsCount = $this->getAllActiveClients();

        return response([ 'clients' => $clientsCount, 'message' => 'Retrieved successfully'], 200);
    }
}
