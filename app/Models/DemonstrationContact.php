<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemonstrationContact extends Model
{
    protected $table = 'demonstration_contact';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'employeesNumber', 'otherSoftware', 'mainDifficulty'
    ];
}
