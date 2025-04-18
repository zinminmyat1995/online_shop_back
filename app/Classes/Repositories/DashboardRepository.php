<?php

namespace App\Classes\Repositories;

use App\Models\Sale;
use App\Models\Warehouse;
use App\Models\Customer;
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
		$res = Customer::leftJoin('payment', 'customer.payment_id', '=', 'payment.id')
				->whereNull('customer.deleted_at')
				->whereDate('customer.updated_at', $currentDate)
				->select('payment.name as payment_name', DB::raw('SUM(customer.total_price) as total_price'))
				->groupBy('payment.name')
				->get();

		$dayTotal = Customer::whereNull('customer.deleted_at')
				->whereDate('customer.updated_at', $currentDate)
				->sum('customer.total_price');

		$currentYear = date('Y');
		$currentMonth = date('m');

		$res1 = Customer::leftJoin('payment', 'customer.payment_id', '=', 'payment.id')
			->whereNull('customer.deleted_at')
			->whereYear('customer.updated_at', $currentYear)
			->whereMonth('customer.updated_at', $currentMonth)
			->select('payment.name as payment_name', DB::raw('SUM(customer.total_price) as total_price'))
			->groupBy('payment.name')
			->get();
		$monthTotal = Customer::whereNull('deleted_at')
			->whereYear('updated_at', $currentYear)
			->whereMonth('updated_at', $currentMonth)
			->sum('total_price');

		$res2 = Customer::leftJoin('payment', 'customer.payment_id', '=', 'payment.id')
			->whereNull('customer.deleted_at')
			->whereYear('customer.updated_at', $currentYear)
			->select('payment.name as payment_name', DB::raw('SUM(customer.total_price) as total_price'))
			->groupBy('payment.name')
			->get();

		$yearTotal = Customer::whereNull('deleted_at')
		->whereYear('updated_at', $currentYear)
		->sum('total_price');


		$thisMonth = now()->month;

		// Get actual data from DB
		$rawMonthlyTotals = Customer::selectRaw('MONTH(updated_at) as month, SUM(total_price) as total')
			->whereNull('deleted_at')
			->whereYear('updated_at', $currentYear)
			->groupBy(DB::raw('MONTH(updated_at)'))
			->pluck('total', 'month')
			->toArray();

		// Build final array with just values (0 for missing months)
		$monthlyTotals = [];
		for ($i = 1; $i <= $thisMonth; $i++) {
			$monthlyTotals[] = isset($rawMonthlyTotals[$i]) ? (float) $rawMonthlyTotals[$i] : 0;
		}


		$warehouseData = Warehouse::whereNull('deleted_at')
							->selectRaw('SUM(remain) as remain, SUM(ng) as ng')
							->first();
	
		$saleData = Sale::whereNull('deleted_at')
						->selectRaw('SUM(qty) as sale')
						->first();
		
		$data = [
			"current_day_sale" => $res,
			"day_total" => $dayTotal,
			"current_month_sale" => $res1,
			"month_total" => $monthTotal,
			"current_year_sale" => $res2,
			"year_total" => $yearTotal,
			"bar_data" => [$monthlyTotals],
			"pie_data" => [$warehouseData['remain'],$warehouseData['ng'],$saleData['sale']]
		];
		if (count($data) > 0) {
			return $data;
		} else {
			return [];
		}
	}


	function getStorage($login_id){
		

		$databaseName = env('DB_DATABASE');

		$size = DB::select("
			SELECT table_schema AS 'database', 
				   ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size_mb'
			FROM information_schema.tables 
			WHERE table_schema = ?
			GROUP BY table_schema
		", [$databaseName]);

		$databaseSize = $size[0]->size_mb ?? 0;
		$percentage = ($databaseSize / 5120) * 100;
		return $percentage;

	}

}
