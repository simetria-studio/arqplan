<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\Provider;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $this->checkAccessArea('PROVIDER');

        $providers = Provider::where('company_id', Auth::user()->company->id)->get();
        return view('provider.index')->with('providers', $providers);
    }
    
    public function new()
    {
        $this->checkAccess('PROVIDER_EDIT');
        
        $provider = new Provider();
        return view('provider.form')->with('provider', $provider);
    }
    
    public function show(Request $request)
    {
        $this->checkAccessArea('PROVIDER');

        $provider = Provider::find($request->id);
        return view('provider.show')->with('provider', $provider);
    }
    
    public function edit(Request $request)
    {
        $this->checkAccess('PROVIDER_EDIT');

        $provider = Provider::find($request->id);
        return view('provider.form')->with('provider', $provider);
    }
    
    public function newPost(Request $request)
    {  
        $this->checkAccess('PROVIDER_EDIT');

        $provider = new Provider();
        
        $provider->company_id = Auth::user()->company->id;
        $provider->name = $request->name;
        $provider->description = $request->description ?? "";
        $provider->cnpjcpf = $request->cnpjcpf ?? "";
        
        $provider->email = $request->email ?? "";
        $provider->mobile = $request->mobile ?? "";
        $provider->phone = $request->phone ?? "";

        $provider->zipcode = $request->zipcode ?? "";   
        $provider->address = $request->address ?? "";        
        $provider->addressnumber = $request->addressnumber ?? "";
        $provider->addresscomplement = $request->addresscomplement ?? "";
        $provider->neighborhood = $request->neighborhood ?? "";
        $provider->city = $request->city ?? "";
        $provider->state = $request->state ?? "";     

        $provider->save();

        $request->session()->flash('message', 'Fornecedor criado com sucesso!'); 

        return redirect()->route('provider');
    }
    
    public function editPost(Request $request)
    {        
        $this->checkAccess('PROVIDER_EDIT');

        $provider = Provider::find($request->id);
        
        $provider->name = $request->name;
        $provider->description = $request->description ?? "";
        $provider->cnpjcpf = $request->cnpjcpf ?? "";

        $provider->zipcode = $request->zipcode ?? "";   
        $provider->address = $request->address ?? "";        
        $provider->addressnumber = $request->addressnumber ?? "";
        $provider->addresscomplement = $request->addresscomplement ?? "";
        $provider->neighborhood = $request->neighborhood ?? "";
        $provider->city = $request->city ?? "";
        $provider->state = $request->state ?? "";   

        $provider->save();

        $request->session()->flash('message', 'Fornecedor atualizado com sucesso!');

        return redirect()->route('provider');
    }
    
    public function delete(Request $request)
    {
        $this->checkAccess('PROVIDER_EDIT');

        $provider = Provider::find($request->id);
        return view('provider.delete')->with('provider', $provider);
    }
    
    public function deletePost(Request $request)
    {  
        $this->checkAccess('PROVIDER_EDIT');

        $provider = Provider::find($request->id); 

        $provider->delete();

        $request->session()->flash('message', 'providere removido com sucesso!'); 

        return redirect()->route('provider');
    }













    // API     
    public function api_get_active_providers()
    {
        $providersCount = $this->getAllActiveProviders();

        return response([ 'providers' => $providersCount, 'message' => 'Retrieved successfully'], 200);
    }
}
