<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class Company extends Model
{
    protected $table = 'company';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled', 'name', 'description', 'cnpjcpf', 'phone', 'mobile', 'address', 'addressnumber', 'addresscomplement', 'neighborhood', 'city', 'state', 'logo'
    ];

    protected $attributes = [
        'enabled' => true,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'approved',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Get users that owners.
     */
    public function users()
    {
        return $this->hasMany(App\Models\User::Class);
    }
    
    public function projects()
    {
        return $this->hasMany(App\Models\Project::Class);
    }

    public function events()
    {
        return $this->hasMany(App\Models\Events::Class);
    }

    public function clients()
    {
        return $this->hasMany(App\Models\Client::Class);
    }

    public function responsibles()
    {        
        return User::where('company_id', $this->id)->where('is_super_admin', 'true')->get();
    }

    public function financeAccounts()
    {
        return $this->hasMany(App\Models\FinanceAccounts::Class);
    }

    public function financeTransactionCategory()
    {
        return $this->hasMany(App\Models\FinanceTransactionCategory::Class);
    }
}
