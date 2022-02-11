<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Company;
use App\Models\Event;
use App\Models\ProjectStatus;

class ProjectStep extends Model
{
    protected $table = 'project_step';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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

    public function company()
    {
        return $this->belongsTo(Company::Class);
    }

    public function status()
    {
        return $this->hasOne(ProjectStepStatus::Class);
    }
}
