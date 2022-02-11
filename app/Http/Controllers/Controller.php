<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectStep;
use App\Models\FinanceAccount;
use App\Models\Provider;
use App\Models\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;    

    public function checkAccess(string $userProfile)
    {
        if(!Auth::user()->isAdmin() && !Auth::user()->hasProfile("ADMIN") && !Auth::user()->hasProfile($userProfile)){
            abort(401);
        }
    }
    public function checkAccessArea(string $area)
    {
        if(!Auth::user()->isAdmin() && !Auth::user()->hasProfile("ADMIN") && !Auth::user()->hasProfile($area."_VIEW") && !Auth::user()->hasProfile($area."_EDIT")){
            abort(401);
        }
    }

    public function checkIsAdmin()
    {
        if(!Auth::user()->isAdmin()){
            abort(401);
        }
    }


    
    
    public function getAllUsers($columns = null)
    {
        if($columns != null){
            return User::select($columns)->where('company_id', Auth::user()->company->id)->orderBy('name', 'ASC')->get();
        }else{
            return User::where('company_id', Auth::user()->company->id)->orderBy('name', 'ASC')->get();
        }
    }

    public function getAllFinanceAccounts()
    {
        return FinanceAccount::where('company_id', Auth::user()->company->id)->get();
    }

    public function getAllProviders()
    {        
        $providers = Provider::get();
        foreach($providers as $account) {
            $account->type = 'provider';
            $account->value = 'provider|'.$account->id;
        }
        $providers = $providers->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values()->toArray();

        return $providers;
    }
    
    public function getAllActiveProviders(){
        $providersCount = Provider::where('company_id', Auth::user()->company->id)->count();
        return $providersCount;
    }
    

    public function getAllProjects()
    {        
        return Project::where('company_id', Auth::user()->company->id)->get();
    }  
    
    public function getAllActiveProjects(){
        $projectsCount = Project::where('company_id', Auth::user()->company->id)->count();
        return $projectsCount;
    }

    public function getAllClients()
    {        
        $clients = Client::get();
        foreach($clients as $client) {
            $client->type = 'client';
            $client->value = 'client|'.$client->id;
        }
        $clients = $clients->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values()->toArray();

        return $clients;
    } 
    
    public function getAllActiveClients(){
        $clientsCount = Client::where('company_id', Auth::user()->company->id)->count();
        return $clientsCount;
    }

    public function getAllProjectSteps()
    {
        $status = ProjectStep::get();
        return $status->reject(function ($item, $key) {
            return $item->company <> null && $item->company->id <> Auth::user()->company->id;
        })->values();
    }

    public function getById($categories, $id) {
        foreach($categories as $p) {
            if($p['id'] == $id) {
                return $p;
            }
        }
    } 

    public function getPeopleById($peoples, $id, $type) {
        if($id == null || $type == null) return (object) array('type' => null, 'id' => null, 'value' => null);
        foreach($peoples as $p) {
            if($p['value'] == $type."|".$id) {
                return $p;
            }
        }
    }     

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function formatCurrencyNumber($number) {
        return number_format($number, 2, '.', '');
    } 

    public function getFloatFromString($number) {
        return floatval(str_replace(',','.',str_replace('.','',$number)));
    } 
}
