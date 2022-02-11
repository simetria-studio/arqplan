<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Company;
use App\Models\Event;
use App\Models\ProjectStatus;

class ProjectStepStatus extends Model
{
    protected $table = 'project_step_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endDate', 'position'
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
    ];

    public function project()
    {
        return $this->belongsTo(Project::Class);
    }

    public function project_step()
    {
        return $this->belongsTo(ProjectStep::Class);
    }

    public function project_status()
    {
        return $this->belongsTo(ProjectStatus::Class);
    }
}
