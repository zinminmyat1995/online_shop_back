<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Interfaces\SaleRegistrationInterface;
use App\Classes\Repositories\SaleRegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleRegistrationController extends Controller
{
    private SaleRegistrationRepository $repository;
    public function __construct(SaleRegistrationInterface $repository)
    {
        $this->repository = $repository;
    }


	
    public function searchAllData(Request $request)
    {
        $product_name = $request->product_name;
        $login_id = $request->login_id;

        $res = $this->repository->search($product_name, $login_id);
        
        if (count($res) > 0) {
            return response()->json(['status' => 'OK', 'data' => $res], 200);
        }else {
            return response()->json(['status' => 'NG', 'message' => "Data is not found!"], 200);
        }

    }

    

    public function save(Request $request)
    {
        $data = $request->data;
		$actual_price = $request->actual_price;
        $delivery_service_amount = $request->delivery_service_amount;
        $discount_percent = $request->discount_percent;
        $total_price = $request->total_price;
        $name = $request->name;
        $address = $request->address;
        $phone_no = $request->phone_no;
        $payment = $request->payment;
        $login_id = $request->login_id;
      
        $res = $this->repository->save($data, $actual_price, $delivery_service_amount, $discount_percent , $total_price , $name , $address , $phone_no , $payment , $login_id );

        if ($res) {
            return response()->json(['status' => 'OK', 'message' => "Save Successfully!"], 200);
        }else{
            return response()->json(['status' => 'NG', 'message' => "Fail to save data!"], 200);
        }
       
    }

}
