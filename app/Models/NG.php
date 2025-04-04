<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NG extends Model
{
    use SoftDeletes;
    protected $table = 'ng';
    protected $guarded=[];
}
