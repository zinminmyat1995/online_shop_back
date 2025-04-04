<?php

namespace App\Classes\Repositories;

use App\Models\Warehouse;
use App\Models\NG;
use App\Models\NGReturn;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NGReturnRegistrationInterface;

class NGReturnRegistrationRepository implements NGReturnRegistrationInterface
{
    function __construct()
    {
    }

	function save($data, $login_id, $note){
		$history_data = [
			"note" => $note,
			"type" => 3,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
		$historyResult = History::create($history_data);
		$historyResultArray = $historyResult->toArray();
		$history_id = $historyResultArray['id'];

		foreach ($data as $key => $value) {
			$code = $value['code'];		
			$ng_return = NGReturn::where('deleted_at', null)
			->where('p_code', $code)
			->get()->toArray();

			if (count($ng_return) > 0) {
				$ngReturnResults = NGReturn::where('deleted_at', null)
				->where('p_code', $code)
				->update([
					'qty' => $ng_return[0]['qty'] + $value['ng_count'],
					'price' => $value['price'],
					'total_price' => $ng_return[0]['total_price'] + $value['total_price'],
					"ng_return_date" => Carbon::now(),
					"updated_id" => $login_id,
					"updated_at"     => now()
				]);

			}else{
				$ng_return_data = [
					"p_code" => $value['code'],
					"qty" => $value['ng_count'],
					"price" => $value['price'],
					"total_price" =>$value['total_price'],
					"history_id" => $history_id,
					"ng_return_date" => Carbon::now(),
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
	
				$ngReturnResults = NGReturn::insert($ng_return_data);
			}
			$ng_data = NG::where('deleted_at', null)
								->where('p_code', $code)
								->get()->toArray();
			if (count($ng_data) > 0 && ($ng_data[0]['qty'] - $value['ng_count']) > 0) {
				$ngReturnResults = NG::where('deleted_at', null)
					->where('p_code', $code)
					->update([
						'qty' => $ng_data[0]['qty'] - $value['ng_count'],
						'price' => $ng_data[0]['price'],
						'total_price' => $ng_data[0]['total_price'] - ($value['ng_count'] * $value['price']),
						"updated_id" => $login_id,
						"updated_at"     => now()
					]);
			}else{
				$res = NG::whereNull('deleted_at')
					->where('p_code', $code)
					->forceDelete();
			}
		}
        return true;
	}
	

}
