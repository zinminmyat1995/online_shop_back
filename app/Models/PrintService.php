<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintService extends Model
{
	use SoftDeletes;
	protected $table = 'print_service';
	protected $guarded=[];
}
