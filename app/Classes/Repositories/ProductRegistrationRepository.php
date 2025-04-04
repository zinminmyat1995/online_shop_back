<?php

namespace App\Classes\Repositories;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ProductRegistrationInterface;

class ProductRegistrationRepository implements ProductRegistrationInterface
{
    function __construct()
    {
    }

	function addProductCategory($name,$login_id){

		$temp = ProductCategory::where('deleted_at', null)
           ->where('p_category_name',$name)
           ->get()->toArray();

		if(count($temp) > 0){
			return false;
		}else{
			$data = [
				"p_category_name" => $name,
				"created_id" => $login_id,
				"updated_id" => $login_id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
			];
	
			$result = ProductCategory::insert($data);
			
			return true;
		}
		
		
	}

	function removeProductCategory($login_id,$id ){
        $results = ProductCategory::where('id', $id)
        ->update([
			'updated_id' => $login_id,
            'deleted_at'  => now()
        ]);
        return $results;
    }

	function productCategorySearch($login_id){
		$temp = array();
        $resData = array();
        $res = ProductCategory::where('deleted_at', null)
        ->get()->toArray();

        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $temp['id'] = $value['id'];
				$temp['name'] = $value['p_category_name'];
                $temp['count'] = $value['p_category_count'] +1;
                array_push($resData, $temp);
            }

            return $resData;
        } else {
            return [];
        }
	}

	function save($product_code, $product_name, $product_category,$gender,$made_in,$login_id){

		$data = [
			"p_code" => $product_code,
			"p_name" => $product_name,
			"p_category_id" => $product_category,
			"p_gender" => $gender,
			"p_made_in" => $made_in,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now(),
			"updated_at" => Carbon::now()
		];
        $result = Product::insert($data);
        
		$resProduct = ProductCategory::where('deleted_at', null)->where('id',$product_category)->get()->toArray();
		$resultsProduct = ProductCategory::where('deleted_at', null)
        ->where('id',$product_category)
        ->update([
            'p_category_count' => $resProduct[0]['p_category_count'] + 1,
			"updated_id" => $login_id,
            "updated_at"     => now()
        ]);

        return true;
	}
	

}
