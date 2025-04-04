<?php

namespace App\Classes\Repositories;

use App\Models\Discount;
use App\Models\Payment;
use App\Models\DeliveryService;
use App\Models\PrintService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SettingInterface;

class SettingRespository implements SettingInterface
{
    function __construct()
    {
    }

	function discountSave($discount,$discount_percent, $login_id){
		$res = Discount::get()->toArray();
		if(count($res) > 0 ){
			$results = Discount::where('deleted_at', null)
			->update([
				'discount_percent' => $discount == true ? $discount_percent : 0,
				"updated_id" => $login_id,
				"updated_at"     => now()
			]);	
		}else{
			$data = [
				"discount_percent" => $discount == true ? $discount_percent : 0,
				"created_id" => $login_id,
				"updated_id" => $login_id,
				"created_at" => Carbon::now(),
				"updated_at" => Carbon::now()
			];
		
				$discountInsert = Discount::insert($data);
		}
        
		return true;
	}

	function discountSearch($login_id){
		$res = Discount::get()->toArray();
		if(count($res) > 0 ){
			return $res;
		}else{
			return [];
		}
	}

	function paymentSave($payment_name, $login_id){
		$res = Payment::where('name', $payment_name)->get()->toArray();
		if(count($res) > 0 ){
			return false;
		}else{
				$data = [
					"name" => $payment_name,
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
		
				$PaymentInsert = Payment::insert($data);
				return true;
		}
	}

	function paymentDelete($payment_name, $login_id){
		$res = Payment::whereNull('deleted_at')
				->where('name', $payment_name)
				->forceDelete();
				return true;
	}
	

	function paymentSearch($login_id){
		$res = Payment::orderBy('id')->get()->toArray();
		if(count($res) > 0 ){
			return $res;
		}else{
			return [];
		}
	}

	function deliverySearch($login_id){
		$res = DeliveryService::get()->toArray();
		if(count($res) > 0 ){
			return $res;
		}else{
			return [];
		}
	}

	function deliverySave($delivery_service, $login_id){
		$res = DeliveryService::get()->toArray();
		if(count($res) > 0 ){
			$results = DeliveryService::where('deleted_at', null)
			->update([
				'delivery_service_flag' => $delivery_service == true ? 1 : 0 ,
				"updated_id" => $login_id,
				"updated_at"     => now()
			]);	
			return true;
		}else{
				$data = [
					"delivery_service_flag" => $delivery_service == true ? 1 : 0 ,
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
		
				$deliveryInsert = DeliveryService::insert($data);
				return true;
		}
	}

	function printSearch($login_id){
		$res = PrintService::get()->toArray();
		if(count($res) > 0 ){
			return $res;
		}else{
			return [];
		}
	}

	function printSave($print_service, $login_id){
		$res = PrintService::get()->toArray();
		if(count($res) > 0 ){
			$results = PrintService::where('deleted_at', null)
			->update([
				'print_service_flag' => $print_service == true ? 1 : 0 ,
				"updated_id" => $login_id,
				"updated_at"     => now()
			]);	
			return true;
		}else{
				$data = [
					"print_service_flag" => $print_service == true ? 1 : 0 ,
					"created_id" => $login_id,
					"updated_id" => $login_id,
					"created_at" => Carbon::now(),
					"updated_at" => Carbon::now()
				];
		
				$deliveryInsert = PrintService::insert($data);
				return true;
		}
	}
	
	function getAllService($login_id){
		$res1 = Discount::get()->toArray();
		$res2 = DeliveryService::get()->toArray();
		$res3 = Payment::orderBy('id')->get()->toArray();
		$res4 = PrintService::get()->toArray();

		$data = [
			"discount_percent" => count($res1) > 0? $res1[0]['discount_percent'] : 0,
			"delivery_service" => count($res2) > 0? $res2[0]['delivery_service_flag'] : 0,
			"payment" => count($res3) > 0? $res3 : [],
			"print_service" => count($res4) > 0? $res4[0]['print_service_flag'] : 0
		];
		
		return $data;
	}
}
