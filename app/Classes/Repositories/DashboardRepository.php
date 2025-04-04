<?php

namespace App\Classes\Repositories;

use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\DashboardInterface;

class DashboardRepository implements DashboardInterface
{
	function __construct()
	{
	}

	
	function allData($login_id)
	{
		$currentDate = date('Y-m-d');
		$currentMonth = date('Y-m');
		$res = array();
		$res = Sale::whereNull('deleted_at')
					->whereDate('updated_at', $currentDate)
					->sum('total_price');

		$currentYear = date('Y');
		$currentMonth = date('m');

		$res1 = Sale::whereNull('deleted_at')
			->whereYear('updated_at', $currentYear)
			->whereMonth('updated_at', $currentMonth)
			->sum('total_price');

		$data = [
			"current_day_sale" => $res,
			"current_month_sale" => $res1,
		];
		if (count($data) > 0) {
			return $data;
		} else {
			return [];
		}
	}

}
