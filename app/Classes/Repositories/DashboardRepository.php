<?php

namespace App\Classes\Repositories;

use App\Models\Sale;
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

		$data = [
			"current_day_sale" => $res,
			"day_total" => $dayTotal,
			"current_month_sale" => $res1,
			"month_total" => $monthTotal,
			"current_year_sale" => $res2,
			"year_total" => $yearTotal,
		];
		if (count($data) > 0) {
			return $data;
		} else {
			return [];
		}
	}

}
