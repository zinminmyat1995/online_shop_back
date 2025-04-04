<?php

namespace App\Classes\Repositories;

use App\Models\NG;
use App\Models\NGArrive;
use App\Models\NGReturn;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\NGListInterface;

class NGListRepository implements NGListInterface
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
			$res = NG::whereNull('ng.deleted_at')
							->join('product', 'ng.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else if($product_category != ""  && $gender == "" ){
			$res = NG::whereNull('ng.deleted_at')
							->join('product', 'ng.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();

		}else if($product_category == ""  && $gender != "" ){
			$res = NG::whereNull('ng.deleted_at')
							->join('product', 'ng.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in'
								) 
							->get()
							->toArray();
		}else{
			$res = NG::whereNull('ng.deleted_at')
							->join('product', 'ng.p_code', '=', 'product.p_code')
							->where('product.p_code', 'LIKE', "%{$product_code}%")
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->where('product.p_category_id', $product_category)
							->where('product.p_gender', $gender)
							->where('product.p_made_in', 'LIKE', "%{$made_in}%")
							->select(
								'ng.*',
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
				$temp['ng_register_date'] = $value['ng_register_date'];
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
	function update($product_code, $ng_qty, $login_id){

		$res = NG::where('deleted_at', null)
						->where('p_code',$product_code)
						->get()->toArray();

		$res2 = NGReturn::where('deleted_at', null)
						->where('p_code',$product_code)
						->get()->toArray();
		$res3 = NGArrive::where('deleted_at', null)
						->where('p_code',$product_code)
						->get()->toArray();

		$ng_return_qty = count($res2) > 0? $res2[0]['qty'] : 0;		
		$ng_arrive_qty = count($res3) > 0? $res3[0]['qty'] : 0;		
		$total_ng_qty = $ng_qty + $ng_return_qty + $ng_arrive_qty;
				
		$results = NG::where('deleted_at', null)
					->where('p_code',$product_code)
					->update([
								'qty' => $ng_qty,
								'total_price' => $res[0]['price'] * $ng_qty,
								'ng_register_date' => now(),
								"updated_id" => $login_id,
								"updated_at"     => now()
							]);
		
		$res1 = Warehouse::where('deleted_at', null)
		->where('p_code',$product_code)
		->get()->toArray();
		
		$results1 = Warehouse::where('deleted_at', null)
		->where('p_code',$product_code)
		->update([
				'ng' => $total_ng_qty,
				'remain' =>$res1[0]['import_qty'] - ($total_ng_qty),
				'total_price' => ($res1[0]['import_qty'] - $total_ng_qty) * $res1[0]['price'],
				"updated_id" => $login_id,
				"updated_at"     => now()
				]);
		
		return $results;
	}

	function  delete($login_id,$data  ){
		$res1 = Warehouse::where('deleted_at', null)
		->where('p_code',$data['code'])
		->get()->toArray();

		$ng_count = $data['ng_count'];
		$origin_ng_count = $res1[0]['ng'];

		if($ng_count< $origin_ng_count){
			$results1 = Warehouse::where('deleted_at', null)
			->where('p_code',$data['code'])
			->update([
					'ng' => $origin_ng_count - $ng_count,
					'remain' =>$res1[0]['import_qty'] - ($origin_ng_count - $ng_count),
					'total_price' =>  ($res1[0]['import_qty'] - ($origin_ng_count - $ng_count)) * $res1[0]['price'],
					"updated_id" => $login_id,
					"updated_at"     => now()
					]);
		}else{
			$results1 = Warehouse::where('deleted_at', null)
			->where('p_code',$data['code'])
			->update([
					'ng' => 0,
					'remain' =>$res1[0]['import_qty'],
					'total_price' => $res1[0]['import_qty']  * $res1[0]['price'],
					"updated_id" => $login_id,
					"updated_at"     => now()
					]);
		}

		$res = NG::whereNull('deleted_at')
		->where('p_code', $data['code'])
		->forceDelete();

        return $results1;
    }

	function getDetailInformation($login_id){
		$resData = array();$temp = array();		
		$ngData1 = NG::whereNull('deleted_at')
			->select(
				'p_code'
				) 
			->get()
			->toArray();
		$ngData2 = NGReturn::whereNull('deleted_at')
			->select(
				'p_code'
				) 
			->get()
			->toArray();
		$ngData3 = NGArrive::whereNull('deleted_at')
			->select(
				'p_code'
				) 
			->get()
			->toArray();

		$pCodes1 = array_column($ngData1, 'p_code');
		$pCodes2 = array_column($ngData2, 'p_code');
		$pCodes3 = array_column($ngData3, 'p_code');

		$uniquePCodes = array_unique(array_merge($pCodes1, $pCodes2, $pCodes3));

		if( count($uniquePCodes) > 0 ){
			foreach ($uniquePCodes as $key => $pCode) {
				$temp['id'] = $key + 1;
				$temp['p_code'] = $pCode;
				$return_qty = 0;$arrive_qty = 0;$ng_qty =0;
				$productData = Product::where('deleted_at', null)
					->where('p_code',$pCode)
					->get()->toArray();
				$temp['p_name'] = $productData[0]['p_name'];

				$ngData = NG::where('deleted_at', null)
					->where('p_code',$pCode)
					->get()->toArray();

				if(count($ngData) > 0){
					$ng_qty = $ngData[0]['qty'];
				}
				$ngReturnData = NGReturn::where('deleted_at', null)
				->where('p_code',$pCode)
				->get()->toArray();
				if(count($ngReturnData) > 0){
					$return_qty = $ngReturnData[0]['qty'];
				}
				$ngArriveData = NGArrive::where('deleted_at', null)
				->where('p_code',$pCode)
				->get()->toArray();
				if(count($ngArriveData) > 0){
					$arrive_qty = $ngArriveData[0]['qty'];
				}

				$temp['ng_qty'] = $ng_qty;
				$temp['ng_return_qty'] = $return_qty ;
				$temp['ng_arrive_qty'] = $arrive_qty;
				array_push($resData, $temp);
			}
		}
		return $resData;
	}

	function approve($product_code,$ng_arrive_qty,$login_id){
		$res = NGArrive::whereNull('deleted_at')
			->where('p_code', $product_code)
			->forceDelete();

		$res1 = Warehouse::where('deleted_at', null)
			->where('p_code',$product_code)
			->get()->toArray();

		$results1 = Warehouse::where('deleted_at', null)
		->where('p_code',$product_code)
		->update([
				'ng' => $res1[0]['ng'] - $ng_arrive_qty,
				'remain' =>$res1[0]['remain'] + $ng_arrive_qty,
				'total_price' => ($res1[0]['remain'] + $ng_arrive_qty)  * $res1[0]['price'],
				"updated_id" => $login_id,
				"updated_at"     => now()
				]);
				
		return $results1;
	}
}
