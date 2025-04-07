<?php

namespace App\Classes\Repositories;

use App\Models\Warehouse;
use App\Models\ActualImport;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ImportRegistrationInterface;

class ImportRegistrationRepository implements ImportRegistrationInterface
{
    function __construct()
    {
    }

	function save($data, $login_id, $note){
		$history_data = [
			"note" => $note,
			"type" => 1,
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
	
			if (count($wh) > 0) {
				$warehouseResults = Warehouse::where('deleted_at', null)
				->where('p_code', $code)
				->update([
					'import_qty' => $wh[0]['import_qty'] + $value['count'],
					'remain'  => $wh[0]['remain'] + $value['count'],
					'price' => $value['price'],
					'total_price' => $wh[0]['total_price'] + $value['total_price'],
					"updated_id" => $login_id,
					"updated_at"     => now()
				]);

			} else {
				$actual_data = [
					"p_code" => $value['code'],
					"import_qty" => $value['count'],
					"remain" => $value['count'],
					"price" => $value['price'],
					"total_price" =>$value['total_price'],
					"history_id" => $history_id,
					"import_date" => Carbon::now(),
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
	
				$importResult = Warehouse::insert($actual_data);
			}

			$import_data = [
				"p_code" => $value['code'],
				"import_qty" => $value['count'],
				"price" => $value['price'],
				"total_price" =>$value['total_price'],
				"history_id" => $history_id,
				"import_date" => Carbon::now(),
				"created_id" => $login_id,
				"updated_id" => $login_id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
			];

			$importResult = ActualImport::insert($import_data);


		}
        return true;
	}
	

}
