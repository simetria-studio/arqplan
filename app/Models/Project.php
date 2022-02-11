<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Company;
use App\Models\Event;
use App\Models\ProjectStatus;
class Project extends Model
{
    protected $table = 'project';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'description', 'startDate', 'endDate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::Class);
    }

    public function steps()
    {
        return $this->hasMany(ProjectStepStatus::Class)->orderBy('position');
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::Class)->get();
    }

    public function company()
    {
        return $this->belongsTo(Company::Class);
    }

    public function responsibles()
    {
        return $this->belongsToMany(\App\Models\User::Class);
    }

    public function responsibleNames()
    {
        return implode(", ", $this->responsibles()->get()->pluck('name')->toArray());
    }

    public function isResponsible(int $userId)
    {
        return $this->responsibles()->where("id", $userId)->exists();
    }

    public function hasStep(int $stepId)
    {
        return $this->steps->where("project_step_id", $stepId)->count() > 0;
    }

    public function getStepStatusId(int $stepId)
    {
        if(!$this->hasStep($stepId)) return 0;

        return $this->steps->where("project_step_id", $stepId)->first()->project_status_id;
    }

    public function getStepStatusName(int $stepId)
    {
        if(!$this->hasStep($stepId)) return 0;
        
        return ProjectStatus::where('id', $this->steps->where("project_step_id", $stepId)->first()->project_status_id)->first();
    }

    public function getStepStatusEndDate(int $stepId)
    {
        if(!$this->hasStep($stepId)) return 0;

        return $this->steps->where("project_step_id", $stepId)->first()->endDate;
    }

    public function getStepStatusPosition(int $stepId)
    {
        if(!$this->hasStep($stepId)) return 99;

        return $this->steps->where("project_step_id", $stepId)->first()->position;
    }
    

    public function client()
    {
        return $this->belongsTo(Client::Class);
    }
}
