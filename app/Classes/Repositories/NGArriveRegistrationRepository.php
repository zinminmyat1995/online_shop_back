<?php

namespace App\Classes\Repositories;

use App\Models\Warehouse;
use App\Models\NGArrive;
use App\Models\NGReturn;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NGArriveRegistrationInterface;

class NGArriveRegistrationRepository implements NGArriveRegistrationInterface
{
    function __construct()
    {
    }

	function search($product_code,$product_name,$product_category,$gender,$made_in, $login_id){

        $temp = array();
        $resData = array();
		$res = array();

		if($product_category == ""  && $gender == "" ){
			$res = NGReturn::whereNull('ng_return.deleted_at')
							->join('product', 'ng_return.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_return.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else if($product_category != ""  && $gender == "" ){
			$res = NGReturn::whereNull('ng_return.deleted_at')
							->join('product', 'ng_return.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_return.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();

		}else if($product_category == ""  && $gender != "" ){
			$res = NGReturn::whereNull('ng_return.deleted_at')
							->join('product', 'ng_return.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_return.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else{
			$res = NGReturn::whereNull('ng_return.deleted_at')
							->join('product', 'ng_return.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_return.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}

        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $temp['id'] = $key + 1;
				$temp['code'] = $value['p_code'];
                $temp['name'] = $value['p_name'];
                $temp['category'] = $value['p_category_id'];
                $temp['gender_stauts'] = $value['p_gender'];
                $temp['made_in'] = $value['p_made_in'];
				$temp['ng_return_date'] = $value['ng_return_date'];
				$temp['price'] = $value['price'];
				$temp['ng_count'] = $value['qty'];
				$temp['total_price'] = $value['total_price'];
				$temp['edit_flag'] = false;
                array_push($resData, $temp);
            }
            return $resData;
        } else {
            return [];
        }
    
	}


	function save($data, $login_id, $note){
		$history_data = [
			"note" => $note,
			"type" => 4,
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
			$ng_arrive = NGArrive::where('deleted_at', null)
			->where('p_code', $code)
			->get()->toArray();

			if (count($ng_arrive) > 0) {
				$ngReturnResults = NGArrive::where('deleted_at', null)
				->where('p_code', $code)
				->update([
					'qty' => $ng_arrive[0]['qty'] + $value['ng_arrive_count'],
					'price' => $value['price'],
					'total_price' => $ng_arrive[0]['total_price'] + $value['total_price'],
					"ng_arrive_date" => Carbon::now(),
					"updated_id" => $login_id,
					"updated_at"     => now()
				]);

			}else{
				$ng_arrive_data = [
					"p_code" => $value['code'],
					"qty" => $value['ng_arrive_count'],
					"price" => $value['price'],
					"total_price" =>$value['total_price'],
					"history_id" => $history_id,
					"ng_arrive_date" => Carbon::now(),
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
	
				$ngReturnResults = NGArrive::insert($ng_arrive_data);
			}

			$ng_arrive_data = NGReturn::where('deleted_at', null)
								->where('p_code', $code)
								->get()->toArray();
			if (count($ng_arrive_data) > 0 && ($ng_arrive_data[0]['qty'] - $value['ng_arrive_count'])>0) {
				$ngReturnResults = NGReturn::where('deleted_at', null)
					->where('p_code', $code)
					->update([
						'qty' => $ng_arrive_data[0]['qty'] - $value['ng_arrive_count'],
						'price' => $ng_arrive_data[0]['price'],
						'total_price' => $ng_arrive_data[0]['total_price'] - ($value['ng_arrive_count'] * $value['price']),
						"updated_id" => $login_id,
						"updated_at"     => now()
					]);
			}else{
				$res = NGReturn::whereNull('deleted_at')
					->where('p_code', $code)
					->forceDelete();
			}
		}
        return true;
	}
	

}
