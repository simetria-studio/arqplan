<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\Company;
use App\Models\Project;
use App\Models\Event;

class FinanceAccount extends Model
{
    protected $table = 'financeaccount';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'initial', 'agency', 'account'
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
        'initial' => 'float(10,2)',
    ];
    
    public function company()
    {
        return $this->hasOne(Company::Class);
    }
    
    public function transactions()
    {
        return $this->belongsToMany(FinanceTransaction::Class);
    }
}
