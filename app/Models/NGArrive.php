<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NGArrive extends Model
{
    use SoftDeletes;
    protected $table = 'ng_arrive';
    protected $guarded=[];
}
