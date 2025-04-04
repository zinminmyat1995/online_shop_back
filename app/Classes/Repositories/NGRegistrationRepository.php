<?php

namespace App\Classes\Repositories;

use App\Models\Warehouse;
use App\Models\NG;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NGRegistrationInterface;

class NGRegistrationRepository implements NGRegistrationInterface
{
    function __construct()
    {
    }

	function search($product_code, $login_id){
		$res = array();
		$res = Warehouse::where('deleted_at', null)
		->where('p_code',$product_code)
		->get()->toArray();
        if (count($res) > 0) {
            return $res;
        } else {
            return [];
        }
	}

	function save($data, $login_id, $note){
		$history_data = [
			"note" => $note,
			"type" => 2,
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
			$wh = Warehouse::where('deleted_at', null)
			->where('p_code', $code)
			->get()->toArray();

			$ng = NG::where('deleted_at', null)
			->where('p_code', $code)
			->get()->toArray();
	
			$warehouseResults = Warehouse::where('deleted_at', null)
				->where('p_code', $code)
				->update([
					'ng' => $wh[0]['ng']  + $value['ng_count'],
					'remain'  => $wh[0]['remain'] - $value['ng_count'],
					'price' => $value['price'],
					'total_price' => $wh[0]['total_price'] - $value['total_price'],
					"history_id" => $history_id,
					"updated_id" => $login_id,
					"updated_at"     => now()
				]);

			
			if (count($ng) > 0) {
				$ngResults = NG::where('deleted_at', null)
				->where('p_code', $code)
				->update([
					'qty' => $ng[0]['qty'] + $value['ng_count'],
					'price' => $value['price'],
					'total_price' => $ng[0]['total_price'] + $value['total_price'],
					"updated_id" => $login_id,
					"updated_at"     => now()
				]);

			}else{
				$ng_data = [
					"p_code" => $value['code'],
					"qty" => $value['ng_count'],
					"price" => $value['price'],
					"total_price" =>$value['total_price'],
					"history_id" => $history_id,
					"ng_register_date" => Carbon::now(),
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
	
				$NGResult = NG::insert($ng_data);
			}
		}
        return true;
	}
	

}
