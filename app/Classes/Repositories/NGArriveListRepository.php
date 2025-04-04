<?php

namespace App\Classes\Repositories;

use App\Models\NGArrive;
use App\Models\History;
use App\Models\NGReturn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NGArriveListInterface;

class NGArriveListRepository implements NGArriveListInterface
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
			$res = NGArrive::whereNull('ng_arrive.deleted_at')
							->join('product', 'ng_arrive.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_arrive.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else if($product_category != ""  && $gender == "" ){
			$res = NGArrive::whereNull('ng_arrive.deleted_at')
							->join('product', 'ng_arrive.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_arrive.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();

		}else if($product_category == ""  && $gender != "" ){
			$res = NGArrive::whereNull('ng_arrive.deleted_at')
							->join('product', 'ng_arrive.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_arrive.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else{
			$res = NGArrive::whereNull('ng_arrive.deleted_at')
							->join('product', 'ng_arrive.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng_arrive.*',
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
				$temp['ng_arrive_date'] = $value['ng_arrive_date'];
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


	function  delete($login_id,$product_code,$ng_count ,$price , $total_price  ){
		$history_data = [
			"note" => "NG Return Cancelation",
			"type" => 3,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
		$historyResult = History::create($history_data);
		$historyResultArray = $historyResult->toArray();

		$history_id = $historyResultArray['id'];
		$res = NGArrive::whereNull('deleted_at')
				->where('p_code', $product_code)
				->forceDelete();

		$res1 = NGReturn::where('deleted_at', null)
				->where('p_code',$product_code)
				->get()->toArray();

        if(count($res1) > 0){
			$results1 = NGReturn::where('deleted_at', null)
			->where('p_code',$product_code)
			->update([
					'qty' => $res1[0]['qty']  + $ng_count,
					'total_price' => $res1[0]['total_price']  + $total_price,
					'ng_return_date' => now(),
					"updated_id" => $login_id,
					"updated_at"     => now()
					]);
		}else{
			$results1 = [
				"p_code" => $product_code,
				"qty" => $ng_count,
				"price" => $price,
				"total_price" =>$total_price,
				"history_id" => $history_id,
				"ng_return_date" => Carbon::now(),
				"created_id" => $login_id,
				"updated_id" => $login_id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
			];
			$ngReturnResults = NGReturn::insert($results1);
		}
				
        return $results1;
    }

}
