<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Client;
use App\Models\Product;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectStep;
use Illuminate\Http\Request;
use App\Models\ProjectStatus;
use Illuminate\Http\Response;
use App\Models\ProjectCategory;
use App\Models\ProjectStepStatus;
use App\Models\ProjectToProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->checkAccessArea('PROJECT');

        $projects = Project::where('company_id', Auth::user()->company->id)->get();
        return view('project.index')->with('projects', $projects)->with('categories', $this->getAllCategories());
    }

    public function new()
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = new Project();
        $project->startDate = new \DateTime();
        $project->endDate = new \DateTime();
        $project->endDate = $project->endDate->modify('+5 days');

        return view('project.form')->with('project', $project)
            ->with('users', $this->getAllUsers())
            ->with('clients', $this->getAllClients())
            ->with('categories', $this->getAllCategories())
            ->with('allStatus', $this->getAllStatus())
            ->with('allSteps', $this->getAllProjectSteps());
    }

    public function show(Request $request)
    {
        $this->checkAccessArea('PROJECT');

       $userFinance = auth()->user()->profiles->map(function($query)
       { return $query->code; });

        $project = Project::where('company_id', Auth::user()->company->id)->where('code', $request->code)->first();
        $products = Product::where('user_id', Auth::user()->company->id)->get();
        $projandprods = ProjectToProduct::where('project_id', $project->id)->with('products')->get();
// dd(auth()->user()->profiles->map(function($query){ return $query->code;  }));

        return view('project.show', get_defined_vars())->with('project', $project)
            ->with('users', $this->getAllUsers())
            ->with('clients', $this->getAllClients())
            ->with('categories', $this->getAllCategories())
            ->with('allStatus', $this->getAllStatus())
            ->with('allSteps', $this->getAllProjectSteps())
            ->with('nextEvents', $this->getNextEvents($project->id));
    }

    public function edit(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = Project::where('company_id', Auth::user()->company->id)->where('code', $request->code)->first();

        return view('project.form')->with('project', $project)
            ->with('users', $this->getAllUsers())
            ->with('clients', $this->getAllClients())
            ->with('categories', $this->getAllCategories())
            ->with('allStatus', $this->getAllStatus())
            ->with('allSteps', $this->getAllProjectSteps());
    }

    public function newPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = new Project();

        $project->code = $this->getNewCode();
        $project->company_id = Auth::user()->company->id;
        $project->client_id = $request->client;
        $project->category_id = $request->category ?? null;
        $project->name = $request->name;
        $project->scope = $request->scope ?? "";
        $project->startDate = new \DateTime();
        $project->endDate = new \DateTime();
        $project->endDate = $project->endDate->modify('+5 days');

        $project->save();

        $project_steps = ProjectStepStatus::where('project_id', $project->id);
        $project_steps->delete();

        for ($i = 0; $i < 30; $i++) {
            if($request['step_'.$i] != null && $request['status_'.$i] != null){
                if($request['position_'.$i] == 99) $request['position_'.$i] = 98;

                $project_step_status = new ProjectStepStatus();
                $project_step_status->project_id = $project->id;
                $project_step_status->project_step_id = $request['step_'.$i];
                $project_step_status->project_status_id = $request['status_'.$i];
                $project_step_status->endDate = $request['endDate_'.$i];
                $project_step_status->position = $request['position_'.$i];
                $project_step_status->save();
            }
        }

        $request->session()->flash('message', 'Projeto criado com sucesso!');

        return redirect()->route('project.show', $project->code);
    }

    public function editPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = Project::where('company_id', Auth::user()->company->id)->where('code', $request->code)->first();

        $project->client_id = $request->client;
        $project->category_id = $request->category ?? null;
        $project->name = $request->name;
        $project->scope = $request->scope ?? "";
        $project->startDate = new \DateTime($request->start);
        $project->endDate = new \DateTime($request->end);

        $responsibles = $request->responsibles ? User::where('company_id', Auth::user()->company->id)->whereIn('id', $request->responsibles)->get() : null;

        $project->responsibles()->sync($responsibles);

        $project->save();

        $project_steps = ProjectStepStatus::where('project_id', $project->id);
        $project_steps->delete();

        for ($i = 0; $i < 30; $i++) {
            if($request['step_'.$i] != null && $request['status_'.$i] != null){
                if($request['position_'.$i] == 99) $request['position_'.$i] = 98;

                $project_step_status = new ProjectStepStatus();
                $project_step_status->project_id = $project->id;
                $project_step_status->project_step_id = $request['step_'.$i];
                $project_step_status->project_status_id = $request['status_'.$i];
                $project_step_status->endDate = $request['endDate_'.$i];
                $project_step_status->position = $request['position_'.$i];
                $project_step_status->save();
            }
        }

        $request->session()->flash('message', 'Projeto atualizado com sucesso!');

        return redirect()->route('project.show', $project->code);
    }

    public function delete(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = Project::where('company_id', Auth::user()->company->id)->where('code', $request->code)->first();
        return view('project.delete')->with('project', $project);
    }

    public function deletePost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $project = Project::where('company_id', Auth::user()->company->id)->where('code', $request->code)->first();

        $project->delete();

        $request->session()->flash('message', 'Projeto removido com sucesso!');

        return redirect()->route('project');
    }




    //CATEGORY
    public function category_index()
    {
        $this->checkAccessArea('PROJECT');

        return view('project.category.index')->with('categories', $this->getAllCategories());
    }

    public function category_new()
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = new ProjectCategory();

        return view('project.category.form')
            ->with('projectCategory', $projectCategory)
            ->with('clients', $this->getAllClients())
            ->with('categories', $this->getAllCategories());
    }

    public function category_edit(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = ProjectCategory::find($request->id);

        if($projectCategory->is_public == 1){
            abort(401);
        }

        return view('project.category.form')
            ->with('projectCategory', $projectCategory)
            ->with('allStatus', $this->getAllStatus());
    }

    public function category_newPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = new ProjectCategory();

        $projectCategory->company_id = Auth::user()->company->id;
        $projectCategory->name = $request->name;
        $projectCategory->is_public = 0;

        $projectCategory->save();

        $request->session()->flash('message', 'Categoria criada com sucesso!');

        return redirect()->route('project.category');
    }

    public function category_editPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = ProjectCategory::find($request->id);

        if($projectCategory->is_public == 1){
            abort(401);
        }

        $projectCategory->company_id = Auth::user()->company->id;
        $projectCategory->name = $request->name;
        $projectCategory->is_public = 0;

        $projectCategory->save();

        $request->session()->flash('message', 'Categoria atualizada com sucesso!');

        return redirect()->route('project.category');
    }

    public function category_delete(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = ProjectCategory::find($request->id);

        if($projectCategory->is_public == 1){
            abort(401);
        }
        return view('project.category.delete')->with('projectCategory', $projectCategory);
    }

    public function category_deletePost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectCategory = ProjectCategory::find($request->id);

        if($projectCategory->is_public == 1){
            abort(401);
        }

        $projectCategory->delete();

        $request->session()->flash('message', 'Categoria removida com sucesso!');

        return redirect()->route('project.category');
    }




    //STATUS

    public function status_index()
    {
        $this->checkAccessArea('PROJECT');

        return view('project.status.index')->with('allStatus', $this->getAllStatus());
    }

    public function status_new()
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = new ProjectStatus();

        return view('project.status.form')->with('projectStatus', $projectStatus)->with('allStatus', $this->getAllStatus());
    }

    public function status_edit(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = ProjectStatus::find($request->id);

        if($projectStatus->company == null){
            abort(401);
        }

        return view('project.status.form')->with('projectStatus', $projectStatus)->with('allStatus', $this->getAllStatus());
    }

    public function status_newPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = new ProjectStatus();

        $projectStatus->company_id = Auth::user()->company->id;
        $projectStatus->name = $request->name;

        $projectStatus->save();

        $request->session()->flash('message', 'Status criado com sucesso!');

        return redirect()->route('project.status');
    }

    public function status_editPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = ProjectStatus::find($request->id);

        if($projectStatus->company == null){
            abort(401);
        }

        $projectStatus->company_id = Auth::user()->company->id;
        $projectStatus->name = $request->name;

        $projectStatus->save();

        $request->session()->flash('message', 'Status atualizado com sucesso!');

        return redirect()->route('project.status');
    }

    public function status_delete(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = ProjectStatus::find($request->id);

        if($projectStatus->company == null){
            abort(401);
        }
        return view('project.status.delete')->with('projectStatus', $projectStatus);
    }

    public function status_deletePost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStatus = ProjectStatus::find($request->id);

        if($projectStatus->company == null){
            abort(401);
        }

        Project::where('company_id', Auth::user()->company->id)->where('status_id', $request->id)->update(array('status_id' => 1));

        $projectStatus->delete();

        $request->session()->flash('message', 'Status removido com sucesso!');

        return redirect()->route('project.status');
    }




    //STEP

    public function step_index()
    {
        $this->checkAccessArea('PROJECT');

        return view('project.step.index')->with('allSteps', $this->getAllProjectSteps());
    }

    public function step_new()
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = new ProjectStep();

        return view('project.step.form')->with('projectStep', $projectStep)->with('allSteps', $this->getAllProjectSteps());
    }

    public function step_edit(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = ProjectStep::find($request->id);

        if($projectStep->company == null){
            abort(401);
        }

        return view('project.step.form')->with('projectStep', $projectStep)->with('allSteps', $this->getAllProjectSteps());
    }

    public function step_newPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = new ProjectStep();

        $projectStep->company_id = Auth::user()->company->id;
        $projectStep->name = $request->name;

        $projectStep->save();

        $request->session()->flash('message', 'Etapa criada com sucesso!');

        return redirect()->route('project.step');
    }

    public function step_editPost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = ProjectStep::find($request->id);

        if($projectStep->company == null){
            abort(401);
        }

        $projectStep->company_id = Auth::user()->company->id;
        $projectStep->name = $request->name;

        $projectStep->save();

        $request->session()->flash('message', 'Etapa atualizada com sucesso!');

        return redirect()->route('project.step');
    }

    public function step_delete(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = ProjectStep::find($request->id);

        if($projectStep->company == null){
            abort(401);
        }
        return view('project.step.delete')->with('projectStep', $projectStep);
    }

    public function step_deletePost(Request $request)
    {
        $this->checkAccess('PROJECT_EDIT');

        $projectStep = ProjectStep::find($request->id);

        if($projectStep->company == null){
            abort(401);
        }

        Project::where('company_id', Auth::user()->company->id)->where('step_id', $request->id)->update(array('step_id' => 1));
        $projectStep->delete();

        $request->session()->flash('message', 'Etapa removida com sucesso!');

        return redirect()->route('project.step');
    }





    public function files_upload(Request $request, $projectId)
    {
        $this->checkAccess('PROJECT_EDIT');

        if($projectId == null) abort(500);
        $project = Project::where('code', $projectId)->first();
        if(!$project) abort(500);

        $files = $request->file('files');
        if(!$request->hasFile('files'))
        {
            abort(500);
        }

        foreach ($files as $file) {
            //$currentFilename = $this->generateRandomString(15).'.'.$file->getClientOriginalExtension();
            $currentFilename = $file->getClientOriginalName();

            $projectFile = new ProjectFile();
            $projectFile->name = $currentFilename;
            $projectFile->path = 'project_' . $project->id.'/'.$currentFilename;
            $projectFile->user_id = Auth::user()->id;
            $projectFile->company_id = Auth::user()->company->id;
            $projectFile->project_id = $project->id;
            $projectFile->save();

            $file->storeAs('project_' . $project->id, $currentFilename);
        }

        response();
    }

    public function files_remove(Request $request, $projectId, $file_id)
    {
        if($projectId == null || $file_id == null) abort(500);
        $project = Project::where('code', $projectId)->first();
        $projectFile = ProjectFile::where('id', $file_id)->where('project_id', $project->id)->first();

        if($projectFile == null) abort(500);
        $projectFile->delete();
        Storage::delete($projectFile->path);

        $request->session()->flash('message', 'Arquivo excluido com sucesso!');

        return back()->withInput();
    }

    public function files_open(Request $request, $projectId, $file_id)
    {
        if($projectId == null || $file_id == null) abort(500);
        $project = Project::where('code', $projectId)->first();
        $projectFile = ProjectFile::where('id', $file_id)->where('project_id', $project->id)->first();

        if($projectFile == null) abort(500);

        return response()->file(storage_path('app/' . $projectFile->path));
    }

    public function files_download(Request $request, $projectId, $file_id)
    {
        if($projectId == null || $file_id == null) abort(500);
        $project = Project::where('code', $projectId)->first();
        $projectFile = ProjectFile::where('id', $file_id)->where('project_id', $project->id)->first();

        if($projectFile == null) abort(500);

        return Storage::download($projectFile->path);
    }





    public function api_get_report_data(Request $request){
        $this->checkAccessArea('PROJECT');

        $projects = Project::where('company_id', Auth::user()->company->id);

        if(isset($request->client) && $request->client > 0){
            $projects = $projects->where('client_id', $request->client);
        }

        if(isset($request->owner) && $request->owner > 0){
            $projects = $projects->where('category_id', $request->category);
        }

        $projects = $projects->orderBy('name', 'ASC')->get();


        if(isset($request->step) && $request->step > 0){
            $step = $request->step;
            $projects = $projects->filter(function ($item, $key) use ($step) {
                return $item->hasStep($step);
            })->values();
        }

        $clients = $this->getAllClients();
        $categories = $this->getAllCategories();
        $users = $this->getAllUsers();
        /*->with('allStatus', $this->getAllStatus())
        ->with('allSteps', $this->getAllProjectSteps());*/

        for ($i=0; $i < count($projects); $i++) {
            $projects[$i]->client = $this->getById($clients, $projects[$i]->client_id);
            $projects[$i]->category = $this->getById($categories, $projects[$i]->category_id);
            $projects[$i]->owner = $projects[$i]->responsibleNames();
            $projects[$i]->stepStatus = $projects[$i]->getStepStatusName($request->step);
        }

        return response([ 'projects' => $projects, 'message' => 'Retrieved successfully'], 200);
    }













    // API
    public function api_get_active_projects()
    {
        $projectsCount = $this->getAllActiveProjects();

        return response([ 'projects' => $projectsCount, 'message' => 'Retrieved successfully'], 200);
    }






























    private function getNewCode()
    {
        $lastCode = Project::where('company_id', Auth::user()->company->id)->orderByDesc('code')->get();
        if(count($lastCode) == 0)
            $lastCode = 10000;
        else
            $lastCode = $lastCode->first()->code + 1;

        return $lastCode;
    }

    public function getAllClients()
    {
        return Client::where('company_id', Auth::user()->company->id)->get();
    }

    private function getAllCategories()
    {
        $categories = ProjectCategory::get();
        return $categories->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->company->id <> Auth::user()->company->id;
        })->values();
    }

    private function getAllStatus()
    {
        $status = ProjectStatus::get();
        return $status->reject(function ($item, $key) {
            return $item->company <> null && $item->company->id <> Auth::user()->company->id;
        })->values();
    }

    private function getNextEvents(int $projectId)
    {
        $datetimeLimit = new \DateTime();
        $datetimeLimit->add(new \DateInterval('P15D'));


        $events = Event::where('company_id', Auth::user()->company->id)->where('project_id', $projectId)->whereBetween('start', [new \DateTime(), $datetimeLimit])->get();
        $events = $events->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->user_id <> Auth::user()->id;
        })->values();

        return $events;
    }
}
