<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'obs',
        'price',
        'fornecedor',
        'unidade',
        'tipo',
        'altura',
        'largura',
        'peso',
        'comprimento',
        'quantidade',
        'image',
        'categoria',
        'rt',
        'percent',
        'observacao',
    ];
}

