<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\Company;
use App\Models\Project;
use App\Models\Event;

class Client extends Model
{
    protected $table = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'cnpjcpf', 'address', 'addressnumber', 'addresscomplement', 'neighborhood', 'city', 'state'
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
        return $this->hasOne(Company::Class);
    }
    
    public function projects()
    {
        return $this->hasMany(Project::Class);
    }

    public function events()
    {
        return $this->hasMany(Event::Class);
    }
}
