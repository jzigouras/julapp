<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teleport extends Model
{
    protected $fillable = [
        'city_search',
        'status',
        'query_result',
    ];
}
