<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Models\FinanceAccount;
use App\Models\Company;
use App\Models\Project;
use App\Models\Event;

class FinanceTransaction extends Model
{
    protected $table = 'financetransaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'date', 'type', 'amount', 'status', 'people_type', 'recurrent', 'recurrentId', 'recurrentType', 'recurrentLimit', 'recurrentLimitTimes', 'recurrentLimitDate', 'recurrentWeekday2', 'recurrentWeekday3', 'recurrentWeekday4', 'recurrentWeekday5', 'recurrentWeekday6', 'recurrentWeekdayS', 'recurrentWeekdayD',
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
    
    public function financeAccount()
    {
        return $this->hasOne(financeAccount::Class);
    }
    
    public function company()
    {
        return $this->hasOne(Company::Class);
    }

    public function category()
    {
        return $this->hasOne(App\Models\FinanceTransactionCategory::Class);
    }

    public function people()
    {
        if($this->people_type == 'client'){
            return $this->hasOne(App\Models\Client::Class);
        }elseif($this->people_type == 'provider'){
            return $this->hasOne(App\Models\Provider::Class);
        }else{
            return null;
        }
    }
}
