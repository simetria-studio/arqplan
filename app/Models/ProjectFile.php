<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Project;
use App\Models\Company;

class ProjectFile extends Model
{
    protected $table = 'projectfile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code'
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

    public function user()
    {
        return $this->hasOne(App\Models\User::Class);
    }

    public function project()
    {
        return $this->hasOne(App\Models\Project::Class);
    }

    public function company()
    {
        return $this->hasOne(App\Models\Company::Class);
    }
}
