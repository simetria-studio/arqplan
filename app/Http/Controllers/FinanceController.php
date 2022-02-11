<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\FinanceTransactionCategory;
use App\Models\Client;
use App\Models\Provider;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->checkAccessArea('FINANCE');

        $accounts = $this->getAllFinanceAccountWithTransactions();

        return view('finance.index')
            ->with('accounts', $accounts)
            ->with('categories', $this->getAllFinanceTransactionCategories())
            ->with('peoples', $this->getAllFinanceTransactionPeoples());
    }
    
    public function edit(Request $request)
    {
        $this->checkAccess('FINANCE_EDIT');

        $account = FinanceAccount::find($request->id);
        return view('finance.form')->with('account', $account);
    }

    public function new()
    {
        $this->checkAccess('FINANCE_EDIT');
        return view('finance.new');
    }
    
    public function newPost(Request $request)
    {  
        $this->checkAccess('FINANCE_EDIT');

        $account = new FinanceAccount();
        
        $account->company_id = Auth::user()->company->id;
        $account->name = $request->name;
        $account->description = $request->description ?? "";
        $account->initial = $request->initial ?? 0;

        $account->save();

        $request->session()->flash('message', 'Conta criada com sucesso!'); 

        return redirect()->route('provider');
    }
    
    public function editPost(Request $request)
    {        
        $this->checkAccess('FINANCE_EDIT');
        
        $account->company_id = Auth::user()->company->id;
        $account->name = $request->name;
        $account->description = $request->description ?? "";
        $account->initial = $request->initial ?? 0;

        $account->save();

        $request->session()->flash('message', 'Conta atualizada com sucesso!');

        return redirect()->route('provider');
    }
    
    public function delete(Request $request)
    {
        $this->checkAccess('FINANCE_EDIT');

        $account = FinanceAccount::find($request->id);
        return view('provider.delete')->with('provider', $account);
    }
    
    public function deletePost(Request $request)
    {  
        $this->checkAccess('FINANCE_EDIT');

        $account = FinanceAccount::find($request->id); 

        $account->delete();

        $request->session()->flash('message', 'Conta removida com sucesso!'); 

        return redirect()->route('provider');
    }






    

    private function getAllFinanceAccountWithTransactions(){
        $accounts = $this->getAllFinanceAccounts();

        foreach ($accounts as $key => $account) {
            $account->balance = $account->initial;

            $transactions = $this->getAllFinanceTransactions($account->id);

            for($i=0; $i < count($transactions); $i++){
                $transactions[$i]->year = date('Y', strtotime($transactions[$i]->date));
                $transactions[$i]->month = date('m', strtotime($transactions[$i]->date));

                if($transactions[$i]->status == "OK" && $transactions[$i]->date < new \Datetime())
                    $account->balance += $transactions[$i]->type == "D" ? -$transactions[$i]->amount : $transactions[$i]->amount;                    
            }
        }

        return $accounts;
    }

    private function getFinanceAccountById($id)
    {
        return FinanceAccount::where('company_id', Auth::user()->company->id)->where('id', $id)->first();
    }

    private function getFinanceTransactionById($id)
    {
        return FinanceTransaction::where('company_id', Auth::user()->company->id)->where('id', $id)->first();
    }
    

    private function getAllFinanceTransactions(int $account_id)
    {
        $account = $this->getFinanceAccountById($account_id);
        if($account == null) return [];

        $transactions = FinanceTransaction::where('financeaccount_id', $account->id)->orderBy('date', 'ASC')->get();
        $categories = $this->getAllFinanceTransactionCategories();
        $peoples = $this->getAllFinanceTransactionPeoples();

        if($account->initial == null || $account->initial == ""){
            $account->initial = 0;
        }

        $balance = $account->initial;

        for ($i=0; $i < count($transactions); $i++) { 
            $transactions[$i]->category = $this->getById($categories, $transactions[$i]->financecategory_id);
            $transactions[$i]->people = $this->getPeopleById($peoples, $transactions[$i]->people_id, $transactions[$i]->people_type);
            $transactions[$i]->amount = $this->formatCurrencyNumber($transactions[$i]->amount);
            $transactions[$i]->year = date('Y', strtotime($transactions[$i]->date));
            $transactions[$i]->month = date('m', strtotime($transactions[$i]->date));
            $transactions[$i]->day = date('d', strtotime($transactions[$i]->date));
            $balance = $this->formatCurrencyNumber($transactions[$i]->type == "D" ? $balance - $transactions[$i]->amount :  $balance + $transactions[$i]->amount);
            $transactions[$i]->balance = $balance;

            if($transactions[$i]->parceled == true){
                $transactions[$i]->parcels = $this->getParcels($transactions[$i]->parceledId);
            }

        }
        $balance = $this->formatCurrencyNumber($balance);

        return $transactions;
    }

    private function getAllCategories()
    {
        $categories = FinanceTransactionCategory::get();
        return $categories->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values();
    } 

    private function getAllFinanceTransactionCategories()
    {
        $categories = FinanceTransactionCategory::get();
        return $categories->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values();
    }

    private function getAllFinanceTransactionPeoples()
    {
        $peoples = [];
        
        $clients = Client::get();
        foreach($clients as $client) {
            $client->type = 'client';
            $client->value = 'client|'.$client->id;
        }
        $clients = $clients->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values()->toArray();
        
        $accounts = Provider::get();
        foreach($accounts as $account) {
            $account->type = 'provider';
            $account->value = 'provider|'.$account->id;
        }
        $accounts = $accounts->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values()->toArray();

        $result = array_merge($clients, $accounts);

        return  $result;
    }

    private function getParcels($parceledId){
        $parcels = FinanceTransaction::where('parceledId', $parceledId)->orderBy('parcelNumber', 'ASC')->get();
        foreach ($parcels as $value) {
            $value->amount = $this->formatCurrencyNumber($value->amount);
        }
        return $parcels->reject(function ($item, $key) {
            return $item->company_id <> null && $item->company_id <> Auth::user()->company->id;
        })->values();
    }



    //CATEGORY    
    public function category_index()
    {
        $this->checkAccessArea('FINANCE');

        return view('finance.category.index')->with('categories', $this->getAllCategories());
    }
    
    public function category_new()
    {
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = new FinanceTransactionCategory();

        return view('finance.category.form')
            ->with('financeTransactionCategory', $financeTransactionCategory);
    }
    
    public function category_edit(Request $request)
    {
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = FinanceTransactionCategory::find($request->id);

        if(!$financeTransactionCategory->company_id){
            abort(401);
        }

        return view('finance.category.form')
            ->with('financeTransactionCategory', $financeTransactionCategory);
    }
    
    public function category_newPost(Request $request)
    {  
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = new FinanceTransactionCategory();
        
        $financeTransactionCategory->company_id = Auth::user()->company->id;
        $financeTransactionCategory->name = $request->name;

        $financeTransactionCategory->save();

        $request->session()->flash('message', 'Categoria criada com sucesso!'); 

        return redirect()->route('finance.category');
    }
    
    public function category_editPost(Request $request)
    {        
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = FinanceTransactionCategory::find($request->id);

        if(!$financeTransactionCategory->company_id){
            abort(401);
        }
        
        $financeTransactionCategory->company_id = Auth::user()->company->id;
        $financeTransactionCategory->name = $request->name;

        $financeTransactionCategory->save();

        $request->session()->flash('message', 'Categoria atualizada com sucesso!');

        return redirect()->route('finance.category');
    }
    
    public function category_delete(Request $request)
    {
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = FinanceTransactionCategory::find($request->id);

        if(!$financeTransactionCategory->company_id){
            abort(401);
        }
        return view('finance.category.delete')->with('financeTransactionCategory', $financeTransactionCategory);
    }
    
    public function category_deletePost(Request $request)
    {  
        $this->checkAccess('FINANCE_EDIT');

        $financeTransactionCategory = FinanceTransactionCategory::find($request->id); 

        if(!$financeTransactionCategory->company_id){
            abort(401);
        }

        $financeTransactionCategory->delete();

        $request->session()->flash('message', 'Categoria removida com sucesso!'); 

        return redirect()->route('finance.category');
    }












    // API     
    public function api_get_accounts()
    {
        $this->checkAccessArea('FINANCE');

        $accounts = $this->getAllFinanceAccountWithTransactions();
        $categories = $this->getAllFinanceTransactionCategories();
        $peoples = $this->getAllFinanceTransactionPeoples();
        $projects = $this->getAllProjects();

        return response([ 'accounts' => $accounts, 'categories' => $categories, 'peoples' => $peoples, 'projects' => $projects, 'message' => 'Retrieved successfully'], 200);
    }   

    public function api_get_transactions(int $accountId)
    {
        $this->checkAccessArea('FINANCE');

        $transactions = $this->getAllFinanceTransactions($accountId);

        return response([ 'transactions' => $transactions, 'message' => 'Retrieved successfully'], 200);
    }   

    public function api_get_last_transactions()
    {
        $this->checkAccessArea('FINANCE');
        
        $accounts = $this->getAllFinanceAccounts();
        $transactions = FinanceTransaction::where('company_id', Auth::user()->company->id)
        ->where(function ($query) {
            $today = Carbon::now()->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->addDay(11)->hour(0)->minute(0)->second(0)->millisecond(0);

            $query->where('date', '>', $today)->where('date', '<=', $lastDay);
        })
        ->orderBy('date', 'ASC')->take(10)->get();

        for ($i=0; $i < count($transactions); $i++) {
            $transactions[$i]->account = $this->getById($accounts, $transactions[$i]->financeaccount_id)->name;
            $transactions[$i]->formatedDate = date('d/m/Y', strtotime($transactions[$i]->date));
        }
        
        return response([ 'transactions' => $transactions, 'message' => 'Retrieved successfully'], 200);
    } 

    public function api_get_to_pay_range($range)
    {
        $this->checkAccessArea('FINANCE');
        if($range == "week"){
            $today = Carbon::now()->startOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->endOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);
        }elseif($range == "nextmonth"){
            $today = Carbon::now()->day(1)->addMonth(1)->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->day(1)->addMonth(2)->hour(0)->minute(0)->second(0)->millisecond(0);
        }else{
            $today = Carbon::now()->day(1)->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->day(1)->addMonth(1)->hour(0)->minute(0)->second(0)->millisecond(0);
        }
        
        $accounts = $this->getAllFinanceAccounts();
        $transactions = FinanceTransaction::where('company_id', Auth::user()->company->id)
        ->where('type', "D")
        ->where(function ($query) use($today, $lastDay) {
            $query->where('date', '>=', $today)->where('date', '<=', $lastDay);
        })
        ->orderBy('date', 'ASC')->get();

        $amount = 0;
        for ($i=0; $i < count($transactions); $i++) {
            $transactions[$i]->account = $this->getById($accounts, $transactions[$i]->financeaccount_id)->name;
            $transactions[$i]->formatedDate = date('d/m/Y', strtotime($transactions[$i]->date));
            $amount += (float) $transactions[$i]->amount;
        }
        
        return response([ 'amount' => $amount, 'transactions' => $transactions, 'message' => 'Retrieved successfully'], 200);
    }

    public function api_get_to_receive_range($range)
    {
        $this->checkAccessArea('FINANCE');
        if($range == "week"){
            $today = Carbon::now()->startOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->endOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);
        }elseif($range == "nextmonth"){
            $today = Carbon::now()->day(1)->addMonth(1)->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->day(1)->addMonth(2)->hour(0)->minute(0)->second(0)->millisecond(0);
        }else{
            $today = Carbon::now()->day(1)->hour(0)->minute(0)->second(0)->millisecond(0);
            $lastDay = Carbon::now()->day(1)->addMonth(1)->hour(0)->minute(0)->second(0)->millisecond(0);
        }
        
        $accounts = $this->getAllFinanceAccounts();
        $transactions = FinanceTransaction::where('company_id', Auth::user()->company->id)
        ->where('type', "C")
        ->where(function ($query) use($today, $lastDay) {
            $query->where('date', '>=', $today)->where('date', '<=', $lastDay);
        })
        ->orderBy('date', 'ASC')->get();

        $amount = 0;
        for ($i=0; $i < count($transactions); $i++) {
            $transactions[$i]->account = $this->getById($accounts, $transactions[$i]->financeaccount_id)->name;
            $transactions[$i]->formatedDate = date('d/m/Y', strtotime($transactions[$i]->date));
            $amount += (float) $transactions[$i]->amount;
        }
        
        return response([ 'amount' => $amount, 'transactions' => $transactions, 'message' => 'Retrieved successfully'], 200);
    }

    public function api_account_create(Request $request)
    {
        $this->checkAccessArea('FINANCE_EDIT');
        
        if($request->name == null ||  $request->name == "") return response()->json(['message' => "Nome deve ser preenchido"], 500);
        if($request->description == null ||  $request->description == "") $request->description = "";
        if($request->initial == null ||  $request->initial == "") $request->initial = 0;


        $account = new FinanceAccount();
        $account->company_id = Auth::user()->company->id;
        $account->name = $request->name;
        $account->description = $request->description;

        if(str_contains($request->initial, ",")) $request->initial = str_replace(',','.',str_replace('.','',$request->initial));
        $account->initial = $request->initial;
        $account->agency = $request->agency;
        $account->account = $request->account;

        $account->save();
        
        return "ok";        
    }

    public function api_account_update(Request $request, $accountId)
    {
        $this->checkAccessArea('FINANCE_EDIT');
        $account = $this->getFinanceAccountById($accountId);
        if( $account == null ||  $account == "") return response()->json(['message' => "Não possui permissões a essa conta"], 500);

        if($request->name == null ||  $request->name == "") return response()->json(['message' => "Nome deve ser preenchido"], 500);
        if($request->description == null ||  $request->description == "") $request->description = "";
        if($request->initial == null ||  $request->initial == "") $request->initial = 0;
        if(str_contains($request->initial, ",")) $request->initial = str_replace(',','.',str_replace('.','',$request->initial));
        
        $account->name = $request->name;
        $account->description = $request->description;
        $account->initial = $request->initial;
        $account->agency = $request->agency;
        $account->account = $request->account;

        $account->save();

        return "ok";
        
    }

    public function api_account_remove(Request $request, $accountId)
    {
        $this->checkAccessArea('FINANCE_EDIT');
        $account = $this->getFinanceAccountById($accountId);
        if( $account == null ||  $account == "") return response()->json(['message' => "Não possui permissões a essa conta"], 500);

        $account->delete();

        return "ok";
        
    }
    
    public function api_add_transaction(Request $request, $accountId)
    {
        $this->checkAccessArea('FINANCE');
        $account = $this->getFinanceAccountById($accountId);
        if( $account == null ||  $account == "") return response()->json(['message' => "Não possui permissões a essa conta"], 500);

        if( $accountId == null ||  $accountId == "") return response()->json(['message' => "Conta deve ser preenchido"], 500);
        if( $request->date == null ||  $request->date == "") return response()->json(['message' => "Data deve ser preenchido"], 500);
        if( $request->type == null ||  $request->type == "") return response()->json(['message' => "Tipo deve ser preenchido"], 500);
        if( $request->amount == null ||  $request->amount == "") return response()->json(['message' => "Valor deve ser preenchido"], 500);
        if( $request->status == null ||  $request->status == "") return response()->json(['message' => "Status deve ser preenchido"], 500);
        if($request->description == null ||  $request->description == "") $request->description = "";
        if( $request->financecategory_id == null || $request->financecategory_id == "" || $request->financecategory_id == "null") $request->financecategory_id = null;
        if( $request->people_id == null ||  $request->people_id == "") $request->people_id = 0;
        if( $request->project_id == null || $request->project_id == "" || $request->project_id == "null") $request->project_id = null;
        if(str_contains($request->amount, ",")) $request->amount = str_replace(',','.',str_replace('.','',$request->amount));

        $transaction =  new FinanceTransaction();
        $transaction->company_id = Auth::user()->company->id;
        $transaction->financeaccount_id = $account->id;

        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->description = $request->description;
        $transaction->financecategory_id = $request->financecategory_id;
        $transaction->project_id = $request->project_id;
        $transaction->amount = (float)$request->amount;
        $transaction->status = $request->status;

        if( $request->people != null && $request->people != "" && $request->people != "null" && $request->people['value'] != null && $request->people['value'] != "" && $request->people['value'] != "null"){
            $peoplePiece = explode("|", $request->people['value']);
            $transaction->people_type = $peoplePiece[0];
            $transaction->people_id = $peoplePiece[1];
        }else{
            $transaction->people_type = null;
            $transaction->people_id = null;
        }

        if(isset($request->recurrent) && $request->recurrent == true){
            $transaction->recurrent = $request->recurrent;
            $transaction->recurrentId = $this->generateRandomString(20);
            $transaction->recurrentType = $request->recurrentType;
            $transaction->recurrentLimit = $request->recurrentLimit;
            $transaction->recurrentLimitTimes = $request->recurrentLimitTimes;
            $transaction->recurrentLimitDate = new \DateTime($request->recurrentLimitDate);
            $transaction->recurrentWeekday2 = $request->recurrentWeekday2;
            $transaction->recurrentWeekday3 = $request->recurrentWeekday3;
            $transaction->recurrentWeekday4 = $request->recurrentWeekday4;
            $transaction->recurrentWeekday5 = $request->recurrentWeekday5;
            $transaction->recurrentWeekday6 = $request->recurrentWeekday6;
            $transaction->recurrentWeekdayS = $request->recurrentWeekdayS;
            $transaction->recurrentWeekdayD = $request->recurrentWeekdayD;
        }else if(isset($request->parceled) && $request->parceled == true){
            $transaction->parceled = true;
            $transaction->parceledId = $this->generateRandomString(20);
            $transaction->parcelNumber = 1;
            $transaction->parceledTimes = $request->parceledTimes;
            $transaction->amount = $this->getFloatFromString($request->parcels[0]['amount']);
            $transaction->status = $request->parcels[0]['status'];
            $transaction->date = $request->parcels[0]['date'];
        }
        
        $transaction->save();

        if($transaction->recurrent == true){
            $this->createRecurrency($transaction);
        }else if(isset($request->parceled) && $request->parceled == true){
            $this->createParceled($transaction, $request->parcels);
        }

        return "ok";
    }
    
    public function api_update_transaction(Request $request, $accountId, $transactionId)
    {
        $this->checkAccessArea('FINANCE');
        $account = $this->getFinanceAccountById($accountId);
        if( $account == null ||  $account == "") return response()->json(['message' => "Não possui permissões a essa conta"], 500);
        
        $transaction = $this->getFinanceTransactionById($transactionId);
        if( $transaction == null ||  $transaction == "") return response()->json(['message' => "Transação não encontrada"], 500);

        if( $request->date == null ||  $request->date == "") return response()->json(['message' => "Data deve ser preenchido"], 500);
        if( $request->type == null ||  $request->type == "") return response()->json(['message' => "Tipo deve ser preenchido"], 500);
        if( $request->amount == null ||  $request->amount == "") return response()->json(['message' => "Valor deve ser preenchido"], 500);
        if( $request->status == null ||  $request->status == "") return response()->json(['message' => "Status deve ser preenchido"], 500);
        if( $request->financecategory_id == null || $request->financecategory_id == "" || $request->financecategory_id == "null") $request->financecategory_id = null;
        if( $request->project_id == null || $request->project_id == "" || $request->project_id == "null") $request->project_id = null;
        if($request->description == null ||  $request->description == "") $request->description = "";
        if(str_contains($request->amount, ",")) $request->amount = str_replace(',','.',str_replace('.','',$request->amount));

        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->description = $request->description;
        $transaction->financecategory_id = $request->financecategory_id;
        $transaction->project_id = $request->project_id;
        $transaction->amount = (float)$request->amount;
        $transaction->status = $request->status;

        if( $request->people != null && $request->people != "" && $request->people != "null" && $request->people['value'] != null && $request->people['value'] != "" && $request->people['value'] != "null"){
            $peoplePiece = explode("|", $request->people['value']);
            $transaction->people_type = $peoplePiece[0];
            $transaction->people_id = $peoplePiece[1];
        }else{
            $transaction->people_type = null;
            $transaction->people_id = null;
        }

        if(isset($transaction->recurrentId) && $request->updateRecurrentMode == "A"){
            $this->removeRecurrency($transaction->recurrentId);
            $transaction = $transaction->replicate();
        }else if(isset($transaction->parceledId) && $transaction->parceled == true){
            $this->removeParcels($transaction->parceledId);
            $transaction = $transaction->replicate();
        }  

        if(isset($request->recurrent) && $request->recurrent == true){
            $transaction->recurrent = $request->recurrent;
            $transaction->recurrentType = $request->recurrentType;
            $transaction->recurrentLimitDate = $request->recurrentLimitDate;
            $transaction->recurrentLimitTimes = $request->recurrentLimitTimes;
            $transaction->recurrentLimitDate = new \DateTime($request->recurrentLimitDate);
            $transaction->recurrentWeekday2 = $request->recurrentWeekday2;
            $transaction->recurrentWeekday3 = $request->recurrentWeekday3;
            $transaction->recurrentWeekday4 = $request->recurrentWeekday4;
            $transaction->recurrentWeekday5 = $request->recurrentWeekday5;
            $transaction->recurrentWeekday6 = $request->recurrentWeekday6;
            $transaction->recurrentWeekdayS = $request->recurrentWeekdayS;
            $transaction->recurrentWeekdayD = $request->recurrentWeekdayD;
        } else if(isset($request->parceled) && $request->parceled == true){
            $transaction->parcelNumber = 1;
            $transaction->parceledTimes = $request->parceledTimes;
            $transaction->amount = $this->getFloatFromString($request->parcels[0]['amount']);
            $transaction->status = $request->parcels[0]['status'];
            $transaction->date = $request->parcels[0]['date'];
        }

        $transaction->save();

        if($transaction->recurrent == true && $request->updateRecurrentMode == "A"){
            $this->createRecurrency($transaction);
        }else if($transaction->parceled == true){
            $this->createParceled($transaction, $request->parcels);
        }

        return "ok";
    }
    
    public function api_delete_transaction(Request $request, $accountId, $transactionId, $recurrentMode)
    {
        $this->checkAccessArea('FINANCE');
        $account = $this->getFinanceAccountById($accountId);
        if( $account == null ||  $account == "") return response()->json(['message' => "Não possui permissões a essa conta"], 500);
        
        $transaction = $this->getFinanceTransactionById($transactionId);
        if( $transaction == null ||  $transaction == "") return response()->json(['message' => "Transação não encontrada"], 500);


        if(isset($transaction->recurrentId) && $recurrentMode == "A"){
            $this->removeRecurrency($transaction->recurrentId);
            return "ok";
        }
        if(isset($transaction->parceledId)){
            $this->removeParcels($transaction->parceledId);
            return "ok";
        }

        $transaction->delete();
        return "ok";
    }


    public function api_get_report_data(Request $request){
        $this->checkAccessArea('FINANCE');

        if(isset($request->account) && $request->account > 0){
            $transactions = FinanceTransaction::where('company_id', Auth::user()->company->id)->where('financeaccount_id', $request->account);
        }else{
            $transactions = FinanceTransaction::where('company_id', Auth::user()->company->id);
        }        

        if(isset($request->type)){
            $transactions = $transactions->where('type', $request->type);
        }

        if(isset($request->people) && $request->people > 0 && isset($request->peopletype)){
            $transactions = $transactions->where('people_id', $request->people)->where('people_type', $request->peopletype);
        }

        if(isset($request->startDate)){
            $transactions = $transactions->whereDate('date', '>=', $request->startDate);
        }

        if(isset($request->endDate)){
            $transactions = $transactions->whereDate('date', '<=', $request->endDate);
        }

        if(isset($request->state) && $request->state != "0"){
            $transactions = $transactions->where('STATUS', $request->state);
        }

        $transactions = $transactions->orderBy('date', 'ASC')->get();
        $accounts = $this->getAllFinanceAccounts();
        $categories = $this->getAllFinanceTransactionCategories();
        $peoples = $this->getAllFinanceTransactionPeoples();

        for ($i=0; $i < count($transactions); $i++) { 
            $transactions[$i]->account = $this->getById($accounts, $transactions[$i]->financeaccount_id);
            $transactions[$i]->category = $this->getById($categories, $transactions[$i]->financecategory_id);
            $transactions[$i]->people = $this->getPeopleById($peoples, $transactions[$i]->people_id, $transactions[$i]->people_type);
            $transactions[$i]->amount = (float)str_replace(',','',$transactions[$i]->amount);
            $transactions[$i]->year = date('Y', strtotime($transactions[$i]->date));
            $transactions[$i]->month = date('m', strtotime($transactions[$i]->date));
            $transactions[$i]->day = date('d', strtotime($transactions[$i]->date));
        }
        
        return response([ 'transactions' => $transactions, 'message' => 'Retrieved successfully'], 200);
    }






    

    private function createRecurrency(FinanceTransaction $transaction)
    {
        $times = 1000;
        if($transaction->recurrentLimitTimes > $times) $transaction->recurrentLimitTimes = $times;

        if($transaction->recurrentType == "D"){
            if($transaction->recurrentLimit == "N"){
                $times = $transaction->recurrentLimitTimes;
            }

            $limitDay = new Carbon($transaction->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $transaction = $transaction->replicate();
                $transactionDate = new Carbon($transaction->date);
                $interval = new \DateInterval('P1D');
                $transaction->date = $transactionDate->add($interval);
                
                if($transaction->recurrentLimit == "U" && $transactionDate > $limitDay){
                    return;
                }
                $transaction->save();
            }
        }elseif($transaction->recurrentType == "W"){
            if($transaction->recurrentLimit == "N"){
                $weeks = $transaction->recurrentLimitTimes;
                
                $limitDay = new Carbon($transaction->date);
                $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add($weeks*7 - 1, 'day');
            }elseif($transaction->recurrentLimit == "U"){          
                $limitDay = new Carbon($transaction->recurrentLimitDate);
            }
            

            for ($i=1; $i < $times; $i++) { 
                $transaction = $transaction->replicate();
                $transactionDate = new Carbon($transaction->date);
                $interval = new \DateInterval('P1D');
                $transaction->date = $transactionDate->add($interval);

                if(($transaction->recurrentLimit == "U" || $transaction->recurrentLimit == "N") && $transactionDate > $limitDay){
                    return;
                }

                if($this->weekdayIsSelectedOnEvent($transaction, $transactionDate)) $transaction->save();
            }
        }elseif($transaction->recurrentType == "M"){
            if($transaction->recurrentLimit == "N"){
                $times = $transaction->recurrentLimitTimes;
            }

            $limitDay = new Carbon($transaction->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $transaction = $transaction->replicate();
                $transactionDate = new Carbon($transaction->date);
                $interval = new \DateInterval('P1M');
                $transaction->date = $transactionDate->add($interval);
                
                if($transaction->recurrentLimit == "U" && $transactionDate > $limitDay){
                    return;
                }
                $transaction->save();
            }
        }elseif($transaction->recurrentType == "Y"){
            if($transaction->recurrentLimit == "N"){
                $times = $transaction->recurrentLimitTimes;
            }

            $limitDay = new Carbon($transaction->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $transaction = $transaction->replicate();
                $transactionDate = new Carbon($transaction->date);
                $interval = new \DateInterval('P1Y');
                $transaction->date = $transactionDate->add($interval);
                
                if($transaction->recurrentLimit == "U" && $transactionDate > $limitDay){
                    return;
                }
                $transaction->save();
            }
        }
    }

    private function removeRecurrency(string $recurrentId)
    {
        $transaction = FinanceTransaction::where('recurrentId', $recurrentId)->where('company_id', Auth::user()->company->id);
        $transaction->delete();
    }

    private function removeParcels(string $parceledId)
    {
        $transaction = FinanceTransaction::where('parceledId', $parceledId)->where('company_id', Auth::user()->company->id);
        $transaction->delete();
    }    

    private function createParceled(FinanceTransaction $transaction, array $parcels)
    {
        for ($i=2; $i <= $transaction->parceledTimes; $i++) { 
            $transaction = $transaction->replicate();
            $transaction->date = new Carbon($parcels[$i-1]['date']);     
            $transaction->amount = $this->getFloatFromString($parcels[$i-1]['amount']);
            $transaction->parcelNumber = $parcels[$i-1]['parcelNumber'];
            $transaction->parceledTimes = $parcels[$i-1]['parceledTimes'];
            $transaction->status = $parcels[$i-1]['status'];
            
            $transaction->save();
        }     
    }

    public function weekdayIsSelectedOnEvent(FinanceTransaction $transaction, \Datetime $date)
    {
        return (($transaction->recurrentWeekdayD == true && $date->format('w') == 0) ||
            ($transaction->recurrentWeekday2 == true && $date->format('w') == 1) ||
            ($transaction->recurrentWeekday3 == true && $date->format('w') == 2) ||
            ($transaction->recurrentWeekday4 == true && $date->format('w') == 3) ||
            ($transaction->recurrentWeekday5 == true && $date->format('w') == 4) ||
            ($transaction->recurrentWeekday6 == true && $date->format('w') == 5) ||
            ($transaction->recurrentWeekdayS == true && $date->format('w') == 6)
        );
    }
};