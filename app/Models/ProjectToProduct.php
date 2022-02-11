<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectToProduct extends Model
{
    protected $table = 'project_to_products';

    protected $fillable = [
        'user_id',
        'project_id',
        'product_id',
        'quantidade',
        'total',
        'service',
        'product',
        'cpe'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
