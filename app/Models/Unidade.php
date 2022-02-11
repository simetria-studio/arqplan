<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $table = 'unidades';

    protected $fillable = [
        'name', 'company_id',
    ];
}
