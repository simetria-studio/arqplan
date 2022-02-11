<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class Provider extends Model
{
    protected $table = 'provider';

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
        return $this->hasMany(App\Models\Company::Class);
    }
    
    public function projects()
    {
        return $this->hasMany(App\Models\Project::Class);
    }

    public function events()
    {
        return $this->hasMany(App\Models\Events::Class);
    }
}
