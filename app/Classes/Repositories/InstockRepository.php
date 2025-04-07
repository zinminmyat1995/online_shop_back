<?php

namespace App\Classes\Repositories;

use App\Models\ActualImport;
use App\Models\History;
use App\Models\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\InstockInterface;

class InstockRepository implements InstockInterface
{
    function __construct()
    {
    }

	
	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id)
    {
        $temp = array();
        $resData = array();
		$res = array();

		if($product_category == ""  && $gender == "" ){
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'warehouse.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else if($product_category != ""  && $gender == "" ){
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'warehouse.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();

		}else if($product_category == ""  && $gender != "" ){
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'warehouse.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else{
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'warehouse.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}

        if (count($res) > 0) {
            return $res;
        } else {
            return [];
        }
    }

	function update($product_code, $import_qty, $ng, $price, $login_id){
		if( $ng > $import_qty ){
			return false;
		}else{
			$temp = Warehouse::where('deleted_at', null)
						->where('p_code',$product_code)
        				->get()->toArray();

			$results1 = Warehouse::where('deleted_at', null)
				->where('p_code',$product_code)
				->update([
					'import_qty' => $import_qty,
					'remain' =>$import_qty - $ng,
					'total_price' => ($import_qty - $ng) * $price,
					"updated_id" => $login_id,
					"updated_at"     => now()
			]);
			return true;
		}
		
	}

	function delete($login_id,$product_code){
		$history_data = [
			"note" => "To delete item form Instock",
			"type" => 5,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
		$historyResult = History::create($history_data);
		$historyResultArray = $historyResult->toArray();
		$history_id = $historyResultArray['id'];

		$res = Warehouse::whereNull('deleted_at')
				->where('p_code', $product_code)
				->forceDelete();
		
		$results1 = ActualImport::where('p_code',$product_code)
			->update([
				'deleted_at' => now(),
				"updated_id" => $login_id,
				"updated_at"     => now()
		]);

        return $res;
    }

}
