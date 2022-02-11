<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Company;

use Intervention\Image\ImageManagerStatic as Image;

class CompanyController extends Controller
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

        $company = Auth::user()->company;
        return view('company.index')->with('company', $company);
    }
    
    public function updatePost(Request $request)
    {        
        $this->checkAccess("ADMIN");

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'cnpjcpf' => 'required|max:18',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $company = Auth::user()->company;
        $company->name = $request->name;
        $company->description = $request->description ?? "";
        $company->cnpjcpf = $request->cnpjcpf ?? "";
        $company->phone = $request->phone ?? "";
        $company->mobile = $request->mobile ?? "";

        $company->zipcode = $request->zipcode ?? "";   
        $company->address = $request->address ?? "";        
        $company->addressnumber = $request->addressnumber ?? "";
        $company->addresscomplement = $request->addresscomplement ?? "";
        $company->neighborhood = $request->neighborhood ?? "";
        $company->city = $request->city ?? "";
        $company->state = $request->state ?? "";

        $company->save();

        return redirect()->route('company');
    }
    
    public function images($fileName)
    {        
        $path = 'app/company_' . (Auth::user()->company->id ?? '') . '/' . $fileName;

        if(!file_exists(storage_path($path))){
            return response()->file(public_path('images/no-image.png'));
        }else{         
            return response()->file(storage_path($path));
        }
    }
    
    public function logo()
    {
        return $this->images(Auth::user()->company->logo ?? "logo")->setMaxAge(0);
    }
    
    public function logoUpload(Request $request)
    {        
        $this->checkAccess("ADMIN");
        
        $request->validate([
            'uploadFileObj' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);
  
        $imageName = 'logo.'.$request->uploadFileObj->extension();   
        $path = $request->uploadFileObj->storeAs('/company_' . Auth::user()->company->id, $imageName);

        Auth::user()->company->logo = $imageName;
        Auth::user()->company->save();

        return response()->json([
            'url' => route('company.logo'),
            'success' => 'true',
        ]);
    }
    
    public function users()
    {
        $this->checkAccess("ADMIN");
        
        $users = User::where('company_id', Auth::user()->company->id)->where('id', '<>', Auth::user()->id)->where('is_super_admin', 'false')->get();
        return view('company.users')->with('users', $users);
    }





    // ADMIN    
    public function admin_index()
    {        
        $this->checkIsAdmin();
        
        $companies = Company::get();
        return view('admin.company.index')->with('companies', $companies);
    }
    
    public function admin_new()
    {        
        $this->checkIsAdmin();
        
        $company = new Company();
        return view('admin.company.form')->with('company', $company);
    }
    
    public function admin_edit(Request $request)
    {        
        $this->checkIsAdmin();
        
        $company = Company::find($request->id);
        return view('admin.company.form')->with('company', $company);
    }
    
    public function admin_newPost(Request $request)
    {        
        $this->checkIsAdmin();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'cnpjcpf' => 'required|max:18',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $company = new Company();
        
        $company->name = $request->name;
        $company->description = $request->description ?? "";
        $company->cnpjcpf = $request->cnpjcpf ?? "";
        $company->phone = $request->phone ?? "";
        $company->mobile = $request->mobile ?? "";

        $company->zipcode = $request->zipcode ?? "";   
        $company->address = $request->address ?? "";
        $company->addressnumber = $request->addressnumber ?? "";
        $company->addresscomplement = $request->addresscomplement ?? "";
        $company->neighborhood = $request->neighborhood ?? "";
        $company->city = $request->city ?? "";
        $company->state = $request->state ?? "";
        
        $company->enabled = $request->enabled ?? false;

        $company->save();

        $request->session()->flash('message', 'Empresa criada com sucesso!');

        return redirect()->route('admin.company');
    }
    
    public function admin_editPost(Request $request)
    {        
        $this->checkIsAdmin();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'cnpjcpf' => 'required|max:18',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $company = Company::find($request->id);
        
        $company->name = $request->name;
        $company->description = $request->description ?? "";
        $company->cnpjcpf = $request->cnpjcpf ?? "";
        $company->phone = $request->phone ?? "";
        $company->mobile = $request->mobile ?? "";

        $company->zipcode = $request->zipcode ?? "";   
        $company->address = $request->address ?? "";
        $company->addressnumber = $request->addressnumber ?? "";
        $company->addresscomplement = $request->addresscomplement ?? "";
        $company->neighborhood = $request->neighborhood ?? "";
        $company->city = $request->city ?? "";
        $company->state = $request->state ?? "";

        $company->enabled = $request->enabled ?? false;
        
        $company->save();

        $request->session()->flash('message', 'Empresa atualizada com sucesso!');

        return redirect()->route('admin.company');
    }
    
    public function admin_delete(Request $request)
    {        
        $this->checkIsAdmin();
        
        $company = Company::find($request->id);
        return view('admin.company.delete')->with('company', $company);
    }
    
    public function admin_deletePost(Request $request)
    {        
        $this->checkIsAdmin();
        
        $company = Company::find($request->id);

        User::where('company_id', $company->id)
              ->update(['company_id' => null]);

        $company->delete();

        $request->session()->flash('message', 'Empresa removida com sucesso!');

        return redirect()->route('admin.company');
    }
    
    public function admin_logo($id)
    {        
        $this->checkIsAdmin();
        $path = '';

        $company = Company::find($id);

        if($company){
            $path = 'app/company_' . $company->id . '/' . $company->logo;
        }

        if($company == null || $company->logo == null || !file_exists(storage_path($path))){
            return response()->file(public_path('images/no-image.png'));
        }else{         
            return response()->file(storage_path($path))->setMaxAge(0);
        }
    }
    
    public function admin_logoUpload(Request $request)
    {        
        $this->checkIsAdmin();

        if($request->id == null) abort(500);
        $company = Company::find($request->id);
        if(!$company) abort(500);

        $request->validate([
            'uploadFileObj' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);
  
        $imageName = 'logo.'.$request->uploadFileObj->extension();   
        $path = $request->uploadFileObj->storeAs('/company_' . $company->id, $imageName);

        $company->logo = $imageName;
        $company->save();

        return response()->json([
            'url' => route('admin.company.logo', $company->id),
            'success' => 'true',
        ]);
    }
}
