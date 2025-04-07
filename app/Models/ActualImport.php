<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActualImport extends Model
{
    use SoftDeletes;
    protected $table = 'actual_import';
    protected $guarded=[];
}
