<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryType extends Model
{
	use SoftDeletes;
	protected $table = 'history_type';
	protected $guarded=[];
}
