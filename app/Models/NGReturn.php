<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NGReturn extends Model
{
    use SoftDeletes;
    protected $table = 'ng_return';
    protected $guarded=[];
}
