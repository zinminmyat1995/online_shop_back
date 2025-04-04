<?php

namespace App\Classes\Repositories;

use App\Models\Warehouse;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\History;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SaleRegistrationInterface;

class SaleRegistrationRepository implements SaleRegistrationInterface
{
    function __construct()
    {
    }

	function search($product_name, $login_id){
		$temp = array();
        $resData = array();
		$res = array();
		if($product_name != ""){
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'product.p_code', '=', 'warehouse.p_code')
							->join('product_category', 'product_category.id', '=', 'product.p_category_id')
							->where('warehouse.remain', '!=', 0)
							->where('product.p_name', 'LIKE', "%{$product_name}%")
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in',
								'product_category.p_category_name'
								) 
							->get()
							->toArray();
		}else{
			$res = Warehouse::whereNull('warehouse.deleted_at')
							->join('product', 'product.p_code', '=', 'warehouse.p_code')
							->join('product_category', 'product_category.id', '=', 'product.p_category_id')
							->where('warehouse.remain', '!=', 0)
							->select(
								'warehouse.*',
								'product.p_name',
								'product.p_category_id',
								'product.p_gender',
								'product.p_made_in',
								'product_category.p_category_name'
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

	
	function save($data, $actual_price, $delivery_service_amount, $discount_percent , $total_price , $name , $address , $phone_no , $payment , $login_id ){
		$customer_data = [
			"name" => $name,
			"address" => $address,
			"phone_no" => $phone_no,
			"payment_id" => $payment,
			"actual_price" => $actual_price,
			"delivery_service_amount" => $delivery_service_amount,
			"discount_percent" => $discount_percent,
			"total_price" => $total_price,
			"created_id" => $login_id,
			"updated_id" => $login_id,
			"created_at" => Carbon::now('Asia/Phnom_Penh'),
			"updated_at" => Carbon::now('Asia/Phnom_Penh')
		];
		$customerResult = Customer::create($customer_data);
		$customerResultArray = $customerResult->toArray();
		$customer_id = $customerResultArray['id'];

		foreach ($data as $key => $value) {
			$code = $value['code'];		
			$wh = Warehouse::where('deleted_at', null)
			->where('p_code', $code)
			->get()->toArray();
			$warehouse_id = $wh[0]['id'];
			$warehouseResults = Warehouse::where('deleted_at', null)
			->where('p_code', $code)
			->update([
				'import_qty' => $wh[0]['import_qty'] - $value['count'],
				'remain' => $wh[0]['remain'] - $value['count'],
				'total_price' => ($wh[0]['remain'] - $value['count']) * $wh[0]['price'],
				"updated_id" => $login_id,
				"updated_at"     => Carbon::now()
			]);
		
			$sale_data = [
				"customer_id" => $customer_id,
				"warehouse_id" => $warehouse_id,
				"price" => $value['price'],
				"qty" =>$value['count'],
				"total_price" =>$value['total_price'],
				"created_id" => $login_id,
				"updated_id" => $login_id,
				"created_at" => Carbon::now('Asia/Phnom_Penh'),
				"updated_at" => Carbon::now('Asia/Phnom_Penh')
			];
			$saleResults = Sale::insert($sale_data);
		}
        return true;
	}
	

}
