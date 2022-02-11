<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Event;
use App\Models\Project;
use App\Models\ProjectCategory;

class TaskController extends Controller
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
        $projects = Project::where('company_id', Auth::user()->company->id)->get();
        return view('task.index')->with('projects', $projects);
    } 

    public function kanban()
    {
        $projects = Project::where('company_id', Auth::user()->company->id)->get();
        return view('task.kanban')->with('projects', $projects);
    } 

    public function kanbanOfProject($project_id)
    {
        $project = Project::where('company_id', Auth::user()->company->id)->where('id', $project_id)->first();
        return view('task.kanban.project')->with('project', $project);
    }    

    private function getEventById($id)
    {
        return Event::where('id', $id)->where('company_id', Auth::user()->company->id)->first();
    }

    // API     
    public function api_get_tasks()
    {
        $users = $this->getAllUsers(['id', 'name', 'lastname', 'email']);
        $tasks = Event::where('company_id', Auth::user()->company->id)->where('type', 'T')->orderBy('start', 'ASC')->get();
        foreach ($tasks as $task) {
            $task->type = "project";
            $task->percent = 0;

            switch ($task->state) {
                case 'N':
                    $task->state_name = "Novo";
                    break;
                case 'A':
                    $task->state_name = "Ativo";
                    break;                
                case 'P':
                    $task->state_name = "Pendente";
                    break;                
                case 'D':
                    $task->state_name = "Concluído";
                    break;
            }
        }        

        return response([ 'tasks' => $tasks, 'users' => $users, 'message' => 'Retrieved successfully'], 200);
    }
    
    public function api_get_tasks_of_project($project_id)
    {
        $tasks = Event::where('company_id', Auth::user()->company->id)->where('type', 'T')->where('project_id', $project_id)->orderBy('start', 'ASC')->get();

        return response([ 'tasks' => $tasks, 'message' => 'Retrieved successfully'], 200);
    }
    
    public function api_add_task(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'state' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response([ 'errors' => $validator->errors()], 400);
        }        
        
        if( $request->project_id == null || $request->project_id == "" || $request->project_id == "null" || $request->project_id == 0) $request->project_id = null;
        if( $request->responsible_id == null || $request->responsible_id == "" || $request->responsible_id == "null" || $request->responsible_id == 0) $request->responsible_id = null;

        $event = new Event();
        $event->user_id = Auth::user()->id;
        $event->company_id = Auth::user()->company->id;
        $event->project_id = $request->project_id;
        $event->responsible_id = $request->responsible_id;
        $event->title = $request->title;
        $event->type = 'T';
        $event->state = $request->state;
        $event->description = $request->description;
        $event->start = new \DateTime($request->start);
        $event->end = new \DateTime($request->end);

        if(isset($request->recurrent) && $request->recurrent == true){
            $event->recurrent = $request->recurrent;
            $event->recurrentId = $this->generateRandomString(20);
            $event->recurrentType = $request->recurrentType;
            $event->recurrentLimit = $request->recurrentLimit;
            $event->recurrentLimitTimes = $request->recurrentLimitTimes;
            $event->recurrentLimitDate = new \DateTime($request->recurrentLimitDate);
            $event->recurrentWeekday2 = $request->recurrentWeekday2;
            $event->recurrentWeekday3 = $request->recurrentWeekday3;
            $event->recurrentWeekday4 = $request->recurrentWeekday4;
            $event->recurrentWeekday5 = $request->recurrentWeekday5;
            $event->recurrentWeekday6 = $request->recurrentWeekday6;
            $event->recurrentWeekdayS = $request->recurrentWeekdayS;
            $event->recurrentWeekdayD = $request->recurrentWeekdayD;
        }

        $event->is_public = $request->isPublic;
        $event->save();

        if($event->recurrent == true){
            $this->createRecurrency($event);
        }

        $event = Event::where('id', $event->id)->first();

        return response(['data' => $event], 200);
    }
    
    public function api_update_task(Request $request, $taskId)
    {
        $task = $this->getEventById($taskId);
        if( $task == null ||  $task == "") return response()->json(['message' => "Atividade não encontrada"], 500);
        
        if( $request->project_id == null || $request->project_id == "" || $request->project_id == "null" || $request->project_id == 0) $request->project_id = null;
        if( $request->responsible_id == null || $request->responsible_id == "" || $request->responsible_id == "null" || $request->responsible_id == 0) $request->responsible_id = null;
        
        $task->user_id = Auth::user()->id;
        $task->company_id = Auth::user()->company->id;
        $task->project_id = $request->project_id;
        $task->responsible_id = $request->responsible_id;
        $task->title = $request->title;
        $task->state = $request->state;
        $task->description = $request->description;
        $task->start = new \DateTime($request->start);
        $task->end = new \DateTime($request->end);
        $task->recurrent = false;            
        $task->is_public = true;
        $task->save();

        $task = Event::where('id', $task->id)->first();

        return response(['data' => $task], 200);
    }
    
    public function api_update_task_status(Request $request, $taskId)
    {
        $task = $this->getEventById($taskId);
        if( $task == null ||  $task == "") return response()->json(['message' => "Atividade não encontrada"], 500);
        
        if( $request->status == null || $request->status == "" || $request->status == "null") $request->status = 'N';
        $task->state = $request->status;
        $task->save();

        $task = Event::where('id', $task->id)->first();

        return response(['data' => $task], 200);
    }
    
    public function api_delete_task(Request $request, $taskId)
    {
        $task = $this->getEventById($taskId);
        if( $task == null ||  $task == "") return response()->json(['message' => "Atividade não encontrada"], 500);
        
        $task->delete();

        return "ok";
    }

    public function api_get_last_events()
    {
        $today = Carbon::now()->startOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);
        $lastDay = Carbon::now()->endOfWeek()->hour(0)->minute(0)->second(0)->millisecond(0);

        $events = Event::where('company_id', Auth::user()->company->id)        
        ->where(function ($query) use($today, $lastDay) {
            $query->where('start', '>=', $today)->where('start', '<=', $lastDay);
        })
        ->orderBy('start', 'ASC')->get();        

        $events = $events->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->user_id <> Auth::user()->id;
        })->values();

        foreach ($events as $event) {
            switch ($event->type) {
                case 'R':
                    $event->color = "blue";
                    break;
                case 'T':
                    $event->color = "purple";
                    break;                
                case 'E':
                default:
                    $event->color = "red";
                    break;
            }
        }

        return response([ 'events' => $events, 'message' => 'Retrieved successfully'], 200);
    }

    public function api_create_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'state' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response([ 'errors' => $validator->errors()], 400);
        }

        $event = new Event();
        $event->user_id = Auth::user()->id;
        $event->company_id = Auth::user()->company->id;
        $event->project_id = $request->project;
        $event->title = $request->title;
        $event->type = $request->type;
        $event->state = $request->state;
        $event->description = $request->description;
        $event->start = new \DateTime($request->start);
        $event->end = new \DateTime($request->end);

        if(isset($request->recurrent) && $request->recurrent == true){
            $event->recurrent = $request->recurrent;
            $event->recurrentId = $this->generateRandomString(20);
            $event->recurrentType = $request->recurrentType;
            $event->recurrentLimit = $request->recurrentLimit;
            $event->recurrentLimitTimes = $request->recurrentLimitTimes;
            $event->recurrentLimitDate = new \DateTime($request->recurrentLimitDate);
            $event->recurrentWeekday2 = $request->recurrentWeekday2;
            $event->recurrentWeekday3 = $request->recurrentWeekday3;
            $event->recurrentWeekday4 = $request->recurrentWeekday4;
            $event->recurrentWeekday5 = $request->recurrentWeekday5;
            $event->recurrentWeekday6 = $request->recurrentWeekday6;
            $event->recurrentWeekdayS = $request->recurrentWeekdayS;
            $event->recurrentWeekdayD = $request->recurrentWeekdayD;
        }

        $event->is_public = $request->isPublic;
        $event->save();

        if($event->recurrent == true){
            $this->createRecurrency($event);
        }

        $event = Event::where('id', $event->id)->first();

        return response(['data' => $event], 200);
    }

    private function createRecurrency(Event $event)
    {
        $times = 1000;

        if($event->recurrentType == "D"){
            if($event->recurrentLimit == "N"){
                $times = $event->recurrentLimitTimes;
            }

            $limitDay = new Carbon($event->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $event = $event->replicate();
                $interval = new \DateInterval('P1D');
                $event->start = $event->start->add($interval);
                $event->end = $event->end->add($interval);
                
                if($event->recurrentLimit == "U" && $event->start > $limitDay){
                    return;
                }
                $event->save();
            }
        }elseif($event->recurrentType == "W"){
            if($event->recurrentLimit == "N"){
                $weeks = $event->recurrentLimitTimes;
                
                $limitDay = new Carbon($event->start);
                $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add($weeks*7 - 1, 'day');
            }elseif($event->recurrentLimit == "U"){          
                $limitDay = new Carbon($event->recurrentLimitDate);
            }
            

            for ($i=1; $i < $times; $i++) { 
                $event = $event->replicate();
                $interval = new \DateInterval('P1D');
                $event->start = $event->start->add($interval);
                $event->end = $event->end->add($interval);

                if(($event->recurrentLimit == "U" || $event->recurrentLimit == "N") && $event->start > $limitDay){
                    return;
                }

                if($this->weekdayIsSelectedOnEvent($event, $event->start)) $event->save();
            }
        }elseif($event->recurrentType == "M"){
            if($event->recurrentLimit == "N"){
                $times = $event->recurrentLimitTimes;
            }

            $limitDay = new Carbon($event->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $event = $event->replicate();
                $interval = new \DateInterval('P1M');
                $event->start = $event->start->add($interval);
                $event->end = $event->end->add($interval);
                
                if($event->recurrentLimit == "U" && $event->start > $limitDay){
                    return;
                }
                $event->save();
            }
        }elseif($event->recurrentType == "Y"){
            if($event->recurrentLimit == "N"){
                $times = $event->recurrentLimitTimes;
            }

            $limitDay = new Carbon($event->recurrentLimitDate);
            $limitDay = $limitDay->minute(0)->second(0)->millisecond(0)->add(1, 'day');

            for ($i=1; $i < $times; $i++) { 
                $event = $event->replicate();
                $interval = new \DateInterval('P1Y');
                $event->start = $event->start->add($interval);
                $event->end = $event->end->add($interval);
                
                if($event->recurrentLimit == "U" && $event->start > $limitDay){
                    return;
                }
                $event->save();
            }
        }
    }

    private function removeRecurrency(string $recurrentId)
    {
        $event = Event::where('recurrentId', $recurrentId)->where('company_id', Auth::user()->company->id);
        $event->delete();
    }

    public function weekdayIsSelectedOnEvent(Event $event, \Datetime $date)
    {
        return (($event->recurrentWeekdayD == true && $date->format('w') == 0) ||
            ($event->recurrentWeekday2 == true && $date->format('w') == 1) ||
            ($event->recurrentWeekday3 == true && $date->format('w') == 2) ||
            ($event->recurrentWeekday4 == true && $date->format('w') == 3) ||
            ($event->recurrentWeekday5 == true && $date->format('w') == 4) ||
            ($event->recurrentWeekday6 == true && $date->format('w') == 5) ||
            ($event->recurrentWeekdayS == true && $date->format('w') == 6)
        );
    }

    public function api_update_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'title' => 'required',
            'state' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response([ 'errors' => $validator->errors()], 400);
        }

        $event = Event::where('id', $request->id)->where('company_id', Auth::user()->company->id)->first();
        $event->user_id = Auth::user()->id;
        $event->company_id = Auth::user()->company->id;
        $event->project_id = $request->project;
        $event->title = $request->title;
        $event->state = $request->state;
        $event->description = $request->description;
        $event->start = new \DateTime($request->start);
        $event->end = new \DateTime($request->end);

        if(isset($event->recurrentId) && $request->updateRecurrentMode == "A"){
            $this->removeRecurrency($event->recurrentId);
            $event = $event->replicate();
        }

        if(isset($request->recurrent) && $request->recurrent == true){
            $event->recurrent = $request->recurrent;
            $event->recurrentType = $request->recurrentType;
            $event->recurrentLimitDate = $request->recurrentLimitDate;
            $event->recurrentLimitTimes = $request->recurrentLimitTimes;
            $event->recurrentLimitDate = new \DateTime($request->recurrentLimitDate);
            $event->recurrentWeekday2 = $request->recurrentWeekday2;
            $event->recurrentWeekday3 = $request->recurrentWeekday3;
            $event->recurrentWeekday4 = $request->recurrentWeekday4;
            $event->recurrentWeekday5 = $request->recurrentWeekday5;
            $event->recurrentWeekday6 = $request->recurrentWeekday6;
            $event->recurrentWeekdayS = $request->recurrentWeekdayS;
            $event->recurrentWeekdayD = $request->recurrentWeekdayD;
        }
        $event->is_public = $request->isPublic;
        $event->save();

        if($event->recurrent == true && $request->updateRecurrentMode == "A"){
            $this->createRecurrency($event);
        }

        $event = Event::where('id', $event->id)->first();

        return response(['data' => $event], 200);
    }

    public function api_remove_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response([ 'errors' => $validator->errors()], 400);
        }

        $event = Event::where('id', $request->id)->where('company_id', Auth::user()->company->id)->first();
        if(isset($event->recurrentId) && $request->updateRecurrentMode == "A") $this->removeRecurrency($event->recurrentId);
        $event->delete();

        return response([], 200);
    }





    public function api_get_report_data(Request $request){
        $this->checkAccessArea('REPORT');
        
        $events = Event::where('company_id', Auth::user()->company->id);

        if(isset($request->startDate)){
            $events = $events->whereDate('start', '>=', $request->startDate);
        }

        if(isset($request->endDate)){
            $events = $events->whereDate('end', '<=', $request->endDate);
        }

        if(isset($request->user) && $request->user > 0){
            $events = $events->where('user_id', $request->user);
        }

        if(isset($request->type) && $request->type != "0"){
            $events = $events->where('type', $request->type);
        }

        if(isset($request->state) && $request->state != "0"){
            $events = $events->where('state', $request->state);
        }

        $events = $events->orderBy('start', 'ASC')->get();
        
        $events = $events->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->user_id <> Auth::user()->id;
        })->values();

        $clients = $this->getAllClients();
        $categories = $this->getAllCategories();
        $projects = $this->getAllProjects();
        $users = $this->getAllUsers();

        for ($i=0; $i < count($events); $i++) { 
            $events[$i]->client = $this->getById($clients, $events[$i]->client_id);
            $events[$i]->category = $this->getById($categories, $events[$i]->category_id);
            $events[$i]->project = $this->getById($projects, $events[$i]->project_id);
        }
        
        return response([ 'events' => $events, 'message' => 'Retrieved successfully'], 200);
    }



    private function getAllCategories()
    {
        $categories = ProjectCategory::get();
        return $categories->reject(function ($item, $key) {
            return $item->is_public == 0 && $item->company->id <> Auth::user()->company->id;
        })->values();
    }
}
