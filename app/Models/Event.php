<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class Event extends Model
{
    protected $table = 'event';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_public', 'title', 'type', 'state', 'description', 'responsible_id', 'start', 'end', 'color', 'recurrent', 'recurrentId', 'recurrentType', 'recurrentLimit', 'recurrentLimitTimes', 'recurrentLimitDate', 'recurrentWeekday2', 'recurrentWeekday3', 'recurrentWeekday4', 'recurrentWeekday5', 'recurrentWeekday6', 'recurrentWeekdayS', 'recurrentWeekdayD',
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
        return $this->hasOne(App\Models\Project::Class);
    }

    public function company()
    {
        return $this->hasOne(App\Models\Company::Class);
    }

    public function responsible()
    {
        return $this->hasOne(App\Models\User::Class);
    }
}
