<?php

namespace App\Classes\Repositories;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ProductListInterface;

class ProductListRepository implements ProductListInterface
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
			$res = Product::where('deleted_at', null)
			->where('p_code', 'LIKE', "%{$product_code}%")
			->where('p_name', 'LIKE', "%{$product_name}%")
			->where('p_made_in', 'LIKE', "%{$made_in}%")
			->get()->toArray();
		}else if($product_category != ""  && $gender == "" ){
			$res = Product::where('deleted_at', null)
			->where('p_code', 'LIKE', "%{$product_code}%")
			->where('p_name', 'LIKE', "%{$product_name}%")
			->where('p_category_id',$product_category)
			->where('p_made_in', 'LIKE', "%{$made_in}%")
			->get()->toArray();
		}else if($product_category == ""  && $gender != "" ){
			$res = Product::where('deleted_at', null)
			->where('p_code', 'LIKE', "%{$product_code}%")
			->where('p_name', 'LIKE', "%{$product_name}%")
			->where('p_gender',$gender)
			->where('p_made_in', 'LIKE', "%{$made_in}%")
			->get()->toArray();
		}else{
			$res = Product::where('deleted_at', null)
			->where('p_code', 'LIKE', "%{$product_code}%")
			->where('p_name', 'LIKE', "%{$product_name}%")
			->where('p_category_id',$product_category)
			->where('p_gender',$gender)
			->where('p_made_in', 'LIKE', "%{$made_in}%")
			->get()->toArray();
		}

        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $temp['id'] = $key + 1;
				$temp['code'] = $value['p_code'];
                $temp['name'] = $value['p_name'];
                $temp['category'] = $value['p_category_id'];
                $temp['gender_stauts'] = $value['p_gender'];
                $temp['made_in'] = $value['p_made_in'];
				$temp['edit_flag'] = false;
                array_push($resData, $temp);
            }

            return $resData;
        } else {
            return [];
        }
    }
	function update($product_code, $product_name, $product_category, $gender,$made_in,$login_id){
		$results = Product::where('deleted_at', null)
        ->where('p_code',$product_code)
        ->update([
            'p_name' => $product_name,
            'p_category_id'  => $product_category,
			'p_gender' => $gender,
			'p_made_in' => $made_in,
			"updated_id" => $login_id,
            "updated_at"     => now()
        ]);

        return $results;
	}

	function delete($login_id,$product_code ){
        $results = Product::where('p_code', $product_code)
        ->update([
			'updated_id' => $login_id,
            'deleted_at'  => now()
        ]);
        return $results;
    }

}
