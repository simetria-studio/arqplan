<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade;
use Illuminate\Support\Facades\Auth;

use App\Models\FinanceAccount;
use App\Models\ReportData;
use App\Models\ProjectCategory;
use App\Models\Client;
use App\Models\Provider;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->checkAccessArea('REPORT');
        return view('report.index');
    }

    public function finance_topay()
    {
        $this->checkAccessArea('REPORT');
        
        $report = new ReportData();
        $report->title = "Financeiro - Contas à Pagar";
        $report->mode = "finance.topay";
        $now = new \DateTime();
        $report->startDate = $now->format('Y-m-d');
        $now->add(new \DateInterval('P1M'));
        $report->endDate = $now->format('Y-m-d');
        $report->accounts = $this->getAllFinanceAccounts();
        $report->peoples = $this->getAllProviders();
        $report->peoplesType = 'provider';

        return view('report.report')->with('report', $report);
    }

    public function finance_toreceive()
    {
        $this->checkAccessArea('REPORT');
        
        $report = new ReportData();
        $report->title = "Financeiro - Contas à Receber";
        $report->mode = "finance.toreceive";
        $now = new \DateTime();
        $report->startDate = $now->format('Y-m-d');
        $now->add(new \DateInterval('P1M'));
        $report->endDate = $now->format('Y-m-d');
        $report->accounts = $this->getAllFinanceAccounts();
        $report->peoples = $this->getAllClients();
        $report->peoplesType = 'client';

        return view('report.report')->with('report', $report);
    }

    public function projects()
    {
        $this->checkAccessArea('REPORT');
        
        $report = new ReportData();
        $report->title = "Projetos";
        $report->mode = "projects";
        $now = new \DateTime();
        $report->startDate = $now->format('Y-m-d');
        $now->add(new \DateInterval('P1M'));
        $report->endDate = $now->format('Y-m-d');
        $report->clients = $this->getAllClients();
        $report->owners = $this->getAllUsers();   
        $report->categories = $this->getAllCategories();  
        $report->steps = $this->getAllProjectSteps();    

        return view('report.report')->with('report', $report);
    }

    public function calendar()
    {
        $this->checkAccessArea('REPORT');
        
        $report = new ReportData();
        $report->title = "Lista de agendamentos";
        $report->mode = "calendar";
        $now = new \DateTime();
        $report->startDate = $now->format('Y-m-d');
        $now->add(new \DateInterval('P1M'));
        $report->endDate = $now->format('Y-m-d');
        $report->projects = $this->getAllProjects();
        $report->clients = $this->getAllClients();
        $report->users = $this->getAllUsers();   
        $report->types = ["E", "R", "T"];    

        return view('report.report')->with('report', $report);
    }

    public function finance_topay_pdf(Request $request)
    {  
        $this->checkAccessArea('REPORT');

        $reportData = $request->reportData;
        if($reportData == null || $reportData == "") return response()->json(['message' => "Relatório sem dados"], 500);

        $pdf = \PDF::loadView('report.finance_topay.pdf', $reportData);
        return $pdf->stream();
    }









    

    private function getAllCategories()
    {
        $categories = ProjectCategory::get();
        return $categories->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->company->id <> Auth::user()->company->id;
        })->values();
    }

    private function getAllPeoples()
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
}
