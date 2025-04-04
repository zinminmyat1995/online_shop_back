<?php

namespace App\Classes\Repositories;

use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\HistoryInterface;

class HistoryRepository implements HistoryInterface
{
	function __construct()
	{
	}

	
	function search($from_date,$to_date, $login_id)
	{
		$res = array();
		$res = History::whereNull('history.deleted_at')
						->join('history_type', 'history.type', '=', 'history_type.id')
						->whereBetween('history.updated_at', [$from_date, $to_date])
						->select(
							'history.*',
							'history_type.name as history_type_name'
							) 
						->get()
						->toArray();
		
		if (count($res) > 0) {
			return $res;
		} else {
			return [];
		}
	}

}
